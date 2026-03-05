<div class="py-12 min-h-screen flex flex-col items-center justify-center bg-gray-50 dark:bg-gray-900">
    <div class="bg-white dark:bg-gray-800 p-10 rounded-[3.5rem] shadow-2xl w-full max-w-md text-center border-2 border-brand-400/10">
        
        <div class="mb-8">
            <h2 class="text-3xl font-black uppercase italic italic text-gray-900 dark:text-white leading-none">
                Finalizar <span class="text-brand-400">Pago</span>
            </h2>
            <div class="mt-4 p-4 bg-brand-400/5 rounded-2xl inline-block">
                <p class="text-gray-500 dark:text-gray-400 font-bold uppercase text-[10px] tracking-widest">Monto a depositar</p>
                <span class="text-4xl font-black text-brand-400 italic">S/ {{ number_format($payment->amount, 2) }}</span>
            </div>
        </div>

        @if($formToken)
            {{-- Importante: wire:ignore evita que Livewire rompa el script de Izipay --}}
            <div id="izipay-container" wire:ignore class="flex justify-center">
                <div class="kr-embedded" kr-form-token="{{ $formToken }}">
                    </div>
            </div>

            <script 
                src="https://static.micuentaweb.pe/static/js/krypton-client/V4.0/stable/kr-payment-form.min.js"
                kr-public-key="{{ config('services.izipay.public_key') }}"
                kr-post-url-success="{{ route('payment.izipay.result') }}">
            </script>
            
            <link rel="stylesheet" href="https://static.micuentaweb.pe/static/js/krypton-client/V4.0/ext/classic-reset.css">
            <script src="https://static.micuentaweb.pe/static/js/krypton-client/V4.0/ext/classic.js"></script>
        @else
            <div class="py-10">
                <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-brand-400 mx-auto"></div>
                <p class="text-xs font-black uppercase text-gray-400 mt-4 tracking-widest">Generando sesión segura...</p>
            </div>
        @endif

        <p class="mt-10 text-[9px] font-black uppercase text-gray-400 tracking-widest italic">
            🛡️ Transacción protegida por Izipay Perú
        </p>
    </div>

    <button onclick="window.history.back()" class="mt-8 text-[10px] font-black uppercase text-gray-500 hover:text-brand-400 transition-colors tracking-widest">
        ← Volver y cambiar plan
    </button>
</div>