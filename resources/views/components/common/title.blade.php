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
</div>