<?php
namespace App\Livewire\Classroom;

use App\Models\Classroom;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class JoinClassroom extends Component
{
    public function mount($code)
    {
        // 1. Si no está logueado, lo mandamos al login pero avisándole a dónde volver
        if (!Auth::check()) {
            return redirect()->guest(route('login'));
        }

        // 2. Buscamos el aula
        $classroom = Classroom::where('invitation_code', $code)->first();

        if (!$classroom) {
            abort(404, 'El código de invitación no es válido.');
        }

        // 3. Verificamos vinculación
        $isMember = $classroom->students()->where('user_id', Auth::id())->exists();

        if (!$isMember) {
            // Usamos syncWithoutDetaching para evitar el error de duplicados si recarga la página
            $classroom->students()->syncWithoutDetaching([Auth::id()]);

            session()->flash('swal', [
                'title' => '¡Excelente!',
                'text' => "Te has unido correctamente al aula: {$classroom->name}",
                'icon' => 'success'
            ]);
        }

        // 4. Redirigimos al Dashboard
        return redirect()->route('dashboard');
    }

    public function render()
    {
        return '<div></div>';
    }
}