<?php

namespace App\Livewire\Classroom;

use App\Models\Classroom;
use App\Models\Project;
use Livewire\Component;
use Illuminate\Support\Str;

class ShowClassroom extends Component
{
    public $classroom;

    public function mount(Classroom $classroom)
    {
        $this->classroom = $classroom;

        // 1. Validar que el alumno pertenece al aula
        if (!$classroom->students()->where('user_id', auth()->id())->exists()) {
            abort(403, 'No estás inscrito en esta aula.');
        }

        // 2. AUTO-CREACIÓN: ¿Ya tiene un proyecto en esta aula?
        $project = Project::where('classroom_id', $classroom->id)
                          ->where('user_id', auth()->id())
                          ->first();

        if (!$project) {
            // Si es su primera vez entrando, le creamos su espacio de tesis automáticamente
            Project::create([
                'uuid' => (string) Str::uuid(),
                'user_id' => auth()->id(),
                'classroom_id' => $classroom->id,
                'university_id' => $classroom->university_id,
                'title' => 'Tesis: ' . $classroom->name . ' - ' . auth()->user()->name,
                'status' => 'in_progress',
                'ai_word_limit' => 10000, // O el límite que definas para Trujillo
                'document_type' => 'proyecto_tesis', // O el valor que manejes por defecto
            ]);
            
            // Refrescamos para que el resto de la vista ya vea el proyecto
            $this->dispatch('swal', ['title' => '¡Espacio de Tesis Creado!', 'icon' => 'success']);
        }
    }

    public function render()
    {
        // Cargamos los pasos personalizados del asesor
        $steps = $this->classroom->classroomsteps()->orderBy('order')->get();
        // Buscamos el proyecto (que ahora sí o sí existe por el mount)
        $project = Project::where('classroom_id', $this->classroom->id)
                          ->where('user_id', auth()->id())
                          ->first();

        return view('livewire.classroom.show-classroom', [
            'steps' => $steps,
            'project' => $project
        ]);
    }
}