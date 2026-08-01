<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Maestro;
use App\Models\Alumno;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;
    
    protected $table = 'users';

    protected $fillable = [
        'name',
        'apellido',
        'email',
        'password',
        'role',
        'foto',
    ];

    public function maestro(): HasOne
    {
        // Un usuario tiene un registro de maestro
        return $this->hasOne(Maestro::class);
    }

    public function alumno(): HasOne
    {
        return $this->hasOne(Alumno::class);
    }

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
        ];
    }

    public function getRoleLangAttribute() {
        $rolesLang = [
            'alumno' => __('messages.dashboard_role_student'),
            'maestro' => __('messages.dashboard_role_teacher'),
            'admin' => __('messages.dashboard_role_admin'),
        ];
        return $rolesLang[$this->role] ?? $this->role;
    }
}
