<div class="min-h-screen bg-[#F9FAFB] dark:bg-gray-955 relative" x-data="{ loading: false }" {{-- Este escuchador permite apagar el loader desde Livewire cuando termine --}}
    @close-local-loader.window="loading = false">


    <x-common.custom-scrollbar />
    <x-common.loader text="Analizando Documento..." />



    @switch($project->document_type)
        @case('PROYECTO DE TESIS')
            {{-- Tu contenido aquí --}}
            <div class="max-w-full mx-auto flex flex-col lg:flex-row min-h-screen">

                {{-- PANEL IZQUIERDO: GUÍA DE PROGRESO --}}
                <aside
                    class=" bg-white dark:bg-gray-900 border-r border-gray-100 dark:border-gray-800 p-8 lg:p-12 flex flex-col justify-between">
                    <div>
                        <div class="mb-12">
                            <p class="text-[10px] font-black text-brand-600 uppercase tracking-[0.3em] mb-2">Setup
                                v1.0
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
                                    <span class="text-xs font-black ">01</span>
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
                                    <span class="text-xs font-black">02</span>
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
                                    <span class="text-xs font-black">03</span>
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

                            {{-- Paso 04: Pago --}}
                            <div
                                class="relative flex items-start gap-6 mb-4 {{ $step < 4 ? 'opacity-40' : '' }} transition-opacity duration-500">
                                <div
                                    class="shrink-0 w-10 h-10 rounded-xl flex items-center justify-center transition-all duration-500 z-10 
                                      {{ $step >= 4 ? 'bg-brand-600 text-white shadow-[0_10px_20px_rgba(var(--brand-rgb),0.3)]' : 'bg-gray-100 text-gray-400' }}">
                                    {{-- Icono de Moneda o Tarjeta para Pago --}}
                                    <span class="text-xs font-black">04</span>
                                </div>
                                <div class="flex flex-col pt-1">
                                    <span
                                        class="text-[12px] font-black uppercase {{ $step == 4 ? 'text-gray-900 dark:text-white' : 'text-gray-400' }}">
                                        Activación
                                    </span>
                                    <p class="text-[13px] text-gray-400 font-medium mt-1 leading-relaxed">
                                        Elige el plan ideal para desbloquear tu tesis.
                                    </p>
                                </div>
                            </div>

                            {{-- Paso 05: Finalización --}}
                            <div
                                class="relative flex items-start gap-6 mb-4 {{ $step < 5 ? 'opacity-40' : '' }} transition-opacity duration-500">
                                <div
                                    class="shrink-0 w-10 h-10 rounded-xl flex items-center justify-center transition-all duration-500 z-10 
                                       {{ $step >= 5 ? 'bg-brand-600 text-white shadow-[0_10px_20px_rgba(var(--brand-rgb),0.3)]' : 'bg-gray-100 text-gray-400' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M5 13l4 4L19 7" stroke-width="3" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <div class="flex flex-col pt-1">
                                    <span
                                        class="text-[12px] font-black uppercase {{ $step == 5 ? 'text-gray-900 dark:text-white' : 'text-gray-400' }}">
                                        Finalización
                                    </span>
                                    <p class="text-[13px] text-gray-400 font-medium mt-1 leading-relaxed">
                                        ¡Todo listo! Accede ahora al editor premium.
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
                                <x-common.button-submit type="button" wire:click="saveStep1" target="nextStep" variant="brand">
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

                                <x-common.button-submit type="button" wire:click="generateTitles" target="generateTitles"
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
                                        <p class="mt-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">
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
                            <div class="bg-white p-6 rounded-2xl dark:bg-gray-900 shadow-sm">
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
                                            <h7
                                                class="text-xl font-black text-gray-800 dark:text-white uppercase tracking-tighter">
                                                CAPÍTULOS
                                            </h7>

                                        </div>
                                    </div>
                                    <span class="px-3 py-1 bg-indigo-100 text-indigo-700 text-xs font-bold rounded-full">
                                        {{ count($formatSteps) }} Capítulos
                                    </span>
                                </div>

                                @if (!empty($formatSteps))
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 ">
                                        @foreach ($formatSteps as $index => $fStep)
                                            <div
                                                class="group flex items-center gap-4 p-3 bg-gray-50 hover:bg-white hover:border-indigo-200 border border-transparent rounded-xl transition-all shadow-sm hover:shadow-md dark:bg-gray-700">
                                                <span
                                                    class="flex-shrink-0 w-8 h-8 bg-white border border-gray-200 text-gray-400 text-sm font-bold rounded-lg flex items-center justify-center group-hover:text-indigo-600 group-hover:border-indigo-100 shadow-xs">
                                                    {{ $fStep['orden'] }}
                                                </span>

                                                <div class="flex-1 min-w-0">

                                                    <x-form.input.text wire:model="formatSteps.{{ $index }}.titulo"
                                                        for="title" :error="$errors->has(
                                                            'formatSteps.{{ $index }}.titulo',
                                                        )" />


                                                    @if (isset($fStep['secciones']))
                                                        <div class="flex flex-wrap gap-1 mt-1.5">
                                                            @foreach ($fStep['secciones'] as $secc)
                                                                <p class="text-sm text-gray-500 mt-2 font-medium">
                                                                    {{ $secc }}
                                                                </p>
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
                                        <p class="text-gray-400 text-sm">No se pudo cargar la estructura de la universidad.
                                        </p>
                                    </div>
                                @endif
                            </div>

                            {{-- Botón de Acción --}}
                            <div class="pt-10">
                                <x-common.button-submit type="button" wire:click="nextStep(4)" target="nextStep"
                                    variant="brand">
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
                    @elseif($step == 4)
                        {{-- ETAPA 4: ACTIVACIÓN --}}
                        <div class="w-full max-w-full animate-fade-in">

                            {{-- CABECERA --}}
                            <div class="flex flex-col gap-1 mb-8">
                                <button wire:click="$set('step', {{ $step - 1 }})"
                                    class="flex items-center gap-2 text-gray-400 hover:text-brand-600 transition-all group w-fit mb-3">
                                    <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M15 19l-7-7 7-7" stroke-width="3" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                    <span class="text-[10px] font-black uppercase">Volver a estructura</span>
                                </button>

                                <h2 class="text-4xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">
                                    ACTIVACIÓN
                                </h2>
                                <p class="text-sm text-gray-500 mt-2 font-medium">
                                    Estás a un paso de desbloquear el poder de la IA. Elige el plan que mejor se adapte a tu
                                    etapa académica.
                                </p>
                            </div>

                            {{-- NUEVO: PASOS PARA LA ACTIVACIÓN (Genera Confianza) --}}
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-2">
                                <div
                                    class="flex items-center gap-3 p-4 bg-gray-50 dark:bg-gray-800/50 rounded-2xl border border-gray-100 dark:border-gray-700">
                                    <span
                                        class="w-8 h-8 rounded-full bg-brand-600 text-white flex items-center justify-center text-xs font-black shrink-0">1</span>
                                    <p class="text-[10px] font-bold text-gray-600 dark:text-gray-400 uppercase leading-tight">
                                        Elige tu plan y contacta a soporte</p>
                                </div>
                                <div
                                    class="flex items-center gap-3 p-4 bg-gray-50 dark:bg-gray-800/50 rounded-2xl border border-gray-100 dark:border-gray-700">
                                    <span
                                        class="w-8 h-8 rounded-full bg-brand-600 text-white flex items-center justify-center text-xs font-black shrink-0">2</span>
                                    <p class="text-[10px] font-bold text-gray-600 dark:text-gray-400 uppercase leading-tight">
                                        Envía el voucher (Yape o BCP)</p>
                                </div>
                                <div
                                    class="flex items-center gap-3 p-4 bg-gray-50 dark:bg-gray-800/50 rounded-2xl border border-gray-100 dark:border-gray-700">
                                    <span
                                        class="w-8 h-8 rounded-full bg-brand-600 text-white flex items-center justify-center text-xs font-black shrink-0">3</span>
                                    <p class="text-[10px] font-bold text-gray-600 dark:text-gray-400 uppercase leading-tight">
                                        Activamos tu acceso al instante</p>
                                </div>
                            </div>

                            {{-- GRID DE PLANES --}}
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12 items-stretch">

                                {{-- Plan 01: Proyecto Base --}}
                                <div
                                    class="bg-white dark:bg-gray-800 border-2 border-gray-100 dark:border-gray-700 rounded-[2.5rem] p-8 flex flex-col hover:border-brand-500/50 transition-all shadow-sm">
                                    <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Solo
                                        Proyecto de Tesis</h3>
                                    <div class="flex items-baseline gap-1 mb-6">
                                        <span class="text-4xl font-black text-gray-900 dark:text-white">S/ 399</span>
                                        <span class="text-lg font-bold text-gray-400">.90</span>
                                    </div>
                                    <ul class="space-y-4 mb-8 flex-1">
                                        <li class="flex items-start gap-3 text-sm text-gray-600 dark:text-gray-300">
                                            <svg class="w-5 h-5 text-brand-500 shrink-0" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path d="M5 13l4 4L19 7" stroke-width="3" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                            <span>Acceso al editor premium</span>
                                        </li>
                                        <li class="flex items-start gap-3 text-sm text-gray-600 dark:text-gray-300">
                                            <svg class="w-5 h-5 text-brand-500 shrink-0" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path d="M5 13l4 4L19 7" stroke-width="3" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                            <span>+50,000 tokens de IA</span>
                                        </li>
                                    </ul>
                                    <a href="https://wa.me/+51922700200?text={{ urlencode('Hola! Quiero activar mi ' . $selectedFormatKey . '. Título: ' . $title . '. con ID: ' . $Idproyect) }}"
                                        target="_blank"
                                        class="w-full py-5 bg-brand-600 hover:bg-brand-700 text-white rounded-[1.5rem] text-center text-sm font-black uppercase tracking-widest transition-all shadow-xl shadow-brand-500/40 flex items-center justify-center gap-2">
                                        Elegir este
                                    </a>
                                </div>

                                {{-- Plan 02: Combo Grado Total (DESTACADO) --}}
                                <div
                                    class="relative bg-white dark:bg-gray-900 border-4 border-brand-500 rounded-[2.5rem] p-8 flex flex-col transform md:-translate-y-4 shadow-2xl shadow-brand-500/20 z-10">
                                    <div
                                        class="text-gray-900 dark:text-white px-6 py-1.5 rounded-full text-[10px] font-black uppercase whitespace-nowrap">
                                        🚀 Recomendado: Grado asegurado
                                    </div>
                                    <h3 class="text-xs font-black text-brand-500 uppercase mb-4 text-center">
                                        Grado Total (Combo)</h3>

                                    <div class="text-center mb-6">
                                        <div class="flex items-center justify-center gap-2 mb-1">
                                            <span class="text-sm text-gray-400 line-through font-bold">S/ 999.90</span>
                                            <span
                                                class="bg-green-100 dark:bg-green-500/20 text-green-800 dark:text-green-200 text-[10px] px-2 py-0.5 rounded-md font-bold uppercase">-
                                                S/ 200 dto</span>
                                        </div>
                                        <div class="flex items-baseline justify-center gap-1">
                                            <span class="text-2xl font-black text-gray-900 dark:text-white tracking-tighter">S/
                                                799</span>
                                            <span class="text-2xl font-bold text-gray-400">.90</span>
                                        </div>
                                        <p class="text-[10px] text-green-500 font-bold uppercase mt-2 tracking-widest">¡Ahorras
                                            un 20% hoy!</p>
                                    </div>

                                    <ul class="space-y-4 mb-8 flex-1">
                                        <li class="flex items-start gap-3 text-sm text-gray-900 dark:text-white font-bold">
                                            <svg class="w-6 h-6 text-brand-500 shrink-0" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path d="M5 13l4 4L19 7" stroke-width="3" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                            Proyecto + Informe de Tesis
                                        </li>
                                        <li class="flex items-start gap-3 text-sm text-gray-600 dark:text-gray-300">
                                            <svg class="w-5 h-5 text-brand-500 shrink-0" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path d="M5 13l4 4L19 7" stroke-width="3" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                            Límite de IA Pro ampliado
                                        </li>
                                    </ul>

                                    <a href="https://wa.me/+51922700200?text={{ urlencode('¡QUIERO EL COMBO GRADO TOTAL! 🚀 Proyecto: ' . $title) }}"
                                        target="_blank"
                                        class="w-full py-5 bg-brand-600 hover:bg-brand-700 text-white rounded-[1.5rem] text-center text-sm font-black uppercase tracking-widest transition-all shadow-xl shadow-brand-500/40 flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.992-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.438 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884 0 2.225.586 3.891 1.746 5.634l-.999 3.648 3.742-.981z" />
                                        </svg>
                                        Activar Ahora
                                    </a>
                                </div>
                                {{-- Plan 03: Tokens --}}
                                <div
                                    class="bg-white dark:bg-gray-800 border-2 border-gray-100 dark:border-gray-700 rounded-[2.5rem] p-8 flex flex-col hover:border-indigo-500/50 transition-all group shadow-sm opacity-90 hover:opacity-100">
                                    <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Recarga de
                                        IA</h3>

                                    <div class="flex items-baseline gap-1 mb-6">
                                        <span class="text-4xl font-black text-gray-900 dark:text-white">S/ 19</span>
                                        <span class="text-lg font-bold text-gray-400">.90</span>
                                    </div>

                                    <ul class="space-y-4 mb-10 flex-1">
                                        <li class="flex items-center gap-3 text-sm text-gray-500">
                                            <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path d="M13 10V3L4 14h7v7l9-11h-7z" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                            Ideal para revisiones
                                        </li>
                                        <li class="flex items-center gap-3 text-sm text-gray-500 font-bold">
                                            <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path d="M13 10V3L4 14h7v7l9-11h-7z" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                            +20,000 palabras de IA
                                        </li>
                                    </ul>

                                    <a href="https://wa.me/+51922700200?text={{ urlencode('Hola! Necesito una RECARGA DE IA (S/ 19.90). Usuario: ' . auth()->user()->name) }}"
                                        target="_blank"
                                        class="w-full py-5 bg-brand-600 hover:bg-brand-700 text-white rounded-[1.5rem] text-center text-sm font-black uppercase tracking-widest transition-all shadow-xl shadow-brand-500/40 flex items-center justify-center gap-2">
                                        Comprar Tokens
                                    </a>
                                </div>
                            </div>

                            {{-- FOOTER DE PAGO: Información y Acción --}}
                            <div
                                class="mt-12 p-8 bg-gray-50 dark:bg-gray-800/50 rounded-[2.5rem] border border-gray-100 dark:border-gray-700 shadow-inner">
                                <div class="flex flex-col lg:flex-row items-center justify-between gap-8">

                                    {{-- LADO IZQUIERDO: Métodos y Soporte --}}
                                    <div class="flex flex-col md:flex-row items-center gap-6 text-center md:text-left">
                                        <div class="space-y-1">
                                            <h4
                                                class="text-xs font-black text-gray-900 dark:text-white uppercase tracking-wider">
                                                Métodos de Pago
                                            </h4>
                                            <p class="text-[11px] text-gray-500 max-w-[200px] leading-tight">
                                                Activación manual mediante validación de voucher vía WhatsApp.
                                            </p>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <span
                                                class="px-3 py-2 bg-white dark:bg-gray-700 rounded-xl text-[10px] font-black shadow-sm text-brand-400 border border-gray-100 dark:border-gray-600">
                                                YAPE / PLIN
                                            </span>
                                            <span
                                                class="px-3 py-2 bg-white dark:bg-gray-700 rounded-xl text-[10px] font-black shadow-sm text-brand-400 border border-gray-100 dark:border-gray-600">
                                                BCP
                                            </span>
                                        </div>
                                    </div>

                                    {{-- LADO DERECHO: Botón de Finalización Dinámico --}}
                                    <div class="flex flex-col items-center lg:items-end gap-3 wire:poll.10s">

                                        @if ($project->is_paid)
                                            {{-- CASO: PAGO DETECTADO --}}
                                            <p
                                                class="text-[10px] text-green-500 font-black uppercase tracking-widest flex items-center gap-2">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" />
                                                </svg>
                                                Pago verificado correctamente
                                            </p>

                                            {{-- BOTÓN REGENERAR (Ya lo tenías) --}}
                                            <x-common.button-submit type="button" wire:click="confirmFinishSetup"
                                                target="confirmFinishSetup" variant="brand" wire:loading.attr="disabled">
                                                <x-slot:icon>
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/01/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z">
                                                        </path>
                                                    </svg>
                                                </x-slot:icon>
                                                Finalizar Configuración
                                            </x-common.button-submit>
                                        @else
                                            {{-- CASO: PAGO NO DETECTADO --}}
                                            <p
                                                class="text-[10px] text-gray-400 font-bold uppercase tracking-widest flex items-center gap-2">
                                                <span class="relative flex h-2 w-2">
                                                    <span
                                                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                                    <span
                                                        class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                                                </span>
                                                Esperando validación de voucher...
                                            </p>
                                            <div class="flex gap-2">
                                                <x-common.button-submit type="button" wire:click="$refresh"
                                                    variant="secondary"
                                                    class="px-6 py-4 rounded-[1.25rem] text-[10px] font-black uppercase">
                                                    Verificar Pago
                                                </x-common.button-submit>
                                            </div>
                                        @endif

                                    </div>

                                </div>
                            </div>

                            {{-- PIE DE PÁGINA (OPCIONAL: Para reforzar confianza) --}}
                            <div class="flex justify-center gap-6 mt-6 opacity-40">
                                <div class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                                            stroke-width="2" />
                                    </svg>
                                    <span
                                        class="text-[9px] font-bold uppercase tracking-widest text-gray-600 dark:text-gray-300">Transacción
                                        Asistida</span>
                                </div>
                                <div class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2" />
                                    </svg>
                                    <span
                                        class="text-[9px] font-bold uppercase tracking-widest text-gray-600 dark:text-gray-300">Activación
                                        en < 15 min</span>
                                </div>
                            </div>
                        </div>
                    @endif
                </main>
            </div>
        @break

        @case('INFORME DE TESIS')
            {{-- Tu contenido aquí --}}
            <div class="max-w-full mx-auto flex flex-col lg:flex-row min-h-screen">

                {{-- PANEL IZQUIERDO: GUÍA DE PROGRESO --}}
                <aside
                    class=" bg-white dark:bg-gray-900 border-r border-gray-100 dark:border-gray-800 p-8 lg:p-12 flex flex-col justify-between">
                    <div>
                        <div class="mb-12">
                            <p class="text-[10px] font-black text-brand-600 uppercase tracking-[0.3em] mb-2 ">Setup
                                v1.0
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
                                    <span class="text-xs font-black ">01</span>
                                </div>
                                <div class="flex flex-col pt-1">
                                    <span
                                        class="text-[12px] font-black uppercase {{ $step == 1 ? 'text-gray-900 dark:text-white' : 'text-gray-400' }}">
                                        Datos Generales
                                    </span>
                                    <p class="text-[13px] text-gray-400 font-medium mt-1 leading-relaxed">
                                        Definición de proyecto de tesis.
                                    </p>
                                </div>
                            </div>

                            {{-- Paso 02 --}}
                            <div class="relative flex items-start gap-6 mb-4 {{ $step < 2 ? 'opacity-40' : '' }}">
                                <div
                                    class="shrink-0 w-10 h-10 rounded-xl flex items-center justify-center transition-all duration-500 z-10 {{ $step >= 2 ? 'bg-brand-600 text-white shadow-[0_10px_20px_rgba(var(--brand-rgb),0.3)]' : 'bg-gray-100 text-gray-400' }}">
                                    <span class="text-xs font-black">02</span>
                                </div>
                                <div class="flex flex-col pt-1">
                                    <span
                                        class="text-[12px] font-black uppercase {{ $step == 2 ? 'text-gray-900 dark:text-white' : 'text-gray-400' }}">
                                        Estructura Del Proyecto
                                    </span>
                                    <p class="text-[13px] text-gray-400 font-medium mt-1 leading-relaxed">
                                        Define las etapas de tu proyecto.
                                    </p>
                                </div>
                            </div>

                            {{-- Paso 03 --}}
                            {{-- He cambiado opacity-40 fija por una condicional --}}
                            <div
                                class="relative flex items-start gap-6 mb-4 {{ $step < 3 ? 'opacity-40' : '' }} transition-opacity duration-500">
                                <div
                                    class="shrink-0 w-10 h-10 rounded-xl flex items-center justify-center transition-all duration-500 z-10 {{ $step >= 3 ? 'bg-brand-600 text-white shadow-[0_10px_20px_rgba(var(--brand-rgb),0.3)]' : 'bg-gray-100 text-gray-400' }}">
                                    <span class="text-xs font-black">03</span>
                                </div>
                                <div class="flex flex-col pt-1">
                                    <span
                                        class="text-[12px] font-black uppercase {{ $step == 3 ? 'text-gray-900 dark:text-white' : 'text-gray-400' }}">
                                        Finalización
                                    </span>
                                    <p class="text-[13px] text-gray-400 font-medium mt-1 leading-relaxed">
                                        Acceso al editor premium.
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

                            <div x-data="{ source: 'manual' }" class="space-y-8">

                                {{-- Selector de Método (Tabs Minimalistas) --}}
                                <div class="flex p-1 bg-slate-100 dark:bg-slate-800 rounded-2xl w-fit mx-auto shadow-inner">
                                    <button @click="source = 'manual'"
                                        :class="source === 'manual' ? 'bg-white dark:bg-slate-700 shadow-sm text-brand-600' :
                                            'text-slate-500'"
                                        class="px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all">
                                        📝 Manual / Proyecto
                                    </button>
                                    <button @click="source = 'upload'"
                                        :class="source === 'upload' ? 'bg-white dark:bg-slate-700 shadow-sm text-brand-600' :
                                            'text-slate-500'"
                                        class="px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all">
                                        📂 Subir Archivo
                                    </button>
                                </div>

                                <div class="h-[1px] w-full bg-slate-100 dark:bg-slate-800"></div>

                                {{-- OPCIÓN 1: MANUAL / SELECCIÓN --}}
                                <div x-show="source === 'manual'" x-transition class="space-y-6">


                                    {{-- Dropdown de projects Existentes (Si aplica) --}}
                                    <div class="max-w-md mx-auto mb-10">
                                        <x-form.input.select wire:model.live="selected_project_id"
                                            label="Seleccionar Proyecto" for="selected_project_id">
                                            <option value="">Selecciona un proyecto de tesis...</option>
                                            @foreach ($projects as $p)
                                                {{-- Usamos clases para forzar el diseño en la lista --}}
                                                <option value="{{ $p->id }}"
                                                    class="py-4 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200">
                                                    📂 {{ Str::limit($p->title, 80) }} {{-- Limitamos el texto para que no rompa el diseño --}}
                                                </option>
                                            @endforeach
                                        </x-form.input.select>
                                    </div>


                                </div>

                                {{-- OPCIÓN 2: SUBIDA DE ARCHIVO (Layout Dividido) --}}
                                <div x-show="source === 'upload'" x-transition class="max-w-6xl mx-auto">
                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">

                                        {{-- COLUMNA IZQUIERDA: ÁREA DE SUBIDA --}}
                                        {{-- COLUMNA IZQUIERDA: ÁREA DE SUBIDA --}}
                                        <div class="space-y-6">
                                            <div
                                                class="relative group border-2 border-dashed border-slate-300 dark:border-slate-700 rounded-[2.5rem] p-12 text-center hover:border-brand-500 transition-all bg-slate-50/50 dark:bg-slate-900/50 shadow-inner overflow-hidden">


                                                {{-- Input Real Escondido --}}

                                                <input type="file" wire:model="document" @change="loading = true"
                                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                                    accept=".pdf,.doc,.docx">

                                                <div class="space-y-4">
                                                    <div
                                                        class="w-20 h-20 bg-brand-100 dark:bg-brand-500/10 rounded-3xl flex items-center justify-center mx-auto text-brand-600 group-hover:scale-110 transition-transform duration-500">
                                                        <svg class="w-10 h-10" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path
                                                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"
                                                                stroke-width="1.5" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                    </div>

                                                    <div>
                                                        <p
                                                            class="text-sm font-black text-slate-700 dark:text-white uppercase tracking-tighter">
                                                            {{ $document ? '📄 ' . Str::limit($document->getClientOriginalName(), 40) : 'Sube tu proyecto de tesis' }}
                                                        </p>
                                                        <p class="text-[10px] text-slate-400 mt-2 uppercase tracking-[0.2em]">
                                                            PDF, DOCX hasta 10MB</p>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- LISTADO DE PASOS DETECTADOS (Faltaba esto para que el usuario vea el progreso real) --}}
                                            @if ($pasos_detectados)
                                                <div class="grid grid-cols-2 gap-3 animate-fade-in">
                                                    @foreach ($pasos_detectados as $p)
                                                        <div
                                                            class="flex items-center gap-3 p-3 bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 shadow-sm">
                                                            <div class="shrink-0">
                                                                @if ($p['estado'] === 'completado')
                                                                    <span
                                                                        class="flex h-5 w-5 items-center justify-center rounded-full bg-green-100 dark:bg-green-500/20 text-green-600">
                                                                        <svg class="w-3 h-3" fill="currentColor"
                                                                            viewBox="0 0 20 20">
                                                                            <path fill-rule="evenodd"
                                                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                                clip-rule="evenodd" />
                                                                        </svg>
                                                                    </span>
                                                                @else
                                                                    <span
                                                                        class="flex h-5 w-5 items-center justify-center rounded-full bg-slate-100 dark:bg-slate-700 text-slate-400">
                                                                        <svg class="w-3 h-3" fill="none"
                                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round" stroke-width="3"
                                                                                d="M6 18L18 6M6 6l12 12" />
                                                                        </svg>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            <span
                                                                class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase truncate">{{ $p['titulo'] }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif

                                            {{-- Info Bot --}}
                                            <div
                                                class="flex items-start gap-4 p-5 bg-brand-50 dark:bg-brand-500/5 rounded-[2rem] border border-brand-100 dark:border-brand-500/20 shadow-sm">
                                                <span class="text-2xl animate-pulse">🤖</span>
                                                <p
                                                    class="text-[11px] text-brand-800 dark:text-brand-300 font-medium leading-relaxed">
                                                    <strong
                                                        class="uppercase font-black block mb-1 tracking-tighter text-brand-600">Procesamiento
                                                        Inteligente</strong>
                                                    Extraeremos la problemática, objetivos y metodología para pre-llenar tu
                                                    formulario automáticamente.
                                                </p>
                                            </div>
                                        </div>



                                        {{-- COLUMNA DERECHA: RESULTADO IA --}}
                                        <div class="relative h-full flex flex-col max-h-[700px]">
                                            @if ($resumen_ia)
                                                <div
                                                    class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 shadow-2xl flex flex-col overflow-hidden h-full animate-fade-in-up">

                                                    {{-- HEADER FIJO --}}
                                                    <div
                                                        class="bg-slate-50/80 dark:bg-white/5 px-8 py-5 border-b border-slate-100 dark:border-white/5 flex justify-between items-center shrink-0">
                                                        <div class="flex items-center gap-3">
                                                            {{-- Si ya está analizado, el punto es verde fijo, si está cargando, pulsa --}}
                                                            <span
                                                                class="flex h-2 w-2 rounded-full {{ $loading ? 'bg-brand-500 animate-pulse' : 'bg-green-500' }}"></span>
                                                            <h4 class="text-[12px] font-black uppercase text-slate-500">
                                                                {{ $loading ? 'Procesando Documento...' : 'Resumen de Identificación IA' }}
                                                            </h4>
                                                        </div>

                                                        {{-- Badge de "Guardado" --}}
                                                        @if (!$loading)
                                                            <span
                                                                class="text-[9px] bg-green-100 text-green-700 dark:bg-green-500/10 dark:text-green-400 px-2 py-1 rounded-md font-bold uppercase tracking-widest">
                                                                Analizado
                                                            </span>
                                                        @endif
                                                    </div>

                                                    {{-- CONTENIDO SCROLLABLE --}}
                                                    <div class="p-6 overflow-y-auto custom-scrollbar flex-grow bg-white dark:bg-slate-900 space-y-8"
                                                        style="height: 550px">

                                                        {{-- 1. TÍTULO DEL DOCUMENTO --}}
                                                        <div
                                                            class="border-l-4 border-brand-500 pl-4 bg-brand-50/30 dark:bg-brand-500/5 py-2 rounded-r-xl">
                                                            <h3
                                                                class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-1">
                                                                Documento procesado:</h3>
                                                            <p
                                                                class="text-sm font-bold text-slate-800 dark:text-white italic leading-tight">
                                                                {{ $document ? '📄 ' . Str::limit($document->getClientOriginalName(), 100) : '📄 Proyecto guardado en sistema' }}
                                                            </p>
                                                        </div>

                                                        {{-- 2. RESUMEN EJECUTIVO --}}
                                                        <div
                                                            class="bg-slate-50 dark:bg-white/5 rounded-[2rem] p-6 border border-slate-100 dark:border-white/5 shadow-inner">
                                                            <h4
                                                                class="text-[10px] font-black text-brand-600 uppercase tracking-widest mb-4 flex items-center gap-2">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path
                                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                                                        stroke-width="2" />
                                                                </svg>
                                                                Análisis Contextual de la Propuesta
                                                            </h4>
                                                            <div
                                                                class="prose prose-sm dark:prose-invert max-w-none text-slate-600 dark:text-slate-400 leading-relaxed text-[13px] text-justify">
                                                                {!! nl2br(e($resumen_ia)) !!}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- FOOTER FIJO --}}
                                                    <div
                                                        class="p-6 border-t border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/5 shrink-0">
                                                        <x-common.button-submit type="button" wire:click="saveStep1"
                                                            target="nextStep" variant="brand">
                                                            <x-slot:icon>
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                                                </svg>
                                                            </x-slot:icon>
                                                            Siguente Paso
                                                        </x-common.button-submit>
                                                    </div>
                                                </div>
                                            @else
                                                {{-- Estado Vacío (Tu código actual) --}}
                                                <div
                                                    class="h-full border-2 border-dotted border-slate-200 dark:border-slate-800 rounded-[2.5rem] flex flex-col items-center justify-center p-12 text-center opacity-40">
                                                    <span class="text-5xl mb-4">🤖</span>
                                                    <p
                                                        class="text-[11px] font-black uppercase tracking-[0.2em] text-slate-400">
                                                        Esperando análisis de documento...</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <style>
                                    .custom-scrollbar::-webkit-scrollbar {
                                        width: 4px;
                                    }

                                    .custom-scrollbar::-webkit-scrollbar-track {
                                        background: transparent;
                                    }

                                    .custom-scrollbar::-webkit-scrollbar-thumb {
                                        background: #e2e8f0;
                                        border-radius: 10px;
                                    }

                                    .dark .custom-scrollbar::-webkit-scrollbar-thumb {
                                        background: #334155;
                                    }

                                    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
                                        background: #465fff;
                                    }
                                </style>
                            </div>

                            {{-- Botón de Acción --}}
                            <div class="pt-10">
                                <x-common.button-submit type="button" wire:click="saveStep1" target="nextStep"
                                    variant="brand">
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
                        {{-- ETAPA 2: ESTRUCTURA DEL PROYECTO  --}}
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
                            <div class="bg-white p-6 rounded-2xl dark:bg-gray-900 shadow-sm">
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
                                            <h7
                                                class="text-xl font-black text-gray-800 dark:text-white uppercase tracking-tighter">
                                                CAPÍTULOS
                                            </h7>

                                        </div>
                                    </div>
                                    <span class="px-3 py-1 bg-indigo-100 text-indigo-700 text-xs font-bold rounded-full">
                                        {{ count($formatSteps) }} Capítulos
                                    </span>
                                </div>

                                @if (!empty($formatSteps))
                                    {{-- CONTENEDOR CON SCROLL --}}
                                    <div class="pr-2 overflow-y-auto custom-scrollbar" style="max-height: 500px;">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            @foreach ($formatSteps as $index => $fStep)
                                                <div
                                                    class="group flex items-center gap-4 p-4 bg-gray-50 hover:bg-white hover:border-indigo-200 border border-transparent rounded-2xl transition-all shadow-sm hover:shadow-md dark:bg-gray-800">

                                                    {{-- INDICADOR DE ORDEN --}}
                                                    <span
                                                        class="flex-shrink-0 w-10 h-10 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-500 text-sm font-black rounded-xl flex items-center justify-center group-hover:text-indigo-600 group-hover:border-indigo-100 shadow-sm transition-colors">
                                                        {{ $fStep['orden'] }}
                                                    </span>

                                                    <div class="flex-1 min-w-0">
                                                        {{-- INPUT DE TÍTULO --}}
                                                        <x-form.input.text wire:model="formatSteps.{{ $index }}.titulo"
                                                            class="font-bold text-slate-700 dark:text-white"
                                                            :error="$errors->has(
                                                                'formatSteps.' . $index . '.titulo',
                                                            )" />

                                                        {{-- BLOQUE DE INSTRUCCIONES (Lo que te faltaba) --}}

                                                        {{-- SECCIONES COMO BADGES --}}
                                                        @if (isset($fStep['instrucciones']) && $fStep['instrucciones'])
                                                            <div class="flex flex-wrap gap-1 mt-1.5">

                                                                <p class="text-sm text-gray-500 mt-2 font-medium">
                                                                    {{ $fStep['instrucciones'] }}
                                                                </p>

                                                            </div>
                                                        @endif
                                                    </div>

                                                    {{-- ICONO DE EDICIÓN --}}
                                                    <div class="flex-shrink-0 ml-2">
                                                        <svg class="w-5 h-5 text-gray-300 group-hover:text-indigo-500 transition-colors"
                                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                                            </path>
                                                        </svg>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center py-10 border-2 border-dashed border-gray-100 rounded-2xl">
                                        <p class="text-gray-400 text-sm">No se pudo cargar la estructura de la universidad.
                                        </p>
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
        @break

        @default
            <p>No Disponible.</p>
    @endswitch

</div>
