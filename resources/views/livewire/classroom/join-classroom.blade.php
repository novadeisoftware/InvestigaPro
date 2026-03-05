<div class="max-w-md mx-auto p-6 bg-white dark:bg-gray-900 rounded-[2rem] shadow-xl border border-gray-100 dark:border-gray-800">
    <div class="text-center mb-6">
        <h3 class="text-2xl font-black text-gray-900 dark:text-white">Unirse a un Aula</h3>
        <p class="text-sm text-gray-500">Ingresa el código que te proporcionó tu asesor de tesis.</p>
    </div>

    <form wire:submit.prevent="join" class="space-y-4">
        <div>
            <input wire:model="code" type="text" 
                placeholder="Ej: ABC-123"
                class="w-full px-5 py-4 text-center text-xl font-black tracking-widest uppercase rounded-2xl border-2 border-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:border-brand-500 focus:ring-0 transition-all">
            @error('code') <span class="text-red-500 text-xs mt-1 block px-2">{{ $message }}</span> @enderror
        </div>

        <x-common.button-submit target="join" variant="brand" class="w-full py-4 rounded-2xl shadow-lg shadow-brand-500/30">
            Unirme ahora
        </x-common.button-submit>
    </form>
</div>