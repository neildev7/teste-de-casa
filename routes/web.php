<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\HomeController;

// 🏠 Tela inicial (home.blade.php)
Route::get('/', [HomeController::class, 'index'])->name('home');

// 🧙‍♂️ Criação do personagem
Route::get('/create', [GameController::class, 'create'])->name('character.create');
Route::post('/store', [GameController::class, 'store'])->name('character.store');

// 🎓 Tutorial e alocação
Route::get('/tutorial/{id}', [GameController::class, 'tutorial'])->name('character.tutorial');
Route::get('/allocate/{id}', [GameController::class, 'allocate'])->name('character.allocate');
Route::post('/allocate/{id}', [GameController::class, 'allocateStore'])->name('character.allocate.store');

// ⚔️ Tela do jogo
Route::get('/play/{id}', [GameController::class, 'index'])->name('character.play');
Route::post('/attack', [GameController::class, 'attack'])->name('character.attack');

// Atualizar nome do personagem
Route::post('/character/update/{id}', [GameController::class, 'update'])->name('character.update');

// Deletar personagem
Route::delete('/character/delete/{id}', [GameController::class, 'destroy'])->name('character.delete');
