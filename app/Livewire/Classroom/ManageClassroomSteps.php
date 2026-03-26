<?php

namespace App\Livewire\Classroom;

use App\Models\Classroom;
use App\Models\ClassroomStep;
use Livewire\Component;
use Livewire\Attributes\On;

class ManageClassroomSteps extends Component
{
    public $classroom;
    public $editingStepId;
    
    // Propiedades para la UI y edición
    public $custom_name, $additional_instructions, $availability_mode, $available_at;

    // 1. Agrega esta propiedad para guardar las fechas temporalmente
    public $stepDates = [];

    public function render()
    {
        return view('livewire.classroom.manage-classroom-steps', [
            'steps' => $this->classroom 
                ? ClassroomStep::where('classroom_id', $this->classroom->id)->orderBy('order')->get() 
                : []
        ]);
    }

    

    /* |--------------------------------------------------------------------------
    | Carga el step_strategy de classroom
    |--------------------------------------------------------------------------
    */


    #[On('open-steps-config')]
    public function loadSteps($classroomId)
    {
        $this->classroom = Classroom::find($classroomId);
        $this->availability_mode = $this->classroom->step_strategy ?? 'locked';
        

        // 2. Cargamos las fechas actuales de los pasos en el array
        foreach ($this->classroom->classroomSteps as $step) {
            $this->stepDates[$step->id] = $step->available_at 
                ? $step->available_at->format('Y-m-d\TH:i') 
                : null;
        }
    
        $this->dispatch('open-modal', 'modal-steps-list');
        $this->dispatch('loading-finished');
    }

    /* |--------------------------------------------------------------------------
    | Guarda la step_strategy escogida por el usuario
    |--------------------------------------------------------------------------
    */

    public function applyStrategy()
    {
        if (!$this->classroom) return;
    
        $this->classroom->update(['step_strategy' => $this->availability_mode]);
    
        $steps = ClassroomStep::where('classroom_id', $this->classroom->id)->get();
    
        foreach ($steps as $step) {
            if ($this->availability_mode === 'open') {
                $step->update(['availability_mode' => 'open', 'available_at' => null]);
            } 
            elseif ($this->availability_mode === 'locked') {
                $newMode = ($step->availability_mode === 'open' || $step->available_at) ? 'open' : 'locked';
                $step->update(['availability_mode' => $newMode, 'available_at' => null]);
            }
            elseif ($this->availability_mode === 'scheduled') {
                // 3. RECUPERAR LA FECHA DEL ARRAY stepDates
                $date = $this->stepDates[$step->id] ?? null;
                
                $step->update([
                    'availability_mode' => 'scheduled',
                    'available_at' => $date // Laravel lo casteará a datetime automáticamente
                ]);
            }
        }
    
        $this->dispatch('swal', ['title' => 'Estrategia Sincronizada', 'icon' => 'success', 'type' => 'toast']);
        $this->dispatch('close-modal');
    }
   /* |--------------------------------------------------------------------------
    | Toggle para cambiar animacion de open con locked
    |--------------------------------------------------------------------------
    */

    public function toggleStepStatus($stepId)
    {
        $step = ClassroomStep::find($stepId);
        $step->update([
            'availability_mode' => $step->availability_mode === 'open' ? 'locked' : 'open'
        ]);
    }


   /* |--------------------------------------------------------------------------
    | Update para cambiar nombre
    |--------------------------------------------------------------------------
    */

    public function updateStepName($stepId, $newName)
    {
        ClassroomStep::find($stepId)->update([
            'custom_name' => $newName
        ]);
    
        // No lanzamos swal para que sea una edición "silenciosa" y fluida
    }




}