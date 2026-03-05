<div class="p-8 max-w-7xl mx-auto animate-fade-in" x-data="{ tab: @entangle('activeTab'), showOffers: @entangle('showOffers') }">

    {{-- SECCIÓN: MI ESTADO ACTUAL (Solo se ve si tiene suscripción y no estamos viendo ofertas) --}}
    {{-- SECCIÓN: MI ESTADO ACTUAL --}}
    @if ($subscription && !$showOffers)
        <div class="mb-16 space-y-8 animate-fade-in">

            {{-- Header de Usuario --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h2
                        class="text-3xl font-black text-gray-900 dark:text-white uppercase italic tracking-tighter leading-none">
                        Planes</h2>
                    <p class="text-gray-400 text-[9px] font-black uppercase mt-2 italic tracking-[0.2em]">Bienvenido de
                        vuelta, {{ auth()->user()->name }}</p>
                </div>
                <button @click="showOffers = true"
                    class="group px-6 py-3 bg-white dark:bg-gray-800 border-2 border-gray-900 dark:border-brand-400 text-gray-900 dark:text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-gray-900 hover:text-white dark:hover:bg-brand-400 transition-all shadow-lg">
                    🚀 Mejorar mi Plan Actual
                </button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- Card 1: Status Global --}}
                <div
                    class="bg-white dark:bg-gray-800 rounded-[3rem] p-8 border border-gray-100 dark:border-gray-700 shadow-xl relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-brand-400/5 rounded-full blur-2xl"></div>

                    <div class="flex justify-between items-start mb-6">
                        <div class="p-3 bg-brand-400/10 rounded-2xl text-brand-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <span
                            class="px-3 py-1 bg-green-500/10 text-green-500 rounded-lg text-[8px] font-black uppercase italic tracking-widest border border-green-500/20">Protegido</span>
                    </div>

                    <h4 class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1 italic">Membresía
                        Activa</h4>
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white uppercase italic leading-none mb-6">
                        {{ str_replace('_', ' ', $subscription->plan_key) }}
                    </h3>

                    <div class="space-y-3 pt-6 border-t border-gray-50 dark:border-gray-700">
                        <div class="flex justify-between text-[10px] font-bold">
                            <span class="text-gray-400 uppercase tracking-tighter">Miembro desde:</span>
                            <span
                                class="text-gray-900 dark:text-gray-200 uppercase">{{ $subscription->starts_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between text-[10px] font-bold">
                            <span class="text-gray-400 uppercase tracking-tighter">Estado de Pago:</span>
                            <span class="text-brand-400 uppercase italic">Verificado</span>
                        </div>
                    </div>
                </div>

                {{-- Card 2: Barra de Energía (IA) --}}
                @php
                    $project = auth()->user()->projects()->first();
                    $limit = $project->ai_word_limit ?? 1;
                    $used = $project->ai_words_used ?? 0;
                    $percent = ($used / $limit) * 100;
                @endphp
                <div
                    class="lg:col-span-2 bg-gray-900 rounded-[3rem] p-8 shadow-2xl border-2 border-brand-400/20 flex flex-col justify-between">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-8">
                        <div>
                            <h4 class="text-[9px] font-black text-brand-400 uppercase tracking-[0.3em] mb-2 italic">
                                Capacidad de Redacción IA</h4>
                            <div class="flex items-baseline gap-2">
                                <span
                                    class="text-5xl font-black text-white italic tracking-tighter">{{ number_format($used) }}</span>
                                <span class="text-gray-500 font-black text-xs uppercase italic tracking-widest">/
                                    {{ number_format($limit) }} Palabras</span>
                            </div>
                        </div>
                        <button
                            class="w-full md:w-auto px-8 py-4 bg-brand-400 text-white rounded-[1.5rem] font-black text-[10px] uppercase tracking-[0.2em] shadow-lg shadow-brand-400/20 hover:scale-105 transition-all">
                            ⚡ Recargar Palabras
                        </button>
                    </div>

                    <div>
                        <div
                            class="w-full h-4 bg-gray-800 rounded-full overflow-hidden p-1 shadow-inner border border-white/5">
                            <div class="h-full bg-gradient-to-r from-brand-500 to-brand-300 rounded-full shadow-[0_0_20px_rgba(74,222,128,0.4)] transition-all duration-1000 ease-out"
                                style="width: {{ $percent }}%"></div>
                        </div>
                        <div class="flex justify-between mt-4 text-[9px] font-black uppercase tracking-[0.2em] italic">
                            <span class="text-brand-400">{{ round($percent) }}% Consumido</span>
                            <span class="text-gray-500">{{ number_format($limit - $used) }} Palabras de reserva</span>
                        </div>
                    </div>
                </div>

                {{-- Card 3: Historial de Pagos (Tabla) --}}
                <div
                    class="lg:col-span-3 bg-white dark:bg-gray-800 rounded-[3rem] p-8 border border-gray-100 dark:border-gray-700 shadow-xl overflow-hidden">
                    <h4
                        class="text-[10px] font-black text-gray-900 dark:text-white uppercase tracking-[0.3em] mb-8 italic">
                        Historial de Transacciones</h4>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr
                                    class="text-[9px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-50 dark:border-gray-700">
                                    <th class="pb-4">Fecha</th>
                                    <th class="pb-4">Descripción</th>
                                    <th class="pb-4">Monto</th>
                                    <th class="pb-4 text-right">Comprobante</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                                @forelse($user->payments()->latest()->take(3)->get() as $payment)
                                    <tr class="group">
                                        <td
                                            class="py-4 text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-tighter italic">
                                            {{ $payment->created_at->format('d/m/Y') }}
                                        </td>
                                        <td
                                            class="py-4 text-xs font-black text-gray-900 dark:text-white uppercase italic">
                                            {{ str_replace('_', ' ', $payment->payment_type) }}
                                        </td>
                                        <td class="py-4 text-xs font-black text-brand-600 italic">
                                            S/ {{ number_format($payment->amount, 2) }}
                                        </td>
                                        <td class="py-4 text-right">
                                            <button
                                                class="text-[9px] font-black uppercase text-gray-400 group-hover:text-brand-400 transition-colors underline tracking-widest">
                                                Recibo Niubiz #{{ substr($payment->niubiz_transaction_id, -6) }}
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4"
                                            class="py-8 text-center text-xs font-bold text-gray-400 uppercase italic">No
                                            hay transacciones registradas aún.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- SECCIÓN: PLANES Y OFERTAS (Se ve si NO hay suscripción o si se hizo clic en "Ver otros planes") --}}
    <div x-show="showOffers || !{{ $subscription ? 'true' : 'false' }}" x-transition>

        @if ($subscription)
            <div class="flex justify-center mb-8">
                <button @click="showOffers = false"
                    class="text-[10px] font-black uppercase text-gray-400 hover:text-brand-400 transition-colors tracking-widest italic underline">
                    ← Volver a mi estado actual
                </button>
            </div>
        @endif

        {{-- Header Estratégico --}}
        <div class="text-center mb-16">
            <h2
                class="text-5xl font-black text-gray-900 dark:text-white uppercase italic tracking-tighter mb-4 leading-none">
                Impulsa tu <span class="text-brand-400">Investigación</span>
            </h2>
            <p class="text-gray-500 dark:text-gray-400 font-medium italic text-lg max-w-2xl mx-auto leading-relaxed">
                Obtén las herramientas necesarias para dominar tu tesis con inteligencia artificial y formatos
                oficiales.
            </p>
        </div>

        {{-- Switcher de Perfil Estilizado --}}
        <div class="flex justify-center mb-14">
            <div class="inline-flex p-1.5 bg-gray-100 dark:bg-gray-800 rounded-[2rem] shadow-inner relative">
                <button @click="tab = 'student'"
                    class="px-10 py-4 rounded-[1.5rem] text-[10px] font-black uppercase transition-all duration-300 z-10"
                    :class="tab === 'student' ? 'bg-white dark:bg-gray-700 shadow-xl text-brand-400 scale-105' :
                        'text-gray-400 hover:text-gray-600'">
                    👨‍🎓 Para Estudiantes
                </button>
                <button @click="tab = 'advisor'"
                    class="px-10 py-4 rounded-[1.5rem] text-[10px] font-black uppercase transition-all duration-300 z-10"
                    :class="tab === 'advisor' ? 'bg-white dark:bg-gray-700 shadow-xl text-brand-400 scale-105' :
                        'text-gray-400 hover:text-gray-600'">
                    🏛️ Para Asesores
                </button>
            </div>
        </div>

        {{-- CONTENIDO PARA ESTUDIANTES --}}
        <div x-show="tab === 'student'" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
                <div class="md:col-start-2 relative group">
                    <div
                        class="absolute -inset-1 bg-gradient-to-r from-brand-400 to-brand-400 rounded-[3.5rem] blur opacity-25 group-hover:opacity-50 transition duration-1000">
                    </div>
                    <div
                        class="relative bg-white dark:bg-gray-900 rounded-[3.5rem] p-10 border-2 border-brand-400/20 shadow-2xl overflow-hidden flex flex-col">
                        <div
                            class="absolute top-0 right-0 bg-brand-400 text-white text-[9px] font-black px-8 py-2 uppercase rotate-45 translate-x-6 translate-y-3 shadow-lg">
                            Popular</div>
                        <h4 class="text-xs font-black text-brand-400 uppercase tracking-[0.3em] mb-4 italic">Tesis
                            Exitosa</h4>
                        <div class="flex items-baseline gap-2 mb-8">
                            <span class="text-7xl font-black text-gray-900 dark:text-white tracking-tighter italic">S/
                                249</span>
                            <div class="flex flex-col text-gray-400 font-bold uppercase text-[10px] leading-none">
                                <span>Pago</span>
                                <span>Único</span>
                            </div>
                        </div>
                        <p
                            class="text-[11px] text-gray-500 font-medium mb-8 border-b border-gray-50 dark:border-gray-800 pb-6 leading-relaxed italic">
                            Todo lo que un estudiante de la <b>UNT, UCV o UPAO</b> necesita para finalizar su informe
                            final de investigación.
                        </p>
                        <ul class="space-y-5 mb-12">
                            @foreach ([['Editor IA sin distracciones', 'text-green-500'], ['200,000 Palabras incluidas', 'text-brand-400'], ['Citas APA Automáticas', 'text-blue-500'], ['Exportación Word / PDF', 'text-gray-400'], ['Soporte prioritario', 'text-gray-400']] as $f)
                                <li class="flex items-center gap-4 text-xs font-bold text-gray-700 dark:text-gray-300">
                                    <div
                                        class="w-5 h-5 bg-gray-50 dark:bg-gray-800 rounded-lg flex items-center justify-center shadow-sm">
                                        <svg class="w-3.5 h-3.5 {{ $f[1] }}" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path d="M5 13l4 4L19 7" stroke-width="4" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                    {{ $f[0] }}
                                </li>
                            @endforeach
                        </ul>
                        <button wire:click="selectPlan('student_thesis')"
                            class="w-full py-6 bg-gray-900 dark:bg-brand-400 text-white rounded-[2rem] font-black uppercase tracking-[0.25em] text-[11px] hover:scale-[1.03] active:scale-95 transition-all shadow-2xl shadow-brand-400/40">
                            Adquirir Acceso Ahora →
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- CONTENIDO PARA ASESORES --}}
        <div x-show="tab === 'advisor'" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 max-w-5xl mx-auto">
                {{-- Plan Mentor Pro --}}
                <div
                    class="bg-white dark:bg-gray-800 rounded-[3rem] p-10 border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-2xl transition-all flex flex-col justify-between group">
                    <div>
                        <h4 class="text-[10px] font-black text-brand-400 uppercase tracking-[0.3em] mb-4 italic">Mentor
                            Académico</h4>
                        <div class="flex items-baseline gap-1 mb-6">
                            <span class="text-5xl font-black text-gray-900 dark:text-white tracking-tighter italic">S/
                                69</span>
                            <span class="text-gray-400 font-bold uppercase text-[10px]">/ Mes</span>
                        </div>
                        <p class="text-[11px] text-gray-500 mb-8 leading-relaxed italic">La herramienta perfecta para
                            asesores independientes que gestionan grupos de tesis.</p>
                        <ul class="space-y-4 mb-10">
                            @foreach ([['Hasta 20 Alumnos matriculados', 'text-blue-500'], ['Aulas Virtuales ilimitadas', 'text-blue-500'], ['Dashboard de seguimiento real-time', 'text-blue-500'], ['Feedback directo en el editor', 'text-gray-400'], ['Configuración de estrategia propia', 'text-gray-400']] as $item)
                                <li class="flex items-center gap-3 text-xs font-bold text-gray-600 dark:text-gray-300">
                                    <div class="w-1.5 h-1.5 bg-brand-400 rounded-full"></div>
                                    {{ $item[0] }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <button wire:click="selectPlan('advisor_pro')"
                        class="w-full py-4 border-2 border-gray-900 dark:border-gray-600 text-gray-900 dark:text-white rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-gray-900 hover:text-white transition-all shadow-md">
                        Elegir Mentor Pro
                    </button>
                </div>

                {{-- Plan Elite Académico --}}
                <div
                    class="bg-gray-900 rounded-[3.5rem] p-10 border-2 border-brand-400 shadow-2xl relative overflow-hidden flex flex-col justify-between group">
                    <div class="absolute top-0 right-0 -mr-16 -mt-16 w-40 h-40 bg-brand-400/10 rounded-full blur-3xl">
                    </div>
                    <div>
                        <div class="flex justify-between items-start mb-4">
                            <h4 class="text-[10px] font-black text-brand-400 uppercase tracking-[0.3em] italic">
                                Institucional Elite</h4>
                            <span
                                class="bg-brand-400 text-white text-[8px] font-black px-4 py-1.5 rounded-full uppercase italic">Máximo
                                Poder</span>
                        </div>
                        <div class="flex items-baseline gap-1 mb-6">
                            <span class="text-5xl font-black text-white tracking-tighter italic">S/ 149</span>
                            <span class="text-gray-500 font-bold uppercase text-[10px]">/ Mes</span>
                        </div>
                        <p class="text-[11px] text-gray-400 mb-8 leading-relaxed italic">Control total sobre múltiples
                            promociones y auditoría de redacción avanzada.</p>
                        <ul class="space-y-4 mb-10 text-gray-300">
                            @foreach ([['Hasta 60 Alumnos matriculados', 'text-brand-400'], ['Reportes de originalidad e IA', 'text-brand-400'], ['Exportación masiva de avances', 'text-brand-400'], ['Rúbricas personalizadas por aula', 'text-brand-400'], ['Soporte 24/7 Personalizado', 'text-brand-400']] as $item)
                                <li class="flex items-center gap-3 text-xs font-bold">
                                    <svg class="w-4 h-4 {{ $item[1] }}" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    {{ $item[0] }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <button wire:click="selectPlan('advisor_elite')"
                        class="w-full py-5 bg-brand-400 text-white rounded-2xl font-black uppercase tracking-[0.2em] text-[10px] shadow-lg shadow-brand-400/40 hover:bg-brand-700 transition-all">
                        Activar Plan Elite
                    </button>
                </div>
            </div>
        </div>

        {{-- Footer Confianza --}}
        <div
            class="mt-20 flex flex-wrap justify-center gap-12 opacity-30 grayscale hover:grayscale-0 transition-all duration-1000">
            <div class="flex items-center gap-2">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5e/Visa_Inc._logo.svg/2560px-Visa_Inc._logo.svg.png"
                    class="h-3" alt="Visa">
                <span class="text-[9px] font-black uppercase text-gray-400 tracking-widest italic">Pagos vía
                    Niubiz</span>
            </div>
            <span class="text-[9px] font-black uppercase text-gray-400 tracking-widest italic">🛡️ Cifrado de Datos
                AES-256</span>
            <span class="text-[9px] font-black uppercase text-gray-400 tracking-widest italic">✨ Certificado Normas APA
                7</span>
        </div>

        {{-- Botón de "Tengo un código" --}}
        <div class="text-center mt-12">
            <button
                class="text-[10px] font-black uppercase text-gray-400 hover:text-brand-400 transition-colors tracking-[0.3em]">
                ¿Tienes un código de promoción? Haz clic aquí
            </button>
        </div>
    </div>
</div>
