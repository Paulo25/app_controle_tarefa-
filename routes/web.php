<?php

use App\Http\Controllers\TarefaController;
use App\Mail\MensagemTesteMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});


Auth::routes(['verify' => true]);

Route::get('tarefa/exportacao/{extensao}', [TarefaController::class, 'exportacao'])->name('tarefa.exportacao');
Route::get('tarefa/exportacao-pdf', [TarefaController::class, 'exportacaoPDF'])->name('tarefa.exportacao-pdf');

Route::middleware(['verified'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::resource('tarefa', 'App\Http\Controllers\TarefaController'); //->middleware('auth');

});



Route::get('/acesso-negado', function(){
    return view('acesso-negado');
})->name('acesso.negado');

Route::get('/mensagem-teste', function () {
    return new MensagemTesteMail();
    //Mail::to('paulo.vitor.cs97@gmail.com')->send(new MensagemTesteMail());
    //return 'E-mail enviado com sucesso!';
});
