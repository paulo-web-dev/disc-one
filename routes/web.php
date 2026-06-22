<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ConsultantPanelController;
use App\Http\Controllers\DiscController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — DISC ONE
|--------------------------------------------------------------------------
| Sempre entregue completo nos ZIPs, então extrair e substituir é seguro.
*/

// Landing pública (nova identidade visual).
Route::get('/', function () {
    return view('home');
});

// Link de referral do consultor: grava o cookie e leva ao teste.
Route::get('/r/{code}', [ReferralController::class, 'capture'])->name('referral.capture');

// Pós-login: redireciona conforme o perfil.
Route::get('/dashboard', function () {
    $u = auth()->user();

    if ($u && $u->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }

    if ($u && $u->isConsultant()) {
        return redirect()->route('consultant.dashboard');
    }

    return redirect('/');
})->middleware('auth')->name('dashboard');

/*
|--------------------------------------------------------------------------
| Fluxo do teste DISC — PÚBLICO (sem conta, só nome + e-mail + celular)
|--------------------------------------------------------------------------
*/
Route::prefix('teste')->name('test.')->group(function () {
    Route::get('/', [TestController::class, 'intro'])->name('intro');
    Route::post('/iniciar', [TestController::class, 'start'])->name('start');
    Route::get('/{test}/pergunta/{n}', [TestController::class, 'question'])
        ->whereNumber('n')->name('question');
    Route::post('/{test}/pergunta/{n}', [TestController::class, 'saveAnswer'])
        ->whereNumber('n')->name('answer');
    Route::get('/{test}/resultado', [TestController::class, 'result'])->name('result');
});

/*
|--------------------------------------------------------------------------
| Relatórios em documento (página única, pronta para Ctrl+P / salvar PDF)
|--------------------------------------------------------------------------
*/
Route::get('/disc/documento/resultado/{id}', [DiscController::class, 'resultDocumento'])
    ->name('disc.resultDocumento');
Route::get('/disc/documento/premium/resultado/{id}', [DiscController::class, 'resultDocumentoPremium'])
    ->name('disc.resultDocumentoPremium');

/*
|--------------------------------------------------------------------------
| Checkout do relatório premium (Asaas)
|--------------------------------------------------------------------------
*/
Route::get('/disc/checkout/{test}', [CheckoutController::class, 'show'])->name('disc.checkout');
Route::post('/disc/checkout/{test}', [CheckoutController::class, 'process'])->name('disc.checkout.process');
Route::get('/disc/checkout/{test}/retorno', [CheckoutController::class, 'retorno'])->name('disc.checkout.retorno');
Route::get('/disc/checkout/{test}/status', [CheckoutController::class, 'status'])->name('disc.checkout.status');

// Webhook do Asaas (isento de CSRF — ver app/Http/Middleware/VerifyCsrfToken.php).
Route::post('/webhooks/asaas', [CheckoutController::class, 'webhook'])->name('asaas.webhook');

/*
|--------------------------------------------------------------------------
| Painel do CONSULTOR — só os respondentes que vieram pelo link dele
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'consultant'])->prefix('consultor')->name('consultant.')->group(function () {
    Route::get('/', [ConsultantPanelController::class, 'dashboard'])->name('dashboard');
});

// Painel administrativo — protegido por login + papel admin.
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::get('/respondentes', [AdminController::class, 'respondents'])->name('respondents');

    Route::get('/vendas', [AdminController::class, 'purchases'])->name('purchases');

    Route::get('/perguntas', [AdminController::class, 'questions'])->name('questions');
    Route::get('/perguntas/{question}/editar', [AdminController::class, 'editQuestion'])->name('questions.edit');
    Route::put('/perguntas/{question}', [AdminController::class, 'updateQuestion'])->name('questions.update');

    // Consultores / Divulgadores
    Route::get('/consultores', [AdminController::class, 'consultants'])->name('consultants');
    Route::get('/consultores/novo', [AdminController::class, 'createConsultant'])->name('consultants.create');
    Route::post('/consultores', [AdminController::class, 'storeConsultant'])->name('consultants.store');
    Route::get('/consultores/{consultant}/editar', [AdminController::class, 'editConsultant'])->name('consultants.edit');
    Route::put('/consultores/{consultant}', [AdminController::class, 'updateConsultant'])->name('consultants.update');
    Route::delete('/consultores/{consultant}', [AdminController::class, 'destroyConsultant'])->name('consultants.destroy');
});

// Rotas de autenticação do Breeze (login, registro, logout, recuperar senha…).
require __DIR__.'/auth.php';
