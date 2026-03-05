<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Jetstream\HasProfilePhoto;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasProfilePhoto,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_superadmin',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
        ];
    }

   /** Aulas que este usuario dirige como ASESOR */
    public function managedClassrooms()
    {
        return $this->hasMany(Classroom::class, 'advisor_id');
    }

    /** Aulas en las que el usuario está inscrito como ALUMNO */
    public function classrooms()
    {
        return $this->belongsToMany(Classroom::class, 'classroom_user')
                    ->using(ClassroomUser::class) // <--- INDISPENSABLE para usar tu modelo Pivot
                    ->withPivot('status', 'joined_at') // <--- joined_at estaba en tu pivot
                    ->withTimestamps();
    }

    /** Proyectos (Tesis) del usuario */
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    /* |--------------------------------------------------------------------------
       | Relaciones de Suscripción y Pagos
       |-------------------------------------------------------------------------- */

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function payments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /* |--------------------------------------------------------------------------
       | Helpers de Lógica de Negocio (MSHO Logic)
       |-------------------------------------------------------------------------- */

    public function activeSubscription()
    {
        return $this->subscriptions()
            ->where('status', 'active')
            ->where(function ($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->first();
    }

    public function hasActiveSubscription(): bool
    {
        return !is_null($this->activeSubscription());
    }
}
