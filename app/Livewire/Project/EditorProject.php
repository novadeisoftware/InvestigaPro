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
            $firstId = $this->project->steps->first()->id;
            $this->currentStepId = $firstId;
            $this->previewStepId = $firstId; // Inicializamos ambos
            $this->selectStep($firstId);
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
    public function selectStep($stepId)
    {
        $this->viewMode = 'editor';
        
        if ($this->currentStepId) {
            $this->saveProgress();
        }
    
        $step = ProjectStep::find($stepId);
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
                /** * PRESUPUESTO: Forzamos la estructura de objeto con 'moneda' e 'items'
                 * Esto es vital para que Alpine encuentre data.items y no se borre nada
                 */
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
            $this->content = $step->content ?? '';
            $this->content_json = [];
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
        
        // Si el título indica que es planificación, guardamos el JSON, si no el texto
        $isPlanning = $this->isPlanningStep($step->title);
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
    // 1. Verificación de cuota (Negocio)
    if (!$this->project->hasAvailableQuota()) {
        $this->dispatch('swal', [
            'title' => 'Cuota agotada', 
            'text' => 'Has llegado al límite de palabras de tu plan.', 
            'icon' => 'warning'
        ]);
        return;
    }

    $this->loading = true; // El loader que configuramos en la barra lateral
    $currentStep = ProjectStep::find($this->currentStepId);

    // OBTENER HISTORIAL: Traemos el contenido de todos los pasos anteriores al actual
    $history = $this->project->steps()
        ->where('order', '<', $currentStep->order)
        ->whereNotNull('content')
        ->get()
        ->map(fn($step) => "[{$step->title}]: {$step->content}")
        ->implode("\n\n");



    try {
        // 2. Llamada al servicio con contexto completo
        $generatedText = $aiService->generateDraft([
            'project_title'     => $this->project->title,
            'step_title'        => $currentStep->title,
            'step_key'          => $currentStep->internal_type ?? $currentStep->title, 
            'university_siglas' => $this->project->university->siglas ?? 'InvestigaPro',
            'instructions'      => $currentStep->structured_data['instrucciones'] ?? '',
            'general_data'      => $this->project->general_data, // Datos técnicos del paso 1
            'existing_content'  => $this->content,
            'history_context' => $history, // Enviamos lo que ya se escribió
            'step_key'        => $currentStep->internal_type ?? $currentStep->title,
        ]);

        $generatedText = trim($generatedText);
        
        // 3. Conteo de palabras (Usa str_word_count para más precisión)
        $wordCount = str_word_count(strip_tags($generatedText));

        // 4. Actualización de cuota y registros (Atomicidad)
        $this->project->increment('ai_words_used', $wordCount);
        
        $this->project->usageLogs()->create([
            'palabras_generadas' => $wordCount,
            'step_id'            => $currentStep->id,
            'accion'             => 'generacion_borrador',
            'paso_titulo'        => $currentStep->title,
        ]);

        // 5. Inyectar contenido y persistir en DB
        $this->content = empty($this->content) 
            ? $generatedText 
            : $this->content . "\n\n" . $generatedText;

        $currentStep->update(['content' => $this->content]);
        
        // 6. Feedback visual
        $this->lastSaved = now()->format('H:i');
        $this->dispatch('notify', type: 'success', message: "Se han generado $wordCount palabras.");

    } catch (\Exception $e) {
        $this->dispatch('swal', [
            'title' => 'Error de IA',
            'text'  => $e->getMessage(),
            'icon'  => 'error'
        ]);
    } finally {
        $this->loading = false;
    }
}

    // Helper para identificar el paso por título
    private function isPlanningStep($title)
    {
        $titleLower = strtolower($title);
        return str_contains($titleLower, 'cronograma') || str_contains($titleLower, 'presupuesto');
    }
}