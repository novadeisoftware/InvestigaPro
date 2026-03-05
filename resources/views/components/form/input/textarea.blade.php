@props([
    'disabled' => false, 
    'error' => false, 
    'label' => null,
    'rows' => 3
])

@php
    // Clases base (Comunes a todos los estados)
    $baseClasses = "w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm transition-all focus:ring-3 focus:outline-hidden shadow-theme-xs dark:bg-gray-900";

    // Clases según el ESTADO (Normal vs Error)
    $stateClasses = $error 
        ? "border-error-300 text-gray-800 placeholder:text-gray-400 focus:border-error-300 focus:ring-error-500/10 dark:border-error-700 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-error-800" 
        : "border-gray-300 text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800";

    // Clases para DESHABILITADO
    $disabledClasses = "disabled:border-gray-100 disabled:bg-gray-50 disabled:placeholder:text-gray-300 dark:disabled:border-gray-800 dark:disabled:bg-white/[0.03] dark:disabled:placeholder:text-white/15 focus:ring-0";

    // Clases para el LABEL según el estado
    $labelClasses = $disabled 
        ? "text-gray-300 dark:text-white/15" 
        : "text-gray-700 dark:text-gray-400";
@endphp

<div>
    @if($label)
        <label class="mb-1.5 block text-sm font-medium {{ $labelClasses }}">
            {{ $label }}
        </label>
    @endif

    <textarea 
        {{ $disabled ? 'disabled' : '' }} 
        rows="{{ $rows }}"
        {!! $attributes->merge(['class' => "$baseClasses $stateClasses $disabledClasses"]) !!}
    >{{ $slot }}</textarea>

    @if($error && $attributes->has('for'))
        <p class="text-theme-xs text-error-500 mt-1">
            {{ $errors->first($attributes->get('for')) }}
        </p>
    @elseif($error)
         <p class="text-theme-xs text-error-500 mt-1">
            Este campo es requerido.
        </p>
    @endif
</div>