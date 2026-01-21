<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
// Importamos las clases de Jetstream para que el Provider las reconozca
use Laravel\Jetstream\Http\Livewire\LogoutOtherBrowserSessionsForm;
use Laravel\Jetstream\Http\Livewire\DeleteUserForm;
use Laravel\Jetstream\Http\Livewire\UpdateProfileInformationForm;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // 1. Información de Perfil
        Livewire::component('profile.update-profile-information-form', UpdateProfileInformationForm::class);
        
        // 2. Contraseña (Tu clase personalizada)
        Livewire::component('profile.update-password-form', \App\Livewire\Profile\UpdatePasswordForm::class);

        // 3. Sesiones de Navegador
        Livewire::component('profile.update-logout-other-browser-sessions-form', LogoutOtherBrowserSessionsForm::class);

        // 4. Eliminar Usuario (ESTA ES LA QUE FALTA)
        Livewire::component('profile.delete-user-form', DeleteUserForm::class);
    }
}