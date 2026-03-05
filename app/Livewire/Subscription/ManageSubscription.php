<?php

namespace App\Livewire\Subscription;

use Livewire\Component;
use App\Models\Subscription; // Importante
use App\Models\Payment;      // Importante
use Illuminate\Support\Str;  // ESTA ES LA QUE TE FALTA
use Illuminate\Support\Facades\Auth;

class ManageSubscription extends Component
{
    public $activeTab = 'student';
    public $showOffers = false; // Controla si mostramos los planes para hacer upgrade

    public function render()
    {
        $user = Auth::user();
        $subscription = $user->activeSubscription(); // El método que creamos en el modelo User

        // Si no tiene suscripción, forzamos ver ofertas
        if (!$subscription) {
            $this->showOffers = true;
        }

        return view('livewire.subscription.manage-subscription', [
            'subscription' => $subscription,
            'user' => $user
        ]);
    }

    public function toggleOffers()
    {
        $this->showOffers = !$this->showOffers;
    }

    public function selectPlan($planKey)
    {
        // 1. Definimos el monto según tu diseño (S/ 249 para estudiante)
        $amount = ($planKey === 'student_thesis') ? 249.00 : 69.00;
    
        // 2. Registramos el intento de pago
        $payment = Auth::user()->payments()->create([
            'niubiz_order_id' => 'ORD-' . strtoupper(Str::random(10)),
            'plan_key' => $planKey,
            'amount' => $amount,
            'status' => 'pending',
        ]);
    
        // 3. Redirigimos al Checkout de Niubiz
        return redirect()->route('payment.izipay.checkout', ['payment' => $payment->id]);
    }
}