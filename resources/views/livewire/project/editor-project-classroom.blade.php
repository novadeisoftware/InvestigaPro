<div class="flex h-230 bg-[#F3F4F6] dark:bg-gray-955 overflow-hidden" x-data="{
    leftSidebar: true,
    rightSidebar: false,
    getSelectionText() {
        let text = window.getSelection().toString();
        return text;
    }
}">

    {{-- Overlay para móviles --}}
    <div x-show="leftSidebar || rightSidebar" @click="leftSidebar = false; rightSidebar = false"
        class="fixed inset-0 bg-black/50 z-40 lg:hidden" x-transition></div>

    {{-- ASIDE IZQUIERDO: PASOS DEL AULA --}}
    <aside :class="leftSidebar ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 w-72 bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-800 flex flex-col shadow-xl z-50 transition-transform duration-300 lg:relative lg:translate-x-0">

        <div class="p-6 flex justify-between items-center">
            <div>
                <p class="text-[10px] font-black text-brand-600 uppercase tracking-widest">Guía del Asesor</p>
                <h4 class="text-[11px] font-bold text-gray-400 truncate uppercase tracking-tighter">
                    {{ $project->classroom->name }}</h4>
            </div>
            <button @click="leftSidebar = false" class="lg:hidden text-gray-500">&times;</button>
        </div>
        <nav class="flex-1 overflow-y-auto px-4 pb-6 space-y-1 no-scrollbar">
            @foreach ($steps as $step)
                @php
                    $cStep = $step->classroomStep;
                    $isLocked = false;
                    $lockReason = '';

                    if ($cStep) {
                        $strategy = $this->project->classroom->step_strategy;
                        if ($strategy === 'locked' && $cStep->availability_mode === 'locked') {
                            $isLocked = true;
                            $lockReason = 'Bloqueado por Asesor';
                        }
                        if ($strategy === 'scheduled') {
                            if ($cStep->available_at) {
                                if ($cStep->available_at->isFuture()) {
                                    $isLocked = true;
                                    $lockReason = 'Disponible ' . $cStep->available_at->format('d M');
                                }
                            } else {
                                $isLocked = true;
                                $lockReason = 'Fecha por definir';
                            }
                        }
                    }
                @endphp

                @if ($isLocked && !$isReadOnly)
                    <div
                        class="w-full flex items-center gap-3 p-3 rounded-2xl opacity-50 cursor-not-allowed text-gray-400 bg-gray-50/50 dark:bg-gray-800/30 border border-transparent">
                        <div
                            class="shrink-0 w-6 h-6 rounded-lg flex items-center justify-center bg-gray-100 dark:bg-gray-800 text-gray-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <div class="flex flex-col overflow-hidden">
                            <span class="text-sm font-bold truncate">{{ $step->title }}</span>
                            <span class="text-[12px] tracking-tighter text-brand-500/80">{{ $lockReason }}</span>
                        </div>
                    </div>
                @else
                    <button type="button" wire:key="step-{{ $step->id }}"
                        wire:click="selectStep({{ $step->id }})"
                        @click="if(window.innerWidth < 1024) leftSidebar = false"
                        class="w-full flex items-center gap-3 p-3 rounded-2xl transition-all group {{ $currentStepId == $step->id ? 'bg-brand-600 text-white shadow-lg shadow-brand-500/40' : 'text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                        <div
                            class="shrink-0 w-6 h-6 rounded-lg flex items-center justify-center text-[10px] font-bold {{ $currentStepId == $step->id ? 'bg-white/20 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-400' }}">
                            {{ $step->order }}
                        </div>
                        <span
                            class="text-xs font-bold truncate text-left leading-none uppercase tracking-tighter">{{ $step->title }}</span>
                        @if ($step->content)
                            <div class="ml-auto flex items-center">
                                <svg class="w-3.5 h-3.5 {{ $currentStepId == $step->id ? 'text-white' : 'text-brand-400' }}"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        @endif
                    </button>
                @endif
            @endforeach
        </nav>
    </aside>

    {{-- ÁREA CENTRAL: EDITOR --}}
    <main class="flex-1 flex flex-col overflow-hidden relative" wire:key="main-editor-{{ $currentStepId }}">

        {{-- HEADER --}}
        <header
            class="h-16 bg-white/80 dark:bg-gray-900/80 backdrop-blur-md border-b border-gray-200 dark:border-gray-800 flex items-center justify-between px-4 lg:px-8 z-30">
            <div class="flex items-center gap-2">
                <button @click="leftSidebar = true" class="lg:hidden p-2 text-gray-600 hover:bg-gray-100 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M4 6h16M4 12h16M4 18h16" stroke-width="2" />
                    </svg>
                </button>
                <div class="flex flex-col">
                    <h1
                        class="text-xs font-black text-gray-800 dark:text-gray-200 truncate max-w-[150px] md:max-w-md uppercase tracking-tight italic">
                        {{ $project->title }}
                    </h1>
                    <div class="flex items-center gap-2 leading-none">
                        <span class="text-[8px] font-bold text-brand-500 uppercase tracking-tighter leading-none">Aula
                            Virtual: {{ $project->classroom->name }}</span>
                        @if ($isReadOnly)
                            <span
                                class="px-2 py-0.5 bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 text-[7px] font-black uppercase rounded-full border border-amber-200 dark:border-amber-800 animate-pulse tracking-widest leading-none">
                                👁️ Supervisor
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-4">
                @if ($lastSaved)
                    <div wire:loading.remove wire:target="content, selectStep"
                        class="hidden md:flex items-center gap-1.5 animate-fade-in">
                        <div
                            class="w-1.5 h-1.5 rounded-full {{ $isReadOnly ? 'bg-blue-500 shadow-[0_0_8px_rgba(59,130,246,0.6)]' : 'bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.6)]' }}">
                        </div>
                        <span
                            class="text-[11px] font-black text-gray-400 dark:text-gray-500 tracking-tighter uppercase italic">
                            {{ $isReadOnly ? 'Sincronizado' : 'Guardado' }} {{ $lastSaved }}
                        </span>
                    </div>
                @endif

                <button @click="rightSidebar = !rightSidebar" class="p-2 text-brand-600 lg:hidden">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M13 10V3L4 14h7v7l9-11h-7z" stroke-width="2" />
                    </svg>
                </button>
            </div>
        </header>

        {{-- CUERPO: Área de Redacción --}}
        <div class="flex-1 overflow-y-auto py-6 lg:py-12 px-4 lg:px-6 no-scrollbar bg-[#F8F9FA] dark:bg-gray-955">
            <div
                class="max-w-[850px] mx-auto bg-white dark:bg-gray-900 min-h-screen lg:min-h-[1100px] shadow-2xl rounded-t-[1.5rem] lg:rounded-t-[2.5rem] border border-gray-100 dark:border-gray-800 overflow-hidden">

                <div class="p-8 lg:p-16 pb-0">
                    <div class="flex items-center gap-3 mb-6 leading-none">
                        <span
                            class="px-4 py-1.5 bg-brand-50 dark:bg-brand-900/30 text-brand-600 text-[10px] font-black uppercase rounded-xl tracking-widest leading-none">
                            Paso {{ $currentStep->order }}
                        </span>
                        <div class="h-px flex-1 bg-gray-100 dark:bg-gray-800"></div>
                    </div>

                    <h2
                        class="text-3xl lg:text-5xl font-black text-gray-900 dark:text-white mb-8 leading-tight italic uppercase tracking-tighter">
                        {{ $currentStep->custom_name ?? ($currentStep->title ?? $currentStep->step_key) }}
                    </h2>

                    @if ($currentStep->additional_instructions)
                        <div
                            class="mb-10 p-6 bg-amber-50/50 dark:bg-amber-900/10 border-l-4 border-amber-400 rounded-r-[2rem]">
                            <p class="text-[10px] font-black text-amber-600 uppercase mb-2 tracking-[0.2em] italic">
                                Instrucciones del Asesor ({{ $project->university->siglas }})</p>
                            <p
                                class="text-xs lg:text-sm text-amber-800 dark:text-amber-200 leading-relaxed font-medium italic">
                                "{{ $currentStep->additional_instructions }}"
                            </p>
                        </div>
                    @endif
                </div>

                {{-- Contenido Principal --}}
                <div class="px-8 lg:px-16 pb-20 mt-4">
                    @if ($isReadOnly)
                        {{-- MODO ASESOR: Visor Premium --}}
                        <div
                            class="w-full min-h-[600px] bg-white dark:bg-gray-900/50 rounded-[2rem] border-2 border-gray-100 dark:border-gray-800 p-8 lg:p-12 shadow-inner-lg relative overflow-hidden transition-all duration-500">
                            <div class="absolute inset-0 opacity-[0.03] dark:opacity-[0.05] pointer-events-none"
                                style="background-image: linear-gradient(#000 1px, transparent 1px); background-size: 100% 3rem;">
                            </div>

                            <div class="relative z-10 font-serif select-text cursor-text">
                                @if (trim($content))
                                    <div
                                        class="text-lg lg:text-xl leading-[3rem] text-gray-800 dark:text-gray-200 whitespace-pre-wrap italic outline-none">
                                        {!! str_replace(
                                            ['[[', ']]'],
                                            [
                                                '<span class="bg-amber-100 dark:bg-amber-500/30 border-b-2 border-amber-500 px-1 rounded-md text-gray-900 dark:text-amber-100 not-italic font-bold transition-all hover:bg-amber-200 cursor-help">',
                                                '</span>',
                                            ],
                                            e($content),
                                        ) !!}
                                    </div>
                                @else
                                    <div class="flex flex-col items-center justify-center py-24 opacity-40">
                                        <p
                                            class="text-gray-400 text-xs font-black uppercase tracking-widest italic text-center">
                                            [ Documento sin contenido ]</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        {{-- MODO ESTUDIANTE --}}
                        {{-- MODO ESTUDIANTE: Editor Activo --}}
                        <div class="relative group">
                            <textarea wire:model.blur="content"
                                class="w-full min-h-[500px] lg:min-h-[800px] border-none focus:ring-0 text-lg lg:text-xl leading-[1.8] text-gray-700 dark:text-gray-300 placeholder-gray-200 resize-none bg-transparent p-0 font-serif"
                                placeholder="Corrige las marcas [[ ]] dejadas por tu asesor...">
    </textarea>

                            {{-- Tip visual para el alumno --}}
                            <div class="absolute bottom-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity">
                                <p class="text-[9px] font-black text-brand-500 uppercase italic tracking-widest">
                                    Borra los símbolos [[ ]] para quitar el resaltado
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        {{-- BOTONES FLOTANTES DE ACCIÓN (Estilo Glassmorphism Pro) --}}
        @if ($isReadOnly)
            <div
                class="fixed bottom-12 left-1/2 -translate-x-1/2 z-50 flex items-center gap-2 bg-gray-900/80 backdrop-blur-xl p-1.5 rounded-2xl bordertransition-all hover:border-white/20">

                {{-- Botón RESALTAR: Ahora con un degradado sutil --}}
                <button @mousedown="let text = getSelectionText(); if(text) $wire.highlightSelection(text)"
                    class="relative group px-6 py-3 rounded-xl overflow-hidden transition-all active:scale-95">
                    <div
                        class="absolute inset-0 bg-gradient-to-tr from-amber-500 to-yellow-400 opacity-90 group-hover:opacity-100 transition-opacity">
                    </div>
                    <div class="relative flex items-center gap-2">
                        <svg class="w-4 h-4 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                        <span class="text-[10px] font-black text-black uppercase tracking-[0.1em]">Resaltar</span>
                    </div>
                </button>

                {{-- Separador Visual --}}
                <div class="w-px h-6 bg-white/10 mx-1"></div>

                {{-- Botón QUITAR MARCA: Estilo Minimalista Oscuro --}}
                <button @mousedown="let text = getSelectionText(); if(text) $wire.removeHighlight(text)"
                    class="px-6 py-3 rounded-xl flex items-center gap-2 hover:bg-white/5 transition-all active:scale-95 group">
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-red-400 transition-colors" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <span
                        class="text-[10px] font-bold text-gray-400 group-hover:text-white uppercase tracking-[0.1em] transition-colors">Limpiar</span>
                </button>
            </div>
        @endif
    </main>

    {{-- ASIDE DERECHO --}}
    <aside :class="rightSidebar ? 'translate-x-0' : 'translate-x-full'"
        class="fixed inset-y-0 right-0 w-80 bg-white dark:bg-gray-900 border-l border-gray-200 dark:border-gray-800 p-6 flex flex-col shadow-2xl z-50 transition-transform duration-300 lg:relative lg:translate-x-0">

        <div class="flex items-center justify-between lg:justify-start gap-3 mb-8">
            <div class="flex items-center gap-3">
                <div
                    class="w-8 h-8 bg-brand-600 rounded-xl flex items-center justify-center shadow-lg shadow-brand-500/30 text-white font-black italic text-xs">
                    IA</div>
                <div class="flex flex-col">
                    <h3 class="text-[10px] font-black text-gray-900 dark:text-white uppercase tracking-widest">MSHO
                        Assistant</h3>
                    <span
                        class="text-[8px] text-brand-500 font-bold uppercase tracking-tighter italic leading-none">Laboratorio
                        Investigativa</span>
                </div>
            </div>
            <button @click="rightSidebar = false" class="lg:hidden text-gray-500 hover:text-gray-700">&times;</button>
        </div>

        <div class="space-y-4 overflow-y-auto no-scrollbar flex-1">
            @if (!$isReadOnly)
                <button wire:click="generateDraft" wire:loading.attr="disabled"
                    class="w-full p-5 bg-gray-50 dark:bg-gray-800/50 rounded-[2rem] text-left border-2 border-transparent hover:border-brand-200 transition-all group disabled:opacity-50 relative overflow-hidden shadow-sm">
                    <div wire:loading.remove wire:target="generateDraft">
                        <p
                            class="text-xs font-black text-gray-900 dark:text-white uppercase italic group-hover:text-brand-600 leading-none">
                            Redactar Borrador</p>
                        <p class="text-[10px] text-gray-400 mt-2 font-medium leading-tight">Generar base para este
                            capítulo.</p>
                    </div>
                    <div wire:loading wire:target="generateDraft" class="flex items-center gap-3">
                        <svg class="animate-spin h-4 w-4 text-brand-600" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        <p class="text-[10px] font-black text-brand-600 uppercase italic">Procesando...</p>
                    </div>
                </button>
            @endif

            {{-- FEEDBACK SECTION --}}
            <div class="pt-6 border-t border-gray-100 dark:border-white/5 space-y-4">
                <div class="flex items-center justify-between px-2">
                    <h3 class="text-[15px]  text-gray-900 dark:text-white  leading-none">
                        Feedback Asesoría</h3>
                    <span
                        class="px-2 py-0.5 bg-brand-500 text-white text-[12px] font-black rounded-full shadow-lg">{{ count($comments) }}</span>
                </div>

                @if ($isReadOnly)
                    {{-- Contenedor principal ajustado --}}
                    <div
                        class="bg-gray-50 dark:bg-gray-800/50 p-4 rounded-[2rem] border border-transparent focus-within:border-brand-400/30 transition-all shadow-sm flex flex-col items-center gap-3">

                        {{-- Textarea sin bordes molestos --}}
                        <textarea wire:model="newComment"
                            class="w-full bg-transparent border-none focus:ring-0 text-xs font-medium dark:text-white placeholder-gray-400 resize-none no-scrollbar text-center"
                            placeholder="Escribe una observación..." rows="3"></textarea>

                        {{-- Botón centrado --}}
                        <x-common.button-submit type="button" wire:click="addComment" variant="brand"
                            class="rounded-2xl shadow-lg shadow-brand-500/20 w-full flex justify-center items-center py-3">
                            <x-slot:icon>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 4v16m8-8H4" stroke-width="2" stroke-linecap="round" />
                                </svg>
                            </x-slot:icon>
                            Registrar Nota
                        </x-common.button-submit>
                    </div>
                @endif

                <div class="space-y-3">
                    @forelse($comments as $c)
                        <div
                            class="p-5 bg-white dark:bg-white/5 rounded-[1.8rem] border border-gray-100 dark:border-white/5 shadow-sm relative group">

                            {{-- Botón de eliminar siempre visible con icono de basura --}}
                            @if ($isReadOnly)
                                <button wire:click="deleteComment({{ $c->id }})"
                                    class="absolute top-5 right-5 text-gray-400 hover:text-red-500 transition-colors p-1"
                                    title="Eliminar nota">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            @endif

                            <p
                                class="text-[13px] text-gray-700 dark:text-gray-300 leading-relaxed font-medium italic pr-6">
                                "{{ $c->comment }}"
                            </p>

                            <div
                                class="flex justify-between items-center mt-3 pt-3 border-t border-gray-50 dark:border-white/5 leading-none">
                                <span class="text-[11px] text-gray-400 font-bold tracking-tighter">
                                    {{ $c->created_at->diffForHumans() }}
                                </span>

                                @if (!$isReadOnly)
                                    <span
                                        class="flex items-center gap-1 text-[10px] text-brand-500 font-black uppercase italic">
                                        <span class="w-1 h-1 bg-brand-500 rounded-full animate-pulse"></span>
                                        Pendiente
                                    </span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div
                            class="py-10 text-center border-2 border-dashed border-gray-100 dark:border-white/5 rounded-[2rem] opacity-50">
                            <p
                                class="text-gray-300 text-[9px] font-black uppercase tracking-widest italic leading-none">
                                Sin observaciones
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        @if (!$isReadOnly)
            <div class="mt-6 p-6 bg-brand-600 rounded-[2.5rem] text-white shadow-xl shadow-brand-500/20">
                <div class="flex justify-between items-end mb-3">
                    <div>
                        <p
                            class="text-[9px] font-black text-brand-200 uppercase tracking-widest mb-1 italic leading-none">
                            Uso de IA</p>
                        <p class="text-2xl font-black tracking-tighter leading-none leading-none">
                            {{ number_format($project->ai_words_used) }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[8px] font-bold text-brand-200 uppercase tracking-widest leading-none">Límite
                        </p>
                        <p class="text-xs font-black leading-none">
                            {{ number_format($project->ai_word_limit / 1000, 1) }}k</p>
                    </div>
                </div>
                @php $percentage = $project->ai_word_limit > 0 ? ($project->ai_words_used / $project->ai_word_limit) * 100 : 0; @endphp
                <div
                    class="w-full bg-brand-800 h-1.5 rounded-full overflow-hidden border border-brand-500/30 leading-none">
                    <div class="bg-white h-full transition-all duration-700 ease-out shadow-[0_0_8px_rgba(255,255,255,0.5)]"
                        style="width: {{ min($percentage, 100) }}%"></div>
                </div>
            </div>
        @endif
    </aside>
</div>
