<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
// Importamos las clases de Jetstream para que el Provider las reconozca
use Laravel\Jetstream\Http\Livewire\LogoutOtherBrowserSessionsForm;
use Laravel\Jetstream\Http\Livewire\DeleteUserForm;
use Laravel\Jetstream\Http\Livewire\UpdateProfileInformationForm;
use App\Http\Controllers\SidebarController;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Event; // Importante
use Illuminate\Auth\Events\Login; // El evento
use App\Listeners\UpdateLastLoginAt; // Tu listener
use App\Models\University;
use App\Observers\UniversityObserver;


class AppServiceProvider extends ServiceProvider
{
    /**
     * El método boot se ejecuta después de que todos los servicios han sido registrados.
     * Se utiliza para compartir datos entre vistas o registrar componentes de Livewire.
     */
    public function boot(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Registro de Componentes de Perfil (Jetstream/Livewire)
        |--------------------------------------------------------------------------
        | Forzamos el registro de los componentes de Livewire que utiliza Jetstream 
        | para asegurar que Laravel encuentre las clases correctamente, especialmente 
        | si se han personalizado o movido de su ubicación original.
        */
        
        // Maneja la actualización de nombre y correo del usuario
        Livewire::component('profile.update-profile-information-form', UpdateProfileInformationForm::class);
        
        // Maneja el cambio de contraseña (apunta a tu clase personalizada en App\Livewire)
        Livewire::component('profile.update-password-form', \App\Livewire\Profile\UpdatePasswordForm::class);

        // Permite al usuario cerrar sesiones en otros dispositivos/navegadores
        Livewire::component('profile.update-logout-other-browser-sessions-form', LogoutOtherBrowserSessionsForm::class);

        // Maneja la lógica de eliminación permanente de la cuenta de usuario
        Livewire::component('profile.delete-user-form', DeleteUserForm::class);


        // Maneja la Paginación de la tablas
        \Illuminate\Pagination\Paginator::useTailwind();

        // Registro del Observer cada universidad tiene una configuración automatica por defecto.
        University::observe(UniversityObserver::class);


        // Registramos listener par saber su utlima conexion del usuario
        Event::listen(
            Login::class,
            UpdateLastLoginAt::class
        );
        

        /*
        |--------------------------------------------------------------------------
        | View Composer para el Sidebar (Menú Dinámico)
        |--------------------------------------------------------------------------
        | Esta función inyecta automáticamente la variable $menuGroups cada vez que 
        | se renderiza el sidebar. Esto evita tener que pasar los datos del menú 
        | manualmente desde cada controlador de la aplicación.
        |
        | Se aplica tanto si usas @include('layouts.sidebar') como el componente <x-sidebar />.
        */
        
        View::composer(['layouts.sidebar', 'components.sidebar'], function ($view) {
            // Instanciamos el controlador que contiene la lógica y estructura del menú
            $controller = new SidebarController();
            
            // Enviamos el array de menús (Dashboard, IA Assistant, etc.) a la vista
            // La vista recibirá estos datos bajo la variable $menuGroups
            $view->with('menuGroups', $controller->getMenuDataRaw());
        });
    }
}