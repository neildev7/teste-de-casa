<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ROTA PRINCIPAL - Tela de seleção de personagem (Guild Hall)
Route::get('/', [HomeController::class, 'index'])->name('home');


// --- GRUPO DE ROTAS DO JOGO ---
// Todas as rotas aqui dentro terão o prefixo de URL /game e o prefixo de nome 'character.'
Route::prefix('game')->name('character.')->group(function () {

    // 🧙‍♂️ Criação do personagem (ex: /game/create)
    Route::get('/create', [GameController::class, 'create'])->name('create');
    Route::post('/store', [GameController::class, 'store'])->name('store');

    // 🎓 Tutorial e Alocação de Pontos (ex: /game/tutorial/1)
    Route::get('/tutorial/{id}', [GameController::class, 'tutorial'])->name('tutorial');
    Route::get('/allocate/{id}', [GameController::class, 'allocate'])->name('allocate');
    Route::post('/allocate/{id}', [GameController::class, 'allocateStore'])->name('allocate.store');

    // ⚔️ Fases da Batalha (ex: /game/play/1)
    Route::get('/play/{id}', [GameController::class, 'play'])->name('play');
    Route::get('/play2/{id}', [GameController::class, 'play2'])->name('play2');
    Route::get('/play3/{id}', [GameController::class, 'play3'])->name('play3');

    // 🏪 Loja e Sistema de Compras
    Route::get('/shop/{id}', [GameController::class, 'shop'])->name('shop');
    Route::post('/shop/buy/{id}', [GameController::class, 'buyItem'])->name('shop.buy');
    
    // 💾 Ações do Jogo (Salvar, Editar, Deletar)
    Route::post('/save-progress/{id}', [GameController::class, 'saveProgress'])->name('saveProgress');
    Route::post('/update/{id}', [GameController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [GameController::class, 'destroy'])->name('delete');

});