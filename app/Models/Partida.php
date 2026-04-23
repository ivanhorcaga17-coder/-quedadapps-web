<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

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

    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => str_starts_with($this->imagen ?? '', 'images/')
                ? asset($this->imagen)
                : asset('storage/' . $this->imagen),
        );
    }

    protected function startsSoon(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->fecha instanceof CarbonInterface
                ? $this->fecha->isFuture() && now()->diffInMinutes($this->fecha) <= 120
                : false,
        );
    }

    protected function startsInText(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->fecha instanceof CarbonInterface
                ? $this->fecha->diffForHumans(now(), [
                    'parts' => 2,
                    'short' => true,
                    'syntax' => CarbonInterface::DIFF_RELATIVE_TO_NOW,
                ])
                : null,
        );
    }
}
