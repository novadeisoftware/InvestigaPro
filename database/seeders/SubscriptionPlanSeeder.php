<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subscription;
use App\Models\User;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        // Buscamos tu usuario (ID 2 según tu error de SQL anterior)
        $user = User::find(2) ?? User::first();

        if ($user) {
            // Limpiamos suscripciones anteriores para evitar duplicados en pruebas
            Subscription::where('user_id', $user->id)->delete();

            // Ejemplo 1: Suscripción de Alumno (Pago Único)
            Subscription::create([
                'user_id' => $user->id,
                'plan_key' => 'student_thesis',
                'status' => 'active',
                'starts_at' => now(),
                'expires_at' => null, // De por vida
            ]);

            // Ejemplo 2: Suscripción de Asesor Pro (Mensual)
            Subscription::create([
                'user_id' => $user->id,
                'plan_key' => 'advisor_pro',
                'status' => 'active',
                'starts_at' => now(),
                'expires_at' => now()->addMonth(), // Vence en 30 días
            ]);
        }
    }
}