<?php

namespace App\Livewire\Project;

use App\Models\Project;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use App\Models\PaymentItem;

// Importar el servicio de IA (lo crearemos en el siguiente paso)
use App\Services\Ai\AiWriterService; 

class SetupProject extends Component
{
    use WithFileUploads;

    public Project $project;
    public $Idproyect;
    public $step = 1;
    public $document_type;

    // Propiedad para el archivo
    public $document;
    public $resumen_ia = '';
    public $pasos_detectados = [];

    // Etapa 1: Datos Generales
    public $area, $objeto, $problema, $solucion, $lugar, $tiempo;
    public $projects = [];
    public $selected_project_id = null;

    // Etapa 2: Propuestas de Título
    public $titleOptions = [];
    public $title = '';
    public $selectedTitle = '';
    public $loading = false;

    // Etapa 3: Propuestas de Título
    public $formatSteps = [];

    public $selectedFormatKey = '';

    /**
     * Definición de Reglas de Validación
     */
    protected function rules()
    {
        return [
            'area' => 'required|string|min:5|max:255',
            'objeto' => 'required|string|min:5|max:255',
            'problema' => 'required|string|min:10',
            'solucion' => 'required|string|min:10',
            'lugar' => 'required|string|min:3',
            'tiempo' => 'required|string|min:4',
        ];
    }

    /**
     * Nombres personalizados para los errores (UX Profesional)
     */
    protected $validationAttributes = [
        'area' => 'área de estudio',
        'objeto' => 'objeto de estudio',
        'problema' => 'identificación del problema',
        'solucion' => 'alternativa de solución',
        'lugar' => 'lugar de ejecución',
        'tiempo' => 'periodo de tiempo',
        'title' => 'título',
    ];

    protected function messages()
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'min'      => 'El campo :attribute debe tener al menos :min caracteres.',
            'selectedTitle.required' => 'Por favor, selecciona una propuesta de título para continuar.',
        ];
    }

    public function mount(Project $project)
    {
        // 1. Cargamos todos los proyectos del usuario para el selector
        $this->projects = Project::where('user_id', auth()->id())
                        ->where('id', '!=', $project->id) // Evitar que se seleccione a sí mismo
                        ->get();



        $this->Idproyect = $project->id;
        $this->project = $project;
        $this->step = $project->setup_step ?? 1;
    
        // Recuperar el título ya guardado para que no aparezca vacío al volver
        $this->title = $project->title ?? '';
        // Intentar marcar la opción seleccionada si coincide con el título
        $this->selectedTitle = $project->title ?? '';
    
        $this->selectedFormatKey = $project->document_type ?? 'PROYECTO_DE_TESIS';
    
        if ($project->general_data) {
            $gd = $project->general_data;
            $this->area = $gd['area'] ?? '';
            $this->objeto = $gd['objeto'] ?? '';
            $this->problema = $gd['problema'] ?? '';
            $this->solucion = $gd['solucion'] ?? '';
            $this->lugar = $gd['lugar'] ?? '';
            $this->tiempo = $gd['tiempo'] ?? '';
            $this->titleOptions = $gd['suggested_titles'] ?? [];
        }

        // 2. NUEVO: Cargamos los resultados del análisis previo

 
        if ($project->ia_analysis) {
            $ar = $project->ia_analysis;
    
            // Importante: Usar las llaves exactas de tu JSON
            // Tu JSON usa 'pasos' para el array y 'resumen_ejecutivo' para el texto largo
            $this->pasos_detectados = $ar['pasos'] ?? [];
            $this->resumen_ia = $ar['resumen_ejecutivo'] ?? '';
        }
    
        $this->loadFormatStructure();

    }

    /**
     * Listener que se activa cuando seleccionas un proyecto del dropdown
     */
    public function updatedSelectedProjectId($value)
    {
        if (empty($value)) {
            $this->reset(['area', 'objeto', 'problema', 'solucion', 'lugar', 'tiempo']);
            return;
        }
    
        $sourceProject = Project::find($value);
    
        if ($sourceProject && $sourceProject->general_data) {
            $gd = $sourceProject->general_data;
            $this->area = $gd['area'] ?? '';
            $this->objeto = $gd['objeto'] ?? '';
            $this->problema = $gd['problema'] ?? '';
            $this->solucion = $gd['solucion'] ?? '';
            $this->lugar = $gd['lugar'] ?? '';
            $this->tiempo = $gd['tiempo'] ?? '';
            
            // Feedback visual opcional
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Datos importados correctamente']);
        }
    }


   public function updatedDocument()
   {


       $this->validate([
           'document' => 'mimes:pdf,doc,docx|max:10240',
       ]);

       $this->loading = true;
       try {

           $path = $this->document->getRealPath();
           $aiService = app(AiWriterService::class);
           
           $extractedData = $aiService->analyzeThesisFile($path);
   
           // 1. Mapeo a propiedades locales (para ver en el formulario)
           $dg = $extractedData['datos_generales'] ?? [];
           $this->area     = $dg['area'] ?? $this->area;
           $this->objeto   = $dg['objeto'] ?? $this->objeto;
           $this->problema = $dg['problema'] ?? $this->problema;
           $this->solucion = $dg['solucion'] ?? $this->solucion;
           $this->lugar    = $dg['lugar'] ?? $this->lugar;
           $this->tiempo   = $dg['tiempo'] ?? $this->tiempo;
   
           $this->pasos_detectados = $extractedData['resumen_pasos'] ?? [];
           $this->resumen_ia       = $extractedData['resumen_ejecutivo'] ?? '';
   
           // 2. GUARDADO AUTOMÁTICO EN BD (Persistencia)
           $this->project->update([
               'general_data' => [
                   'area' => $this->area,
                   'objeto' => $this->objeto,
                   'problema' => $this->problema,
                   'solucion' => $this->solucion,
                   'lugar' => $this->lugar,
                   'tiempo' => $this->tiempo,
               ],
               // Guardamos el análisis de los 10 pasos aquí
               'ia_analysis' => [
                   'pasos' => $this->pasos_detectados,
                   'resumen_detallado' => $extractedData['analisis_detallado'] ?? [],
                   'resumen_ejecutivo' => $this->resumen_ia,
               ],
               'has_been_analyzed' => true // Bandera para saber que ya pasó por la IA
           ]);
   
           $this->dispatch('swal', [
               'icon'  => 'success',
               'title' => 'Análisis Completo',
               'text'  => 'Se han identificado las secciones del Proyecto de Tesis.',
           ]);
   
       } catch (\Exception $e) {
           \Log::error("Error IA: " . $e->getMessage());
           $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => 'Fallo en el análisis']);
       }finally {
        // --- ESTO ES LO QUE FALTA ---
        $this->loading = false; // Apaga el loader de Livewire
        $this->dispatch('close-local-loader'); // Apaga el loader de Alpine
       }
   
       
   }

    /**
     * Extrae los pasos del JSON de la universidad del proyecto
     */
     public function loadFormatStructure()
     {
         $university = $this->project->university;

       //  dd($university->reglas_json);
         if ($university && $university->reglas_json) {
             $reglas = $university->reglas_json;
             $keyBuscada = str_replace(' ', '_', strtoupper($this->selectedFormatKey));
     
             if (isset($reglas['formatos'][$keyBuscada])) {
                 // Cargamos los pasos en el array para que Livewire los vincule
                 $this->formatSteps = $reglas['formatos'][$keyBuscada]['pasos'];

             }
         }
     }

    /**
     * Permite avanzar en las etapas
     */
    public function nextStep($step)
    {
        $this->step = $step;

        // Si avanzamos al paso 3 (Estructura), preparamos la configuración
        if($step == 3) {
            // Aseguramos que el proyecto tenga los datos necesarios guardados
            $this->project->refresh(); 
            
            // Aquí podrías cargar la configuración del JSON que definimos antes
            // para que el paso 3 sepa qué esquema mostrar.
            $this->document_type = $this->project->document_type;
        }

        // Este es el bloque que ya tenías y que funciona para Proyecto
        $this->project->update([

            'setup_step' => $step
        ]);
    }
     
    public function saveStep1()
    {
        $this->validate();
    
        switch($this->selectedFormatKey)
        {
            case "INFORME DE TESIS":
                // Aquí guardas lo que sea específico para el Informe Final
                $this->project->update([
                    'setup_step' => 2,
                    // Si el informe no usa general_data o usa uno distinto, lo defines aquí
                    // 'general_data' => [ ... datos de informe ... ]
                ]);

            break;
    
            case "PROYECTO DE TESIS":
                // Este es el bloque que ya tenías y que funciona para Proyecto
                $this->project->update([
                    'general_data' => [
                        'area' => $this->area,
                        'objeto' => $this->objeto,
                        'problema' => $this->problema,
                        'solucion' => $this->solucion,
                        'lugar' => $this->lugar,
                        'tiempo' => $this->tiempo,
                        'suggested_titles' => $this->titleOptions ?? [],
                    ],
                    'setup_step' => 2
                ]);
                break;
        }
    
        $this->step = 2;
    }

    public function saveStep2()
     {
         $this->validate([
             'title' => 'required|min:10' 
         ], [
             'title.required' => 'Debes seleccionar o escribir un título para tu investigación.',
             'title.min' => 'El título es demasiado corto, debe tener al menos :min caracteres.', // <--- Agrega esto
         ]);
     
        
         // IMPORTANTE: Recuperamos el JSON actual para no perder los títulos sugeridos
         $currentData = $this->project->general_data ?? [];
     
         $this->project->update([
             'title' => trim($this->title), // Guardamos el título oficial
             'general_data' => array_merge($currentData, [
                 // Mantenemos lo anterior y actualizamos lo nuevo
                 'area' => $this->area,
                 'objeto' => $this->objeto,
                 'problema' => $this->problema,
                 'solucion' => $this->solucion,
                 'lugar' => $this->lugar,
                 'tiempo' => $this->tiempo,
                 'titulo_final' => trim($this->title), 
             ]),
             'setup_step' => 3
         ]);
     
         $this->step = 3;
        
    }
     
     
     public function generateTitles(\App\Services\Ai\AiWriterService $aiService)
     {
         $this->loading = true;
     
         // 1. Recuperar datos si las propiedades están vacías
         if (empty($this->area) && $this->project->general_data) {
             $gd = $this->project->general_data;
             $this->area = $gd['area'] ?? '';
             $this->objeto = $gd['objeto'] ?? '';
             $this->problema = $gd['problema'] ?? '';
             $this->solucion = $gd['solucion'] ?? '';
             $this->lugar = $gd['lugar'] ?? '';
             $this->tiempo = $gd['tiempo'] ?? '';
         }
     
         // 2. Llamar a la IA (Gemini)
         $titles = $aiService->suggestTitles([
             'area'     => $this->area,
             'objeto'   => $this->objeto,
             'problema' => $this->problema,
             'solucion' => $this->solucion,
             'lugar'    => $this->lugar,
             'tiempo'   => $this->tiempo,
         ]);
     
         // 3. ACTUALIZACIÓN: Guardar los títulos dentro del JSON existente
         $currentData = $this->project->general_data ?? [];
         
         // Agregamos o actualizamos la llave de los títulos sugeridos
         $currentData['suggested_titles'] = $titles;
     
         $this->project->update([
             'general_data' => $currentData
         ]);
     
         // 4. Sincronizar la vista
         $this->titleOptions = $titles;
         $this->loading = false;
     }

         /**
     * Muestra la confirmación de la finalización 
     */
    public function confirmFinishSetup()
    {
        
        $this->dispatch('swal', [
            'title'              => '¿Estás seguro que deseas finalizar?',
            'text'               => 'No se podrá volver a los pasos anteriores una vez aceptado.',
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Sí, Finalizar',
            'confirmButtonColor' => '#465fff',
            'onConfirm'          => 'confirmFinishSetup', // JS disparará este evento si se confirma
        ]);

    }
 
    
   #[On('confirmFinishSetup')] 
   public function finishSetup()
   {
           
        switch($this->selectedFormatKey)
        {
            case "INFORME DE TESIS":
                // 1. Verificación: si ya se completó el setup (paso 3), solo redirigimos
                if ($this->project->setup_step >= 4) {
                    return redirect()->route('projects.show', $this->project->uuid);
                }

               // 3. Actualizamos el proyecto
                $this->project->update([
                    'setup_step' => 3
                ]);

            break;
    
            case "PROYECTO DE TESIS":
                
                // 1. Verificación: si ya se completó el setup (paso 5), solo redirigimos
                if ($this->project->setup_step >= 5) {
                    return redirect()->route('projects.show', $this->project->uuid);
                }

               // 3. Actualizamos el proyecto
                $this->project->update([
                    'setup_step' => 5
                ]);


            break;
        }

       // 4. CAMBIO CLAVE: Usamos steps() en lugar de chapters()
       // Si no tiene pasos creados, los generamos
       if ($this->project->steps()->count() === 0) {
           $this->project->generateStructure(null, $this->formatSteps);
       }
   
       return redirect()->route('projects.show', $this->project->uuid);
   }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.project.setup-project');
    }
}