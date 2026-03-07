<div class="min-h-screen bg-[#F9FAFB] dark:bg-gray-955">

   @if($project->document_type == 'PROYECTO DE TESIS')
    {{-- Tu contenido aquí --}}
    <div class="max-w-full mx-auto flex flex-col lg:flex-row min-h-screen">

        {{-- PANEL IZQUIERDO: GUÍA DE PROGRESO --}}
        <aside
            class=" bg-white dark:bg-gray-900 border-r border-gray-100 dark:border-gray-800 p-8 lg:p-12 flex flex-col justify-between">
            <div>
                <div class="mb-12">
                    <p class="text-[10px] font-black text-brand-600 uppercase tracking-[0.3em] mb-2 italic">Setup v1.0
                    </p>
                    <h1
                        class="text-3xl font-black text-gray-900 dark:text-white uppercase tracking-tighter leading-none">
                        Configuración<br>de Proyecto
                    </h1>
                </div>

                {{-- STEPPER VERTICAL --}}
                <div class="space-y-12 relative">
                    {{-- Línea conectora --}}
                    <div class="absolute left-[19px] top-2 bottom-2 w-0.5 bg-gray-100 dark:bg-gray-800"></div>

                    {{-- Paso 01 --}}
                    <div class="relative flex items-start gap-6 mb-4">
                        <div
                            class="shrink-0 w-10 h-10 rounded-xl flex items-center justify-center transition-all duration-500 z-10 {{ $step >= 1 ? 'bg-brand-600 text-white shadow-[0_10px_20px_rgba(var(--brand-rgb),0.3)]' : 'bg-gray-100 text-gray-400' }}">
                            <span class="text-xs font-black italic">01</span>
                        </div>
                        <div class="flex flex-col pt-1">
                            <span
                                class="text-[12px] font-black uppercase {{ $step == 1 ? 'text-gray-900 dark:text-white' : 'text-gray-400' }}">
                                Datos Generales
                            </span>
                            <p class="text-[13px] text-gray-400 font-medium mt-1 leading-relaxed">
                                Definición de variables, lugar y tiempo de estudio.
                            </p>
                        </div>
                    </div>

                    {{-- Paso 02 --}}
                    <div class="relative flex items-start gap-6 mb-4 {{ $step < 2 ? 'opacity-40' : '' }}">
                        <div
                            class="shrink-0 w-10 h-10 rounded-xl flex items-center justify-center transition-all duration-500 z-10 {{ $step >= 2 ? 'bg-brand-600 text-white shadow-[0_10px_20px_rgba(var(--brand-rgb),0.3)]' : 'bg-gray-100 text-gray-400' }}">
                            <span class="text-xs font-black italic">02</span>
                        </div>
                        <div class="flex flex-col pt-1">
                            <span
                                class="text-[12px] font-black uppercase {{ $step == 2 ? 'text-gray-900 dark:text-white' : 'text-gray-400' }}">
                                Título
                            </span>
                            <p class="text-[13px] text-gray-400 font-medium mt-1 leading-relaxed">
                                Generación algorítmica del título de investigación.
                            </p>
                        </div>
                    </div>

                    {{-- Paso 03 --}}
                    {{-- He cambiado opacity-40 fija por una condicional --}}
                    <div
                        class="relative flex items-start gap-6 mb-4 {{ $step < 3 ? 'opacity-40' : '' }} transition-opacity duration-500">
                        <div
                            class="shrink-0 w-10 h-10 rounded-xl flex items-center justify-center transition-all duration-500 z-10 {{ $step >= 3 ? 'bg-brand-600 text-white shadow-[0_10px_20px_rgba(var(--brand-rgb),0.3)]' : 'bg-gray-100 text-gray-400' }}">
                            <span class="text-xs font-black italic">03</span>
                        </div>
                        <div class="flex flex-col pt-1">
                            <span
                                class="text-[12px] font-black uppercase {{ $step == 3 ? 'text-gray-900 dark:text-white' : 'text-gray-400' }}">
                                Estructura Del Proyecto
                            </span>
                            <p class="text-[13px] text-gray-400 font-medium mt-1 leading-relaxed">
                                Define las etapas de tu proyecto.
                            </p>
                        </div>
                    </div>

                    {{-- Paso 04 --}}
                    <div
                        class="relative flex items-start gap-6 mb-4 {{ $step < 4 ? 'opacity-40' : '' }} transition-opacity duration-500">
                        <div
                            class="shrink-0 w-10 h-10 rounded-xl flex items-center justify-center transition-all duration-500 z-10 {{ $step >= 4 ? 'bg-brand-600 text-white shadow-[0_10px_20px_rgba(var(--brand-rgb),0.3)]' : 'bg-gray-100 text-gray-400' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M5 13l4 4L19 7" stroke-width="3" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </div>
                        <div class="flex flex-col pt-1">
                            <span
                                class="text-[12px] font-black uppercase {{ $step == 4 ? 'text-gray-900 dark:text-white' : 'text-gray-400' }}">
                                Finalización
                            </span>
                            <p class="text-[13px] text-gray-400 font-medium mt-1 leading-relaxed">
                                Acceso al editor premium de InvestigaPro.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </aside>

        {{-- PANEL DERECHO: ÁREA DE TRABAJO --}}
        <main class="flex-1 p-8 lg:p-8 items-center justify-center dark:bg-gray-900">

            @if ($step == 1)
                {{-- ETAPA 1: DATOS GENERALES --}}
                <div class="w-full max-w-full animate-fade-in">

                    <div class="flex flex-col gap-1 mb-12">


                        {{-- BLOQUE DE TÍTULO --}}
                        <div>
                            <h2 class="text-4xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">
                                Datos Generales
                            </h2>
                            <p class="text-sm text-gray-500 mt-2 font-medium">
                                Ingresa los componentes clave para que la IA entienda tu investigación.
                            </p>
                        </div>
                    </div>


                    <div class="space-y-8">



                        {{-- Bloque 3: Core de la Investigación (2 por fila) --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Área de Estudio --}}
                            <div class="space-y-1.5">
                                <x-form.input.textarea wire:model="area" label="Área de Estudio"
                                    placeholder="Ej. Ingeniería de Software..." />
                                <x-input-error for="area" />
                            </div>

                            {{-- Objeto de Estudio --}}
                            <div class="space-y-1.5">
                                <x-form.input.textarea wire:model="objeto" label="Objeto de Estudio"
                                    placeholder="Ej. Pavimentos rígidos..." />
                                <x-input-error for="objeto" />
                            </div>

                            {{-- Identificación del Problema --}}
                            <div class="space-y-1.5">
                                <x-form.input.textarea wire:model="problema" label="Identificación del Problema"
                                    placeholder="Describe el problema principal..." />
                                <x-input-error for="problema" />
                            </div>

                            {{-- Alternativa de Solución --}}
                            <div class="space-y-1.5">
                                <x-form.input.textarea wire:model="solucion" label="Alternativa de Solución"
                                    placeholder="¿Cuál es tu propuesta de solución?..." />
                                <x-input-error for="solucion" />
                            </div>
                        </div>

                        {{-- Bloque 4: Logística --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="group">
                                <x-form.input.text label="Lugar De Ejecución" wire:model="lugar" for="lugar"
                                    placeholder="Ej. Trujillo, La Libertad" :error="$errors->has('lugar')" />
                            </div>
                            <div class="group">
                                <x-form.input.text label="Tiempo/Periodo" wire:model="tiempo" for="tiempo"
                                    placeholder="Ej. 2025 - 2026" :error="$errors->has('tiempo')" />
                            </div>
                        </div>
                    </div>

                    {{-- Botón de Acción --}}
                    <div class="pt-10">
                        <x-common.button-submit type="button" wire:click="saveStep1"
                            target="nextStep" variant="brand">
                            <x-slot:icon>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </x-slot:icon>
                            Siguente Paso
                        </x-common.button-submit>
                    </div>
                </div>
            @elseif($step == 2)
                {{-- ETAPA 2: PROPUESTAS DE TÍTULO --}}
                <div class="w-full max-w-full animate-fade-in">
                    <div class="flex items-center justify-between mb-12">
                        <div class="flex flex-col gap-1"> {{-- Cambiamos a columna para ponerlo encima --}}
                            {{-- BOTÓN DE RETROCESO --}}
                            <button wire:click="$set('step', {{ $step - 1 }})"
                                class="flex items-center gap-2 text-gray-400 hover:text-brand-600 transition-all group w-fit mb-3">
                                <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M15 19l-7-7 7-7" stroke-width="3" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                <span class="text-[10px] font-black uppercase">Volver</span>
                            </button>

                            <div>
                                <h2
                                    class="text-4xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">
                                    Propuestas de Título
                                </h2>
                                <p class="text-sm text-gray-500 mt-2 font-medium">Sugerencias inteligentes que
                                    transforman tu idea de investigación en un título sólido y profesional.</p>
                            </div>
                        </div>

                        <x-common.button-submit  type="button" wire:click="generateTitles" target="generateTitles"
                            variant="brand" wire:loading.attr="disabled">
                       
                            <x-slot:icon>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.456-2.455L18 2.25l.259 1.035a3.375 3.375 0 002.455 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z">
                                    </path>
                                </svg>
                            </x-slot:icon>
                            Analizar y Crear Títulos
                        </x-common.button-submit>

               
                    </div>

                    <div class="space-y-4">
                        @if ($loading)
                            <div class="py-24 flex flex-col items-center">
                                <div
                                    class="w-12 h-12 border-4 border-amber-500 border-t-transparent rounded-full animate-spin">
                                </div>
                                <p class="mt-4 text-[10px] font-black text-gray-400 uppercase tracking-widest italic">
                                    Analizando coherencia sintáctica...</p>
                            </div>
                        @else
                            @foreach ($titleOptions as $index => $opt)
                                <label wire:key="title-opt-{{ md5($opt) }}" {{-- Al hacer clic, actualizamos la selección Y el campo de texto editable --}}
                                    wire:click="$set('selectedTitle', '{{ addslashes($opt) }}'); $set('title', '{{ addslashes($opt) }}')"
                                    class="block group relative p-6 bg-white dark:bg-gray-900 rounded-[1.5rem] border-2 cursor-pointer transition-all duration-300 {{ $selectedTitle == $opt ? 'border-brand-600 shadow-lg bg-brand-50/30' : 'border-gray-100 dark:border-gray-800 hover:border-brand-200' }}">

                                    <div class="flex items-start gap-4">
                                        <div
                                            class="mt-1 w-5 h-5 rounded-full border-2 flex items-center justify-center transition-colors {{ $selectedTitle == $opt ? 'border-brand-600 bg-brand-600' : 'border-gray-200' }}">
                                            @if ($selectedTitle == $opt)
                                                <div class="w-1.5 h-1.5 bg-white rounded-full"></div>
                                            @endif
                                        </div>

                                        <span
                                            class="flex-1 font-medium text-sm text-gray-800 dark:text-gray-100 leading-snug">
                                            {{ $opt }}
                                        </span>
                                    </div>

                                    <input type="radio" name="project-title" value="{{ $opt }}"
                                        class="hidden">
                                </label>
                            @endforeach

                            {{-- El Textarea ahora recibirá el valor automáticamente --}}
                            <div
                                class="space-y-3 mt-8 p-4 bg-gray-50 dark:bg-gray-800/50 rounded-2xl border border-dashed border-gray-200">
                                <div class="flex items-center gap-2 text-brand-600 mb-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                    <span class="text-xs font-bold uppercase tracking-wider">Editor de Título
                                        Final</span>
                                </div>
                                <x-form.input.textarea wire:model.live="title"
                                    label="Puedes ajustar el título seleccionado aquí:"
                                    placeholder="Selecciona una opción arriba o escribe tu propio título..." />
                                <x-input-error for="title" />
                            </div>
                            <x-input-error for="selectedTitle" />

                            <div class="pt-12">
                                {{-- BOTÓN REGENERAR (Ya lo tenías) --}}
                                <x-common.button-submit type="button" wire:click="saveStep2" target="saveStep2"
                                    variant="brand" wire:loading.attr="disabled">
                                    <x-slot:icon>
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                        </svg>
                                    </x-slot:icon>
                                    Siguente Paso
                                </x-common.button-submit>
                            </div>
                        @endif
                    </div>
                </div>
            @elseif($step == 3)
                {{-- ETAPA 3: ESTRUCTURA DEL PROYECTO  --}}
                <div class="w-full max-w-full animate-fade-in">
                    <div class="flex items-center justify-between mb-12">

                        <div class="flex flex-col gap-1"> {{-- Cambiamos a columna para ponerlo encima --}}
                            {{-- BOTÓN DE RETROCESO --}}
                            <button wire:click="$set('step', {{ $step - 1 }})"
                                class="flex items-center gap-2 text-gray-400 hover:text-brand-600 transition-all group w-fit mb-3">
                                <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M15 19l-7-7 7-7" stroke-width="3" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                <span class="text-[10px] font-black uppercase">Volver</span>
                            </button>

                            <div>
                                <h2
                                    class="text-4xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">
                                    ESTRUCTURA DEL PROYECTO
                                </h2>
                                <p class="text-sm text-gray-500 mt-2 font-medium">Personaliza la estructura de tu
                                    investigación. Ajusta los capítulos según los requerimientos específicos de tu
                                    asesor académico.</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-indigo-50 rounded-lg">
                                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-800 text-lg">Capítulos</h3>

                                </div>
                            </div>
                            <span class="px-3 py-1 bg-indigo-100 text-indigo-700 text-xs font-bold rounded-full">
                                {{ count($formatSteps) }} Capítulos
                            </span>
                        </div>

                        @if (!empty($formatSteps))
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach ($formatSteps as $index => $fStep)
                                    <div
                                        class="group flex items-center gap-4 p-3 bg-gray-50 hover:bg-white hover:border-indigo-200 border border-transparent rounded-xl transition-all shadow-sm hover:shadow-md">
                                        <span
                                            class="flex-shrink-0 w-8 h-8 bg-white border border-gray-200 text-gray-400 text-sm font-bold rounded-lg flex items-center justify-center group-hover:text-indigo-600 group-hover:border-indigo-100 shadow-xs">
                                            {{ $fStep['orden'] }}
                                        </span>

                                        <div class="flex-1 min-w-0">
                                            <input type="text" wire:model="formatSteps.{{ $index }}.titulo"
                                                class="w-full bg-transparent border-none focus:ring-0 font-semibold text-gray-700 p-0 text-sm placeholder-gray-400 truncate"
                                                placeholder="Nombre del capítulo...">

                                            @if (isset($fStep['secciones']))
                                                <div class="flex flex-wrap gap-1 mt-1.5">
                                                    @foreach ($fStep['secciones'] as $secc)
                                                        <span
                                                            class="text-[9px] bg-indigo-50 text-indigo-500 px-1.5 py-0.5 rounded-md font-medium uppercase tracking-wider">
                                                            {{ $secc }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>

                                        <svg class="w-4 h-4 text-gray-300 group-hover:text-indigo-400 flex-shrink-0"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                            </path>
                                        </svg>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-10 border-2 border-dashed border-gray-100 rounded-2xl">
                                <p class="text-gray-400 text-sm">No se pudo cargar la estructura de la universidad.</p>
                            </div>
                        @endif
                    </div>
                    <div class="pt-12">
                        {{-- BOTÓN REGENERAR (Ya lo tenías) --}}
                        <x-common.button-submit type="button" wire:click="confirmFinishSetup"
                            target="confirmFinishSetup" variant="brand" wire:loading.attr="disabled">
                            <x-slot:icon>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/01/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z">
                                    </path>
                                </svg>
                            </x-slot:icon>
                            Finalizar Configuración
                        </x-common.button-submit>
                    </div>


                </div>
            @endif
        </main>
    </div>

    @else

    @endif
</div>
