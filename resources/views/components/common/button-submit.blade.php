@props([
    'target' => 'store', 
    'variant' => 'brand', 
    'type' => 'submit',
    'showSpinner' => true // Nueva prop para controlar el spinner
])

@php
    $baseClasses = "flex w-full justify-center items-center gap-2 rounded-lg px-6 py-2.5 text-sm font-medium transition-all sm:w-auto active:scale-95 disabled:opacity-70 disabled:cursor-not-allowed shadow-md";
    
    $variants = [
        'brand' => "bg-brand-500 text-white hover:bg-brand-600 shadow-brand-500/20",
        'white' => "border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400",
        'danger' => "bg-red-600 text-white hover:bg-red-700 shadow-red-500/20",
    ];

    $class = $baseClasses . " " . ($variants[$variant] ?? $variants['brand']);
@endphp

<button {{ $attributes->merge(['type' => $type, 'class' => $class]) }} 
    @if($showSpinner) wire:loading.attr="disabled" wire:target="{{ $target }}" @endif>
    
    {{-- SPINNER: Solo si showSpinner es true --}}
    @if($showSpinner)
        <span wire:loading wire:target="{{ $target }}" 
            class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin">
        </span>
    @endif

    {{-- ICONO: Se oculta al cargar SOLO si el spinner está activado --}}
    @if (isset($icon))
        <span @if($showSpinner) wire:loading.remove wire:target="{{ $target }}" @endif class="flex items-center">
            {{ $icon }}
        </span>
    @endif

    {{-- TEXTO: Manejo inteligente del estado de carga --}}
    <span @if($showSpinner) wire:loading.remove wire:target="{{ $target }}" @endif>
        {{ $slot }}
    </span>

    {{-- Texto auxiliar de carga: Solo si el spinner está activo --}}
    @if($showSpinner)
        <span wire:loading wire:target="{{ $target }}">
          
        </span>
    @endif
</button>