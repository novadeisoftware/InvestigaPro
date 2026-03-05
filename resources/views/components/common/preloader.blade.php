{{-- x-common.preloader --}}
<div
    x-show="loaded"
    x-init="setTimeout(() => loaded = false, 400)" {{-- Alpine ya está listo aquí, solo esperamos un poco --}}
    class="fixed left-0 top-0 z-[999999] flex h-screen w-screen items-center justify-center bg-white dark:bg-black transition-opacity duration-300"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
>
    <div
        class="h-16 w-16 animate-spin rounded-full border-4 border-solid border-brand-500 border-t-transparent"
    ></div>
</div>