<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;


Route::get('/', [GameController::class, 'create'])->name('character.create'); // Tela de criação
Route::post('/store', [GameController::class, 'store'])->name('character.store'); // Salvar personagem

Route::get('/tutorial/{id}', [GameController::class, 'tutorial'])->name('character.tutorial'); // Tutorial
Route::get('/allocate/{id}', [GameController::class, 'allocate'])->name('character.allocate'); // Tela de alocação
Route::post('/allocate/{id}', [GameController::class, 'allocateStore'])->name('character.allocate.store'); // Salvar atributos

Route::get('/play/{id}', [GameController::class, 'index'])->name('character.play'); // Tela do jogo
Route::post('/attack', [GameController::class, 'attack'])->name('character.attack'); // Ataque

