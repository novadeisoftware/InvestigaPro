@props([
    'type' => 'button',
    'variant' => 'white', // white, primary, danger
    'icon' => null,
])

@php
    $baseClasses = "shadow-theme-xs flex w-full items-center justify-center gap-2 rounded-full border px-4 py-3 text-sm font-medium transition-all lg:inline-flex lg:w-auto active:scale-95 disabled:opacity-50";
    
    $variants = [
        'white' => "border-gray-300 bg-white text-gray-700 hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200",
        'primary' => "border-blue-600 bg-blue-600 text-white hover:bg-blue-700 shadow-blue-500/20",
        'danger' => "border-red-500 bg-red-500 text-white hover:bg-red-600",
    ];

    $class = $baseClasses . " " . ($variants[$variant] ?? $variants['white']);
@endphp

<button {{ $attributes->merge(['type' => $type, 'class' => $class]) }}>
    {{-- Spinner de carga automático para Livewire --}}
    @if($attributes->has('wire:click'))
        <span wire:loading wire:target="{{ $attributes->get('wire:click') }}" 
            class="w-4 h-4 border-2 border-current border-t-transparent rounded-full animate-spin">
        </span>
    @endif

    {{-- Icono dinámico --}}
    @if($icon)
        <div wire:loading.remove {{ $attributes->has('wire:click') ? 'wire:target=' . $attributes->get('wire:click') : '' }}>
             {!! $icon !!}
        </div>
    @endif

    <span>{{ $slot }}</span>
</button>