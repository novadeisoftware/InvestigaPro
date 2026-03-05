<?php

namespace App\Livewire\Payment;

use Livewire\Component;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;

class IzipayCheckout extends Component
{
    public $payment;
    public $formToken;

    public function mount(Payment $payment)
    {
        $this->payment = $payment;
        $this->generateFormToken();
    }

    public function generateFormToken()
    {
        // Izipay pide el monto en centavos (S/ 100.00 -> 10000)
        $amountInCents = (int)($this->payment->amount * 100);

        // Llamada al API de Izipay (Micuentaweb)
        $response = Http::withBasicAuth(
            config('services.izipay.client_id'), 
            config('services.izipay.client_secret')
        )->post(config('services.izipay.url') . '/Charge/CreatePayment', [
            'amount'   => $amountInCents,
            'currency' => 'PEN',
            'orderId'  => $this->payment->niubiz_order_id,
            'customer' => [
                'email' => auth()->user()->email,
            ],
        ]);

        if ($response->successful()) {
            $this->formToken = $response->json()['answer']['formToken'];
        } else {
            session()->flash('error', 'No se pudo conectar con la pasarela de pagos.');
        }
    }

    public function render()
    {
        return view('livewire.payment.izipay-checkout')
            ->layout('layouts.app'); // Para que use tu diseño base
    }
}