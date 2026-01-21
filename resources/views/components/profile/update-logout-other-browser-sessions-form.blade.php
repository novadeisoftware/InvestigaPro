<form action="{{ route('other-browser-sessions.destroy') }}" method="POST">
    @csrf
    @method('DELETE')

    <div class="space-y-6">
        {{-- Cuadro Informativo --}}
        <div class="p-4 rounded-xl border border-blue-100 bg-blue-50/50 dark:border-blue-900/30 dark:bg-blue-500/5">
            <div class="flex gap-3">
                <svg class="text-blue-500 shrink-0" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="16" x2="12" y2="12"></line>
                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                </svg>
                <p class="text-sm text-blue-600 dark:text-blue-400">
                    Si es necesario, puedes cerrar sesión en todos tus otros navegadores en todos tus dispositivos. Si crees que tu cuenta ha sido comprometida, también deberías actualizar tu contraseña.
                </p>
            </div>
        </div>

        {{-- Campo de Contraseña --}}
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">
                Contraseña actual
            </label>
            <input type="password" name="password" required
                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white" 
                placeholder="Ingresa tu contraseña para confirmar" />
            
            @error('password', 'logoutOtherBrowserSessions')
                <p class="mt-2 text-xs text-red-600 font-medium">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Botones de Acción --}}
    <div class="flex items-center gap-3 mt-9 lg:justify-end">
        <button @click="open = false" type="button" 
            class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
            Cancelar
        </button>
        <button type="submit" 
            class="rounded-lg bg-brand-500 px-6 py-2.5 text-sm font-medium text-white shadow-md hover:bg-brand-600 transition-all">
            Cerrar otras sesiones
        </button>
    </div>
</form>