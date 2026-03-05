@props([
    'fullPage' => false,
    'text' => null
])

<div 
    x-show="loading" 
    x-transition.opacity.duration.300ms
    {{ $attributes->merge([
        'class' => ($fullPage ? 'fixed' : 'absolute') . ' inset-0 z-[999] flex flex-col items-center justify-center bg-white/80 dark:bg-gray-900/90 backdrop-blur-sm rounded-3xl'
    ]) }}
>
    <div class="relative">
        {{-- Tu spinner original con tus clases de marca --}}
        <div class="h-16 w-16 animate-spin rounded-full border-4 border-solid border-brand-500 border-t-transparent"></div>
        
        {{-- Opcional: un aro de fondo más tenue para que se vea más pro --}}
        <div class="absolute inset-0 h-16 w-16 rounded-full border-4 border-solid border-brand-500/10"></div>
    </div>

    @if($text)
        <span class="mt-4 text-xs font-bold text-brand-500 tracking-widest uppercase animate-pulse">
            {{ $text }}
        </span>
    @endif
</div>