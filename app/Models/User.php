<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'name',
        'email',
        'password',
        'role',
        'foto',
        'remember_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->attributes['nombre'] ?? null,
            set: fn (?string $value) => ['nombre' => $value],
        );
    }

    public function partidasCreadas()
    {
        return $this->hasMany(Partida::class, 'creador_id');
    }

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'usuario_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'user_id');
    }

    public function chatMessages()
    {
        return $this->hasMany(ChatMessage::class, 'usuario_id');
    }

    protected function avatarUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->foto
                ? asset('frontend/img/perfiles/' . $this->foto)
                : asset('frontend/img/perfil.png'),
        );
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
