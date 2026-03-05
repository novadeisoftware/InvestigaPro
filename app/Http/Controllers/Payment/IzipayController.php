<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class IzipayController extends Controller
{
    public function checkout(Payment $payment)
    {
        // 1. Configurar datos para Izipay (Monto en centavos: S/ 100 -> 10000)
        $amount = (int) ($payment->amount * 100);

        // 2. Llamada al API de Izipay para obtener el formToken
        // Nota: Necesitarás tus credenciales de Izipay en el .env
        $response = Http::withBasicAuth(config('services.izipay.client_id'), config('services.izipay.client_secret'))
            ->post(config('services.izipay.url') . '/Charge/CreatePayment', [
                'amount'   => $amount,
                'currency' => 'PEN',
                'orderId'  => $payment->niubiz_order_id, // Reutilizamos el campo de ID de orden
                'customer' => [
                    'email' => auth()->user()->email
                ]
            ]);

        $formToken = $response->json()['answer']['formToken'] ?? null;

        return view('payments.izipay-checkout', compact('payment', 'formToken'));
    }
}