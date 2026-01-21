<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\Contracts\DeletesUsers;

class DeleteUser implements DeletesUsers
{
    public function delete(User $user): void
    {
        // 1. Ejecuta el Soft Delete
        $user->delete();

        // 2. IMPORTANTE para que te saque de la web:
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
    }
}