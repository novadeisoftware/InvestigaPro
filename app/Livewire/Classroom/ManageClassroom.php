<?php

namespace App\Livewire\Classroom;

use App\Models\Classroom;
use App\Models\University;
use App\Models\Project;
use App\Models\ProjectStep;
use App\Models\ClassroomStep;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Livewire\Attributes\On;

class ManageClassroom extends Component
{
    use WithPagination;

    // Propiedades para el formulario y búsqueda
    public $classroom_id, $name, $university_id, $status = 'active',$document_type;
    public $search = '';

    // propiedad para tabs
    public $tab = 'my_classrooms'; // Pestaña por defecto

    protected $queryString = ['search'];

    /**
     * Reglas de validación.
     */
    protected function rules()
    {
        return [
            'name' => 'required|string|min:5|max:100',
            'document_type' => 'required',
            'university_id' => 'required|exists:universities,id',
            'status' => 'required|in:active,inactive,archived',
        ];
    }

    protected $validationAttributes = [
        'name' => 'Nombre',
        'document_type' => 'Tipo de Documento',
        'university_id' => 'Universidad',
        'status' => 'Estado',
    ];
    
    protected $messages = [
         'name.required' => 'Nombre es obligatorio.',
         'status.min' => 'Debes seleccionar un estado',
         'university_id.required' => 'Debes seleccionar una universidad',
         'document_type.required' => 'Debes seleccionar un tipo de documento',
    
     ];

    public function mount()
    {
        // Si viene el parámetro en la URL, cambiamos el tab
        if (request()->has('tab')) {
            $this->tab = request('tab');
        }
    }

    public function render()
    {
        // Aulas que YO asesoro (Mis Alumnos)
        $myClassrooms = Classroom::where('advisor_id', auth()->id())
            ->where('name', 'like', '%' . $this->search . '%')
            ->with('university')
            ->latest()
            ->paginate(6, ['*'], 'myPage');
    
        // Aulas a las que ME UNÍ (Mis Profesores/Asesores)
        $joinedClassrooms = auth()->user()->classrooms()
            ->where('name', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(6, ['*'], 'joinedPage');
    
        return view('livewire.classroom.manage-classroom', [
            'classrooms' => $myClassrooms,
            'joinedClassrooms' => $joinedClassrooms,
            'universities' => University::all()
        ]);
    }

    /**
     * Prepara el modal para crear una nueva aula.
     */
    public function create()
    {
        $this->resetFields();
        $this->dispatch('open-modal', 'classroom-modal');
        $this->dispatch('loading-finished');
    }

    /**
     * Carga datos para editar un aula existente.
     */
    public function edit(Classroom $classroom)
    {
        $this->resetFields();
        $this->classroom_id = $classroom->id;
        $this->name = $classroom->name;
        $this->university_id = $classroom->university_id;
        $this->status = $classroom->status;
        $this->dispatch('open-modal', 'classroom-modal');
        $this->dispatch('loading-finished');
    }

    // Añade este método a tu clase ManageClassroom
   public function enterSetup($classroomId)
   {
       // Buscamos el aula
       $classroom = Classroom::with('classroomSteps')->findOrFail($classroomId);
   
       // Buscamos o creamos el proyecto
       $project = Project::firstOrCreate(
           ['classroom_id' => $classroomId, 'user_id' => auth()->id()],
           [
               'uuid'          => (string) \Illuminate\Support\Str::uuid(),
               'university_id' => $classroom->university_id,
               'title'         => 'Tesis: ' . $classroom->name,
               'document_type' => $classroom->document_type,
               'setup_step'    => 1, // Inicia en el paso 1 por defecto
           ]
       );
   
       // Si es nuevo, clonamos los pasos del aula
       if ($project->steps()->count() === 0) {
           foreach ($classroom->classroomSteps as $cStep) {
               \App\Models\ProjectStep::create([
                   'project_id'        => $project->id,
                   'classroom_step_id' => $cStep->id,
                   'order'             => $cStep->order,
                   'title'             => $cStep->custom_name ?? $cStep->step_key,
                   'content'           => '',
                   'status'            => 'pending'
               ]);
           }
       }
   
       /** * Lógica de Redirección Dinámica:
        * Si setup_step es menor a 3, significa que la configuración está pendiente.
        */
       if ($project->setup_step < 4) {
           return redirect()->route('projects.setup', ['project' => $project->uuid]);
       }
   
       // Si ya completó el setup (paso 3), va al editor normal
       return redirect()->route('projects.show', ['project' => $project->uuid]);
   }


    /**
     * Guarda o actualiza el aula.
     */
    public function store()
    {
        $this->validate();
    
        $isNew = empty($this->classroom_id);
         $message  = $isNew ? 'Aula actualizada con éxito' : 'Aula registrado con éxito';
    
        // 1. Preparamos los datos básicos
        $data = [
            'name'          => $this->name,
            'university_id' => $this->university_id,
            'advisor_id'    => auth()->id(),
            'document_type' => $this->document_type,
            'status'        => $this->status,
        ];
    
        // 2. Solo añadimos el código si es un registro nuevo
        if ($isNew) {
            $data['invitation_code'] = strtoupper(Str::random(6));
        }
    
        $classroom = Classroom::updateOrCreate(
            ['id' => $this->classroom_id],
            $data
        );
    
        // 3. Si es aula nueva, creamos sus ClassroomSteps automáticamente
        if ($isNew) {
            $this->syncClassroomSteps($classroom);
        }
    
        $this->dispatch('close-modal', 'classroom-modal');
        
        $this->dispatch('swal', [
            'type'     => 'toast',
            'title'    => $message,
            'icon'     => 'success',
            'position' => 'bottom-end'
        ]);
    
        $this->resetFields();
    }
    /**
     * Sincroniza los pasos (steps) basados en el reglas_json de la universidad.
     */
    private function syncClassroomSteps(Classroom $classroom)
    {
        $rules = $classroom->university->reglas_json;
        
        /** * Mapeo exacto para tu nuevo JSON:
         * Convertimos el document_type del modelo a las llaves del JSON.
         */
        $map = [
            'thesis_project' => 'PROYECTO_DE_TESIS',
            'final_report'   => 'INFORME_FINAL'
        ];
    
        $jsonKey = $map[$classroom->document_type] ?? 'PROYECTO_DE_TESIS';
        
        // Accedemos a los pasos según tu estructura: formatos -> LLAVE -> pasos
        $pasos = $rules['formatos'][$jsonKey]['pasos'] ?? [];
    
        foreach ($pasos as $paso) {
            ClassroomStep::create([
                'classroom_id'      => $classroom->id,
                // Usamos la 'key' si existe, si no, generamos un slug del título
                'step_key'          => $paso['key'] ?? Str::slug($paso['titulo'], '_'),
                'order'             => $paso['orden'],
                'custom_name'       => $paso['titulo'], 
                'availability_mode' => 'open', 
                // Guardamos instrucciones y secciones si vienen en el JSON
                'additional_instructions' => $paso['instrucciones'] ?? null,
            ]);
        }
    }

    /**
     * Reset de variables.
     */
    public function resetFields()
    {
        $this->reset(['classroom_id', 'name', 'university_id', 'status']);
    }

    /**
     * Confirmación para eliminar (Soft Delete).
     */
    public function confirmDelete($id)
    {
        $this->dispatch('swal', [
            'title'              => '¿Eliminar esta aula?',
            'text'               => 'Esta acción borrará todos los datos asociados.',
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Sí, borrar',
            'confirmButtonColor' => '#dc2626',
            'onConfirm'          => 'deleteClassroom', // JS disparará este evento si se confirma
            'id'                 => $id
        ]);
    }

    #[On('deleteClassroom')]
    public function delete($id)
    {
        $classroom = Classroom::where('id', $id)->where('advisor_id', auth()->id())->first();
        if ($classroom) {
            $classroom->delete();
           $this->dispatch('swal', [
            'type'     => 'toast',
            'title'    => 'Aula eliminada correctamente',
            'icon'     => 'success',
            'position' => 'bottom-end'
           ]);
        }
    }
}