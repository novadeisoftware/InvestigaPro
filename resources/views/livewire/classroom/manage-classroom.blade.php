<div class="space-y-8 md:p-6 p-4" x-data="{ loading: false }">
    <x-common.title pageTitle="Mis Aulas Virtuales" />

    {{-- SELECTOR DE ROL (TABS) --}}
    <div
        class="flex p-1.5 bg-gray-100 dark:bg-gray-800/50 rounded-2xl w-fit mb-4 border border-gray-200 dark:border-gray-700">
        <button wire:click="$set('tab', 'my_classrooms')"
            class="px-6 py-2.5 rounded-xl text-[12px] font-black uppercase transition-all {{ $tab === 'my_classrooms' ? 'bg-white dark:bg-gray-700 text-brand-400 shadow-sm' : 'text-gray-400 hover:text-gray-600' }}">
            Aulas que Asesoro
        </button>
        <button wire:click="$set('tab', 'joined_classrooms')"
            class="px-6 py-2.5 rounded-xl text-[11px] font-black uppercase transition-all {{ $tab === 'joined_classrooms' ? 'bg-white dark:bg-gray-700 text-brand-400 shadow-sm' : 'text-gray-400 hover:text-gray-600' }}">
            Mis Clases (Alumno)
        </button>
    </div>

    {{-- ============== BUSCADOR GLOBAL (Para ambas pestañas) ============== --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        @if ($tab === 'my_classrooms')
            <x-common.button-submit type="button" @click="loading = true; $dispatch('open-classroom-modal')"
                wire:click="create" variant="brand" class="rounded-2xl shadow-lg shadow-brand-500/20">
                <x-slot:icon>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M12 4v16m8-8H4" stroke-width="2" stroke-linecap="round" />
                    </svg>
                </x-slot:icon>
                Nueva Aula Virtual
            </x-common.button-submit>
        @else
            <p class="text-sm text-gray-500 mt-2">Aulas donde eres estudiante</p>
        @endif

        <div class="relative w-full md:w-96">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar aula..."
                class="w-full pl-12 pr-4 py-3 bg-white dark:bg-gray-800 border rounded-2xl focus:ring-2 focus:ring-brand-500 dark:text-gray-200 text-sm shadow-sm">
            <div class="absolute left-4 top-3.5 text-gray-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2" />
                </svg>
            </div>
        </div>
    </div>

    {{-- ============== CONTENIDO DINÁMICO ============== --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">
        @if ($tab === 'my_classrooms')
            @forelse ($classrooms as $classroom)
                <div wire:key="my-{{ $classroom->id }}"
                    class="group relative bg-white dark:bg-gray-800 rounded-[2rem] p-6 border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                    {{-- Header Card --}}
                    <div class="flex justify-between items-start mb-5">
                        <div
                            class="px-3 py-1 bg-brand-50 dark:bg-brand-900/30 text-brand-400 text-[10px] font-black uppercase tracking-widest rounded-lg">
                            {{ $classroom->university->siglas }}</div>
                        <span
                            class="px-3 py-1 text-[10px] font-black uppercase rounded-full {{ $classroom->status == 'active' ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-500' }}">{{ $classroom->status }}</span>
                    </div>
                    <div class="mb-5 h-16">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white line-clamp-2">{{ $classroom->name }}
                        </h3>
                        <p class="text-xs text-gray-400 font-medium mt-1 uppercase">{{ $classroom->university->name }}
                        </p>
                    </div>

                    {{-- Link de Invitación --}}
                    <div class="space-y-3 mb-8" x-data="{ copied: false }">
                        <div class="flex justify-between items-end px-1">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Enlace
                                Invitación</span>
                            <button
                                @click="navigator.clipboard.writeText('{{ route('classroom.autojoin', $classroom->invitation_code) }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                class="text-[10px] font-black uppercase transition-all"
                                :class="copied ? 'text-green-600' : 'text-brand-400'">
                                <span x-text="copied ? '¡Copiado!' : 'Copiar Link'"></span>
                            </button>
                        </div>
                        <div
                            class="w-full bg-gray-50 dark:bg-gray-900/50 py-3 rounded-xl border-2 border-dashed border-gray-100 text-center font-mono text-xs text-brand-400 font-bold">
                            {{ $classroom->invitation_code }}
                        </div>
                    </div>
                    {{-- Acciones Asesor --}}
                    {{-- Acciones Asesor --}}
                    <div class="flex flex-wrap items-center gap-2 pt-4 border-t border-gray-50 dark:border-gray-700">

                        {{-- BOTÓN PRINCIPAL: IR AL DASHBOARD DEL AULA --}}
                        <a href="{{ route('classroom.show', $classroom->id) }}"
                            class="flex-1 bg-gray-900 dark:bg-brand-600 text-white py-3 rounded-xl text-[10px] font-black uppercase text-center hover:bg-brand-500 transition-all shadow-md shadow-brand-500/10">
                            Gestionar Alumnos
                        </a>

                        {{-- ESTRATEGIA (Configuración de pasos) --}}
                        <button @click="$dispatch('open-steps-config', { classroomId: {{ $classroom->id }} })"
                            class="p-3 bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-300 rounded-xl hover:text-brand-400 transition-all"
                            title="Configurar Estrategia">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>

                        {{-- EDITAR --}}
                        <button wire:click="edit({{ $classroom->id }})"
                            @click="loading = true; $dispatch('open-classroom-modal')"
                            class="p-3 text-gray-400 hover:text-brand-500 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"
                                    stroke-width="2" />
                            </svg>
                        </button>

                        {{-- ELIMINAR --}}
                        <button wire:click="confirmDelete({{ $classroom->id }})"
                            class="p-3 text-gray-300 hover:text-red-500 transition-all">
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
                    class="col-span-full py-24 bg-white dark:bg-gray-900 rounded-[3rem] border-2 border-dashed border-gray-100 dark:border-gray-800 flex flex-col items-center justify-center text-center animate-fade-in">
                    {{-- Contenedor del Icono --}}
                    <div
                        class="w-20 h-20 bg-brand-50 dark:bg-brand-900/20 rounded-3xl flex items-center justify-center mb-6 shadow-sm">
                       <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M4 5c-1.1046 0-2 .8954-2 2v9c0 1.1046.8954 2 2 2h16c1.1046 0 2-.8954 2-2V7c0-1.1046-.8954-2-2-2H4zm0 2h16v8H4V7zm3 12a1 1 0 0 1 1-1h8a1 1 0 1 1 0 2H8a1 1 0 0 1-1-1z"
                            fill="currentColor"></path>
                    </svg>
                    </div>

                    {{-- Mensaje --}}
                    <div class="max-w-xs">
                        <h3 class="text-sm font-black text-gray-900 dark:text-white uppercase mb-2">
                            Sin aulas activas
                        </h3>
                        <p class="text-[13px] text-gray-400 font-medium leading-relaxed px-4">
                            Aún no has creado aulas para tus alumnos. Comienza creando una para gestionar sus
                            investigaciones.
                        </p>
                    </div>


                </div>
            @endforelse
        @else
            {{-- GRID ALUMNO --}}
            @forelse ($joinedClassrooms as $joined)
                <div wire:key="joined-{{ $joined->id }}"
                    class="bg-white dark:bg-gray-800 rounded-[2.5rem] p-7 border-2 border-brand-50 shadow-xl shadow-brand-500/5 group">
                    <div class="flex justify-between mb-5">
                        <span
                            class="px-3 py-1 bg-brand-600 text-white text-[9px] font-black rounded-lg uppercase tracking-widest shadow-md">
                            Mi Clase
                        </span>
                    </div>

                    <h3
                        class="text-xl font-black text-gray-900 dark:text-white uppercase leading-tight transition-colors">
                        {{ $joined->name }}
                    </h3>

                    <p class="text-xs text-gray-400 mt-2 font-bold">
                        Asesor: {{ $joined->advisor->name }}
                    </p>

                    {{-- En la sección de GRID ALUMNO --}}
                    <div class="mt-8 pt-6 border-t border-gray-50 dark:border-gray-700">
                        <button wire:click="enterSetup({{ $joined->id }})"
                            class="block w-full text-center py-4 bg-gray-900 text-white text-[10px] font-black uppercase  rounded-2xl hover:bg-brand-600 transition-all shadow-xl shadow-gray-900/10">
                            <span wire:loading.remove wire:target="enterSetup({{ $joined->id }})">Entrar a Redactar
                                →</span>
                            <span wire:loading wire:target="enterSetup({{ $joined->id }})">Preparando
                                Tesis...</span>
                        </button>
                    </div>
                </div>
            @empty
                <div
                    class="col-span-full py-24 bg-white dark:bg-gray-900 rounded-[3rem] border-2 border-dashed border-gray-100 dark:border-gray-800 flex flex-col items-center justify-center text-center animate-fade-in">

                    {{-- Contenedor del Icono con Estilo MSHO --}}
                    <div
                        class="w-20 h-20 bg-brand-50 dark:bg-brand-900/20 rounded-[2rem] flex items-center justify-center mb-6 shadow-sm rotate-3 group-hover:rotate-0 transition-transform">
                        <svg class="w-10 h-10 text-brand-600 opacity-80" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>

                    {{-- Mensaje de Invitación --}}
                    <div class="max-w-xs">
                        <h3 class="text-sm font-black text-gray-900 dark:text-white uppercase mb-2">
                            ¿Listo para investigar?
                        </h3>
                        <p class="text-[13px] text-gray-400 font-medium leading-relaxed px-4">
                            Aún no perteneces a ninguna aula virtual. Pide el código a tu asesor para empezar tu
                            investigación
                            hoy mismo.
                        </p>
                    </div>

                    {{-- Botón de Acción para el Alumno 
                    <button wire:click="openJoinModal"
                        class="mt-8 flex items-center gap-2 px-8 py-3 bg-brand-600 hover:bg-brand-700 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all shadow-lg shadow-brand-500/30 active:scale-95 group">
                        <span class="group-hover:translate-x-1 transition-transform">🚀</span>
                        Unirme a un aula
                    </button> --}}
                </div>
            @endforelse
        @endif
    </div>

    {{-- PAGINACIÓN --}}
    <div class="mt-8">
        {{ $tab === 'my_classrooms' ? $classrooms->links() : $joinedClassrooms->links() }}
    </div>

    {{-- MODAL --}}
    <x-ui.modal x-data="{ open: false }" @open-classroom-modal.window="open = true"
        @close-modal.window="open = false; loading = false" @loading-finished.window="loading = false"
        class="max-w-[650px]">
        <div
            class="no-scrollbar relative w-full overflow-y-auto rounded-[2.5rem] bg-white p-8 dark:bg-gray-900 lg:p-12 min-h-[450px]">
            <x-common.loader x-show="loading" text="Sincronizando..." />
            <div x-show="open && !loading" x-cloak>
                <div class="mb-10 text-center md:text-left">
                    <h4 class="text-3xl font-black text-gray-900 dark:text-white">
                        {{ $classroom_id ? 'Ajustes del Aula' : 'Nueva Aula' }}
                    </h4>
                    <p class="text-sm text-gray-500 mt-2">Configura los detalles de tu aula.</p>
                </div>
                <div class="space-y-6">

                    {{-- Nombre del Aula --}}
                    <div class="space-y-1.5"> {{-- Un pequeño espacio constante entre elementos --}}
                        {{-- Caso con Error (Automático) --}}
                        <x-form.input.text label="Nombre del Aula" wire:model="name" for="name"
                            :error="$errors->has('name')" />
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
                        <x-form.input.select label="Tipo de Documento a desarrollar" wire:model.live="document_type"
                            for="document_type" :error="$errors->has('document_type')">
                            <option value="" class="dark:bg-gray-900">Seleccionar Tipo de Documento...</option>
                            <option value="PROYECTO DE TESIS">Proyecto de Tesis</option>
                            <option value="INFORME FINAL">Informe Final</option>
                        </x-form.input.select>
                    </div>

                    @if ($classroom_id)
                        <x-form.input.select label="Estado" wire:model="status" for="status" :error="$errors->has('status')">
                            <option value="active">Activa</option>
                            <option value="inactive">Inactiva</option>
                        </x-form.input.select>
                    @endif




                </div>
                <div class="flex items-center justify-end gap-4 pt-10 mt-6">
                    <button type="button" @click="open = false"
                        class="text-sm font-bold text-gray-400">Cancelar</button>
                    <x-common.button-submit wire:click="store" target="store" variant="brand"
                        class="rounded-2xl px-10 py-4 shadow-xl">
                        {{ $classroom_id ? 'Actualizar' : 'Registrar' }}
                    </x-common.button-submit>
                </div>
            </div>
        </div>
    </x-ui.modal>

    @livewire('classroom.manage-classroom-steps')
</div>
