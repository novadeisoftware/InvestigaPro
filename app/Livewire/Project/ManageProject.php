<?php

namespace App\Livewire\Project;

use App\Models\Project;
use App\Models\University;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class ManageProject extends Component
{
    use WithPagination;

    // Propiedades del formulario
    public $project_id, $title, $university_id, $document_type = '', $academic_status = 'draft',$faculty, $school, $academic_line;
    public $search = '';

    protected $queryString = ['search'];

    /**
     * Reglas de validación en español para el contexto local.
     */
    protected function rules()
    {
        return [
            'title' => 'required|string|min:3|max:255',
            'university_id' => 'required|exists:universities,id',
            'document_type' => 'required',
            'faculty' => 'nullable|string|min:3|max:255',
            'school' => 'nullable|string|min:3|max:255',
            'academic_line' => 'nullable|string|min:3|max:255',
        ];
    }

    
    protected $validationAttributes = [
        'title' => 'Identificador de Proyecto',
        'university_id' => 'Universidad',
        'document_type' => 'Tipo de Documento',
        'faculty' => 'Facultad',
        'school' => 'Escuela',
        'academic_line' => 'Linea de Investigación',
    ];
    
    protected $messages = [
         'title.required' => 'Identificador de Proyecto es obligatorio.',
         'title.min' => 'El título es muy corto, pon algo más descriptivo',
         'university_id.required' => 'Debes seleccionar una universidad',
         'document_type.required' => 'Debes seleccionar un tipo de docuemento',
         'faculty.required' => 'Facultad es obligatorio',
         'school.required' => 'Escuela es obligatorio',
         'academic_line.required' => 'Linea de Investigación es obligatorio',
     ];

    public function render()
    {
        return view('livewire.project.manage-project', [
            'projects' => Project::where('title', 'like', '%' . $this->search . '%')
                ->where('user_id', auth()->id()) // Solo ver proyectos propios
                ->with('university')
                ->orderBy('id', 'desc')
                ->paginate(9), // 9 para un grid de 3x3 elegante
            'universities' => University::all()
        ]);
    }

    /* |--------------------------------------------------------------------------
    | Gestión de Modal (Crear / Editar)
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $this->resetFields();
        $this->dispatch('loading-finished');
    }

    public function edit($id)
    {
        // Buscamos el proyecto en la DB
        $project = Project::findOrFail($id);
    
        $this->project_id = $project->id;
        $this->title = $project->title;
        $this->university_id = $project->university_id;
        $this->document_type = $project->document_type;
    
        // Si usas los campos condicionales de antes, asegúrate de asignar estos también
        $this->faculty = $project->faculty;
        $this->school = $project->school;
        $this->academic_line = $project->academic_line;
    
        $this->dispatch('loading-finished');
    }

    /* |--------------------------------------------------------------------------
    | Operaciones CRUD (Store y Delete)
    |--------------------------------------------------------------------------
    */

   public function store()
    {
        $this->validate();
    
        $isNewProject = empty($this->project_id);
    
        // 1. Buscamos si el usuario tiene un aula activa (tabla pivote classroom_user)
        // El alumno de Trujillo solo puede estar en un aula activa a la vez para su tesis
        $activeClassroom = auth()->user()->classrooms()
            ->wherePivot('status', 'active')
            ->first();

        $data = [
            'user_id' => auth()->id(),
            'title' => $this->title,
            'university_id' => $this->university_id,
            'document_type' => $this->document_type,
            'academic_status' => $this->academic_status,
            'faculty' => $this->faculty,
            'school' => $this->school,
            'academic_line' => $this->academic_line,
            // 2. Si hay aula, la vinculamos al proyecto automáticamente
            'classroom_id' => $activeClassroom ? $activeClassroom->id : null,
        ];
    
        $project = Project::updateOrCreate(['id' => $this->project_id], $data);
    
        if ($isNewProject) {
            // 3. Pasamos el aula al generador de estructura
            // Si $activeClassroom es null, el método usará el JSON de la universidad por defecto
            $project->generateStructure($activeClassroom);
        }
    
        $this->dispatch('close-modal');
        
        $this->dispatch('swal', [
            'title' => $this->university_id ? 'Actualizado' : 'Registrado',
            'icon' => 'success',
            'type' => 'toast'
        ]);
        
        $this->resetFields();
    }

    /**
     * Confirmación de eliminación con SweetAlert2
     */
    public function confirmDelete($id)
    {
       $this->dispatch('swal', [
            'title'              => '¿Eliminar la investigación?',
            'text'               => 'Esta acción borrará todos los datos asociados.',
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Sí, borrar',
            'confirmButtonColor' => '#dc2626',
            'onConfirm'          => 'deleteProject', // JS disparará este evento si se confirma
            'id'                 => $id
        ]);
    }

    #[On('deleteProject')]
    public function delete($id)
    {
        $project = Project::where('id', $id)->where('user_id', auth()->id())->first();
        if ($project) {
            $this->dispatch('swal', ['title' => 'Eliminado', 'icon' => 'success', 'type' => 'toast']);
            $project->delete();
          
        }
    }

    private function resetFields() {
        $this->reset(['project_id', 'title', 'university_id', 'document_type']);
    }
}