<?php

use App\Http\Controllers\BannedPokemonController;
use App\Http\Controllers\PokemonController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('/banned')->group(function () {
    Route::get('/list', [BannedPokemonController::class, 'index'])->name('banned.list');
    Route::post('/add', [BannedPokemonController::class, 'store'])->name('banned.add');
    Route::delete('/delete/{name}', [BannedPokemonController::class, 'destroy'])->name('banned.delete');
});

Route::get('/info', [PokemonController::class, 'index'])->name('list.pokemon');
Route::post('add', [PokemonController::class, 'store'])->name('add.pokemon');
Route::get('/delete/{name}', [PokemonController::class, 'destroy'])->name('delete.pokemon');
Route::put('/edit/{name}', [PokemonController::class, 'update'])->name('edit.pokemon');

