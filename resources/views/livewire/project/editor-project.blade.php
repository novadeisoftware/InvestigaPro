<div class="flex bg-[#F3F4F6] dark:bg-gray-950 overflow-hidden" x-data="{ leftSidebar: false, rightSidebar: false }">

    @if ($viewMode === 'editor')
        <div x-show="leftSidebar || rightSidebar" @click="leftSidebar = false; rightSidebar = false"
            class="fixed inset-0 bg-black/50 z-40 lg:hidden" x-transition></div>

        <aside :class="leftSidebar ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 w-72 bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-800 flex flex-col shadow-xl z-50 transition-transform duration-300 lg:relative lg:translate-x-0">
            {{-- HEADER: TÍTULO Y ESTADO (SOLO LECTURA) --}}
            <header
                class="h-48 bg-white/80 dark:bg-gray-900/80 backdrop-blur-md border-b border-gray-200 dark:border-gray-800 flex items-center justify-between px-4 z-30">
                <div class="flex items-center gap-2 overflow-hidden  p-2">
                    <div class="flex flex-col min-w-0">
                        <p class="text-[12px] font-bold text-gray-600 uppercase tracking-tighter mb-2">Título</p>

                        {{-- Título como Texto --}}
                        <h1 class="text-sm  text-gray-800 dark:text-gray-200 w-full mb-2">
                            {{ $project->title }}
                        </h1>

                        {{-- Estado de Guardado --}}
                        @if ($lastSaved)
                            <div class="flex items-center gap-1.5 mt-0.5 ">
                                <div wire:target="content" wire:loading.remove
                                    class="w-1 h-1 rounded-full bg-green-500 shadow-[0_0_5px_rgba(34,197,94,0.8)]">
                                </div>
                                <div wire:target="content" wire:loading>
                                    <div class="relative w-2 h-2 flex items-center justify-center">
                                        <span
                                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-brand-500"></span>
                                    </div>
                                </div>
                                <span class="text-xs font-medium text-gray-400">Guardado {{ $lastSaved }}</span>
                            </div>
                            {{-- Loader de sincronización  wire:loading --}}
                        @endif
                    </div>
                </div>


            </header>
            <div class="p-6 flex justify-between items-center">
                <p class="text-[12px] font-bold text-gray-600 uppercase">Pasos de la Investigación</p>
                <button @click="leftSidebar = false" class="lg:hidden text-gray-500">&times;</button>
            </div>

            <nav class="flex-1 overflow-y-auto px-4 pb-6 space-y-1 no-scrollbar">
                @foreach ($project->steps as $step)
                    <button wire:click="selectStep({{ $step->id }})"
                        @click="if(window.innerWidth < 1024) leftSidebar = false"
                        class="w-full flex items-center gap-3 p-3 rounded-2xl transition-all group {{ $currentStepId == $step->id ? 'bg-brand-600 text-white shadow-lg shadow-brand-500/40' : 'text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                        <div
                            class="shrink-0 w-6 h-6 rounded-lg flex items-center justify-center text-[10px] font-bold {{ $currentStepId == $step->id ? 'bg-white/20 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-400' }}">
                            {{ $step->order }}
                        </div>
                        <span class="text-sm font-bold truncate">{{ $step->title }}</span>
                    </button>
                @endforeach
            </nav>
        </aside>

        <main class="flex-1 flex flex-col min-w-0  relative bg-[#F3F4F6] dark:bg-gray-950">

            @if ($viewMode === 'editor')
                {{-- Mantenemos tu Header con el input de título y guardado --}}
                <div class="flex-1 overflow-y-auto py-6 lg:py-6px-4 lg:px-6 ">
                    {{-- Quitamos el min-h-screen para que no se estire forzadamente --}}
                    <div
                        class="max-w-[850px] mx-auto bg-white dark:bg-gray-900 shadow-2xl rounded-t-[1.5rem] lg:rounded-t-[2.5rem] border border-gray-100 dark:border-gray-800 overflow-hidden flex flex-col">

                        <div class="p-6 lg:p-8 pb-6">
                            <div class="flex items-center gap-2 mb-4 lg:mb-2">
                                <span
                                    class="px-3 py-1 bg-brand-50 dark:bg-brand-900/30 text-brand-600 text-[10px] font-black uppercase rounded-lg shadow-sm">
                                    Paso {{ $currentStep->order }}
                                </span>
                                <div class="h-px flex-1 bg-gray-100 dark:border-gray-800"></div>
                            </div>

                            <h2
                                class="text-2xl lg:text-4xl font-black text-gray-900 dark:text-white mb-2 lg:mb-2 leading-tight">
                                {{ $currentStep->title }}
                            </h2>

                            {{-- Guía Académica --}}
                            @if ($currentStep && isset($currentStep->structured_data['instrucciones']))
                                <div
                                    class="p-5 bg-amber-50/50 dark:bg-amber-900/10 border-l-4 border-amber-400 rounded-r-2xl">
                                    <p
                                        class="text-[8px] lg:text-[10px] font-black text-amber-600 uppercase mb-1 tracking-widest">
                                        Guía {{ $project->university->siglas ?? 'InvestigaPro' }}
                                    </p>
                                    <p
                                        class="text-xs lg:text-sm text-amber-800 dark:text-amber-200 italic leading-relaxed">
                                        "{{ $currentStep->structured_data['instrucciones'] }}"
                                    </p>
                                </div>
                            @endif
                        </div>

                        {{-- Área de escritura corregida: min-height flexible --}}
                        <div class="px-2 lg:px-8 pb-8">
                            @php
                                $stepTitle = strtolower($currentStep->title ?? '');
                                // Definimos el tipo de UI a mostrar
                                $uiType = 'text';
                                if (str_contains($stepTitle, 'cronograma')) {
                                    $uiType = 'cronograma';
                                } elseif (str_contains($stepTitle, 'presupuesto')) {
                                    $uiType = 'presupuesto';
                                }
                            @endphp

                            @switch($uiType)
                                @case('cronograma')
                                    {{-- HERRAMIENTA: CRONOGRAMA --}}
                                    <div wire:key="ui-cronograma-{{ $currentStepId }}" x-data="{
                                        rows: @entangle('content_json').live,
                                        meses: ['ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 'JUL', 'AGO', 'SEP', 'OCT', 'NOV', 'DIC'],
                                        addActividad() { this.rows.push({ actividad: '', meses: Array(12).fill(false) }) }
                                    }"
                                        class="space-y-4 animate-in fade-in duration-500">

                                        <div
                                            class="overflow-hidden border border-gray-100 dark:border-gray-800 rounded-3xl bg-white dark:bg-gray-900 shadow-sm">
                                            <div class="overflow-x-auto no-scrollbar">
                                                <table class="w-full text-left text-[11px]">
                                                    <thead
                                                        class="bg-gray-50 dark:bg-gray-800/50 text-gray-400 font-black uppercase">
                                                        <tr>
                                                            <th class="p-4 min-w-[200px]">ACTIVIDAD / ETAPA</th>
                                                            <template x-for="mes in meses">
                                                                <th class="p-2 text-center" x-text="mes"></th>
                                                            </template>
                                                            <th class="p-4 w-10"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                                                        <template x-for="(row, index) in rows" :key="index">
                                                            <tr class="hover:bg-brand-50/20 transition-colors">
                                                                <td class="p-2">
                                                                    <input type="text" x-model="row.actividad"
                                                                        class="w-full bg-transparent border-none  text-sm focus:ring-0 text-gray-700 dark:text-gray-200 font-bold placeholder-gray-300"
                                                                        placeholder="Nueva actividad...">
                                                                </td>
                                                                <template x-for="(check, i) in row.meses">
                                                                    <td class="p-1 text-center">
                                                                        <input type="checkbox" x-model="row.meses[i]"
                                                                            class="w-4 h-4 rounded border-gray-200 text-brand-600 focus:ring-brand-500 bg-transparent">
                                                                    </td>
                                                                </template>
                                                                <td class="p-2 text-center">
                                                                    <button @click="rows.splice(index, 1)"
                                                                        class="text-gray-300 hover:text-red-500 transition-colors">
                                                                        <svg class="w-4 h-4" fill="none"
                                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path
                                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                                                                stroke-width="2" stroke-linecap="round"
                                                                                stroke-linejoin="round" />
                                                                        </svg>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        </template>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <button @click="addActividad()"
                                            class="flex items-center gap-2 text-[10px] font-black uppercase text-brand-600 hover:text-brand-700 transition-colors">+
                                            Agregar Actividad</button>
                                    </div>
                                @break


                            @break

                            @case('presupuesto')
                                {{-- HERRAMIENTA: PRESUPUESTO CON CAMBIO DE MONEDA --}}
                                <div wire:key="ui-presupuesto-{{ $currentStepId }}" x-data="{
                                    data: @entangle('content_json').live,
                                    {{-- Inicializamos la moneda desde el JSON o por defecto Soles --}}
                                    moneda: 'S/',
                                
                                    init() {
                                        if (!this.data.items) this.data = { items: [], moneda: 'S/' };
                                        this.moneda = this.data.moneda || 'S/';
                                    },
                                
                                    addItem() {
                                        this.data.items.push({ item: '', cant: 1, precio: 0 });
                                    },
                                
                                    setMoneda(sym) {
                                        this.moneda = sym;
                                        this.data.moneda = sym;
                                        {{-- Guardamos la preferencia en el JSON --}}
                                    },
                                
                                    getTotal() {
                                        const total = this.data.items.reduce((acc, row) => acc + (parseFloat(row.cant || 0) * parseFloat(row.precio || 0)), 0);
                                        return total.toLocaleString('es-PE', { minimumFractionDigits: 2 });
                                    }
                                }"
                                    class="space-y-4 animate-in fade-in duration-500">

                                    {{-- Selector de Moneda --}}
                                    <div class="flex justify-end gap-2 px-2">
                                        <button @click="setMoneda('S/')"
                                            :class="moneda === 'S/' ? 'bg-brand-600 text-white' :
                                                'bg-white text-gray-400 border border-gray-100'"
                                            class="px-3 py-1 rounded-lg text-[10px] font-black transition-all shadow-sm">PEN
                                            (S/)</button>
                                        <button @click="setMoneda('$')"
                                            :class="moneda === '$' ? 'bg-brand-600 text-white' :
                                                'bg-white text-gray-400 border border-gray-100'"
                                            class="px-3 py-1 rounded-lg text-[10px] font-black transition-all shadow-sm">USD
                                            ($)</button>
                                    </div>

                                    <div
                                        class="overflow-hidden border border-gray-100 dark:border-gray-800 rounded-3xl bg-white dark:bg-gray-900 shadow-sm">
                                        <table class="w-full text-left text-[11px]">
                                            <thead
                                                class="bg-gray-50 dark:bg-gray-800/50 text-gray-400 font-black uppercase">
                                                <tr>
                                                    <th class="p-4">ÍTEM / RECURSO</th>
                                                    <th class="p-4 text-center">CANT.</th>
                                                    <th class="p-4 text-center">PRECIO UNIT. (<span x-text="moneda"></span>)
                                                    </th>
                                                    <th class="p-4 text-right">SUBTOTAL (<span x-text="moneda"></span>)</th>
                                                    <th class="p-4 w-10"></th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                                                <template x-for="(row, index) in data.items" :key="index">
                                                    <tr class="hover:bg-brand-50/20">
                                                        <td class="p-2">
                                                            <input type="text" x-model="row.item"
                                                                class="w-full bg-transparent border-none focus:ring-0 text-sm text-gray-700 dark:text-gray-200 font-bold"
                                                                placeholder="Descripción...">
                                                        </td>
                                                        <td class="p-2">
                                                            <input type="number" x-model.number="row.cant"
                                                                class="w-16 mx-auto text-sm block text-center bg-gray-50 dark:bg-gray-800 border-none rounded-lg focus:ring-brand-500">
                                                        </td>
                                                        <td class="p-2">
                                                            <input type="number" x-model.number="row.precio"
                                                                class="w-20 mx-auto text-sm block text-center bg-gray-50 dark:bg-gray-800 border-none rounded-lg focus:ring-brand-500">
                                                        </td>
                                                        <td class="p-4 text-right font-black text-sm text-gray-500">
                                                            <span x-text="moneda"></span><span
                                                                x-text="(row.cant * row.precio).toFixed(2)"></span>
                                                        </td>
                                                        <td class="p-2 text-center">
                                                            <button @click="data.items.splice(index, 1)"
                                                                class="text-gray-300 hover:text-red-500 transition-colors">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path
                                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                                                        stroke-width="2" stroke-linecap="round"
                                                                        stroke-linejoin="round" />
                                                                </svg>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                            <tfoot class="bg-brand-50/30">
                                                <tr class="font-black text-brand-700">
                                                    <td colspan="3" class="p-4 text-right uppercase">TOTAL PRESUPUESTO:
                                                    </td>
                                                    <td class="p-4 text-right text-lg">
                                                        <span x-text="moneda"></span><span x-text="getTotal()"></span>
                                                    </td>
                                                    <td></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <button @click="addItem()"
                                        class="flex items-center gap-2 text-[10px] font-black uppercase text-brand-600 hover:text-brand-700 transition-colors">+
                                        Agregar Ítem</button>
                                </div>
                            @break

                            @default
                                <div wire:ignore wire:key="quill-editor-{{ $currentStepId }}" x-data="{
                                    content: @entangle('content'),
                                    init() {
                                        // REGISTRAMOS SOLO LOS FORMATOS QUE QUEREMOS PERMITIR
                                        const quill = new Quill($refs.editor, {
                                            theme: 'snow',
                                            placeholder: 'Escribe el desarrollo aquí...',
                                            formats: [
                                                'header', 'bold', 'italic', 'underline', 'list', 'bullet', 'align'
                                            ],
                                            modules: {
                                                toolbar: [
                                                    // LIMITAMOS SOLO A NIVEL 2 Y 3
                                                    [{ 'header': [2, 3, false] }],
                                                    ['bold', 'italic', 'underline'],
                                                    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                                                    [{ 'align': [] }],
                                                    ['clean']
                                                ]
                                            }
                                        });
                                
                                        quill.root.innerHTML = this.content;
                                
                                        quill.on('text-change', () => {
                                            this.content = quill.root.innerHTML;
                                        });
                                
                                        $watch('content', value => {
                                            if (value !== quill.root.innerHTML) {
                                                quill.root.innerHTML = value || '';
                                            }
                                        });
                                    }
                                }">

                                    <div x-ref="editor"
                                        class="w-full max-h-[800px] text-[15px] lg:text-[16px] leading-[1.8] text-gray-700 dark:text-gray-300 font-serif border-none">
                                    </div>
                                </div>

                                <style>
                                    /* 1. Definir el contenedor principal como un flex de alto completo */
                                    [wire\:ignore] {
                                        display: flex;
                                        flex-direction: column;
                                        height: 55vh;
                                        /* Ocupa el 85% del alto de la pantalla, ajustable según tu layout */
                                        min-height: 500px;
                                    }

                                    /* 2. Estilo del Toolbar */
                                    .ql-toolbar.ql-snow {
                                        border: none !important;
                                        border-bottom: 1px solid #f3f4f6 !important;
                                        padding: 0.75rem !important;
                                        background: white;
                                        flex-shrink: 0;
                                        /* Evita que el toolbar se encoja */
                                    }

                                    .dark .ql-toolbar.ql-snow {
                                        background: #111827;
                                        border-bottom: 1px solid #374151 !important;
                                    }

                                    /* 3. Hacer que el contenedor de texto ocupe el resto del espacio */
                                    .ql-container.ql-snow {
                                        border: none !important;
                                        font-family: 'Georgia', serif !important;
                                        flex: 1;
                                        /* Esto estira el editor hacia abajo */
                                        display: flex;
                                        flex-direction: column;
                                        overflow: hidden;
                                    }

                                    /* 4. Habilitar el scroll interno para el texto */
                                    .ql-editor {
                                        padding: 2rem !important;
                                        flex: 1;
                                        overflow-y: auto;
                                        
                                        /* Permite scroll solo en el contenido, no en toda la página */
                                    }

                                    /* Opcional: Hacer que se sienta como una hoja de papel infinita */
                                    .ql-editor.ql-blank::before {
                                        left: 2rem !important;
                                        font-style: italic;
                                        color: #9ca3af;
                                    }
                                </style>

                                <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
                                <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
                            </div>
                    @endswitch
                    {{-- Pie de página de la hoja para cerrar el diseño --}}
                    <div class="mt-auto px-12 py-6 border-t border-gray-50 dark:border-gray-800 opacity-30">
                        <p class="text-[8px] font-bold text-gray-400 uppercase tracking-[0.3em]">
                            Nova Dei Software • Trujillo 2026
                        </p>
                    </div>

                </div>
            @else
                {{-- LIENZO PREVIEW --}}
                <main id="preview-content"
                    class="flex-1 h-full overflow-y-auto preview-container flex flex-col items-center">
                    <div class="py-12 px-4 w-full flex flex-col items-center gap-12">

                        @switch($project->document_type)
                            @case('PROYECTO DE TESIS')
                                {{-- PORTADA REGLAMENTARIA (ESTILO ARIAL WORD ACTUALIZADO) --}}
                                <div id="portada"
                                    class="document-page mb-16 animate-in zoom-in-95 duration-500 font-sans">
                                    <div class="text-center flex flex-col h-full justify-between text-gray-900"
                                        style="font-family: Arial, Helvetica, sans-serif !important;">

                                        <div>
                                            {{-- Nombre de la Universidad (Arial 18pt) --}}
                                            <h1 class="font-bold leading-tight uppercase" style="font-size: 18pt;">
                                                {{ $project->university->nombre }}
                                            </h1>

                                            {{-- Facultad (Arial 16pt) --}}
                                            <p class="font-bold mt-2 uppercase" style="font-size: 16pt;">
                                                {{ $project->faculty ?? 'FACULTAD DE INGENIERÍA' }}
                                            </p>

                                            {{-- Escuela Profesional (Arial 12pt) --}}
                                            <p class="font-bold mt-1 uppercase" style="font-size: 12pt;">
                                                {{ $project->school ?? 'ESCUELA DE INGENIERÍA DE COMPUTACIÓN Y SISTEMAS' }}
                                            </p>

                                            {{-- LOGO DE LA UNIVERSIDAD --}}
                                            @if ($project->university->logo_path)
                                                <div class="flex justify-center my-6">
                                                    <img src="{{ asset('storage/' . $project->university->logo_path) }}"
                                                        alt="Logo {{ $project->university->name }}"
                                                        class="h-40 w-auto object-contain">
                                                </div>
                                            @endif
                                        </div>

                                        <div class="px-8 flex flex-col gap-6">
                                            {{-- Tipo de Documento y Grado --}}
                                            <div class="mt-4">
                                                <p class=" mt-2 uppercase" style="font-size: 11pt;">
                                                    PROYECTO DE TESIS PARA OBTENER EL TÍTULO PROFESIONAL DE: <br>
                                                    ....................................................................
                                                </p>
                                            </div>
                                            {{-- Título del Proyecto con líneas arriba y abajo --}}
                                            <h2 class="leading-normal border-t border-b border-black py-2"
                                                style="font-size: 12pt; max-width: 100%; margin: 0 auto; font-family: Arial, sans-serif;">
                                                {{ $project->title }}
                                            </h2>
                                        </div>

                                        <div class="flex flex-col gap-10 mt-10">
                                            {{-- Contenedor de Linea de Investigación --}}
                                            <div class="mx-auto" style="width: 12cm;"> {{-- Ancho fijo para forzar alineación vertical --}}
                                                <div class="text-center">
                                                    <p class="font-bold"
                                                        style="font-size: 12pt; font-family: Arial, sans-serif;">
                                                        Linea de
                                                        Investigación:</p>
                                                    <div class="mt-4"
                                                        style="font-size: 12pt; font-family: Arial, sans-serif;">
                                                        <p>{{ $project->academic_line ?? 'No especificado' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- Contenedor de Autores --}}
                                            <div class="mx-auto" style="width: 12cm;"> {{-- Ancho fijo para forzar alineación vertical --}}
                                                <div class="text-center">
                                                    <p class="font-bold"
                                                        style="font-size: 12pt; font-family: Arial, sans-serif;">
                                                        Autores:</p>
                                                    <div class="mt-4 space-y-1"
                                                        style="font-size: 12pt; font-family: Arial, sans-serif;">
                                                        <p>Br. {{ $project->user->name }}</p>
                                                        @if ($project->second_author)
                                                            <p>Br. {{ $project->second_author }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Contenedor de Asesor --}}
                                            <div class="mx-auto" style="width: 12cm;"> {{-- Mismo ancho fijo que autores --}}
                                                <div class="text-center">
                                                    <p class="font-bold"
                                                        style="font-size: 12pt; font-family: Arial, sans-serif;">
                                                        Asesor:</p>
                                                    <div class="mt-4"
                                                        style="font-size: 12pt; font-family: Arial, sans-serif;">
                                                        <p>{{ $project->advisor ?? 'Ms. Ing. Sagastegui Chigne Teobaldo Hernan' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- Contenedor de codigo ORCID --}}
                                            <div class="mx-auto" style="width: 12cm;">
                                                <div class="text-center">
                                                    <p class="font-bold"
                                                        style="font-size: 12pt; font-family: Arial, sans-serif;">
                                                        Código Orcid:
                                                    </p>
                                                    <div class="mt-4"
                                                        style="font-size: 12pt; font-family: Arial, sans-serif;">
                                                        ....................................................................
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Ciudad y Fecha --}}
                                            <div class="font-bold mt-12 pb-10"
                                                style="font-size: 12pt; font-family: Arial, sans-serif;">
                                                Trujillo - Perú <br>
                                                {{ now()->isoFormat('YYYY') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- SEGUNDA HOJA: GENERALIDADES O PROGRAMA DE ESTUDIO --}}
                                <div class="document-page mb-16 shadow-2xl animate-in fade-in duration-1000 font-sans"
                                    style="font-family: Arial, sans-serif !important;">
                                    <div class="flex flex-col h-full text-black">

                                        {{-- Título Repetido (Arial 12pt Negrita) --}}
                                        <div class="text-center mb-16">
                                            <h2 class="font-bold leading-normal uppercase" style="font-size: 12pt;">
                                                GENERALIDADES O PROGRAMA DE ESTUDIO
                                            </h2>
                                        </div>


                                        {{-- Sección de Firmas del Jurado --}}
                                        <div class="space-y-12">
                                            <p class="mb-2" style="font-size: 12pt;">Titulo de Proyecto:
                                                {{ $project->title }}</p>

                                            <p class="mb-2" style="font-size: 12pt;">Resposables o Equipo Investigador:
                                                {{ $project->user->name }} @if ($project->second_author)
                                                    <p>{{ $project->second_author }}</p>
                                                @endif
                                            </p>
                                            <p class="mb-2" style="font-size: 12pt;">Asesor:
                                                _____________________________
                                            </p>
                                            <p class="mb-2" style="font-size: 12pt;">Tipo de Investigación:
                                                _____________________________</p>
                                            <p class="mb-2" style="font-size: 12pt;">Linea de Investigación:
                                                {{ $project->academic_line ?? 'No especificado' }}</p>
                                            <p class="mb-2" style="font-size: 12pt;">Lugar y fecha de Presentación:
                                                _____________________________ </p>
                                            <p class="mb-12" style="font-size: 12pt;">Duración:
                                                _____________________________
                                            </p>




                                            {{-- Contenedor Principal: 2 columnas, espacio entre filas de 10 unidades --}}
                                            <div class="grid grid-cols-2 gap-y-10 gap-x-4 mt-8">

                                                {{-- 1. Presidente --}}
                                                <div style="font-size: 11pt;" class="mb-12">
                                                    <p>Dr. _____________________________</p>
                                                    <p class="mt-1">(Presidente)</p>
                                                </div>

                                                {{-- 2. Secretario --}}
                                                <div style="font-size: 11pt;" class="mb-12">
                                                    <p>Dr. _____________________________</p>
                                                    <p class="mt-1">(Secretario)</p>
                                                </div>

                                                {{-- 3. Vocal --}}
                                                <div style="font-size: 11pt;" class="mb-12">
                                                    <p>Ms. _____________________________</p>
                                                    <p class="mt-1">(Vocal)</p>
                                                </div>

                                                {{-- 4. Ejemplo Vocal 2 --}}
                                                <div style="font-size: 11pt;" class="mb-12">
                                                    <p>Dr. _____________________________</p>
                                                    <p class="mt-1">(Vocal 2)</p>
                                                </div>

                                                {{-- 5. Ejemplo Asesor --}}
                                                <div style="font-size: 11pt;" class="mb-12">
                                                    <p>Dr. _____________________________</p>
                                                    <p class="mt-1">(Asesor)</p>
                                                </div>

                                                {{-- 6. Ejemplo Jurado --}}
                                                <div style="font-size: 11pt;" class="mb-12">
                                                    <p>Dr. _____________________________</p>
                                                    <p class="mt-1">(Jurado Externo)</p>
                                                </div>

                                            </div>

                                        </div>



                                    </div>
                                </div>

                                {{-- TERCERA HOJA: ÍNDICE GENERAL --}}
                                <div class="document-page mb-16 shadow-2xl animate-in fade-in duration-1000 font-sans"
                                    style="font-family: Arial, sans-serif !important;">
                                    <div class="flex flex-col h-full text-black uppercase">

                                        {{-- INDICE --}}
                                        <div class="text-center mb-16">
                                            <h2 class="font-bold leading-normal uppercase" style="font-size: 12pt;">
                                                ÍNDICE
                                            </h2>
                                        </div>


                                        {{-- Lista Dinámica --}}
                                        <div class="space-y-2" style="font-size: 12pt; font-family: Arial, sans-serif;">

                                            {{-- Pasos Dinámicos de la Tesis --}}
                                            @php
                                                // Empezamos en la página 10 (asumiendo que el índice es la 9)
                                                $currentPage = 3;
                                            @endphp

                                            @foreach ($project->steps->sortBy('order') as $step)
                                                <div class="flex items-end">
                                                    <span class="truncate uppercase">{{ $loop->iteration }}.
                                                        {{ $step->title }}</span>
                                                    <div class="flex-1 border-b border-dotted border-black mx-2 mb-1">
                                                    </div>
                                                    <span>{{ $currentPage }}</span>
                                                </div>
                                                @php
                                                    // Aquí podrías sumar más si el contenido del paso excede una hoja
                                                    $currentPage++;
                                                @endphp
                                            @endforeach

                                        </div>
                                    </div>
                                </div>

                                {{-- HOJAS DE CONTENIDO --}}
                                @foreach ($project->steps->sortBy('order') as $step)
                                    <div id="step-{{ $step->id }}"
                                        class="document-page shadow-2xl shrink-0 font-sans animate-in slide-in-from-bottom-10 duration-700">

                                        {{-- Título de la Sección --}}
                                        <h3 class="text-[12pt] font-bold uppercase mb-12 text-center text-gray-900">
                                            {{ $step->title }}
                                        </h3>

                                        <div class="academic-body prose-none text-justify text-[12pt] text-gray-800">
                                            @php
                                                $data = json_decode($step->content, true);
                                                $isTable =
                                                    str_contains(strtolower($step->title), 'cronograma') ||
                                                    str_contains(strtolower($step->title), 'presupuesto');
                                            @endphp

                                            @if ($isTable && is_array($data))
                                                {{-- Tus tablas --}}
                                                @include(
                                                    'livewire.project.partials.preview-' .
                                                        (str_contains(strtolower($step->title), 'cronograma')
                                                            ? 'cronograma'
                                                            : 'presupuesto'),
                                                    ['data' => $data]
                                                )
                                            @else
                                                <div class="academic-text-wrapper">
                                                    {!! $step->content !!}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @break

                            @default
                                {{-- PORTADA REGLAMENTARIA (ESTILO ARIAL WORD ACTUALIZADO) --}}
                                <div id="portada"
                                    class="document-page mb-16 animate-in zoom-in-95 duration-500 font-sans">
                                    <div class="text-center flex flex-col h-full justify-between text-gray-900"
                                        style="font-family: Arial, Helvetica, sans-serif !important;">

                                        <div>
                                            {{-- Nombre de la Universidad (Arial 18pt) --}}
                                            <h1 class="font-bold leading-tight uppercase" style="font-size: 18pt;">
                                                {{ $project->university->nombre }}
                                            </h1>

                                            {{-- Facultad (Arial 16pt) --}}
                                            <p class="font-bold mt-2 uppercase" style="font-size: 16pt;">
                                                {{ $project->faculty ?? 'FACULTAD DE INGENIERÍA' }}
                                            </p>

                                            {{-- Escuela Profesional (Arial 12pt) --}}
                                            <p class="font-bold mt-1 uppercase" style="font-size: 12pt;">
                                                {{ $project->school ?? 'ESCUELA DE INGENIERÍA DE COMPUTACIÓN Y SISTEMAS' }}
                                            </p>

                                            {{-- LOGO DE LA UNIVERSIDAD --}}
                                            @if ($project->university->logo_path)
                                                <div class="flex justify-center my-6">
                                                    <img src="{{ asset('storage/' . $project->university->logo_path) }}"
                                                        alt="Logo {{ $project->university->name }}"
                                                        class="h-40 w-auto object-contain">
                                                </div>
                                            @endif
                                        </div>

                                        <div class="px-8 flex flex-col gap-6">
                                            {{-- Título del Proyecto (Arial 12pt) --}}
                                            <h2 class="font-bold leading-normal uppercase"
                                                style="font-size: 12pt; max-width: 90%; margin: 0 auto;">
                                                {{ $project->title }}
                                            </h2>

                                            {{-- Tipo de Documento y Grado --}}
                                            <div class="mt-4">
                                                <p class="font-bold uppercase" style="font-size: 14pt;">
                                                    {{ $project->document_type === 'PROYECTO DE TESIS' ? 'PROYECTO DE TESIS' : 'TESIS' }}
                                                </p>
                                                <p class="font-bold mt-2 uppercase" style="font-size: 11pt;">
                                                    PARA OBTENER EL TÍTULO PROFESIONAL EN <br>
                                                    {{ $project->school ?? 'ESCUELA SIN ESPECIFICAR' }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="flex flex-col gap-10 mt-10">
                                            {{-- Contenedor de Autores --}}
                                            <div class="mx-auto" style="width: 12cm;"> {{-- Ancho fijo para forzar alineación vertical --}}
                                                <div class="text-left">
                                                    <p class="font-bold uppercase"
                                                        style="font-size: 12pt; font-family: Arial, sans-serif;">AUTORES:
                                                    </p>
                                                    <div class="mt-4 space-y-1"
                                                        style="font-size: 12pt; font-family: Arial, sans-serif;">
                                                        <p>Br. {{ $project->user->name }}</p>
                                                        @if ($project->second_author)
                                                            <p>Br. {{ $project->second_author }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Contenedor de Asesor --}}
                                            <div class="mx-auto" style="width: 12cm;"> {{-- Mismo ancho fijo que autores --}}
                                                <div class="text-left">
                                                    <p class="font-bold uppercase"
                                                        style="font-size: 12pt; font-family: Arial, sans-serif;">ASESOR:
                                                    </p>
                                                    <div class="mt-4"
                                                        style="font-size: 12pt; font-family: Arial, sans-serif;">
                                                        <p>{{ $project->advisor ?? 'Ms. Ing. Sagastegui Chigne Teobaldo Hernan' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Ciudad y Fecha --}}
                                            <div class="font-bold mt-12 pb-10"
                                                style="font-size: 12pt; font-family: Arial, sans-serif;">
                                                Trujillo, {{ now()->isoFormat('D [de] MMMM [del] YYYY') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- SEGUNDA HOJA: PÁGINA DE JURADO Y APROBACIÓN --}}
                                <div class="document-page mb-16 shadow-2xl animate-in fade-in duration-1000 font-sans"
                                    style="font-family: Arial, sans-serif !important;">
                                    <div class="flex flex-col h-full text-black">

                                        {{-- Título Repetido (Arial 12pt Negrita) --}}
                                        <div class="text-center mb-16">
                                            <h2 class="font-bold leading-normal uppercase" style="font-size: 12pt;">
                                                {{ $project->title }}
                                            </h2>
                                        </div>

                                        {{-- Autores (Alineados a la derecha según el documento) --}}
                                        <div class="flex justify-end mb-20">
                                            <div class="text-left" style="font-size: 11pt; width: 8cm;">
                                                <p class="mb-2">Por: Br. {{ $project->user->name }}</p>
                                                @if ($project->second_author)
                                                    <p>Br. {{ $project->second_author }}</p>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Sección de Firmas del Jurado --}}
                                        <div class="space-y-12">
                                            <p class="font-bold mb-8" style="font-size: 12pt;">Aprobado:</p>

                                            {{-- Presidente --}}
                                            <div class="flex justify-between items-end">
                                                <div style="font-size: 11pt;">
                                                    <p>Dr. _____________________________</p>
                                                    <p class="mt-1">(Presidente)</p>
                                                </div>
                                            </div>

                                            {{-- Secretario --}}
                                            <div class="flex justify-between items-end">
                                                <div style="font-size: 11pt;">
                                                    <p>Dr. _____________________________</p>
                                                    <p class="mt-1">(Secretario)</p>
                                                </div>
                                            </div>

                                            {{-- Vocal --}}
                                            <div class="flex justify-between items-end">
                                                <div style="font-size: 11pt;">
                                                    <p>Ms. _____________________________</p>
                                                    <p class="mt-1">(Vocal)</p>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Espacio para el Asesor al final --}}
                                        <div class="mt-auto pb-20">
                                            <div style="font-size: 11pt;">
                                                <p class="mb-8">Asesor:</p>
                                                <div class="mt-10">

                                                    <p class="mt-2">{{ $project->advisor ?? 'ASESOR SIN ESPECIFICAR' }}
                                                    </p>
                                                    <p>…………………………………………..</p>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                {{-- TERCERA HOJA: ACREDITACIÓN DEL ASESOR --}}
                                <div class="document-page mb-16 shadow-2xl animate-in fade-in duration-1000 font-sans"
                                    style="font-family: Arial, sans-serif !important;">
                                    <div class="flex flex-col h-full text-black uppercase">

                                        {{-- Título de la Hoja --}}
                                        <div class="text-center mb-16">
                                            <h2 class="font-bold pb-1" style="font-size: 12pt;">
                                                ACREDITACIÓN
                                            </h2>
                                        </div>

                                        {{-- Cuerpo de la Acreditación (Arial 12pt, Interlineado 1.5) --}}
                                        <div class="academic-text-wrapper text-justify"
                                            style="font-size: 12pt; line-height: 1.5; text-transform: none;">
                                            <p style="text-indent: 2.5cm; margin-bottom: 2rem;">
                                                El
                                                <strong>{{ $project->advisor ?? '(ASESOR SIN ESPECIFICAR)' }}</strong>,
                                                que suscribe, asesor de la Tesis con Título
                                                <strong>“{{ mb_strtoupper($project->title) }}”</strong>,
                                                desarrollado por los <strong>Br. {{ $project->user->name }}</strong>
                                                @if ($project->second_author)
                                                    y <strong>{{ $project->second_author }}</strong>
                                                @endif
                                                en
                                                <strong>{{ $project->school ?? '(ESCUELA SIN ESPECIFICAR)' }}</strong>,
                                                acredita haber realizado las observaciones y recomendaciones pertinentes,
                                                encontrándose expedita para su revisión por parte de los señores miembros
                                                del Jurado Evaluador.
                                            </p>
                                        </div>

                                        {{-- Ciudad y Fecha --}}
                                        <div class="text-right font-bold mt-10 pb-10"
                                            style="font-size: 12pt; text-transform: none;">
                                            Trujillo, {{ now()->isoFormat('D [de] MMMM [del] YYYY') }}
                                        </div>

                                    </div>
                                </div>

                                {{-- CUARTA HOJA: PRESENTACIÓN AL JURADO --}}
                                <div class="document-page mb-16 shadow-2xl animate-in fade-in duration-1000 font-sans"
                                    style="font-family: Arial, sans-serif !important;">
                                    <div class="flex flex-col h-full text-black">

                                        {{-- Título de la Hoja --}}
                                        <div class="text-center mb-16">
                                            <h2 class="font-bold pb-1 uppercase" style="font-size: 12pt;">
                                                PRESENTACIÓN
                                            </h2>
                                        </div>

                                        {{-- Cuerpo de la Presentación (Arial 12pt, Interlineado 1.5) --}}
                                        <div class="academic-text-wrapper text-justify" style="font-size: 12pt;">
                                            <p class="mb-8">Señores miembros del Jurado Dictaminador:</p>

                                            <p style="margin-bottom: 2rem;">
                                                Dando cumplimiento a las normas del Reglamento de Grados y Títulos de la
                                                <strong>{{ $project->university->nombre }}</strong>,
                                                presentamos a vuestra consideración el Trabajo de Tesis titulado:
                                                <strong>“{{ mb_strtoupper($project->title) }}”</strong>,
                                                con el fin de obtener el Título Profesional de
                                                <strong>{{ $project->school ?? '(ESCUELA SIN ESPECIFICAR)' }}</strong>.
                                            </p>
                                            <p>Gracias.</p>

                                        </div>

                                        {{-- Bloque de Firmas de los Autores --}}
                                        <div class="mt-auto flex flex-col items-center gap-16 pb-20 uppercase">
                                            <div class="flex justify-around w-full px-10">
                                                {{-- Firma Autor 1 --}}
                                                <div class="text-center w-64">
                                                    <p class="mb-1">__________________________</p>
                                                    <p class="font-bold" style="font-size: 10pt;">Br.
                                                        {{ $project->user->name }}</p>
                                                </div>

                                                {{-- Firma Autor 2 (Si existe) --}}
                                                @if ($project->second_author)
                                                    <div class="text-center w-64">
                                                        <p class="mb-1">__________________________</p>
                                                        <p class="font-bold" style="font-size: 10pt;">Br.
                                                            {{ $project->second_author }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Ciudad y Fecha --}}
                                        <div class="text-right font-bold mt-10 pb-10" style="font-size: 12pt;">
                                            Trujillo, {{ now()->isoFormat('D [de] MMMM [del] YYYY') }}
                                        </div>

                                    </div>
                                </div>

                                {{-- QUINTA HOJA: DEDICATORIA --}}
                                <div class="document-page mb-16 shadow-2xl animate-in fade-in duration-1000 font-sans"
                                    style="font-family: Arial, sans-serif !important;">
                                    <div class="flex flex-col h-full text-black">

                                        {{-- Título de la Hoja --}}
                                        <div class="text-center mb-16">
                                            <h2 class="font-bold pb-1 uppercase" style="font-size: 12pt;">
                                                DEDICATORIA
                                            </h2>
                                        </div>

                                        {{-- Cuerpo de la Presentación (Arial 12pt, Interlineado 1.5) --}}
                                        <div class="academic-text-wrapper text-justify" style="font-size: 12pt;">
                                            <p>
                                                A mis padres, por su amor, paciencia y apoyo incondicional en cada paso de
                                                mi formación.
                                                A Dios, por darme la vida y la sabiduría necesaria para alcanzar esta meta
                                                profesional.
                                                Este logro es tan suyo como mío.
                                            </p>
                                            {{-- Nombre del Bachiller 1 --}}
                                            <p class="text-right font-bold mt-4" style="font-size: 11pt;">
                                                {{ $project->user->name }}
                                            </p>
                                        </div>

                                        {{-- Espacio para el Bachiller 2 (Si existe en el proyecto) --}}
                                        @if ($project->second_author)
                                            <div class="flex justify-end mt-24">
                                                <div class="text-justify italic"
                                                    style="font-size: 12pt; line-height: 1.5; width: 10cm; border-t border-gray-100 pt-10">
                                                    <p>
                                                        A mi familia, por ser mi motor y motivación constante. A mis amigos,
                                                        por
                                                        los momentos
                                                        compartidos y el aliento recibido durante estos años de estudio.
                                                    </p>
                                                </div>
                                            </div>
                                            {{-- Nombre del Bachiller 1 --}}
                                            <p class="text-right font-bold mt-4" style="font-size: 11pt;">
                                                {{ $project->second_author }}
                                            </p>
                                        @endif

                                    </div>
                                </div>

                                {{-- SEXTA HOJA: AGRADECIMIENTOS --}}
                                <div class="document-page mb-16 shadow-2xl animate-in fade-in duration-1000 font-sans"
                                    style="font-family: Arial, sans-serif !important;">
                                    <div class="flex flex-col h-full text-black">

                                        {{-- Título de la Hoja --}}
                                        <div class="text-center mb-16">
                                            <h2 class="font-bold pb-1 uppercase" style="font-size: 12pt;">
                                                AGRADECIMIENTOS
                                            </h2>
                                        </div>

                                        {{-- Cuerpo de la Presentación (Arial 12pt, Interlineado 1.5) --}}
                                        <div class="academic-text-wrapper text-justify" style="font-size: 12pt;">
                                            <p>
                                                Los autores de este proyecto expresan agradecimiento a:
                                            </p>

                                        </div>
                                    </div>
                                </div>

                                {{-- SEPTIMA HOJA: RESUMEN --}}
                                <div class="document-page mb-16 shadow-2xl animate-in fade-in duration-1000 font-sans"
                                    style="font-family: Arial, sans-serif !important;">
                                    <div class="flex flex-col h-full text-black">

                                        {{-- Título de la Hoja --}}
                                        <div class="text-center mb-16">
                                            <h2 class="font-bold pb-1 uppercase" style="font-size: 12pt;">
                                                RESUMEN
                                            </h2>
                                        </div>

                                        <div class="text-center">
                                            {{-- Escuela Profesional (Arial 12pt) --}}
                                            <p class="font-bold mt-1 uppercase" style="font-size: 12pt;">
                                                {{ $project->title ?? 'ESCUELA DE INGENIERÍA DE COMPUTACIÓN Y SISTEMAS' }}
                                            </p>
                                        </div>
                                        {{-- Contenedor de Autores --}}
                                        <div class="mx-auto mb-4" style="width: 12cm;"> {{-- Ancho fijo para forzar alineación vertical --}}
                                            <div class="text-center">
                                                <div class="mt-4 space-y-1"
                                                    style="font-size: 12pt; font-family: Arial, sans-serif;">
                                                    <p>Br. {{ $project->user->name }}</p>
                                                    @if ($project->second_author)
                                                        <p>Br. {{ $project->second_author }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>


                                        {{-- Cuerpo de la Presentación (Arial 12pt, Interlineado 1.5) --}}
                                        <div class="academic-text-wrapper text-justify" style="font-size: 12pt;">
                                            <p>
                                                Resumen
                                            </p>
                                            <p>
                                                Palabras Clave:
                                            </p>
                                        </div>


                                    </div>
                                </div>

                                {{-- OCTAVA HOJA: ABSTRACT --}}
                                <div class="document-page mb-16 shadow-2xl animate-in fade-in duration-1000 font-sans"
                                    style="font-family: Arial, sans-serif !important;">
                                    <div class="flex flex-col h-full text-black">

                                        {{-- Título de la Hoja --}}
                                        <div class="text-center mb-16">
                                            <h2 class="font-bold pb-1 uppercase" style="font-size: 12pt;">
                                                ABSTRACT
                                            </h2>
                                        </div>

                                        <div class="text-center">
                                            {{-- Escuela Profesional (Arial 12pt) --}}
                                            <p class="font-bold mt-1 uppercase" style="font-size: 12pt;">
                                                {{ $project->title ?? 'ESCUELA DE INGENIERÍA DE COMPUTACIÓN Y SISTEMAS' }}
                                            </p>
                                        </div>
                                        {{-- Contenedor de Autores --}}
                                        <div class="mx-auto mb-4" style="width: 12cm;"> {{-- Ancho fijo para forzar alineación vertical --}}
                                            <div class="text-center">
                                                <div class="mt-4 space-y-1"
                                                    style="font-size: 12pt; font-family: Arial, sans-serif;">
                                                    <p>Br. {{ $project->user->name }}</p>
                                                    @if ($project->second_author)
                                                        <p>Br. {{ $project->second_author }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>


                                        {{-- Cuerpo de la Presentación (Arial 12pt, Interlineado 1.5) --}}
                                        <div class="academic-text-wrapper text-justify" style="font-size: 12pt;">
                                            <p>
                                                Resumen
                                            </p>
                                            <p>
                                                Keywords:
                                            </p>
                                        </div>


                                    </div>
                                </div>

                                {{-- NOVENA HOJA: ÍNDICE GENERAL --}}
                                <div class="document-page mb-16 shadow-2xl animate-in fade-in duration-1000 font-sans"
                                    style="font-family: Arial, sans-serif !important;">
                                    <div class="flex flex-col h-full text-black uppercase">

                                        {{-- Título del Índice --}}
                                        <div class="text-center mb-12">
                                            <h2 class="font-bold border-b-2 border-black inline-block pb-1"
                                                style="font-size: 14pt;">
                                                ÍNDICE GENERAL
                                            </h2>
                                        </div>

                                        {{-- Cabecera de Tabla de Contenidos --}}
                                        <div class="flex justify-between font-bold mb-4" style="font-size: 12pt;">
                                            <span>CONTENIDO</span>
                                            <span>PÁG.</span>
                                        </div>

                                        {{-- Lista Dinámica --}}
                                        <div class="space-y-2" style="font-size: 12pt; font-family: Arial, sans-serif;">

                                            {{-- Páginas Preliminares (Manuales) --}}
                                            <div class="flex items-end">
                                                <span>PRESENTACIÓN</span>
                                                <div class="flex-1 border-b border-dotted border-black mx-2 mb-1"></div>
                                                <span>4</span>
                                            </div>

                                            <div class="flex items-end">
                                                <span>DEDICATORIA</span>
                                                <div class="flex-1 border-b border-dotted border-black mx-2 mb-1"></div>
                                                <span>5</span>
                                            </div>

                                            <div class="flex items-end">
                                                <span>AGRADECIMIENTOS</span>
                                                <div class="flex-1 border-b border-dotted border-black mx-2 mb-1"></div>
                                                <span>6</span>
                                            </div>

                                            <div class="flex items-end">
                                                <span>RESUMEN</span>
                                                <div class="flex-1 border-b border-dotted border-black mx-2 mb-1"></div>
                                                <span>7</span>
                                            </div>

                                            <div class="flex items-end">
                                                <span>ABSTRACT</span>
                                                <div class="flex-1 border-b border-dotted border-black mx-2 mb-1"></div>
                                                <span>8</span>
                                            </div>

                                            {{-- Pasos Dinámicos de la Tesis --}}
                                            @php
                                                // Empezamos en la página 10 (asumiendo que el índice es la 9)
                                                $currentPage = 10;
                                            @endphp

                                            @foreach ($project->steps->sortBy('order') as $step)
                                                <div class="flex items-end">
                                                    <span class="truncate uppercase">{{ $loop->iteration }}.
                                                        {{ $step->title }}</span>
                                                    <div class="flex-1 border-b border-dotted border-black mx-2 mb-1">
                                                    </div>
                                                    <span>{{ $currentPage }}</span>
                                                </div>
                                                @php
                                                    // Aquí podrías sumar más si el contenido del paso excede una hoja
                                                    $currentPage++;
                                                @endphp
                                            @endforeach

                                        </div>
                                    </div>
                                </div>
                                {{-- HOJAS DE CONTENIDO (ARIAL 12) --}}
                                @foreach ($project->steps->sortBy('order') as $step)
                                    <div id="step-{{ $step->id }}"
                                        class="document-page shadow-2xl shrink-0 h-fit min-h-[29.7cm] font-sans animate-in slide-in-from-bottom-10 duration-700">
                                        <h3 class="text-[12pt] font-bold uppercase mb-12 text-center text-gray-900">
                                            {{ $step->order }}. {{ $step->title }}
                                        </h3>

                                        <div class="academic-body prose-none text-justify text-[12pt] text-gray-800">
                                            @php
                                                $data = json_decode($step->content, true);
                                                $isTable =
                                                    str_contains(strtolower($step->title), 'cronograma') ||
                                                    str_contains(strtolower($step->title), 'presupuesto');
                                            @endphp

                                            @if ($isTable && is_array($data))
                                                @include(
                                                    'livewire.project.partials.' .
                                                        (str_contains(strtolower($step->title), 'cronograma')
                                                            ? 'preview-cronograma'
                                                            : 'preview-presupuesto'),
                                                    ['data' => $data]
                                                )
                                            @else
                                                <div class="academic-text-wrapper font-sans">
                                                    {!! $step->content !!}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @break
                        @endswitch

                    </div>
                </main>

        @endif


    </main>

    <aside :class="rightSidebar ? 'translate-x-0' : 'translate-x-full'"
        class="fixed inset-y-0 right-0 w-80 bg-white dark:bg-gray-900 border-l border-gray-200 dark:border-gray-800 p-6 flex flex-col shadow-2xl z-50 transition-transform duration-300 lg:relative lg:translate-x-0">

        {{-- Al final de tu lista de pasos --}}

        <x-common.button-submit type="button" wire:click="setViewMode('preview')" variant="brand"
            class="rounded-2xl shadow-lg shadow-brand-500/20 mb-8">
            <x-slot:icon>
                <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-width="2" />
                    <path
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                        stroke-width="2" />
                </svg>
            </x-slot:icon>
            Vista Previa y Exportar
        </x-common.button-submit>

        {{-- Header del Asistente --}}
        <div class="flex items-center justify-between lg:justify-start gap-4 mb-10">
            <div class="flex items-center gap-3">
                <div
                    class="w-8 h-8 bg-brand-600 rounded-xl flex items-center justify-center shadow-lg shadow-brand-500/30 text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M13 10V3L4 14h7v7l9-11h-7z" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-[11px] font-black text-gray-600 dark:text-white uppercase">
                        Asistente de IA</h3>
                    <p class="text-[9px] text-green-500 font-bold uppercase tracking-widest">IA Activa</p>
                </div>
            </div>
            <button @click="rightSidebar = false"
                class="lg:hidden text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M6 18L18 6M6 6l12 12" stroke-width="2" />
                </svg>
            </button>
        </div>

        {{-- Cuerpo de Herramientas --}}
        <div class="flex-1 space-y-4 overflow-y-auto no-scrollbar">
            <p class="text-[12px] font-bold text-gray-600 uppercase mb-2">Herramientas de Redacción</p>

            {{-- Botón: Generar Borrador --}}
            <button wire:click="generateDraft" wire:loading.attr="disabled"
                class="w-full p-5 bg-gray-50 dark:bg-gray-800/50 rounded-[2rem] text-left border-2 border-transparent hover:border-brand-500/20 hover:bg-white dark:hover:bg-gray-800 transition-all group relative overflow-hidden disabled:opacity-50 shadow-sm">

                <div wire:loading.remove wire:target="generateDraft">
                    <div class="flex items-center gap-2 mb-1">
                        <span
                            class="text-sm font-black text-gray-600 dark:text-white group-hover:text-brand-200 transition-colors tracking-tight">✨
                            Redactar Borrador</span>
                    </div>
                    <p class="text-[12px] text-gray-400 leading-relaxed font-medium">Genera contenido académico
                        base
                        para este paso.</p>
                </div>

                {{-- Loader Animado --}}
                <div wire:loading wire:target="generateDraft" class="flex flex-col items-center py-2 space-y-3">
                    <div class="flex space-x-1.5">
                        <div class="w-2 h-2 bg-brand-600 rounded-full animate-bounce [animation-delay:-0.3s]">
                        </div>
                        <div class="w-2 h-2 bg-brand-600 rounded-full animate-bounce [animation-delay:-0.15s]">
                        </div>
                        <div class="w-2 h-2 bg-brand-600 rounded-full animate-bounce"></div>
                    </div>
                    <p class="text-[10px] font-black text-brand-600 uppercase tracking-widest">IA está
                        pensando...</p>
                </div>
            </button>

            {{-- Botón: Parafrasear (Próximamente) --}}
            <button disabled
                class="w-full p-5 bg-gray-50/50 dark:bg-gray-800/30 rounded-[2rem] text-left border-2 border-dashed border-gray-200 dark:border-gray-700 opacity-60 cursor-not-allowed group">
                <div class="flex items-center gap-2">
                    <p class="text-xs font-black text-gray-400 dark:text-gray-500">📝 Parafrasear APA 7</p>
                    <span
                        class="text-[8px] bg-gray-200 dark:bg-gray-700 text-gray-500 px-1.5 py-0.5 rounded uppercase font-bold">Lock</span>
                </div>
                <p class="text-[10px] text-gray-400 mt-1 italic">Próximamente disponible</p>
            </button>

            {{-- Widget de Consumo (Sticky bottom) --}}
            <div class="mt-auto pt-6 border-t border-gray-100 dark:border-gray-800">
                <div
                    class="bg-gray-900 dark:bg-brand-600 rounded-[2.5rem] p-6 text-white  relative overflow-hidden group">
                    {{-- Decoración de fondo --}}
                    <div
                        class="absolute -right-4 -top-4 w-20 h-20 bg-white/10 rounded-full blur-2xl group-hover:bg-white/20 transition-all">
                    </div>

                    <div class="relative z-10">
                        <div class="flex justify-between items-end mb-4">
                            <div>
                                <p class="text-[9px] font-black text-brand-200/60 uppercase mb-1">
                                    Palabras
                                    Generadas</p>
                                <p class="text-3xl font-black italic tracking-tighter leading-none">
                                    {{ number_format($project->ai_words_used) }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-[8px] font-black text-brand-200/60 uppercase">Límite</p>
                                <p class="text-sm font-black tracking-tight">
                                    {{ number_format($project->ai_word_limit / 1000) }}K</p>
                            </div>
                        </div>

                        {{-- Barra de Progreso --}}
                        @php $percentage = ($project->ai_words_used / $project->ai_word_limit) * 100; @endphp
                        <div class="w-full bg-white/20 h-2.5 rounded-full overflow-hidden p-0.5 backdrop-blur-sm">
                            <div class="bg-white h-full rounded-full transition-all duration-1000 ease-out shadow-[0_0_15px_rgba(255,255,255,0.5)]"
                                style="width: {{ min($percentage, 100) }}%">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>


    </aside>
@else
    {{-- VISTA PREVIA CONFIGURADA: ARIAL 12 / SANGRÍA 2.5CM --}}
    <div class="flex flex-col h-screen w-full bg-[#f0f2f5] dark:bg-slate-950 overflow-hidden font-sans">

        {{-- Toolbar Superior --}}
        <header
            class="h-14 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between px-8 z-[60] shrink-0 shadow-sm">


            <a href="{{ route('projects.export-word', $project->id) }}"
                class="flex w-full justify-center items-center gap-2 rounded-lg px-6 py-2.5 text-sm font-medium transition-all sm:w-auto active:scale-95 disabled:opacity-70 disabled:cursor-not-allowed shadow-md bg-brand-500 text-white hover:bg-brand-600 shadow-brand-500/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <div class="flex flex-col items-start leading-tight">
                    <span class="text-xsuppercase tracking-widest">Exportar .DOCX</span>
                </div>
            </a>



            <x-common.button-submit type="button" wire:click="setViewMode('editor')" variant="brand"
                class="rounded-2xl shadow-lg shadow-brand-500/20">
                <x-slot:icon>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M10 19l-7-7m0 0l7-7m-7 7h18" stroke-width="3" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </x-slot:icon>
                Regresar al Editor
            </x-common.button-submit>
        </header>

        <div class="flex flex-1 overflow-hidden relative w-full">
            {{-- ÍNDICE LATERAL --}}
            <aside
                class="w-72 h-full bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-800 flex flex-col shrink-0 z-50 overflow-y-auto p-6">
                <nav class="flex-1 overflow-y-auto px-4 pb-6 space-y-2 no-scrollbar font-sans">

                    <button type="button" wire:click="selectPreviewStep(0)"
                        @click="const container = document.getElementById('preview-content');
                    const target = document.getElementById('portada');
                    if(target && container) {
                        const top = target.getBoundingClientRect().top - container.getBoundingClientRect().top + container.scrollTop;
                        container.scrollTo({ top: top - 20, behavior: 'smooth' });
                    }"
                        class="w-full flex items-center gap-3 p-3 rounded-2xl transition-all group {{ $previewStepId == 0 ? 'bg-brand-600 text-white shadow-lg' : 'text-gray-500 hover:bg-gray-100' }}">
                        <div
                            class="shrink-0 w-6 h-6 rounded-lg flex items-center justify-center text-[10px] font-bold {{ $previewStepId == 0 ? 'bg-white/20' : 'bg-gray-100' }}">

                        </div>
                        <span class="text-sm font-bold truncate text-left">Portada</span>
                    </button>

                    @foreach ($project->steps->sortBy('order') as $step)
                        <button type="button" wire:click="selectPreviewStep({{ $step->id }})"
                            @click="const container = document.getElementById('preview-content');
                        const target = document.getElementById('step-{{ $step->id }}');
                        if(target && container) {
                            const top = target.getBoundingClientRect().top - container.getBoundingClientRect().top + container.scrollTop;
                            container.scrollTo({ top: top - 20, behavior: 'smooth' });
                        }"
                            class="w-full flex items-center gap-3 p-3 rounded-2xl transition-all group {{ $previewStepId == $step->id ? 'bg-brand-600 text-white shadow-lg shadow-brand-500/40' : 'text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800' }}">

                            <div
                                class="shrink-0 w-6 h-6 rounded-lg flex items-center justify-center text-[10px] font-bold {{ $previewStepId == $step->id ? 'bg-white/20 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-400 group-hover:bg-brand-100 group-hover:text-brand-600' }}">
                                {{ $step->order }}
                            </div>

                            <span class="text-sm font-bold truncate text-left">{{ $step->title }}</span>
                        </button>
                    @endforeach
                </nav>
            </aside>

            {{-- LIENZO DERECHO --}}
            <main id="preview-content"
                class="flex-1 h-full overflow-y-auto preview-container flex flex-col items-center">
                <div class="py-12 px-4 w-full flex flex-col items-center gap-12">

                    @switch($project->document_type)
                        @case('PROYECTO DE TESIS')
                            {{-- PORTADA REGLAMENTARIA (ESTILO ARIAL WORD ACTUALIZADO) --}}
                            <div id="portada" class="document-page mb-16 animate-in zoom-in-95 duration-500 font-sans">
                                <div class="text-center flex flex-col h-full justify-between text-gray-900"
                                    style="font-family: Arial, Helvetica, sans-serif !important;">

                                    <div>
                                        {{-- Nombre de la Universidad (Arial 18pt) --}}
                                        <h1 class="font-bold leading-tight uppercase" style="font-size: 18pt;">
                                            {{ $project->university->nombre }}
                                        </h1>

                                        {{-- Facultad (Arial 16pt) --}}
                                        <p class="font-bold mt-2 uppercase" style="font-size: 16pt;">
                                            {{ $project->faculty ?? 'FACULTAD DE INGENIERÍA' }}
                                        </p>

                                        {{-- Escuela Profesional (Arial 12pt) --}}
                                        <p class="font-bold mt-1 uppercase" style="font-size: 12pt;">
                                            {{ $project->school ?? 'ESCUELA DE INGENIERÍA DE COMPUTACIÓN Y SISTEMAS' }}
                                        </p>

                                        {{-- LOGO DE LA UNIVERSIDAD --}}
                                        @if ($project->university->logo_path)
                                            <div class="flex justify-center my-6">
                                                <img src="{{ asset('storage/' . $project->university->logo_path) }}"
                                                    alt="Logo {{ $project->university->name }}"
                                                    class="h-40 w-auto object-contain">
                                            </div>
                                        @endif
                                    </div>

                                    <div class="px-8 flex flex-col gap-6">
                                        {{-- Tipo de Documento y Grado --}}
                                        <div class="mt-4">
                                            <p class=" mt-2 uppercase" style="font-size: 11pt;">
                                                PROYECTO DE TESIS PARA OBTENER EL TÍTULO PROFESIONAL DE: <br>
                                                ....................................................................
                                            </p>
                                        </div>
                                        {{-- Título del Proyecto con líneas arriba y abajo --}}
                                        <h2 class="leading-normal border-t border-b border-black py-2"
                                            style="font-size: 12pt; max-width: 100%; margin: 0 auto; font-family: Arial, sans-serif;">
                                            {{ $project->title }}
                                        </h2>
                                    </div>

                                    <div class="flex flex-col gap-10 mt-10">
                                        {{-- Contenedor de Linea de Investigación --}}
                                        <div class="mx-auto" style="width: 12cm;"> {{-- Ancho fijo para forzar alineación vertical --}}
                                            <div class="text-center">
                                                <p class="font-bold"
                                                    style="font-size: 12pt; font-family: Arial, sans-serif;">
                                                    Linea de
                                                    Investigación:</p>
                                                <div class="mt-4"
                                                    style="font-size: 12pt; font-family: Arial, sans-serif;">
                                                    <p>{{ $project->academic_line ?? 'No especificado' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- Contenedor de Autores --}}
                                        <div class="mx-auto" style="width: 12cm;"> {{-- Ancho fijo para forzar alineación vertical --}}
                                            <div class="text-center">
                                                <p class="font-bold"
                                                    style="font-size: 12pt; font-family: Arial, sans-serif;">
                                                    Autores:</p>
                                                <div class="mt-4 space-y-1"
                                                    style="font-size: 12pt; font-family: Arial, sans-serif;">
                                                    <p>Br. {{ $project->user->name }}</p>
                                                    @if ($project->second_author)
                                                        <p>Br. {{ $project->second_author }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Contenedor de Asesor --}}
                                        <div class="mx-auto" style="width: 12cm;"> {{-- Mismo ancho fijo que autores --}}
                                            <div class="text-center">
                                                <p class="font-bold"
                                                    style="font-size: 12pt; font-family: Arial, sans-serif;">
                                                    Asesor:</p>
                                                <div class="mt-4"
                                                    style="font-size: 12pt; font-family: Arial, sans-serif;">
                                                    <p>{{ $project->advisor ?? 'Ms. Ing. Sagastegui Chigne Teobaldo Hernan' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- Contenedor de codigo ORCID --}}
                                        <div class="mx-auto" style="width: 12cm;">
                                            <div class="text-center">
                                                <p class="font-bold"
                                                    style="font-size: 12pt; font-family: Arial, sans-serif;">
                                                    Código Orcid:
                                                </p>
                                                <div class="mt-4"
                                                    style="font-size: 12pt; font-family: Arial, sans-serif;">
                                                    ....................................................................
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Ciudad y Fecha --}}
                                        <div class="font-bold mt-12 pb-10"
                                            style="font-size: 12pt; font-family: Arial, sans-serif;">
                                            Trujillo - Perú <br>
                                            {{ now()->isoFormat('YYYY') }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- SEGUNDA HOJA: GENERALIDADES O PROGRAMA DE ESTUDIO --}}
                            <div class="document-page mb-16 shadow-2xl animate-in fade-in duration-1000 font-sans"
                                style="font-family: Arial, sans-serif !important;">
                                <div class="flex flex-col h-full text-black">

                                    {{-- Título Repetido (Arial 12pt Negrita) --}}
                                    <div class="text-center mb-16">
                                        <h2 class="font-bold leading-normal uppercase" style="font-size: 12pt;">
                                            GENERALIDADES O PROGRAMA DE ESTUDIO
                                        </h2>
                                    </div>


                                    {{-- Sección de Firmas del Jurado --}}
                                    <div class="space-y-12">
                                        <p class="mb-2" style="font-size: 12pt;">Titulo de Proyecto:
                                            {{ $project->title }}</p>

                                        <p class="mb-2" style="font-size: 12pt;">Resposables o Equipo Investigador:
                                            {{ $project->user->name }} @if ($project->second_author)
                                                <p>{{ $project->second_author }}</p>
                                            @endif
                                        </p>
                                        <p class="mb-2" style="font-size: 12pt;">Asesor: _____________________________
                                        </p>
                                        <p class="mb-2" style="font-size: 12pt;">Tipo de Investigación:
                                            _____________________________</p>
                                        <p class="mb-2" style="font-size: 12pt;">Linea de Investigación:
                                            {{ $project->academic_line ?? 'No especificado' }}</p>
                                        <p class="mb-2" style="font-size: 12pt;">Lugar y fecha de Presentación:
                                            _____________________________ </p>
                                        <p class="mb-12" style="font-size: 12pt;">Duración: _____________________________
                                        </p>




                                        {{-- Contenedor Principal: 2 columnas, espacio entre filas de 10 unidades --}}
                                        <div class="grid grid-cols-2 gap-y-10 gap-x-4 mt-8">

                                            {{-- 1. Presidente --}}
                                            <div style="font-size: 11pt;" class="mb-12">
                                                <p>Dr. _____________________________</p>
                                                <p class="mt-1">(Presidente)</p>
                                            </div>

                                            {{-- 2. Secretario --}}
                                            <div style="font-size: 11pt;" class="mb-12">
                                                <p>Dr. _____________________________</p>
                                                <p class="mt-1">(Secretario)</p>
                                            </div>

                                            {{-- 3. Vocal --}}
                                            <div style="font-size: 11pt;" class="mb-12">
                                                <p>Ms. _____________________________</p>
                                                <p class="mt-1">(Vocal)</p>
                                            </div>

                                            {{-- 4. Ejemplo Vocal 2 --}}
                                            <div style="font-size: 11pt;" class="mb-12">
                                                <p>Dr. _____________________________</p>
                                                <p class="mt-1">(Vocal 2)</p>
                                            </div>

                                            {{-- 5. Ejemplo Asesor --}}
                                            <div style="font-size: 11pt;" class="mb-12">
                                                <p>Dr. _____________________________</p>
                                                <p class="mt-1">(Asesor)</p>
                                            </div>

                                            {{-- 6. Ejemplo Jurado --}}
                                            <div style="font-size: 11pt;" class="mb-12">
                                                <p>Dr. _____________________________</p>
                                                <p class="mt-1">(Jurado Externo)</p>
                                            </div>

                                        </div>

                                    </div>



                                </div>
                            </div>

                            {{-- TERCERA HOJA: ÍNDICE GENERAL --}}
                            <div class="document-page mb-16 shadow-2xl animate-in fade-in duration-1000 font-sans"
                                style="font-family: Arial, sans-serif !important;">
                                <div class="flex flex-col h-full text-black uppercase">

                                    {{-- INDICE --}}
                                    <div class="text-center mb-16">
                                        <h2 class="font-bold leading-normal uppercase" style="font-size: 12pt;">
                                            ÍNDICE
                                        </h2>
                                    </div>


                                    {{-- Lista Dinámica --}}
                                    <div class="space-y-2" style="font-size: 12pt; font-family: Arial, sans-serif;">

                                        {{-- Pasos Dinámicos de la Tesis --}}
                                        @php
                                            // Empezamos en la página 10 (asumiendo que el índice es la 9)
                                            $currentPage = 3;
                                        @endphp

                                        @foreach ($project->steps->sortBy('order') as $step)
                                            <div class="flex items-end">
                                                <span class="truncate uppercase">{{ $loop->iteration }}.
                                                    {{ $step->title }}</span>
                                                <div class="flex-1 border-b border-dotted border-black mx-2 mb-1"></div>
                                                <span>{{ $currentPage }}</span>
                                            </div>
                                            @php
                                                // Aquí podrías sumar más si el contenido del paso excede una hoja
                                                $currentPage++;
                                            @endphp
                                        @endforeach

                                    </div>
                                </div>
                            </div>

                            {{-- HOJAS DE CONTENIDO --}}
                            @foreach ($project->steps->sortBy('order') as $step)
                                <div id="step-{{ $step->id }}"
                                    class="document-page shadow-2xl shrink-0 font-sans animate-in slide-in-from-bottom-10 duration-700">

                                    {{-- Título de la Sección --}}
                                    <h3 class="text-[12pt] font-bold uppercase mb-12 text-center text-gray-900">
                                        {{ $step->title }}
                                    </h3>

                                    <div class="academic-body prose-none text-justify text-[12pt] text-gray-800">
                                        @php
                                            $data = json_decode($step->content, true);
                                            $isTable =
                                                str_contains(strtolower($step->title), 'cronograma') ||
                                                str_contains(strtolower($step->title), 'presupuesto');
                                        @endphp

                                        @if ($isTable && is_array($data))
                                            {{-- Tus tablas --}}
                                            @include(
                                                'livewire.project.partials.preview-' .
                                                    (str_contains(strtolower($step->title), 'cronograma')
                                                        ? 'cronograma'
                                                        : 'presupuesto'),
                                                ['data' => $data]
                                            )
                                        @else
                                            <div class="academic-text-wrapper">
                                                {!! $step->content !!}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @break

                        @default
                            {{-- PORTADA REGLAMENTARIA (ESTILO ARIAL WORD ACTUALIZADO) --}}
                            <div id="portada" class="document-page mb-16 animate-in zoom-in-95 duration-500 font-sans">
                                <div class="text-center flex flex-col h-full justify-between text-gray-900"
                                    style="font-family: Arial, Helvetica, sans-serif !important;">

                                    <div>
                                        {{-- Nombre de la Universidad (Arial 18pt) --}}
                                        <h1 class="font-bold leading-tight uppercase" style="font-size: 18pt;">
                                            {{ $project->university->nombre }}
                                        </h1>

                                        {{-- Facultad (Arial 16pt) --}}
                                        <p class="font-bold mt-2 uppercase" style="font-size: 16pt;">
                                            {{ $project->faculty ?? 'FACULTAD DE INGENIERÍA' }}
                                        </p>

                                        {{-- Escuela Profesional (Arial 12pt) --}}
                                        <p class="font-bold mt-1 uppercase" style="font-size: 12pt;">
                                            {{ $project->school ?? 'ESCUELA DE INGENIERÍA DE COMPUTACIÓN Y SISTEMAS' }}
                                        </p>

                                        {{-- LOGO DE LA UNIVERSIDAD --}}
                                        @if ($project->university->logo_path)
                                            <div class="flex justify-center my-6">
                                                <img src="{{ asset('storage/' . $project->university->logo_path) }}"
                                                    alt="Logo {{ $project->university->name }}"
                                                    class="h-40 w-auto object-contain">
                                            </div>
                                        @endif
                                    </div>

                                    <div class="px-8 flex flex-col gap-6">
                                        {{-- Título del Proyecto (Arial 12pt) --}}
                                        <h2 class="font-bold leading-normal uppercase"
                                            style="font-size: 12pt; max-width: 90%; margin: 0 auto;">
                                            {{ $project->title }}
                                        </h2>

                                        {{-- Tipo de Documento y Grado --}}
                                        <div class="mt-4">
                                            <p class="font-bold uppercase" style="font-size: 14pt;">
                                                {{ $project->document_type === 'PROYECTO DE TESIS' ? 'PROYECTO DE TESIS' : 'TESIS' }}
                                            </p>
                                            <p class="font-bold mt-2 uppercase" style="font-size: 11pt;">
                                                PARA OBTENER EL TÍTULO PROFESIONAL EN <br>
                                                {{ $project->school ?? 'ESCUELA SIN ESPECIFICAR' }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex flex-col gap-10 mt-10">
                                        {{-- Contenedor de Autores --}}
                                        <div class="mx-auto" style="width: 12cm;"> {{-- Ancho fijo para forzar alineación vertical --}}
                                            <div class="text-left">
                                                <p class="font-bold uppercase"
                                                    style="font-size: 12pt; font-family: Arial, sans-serif;">AUTORES:</p>
                                                <div class="mt-4 space-y-1"
                                                    style="font-size: 12pt; font-family: Arial, sans-serif;">
                                                    <p>Br. {{ $project->user->name }}</p>
                                                    @if ($project->second_author)
                                                        <p>Br. {{ $project->second_author }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Contenedor de Asesor --}}
                                        <div class="mx-auto" style="width: 12cm;"> {{-- Mismo ancho fijo que autores --}}
                                            <div class="text-left">
                                                <p class="font-bold uppercase"
                                                    style="font-size: 12pt; font-family: Arial, sans-serif;">ASESOR:</p>
                                                <div class="mt-4"
                                                    style="font-size: 12pt; font-family: Arial, sans-serif;">
                                                    <p>{{ $project->advisor ?? 'Ms. Ing. Sagastegui Chigne Teobaldo Hernan' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Ciudad y Fecha --}}
                                        <div class="font-bold mt-12 pb-10"
                                            style="font-size: 12pt; font-family: Arial, sans-serif;">
                                            Trujillo, {{ now()->isoFormat('D [de] MMMM [del] YYYY') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- SEGUNDA HOJA: PÁGINA DE JURADO Y APROBACIÓN --}}
                            <div class="document-page mb-16 shadow-2xl animate-in fade-in duration-1000 font-sans"
                                style="font-family: Arial, sans-serif !important;">
                                <div class="flex flex-col h-full text-black">

                                    {{-- Título Repetido (Arial 12pt Negrita) --}}
                                    <div class="text-center mb-16">
                                        <h2 class="font-bold leading-normal uppercase" style="font-size: 12pt;">
                                            {{ $project->title }}
                                        </h2>
                                    </div>

                                    {{-- Autores (Alineados a la derecha según el documento) --}}
                                    <div class="flex justify-end mb-20">
                                        <div class="text-left" style="font-size: 11pt; width: 8cm;">
                                            <p class="mb-2">Por: Br. {{ $project->user->name }}</p>
                                            @if ($project->second_author)
                                                <p>Br. {{ $project->second_author }}</p>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Sección de Firmas del Jurado --}}
                                    <div class="space-y-12">
                                        <p class="font-bold mb-8" style="font-size: 12pt;">Aprobado:</p>

                                        {{-- Presidente --}}
                                        <div class="flex justify-between items-end">
                                            <div style="font-size: 11pt;">
                                                <p>Dr. _____________________________</p>
                                                <p class="mt-1">(Presidente)</p>
                                            </div>
                                        </div>

                                        {{-- Secretario --}}
                                        <div class="flex justify-between items-end">
                                            <div style="font-size: 11pt;">
                                                <p>Dr. _____________________________</p>
                                                <p class="mt-1">(Secretario)</p>
                                            </div>
                                        </div>

                                        {{-- Vocal --}}
                                        <div class="flex justify-between items-end">
                                            <div style="font-size: 11pt;">
                                                <p>Ms. _____________________________</p>
                                                <p class="mt-1">(Vocal)</p>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Espacio para el Asesor al final --}}
                                    <div class="mt-auto pb-20">
                                        <div style="font-size: 11pt;">
                                            <p class="mb-8">Asesor:</p>
                                            <div class="mt-10">

                                                <p class="mt-2">{{ $project->advisor ?? 'ASESOR SIN ESPECIFICAR' }}</p>
                                                <p>…………………………………………..</p>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            {{-- TERCERA HOJA: ACREDITACIÓN DEL ASESOR --}}
                            <div class="document-page mb-16 shadow-2xl animate-in fade-in duration-1000 font-sans"
                                style="font-family: Arial, sans-serif !important;">
                                <div class="flex flex-col h-full text-black uppercase">

                                    {{-- Título de la Hoja --}}
                                    <div class="text-center mb-16">
                                        <h2 class="font-bold pb-1" style="font-size: 12pt;">
                                            ACREDITACIÓN
                                        </h2>
                                    </div>

                                    {{-- Cuerpo de la Acreditación (Arial 12pt, Interlineado 1.5) --}}
                                    <div class="academic-text-wrapper text-justify"
                                        style="font-size: 12pt; line-height: 1.5; text-transform: none;">
                                        <p style="text-indent: 2.5cm; margin-bottom: 2rem;">
                                            El
                                            <strong>{{ $project->advisor ?? '(ASESOR SIN ESPECIFICAR)' }}</strong>,
                                            que suscribe, asesor de la Tesis con Título
                                            <strong>“{{ mb_strtoupper($project->title) }}”</strong>,
                                            desarrollado por los <strong>Br. {{ $project->user->name }}</strong>
                                            @if ($project->second_author)
                                                y <strong>{{ $project->second_author }}</strong>
                                            @endif
                                            en
                                            <strong>{{ $project->school ?? '(ESCUELA SIN ESPECIFICAR)' }}</strong>,
                                            acredita haber realizado las observaciones y recomendaciones pertinentes,
                                            encontrándose expedita para su revisión por parte de los señores miembros
                                            del Jurado Evaluador.
                                        </p>
                                    </div>

                                    {{-- Ciudad y Fecha --}}
                                    <div class="text-right font-bold mt-10 pb-10"
                                        style="font-size: 12pt; text-transform: none;">
                                        Trujillo, {{ now()->isoFormat('D [de] MMMM [del] YYYY') }}
                                    </div>

                                </div>
                            </div>

                            {{-- CUARTA HOJA: PRESENTACIÓN AL JURADO --}}
                            <div class="document-page mb-16 shadow-2xl animate-in fade-in duration-1000 font-sans"
                                style="font-family: Arial, sans-serif !important;">
                                <div class="flex flex-col h-full text-black">

                                    {{-- Título de la Hoja --}}
                                    <div class="text-center mb-16">
                                        <h2 class="font-bold pb-1 uppercase" style="font-size: 12pt;">
                                            PRESENTACIÓN
                                        </h2>
                                    </div>

                                    {{-- Cuerpo de la Presentación (Arial 12pt, Interlineado 1.5) --}}
                                    <div class="academic-text-wrapper text-justify" style="font-size: 12pt;">
                                        <p class="mb-8">Señores miembros del Jurado Dictaminador:</p>

                                        <p style="margin-bottom: 2rem;">
                                            Dando cumplimiento a las normas del Reglamento de Grados y Títulos de la
                                            <strong>{{ $project->university->nombre }}</strong>,
                                            presentamos a vuestra consideración el Trabajo de Tesis titulado:
                                            <strong>“{{ mb_strtoupper($project->title) }}”</strong>,
                                            con el fin de obtener el Título Profesional de
                                            <strong>{{ $project->school ?? '(ESCUELA SIN ESPECIFICAR)' }}</strong>.
                                        </p>
                                        <p>Gracias.</p>

                                    </div>

                                    {{-- Bloque de Firmas de los Autores --}}
                                    <div class="mt-auto flex flex-col items-center gap-16 pb-20 uppercase">
                                        <div class="flex justify-around w-full px-10">
                                            {{-- Firma Autor 1 --}}
                                            <div class="text-center w-64">
                                                <p class="mb-1">__________________________</p>
                                                <p class="font-bold" style="font-size: 10pt;">Br.
                                                    {{ $project->user->name }}</p>
                                            </div>

                                            {{-- Firma Autor 2 (Si existe) --}}
                                            @if ($project->second_author)
                                                <div class="text-center w-64">
                                                    <p class="mb-1">__________________________</p>
                                                    <p class="font-bold" style="font-size: 10pt;">Br.
                                                        {{ $project->second_author }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Ciudad y Fecha --}}
                                    <div class="text-right font-bold mt-10 pb-10" style="font-size: 12pt;">
                                        Trujillo, {{ now()->isoFormat('D [de] MMMM [del] YYYY') }}
                                    </div>

                                </div>
                            </div>

                            {{-- QUINTA HOJA: DEDICATORIA --}}
                            <div class="document-page mb-16 shadow-2xl animate-in fade-in duration-1000 font-sans"
                                style="font-family: Arial, sans-serif !important;">
                                <div class="flex flex-col h-full text-black">

                                    {{-- Título de la Hoja --}}
                                    <div class="text-center mb-16">
                                        <h2 class="font-bold pb-1 uppercase" style="font-size: 12pt;">
                                            DEDICATORIA
                                        </h2>
                                    </div>

                                    {{-- Cuerpo de la Presentación (Arial 12pt, Interlineado 1.5) --}}
                                    <div class="academic-text-wrapper text-justify" style="font-size: 12pt;">
                                        <p>
                                            A mis padres, por su amor, paciencia y apoyo incondicional en cada paso de
                                            mi formación.
                                            A Dios, por darme la vida y la sabiduría necesaria para alcanzar esta meta
                                            profesional.
                                            Este logro es tan suyo como mío.
                                        </p>
                                        {{-- Nombre del Bachiller 1 --}}
                                        <p class="text-right font-bold mt-4" style="font-size: 11pt;">
                                            {{ $project->user->name }}
                                        </p>
                                    </div>

                                    {{-- Espacio para el Bachiller 2 (Si existe en el proyecto) --}}
                                    @if ($project->second_author)
                                        <div class="flex justify-end mt-24">
                                            <div class="text-justify italic"
                                                style="font-size: 12pt; line-height: 1.5; width: 10cm; border-t border-gray-100 pt-10">
                                                <p>
                                                    A mi familia, por ser mi motor y motivación constante. A mis amigos, por
                                                    los momentos
                                                    compartidos y el aliento recibido durante estos años de estudio.
                                                </p>
                                            </div>
                                        </div>
                                        {{-- Nombre del Bachiller 1 --}}
                                        <p class="text-right font-bold mt-4" style="font-size: 11pt;">
                                            {{ $project->second_author }}
                                        </p>
                                    @endif

                                </div>
                            </div>

                            {{-- SEXTA HOJA: AGRADECIMIENTOS --}}
                            <div class="document-page mb-16 shadow-2xl animate-in fade-in duration-1000 font-sans"
                                style="font-family: Arial, sans-serif !important;">
                                <div class="flex flex-col h-full text-black">

                                    {{-- Título de la Hoja --}}
                                    <div class="text-center mb-16">
                                        <h2 class="font-bold pb-1 uppercase" style="font-size: 12pt;">
                                            AGRADECIMIENTOS
                                        </h2>
                                    </div>

                                    {{-- Cuerpo de la Presentación (Arial 12pt, Interlineado 1.5) --}}
                                    <div class="academic-text-wrapper text-justify" style="font-size: 12pt;">
                                        <p>
                                            Los autores de este proyecto expresan agradecimiento a:
                                        </p>

                                    </div>
                                </div>
                            </div>

                            {{-- SEPTIMA HOJA: RESUMEN --}}
                            <div class="document-page mb-16 shadow-2xl animate-in fade-in duration-1000 font-sans"
                                style="font-family: Arial, sans-serif !important;">
                                <div class="flex flex-col h-full text-black">

                                    {{-- Título de la Hoja --}}
                                    <div class="text-center mb-16">
                                        <h2 class="font-bold pb-1 uppercase" style="font-size: 12pt;">
                                            RESUMEN
                                        </h2>
                                    </div>

                                    <div class="text-center">
                                        {{-- Escuela Profesional (Arial 12pt) --}}
                                        <p class="font-bold mt-1 uppercase" style="font-size: 12pt;">
                                            {{ $project->title ?? 'ESCUELA DE INGENIERÍA DE COMPUTACIÓN Y SISTEMAS' }}
                                        </p>
                                    </div>
                                    {{-- Contenedor de Autores --}}
                                    <div class="mx-auto mb-4" style="width: 12cm;"> {{-- Ancho fijo para forzar alineación vertical --}}
                                        <div class="text-center">
                                            <div class="mt-4 space-y-1"
                                                style="font-size: 12pt; font-family: Arial, sans-serif;">
                                                <p>Br. {{ $project->user->name }}</p>
                                                @if ($project->second_author)
                                                    <p>Br. {{ $project->second_author }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>


                                    {{-- Cuerpo de la Presentación (Arial 12pt, Interlineado 1.5) --}}
                                    <div class="academic-text-wrapper text-justify" style="font-size: 12pt;">
                                        <p>
                                            Resumen
                                        </p>
                                        <p>
                                            Palabras Clave:
                                        </p>
                                    </div>


                                </div>
                            </div>

                            {{-- OCTAVA HOJA: ABSTRACT --}}
                            <div class="document-page mb-16 shadow-2xl animate-in fade-in duration-1000 font-sans"
                                style="font-family: Arial, sans-serif !important;">
                                <div class="flex flex-col h-full text-black">

                                    {{-- Título de la Hoja --}}
                                    <div class="text-center mb-16">
                                        <h2 class="font-bold pb-1 uppercase" style="font-size: 12pt;">
                                            ABSTRACT
                                        </h2>
                                    </div>

                                    <div class="text-center">
                                        {{-- Escuela Profesional (Arial 12pt) --}}
                                        <p class="font-bold mt-1 uppercase" style="font-size: 12pt;">
                                            {{ $project->title ?? 'ESCUELA DE INGENIERÍA DE COMPUTACIÓN Y SISTEMAS' }}
                                        </p>
                                    </div>
                                    {{-- Contenedor de Autores --}}
                                    <div class="mx-auto mb-4" style="width: 12cm;"> {{-- Ancho fijo para forzar alineación vertical --}}
                                        <div class="text-center">
                                            <div class="mt-4 space-y-1"
                                                style="font-size: 12pt; font-family: Arial, sans-serif;">
                                                <p>Br. {{ $project->user->name }}</p>
                                                @if ($project->second_author)
                                                    <p>Br. {{ $project->second_author }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>


                                    {{-- Cuerpo de la Presentación (Arial 12pt, Interlineado 1.5) --}}
                                    <div class="academic-text-wrapper text-justify" style="font-size: 12pt;">
                                        <p>
                                            Resumen
                                        </p>
                                        <p>
                                            Keywords:
                                        </p>
                                    </div>


                                </div>
                            </div>

                            {{-- NOVENA HOJA: ÍNDICE GENERAL --}}
                            <div class="document-page mb-16 shadow-2xl animate-in fade-in duration-1000 font-sans"
                                style="font-family: Arial, sans-serif !important;">
                                <div class="flex flex-col h-full text-black uppercase">

                                    {{-- Título del Índice --}}
                                    <div class="text-center mb-12">
                                        <h2 class="font-bold border-b-2 border-black inline-block pb-1"
                                            style="font-size: 14pt;">
                                            ÍNDICE GENERAL
                                        </h2>
                                    </div>

                                    {{-- Cabecera de Tabla de Contenidos --}}
                                    <div class="flex justify-between font-bold mb-4" style="font-size: 12pt;">
                                        <span>CONTENIDO</span>
                                        <span>PÁG.</span>
                                    </div>

                                    {{-- Lista Dinámica --}}
                                    <div class="space-y-2" style="font-size: 12pt; font-family: Arial, sans-serif;">

                                        {{-- Páginas Preliminares (Manuales) --}}
                                        <div class="flex items-end">
                                            <span>PRESENTACIÓN</span>
                                            <div class="flex-1 border-b border-dotted border-black mx-2 mb-1"></div>
                                            <span>4</span>
                                        </div>

                                        <div class="flex items-end">
                                            <span>DEDICATORIA</span>
                                            <div class="flex-1 border-b border-dotted border-black mx-2 mb-1"></div>
                                            <span>5</span>
                                        </div>

                                        <div class="flex items-end">
                                            <span>AGRADECIMIENTOS</span>
                                            <div class="flex-1 border-b border-dotted border-black mx-2 mb-1"></div>
                                            <span>6</span>
                                        </div>

                                        <div class="flex items-end">
                                            <span>RESUMEN</span>
                                            <div class="flex-1 border-b border-dotted border-black mx-2 mb-1"></div>
                                            <span>7</span>
                                        </div>

                                        <div class="flex items-end">
                                            <span>ABSTRACT</span>
                                            <div class="flex-1 border-b border-dotted border-black mx-2 mb-1"></div>
                                            <span>8</span>
                                        </div>

                                        {{-- Pasos Dinámicos de la Tesis --}}
                                        @php
                                            // Empezamos en la página 10 (asumiendo que el índice es la 9)
                                            $currentPage = 10;
                                        @endphp

                                        @foreach ($project->steps->sortBy('order') as $step)
                                            <div class="flex items-end">
                                                <span class="truncate uppercase">{{ $loop->iteration }}.
                                                    {{ $step->title }}</span>
                                                <div class="flex-1 border-b border-dotted border-black mx-2 mb-1"></div>
                                                <span>{{ $currentPage }}</span>
                                            </div>
                                            @php
                                                // Aquí podrías sumar más si el contenido del paso excede una hoja
                                                $currentPage++;
                                            @endphp
                                        @endforeach

                                    </div>
                                </div>
                            </div>
                            {{-- HOJAS DE CONTENIDO (ARIAL 12) --}}
                            @foreach ($project->steps->sortBy('order') as $step)
                                <div id="step-{{ $step->id }}"
                                    class="document-page shadow-2xl shrink-0 h-fit min-h-[29.7cm] font-sans animate-in slide-in-from-bottom-10 duration-700">
                                    <h3 class="text-[12pt] font-bold uppercase mb-12 text-center text-gray-900">
                                        {{ $step->order }}. {{ $step->title }}
                                    </h3>

                                    <div class="academic-body prose-none text-justify text-[12pt] text-gray-800">
                                        @php
                                            $data = json_decode($step->content, true);
                                            $isTable =
                                                str_contains(strtolower($step->title), 'cronograma') ||
                                                str_contains(strtolower($step->title), 'presupuesto');
                                        @endphp

                                        @if ($isTable && is_array($data))
                                            @include(
                                                'livewire.project.partials.' .
                                                    (str_contains(strtolower($step->title), 'cronograma')
                                                        ? 'preview-cronograma'
                                                        : 'preview-presupuesto'),
                                                ['data' => $data]
                                            )
                                        @else
                                            <div class="academic-text-wrapper font-sans">
                                                {!! $step->content !!}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @break
                    @endswitch

                </div>
            </main>
        </div>
    </div>
@endif

<style>
    /* 1. Reset Tipográfico y Tamaño Global */
    .preview-container,
    .document-page,
    .academic-text-wrapper {
        font-family: Arial, Helvetica, sans-serif !important;
        color: #1a1a1a;
    }

    /* 2. Dimensiones y Márgenes (Exactos: Izq 3cm, Resto 2.5cm) */
    .document-page {
        width: 21cm;
        /* Cambiamos min-height por height: auto para que la hoja crezca */
        min-height: 29.7cm;
        height: auto;
        padding: 2.5cm 2.5cm 2.5cm 3cm;
        background: white;
        margin: 20px auto;
        /* Para que se centren las hojas en pantalla */
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        position: relative;
        box-sizing: border-box;
    }

    /* 3. Estilo de Párrafos: Sangría 2.5cm, Interlineado 1.5, Doble espacio entre párrafos */
    .academic-text-wrapper p {
        font-size: 12pt;
        /* Letra Arial 12 */
        line-height: 1.5;
        /* Interlineado 1.5 */
        text-indent: 2.5cm;
        /* Sangría 1ra línea 2.5cm */
        margin-bottom: 1.5rem;
        /* Doble espacio entre párrafos (aprox) */
        text-align: justify;
        /* Justificado */
        display: block;
        word-wrap: break-word;
        overflow-wrap: break-word;
        orphans: 3;
        widows: 3;
    }

    /* 4. Títulos (Opcional: quitar sangría a títulos para que se vean bien) */
    .academic-text-wrapper h1,
    .academic-text-wrapper h2,
    .academic-text-wrapper h3 {
        text-indent: 0 !important;
        margin-top: 1rem;
        margin-bottom: 1rem;
        line-height: 1.2;
    }

    /* 5. Navegación (Sidebar) */
    .outline-link {
        display: block;
        padding: 0.75rem 1rem;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        color: #64748b;
        border-radius: 0.75rem;
        text-decoration: none;
    }

    .outline-link.active {
        background: #eff6ff;
        color: #2563eb;
        box-shadow: inset 4px 0 0 #2563eb;
    }

    /* Regla para impresión */
    @media print {
        .document-page {
            box-shadow: none;
            border: none;
            margin: 0;
            padding: 2.5cm 2.5cm 2.5cm 3cm;
        }
    }
</style>
</div>
