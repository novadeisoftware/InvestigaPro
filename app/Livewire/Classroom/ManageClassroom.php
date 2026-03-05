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
    public $classroom_id, $name, $university_id, $status = 'active';
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
            'university_id' => 'required|exists:universities,id',
            'status' => 'required|in:active,inactive,archived',
        ];
    }

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
        $this->classroom_id = $classroom->id;
        $this->name = $classroom->name;
        $this->university_id = $classroom->university_id;
        $this->status = $classroom->status;
        $this->dispatch('open-modal', 'classroom-modal');
        $this->dispatch('loading-finished');
    }

    // Añade este método a tu clase ManageClassroom
public function enterEditor($classroomId)
{
    $classroom = Classroom::find($classroomId);
    
    $project = Project::firstOrCreate(
        ['classroom_id' => $classroomId, 'user_id' => auth()->id()],
        [
            'uuid' => (string) Str::uuid(),
            'university_id' => $classroom->university_id,
            'title' => 'Tesis: ' . $classroom->name,
            'document_type' => 'proyecto_tesis',
        ]
    );

    // SI EL PROYECTO ES NUEVO, LE COPIAMOS LOS PASOS DEL AULA
    if ($project->steps()->count() === 0) {
        foreach ($classroom->classroomSteps as $cStep) {
            ProjectStep::create([
                'project_id' => $project->id,
                'classroom_step_id' => $cStep->id, // <--- Importante añadir esta FK
                'order' => $cStep->order,
                'title' => $cStep->custom_name ?? $cStep->step_key,
                'content' => '',
                'status' => 'pending'
            ]);
        }
    }

    return redirect()->route('classroom.editor', ['project' => $project->uuid]);
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
        
        // Por defecto usamos el formato de proyecto de tesis
        $pasos = $rules['formatos']['proyecto_tesis']['pasos'] ?? [];

        foreach ($pasos as $paso) {
            // Suponiendo que ya creaste el modelo ClassroomStep
            ClassroomStep::create([
                'classroom_id' => $classroom->id,
                'step_key' => $paso['key'] ?? Str::slug($paso['titulo']),
                'order' => $paso['orden'],
                'custom_name' => $paso['titulo'], // El asesor podrá cambiarlo después
                'availability_mode' => 'open', // Por defecto todos abiertos
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