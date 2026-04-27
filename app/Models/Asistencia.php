<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    use HasFactory;

    protected $table = 'asistencias';

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'recordatorio_partida_enviado_at' => 'datetime',
    ];

    protected $fillable = [
        'usuario_id',
        'partida_id',
        'estado',
        'recordatorio_partida_enviado_at',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function partida()
    {
        return $this->belongsTo(Partida::class, 'partida_id');
    }
}
