<div class="p-8 max-w-full mx-auto space-y-8 animate-fade-in">

    {{-- Navegación Estilo Breadcrumb Premium --}}
    <nav class="flex items-center gap-4 text-xs font-black uppercase">
        <a href="{{ route('classroom') }}"
            class="text-gray-400 hover:text-brand-500 transition-colors flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                    stroke-width="2.5" />
            </svg>
            Mis Aulas Virtuales
        </a>
        <span class="text-gray-300">/</span>
        <span class="text-brand-500 flex items-center gap-2">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M4 5c-1.1046 0-2 .8954-2 2v9c0 1.1046.8954 2 2 2h16c1.1046 0 2-.8954 2-2V7c0-1.1046-.8954-2-2-2H4zm0 2h16v8H4V7zm3 12a1 1 0 0 1 1-1h8a1 1 0 1 1 0 2H8a1 1 0 0 1-1-1z"
                    fill="currentColor"></path>
            </svg>
            {{ $classroom->name }}
        </span>
    </nav>
    {{-- Hero Section Reducido con Botón de Copia Integrado --}}
    <div class="relative overflow-hidden rounded-[2.5rem] p-2 mb-8">
        <div class="relative flex flex-col md:flex-row justify-between items-center gap-6">

            {{-- Info del Aula (Izquierda) --}}
            <div class="flex items-center gap-6">
                <div
                    class="w-16 h-16 bg-brand-500 rounded-3xl flex items-center justify-center text-white shadow-lg shadow-brand-400/20">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M4 5c-1.1046 0-2 .8954-2 2v9c0 1.1046.8954 2 2 2h16c1.1046 0 2-.8954 2-2V7c0-1.1046-.8954-2-2-2H4zm0 2h16v8H4V7zm3 12a1 1 0 0 1 1-1h8a1 1 0 1 1 0 2H8a1 1 0 0 1-1-1z"
                            fill="currentColor"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-3xl font-black text-gray-900 dark:text-white uppercase">
                        {{ $classroom->name }}
                    </h2>
                    <p class="text-gray-400 text-[9px] font-bold uppercase mt-1 tracking-widest leading-none">
                        {{ $classroom->university->siglas }} • {{ $students->total() }} Investigadores
                    </p>
                </div>
            </div>

            {{-- Botón de Invitación Estilo Compacto (Derecha) --}}
            <div x-data="{ copied: false }" class="relative">
                <div class="flex flex-col items-center md:items-end gap-2">
                    <span class="text-[12px] text-gray-400">Click para copiar
                        código</span>

                    <button
                        @click="navigator.clipboard.writeText('{{ route('classroom.autojoin', $classroom->invitation_code) }}'); copied = true; setTimeout(() => copied = false, 2000)"
                        class="group flex items-center gap-4 bg-gray-50 dark:bg-gray-900/50 px-6 py-3 rounded-2xl border-2 border-dashed border-gray-100 dark:border-gray-700 hover:border-brand-400 transition-all active:scale-95">

                        <span class="font-mono text-lg text-brand-500 font-black tracking-widest"
                            :class="copied ? 'text-green-500' : 'text-brand-500'">
                            {{ $classroom->invitation_code }}
                        </span>

                        <div
                            class="p-2 bg-white dark:bg-gray-800 rounded-lg shadow-sm group-hover:bg-brand-500 group-hover:text-white transition-colors">
                            {{-- Icono de Copiar --}}
                            <svg x-show="!copied" class="w-4 h-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                            </svg>
                            {{-- Icono de Check (Copiado) --}}
                            <svg x-show="copied" class="w-4 h-4 text-green-500 group-hover:text-white" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </button>
                </div>
            </div>

        </div>
    </div>

    {{-- Buscador y Filtros Rápidos --}}
    <div class="flex flex-col md:flex-row md:items-center justify-end gap-6">
        <div class="relative w-full md:w-96">
            <input wire:model.live.debounce.300ms="search" type="text"
                placeholder="Buscar estudiante por nombre o correo..."
                class="w-full pl-12 pr-4 py-3 bg-white dark:bg-gray-800 border rounded-2xl focus:ring-2 focus:ring-brand-500 dark:text-gray-200 text-sm shadow-sm">
            <div class="absolute left-4 top-3.5 text-gray-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2" />
                </svg>
            </div>
        </div>
    </div>


    <div class="grid grid-cols-1 gap-4 w-full">
        @forelse($students as $student)
            @php
                $project = $student->projects->first();
                $totalSteps = $project->steps_count ?? 0;
                $completedSteps = $project->completed_steps ?? 0;
                $percent = $totalSteps > 0 ? ($completedSteps / $totalSteps) * 100 : 0;
            @endphp

            <div
                class="group relative bg-white dark:bg-gray-800 rounded-[2rem] px-8 py-5 border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-xl transition-all duration-300 w-full">
                <div class="flex flex-row items-center justify-between gap-8 w-full">

                    {{-- 1. NOMBRE Y PERFIL (Ancho controlado) --}}
                    <div class="flex items-center gap-4 w-[250px] flex-shrink-0">
                        <div class="relative flex-shrink-0">
                            <div class="w-12 h-12 p-0.5 bg-gradient-to-br from-brand-400 to-brand-600 rounded-full">
                                <div
                                    class="w-full h-full overflow-hidden rounded-full border-2 border-white dark:border-gray-800">
                                    <img src="{{ $student->profile_photo_url }}" alt="{{ $student->name }}"
                                        class="object-cover w-full h-full bg-gray-900" />
                                </div>
                            </div>
                            <span
                                class="absolute bottom-0 right-0 w-3.5 h-3.5 bg-green-500 border-2 border-white dark:border-gray-800 rounded-full"></span>
                        </div>
                        <div class="truncate">
                            <h4
                                class="font-black text-sm text-gray-900 dark:text-white uppercase truncate leading-tight">
                                {{ $student->name }} {{ $student->last_name }}
                            </h4>
                            <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">Investigador
                            </p>
                        </div>
                    </div>

                    {{-- 2. TÍTULO DEL PROYECTO (Flexible) --}}
                    <div
                        class="flex-1 flex flex-col justify-center border-l border-gray-100 dark:border-gray-700 px-6 overflow-hidden">
                        <span class="text-[11px] font-black uppercase text-brand-500 mb-1">Proyecto</span>
                        <h5 class="text-[11px] font-medium text-gray-500 dark:text-gray-400 truncate">
                            {{ $project->title ?? 'Sin título registrado' }}
                        </h5>
                    </div>

                    {{-- 3. AVANCE (MÁS ANCHO: flex-[1.2]) --}}
                    <div class="flex-[1.2] min-w-[250px] px-6 border-l border-gray-50 dark:border-gray-700/50">
                        @if ($project)
                            <div class="flex justify-between items-end mb-1.5">
                                <div class="flex flex-col">
                                    <span
                                        class="text-[9px] font-black text-gray-400 uppercase tracking-tighter">Progreso
                                        General</span>
                                    <span class="text-[10px] font-black text-gray-700 dark:text-gray-200 uppercase">
                                        {{ $completedSteps }}/{{ $totalSteps }} Entregas
                                    </span>
                                </div>
                                <span
                                    class="text-xl font-black text-brand-500 italic leading-none">{{ round($percent) }}%</span>
                            </div>
                            <div
                                class="w-full h-2 bg-gray-100 dark:bg-gray-900 rounded-full overflow-hidden p-[2px] shadow-inner">
                                <div class="h-full bg-gradient-to-r from-brand-400 to-brand-600 rounded-full transition-all duration-1000"
                                    style="width: {{ $percent }}%"></div>
                            </div>
                        @else
                            <div class="h-full flex items-center">
                                <span class="text-[9px] font-bold text-gray-300 uppercase italic">Esperando asignación
                                    de capítulos...</span>
                            </div>
                        @endif
                    </div>

                    {{-- 4. BOTÓN DE ACCIÓN --}}
                    <div class="flex-shrink-0 ml-4">
                        <button wire:click="reviewProject('{{ $project?->uuid }}')"
                            class="px-10 py-3 bg-gray-900 dark:bg-brand-500 text-white rounded-full text-[10px] font-black uppercase tracking-widest hover:scale-105 transition-all shadow-md active:scale-95">
                            Revisar →
                        </button>
                    </div>

                </div>
            </div>
        @empty
            <div
                class="w-full py-16 text-center border-2 border-dashed border-gray-100 dark:border-gray-700 rounded-[3rem]">
                <p class="text-gray-400 text-xs font-black uppercase italic tracking-widest">Aula sin investigadores
                    registrados</p>
            </div>
        @endforelse
    </div>
