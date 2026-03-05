<div class="p-8" x-data="{ showWelcome: @entangle('classroom').live ? true : false }">
    @if ($classroom)
        {{-- Banner normal que siempre se ve --}}
        <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 rounded-[3rem] p-10 border border-gray-100 shadow-xl">
        </div>

        {{-- MODAL DE BIENVENIDA AUTOMÁTICO --}}
        @if ($showWelcome)
            <div x-show="showWelcome"
                class="fixed inset-0 z-[99999] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100">

                <div @click.away="showWelcome = false"
                    class="bg-white dark:bg-gray-900 w-full max-w-lg rounded-[3.5rem] p-10 shadow-2xl relative overflow-hidden text-center"
                    x-transition:enter="transition ease-out duration-500"
                    x-transition:enter-start="opacity-0 scale-90 translate-y-10"
                    x-transition:enter-end="opacity-100 scale-100 translate-y-0">

                    {{-- Decoración de fondo --}}
                    <div
                        class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-brand-400 via-brand-600 to-brand-800">
                    </div>

                    <div
                        class="w-20 h-20 bg-brand-50 dark:bg-brand-900/30 rounded-3xl flex items-center justify-center mx-auto mb-6">
                        <span class="text-4xl animate-bounce">🎉</span>
                    </div>

                    <h3
                        class="text-2xl font-black text-gray-900 dark:text-white uppercase italic mb-4 tracking-tighter">
                        ¡Acceso Confirmado!
                    </h3>

                    <p class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-8 leading-relaxed">
                        Ya eres parte del aula <span
                            class="text-brand-400 font-bold italic">{{ $classroom->name }}</span>.
                        Tu asesor <span
                            class="text-gray-900 dark:text-white font-bold">{{ $classroom->advisor->name }}</span> ya
                        habilitó los pasos para tu tesis.
                    </p>

                    <div class="flex flex-col gap-3">
                        {{-- Este botón actualiza joined_at y REDIRIGE --}}
                        <button wire:click="completeWelcome"
                            class="w-full py-4 bg-brand-600 text-white rounded-2xl font-black uppercase tracking-[0.2em] text-[10px] shadow-lg shadow-brand-500/30 hover:bg-brand-700 transition-all">
                            Empezar mi Investigación ahora
                        </button>

                        {{-- Este botón solo actualiza joined_at y CIERRA el modal --}}
                        <button wire:click="closeOnly"
                            class="text-[10px] font-black uppercase text-gray-400 hover:text-gray-600 tracking-widest">
                            Cerrar, solo dar un vistazo
                        </button>
                    </div>
                </div>
            </div>
        @endif
    @else
        {{-- Aquí pondrías tu Empty State de alumno que ya diseñamos --}}
        @include('livewire.dashboard.empty-state-student')
    @endif
</div>
