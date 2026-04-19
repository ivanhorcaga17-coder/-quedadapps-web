<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partida extends Model
{
    use HasFactory;

    protected $casts = [
        'fecha' => 'datetime',
    ];

    protected $fillable = [
        'titulo',
        'deporte',
        'fecha',
        'lugar',
        'max_jugadores',
        'imagen',
        'creador_id',
    ];

    public function creador()
    {
        return $this->belongsTo(User::class, 'creador_id');
    }

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'partida_id');
    }

    public function chatMessages()
    {
        return $this->hasMany(ChatMessage::class, 'partida_id')->latest();
    }
}
