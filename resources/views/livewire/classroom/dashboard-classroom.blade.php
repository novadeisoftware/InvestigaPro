<div class="p-8 max-w-full mx-auto space-y-8 animate-fade-in">

    {{-- Navegación Estilo Breadcrumb Premium --}}
    <nav class="flex items-center gap-4 text-xs font-black uppercase tracking-widest italic">
        <a href="{{ route('classroom') }}"
            class="text-gray-400 hover:text-brand-400 transition-colors flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                    stroke-width="2.5" />
            </svg>
            Mis Aulas
        </a>
        <span class="text-gray-300">/</span>
        <span class="text-brand-400 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                    stroke-width="2.5" />
            </svg>
            Panel de Control: {{ $classroom->name }}
        </span>
    </nav>

    {{-- Hero Section Reducido --}}
    <div
        class="relative overflow-hidden bg-white dark:bg-gray-800 rounded-[2.5rem] p-8 border border-gray-100 dark:border-gray-700 shadow-xl shadow-brand-500/5">
        <div class="relative flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-6">
                <div
                    class="w-16 h-16 bg-brand-400 rounded-3xl flex items-center justify-center text-white shadow-lg shadow-brand-400/20">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                            stroke-width="1.5" />
                    </svg>
                </div>
                <div>
                    <h2
                        class="text-3xl font-black text-gray-900 dark:text-white uppercase italic tracking-tighter leading-none">
                        Gestión <span class="text-brand-400">Pro</span>
                    </h2>
                    <p class="text-gray-400 text-[9px] font-bold uppercase mt-1 tracking-widest leading-none">
                        {{ $classroom->university->siglas }} • {{ $students->total() }} Investigadores
                    </p>
                </div>
            </div>

            {{-- Invitation Code Compact --}}
            <div class="flex items-center gap-4 bg-gray-50 dark:bg-gray-900 px-6 py-3 rounded-2xl border border-gray-100 dark:border-gray-700 group cursor-pointer"
                @click="navigator.clipboard.writeText('{{ $classroom->invitation_code }}'); $dispatch('swal', {title: 'Código Copiado', icon: 'success', type: 'toast'})">
                <div class="text-right">
                    <span
                        class="text-[7px] font-black text-gray-400 uppercase block tracking-tighter leading-none italic">Click
                        para copiar</span>
                    <span
                        class="text-xl font-mono font-black text-brand-400 leading-none tracking-tight">{{ $classroom->invitation_code }}</span>
                </div>
                <div
                    class="p-2 bg-white dark:bg-gray-800 rounded-lg text-gray-400 group-hover:text-brand-400 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"
                            stroke-width="2" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
    {{-- Buscador y Filtros Rápidos --}}
    <div class="flex flex-col md:flex-row gap-4 items-center">
        <div class="relative w-full md:flex-1">
            <input wire:model.live.debounce.300ms="search" type="text"
                placeholder="Escribe el nombre del investigador o su correo..."
                class="w-full pl-14 pr-4 py-5 bg-white dark:bg-gray-800 border-none rounded-[2rem] focus:ring-2 focus:ring-brand-400 dark:text-gray-200 text-sm shadow-xl shadow-gray-200/50 dark:shadow-none italic font-medium">
            <div class="absolute left-5 top-5 text-brand-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="3" />
                </svg>
            </div>
        </div>
        {{-- <div class="flex gap-2">
            <button
                class="px-6 py-5 bg-white dark:bg-gray-800 text-gray-400 rounded-[1.5rem] text-[10px] font-black uppercase tracking-widest hover:text-brand-400 transition-all shadow-md">Exportar
                Notas</button>
        </div> --}}
    </div>

    {{-- Lista de Alumnos Estilo Kanban/List --}}
    <div class="grid grid-cols-1 gap-6">
        @forelse($students as $student)
            @php
                $project = $student->projects->first();
                $percent =
                    $project && $project->steps_count > 0
                        ? ($project->completed_steps / $project->steps_count) * 100
                        : 0;
            @endphp

            <div
                class="group relative bg-white dark:bg-gray-800 rounded-[2.5rem] p-8 border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-2xl hover:border-brand-400/30 transition-all duration-500 overflow-hidden">

                {{-- Decoración de Card --}}
                <div
                    class="absolute top-0 right-0 w-32 h-32 bg-brand-400/5 rounded-full -mr-16 -mt-16 group-hover:bg-brand-400/10 transition-colors">
                </div>

                <div class="relative flex flex-col md:flex-row items-center gap-8">
                    {{-- Perfil del Alumno con Foto de Jetstream --}}
                    <div class="flex items-center gap-6 w-full md:w-1/3">
                        <div class="relative">
                            {{-- Contenedor de la Foto con Estilo MSHO --}}
                            <div
                                class="w-16 h-16 p-1 bg-gradient-to-br from-brand-400 to-brand-600 rounded-[1.5rem] shadow-2xl group-hover:rotate-6 transition-transform duration-500">
                                <div
                                    class="w-full h-full overflow-hidden rounded-[1.2rem] border-2 border-white dark:border-gray-800">
                                    {{-- Foto de perfil de Jetstream (Muestra iniciales automáticamente si no hay foto) --}}
                                    <img src="{{ $student->profile_photo_url }}" alt="{{ $student->name }}"
                                        class="object-cover w-full h-full bg-gray-900" />
                                </div>
                            </div>

                            {{-- Indicador de Estado (Online/Activo) --}}
                            <span
                                class="absolute -bottom-1 -right-1 w-5 h-5 bg-green-500 border-4 border-white dark:border-gray-800 rounded-full shadow-lg"></span>
                        </div>

                        <div class="overflow-hidden">
                            <h4
                                class="font-black text-xl text-gray-900 dark:text-white uppercase italic tracking-tighter truncate group-hover:text-brand-400 transition-colors">
                                {{ $student->name }}
                            </h4>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.2em] mt-1 italic">
                                Investigador Jr.
                            </p>
                        </div>
                    </div>

                    {{-- Data de Avance --}}
                    <div class="w-full md:flex-1">
                        @if ($project)
                            <div class="flex justify-between items-end mb-3">
                                <div class="flex flex-col">
                                    <span
                                        class="text-[8px] font-black uppercase text-gray-400 tracking-[0.2em] italic">Capítulos
                                        Completados</span>
                                    <span
                                        class="text-xs font-black text-gray-900 dark:text-gray-200 uppercase mt-1 italic">
                                        {{ $project->completed_steps }} de {{ $project->steps_count }} entregas
                                    </span>
                                </div>
                                <div class="text-right">
                                    <span
                                        class="text-2xl font-black text-brand-400 italic leading-none">{{ round($percent) }}%</span>
                                </div>
                            </div>
                            <div
                                class="w-full h-3 bg-gray-100 dark:bg-gray-900 rounded-full overflow-hidden p-1 shadow-inner">
                                <div class="h-full bg-gradient-to-r from-brand-300 to-brand-600 rounded-full transition-all duration-1000 group-hover:shadow-[0_0_15px_rgba(74,222,128,0.5)]"
                                    style="width: {{ $percent }}%"></div>
                            </div>
                        @else
                            <div
                                class="py-4 px-6 bg-red-50 dark:bg-red-900/10 rounded-2xl border border-red-100 dark:border-red-900/20 text-center">
                                <span
                                    class="text-[9px] font-black uppercase text-red-500 italic tracking-[0.2em]">Alumno
                                    estancado: Proyecto no iniciado</span>
                            </div>
                        @endif
                    </div>

                    {{-- Botón de Acción Focalizado --}}
                    <div class="w-full md:w-auto">
                        @if ($project)
                            <button wire:click="reviewProject('{{ $project->uuid }}')"
                                class="w-full px-10 py-5 bg-gray-900 dark:bg-brand-600 text-white rounded-[1.8rem] text-[10px] font-black uppercase tracking-[0.3em] shadow-xl hover:scale-105 active:scale-95 transition-all group-hover:bg-brand-500 group-hover:shadow-brand-500/20">
                                Supervisar Avance →
                            </button>
                        @else
                            <button
                                class="w-full px-10 py-5 bg-gray-100 dark:bg-gray-700 text-gray-400 rounded-[1.8rem] text-[10px] font-black uppercase tracking-[0.3em] cursor-not-allowed">
                                Sin Contenido
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div
                class="py-32 text-center bg-white dark:bg-gray-800 rounded-[4rem] shadow-2xl border-2 border-dashed border-gray-100 dark:border-gray-700">
                <div
                    class="w-24 h-24 bg-gray-50 dark:bg-gray-900 rounded-[2.5rem] mx-auto flex items-center justify-center mb-8">
                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"
                            stroke-width="1.5" />
                    </svg>
                </div>
                <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase italic tracking-widest mb-2">Aula
                    Vacía</h3>
                <p class="text-gray-400 text-sm max-w-xs mx-auto italic font-medium">Comparte el código de invitación
                    con tus estudiantes para que aparezcan en este panel.</p>
            </div>
        @endforelse
    </div>

    {{-- Paginación MSHO Style --}}
    <div class="mt-12 flex justify-center">
        <div class="bg-white dark:bg-gray-800 p-2 rounded-[2rem] shadow-xl">
            {{ $students->links() }}
        </div>
    </div>
</div>
