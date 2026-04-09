<div class="p-8 space-y-8" x-data="{ loading: false }">
    <x-common.title pageTitle="Mis Proyectos" />


    {{-- CABECERA: Botón y Buscador --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <x-common.button-submit type="button" @click="loading = true; $dispatch('open-project-modal')" wire:click="create"
            variant="brand" class="rounded-2xl shadow-lg shadow-brand-500/20">
            <x-slot:icon>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M12 4v16m8-8H4" stroke-width="2" stroke-linecap="round" />
                </svg>
            </x-slot:icon>
            Nuevo Proyecto
        </x-common.button-submit>

        <div class="relative w-full md:w-96">
            <input wire:model.live.debounce.300ms="search" type="text"
                placeholder="Buscar tesis por título o universidad..."
                class="w-full pl-12 pr-4 py-3 bg-white dark:bg-gray-800 border rounded-2xl focus:ring-2 focus:ring-brand-500 dark:text-gray-200 text-sm shadow-sm">
            <div class="absolute left-4 top-3.5 text-gray-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2" />
                </svg>
            </div>
        </div>
    </div>

    {{-- GRID DE PROYECTOS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($projects as $project)
            <div
                class="group relative bg-white dark:bg-gray-800 rounded-[2rem] p-6 border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1">

                {{-- Header de la Card: Universidad y Estado --}}
                <div class="flex justify-between items-start mb-5">
                    <div
                        class="w-12 h-12 rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-100 dark:border-gray-700 p-2 shadow-inner">
                        <img src="{{ asset('storage/' . $project->university->logo_path) }}"
                            class="w-full h-full object-contain">
                    </div>

                    <div class="flex flex-col items-end gap-2">
                        {{-- Badge de Estado Académico --}}
                        <span
                            class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-full 
            {{ $project->academic_status == 'approved' ? 'bg-green-100 text-green-600' : 'bg-brand-50 text-brand-600' }}">
                            {{ $project->academic_status == 'draft' ? 'Borrador' : ($project->academic_status == 'approved' ? 'Aprobado' : $project->academic_status) }}
                        </span>

                        {{-- NUEVO: Badge de Estado de Pago --}}
                        @if ($project->payment_status !== 'paid')
                            <span
                                class="flex items-center gap-1 px-2 py-1 bg-amber-100 text-amber-700 text-[9px] font-bold uppercase rounded-lg animate-pulse">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" />
                                </svg>
                                Activación Requerida
                            </span>
                        @else
                            <span class="text-[9px] text-green-500 font-bold uppercase flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M5 13l4 4L19 7" stroke-width="3" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                Proyecto Activo
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Título --}}
                <div class="mb-5 h-16">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white line-clamp-2  transition-colors">
                        {{ $project->title }}
                    </h3>
                    <p class="text-xs text-gray-400 font-medium mt-1 uppercase">{{ $project->university->siglas }} •
                        {{ str_replace('_', ' ', $project->document_type) }}</p>
                </div>

                {{-- Barra de Progreso de IA --}}
                <div class="space-y-2 mb-6">
                    <div class="flex justify-between text-[10px] font-bold uppercase tracking-tighter text-gray-400">
                        <span>Consumo de IA</span>
                        <span>{{ number_format($project->ai_words_used) }} /
                            {{ number_format($project->ai_word_limit) }} palabras</span>
                    </div>
                    <div class="w-full bg-gray-100 dark:bg-gray-700 h-2 rounded-full overflow-hidden">
                        <div class="bg-brand-500 h-full transition-all duration-700"
                            style="width: {{ ($project->ai_words_used / $project->ai_word_limit) * 100 }}%"></div>
                    </div>
                </div>

                {{-- Acciones Inferiores --}}
                <div class="flex items-center gap-2 pt-4 border-t border-gray-50 dark:border-gray-700">

                    @switch($project->document_type)
                        @case('PROYECTO DE TESIS')
                            @if ($project->setup_step < 5)
                                {{-- Si no ha terminado, el botón lo lleva al Wizard --}}
                                <a href="{{ route('projects.setup', $project->uuid) }}"
                                    class="flex-1 bg-amber-600 hover:bg-amber-600  text-white text-center py-3 rounded-xl text-xs font-bold transition-all shadow-sm">
                                    Configuración Pendiente
                                </a>
                            @else
                                {{-- Si ya terminó, va al editor normal --}}
                                <a href="{{ route('projects.show', $project) }}"
                                    class="flex-1 bg-gray-900 dark:bg-brand-600 hover:bg-brand-600 dark:hover:bg-brand-700 text-white text-center py-3 rounded-xl text-xs font-bold transition-all shadow-sm">
                                    Continuar Redacción
                                </a>
                            @endif
                        @break

                        @case('INFORME DE TESIS')
                            @if ($project->setup_step < 4)
                                {{-- Si no ha terminado, el botón lo lleva al Wizard --}}
                                <a href="{{ route('projects.setup', $project->uuid) }}"
                                    class="flex-1 bg-amber-600 hover:bg-amber-600  text-white text-center py-3 rounded-xl text-xs font-bold transition-all shadow-sm">
                                    Configuración Pendiente
                                </a>
                            @else
                                {{-- Si ya terminó, va al editor normal --}}
                                <a href="{{ route('projects.show', $project) }}"
                                    class="flex-1 bg-gray-900 dark:bg-brand-600 hover:bg-brand-600 dark:hover:bg-brand-700 text-white text-center py-3 rounded-xl text-xs font-bold transition-all shadow-sm">
                                    Continuar Redacción
                                </a>
                            @endif
                        @break

                        @default
                    @endswitch



                    <button wire:click="edit({{ $project->id }})"
                        @click="loading = true; $dispatch('open-project-modal')"
                        class="p-3 text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-xl transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"
                                stroke-width="2" />
                        </svg>
                    </button>

                    <button wire:click="confirmDelete({{ $project->id }})"
                        class="p-3 text-gray-300 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                stroke-width="2" />
                        </svg>
                    </button>
                </div>
            </div>
            @empty
                <div
                    class="col-span-full py-20 bg-white dark:bg-gray-800 rounded-[2rem] border border-dashed border-gray-200 dark:border-gray-700 flex flex-col items-center">
                    <div class="w-20 h-20 bg-gray-50 dark:bg-gray-900 rounded-3xl flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                stroke-width="2" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">No tienes proyectos aún</h3>
                    <p class="text-sm text-gray-500 max-w-xs text-center mt-2">Comienza creando tu proyecto de tesis con el
                        formato oficial de tu universidad.</p>
                </div>
            @endforelse
        </div>

        {{-- PAGINACIÓN --}}
        <div class="mt-8">
            {{ $projects->links() }}
        </div>

        {{-- MODAL (Adaptado para Proyectos) --}}
        <x-ui.modal x-data="{ open: false }" @open-project-modal.window="open = true"
            @close-modal.window="open = false; loading = false" @loading-finished.window="loading = false"
            class="max-w-[650px]">

            <div
                class="no-scrollbar relative w-full overflow-y-auto rounded-[2.5rem] bg-white p-8 dark:bg-gray-900 lg:p-12 min-h-[450px]">
                <x-common.loader x-show="loading" text="Preparando estructura académica..." />

                <div x-show="open && !loading" x-cloak x-transition:enter.duration.400ms>
                    <div class="mb-10 text-center md:text-left">
                        <h4 class="text-3xl font-black text-gray-900 dark:text-white">
                            {{ $project_id ? 'Ajustes del Proyecto' : 'Nueva Investigación' }}
                        </h4>
                        <p class="text-sm text-gray-500 mt-2">Configura los detalles base de tu investigación.</p>
                    </div>

                    <div class="space-y-6">
                        {{-- Título del Proyecto --}}
                        <div class="space-y-1.5"> {{-- Un pequeño espacio constante entre elementos --}}
                            {{-- Caso con Error (Automático) --}}
                            <x-form.input.text label="Identificador de Proyecto" wire:model="title" for="title"
                                :error="$errors->has('title')" />
                        </div>

                        {{-- Universidad --}}
                        <div class="space-y-1.5"> {{-- Un pequeño espacio constante entre elementos --}}

                            <x-form.input.select label="Universidad" wire:model="university_id" for="university_id"
                                :error="$errors->has('university_id')">
                                <option value="" class="dark:bg-gray-900">Seleccionar Universidad...</option>
                                @foreach ($universities as $uni)
                                    <option value="{{ $uni->id }}" class="dark:bg-gray-900">{{ $uni->nombre }}
                                    </option>
                                @endforeach
                            </x-form.input.select>

                        </div>

                        {{-- Tipo de Documento --}}
                        <div class="space-y-1.5">
                            <x-form.input.select label="Tipo de Documento" wire:model.live="document_type"
                                for="document_type" :error="$errors->has('document_type')">
                                <option value="" class="dark:bg-gray-900">Seleccionar Tipo de Documento...</option>
                                <option value="PROYECTO DE TESIS">Proyecto de Tesis</option>
                                <option value="INFORME DE TESIS">Informe Final</option>
                            </x-form.input.select>
                        </div>

                        @if ($document_type === 'PROYECTO DE TESIS')
                            {{-- Facultad --}}
                            <div class="space-y-1.5">
                                <x-form.input.text label="Facultad" wire:model="faculty" for="faculty"
                                    :error="$errors->has('faculty')" />
                            </div>

                            {{-- Escuela --}}
                            <div class="space-y-1.5">
                                <x-form.input.text label="Escuela" wire:model="school" for="school"
                                    :error="$errors->has('school')" />
                            </div>

                            {{-- Linea de Investigación --}}
                            <div class="space-y-1.5">
                                <x-form.input.text label="Línea de Investigación" wire:model="academic_line"
                                    for="academic_line" :error="$errors->has('academic_line')" />
                            </div>
                        @endif


                    </div>

                    <div class="flex items-center justify-end gap-4 pt-10 mt-6">
                        <button type="button" @click="open = false"
                            class="text-sm font-bold text-gray-400 hover:text-gray-600 transition-colors">Cancelar</button>
                        <x-common.button-submit wire:click="store" target="store" variant="brand"
                            class="rounded-2xl px-10 py-4 shadow-xl shadow-brand-500/30">
                            {{ $project_id ? 'Actualizar' : 'Crear' }}
                        </x-common.button-submit>
                    </div>
                </div>
            </div>
        </x-ui.modal>

    </div>
