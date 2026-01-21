<form action="{{ route('user-password.update') }}" method="POST">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 gap-6">
        {{-- Contraseña Actual --}}
        <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Contraseña Actual</label>
            <input type="password" name="current_password" required
                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
            @if($errors->updatePassword->has('current_password'))
                <p class="mt-1 text-xs text-red-600">{{ $errors->updatePassword->first('current_password') }}</p>
            @endif
        </div>

        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            {{-- Nueva Contraseña --}}
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Nueva Contraseña</label>
                <input type="password" name="password" required
                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                @if($errors->updatePassword->has('password'))
                    <p class="mt-1 text-xs text-red-600">{{ $errors->updatePassword->first('password') }}</p>
                @endif
            </div>

            {{-- Confirmar Contraseña --}}
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Confirmar Contraseña</label>
                <input type="password" name="password_confirmation" required
                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
            </div>
        </div>

        <div class="flex items-center gap-3 mt-8 lg:justify-end">
            <button @click="open = false" type="button"
                class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 sm:w-auto">
                Cerrar
            </button>
            <button type="submit"
                class="flex w-full justify-center rounded-lg bg-brand-500 px-6 py-2.5 text-sm font-medium text-white hover:bg-brand-600 shadow-md transition-all sm:w-auto">
                Actualizar Contraseña
            </button>
        </div>
    </div>
</form>