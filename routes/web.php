<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\DescargarController;
use App\Http\Controllers\PartidaController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\ChatMessageController;
use App\Http\Controllers\Admin\AdminController;

/* PÁGINAS PRINCIPALES */
Route::get('/', fn() => view('frontend.index'))->name('index');
Route::get('/buscar-partidas', [FrontendController::class, 'buscar'])->name('buscar');
Route::get('/calendario', [FrontendController::class, 'calendario'])->name('calendario');
Route::get('/acerca-de', [FrontendController::class, 'acerca'])->name('acerca');
Route::get('/asistencia', [FrontendController::class, 'asistencia'])->name('asistencia');

/* CREAR PARTIDAS */
Route::middleware('auth')->group(function () {
    Route::get('/crear-partidas', [PartidaController::class, 'create'])->name('partidas.create');
    Route::post('/crear-partidas', [PartidaController::class, 'store'])->name('partidas.store');
    Route::get('/partidas/confirmar', [PartidaController::class, 'preview'])->name('partidas.preview');
    Route::post('/partidas/confirmar', [PartidaController::class, 'finalize'])->name('partidas.finalize');
});

/* CONFIRMAR PARTIDA */
Route::get('/partida/{partida}', [PartidaController::class, 'showPage'])->name('partidas.showPage');
Route::get('/partida/{partida}/confirmar', [PartidaController::class, 'confirmar'])->name('partidas.confirmar');

/* AUTH */
Route::middleware('guest')->group(function () {
    Route::get('/login', fn() => view('frontend.login'))->name('login');
    Route::get('/register', fn() => view('frontend.registro'))->name('registro');
    Route::post('/registro', [AuthController::class, 'register'])->name('registro.post');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

/* PERFIL */
Route::middleware('auth')->group(function () {
    Route::get('/perfil', [PerfilController::class, 'index'])->name('perfil');
    Route::post('/perfil/actualizar', [PerfilController::class, 'update'])->name('perfil.update');
    Route::post('/perfil/eliminar-foto', [PerfilController::class, 'deletePhoto'])->name('perfil.deletePhoto');
    Route::post('/asistencia/{partida}', [AsistenciaController::class, 'join'])->name('asistencia.join');
    Route::delete('/asistencia/{partida}', [AsistenciaController::class, 'leave'])->name('asistencia.leave');
    Route::post('/partida/{partida}/chat', [ChatMessageController::class, 'store'])->name('partidas.chat.store');
    Route::delete('/partida/{partida}/chat/{chatMessage}', [ChatMessageController::class, 'destroy'])->name('partidas.chat.destroy');
    Route::delete('/partida/{partida}', [PartidaController::class, 'destroy'])->name('partidas.destroy');
});

/* DESCARGAR */
Route::get('/descargar', [DescargarController::class, 'index'])->name('descargar');

/* REVIEWS */
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

/* PASSWORD RESET */
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

/* ADMIN */
Route::prefix('admin')->name('admin.')->middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::get('/partidas/{partida}/edit', [AdminController::class, 'editPartida'])->name('partidas.edit');
    Route::put('/partidas/{partida}', [AdminController::class, 'updatePartida'])->name('partidas.update');
    Route::delete('/partidas/{partida}', [AdminController::class, 'destroyPartida'])->name('partidas.destroy');
    Route::delete('/usuarios/{user}', [AdminController::class, 'destroyUser'])->name('usuarios.destroy');
    Route::delete('/reviews/{review}', [AdminController::class, 'destroyReview'])->name('reviews.destroy');
});
