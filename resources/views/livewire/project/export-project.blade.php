<div x-data="{ open: @entangle('showModal') }" x-show="open" 
    class="fixed inset-0 z-[100] overflow-y-auto bg-gray-900/95 backdrop-blur-md" 
    x-transition x-cloak>
    
    <div class="min-h-screen flex flex-col items-center py-12 px-4">
        
        {{-- Toolbar Flotante --}}
        <div class="fixed top-6 flex gap-3 z-[110] bg-white/10 backdrop-blur-xl p-2 rounded-full border border-white/20 shadow-2xl">
            <button @click="open = false" class="px-6 py-2 text-[10px] font-black text-white uppercase hover:bg-white/10 rounded-full transition-all">
                Volver a Editar
            </button>
            <a href="{{ route('projects.export-word', $project->id) }}" 
               class="bg-blue-600 hover:bg-blue-500 text-white px-8 py-2 rounded-full text-[10px] font-black uppercase tracking-widest flex items-center gap-2 shadow-lg transition-all">
               <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z"/></svg>
               Descargar Word (.docx)
            </a>
        </div>

        {{-- SIMULACIÓN DE HOJA A4 --}}
        <div class="w-full max-w-[21cm] bg-white shadow-[0_0_80px_rgba(0,0,0,0.5)] min-h-[29.7cm] p-[2.54cm] lg:p-[3cm] text-gray-900 mt-16 mb-20 animate-in zoom-in-95 duration-500 origin-top">
            
            {{-- Portada Automática --}}
            <div class="text-center flex flex-col h-[22cm] justify-between uppercase font-serif">
                <div>
                    <h1 class="text-xl font-bold mb-2">{{ $project->university->name ?? 'UNIVERSIDAD' }}</h1>
                    <div class="w-16 h-0.5 bg-gray-900 mx-auto my-6"></div>
                    <p class="text-xs tracking-[0.3em] font-medium">FACULTAD DE CIENCIAS</p>
                </div>

                <div class="px-4">
                    <h2 class="text-2xl font-black leading-tight italic">{{ $project->title }}</h2>
                    <p class="mt-10 text-[10px] lowercase first-letter:uppercase font-bold tracking-widest">PROYECTO DE TESIS</p>
                </div>

                <div class="text-xs font-bold space-y-2">
                    <p>AUTOR: {{ strtoupper(auth()->user()->name) }}</p>
                    <p class="mt-20">TRUJILLO - PERÚ</p>
                    <p>2026</p>
                </div>
            </div>

            <div class="my-16 border-b border-dashed border-gray-200"></div>

            {{-- MAPEADO DE TODOS LOS PASOS --}}
            @foreach($steps as $step)
                <div class="mb-10 page-section">
                    <h3 class="text-lg font-bold uppercase mb-4 text-gray-900 font-serif">
                        {{ $step->order }}. {{ $step->title }}
                    </h3>
                    
                    <div class="prose prose-sm max-w-none text-justify leading-[1.8] font-serif text-gray-800">
                        @php
                            $isTable = str_contains(strtolower($step->title), 'cronograma') || str_contains(strtolower($step->title), 'presupuesto');
                        @endphp

                        @if($isTable)
                            {{-- Aquí puedes renderizar una versión simplificada de la tabla para la vista previa --}}
                            <div class="p-4 bg-gray-50 border border-gray-100 rounded italic text-center text-gray-400">
                                [Tabla de {{ $step->title }} se incluirá en el archivo Word final]
                            </div>
                        @else
                            {!! $step->content !!}
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>