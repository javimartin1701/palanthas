<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LibroController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/libros', [LibroController::class, 'index'])->name('libros.index');
Route::get('/libros/create', [LibroController::class, 'create'])->name('libros.create');
Route::post('/libros', [LibroController::class, 'store'])->name('libros.store');
Route::delete('/libros/{id}', [LibroController::class, 'destroy'])->name('libros.destroy');
Route::get('/libros/{id}', [LibroController::class, 'show'])->name('libros.show');
Route::put('/libros/{id}', [LibroController::class, 'update'])->name('libros.update');


Route::post('/buscar', [LibroController::class, 'buscar'])->name('libros.buscar');
Route::post('/buscargoogle', [LibroController::class, 'buscargoogle'])->name('libros.buscargoogle');




Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
