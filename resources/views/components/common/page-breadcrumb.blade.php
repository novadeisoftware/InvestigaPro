{{-- 
    @props: Define la variable de entrada. 
    En INVESTIGAPRO, 'pageTitle' se usa para dinamizar el título de la sección (ej. Usuarios, Tesis). 
--}}
@props(['pageTitle' => 'Page'])

<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    {{-- Título dinámico de la página actual --}}
    <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">
        {{ $pageTitle }}
    </h2>

    <nav>
        <ol class="flex items-center gap-1.5">
            <li>
                {{-- Link estático al Home --}}
                <a class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400 hover:text-blue-600 transition-colors"
                   href="{{ url('/') }}">
                    Home
                    {{-- Icono separador SVG --}}
                    <svg class="stroke-current" width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6.0765 12.667L10.2432 8.50033L6.0765 4.33366" 
                              stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
            </li>
            {{-- Texto indicador de la ubicación actual --}}
            <li class="text-sm text-gray-800 dark:text-white/90 font-medium">
                {{ $pageTitle }}
            </li>
        </ol>
    </nav>
</div>