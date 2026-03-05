<?php

namespace App\Listeners;

// ESTE ES EL IMPORT QUE DEBES CORREGIR:
use Illuminate\Auth\Events\Login; 
use Carbon\Carbon;
use Illuminate\Support\Facades\Log; // Opcional, para depurar

class UpdateLastLoginAt
{
    /**
     * Maneja el evento de inicio de sesión.
     * * @param  \Illuminate\Auth\Events\Login  $event
     * @return void
     */
    public function handle(Login $event): void
    {
        // $event->user contiene la instancia del usuario que acaba de entrar
        $event->user->update([
            'last_login_at' => Carbon::now()
        ]);
    }
}