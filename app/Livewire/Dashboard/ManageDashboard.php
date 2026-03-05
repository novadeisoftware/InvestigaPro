<?php
namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Classroom;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ManageDashboard extends Component
{
    public $classroom;
    public $showWelcome = false;

    public function mount()
    {
        // Buscamos la primera relación donde joined_at sea NULL
        $membership = Auth::user()->classrooms()
            ->wherePivot('joined_at', null)
            ->first();


        if ($membership) {
            $this->classroom = $membership;
            $this->showWelcome = true;
        } else {
            // Si ya todos tienen fecha, solo cargamos el aula para el banner normal
            $this->classroom = Auth::user()->classrooms()->first();
        }
    }

    public function completeWelcome()
    {
        if ($this->classroom) {
            // Actualizamos el campo joined_at en la tabla pivote
            Auth::user()->classrooms()->updateExistingPivot($this->classroom->id, [
                'joined_at' => Carbon::now()
            ]);
        }

        $this->showWelcome = false;
        
        // Redirigimos a la pestaña de aulas
        return redirect()->route('classroom', ['tab' => 'joined_classrooms']);
    }
    
    public function closeOnly()
    {
        if ($this->classroom) {
            Auth::user()->classrooms()->updateExistingPivot($this->classroom->id, [
                'joined_at' => Carbon::now()
            ]);
        }
        $this->showWelcome = false;
    }

    public function render()
    {
        return view('livewire.dashboard.manage-dashboard');
    }
}