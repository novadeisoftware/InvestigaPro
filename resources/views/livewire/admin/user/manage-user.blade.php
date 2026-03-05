{{-- Envolvemos TODO el archivo en este div para que 'loading' sea global --}}
<div class="p-8 space-y-6" x-data="{ loading: false }">

    <x-common.title pageTitle="Gestión de Usuarios" />

    {{-- CONTENEDOR PRINCIPAL DE HERRAMIENTAS --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">

        {{-- PARTE IZQUIERDA: Botón Nuevo --}}
        <div class="flex items-center gap-4">
            <x-common.button-submit type="button" @click="loading = true; $dispatch('open-sessions-modal')"
                wire:click="create" target="create" variant="brand">
                <x-slot:icon>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M12 4v16m8-8H4" stroke-width="2" stroke-linecap="round" />
                    </svg>
                </x-slot:icon>
                Nuevo Usuario
            </x-common.button-submit>
        </div>

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



    {{-- TABLE --}}
    <div class="space-y-4">
        {{-- Contenedor Responsivo: Permite scroll lateral en móviles --}}
        <div
            class="overflow-x-auto bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
            <table class="w-full text-left border-collapse min-w-[600px]"> {{-- min-w evita que las columnas se aplasten --}}
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-gray-500 dark:text-gray-400">Usuario</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-gray-500 dark:text-gray-400 text-center">
                            Última conexión</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-gray-500 dark:text-gray-400 text-center">
                            Fecha de Registro</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase text-gray-500 dark:text-gray-400 text-right">
                            Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse ($users as $user)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="shrink-0 w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center text-blue-600 dark:text-blue-400 font-bold text-sm">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div class="min-w-0 {{-- evita que el texto rompa el flex --}}">
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                            {{ $user->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $user->email }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            {{-- Columna: Última Conexión --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    {{-- Indicador de Estado --}}
                                    <div class="flex shrink-0">
                                        @if ($user->last_login_at && $user->last_login_at->diffInMinutes(now()) < 15)
                                            <span class="relative flex h-2 w-2">
                                                <span
                                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                                <span
                                                    class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                                            </span>
                                        @else
                                            <span class="h-2 w-2 rounded-full bg-gray-300 dark:bg-gray-700"></span>
                                        @endif
                                    </div>

                                    <div class="flex flex-col leading-tight">
                                        {{-- Tiempo relativo corto --}}
                                        <span
                                            class="text-[12px] font-bold text-gray-800 dark:text-gray-200 uppercase tracking-tighter">
                                            {{ $user->last_login_at ? $user->last_login_at->diffForHumans(null, true, true) : 'Sin acceso' }}
                                        </span>
                                        {{-- Detalle exacto --}}
                                        <span class="text-[10px] text-gray-400 font-mono tabular-nums">
                                            @if ($user->last_login_at)
                                                {{ $user->last_login_at->format('d/m/Y') }} <span
                                                    class="text-gray-300 dark:text-gray-800">·</span>
                                                {{ $user->last_login_at->format('h:i A') }}
                                            @else
                                                n/a_activity
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </td>

                            {{-- Columna: Fecha de Registro --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    {{-- Icono de Calendario Sutil --}}
                                    <div class="flex shrink-0">
                                        <svg class="w-4 h-4 text-blue-500/70" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                                stroke-width="2" stroke-linecap="round" />
                                        </svg>
                                    </div>

                                    <div class="flex flex-col leading-tight">
                                        {{-- Fecha destacada --}}
                                        <span
                                            class="text-[12px] font-bold text-gray-800 dark:text-gray-200 uppercase tracking-tighter">
                                            {{ $user->created_at->format('d/m/Y') }}
                                        </span>
                                        {{-- Hora y diff relativo --}}
                                        <span
                                            class="text-[10px] text-blue-500/80 dark:text-blue-400/80 font-mono tabular-nums">
                                            {{ $user->created_at->format('h:i A') }} <span
                                                class="text-gray-300 dark:text-gray-800">·</span>
                                            {{ $user->created_at->diffForHumans(null, true, true) }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <div class="flex justify-end gap-1">

                                    {{-- Boton Editar --}}
                                    <button @click="loading = true; $dispatch('open-sessions-modal')"
                                        wire:click="edit({{ $user->id }})"
                                        class="p-2 text-gray-400 hover:text-brand-500 transition-all" title="Editar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"
                                                stroke-width="2" />
                                        </svg>
                                    </button>

                                    {{-- Boton Eliminar --}}
                                    <button wire:click="confirmDelete({{ $user->id }})"
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
                            <td colspan="3" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400 italic">
                                No se encontraron investigadores registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINACIÓN: Solo se muestra si de acuerdo como esta el render Ejm: ->paginate(10) a partir de 10 se muestra --}}
        <div class="mt-4 px-2">
            {{ $users->links() }}
        </div>
    </div>

    {{-- MODAL --}}
    {{-- 
    ESTRUCTURA DEL MODAL - INVESTIGAPRO 
    Control de estados:
    - @open-sessions-modal.window: Escucha el evento global para abrir el modal.
    - @close-modal.window: Resetea el estado de carga y cierra la ventana.
    - @loading-finished.window: Evento disparado desde el Backend (PHP) cuando los datos están listos.
--}}
    <x-ui.modal x-data="{ open: false }" @open-sessions-modal.window="open = true"
        @close-modal.window="open = false; loading = false" @loading-finished.window="loading = false" :isOpen="false"
        class="max-w-[650px]">

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
                            {{ $user_id ? 'Editar Usuario' : 'Nuevo Usuario' }}
                        </h4>
                        <p class="text-sm text-gray-500 mt-1">Complete la información del personal.</p>
                    </div>
                </div>

                <div class="space-y-6">
                    {{-- Grid de Entradas: Nombre, Email y Contraseña --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-left">

                        {{-- Campo Nombre: Conectado a Livewire vía wire:model --}}
                        <div class="col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Nombre y
                                Apellidos</label>
                            <input wire:model="name" type="text" placeholder="Ej. Juan Pérez"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:bg-gray-800 dark:border-gray-700 dark:text-white focus:ring-2 focus:ring-brand-500 outline-none transition-all">
                            @error('name')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Campo Email --}}
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Correo
                                Electrónico</label>
                            <input wire:model="email" type="email" placeholder="correo@ejemplo.com"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:bg-gray-800 dark:border-gray-700 dark:text-white focus:ring-2 focus:ring-brand-500 outline-none">
                            @error('email')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Campo Password: Nota informativa si es edición --}}
                        <div class="col-span-2 md:col-span-1">
                            <label
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Contraseña</label>
                            <input wire:model="password" type="password" placeholder="••••••••"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:bg-gray-800 dark:border-gray-700 dark:text-white focus:ring-2 focus:ring-brand-500 outline-none">
                            @if ($user_id)
                                <p class="text-[10px] text-gray-400 mt-1 italic">Dejar vacío para no cambiar</p>
                            @endif
                            @error('password')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
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
                            {{ $user_id ? 'Actualizar' : 'Registrar' }}
                        </x-common.button-submit>
                    </div>
                </div>
            </div>
        </div>
    </x-ui.modal>

</div>
