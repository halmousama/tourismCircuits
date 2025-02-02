<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\SignUpController;
use App\Http\Controllers\CircuitController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/signup', function () {
    return view('signup');
});

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/home', function () {
    return view('home');
})->name('home');

Route::get('/map', function () {
    return view('map');
})->name('map');

Auth::routes();
Route::post('/signup', [SignUpController::class, 'signup']);
Route::post('/login', [LoginController::class, 'login']);
Route::get('login', function () {
    return view('login');
})->name('login');

Route::post('/store-circuit', [CircuitController::class, 'store'])->name('store.circuit');

Route::get('/api/circuits', [CircuitController::class, 'getCircuits'])->name('get.circuits');

Route::delete('/api/circuits/{id}', [CircuitController::class, 'destroy'])->name('destroy.circuit');

Route::post('/api/update-coordinates/{id}', [CircuitController::class, 'update'])->name('update.circuit');

Route::get('/view-circuit/{id}', [CircuitController::class, 'show'])->name('view.circuit');

Route::get('/export-circuit/{id}', [CircuitController::class, 'export'])->name('export.circuit');

Route::get('/download-circuit/{id}', [CircuitController::class, 'downloadPdf'])->name('download.circuit');

Route::fallback(function () {
    return redirect()->route('home');
});
