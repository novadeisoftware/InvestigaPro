@props(['disabled' => false, 'error' => false, 'label' => null])

@php
    // CLASES BASE: Altura h-11, sombra suave y bordes redondeados LG
    $baseClasses = "h-11 w-full rounded-lg border bg-white px-4 py-2.5 text-sm transition-all focus:ring-3 focus:outline-hidden shadow-theme-xs";

    // COLORES SEGÚN ESTADO: Normal vs Error
    $stateClasses = $error 
        ? "border-red-500 text-gray-800 placeholder:text-gray-400 focus:border-red-500 focus:ring-red-500/10 dark:border-red-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" 
        : "border-gray-300 text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800";

    // ESTADO DESHABILITADO: Colores opacos y sin interacción
    $disabledClasses = "disabled:border-gray-100 disabled:bg-gray-50 disabled:placeholder:text-gray-300 dark:disabled:border-gray-800 dark:disabled:bg-white/[0.03] dark:disabled:placeholder:text-white/15 disabled:cursor-not-allowed";

    // CLASES DEL LABEL: Cambia a rojo si hay error o a gris tenue si está disabled
    $labelColor = $error ? 'text-red-500' : ($disabled ? 'text-gray-300 dark:text-white/15' : 'text-gray-700 dark:text-gray-400');
@endphp

<div class="w-full">
    @if($label)
        <label class="mb-1.5 block text-sm font-medium {{ $labelColor }}">
            {{ $label }}
        </label>
    @endif

    <input 
        {{ $disabled ? 'disabled' : '' }} 
        {!! $attributes->merge(['class' => "$baseClasses $stateClasses $disabledClasses"]) !!}
    >

    @if($error && $attributes->has('for'))
        <div class="mt-1">
            <x-input-error :for="$attributes->get('for')" />
        </div>
    @endif
</div>