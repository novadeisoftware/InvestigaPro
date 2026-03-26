<?php

namespace App\Livewire\Project;

use App\Models\Project;
use App\Models\ProjectStep;
use Livewire\Component;

// Importar el servicio de IA (lo crearemos en el siguiente paso)
use App\Services\Ai\AiWriterService; 

class EditorProject extends Component
{
    public Project $project;
    public $currentStepId;
    public $previewStepId;
    public $content;
    public $content_json = [];
    public $lastSaved;
    public $isGenerating = false; // Nuevo: para deshabilitar el botón y mostrar un loader

    public $viewMode = 'editor'; // Puede ser 'editor' o 'preview'

    // Escuchar eventos de la IA (cuando MSHO genere texto)
    protected $listeners = ['aiContentGenerated' => 'updateContentFromAi'];

    public function mount(Project $project)
    {
        $this->project = $project->load('steps');
        
        if ($this->project->steps->count() > 0) {
            $firstStep = $this->project->steps->first();
            $this->currentStepId = $firstStep->id;
            $this->previewStepId = $firstStep->id;
            
            // Cargamos el contenido inicial
            $this->content = $firstStep->content ?? '';
            
            // TIP: Para la primera carga, el entangle es suficiente, 
            // pero disparamos el evento por si el componente ya estaba montado.
            $this->dispatch('tinymce-load-content', content: $this->content);
        }
    }
    
    public function render()
    {
        return view('livewire.project.editor-project', [
            'currentStep' => ProjectStep::find($this->currentStepId)
        ])->layout('layouts.app'); 
    }

    /* |--------------------------------------------------------------------------
    | UPDATES
    |--------------------------------------------------------------------------
    */


    /**
     * LÓGICA DE SELECCIÓN Y CARGA
     */
 /**
     * LÓGICA DE SELECCIÓN Y CARGA ACTUALIZADA
     */
    public function selectStep($stepId)
    {
        $this->viewMode = 'editor';
        
        // 1. Guardamos el progreso del paso actual antes de cambiar
        if ($this->currentStepId) {
            $this->saveProgress();
        }

        // 2. Limpieza preventiva para forzar la reactividad en el frontend
        $this->content = ''; 

        $step = ProjectStep::find($stepId);
        
        // 3. Actualizamos el ID (esto disparará el initTiny en la vista)
        $this->currentStepId = $step->id;
        $stepTitle = strtolower($step->title);
        
        if ($this->isPlanningStep($step->title)) {
            $decoded = json_decode($step->content, true);
            
            if (str_contains($stepTitle, 'cronograma')) {
                $this->content_json = $decoded ?: [
                    ['actividad' => 'Revisión Bibliográfica', 'meses' => array_fill(0, 12, false)],
                    ['actividad' => 'Presentación de proyecto', 'meses' => array_fill(0, 12, false)],
                    ['actividad' => 'Ejecución del proyecto', 'meses' => array_fill(0, 12, false)],
                    ['actividad' => 'Manejo de Datos', 'meses' => array_fill(0, 12, false)],
                    ['actividad' => 'Redacción de Informe Final', 'meses' => array_fill(0, 12, false)],
                    ['actividad' => 'Aprobación y sustentación', 'meses' => array_fill(0, 12, false)],
                ];
            } else {
                $this->content_json = [
                    'moneda' => $decoded['moneda'] ?? 'S/',
                    'items' => $decoded['items'] ?? [
                        ['item' => 'Materiales de escritorio', 'cant' => 1, 'precio' => 0],
                        ['item' => 'Movilidad y transporte', 'cant' => 1, 'precio' => 0],
                        ['item' => 'Otros', 'cant' => 1, 'precio' => 0],
                    ]
                ];
            }
        } else {
            // 4. CARGA DE CONTENIDO PARA TINYMCE
            $this->content = $step->content ?? '';
            $this->content_json = [];
            // EL TRUCO: Notificamos al navegador que el contenido está listo
            $this->dispatch('tinymce-load-content', content: $this->content);
        }
    
        $this->lastSaved = $step->updated_at ? $step->updated_at->format('h:i A') : null;
    }

    // Modifica o crea este método para la navegación de vista previa
    public function selectPreviewStep($stepId)
    {
        $this->previewStepId = $stepId;
        // Aquí no guardamos progreso porque es solo lectura/vista previa
    }



    /**
     * GUARDADO AUTOMÁTICO PARA TABLAS (content_json)
     * Este método se dispara gracias a @entangle('content_json').live
     */
    public function updatedContentJson()
    {
      
        $this->saveProgress();
    }
    
    /**
     * GUARDADO AUTOMÁTICO PARA TEXTO (content)
     */
    public function updatedContent()
    {
     
        $this->saveProgress();
    }

    public function setViewMode($mode)
    {
    if ($mode === 'preview') {
        $this->saveProgress(); // Guardamos todo antes de mostrar la previa
    }
    $this->viewMode = $mode;
    }
    

    /* |--------------------------------------------------------------------------
    | Operaciones CRUD (Store y Delete)
    |--------------------------------------------------------------------------
    */


    public function saveProgress()
    {
        if (!$this->currentStepId) return;

        $step = ProjectStep::find($this->currentStepId);
        
        $isPlanning = $this->isPlanningStep($step->title);
        
        // Si es planificación usamos tu lógica de JSON (Cronograma/Presupuesto)
        // Si no, guardamos el HTML puro de TinyMCE que ya incluye las tablas e imágenes
        $finalContent = $isPlanning ? json_encode($this->content_json) : $this->content;

        $step->update([
            'content' => $finalContent,
            'status' => empty($finalContent) ? 'pending' : 'in_progress'
        ]);
        
        $this->lastSaved = now()->format('h:i A');
    }


    /* |--------------------------------------------------------------------------
    | Generacion de texto por IA
    |--------------------------------------------------------------------------
    */


    // Nuevo método para interactuar con la IA
    public function generateDraft(AiWriterService $aiService)
    {
        // 1. Verificación de cuota (Igual)
        if (!$this->project->hasAvailableQuota()) {
            $this->dispatch('swal', [
                'title' => 'Cuota agotada', 
                'text' => 'Has llegado al límite de palabras de tu plan.', 
                'icon' => 'warning'
            ]);
            return;
        }
    
        $currentStep = ProjectStep::find($this->currentStepId);
    
        // OBTENER HISTORIAL (Igual)
        $history = $this->project->steps()
            ->where('order', '<', $currentStep->order)
            ->whereNotNull('content')
            ->get()
            ->map(fn($step) => "[{$step->title}]: " . strip_tags($step->content))
            ->implode("\n\n");
    
        try {
            // 2. Llamada al servicio
            $generatedText = $aiService->generateDraft([
                'project_title'     => $this->project->title,
                'step_title'        => $currentStep->title,
                'university_siglas' => $this->project->university->siglas ?? 'UPAO',
                'instructions'      => $currentStep->structured_data['instrucciones'] ?? '',
                'general_data'      => $this->project->general_data,
                'existing_content'  => $this->content,
                'history_context'   => $history,
                'step_key'          => $currentStep->internal_type ?? $currentStep->title,
            ]);
    
            // --- VALIDACIÓN CRÍTICA: ¿Es un error de Google? ---
            if (str_contains($generatedText, 'ERROR_AI_SATURATED') || str_contains($generatedText, 'high demand')) {
                $this->dispatch('draft-generated'); // Apaga el loader sólido
                return $this->dispatch('swal', [
                    'type'     => 'toast',
                    'title'    => 'IA Saturada. Reintenta en 10 segundos.',
                    'icon'     => 'info',
                    'position' => 'bottom-end',
                    'timer'    => 5000
                ]);
            }
    
            $generatedText = trim($generatedText);
            
            // 3. Conteo de palabras (Solo si no hubo error)
            $wordCount = str_word_count(strip_tags($generatedText));
    
            // 4. Actualización de cuota (Solo si hubo texto real)
            $this->project->increment('ai_words_used', $wordCount);
            
            $this->project->usageLogs()->create([
                'palabras_generadas' => $wordCount,
                'step_id'            => $currentStep->id,
                'accion'             => 'generacion_borrador',
                'paso_titulo'        => $currentStep->title,
            ]);
    
            // 5. Inyectar contenido
            $this->content = empty($this->content) 
                ? $generatedText 
                : $this->content . "<br><br>" . $generatedText;
    
            $currentStep->update(['content' => $this->content]);
            
            $this->dispatch('tinymce-load-content', content: $this->content);
    
            // 6. Feedback
            $this->lastSaved = now()->format('H:i');
            $this->dispatch('swal', [
                'type'     => 'toast',
                'title'    => 'Se generó el borrador con éxito',
                'icon'     => 'success',
                'position' => 'bottom-end'
            ]);
    
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'title' => 'Error de conexión',
                'text'  => 'No pudimos conectar con el servidor de IA. Intenta de nuevo.',
                'icon'  => 'error'
            ]);
        } finally {
            $this->dispatch('draft-generated'); // Cerramos el loader pase lo que pase
        }
    }

    /**
     * PARAFRASEAR TEXTO SELECCIONADO
     */
    public function paraphraseSelection($selectedText, AiWriterService $aiService)
    {
    // 1. VALIDACIÓN INICIAL: ¿El usuario seleccionó algo?
    if (empty(trim($selectedText))) {
        return $this->dispatch('swal', [
            'title'    => 'Selección vacía',
            'text'     => 'Por favor, selecciona con el mouse el texto que deseas parafrasear dentro del editor.',
            'icon'     => 'info',
            'confirmButtonColor' => '#2563eb', // El color de tu marca Nova Dei
        ]);
    }
    
        // 1. Activamos loaders (el del botón y el global de JirehLux)
        $this->loading = true; 
    
        try {
            $paraphrased = $aiService->paraphraseText($selectedText, [
                'project_title' => $this->project->title,
                'general_data' => $this->project->general_data,
                'university_siglas' => $this->project->university->siglas ?? 'UPAO'
            ]);
    
            // --- VALIDACIÓN ANTI-SATURACIÓN ---
            if (str_contains($paraphrased, 'ERROR_AI_SATURATED') || str_contains($paraphrased, 'high demand')) {
                $this->dispatch('draft-generated'); // Apaga el loader sólido
                return $this->dispatch('swal', [
                    'type'     => 'toast',
                    'title'    => 'IA ocupada. Reintenta en unos segundos.',
                    'icon'     => 'info',
                    'position' => 'bottom-end'
                ]);
            }
    
            // --- OPCIONAL: LIMPIEZA DE ETIQUETAS ---
            // Si solo quieres que se reemplace el texto con la paráfrasis 
            // y NO quieres que aparezca el título "PARÁFRASIS ACADÉMICA" dentro del editor:
            if (str_contains($paraphrased, 'PARÁFRASIS ACADÉMICA:')) {
                $parts = explode('PARÁFRASIS ACADÉMICA:', $paraphrased);
                $paraphrased = trim($parts[1]);
            }
    
            // 2. Reemplazamos en TinyMCE
            $this->dispatch('tinymce-replace-selection', content: trim($paraphrased));
    
            $this->dispatch('swal', [
                'type'     => 'toast',
                'title'    => 'Texto mejorado con éxito',
                'icon'     => 'success',
                'position' => 'bottom-end'
            ]);
            
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'title' => 'Error',
                'text'  => 'No se pudo parafrasear el texto.',
                'icon'  => 'error'
            ]);
        } finally {
            $this->loading = false;
            $this->dispatch('draft-generated'); // Apaga el loader sólido pase lo que pase
        }
    }

    // Helper para identificar el paso por título
    private function isPlanningStep($title)
    {
        $titleLower = strtolower($title);
        return str_contains($titleLower, 'cronograma') || str_contains($titleLower, 'presupuesto');
    }
}