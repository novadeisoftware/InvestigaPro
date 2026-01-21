<div x-data="{}">
    {{-- Card Visual --}}
    <div class="mb-6 rounded-2xl border border-gray-200 p-5 lg:p-6 dark:border-gray-800 bg-white dark:bg-gray-900">
        <div class="flex flex-col gap-5 xl:flex-row xl:items-center xl:justify-between">
            <div class="flex w-full flex-col items-center gap-6 xl:flex-row">
                {{-- Icono representativo --}}
                <div class="flex h-20 w-20 items-center justify-center rounded-full border border-gray-200 bg-gray-50 dark:border-gray-800 dark:bg-white/[0.03]">
                    <svg class="text-gray-500 dark:text-gray-400" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect>
                        <line x1="12" y1="18" x2="12.01" y2="18"></line>
                    </svg>
                </div>
                
                <div class="order-3 xl:order-2 text-center xl:text-left">
                    <h4 class="mb-2 text-lg font-semibold text-gray-800 dark:text-white/90">
                        Sesiones del Navegador
                    </h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Administra tus sesiones activas en otros navegadores y dispositivos.
                    </p>
                </div>
            </div>

            <button @click="$dispatch('open-sessions-modal')"
                class="shadow-theme-xs flex w-full items-center justify-center gap-2 rounded-full border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 lg:inline-flex lg:w-auto dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                Gestionar Sesiones
            </button>
        </div>
    </div>

    {{-- Modal --}}
    <x-ui.modal x-data="{ open: false }" @open-sessions-modal.window="open = true" :isOpen="false" class="max-w-[700px]">
        <div class="no-scrollbar relative w-full max-w-[700px] overflow-y-auto rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-11">
            <div class="px-2 pr-14 mb-8">
                <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">Sesiones Activas</h4>
            </div>

            {{-- Llamada al componente de formulario --}}
            <x-profile.update-logout-other-browser-sessions-form />
        </div>
    </x-ui.modal>
</div>