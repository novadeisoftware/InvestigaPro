<div x-data="{}">
    {{-- Card de Seguridad (Idéntica a la de Información Personal) --}}
    <div class="mb-6 rounded-2xl border border-gray-200 p-5 lg:p-6 dark:border-gray-800 bg-white dark:bg-gray-900">
        <div class="flex flex-col gap-5 xl:flex-row xl:items-center xl:justify-between">
            <div class="flex w-full flex-col items-center gap-6 xl:flex-row">
                {{-- Icono de Seguridad --}}
                <div
                    class="flex h-20 w-20 items-center justify-center rounded-full border border-gray-200 bg-gray-50 dark:border-gray-800 dark:bg-white/[0.03]">
                    <svg class="text-gray-500 dark:text-gray-400" width="32" height="32" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                </div>

                <div class="order-3 xl:order-2">
                    <h4 class="mb-2 text-center text-lg font-semibold text-gray-800 xl:text-left dark:text-white/90">
                        Seguridad de la Cuenta
                    </h4>
                    <div class="flex flex-col items-center gap-1 text-center xl:flex-row xl:gap-3 xl:text-left">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Contraseña: ••••••••••••
                        </p>
                        <div class="hidden h-3.5 w-px bg-gray-300 xl:block dark:bg-gray-700"></div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Actualizado {{ Auth::user()->updated_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
            </div>

            <button @click="$dispatch('open-password-modal')"
                class="shadow-theme-xs flex w-full items-center justify-center gap-2 rounded-full border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-800 lg:inline-flex lg:w-auto dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200">
                <svg class="fill-current" width="18" height="18" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M10 2C6.68629 2 4 4.68629 4 8V10H3C2.44772 10 2 10.4477 2 11V17C2 17.5523 2.44772 18 3 18H17C17.5523 18 18 17.5523 18 17V11C18 10.4477 17.5523 10 17 10H16V8C16 4.68629 13.3137 2 10 2ZM14 10V8C14 5.79086 12.2091 4 10 4C7.79086 4 6 5.79086 6 8V10H14ZM4 12V16H16V12H4Z"
                        fill="currentColor" />
                </svg>
                Actualizar
            </button>
        </div>
    </div>

    <x-ui.modal x-data="{ open: false }" @open-password-modal.window="open = true" :isOpen="false"
        class="max-w-[700px]">
        <div
            class="no-scrollbar relative w-full max-w-[700px] overflow-y-auto rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-11">

            <div class="px-2 pr-14 mb-6">
                <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">
                    Cambiar Contraseña
                </h4>
            </div>

            {{-- LLAMADA CORRECTA AL COMPONENTE DE BLADE --}}
            <x-profile.update-password-form />

        </div>
    </x-ui.modal>
</div>
