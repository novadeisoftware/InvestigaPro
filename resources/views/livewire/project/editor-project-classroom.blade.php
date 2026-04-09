<div class="flex bg-[#F3F4F6] dark:bg-gray-950 overflow-hidden" x-data="{ leftSidebar: false, rightSidebar: false, loading: false }"
    x-on:draft-generated.window="loading = false" class="relative">

    <div x-show="leftSidebar || rightSidebar" @click="leftSidebar = false; rightSidebar = false"
        class="fixed inset-0 bg-black/50 z-40 lg:hidden" x-transition></div>

    {{-- ASIDE IZQUIERDO: PASOS DEL AULA --}}
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

    {{-- ÁREA CENTRAL: EDITOR --}}
    <main class="flex-1 flex flex-col min-w-0  relative bg-[#F3F4F6] dark:bg-gray-950">


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
                        <div class="p-5 bg-amber-50/50 dark:bg-amber-900/10 border-l-4 border-amber-400 rounded-r-2xl">
                            <p
                                class="text-[8px] lg:text-[10px] font-black text-amber-600 uppercase mb-1 tracking-widest">
                                Guía {{ $project->university->siglas ?? 'InvestigaPro' }}
                            </p>
                            <p class="text-xs lg:text-sm text-amber-800 dark:text-amber-200 italic leading-relaxed">
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
                                            <thead class="bg-gray-50 dark:bg-gray-800/50 text-gray-400 font-black uppercase">
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
                                    <thead class="bg-gray-50 dark:bg-gray-800/50 text-gray-400 font-black uppercase">
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
                        {{-- 2. EL EDITOR SIMPLIFICADO (SOLO NOTAS) --}}
                        <div wire:ignore wire:key="wrapper-{{ $currentStepId }}" x-data="{
                            content: @entangle('content').live,
                            showNotePortal: false,
                            pendingTextHtml: '',
                        
                            abrirModalNota() {
                                let editor = tinymce.get('tinymce-editor');
                                if (!editor) return;
                        
                                this.pendingTextHtml = editor.selection.getContent({ format: 'html' });
                        
                                if (!this.pendingTextHtml || this.pendingTextHtml.trim().length === 0) {
                                    Swal.fire({ title: '¡Espera!', text: 'Selecciona un texto primero.', icon: 'warning', confirmButtonColor: '#4F46E5' });
                                    return;
                                }
                        
                                this.showNotePortal = true;
                                window.dispatchEvent(new CustomEvent('open-note-modal'));
                            },
                        
                            irALaNota(notaId) {
                                let editor = tinymce.get('tinymce-editor');
                                if (!editor) return;
                                let span = editor.dom.select(`span[data-id='${notaId}']`)[0];
                                if (span) {
                                    editor.selection.select(span);
                                    span.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                    const originalStyle = span.style.cssText;
                                    span.style.backgroundColor = '#4F46E5';
                                    span.style.color = '#FFFFFF';
                                    setTimeout(() => { span.style.cssText = originalStyle; }, 2000);
                                }
                            },
                        
                            // --- ESTA FUNCIÓN ELIMINA EL SPAN MANTENIENDO EL TEXTO ---
                            // Dentro del objeto x-data del editor...
                            eliminarResaltado(notaId) {
                                let editor = tinymce.get('tinymce-editor');
                                if (!editor) return;
                        
                                // Buscamos el span por el data-id
                                let span = editor.dom.select(`span[data-id='${notaId}']`)[0];
                        
                                if (span) {
                                    // 'true' indica que borre la etiqueta span pero mantenga el texto interno
                                    editor.dom.remove(span, true);
                        
                                    // Actualizamos el contenido en Livewire
                                    this.content = editor.getContent();
                                    this.$wire.updateContentFromAdvisor(this.content);
                                }
                            },
                            
                        
                            initTiny() {
                                if (window.tinymce) { tinymce.remove(); }
                                setTimeout(() => {
                                    tinymce.init({
                                        license_key: 'gpl',
                                        base_url: '/js/tinymce',
                                        suffix: '.min',
                                        selector: '#tinymce-editor',
                                        height: 620,
                                        language: 'es',
                                        menubar: false,
                                        branding: false,
                                        promotion: false,
                                        toolbar: false,
                                        elementpath: false,
                                        verify_html: false,
                                        extended_valid_elements: 'span[class|style|title|data-id]',
                                        content_style: `
                                                                                            body { font-family:Inter,sans-serif; font-size:14px; line-height:1.6; }
                                                                                            .highlight-suggest { background-color: #fef08a; border-bottom: 2px dashed #ca8a04; color: #854d0e; padding: 2px 0; }
                                                                                        `,
                                        setup: (editor) => {
                                            // Definimos solo el botón de nota
                                            editor.ui.registry.addButton('add_note_portal', {
                                                icon: 'comment',
                                                tooltip: 'Escribir Nota',
                                                onAction: () => this.abrirModalNota()
                                            });
                        
                                            // Menú contextual: SOLO aparece el botón de nota al seleccionar
                                            editor.ui.registry.addContextToolbar('advisor_menu', {
                                                predicate: (node) => !editor.selection.isCollapsed(),
                                                items: 'add_note_portal',
                                                position: 'selection',
                                                scope: 'node'
                                            });
                        
                                            editor.on('init', () => editor.setContent(this.content || ''));
                        
                                            // Bloquear escritura pero permitir comandos
                                            editor.on('keydown', (e) => {
                                                if (!e.ctrlKey && !e.metaKey) {
                                                    e.preventDefault();
                                                    return false;
                                                }
                                            });
                                        }
                                    });
                                }, 100);
                            }
                        }"
                            x-init="initTiny()" @navegar-a-nota.window="irALaNota($event.detail.id)"
                            @close-note-modal.window="
                               let editor = tinymce.get('tinymce-editor');
                               if (editor && pendingTextHtml) {
                                   showNotePortal = false; 
                                   const idNota = $event.detail.id; 
                                   const comentario = $event.detail.comment;
                                   // Insertamos siempre con la clase amarilla (highlight-suggest)
                                   const highlight = `<span data-id='${idNota}' class='highlight-suggest' title='${comentario}'>${pendingTextHtml}</span>`;
                                   editor.execCommand('mceInsertContent', false, highlight);
                                   content = editor.getContent();
                                   $wire.updateContentFromAdvisor(content);
                                   pendingTextHtml = '';
                               }
                           "
                           @note-deleted.window="eliminarResaltado($event.detail.id)">
                            <textarea id="tinymce-editor" wire:ignore></textarea>
                        </div>

                    </div>

                    <style>
                        /* Estilo de resaltado suave y moderno */
                        .highlight-error {
                            background-color: rgba(0, 0, 0, 0.15) !important;
                            color: #b91c1c !important;
                            border-bottom: 2px solid #ef4444;
                            padding: 0 2px;
                        }

                        .highlight-suggest {
                            background-color: rgba(250, 204, 21, 0.2) !important;
                            /* Amarillo Soft */
                            color: #854d0e !important;
                            border-bottom: 2px dashed #facc15;
                            padding: 0 2px;
                        }

                        .highlight-success {
                            background-color: rgba(34, 197, 94, 0.15) !important;
                            color: #15803d !important;
                            border-bottom: 2px solid #22c55e;
                            padding: 0 2px;
                        }

                        /* Ajuste para modo oscuro */
                        .dark .highlight-suggest {
                            color: #fde047 !important;
                        }

                        .dark .highlight-error {
                            color: #fca5a5 !important;
                        }

                        .dark .highlight-success {
                            color: #86efac !important;
                        }



                        /* 1. Color ROJO - Error Crítico */
                        .tox.tox-tinymce-aux .tox-tbtn[aria-label*="Error"] {
                            color: #ef4444 !important;
                        }

                        .tox.tox-tinymce-aux .tox-tbtn[aria-label*="Error"] svg {
                            fill: #ef4444 !important;
                        }

                        /* 2. Color AMARILLO - Sugerencia */
                        .tox.tox-tinymce-aux .tox-tbtn[aria-label*="Sugerencia"] {
                            color: #f59e0b !important;
                        }

                        .tox.tox-tinymce-aux .tox-tbtn[aria-label*="Sugerencia"] svg {
                            fill: #f59e0b !important;
                        }

                        /* 3. Color VERDE - Correcto */
                        .tox.tox-tinymce-aux .tox-tbtn[aria-label*="Correcto"] {
                            color: #10b981 !important;
                        }

                        .tox.tox-tinymce-aux .tox-tbtn[aria-label*="Correcto"] svg {
                            fill: #10b981 !important;
                        }

                        /* 4. Color BLANCO - Quitar Marca */
                        .tox.tox-tinymce-aux .tox-tbtn[aria-label*="Quitar"] {
                            color: #ffffff !important;
                            opacity: 0.7;
                        }


                        /* El botón de nota en un color azul cian para diferenciarlo de los resaltadores */
                        .tox.tox-tinymce-aux .tox-tbtn[aria-label*="observación"] {
                            color: #06b6d4 !important;
                            /* Cyan 500 */
                        }

                        /* Efecto cuando el alumno pasa el mouse por un texto con nota */
                        .highlight-suggest[title],
                        .highlight-error[title] {
                            border-bottom-style: wavy !important;
                            /* Línea ondulada para indicar que hay texto extra */
                            cursor: help;
                        }
                    </style>
                </div>
        @endswitch
        {{-- Pie de página de la hoja para cerrar el diseño --}}
        <div class="mt-auto px-12 py-6 border-t border-gray-50 dark:border-gray-800 opacity-30">
            <p class="text-[8px] font-bold text-gray-400 uppercase tracking-[0.3em]">
                Nova Dei Software • Trujillo 2026
            </p>
        </div>
    </div>



</main>

{{-- ASIDE DERECHO --}}
<aside :class="rightSidebar ? 'translate-x-0' : 'translate-x-full'"
    class="fixed inset-y-0 right-0 w-80 bg-white dark:bg-gray-900 border-l border-gray-200 dark:border-gray-800 p-6 flex flex-col shadow-2xl z-50 transition-transform duration-300 lg:relative lg:translate-x-0">

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


    <div class="space-y-4 overflow-y-auto no-scrollbar flex-1">



        {{-- FEEDBACK SECTION --}}
        <div class="pt-6 border-t border-gray-100 dark:border-white/5 space-y-4">
            {{-- Header --}}
            <div class="flex justify-between items-end mb-3 px-2">
                <p class="text-[12px] font-bold text-gray-600 uppercase">
                    Notas
                </p>
            </div>
            <div class="space-y-3" x-data="{ notaActivaId: null }">
                @forelse($comments as $c)
                    <div {{-- Al hacer clic, enviamos el evento al editor y actualizamos el estado local --}}
                        @click="notaActivaId = {{ $c->id }}; $dispatch('navegar-a-nota', { id: {{ $c->id }} })"
                        {{-- Escuchamos si el editor activa una nota (por si se hace clic dentro del TinyMCE) --}} @nota-enfocada-desde-editor.window="notaActivaId = $event.detail.id"
                        :class="notaActivaId == {{ $c->id }} ?
                            'bg-brand-50 border-brand-500 dark:bg-brand-500/10 dark:border-brand-500 shadow-lg shadow-brand-500/20 scale-[1.02]' :
                            'bg-white dark:bg-white/5 border-gray-100 dark:border-white/5 shadow-sm'"
                        class="p-5 rounded-[1.8rem] border relative group cursor-pointer transition-all duration-300 active:scale-95">

                        {{-- Botón de eliminar --}}
                        @if ($isReadOnly)
                            <button wire:click.stop="deleteComment({{ $c->id }})"
                                class="absolute top-5 right-5 text-gray-400 hover:text-red-500 transition-colors p-1"
                                title="Eliminar nota">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        @endif

                        {{-- Texto de la nota - Cambia de color si está activa --}}
                        <p :class="notaActivaId == {{ $c->id }} ? 'text-brand-700 dark:text-brand-400' :
                            'text-gray-700 dark:text-gray-300'"
                            class="text-[13px] leading-relaxed font-medium italic pr-6 transition-colors duration-300">
                            "{{ $c->comment }}"
                        </p>

                        <div
                            class="flex justify-between items-center mt-3 pt-3 border-t border-gray-50 dark:border-white/5 leading-none">
                            <span class="text-[11px] text-gray-400 font-bold tracking-tighter">
                                {{ $c->created_at->diffForHumans() }}
                            </span>

                            @if (!$isReadOnly)
                                <span
                                    class="flex items-center gap-1 text-[10px] text-brand-500 font-black uppercase italic">
                                    <span class="w-1.5 h-1.5 bg-brand-500 rounded-full"
                                        :class="notaActivaId == {{ $c->id }} ? 'animate-ping' : 'animate-pulse'"></span>
                                    Pendiente
                                </span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div
                        class="py-10 text-center border-2 border-dashed border-gray-100 dark:border-white/5 rounded-[2rem] opacity-50">
                        <p class="text-gray-300 text-[9px] font-black uppercase tracking-widest italic">
                            Sin observaciones
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    @if (!$isReadOnly)
        <div class="mt-6 p-6 bg-brand-600 rounded-[2.5rem] text-white shadow-xl shadow-brand-500/20">
            <div class="flex justify-between items-end mb-3">
                <div>
                    <p
                        class="text-[9px] font-black text-brand-200 uppercase tracking-widest mb-1 italic leading-none">
                        Uso de IA</p>
                    <p class="text-2xl font-black tracking-tighter leading-none leading-none">
                        {{ number_format($project->ai_words_used) }}</p>
                </div>
                <div class="text-right">
                    <p class="text-[8px] font-bold text-brand-200 uppercase tracking-widest leading-none">Límite
                    </p>
                    <p class="text-xs font-black leading-none">
                        {{ number_format($project->ai_word_limit / 1000, 1) }}k</p>
                </div>
            </div>
            @php $percentage = $project->ai_word_limit > 0 ? ($project->ai_words_used / $project->ai_word_limit) * 100 : 0; @endphp
            <div
                class="w-full bg-brand-800 h-1.5 rounded-full overflow-hidden border border-brand-500/30 leading-none">
                <div class="bg-white h-full transition-all duration-700 ease-out shadow-[0_0_8px_rgba(255,255,255,0.5)]"
                    style="width: {{ min($percentage, 100) }}%"></div>
            </div>
        </div>
    @endif


</aside>


{{-- MODAL DE NOTAS (Vinculado a showNotePortal) --}}
<x-ui.modal x-data="{ open: false }" x-show="showNotePortal || open" {{-- Escucha el evento que mandas desde el PHP para cerrar todo --}}
    @open-note-modal.window="open = true; showNotePortal = true"
    @close-note-modal.window="open = false; showNotePortal = false; loading = false" class="max-w-[650px]">

    <div
        class="no-scrollbar relative w-full overflow-y-auto rounded-[2.5rem] bg-white p-8 dark:bg-gray-900 lg:p-12 min-h-[450px]">
        <x-common.loader x-show="loading" text="Sincronizando..." />

        {{-- Cambiamos a showNotePortal para que sea consistente --}}
        <div x-show="!loading" x-cloak>
            <div class="mb-10 text-center md:text-left">
                <h4 class="text-3xl font-black text-gray-900 dark:text-white">
                    Agregar Nota
                </h4>
                <p class="text-sm text-gray-500 mt-2">Escribe la observación técnica para el alumno.</p>
            </div>

            <div class="space-y-6">
                <x-form.input.textarea wire:model="newComment" label="Nota:" placeholder="Escribe aquí..." />
            </div>

            <div class="flex items-center justify-end gap-4 pt-10 mt-6">
                <button type="button" @click="open = false"
                    class="text-sm font-bold text-gray-400">Cancelar</button>

                <x-common.button-submit wire:click="addComment" target="addComment" variant="brand"
                    class="rounded-2xl px-10 py-4 shadow-xl">
                    Registrar Nota
                </x-common.button-submit>
            </div>


        </div>
    </div>
</x-ui.modal>




</div>
