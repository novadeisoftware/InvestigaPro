<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Class Namespace
    |--------------------------------------------------------------------------
    |
    | Esta opción determina el namespace predeterminado para las clases de
    | tus componentes. Esto ayuda a Livewire a encontrar tus archivos.
    |
    */

    'class_namespace' => 'App\\Livewire',

    /*
    |--------------------------------------------------------------------------
    | View Path
    |--------------------------------------------------------------------------
    |
    | Aquí se define la ruta donde se guardarán las vistas de Blade para 
    | tus componentes de Livewire.
    |
    */

    'view_path' => resource_path('views/livewire'),

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | El layout predeterminado que se usará al renderizar componentes como 
    | páginas completas (Full-page components).
    |
    */

    'layout' => 'components.layouts.app',

    /*
    |--------------------------------------------------------------------------
    | Temporary File Uploads
    |--------------------------------------------------------------------------
    |
    | Configuración para la subida de archivos (como los logos de universidades).
    |
    */

    'temporary_file_upload' => [
        'disk' => 'local',        // Disco para archivos temporales
        'rules' => 'max:12288',   // 12MB max por defecto
        'directory' => 'livewire-tmp',
        'middleware' => 'throttle:60,1',
    ],

    /*
    |--------------------------------------------------------------------------
    | Render On Hydrate
    |--------------------------------------------------------------------------
    |
    | Optimización de rendimiento: Renderiza solo cuando sea necesario.
    |
    */

    'render_on_hydrate' => false,

    /*
    |--------------------------------------------------------------------------
    | Inject Assets
    |--------------------------------------------------------------------------
    |
    | Livewire v3+ inyecta automáticamente scripts y estilos.
    |
    */

    'inject_assets' => true,

    /*
    |--------------------------------------------------------------------------
    | Navigate (SPA mode)
    |--------------------------------------------------------------------------
    |
    | Configuración para wire:navigate.
    |
    */

    'navigate' => [
        'show_progress_bar' => true,
        'progress_bar_color' => '#2299dd',
    ],

    'back_button_cache' => false,

    'render_on_redirect' => false,

];