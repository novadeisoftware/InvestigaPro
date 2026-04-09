@props(['disabled' => false, 'error' => false, 'label' => null])

@php
    // CLASES BASE: Altura h-11, sin flecha nativa, sombra suave y bordes LG
    $baseClasses = "h-11 w-full appearance-none rounded-lg border bg-transparent bg-none px-4 py-2.5 pr-11 text-sm transition-all focus:ring-3 focus:outline-hidden shadow-theme-xs cursor-pointer";

    // ESTADO: NORMAL vs ERROR
    $stateClasses = $error 
        ? "border-red-500 text-gray-800 focus:border-red-500 focus:ring-red-500/10 dark:border-red-700 dark:bg-gray-900 dark:text-white/90" 
        : "border-gray-300 text-gray-800 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:focus:border-brand-800";

    // ESTADO: DESHABILITADO
    $disabledClasses = "disabled:border-gray-100 disabled:bg-gray-50 disabled:placeholder:text-gray-300 dark:disabled:border-gray-800 dark:disabled:bg-white/[0.03] dark:disabled:placeholder:text-white/15 disabled:cursor-not-allowed";

    // CLASES DEL LABEL
    $labelColor = $error ? 'text-red-500' : ($disabled ? 'text-gray-300 dark:text-white/15' : 'text-gray-700 dark:text-gray-400');
@endphp

<div class="w-full">
    @if($label)
        <label class="mb-1.5 block text-sm font-medium {{ $labelColor }}">
            {{ $label }}
        </label>
    @endif

    <div x-data="{ isOptionSelected: @entangle($attributes->wire('model')).live ? true : false }" class="relative z-20 bg-transparent">
        
        <select
            {{ $disabled ? 'disabled' : '' }}
            @change="isOptionSelected = true"
            {!! $attributes->merge(['class' => "$baseClasses $stateClasses $disabledClasses"]) !!}
            :class="isOptionSelected ? 'text-gray-800 dark:text-white/90' : 'text-gray-400 dark:text-gray-500'"
        >
            {{ $slot }}
        </select>

        {{-- Icono de Flecha de la Plantilla --}}
        <span class="pointer-events-none absolute top-1/2 right-4 z-30 -translate-y-1/2 {{ $disabled ? 'text-gray-300 dark:text-white/15' : 'text-gray-700 dark:text-gray-400' }}">
            <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </span>
    </div>

    @if($error && $attributes->has('for'))
        <div class="mt-1">
            <x-input-error :for="$attributes->get('for')" />
        </div>
    @endif
</div>