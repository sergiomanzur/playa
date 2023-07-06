<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
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

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');

    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'main'])->name('dashboard');

    Route::get('/recibos/{pagoId}', [\App\Http\Controllers\DashboardController::class, 'recibo'])->name('recibo');

    Route::get('/estados-de-cuenta/{balanceId}', [\App\Http\Controllers\DashboardController::class, 'printBalance'])->name('printBalance');

    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/pagos', [\App\Http\Controllers\PagosController::class, 'index'])->name('pagos.index');
    Route::post('/pagos/insertar', [\App\Http\Controllers\PagosController::class, 'insert'])->name('pagos.insert');

    Route::get('/cuenta-madre', [\App\Http\Controllers\DashboardController::class, 'cuentaMadre'])->name('cuentaMadre.index');
    Route::post('/cuenta-madre/dashboard', [\App\Http\Controllers\DashboardController::class, 'cuentaMadreDashboard'])->name('cuentaMadreDashboard');

    Route::get('/blog/noticias', [\App\Http\Controllers\BlogController::class, 'noticiasIndex'])->name('blog.noticias');
    Route::get('/post/{slug}', [PostController::class, 'show'])->name('post.show');
});

require __DIR__.'/auth.php';
