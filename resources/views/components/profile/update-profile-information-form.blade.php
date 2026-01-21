<form action="{{ route('user-profile-information.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    {{-- Cuerpo del Modal con Scroll --}}
    <div class="custom-scrollbar h-auto max-h-[500px] overflow-y-auto p-2">
        <div class="grid grid-cols-1 gap-6">
            
            {{-- Sección de Foto con Validación de 10MB --}}
            <div x-data="{ photoPreview: null, photoError: null }" class="flex flex-col items-center border-b pb-6 dark:border-gray-800">
                <h5 class="mb-4 text-lg font-medium text-gray-800 dark:text-white/90">Foto de Perfil</h5>
                
                <input type="file" name="photo" class="hidden" x-ref="photo"
                       x-on:change="
                           const file = $refs.photo.files[0];
                           if (file) {
                               // Validar si supera los 10MB (10 * 1024 * 1024 bytes)
                               if (file.size > 10485760) {
                                   photoError = 'La imagen es demasiado grande (máximo 10MB).';
                                   photoPreview = null;
                                   $refs.photo.value = ''; // Limpia el input
                                   return;
                               }
                               
                               photoError = null;
                               const reader = new FileReader();
                               reader.onload = (e) => { photoPreview = e.target.result; };
                               reader.readAsDataURL(file);
                           }
                       " />

                {{-- Foto Actual --}}
                <div class="relative" x-show="!photoPreview">
                    <img src="{{ Auth::user()->profile_photo_url }}" class="h-24 w-24 rounded-full object-cover border-2 border-brand-500 shadow-lg">
                </div>

                {{-- Vista Previa Nueva --}}
                <div class="relative" x-show="photoPreview" style="display: none;">
                    <span class="block h-24 w-24 rounded-full bg-cover bg-center bg-no-repeat border-2 border-brand-500 shadow-lg"
                          x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                    </span>
                </div>

                <button type="button" class="mt-4 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-xs font-semibold text-gray-700 dark:text-gray-300"
                        x-on:click.prevent="$refs.photo.click()">
                    Seleccionar Nueva Foto
                </button>

                {{-- Error de tamaño (Cliente) --}}
                <template x-if="photoError">
                    <p class="mt-2 text-sm text-red-600 font-medium" x-text="photoError"></p>
                </template>

                {{-- Error de validación (Servidor) --}}
                @error('photo', 'updateProfileInformation')
                    <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            {{-- Datos Personales --}}
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Nombre</label>
                    <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" 
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white" required />
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Email</label>
                    <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" 
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white" required />
                </div>
            </div>
        </div>
    </div>

    {{-- Botones de Acción --}}
    <div class="flex items-center gap-3 px-2 mt-8 lg:justify-end">
        <button @click="open = false" type="button" class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 sm:w-auto">
            Cerrar
        </button>
        <button type="submit" class="flex w-full justify-center rounded-lg bg-brand-500 px-6 py-2.5 text-sm font-medium text-white hover:bg-brand-600 shadow-md transition-all sm:w-auto">
            Guardar Cambios
        </button>
    </div>
</form>