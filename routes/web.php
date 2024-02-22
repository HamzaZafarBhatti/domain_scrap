<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\KeywordController;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\NicheController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('domain.index');
    })->name('dashboard');
    Route::controller(DomainController::class)->name('domain.')->prefix('domain')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/start', 'start')->name('start');
    });

    Route::resource('niche', NicheController::class);
    Route::get('niche/change_status/{niche}', [NicheController::class, 'change_status'])->name('niche.change_status');
    Route::resource('countries', CountryController::class);
    Route::get('countries/change_status/{country}', [CountryController::class, 'change_status'])->name('countries.change_status');
    Route::resource('cities', CityController::class);
    Route::get('cities/change_status/{city}', [CityController::class, 'change_status'])->name('cities.change_status');
    Route::resource('keywords', KeywordController::class);
    Route::get('keywords/change_status/{keyword}', [KeywordController::class, 'change_status'])->name('keywords.change_status');
    Route::resource('users', UserController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
