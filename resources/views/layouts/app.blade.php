<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full overflow-hidden">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Dashboard' }} | InvestigaPro</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('theme', {
                theme: localStorage.getItem('theme') ||
                    (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'),

                init() {
                    this.updateTheme();
                },

                toggle() {
                    this.theme = this.theme === 'light' ? 'dark' : 'light';
                    localStorage.setItem('theme', this.theme);
                    this.updateTheme();
                },

                updateTheme() {
                    const html = document.documentElement;
                    if (this.theme === 'dark') {
                        html.classList.add('dark');
                        // Validación de seguridad para evitar el error 'classList of null'
                        if (document.body) document.body.classList.add('dark', 'bg-gray-900');
                    } else {
                        html.classList.remove('dark');
                        if (document.body) document.body.classList.remove('dark', 'bg-gray-900');
                    }
                }
            });

            Alpine.store('sidebar', {
                isExpanded: window.innerWidth >= 1280,
                isMobileOpen: false,
                isHovered: false,

                toggleExpanded() {
                    this.isExpanded = !this.isExpanded;
                    this.isMobileOpen = false;
                },
                toggleMobileOpen() {
                    this.isMobileOpen = !this.isMobileOpen;
                },
                setMobileOpen(val) {
                    this.isMobileOpen = val;
                },
                setHovered(val) {
                    if (window.innerWidth >= 1280 && !this.isExpanded) {
                        this.isHovered = val;
                    }
                }
            });
        });
    </script>

    <script>
        (function() {
            const theme = localStorage.getItem('theme') ||
                (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>
</head>

<body class="bg-white dark:bg-gray-900" x-data="{ 'loaded': true }" x-init="const checkMobile = () => {
    if (window.innerWidth < 1280) {
        $store.sidebar.setMobileOpen(false);
        $store.sidebar.isExpanded = false;
    } else {
        $store.sidebar.isMobileOpen = false;
        $store.sidebar.isExpanded = true;
    }
};
window.addEventListener('resize', checkMobile);
$store.theme.init();
{{-- Sincroniza el body al cargar --}}">

    <x-common.preloader />

    <div class="min-h-screen xl:flex">
        @include('layouts.backdrop')
        @include('layouts.sidebar')

        <div class="flex-1 transition-all duration-300 ease-in-out"
            :class="{
                'xl:ml-[290px]': $store.sidebar.isExpanded || $store.sidebar.isHovered,
                'xl:ml-[90px]': !$store.sidebar.isExpanded && !$store.sidebar.isHovered,
                'ml-0': $store.sidebar.isMobileOpen
            }">

            @include('layouts.app-header')

            <main class="mx-auto max-w-full">
                {{-- 
                    SOPORTE UNIVERSAL:
                    - @yield('content'): Para vistas de controlador (Calendario).
                    - {{ $slot ?? '' }}: Para componentes Livewire (ManageUser).
                --}}
                @yield('content')

                @isset($slot)
                    {{ $slot }}
                @endisset
            </main>
        </div>
    </div>

    <script src="{{ asset('vendor/sweetalert/sweetalert.all.js') }}"></script>

    @livewireScripts
    @stack('scripts')



</body>

</html>

<script>
    window.addEventListener('swal', function(e) {
        const data = e.detail[0];
        const isToast = data.type === 'toast';

        // Configuración de colores dinámica para INVESTIGAPRO
        const isDark = document.documentElement.classList.contains('dark');
        const bgColor = isDark ? '#1f2937' : '#ffffff';
        const textColor = isDark ? '#f3f4f6' : '#111827';

        if (isToast) {
            const Toast = Swal.mixin({
                toast: true,
                position: data.position || 'bottom-end',
                showConfirmButton: false,
                timer: data.timer || 3000,
                timerProgressBar: true,
                background: bgColor,
                color: textColor,
                didOpen: (toast) => {
                    toast.style.zIndex = '1000'; // Ajuste manual de z-index
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
            Toast.fire({
                icon: data.icon || 'success',
                title: data.title
            });
        } else {
            Swal.fire({
                title: data.title || '¿Estás seguro?',
                text: data.text || '',
                icon: data.icon || 'info',
                background: bgColor,
                color: textColor,
                showCancelButton: data.showCancelButton || false,
                confirmButtonColor: data.confirmButtonColor || '#3b82f6',
                cancelButtonColor: '#6b7280',
                confirmButtonText: data.confirmButtonText || 'Aceptar',
                cancelButtonText: data.cancelButtonText || 'Cancelar',
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed && data.onConfirm) {
                    window.Livewire.dispatch(data.onConfirm, {
                        id: data.id || null
                    });
                }
            });
        }
    });

    /* ===========================================================================
    EJEMPLOS DE USO DESDE EL BACKEND (Livewire PHP)
    ===========================================================================

    1. NOTIFICACIÓN RÁPIDA (TOAST):
    ---------------------------------------------------------------------------
    $this->dispatch('swal', [
        'type'     => 'toast',
        'title'    => '¡Cambios guardados!',
        'icon'     => 'success', // success, error, warning, info, question
        'position' => 'bottom-end',
        'timer'    => 4000
    ]);

    2. ALERTA DE ÉXITO ESTÁNDAR (MODAL):
    ---------------------------------------------------------------------------
    $this->dispatch('swal', [
        'title' => '¡Registro Exitoso!',
        'text'  => 'El usuario ha sido añadido al sistema correctamente.',
        'icon'  => 'success',
        'confirmButtonText' => 'Entendido'
    ]);

    3. ALERTA DE ERROR / VALIDACIÓN:
    ---------------------------------------------------------------------------
    $this->dispatch('swal', [
        'title' => '¡Error!',
        'text'  => 'No tienes permisos para realizar esta acción.',
        'icon'  => 'error',
        'confirmButtonColor' => '#ef4444' // Color rojo
    ]);

    4. CONFIRMACIÓN DE ELIMINACIÓN (INTERACTIVO):
    ---------------------------------------------------------------------------
    $this->dispatch('swal', [
        'title'              => '¿Eliminar Investigador?',
        'text'               => 'Esta acción borrará todos los datos asociados.',
        'icon'               => 'warning',
        'showCancelButton'   => true,
        'confirmButtonText'  => 'Sí, borrar todo',
        'confirmButtonColor' => '#dc2626',
        'onConfirm'          => 'deleteUser', // Nombre de la función en tu componente PHP
        'id'                 => $user->id    // ID que recibirá la función
    ]);
    ===========================================================================
    */
</script>

<style>
    /* Forzar que el contenedor de SweetAlert2 esté por encima de todo */
    .swal2-container {
        z-index: 99999 !important;
    }
</style>
