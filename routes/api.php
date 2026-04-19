<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\PartidaController;
use App\Http\Controllers\AsistenciaController;

Route::apiResource('usuarios', UsuarioController::class);
Route::apiResource('partidas', PartidaController::class)->only(['index', 'show']);
Route::apiResource('asistencia', AsistenciaController::class)->only(['index', 'store', 'destroy']);
