<div class="p-8 space-y-6" x-data="{ loading: false }">
    <x-common.title pageTitle="Universidades" />

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">

        {{-- PARTE IZQUIERDA: Botón Nuevo --}}
        <x-common.button-submit type="button" @click="loading = true; $dispatch('open-uni-modal')" wire:click="create"
            variant="brand">
            <x-slot:icon>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M12 4v16m8-8H4" stroke-width="2" stroke-linecap="round" />
                </svg>
            </x-slot:icon>
            Nueva Universidad
        </x-common.button-submit>


        {{-- PARTE DERECHA: Buscador --}}
        <div class="relative w-full md:w-80">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar por nombre o correo..."
                class="w-full pl-10 pr-4 py-2.5 bg-gray-50 dark:bg-gray-700 border-none rounded-lg focus:ring-2 focus:ring-brand-500 dark:text-gray-200 text-sm transition-all shadow-inner">
            <div class="absolute left-3 top-3 text-gray-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2" />
                </svg>
            </div>
        </div>

    </div>

    {{-- TABLE: Gestión de Universidades --}}
    <div class="space-y-4">
        <div
            class="overflow-x-auto bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
            <table class="w-full text-left border-collapse min-w-[600px]">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-gray-500 dark:text-gray-400">Universidad
                        </th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-gray-500 dark:text-gray-400 text-center">
                            Siglas</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-gray-500 dark:text-gray-400 text-center">
                            Configuración</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-gray-500 dark:text-gray-400 text-center">
                            Registro</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-gray-500 dark:text-gray-400 text-right">
                            Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse ($universities as $uni)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                            {{-- Columna: Logo y Nombre --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    {{-- Contenedor con Aspect Ratio cuadrado y bordes redondeados tipo App Icon --}}
                                    <div
                                        class="shrink-0 w-14 h-14 rounded-2xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-sm flex items-center justify-center p-1.5 transition-all hover:shadow-md">
                                        @if ($uni->logo_path)
                                            <img src="{{ asset('storage/' . $uni->logo_path) }}"
                                                alt="Logo {{ $uni->siglas }}"
                                                class="w-full h-full object-contain object-center">
                                        @else
                                            <div class="flex flex-col items-center justify-center opacity-40">
                                                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v12a2 2 0 002 2z"
                                                        stroke-width="2" />
                                                </svg>
                                                <span
                                                    class="text-[8px] font-black uppercase tracking-tighter">S/L</span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="min-w-0 flex flex-col justify-center">
                                        <p
                                            class="text-sm font-bold text-gray-900 dark:text-white leading-tight truncate group-hover:text-brand-600 transition-colors">
                                            {{ $uni->nombre }}
                                        </p>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span
                                                class="inline-flex items-center px-1.5 py-0.5 rounded-md text-[10px] font-medium bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400">
                                                ID #{{ $uni->id }}
                                            </span>
                                            <span
                                                class="text-[10px] font-black text-brand-500/80 uppercase tracking-widest">
                                                {{ $uni->siglas }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Columna: Siglas --}}
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                <span
                                    class="px-3 py-1 text-[11px] font-black bg-brand-50 dark:bg-brand-900/20 text-brand-600 dark:text-brand-400 rounded-lg uppercase tracking-wider">
                                    {{ $uni->siglas }}
                                </span>
                            </td>

                            {{-- Columna: Estado de Reglas (JSON) --}}
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                <div class="flex flex-col items-center leading-tight">
                                    @if ($uni->reglas_json)
                                        <span
                                            class="text-[10px] font-bold text-green-600 dark:text-green-400 uppercase tracking-tighter flex items-center gap-1">
                                            <div class="h-1.5 w-1.5 rounded-full bg-green-500"></div> Configurada
                                        </span>
                                    @else
                                        <span
                                            class="text-[10px] font-bold text-amber-600 dark:text-amber-400 uppercase tracking-tighter flex items-center gap-1">
                                            <div class="h-1.5 w-1.5 rounded-full bg-amber-500 animate-pulse"></div>
                                            Pendiente
                                        </span>
                                    @endif
                                </div>
                            </td>

                            {{-- Columna: Fecha de Registro --}}
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex flex-col leading-tight">
                                    <span
                                        class="text-[12px] font-bold text-gray-800 dark:text-gray-200 uppercase tracking-tighter">
                                        {{ $uni->created_at->format('d/m/Y') }}
                                    </span>
                                    <span class="text-[10px] text-gray-400 font-mono tabular-nums">
                                        {{ $uni->created_at->diffForHumans(null, true, true) }}
                                    </span>
                                </div>
                            </td>

                            {{-- Acciones --}}
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <div class="flex justify-end gap-1">
                                    <button @click="loading = true; $dispatch('open-uni-modal')"
                                        wire:click="edit({{ $uni->id }})"
                                        class="p-2 text-gray-400 hover:text-brand-500 transition-all" title="Editar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"
                                                stroke-width="2" />
                                        </svg>
                                    </button>

                                    <button wire:click="confirmDelete({{ $uni->id }})"
                                        class="p-2 text-gray-400 hover:text-red-500 transition-all" title="Eliminar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-200 mb-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1"
                                            stroke-width="2" stroke-linecap="round" />
                                    </svg>
                                    <p class="text-gray-500 dark:text-gray-400 italic text-sm">No hay universidades
                                        registradas en el sistema.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINACIÓN --}}
        <div class="mt-4 px-2">
            {{ $universities->links() }}
        </div>
    </div>

    {{-- MODAL --}}
    <x-ui.modal x-data="{ open: false }" @open-uni-modal.window="open = true"
        @close-modal.window="open = false; loading = false" @loading-finished.window="loading = false"
        :isOpen="false" class="max-w-[650px]">

        <div
            class="no-scrollbar relative w-full overflow-y-auto rounded-3xl bg-white p-6 dark:bg-gray-900 lg:p-10 min-h-[420px]">

            {{-- 
            1. COMPONENTE LOADER (OVERLAY) 
            - Se activa mediante la variable global 'loading' de Alpine.
            - Al ser 'absolute', cubre todo el contenido interno del modal mientras los datos se sincronizan.
        --}}
            <x-common.loader x-show="loading" text="" />

            {{-- 
            2. CONTENIDO DEL FORMULARIO 
            - x-show="open && !loading": Solo se renderiza si el modal está abierto Y la carga terminó.
            - x-cloak: Evita que el formulario sea visible antes de que Alpine.js tome el control.
            - x-transition: Crea un efecto de fundido suave al aparecer el formulario cargado.
        --}}
            <div x-show="open && !loading" x-cloak x-transition:enter.duration.400ms>

                {{-- Cabecera: Cambia dinámicamente según si existe un ID de usuario --}}
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h4 class="text-2xl font-bold text-gray-800 dark:text-white/90">
                            {{ $university_id ? 'Editar Universidad' : 'Nueva Universidad' }}
                        </h4>
                        <p class="text-sm text-gray-500 mt-1">Complete la información de la entidad.</p>
                    </div>
                </div>

                <div class="space-y-6">
                    {{-- Grid: Nombre y Siglas --}}
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-5 text-left">

                        {{-- Nombre de la Universidad --}}
                        <div class="col-span-1 md:col-span-3">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Nombre
                                Institucional</label>
                            <input wire:model="nombre" type="text" placeholder="Ej. Universidad César Vallejo"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:bg-gray-800 dark:border-gray-700 dark:text-white focus:ring-2 focus:ring-brand-500 outline-none transition-all">
                            @error('nombre')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Siglas --}}
                        <div class="col-span-1">
                            <label
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Siglas</label>
                            <input wire:model="siglas" type="text" placeholder="Ej. UCV"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:bg-gray-800 dark:border-gray-700 dark:text-white focus:ring-2 focus:ring-brand-500 outline-none transition-all">
                            @error('siglas')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Sección de Logo (Carga de Archivo) --}}
                        <div class="col-span-4">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Logo
                                Oficial</label>
                            <div
                                class="flex flex-col md:flex-row items-center gap-5 p-4 rounded-2xl border-2 border-dashed border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">

                                {{-- Previsualización --}}
                                <div class="shrink-0">
                                    <div
                                        class="relative w-24 h-24 rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 overflow-hidden flex items-center justify-center p-2 shadow-sm">
                                        @if ($logo)
                                            <img src="{{ $logo->temporaryUrl() }}"
                                                class="w-full h-full object-contain">
                                        @elseif ($logo_path)
                                            <img src="{{ asset('storage/' . $logo_path) }}"
                                                class="w-full h-full object-contain">
                                        @else
                                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                                    stroke-width="2" />
                                            </svg>
                                        @endif

                                        {{-- Loading State para la subida del archivo --}}
                                        <div wire:loading wire:target="logo"
                                            class="absolute inset-0 bg-white/80 dark:bg-gray-900/80 flex items-center justify-center">
                                            <div
                                                class="w-5 h-5 border-2 border-brand-500 border-t-transparent rounded-full animate-spin">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Input de Archivo --}}
                                <div class="flex-1 text-center md:text-left">
                                    <input type="file" wire:model="logo" id="upload-logo" class="hidden">
                                    <label for="upload-logo"
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-xs font-bold text-gray-700 dark:text-gray-200 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-600 transition-all shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"
                                                stroke-width="2" />
                                        </svg>
                                        Seleccionar Imagen
                                    </label>
                                    <p class="text-[10px] text-gray-400 mt-2 italic">Recomendado: PNG fondo
                                        transparente (Max. 1MB)</p>
                                </div>
                            </div>
                            @error('logo')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>


                {{-- Pie del Modal: Botones de Acción --}}
                <div
                    class="flex items-center justify-end gap-3 pt-8 mt-4 border-t border-gray-100 dark:border-gray-800">
                    {{-- Botón Cancelar: Solo manipula el estado local de Alpine --}}
                    <button type="button" @click="open = false"
                        class="px-6 py-3 text-sm font-medium text-gray-500 hover:text-gray-800 dark:text-gray-400 transition-colors">
                        Cancelar
                    </button>

                    {{-- 
                        BOTÓN SUBMIT COMPONENTE 
                        - wire:click="store": Dispara la función de guardado en el servidor.
                        - target="store": Vincula el spinner interno a este proceso específico.
                        - showSpinner: Habilita el feedback visual de carga en el botón.
                    --}}
                    <x-common.button-submit wire:click="store" target="store" variant="brand" :showSpinner="true">
                        {{ $university_id ? 'Actualizar' : 'Guardar' }}
                    </x-common.button-submit>
                </div>
            </div>
        </div>
    </x-ui.modal>

</div>
