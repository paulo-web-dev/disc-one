<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — DISC ONE
|--------------------------------------------------------------------------
| Sempre entregue completo nos ZIPs, então extrair e substituir é seguro.
*/

// Home de teste (Etapa 2) — vira a landing real mais pra frente.
Route::get('/', function () {
    return view('teste');
});

// Pós-login: o Breeze redireciona pra cá.
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

// Fluxo do teste DISC (respondente logado).
Route::middleware('auth')->prefix('teste')->name('test.')->group(function () {
    Route::get('/', [TestController::class, 'intro'])->name('intro');
    Route::post('/iniciar', [TestController::class, 'start'])->name('start');
    Route::get('/{test}/pergunta/{n}', [TestController::class, 'question'])
        ->whereNumber('n')->name('question');
    Route::post('/{test}/pergunta/{n}', [TestController::class, 'saveAnswer'])
        ->whereNumber('n')->name('answer');
    Route::get('/{test}/resultado', [TestController::class, 'result'])->name('result');
});

// Painel administrativo — protegido pelo papel admin.
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::get('/respondentes', [AdminController::class, 'respondents'])->name('respondents');
    Route::get('/respondentes/{user}', [AdminController::class, 'showRespondent'])->name('respondent.show');

    Route::get('/vendas', [AdminController::class, 'purchases'])->name('purchases');

    Route::get('/perguntas', [AdminController::class, 'questions'])->name('questions');
    Route::get('/perguntas/{question}/editar', [AdminController::class, 'editQuestion'])->name('questions.edit');
    Route::put('/perguntas/{question}', [AdminController::class, 'updateQuestion'])->name('questions.update');
});

// Rotas de autenticação do Breeze (login, registro, logout, recuperar senha…).
require __DIR__.'/auth.php';
