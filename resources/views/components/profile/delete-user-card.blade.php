<div x-data="{ openDeleteModal: {{ $errors->deleteUser->any() ? 'true' : 'false' }} }">
    {{-- Card de Peligro --}}
    <div class="mb-6 rounded-2xl border border-red-100 p-5 lg:p-6 dark:border-red-900/20 bg-red-50/30 dark:bg-red-500/5">
        <div class="flex flex-col gap-5 xl:flex-row xl:items-center xl:justify-between">
            <div class="flex w-full flex-col items-center gap-6 xl:flex-row">
                <div class="flex h-20 w-20 items-center justify-center rounded-full border border-red-100 bg-white dark:border-red-900/30 dark:bg-gray-900">
                    <svg class="text-red-500" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </div>
                <div class="text-center xl:text-left">
                    <h4 class="mb-2 text-lg font-semibold text-red-600 dark:text-red-400">Zona de Peligro</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Elimina tu cuenta permanentemente.</p>
                </div>
            </div>

            <button @click="openDeleteModal = true"
                class="flex w-full items-center justify-center rounded-full bg-red-500 px-6 py-3 text-sm font-medium text-white hover:bg-red-600 shadow-md lg:w-auto transition-all">
                Eliminar Cuenta
            </button>
        </div>
    </div>

    {{-- Modal Manual (Capa sobre todo) --}}
    <div x-show="openDeleteModal" 
         class="fixed inset-0 z-[999] flex items-center justify-center bg-black/60 backdrop-blur-sm"
         x-cloak
         style="display: none;">
        
        <div @click.away="openDeleteModal = false" 
             class="relative w-full max-w-lg rounded-3xl bg-white p-8 dark:bg-gray-900 shadow-2xl">
            
            <h4 class="mb-4 text-2xl font-bold text-gray-800 dark:text-white">Confirmar Identidad</h4>

            <form action="{{ route('user.destroy.manual') }}" method="POST">
                @csrf
                @method('DELETE')

                <div class="space-y-4">
                    {{-- Si hay error de contraseña, se verá aquí --}}
                    @if ($errors->deleteUser->has('password'))
                        <div class="p-3 rounded-lg bg-red-50 text-red-600 text-xs border border-red-100 font-medium">
                            {{ $errors->deleteUser->first('password') }}
                        </div>
                    @endif

                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Por seguridad, ingresa tu contraseña para confirmar que quieres eliminar tu cuenta de InvestigaPro.
                    </p>

                    <input type="password" name="password" required
                        class="h-12 w-full rounded-xl border border-gray-300 bg-transparent px-4 dark:border-gray-700 dark:text-white focus:ring-2 focus:ring-red-500 outline-none" 
                        placeholder="Escribe tu contraseña" />
                </div>

                <div class="flex justify-end gap-3 mt-8">
                    <button @click="openDeleteModal = false" type="button" class="px-4 py-2 text-gray-500 font-medium">
                        Cancelar
                    </button>
                    <button type="submit" class="rounded-xl bg-red-600 px-6 py-2.5 text-sm font-medium text-white hover:bg-red-700 shadow-lg">
                        Confirmar Borrado
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>