<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// 🏠 Tela inicial (home.blade.php)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Agrupando todas as rotas do jogo sob o prefixo '/game'
Route::prefix('game')->name('character.')->group(function () {

    // 🧙‍♂️ Criação do personagem (ex: /game/create)
    Route::get('/create', [GameController::class, 'create'])->name('create');
    Route::post('/store', [GameController::class, 'store'])->name('store');

    // 🎓 Tutorial e alocação (ex: /game/tutorial/1)
    Route::get('/tutorial/{id}', [GameController::class, 'tutorial'])->name('tutorial');
    Route::get('/allocate/{id}', [GameController::class, 'allocate'])->name('allocate');
    Route::post('/allocate/{id}', [GameController::class, 'allocateStore'])->name('allocate.store');
    // DENTRO DE routes/web.php, dentro do Route::prefix('game')->...

// ... (suas outras rotas de play, play2, etc)

// ROTA PARA SALVAR O PROGRESSO
Route::post('/save-progress/{id}', [GameController::class, 'saveProgress'])->name('saveProgress');

    // ⚔️ Telas de batalha (ex: /game/play/1, /game/play2/1, etc.)
    Route::get('/play/{id}', [GameController::class, 'play'])->name('play');
    Route::get('/play2/{id}', [GameController::class, 'play2'])->name('play2');
    Route::get('/play3/{id}', [GameController::class, 'play3'])->name('play3');

    // Ações do jogo
    Route::post('/attack', [GameController::class, 'attack'])->name('attack');
    Route::post('/update/{id}', [GameController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [GameController::class, 'destroy'])->name('delete');

});