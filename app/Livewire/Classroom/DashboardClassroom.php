<?php

namespace App\Livewire\Classroom;

use App\Models\Classroom;
use App\Models\Project;
use Livewire\Component;
use Livewire\WithPagination;

class DashboardClassroom extends Component
{
    use WithPagination;

    public Classroom $classroom;
    public $search = '';

    public function mount(Classroom $classroom)
    {
        // Seguridad: Solo el dueño del aula (el asesor) puede entrar aquí
        if ($classroom->advisor_id !== auth()->id()) {
            abort(403, 'No tienes permiso para gestionar este aula.');
        }
        
        $this->classroom = $classroom;
    }

    public function render()
    {
        $students = $this->classroom->users()
            ->where(function($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->with(['projects' => function($q) {
                // Filtramos por el aula actual y traemos los conteos
                $q->where('classroom_id', $this->classroom->id)
                  ->withCount('steps')
                  ->withCount(['steps as completed_steps' => function($sq) {
                      $sq->where('status', 'completed');
                  }]);
            }])
            ->paginate(10);

  
    
        return view('livewire.classroom.dashboard-classroom', [
            'students' => $students
        ])->layout('layouts.app');
    }

    /**
     * El asesor entra a revisar el avance del alumno
     */
    public function reviewProject($projectUuid)
    {
        return redirect()->route('classroom.editor', [
            'project' => $projectUuid,
            // Al no estar en la definición de la ruta, Laravel lo añade como ?mode=advisor
            'mode' => 'advisor' 
        ]);
    }
}