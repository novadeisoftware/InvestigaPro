<div>
    <x-ui.modal x-data="{ open: false }" @open-modal.window="if($event.detail == 'modal-steps-list') open = true"
        @close-modal.window="open = false" class="max-w-[550px]">

        {{-- Cerrar al hacer clic fuera --}}
        <div @click.outside="open = false"
            class="no-scrollbar relative w-full max-h-[85vh] overflow-y-auto rounded-[2rem] bg-white p-6 dark:bg-gray-900 lg:p-8">

            <x-common.loader x-show="loading" text="Sincronizando..." />

            @if ($classroom)
                <div x-cloak>
                    {{-- HEADER --}}
                    <div
                        class="mb-6 flex justify-between items-center border-b border-gray-100 dark:border-gray-800 pb-4">
                        <div>
                            <h4 class="text-xl font-black text-gray-900 dark:text-white leading-none">Estrategia de
                                Avance
                            </h4>
                            <p class="text-[11px] text-gray-500 mt-1 uppercase tracking-wider">{{ $classroom->name }}
                            </p>
                        </div>
                        <button @click="open = false" class="text-gray-400 hover:text-brand-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round" />
                            </svg>
                        </button>
                    </div>

                    {{-- CONTROL GLOBAL --}}
                    <div
                        class="mb-4 p-4 bg-brand-50/50 dark:bg-brand-900/10 rounded-2xl border border-brand-100 dark:border-brand-900/20">
                        <div class="flex gap-2">
                            <select wire:model.live="availability_mode"
                                class="flex-1 px-3 py-2 text-xs rounded-xl border border-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:text-white outline-none">
                                <option value="open">Todo Abierto</option>
                                <option value="locked">Manual (On/Off)</option>
                                <option value="scheduled">Por fechas</option>
                            </select>



                            {{-- Solo mostramos el botón si la intención es Abrir Todo --}}

                            <x-common.button-submit wire:click="applyStrategy" target="applyStrategy" variant="brand"
                                class="rounded-2xl px-10 py-4 shadow-xl shadow-brand-500/30">
                                Aplicar
                            </x-common.button-submit>



                        </div>

                        {{-- LEYENDA DINÁMICA RECUPERADA --}}
                        <div
                            class="mt-3 px-2 py-2 bg-white/50 dark:bg-gray-900/50 rounded-xl border border-dashed border-brand-200 dark:border-brand-800">
                            <p class="text-[13px] text-gray-600 dark:text-gray-400 leading-tight">
                                @if ($availability_mode === 'open')
                                    <strong class="text-brand-300">🚀 Libertad:</strong> Todo desbloqueado desde el
                                    inicio.
                                @elseif($availability_mode === 'locked')
                                    <strong class="text-brand-300">🎯 Manual:</strong> Tú activas cada paso (On/Off) en
                                    la lista.
                                @elseif($availability_mode === 'scheduled')
                                    <strong class="text-brand-300">📅 Agenda:</strong> Los pasos se abren solos en la
                                    fecha puesta.
                                @endif
                            </p>
                        </div>
                    </div>

                    {{-- LISTA DE PASOS CON CLICK-TO-EDIT --}}
                    <div class="space-y-2">
                        @foreach ($steps as $step)
                            <div wire:key="step-{{ $step->id }}"
                                class="bg-gray-50 dark:bg-gray-800 rounded-xl p-3 flex items-center justify-between border border-gray-100 dark:border-gray-700">
                                <div class="flex items-center gap-3 flex-1" x-data="{ isEditing: false }">
                                    <div
                                        class="w-7 h-7 rounded-lg bg-white dark:bg-gray-900 flex items-center justify-center text-gray-900 dark:text-white font-black text-xs shadow-sm">
                                        {{ $step->order }}
                                    </div>

                                    <div class="flex-1">
                                        <template x-if="!isEditing">
                                            <span @click="isEditing = true"
                                                class="font-bold text-xs text-gray-700 dark:text-gray-200 cursor-pointer hover:text-brand-600 border-b border-transparent hover:border-brand-600/30 transition-all">
                                                {{ $step->custom_name ?? $step->step_key }}
                                            </span>
                                        </template>
                                        <template x-if="isEditing">
                                            <input type="text"
                                                class="w-full text-xs font-bold bg-white  border-none rounded-lg p-1 focus:ring-1 focus:ring-brand-500"
                                                value="{{ $step->custom_name ?? $step->step_key }}"
                                                @keydown.enter="isEditing = false; $wire.updateStepName({{ $step->id }}, $event.target.value)"
                                                @blur="isEditing = false; $wire.updateStepName({{ $step->id }}, $event.target.value)"
                                                @keydown.escape="isEditing = false" x-init="$el.focus()">
                                        </template>
                                    </div>
                                </div>

                                {{-- Dentro del foreach de los pasos --}}
                                <div class="flex items-center gap-2">
                                    @if ($availability_mode === 'scheduled')
                                        {{-- Enlazamos directamente al ID del paso en el array --}}
                                        <input type="datetime-local" wire:model="stepDates.{{ $step->id }}"
                                            class="text-[9px] px-2 py-1 rounded-lg border border-gray-200 dark:bg-gray-900 dark:text-white w-32 outline-none focus:border-brand-500">
                                    @elseif($availability_mode === 'locked')
                                        {{-- Tu botón On/Off actual --}}
                                        <button wire:click="toggleStepStatus({{ $step->id }})"
                                            class="px-3 py-1.5 rounded-lg text-[8px] font-black uppercase tracking-widest transition-all 
                {{ $step->availability_mode == 'open' ? 'bg-green-500 text-white' : 'bg-white dark:bg-gray-900 text-gray-400' }}">
                                            {{ $step->availability_mode == 'open' ? 'On' : 'Off' }}
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </x-ui.modal>
</div>
