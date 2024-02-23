<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\KeywordController;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\UserController;
use App\Imports\AdditionalKeywordImport;
use App\Imports\CityImport;
use App\Imports\CountrtyImport;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

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
Route::get('test', function () {
    $countries = public_path('countries.xls');
    Excel::import(new CountrtyImport, $countries);
    $cities = public_path('cities.xls');
    Excel::import(new CityImport, $cities);
    $keywords = public_path('keywords.xls');
    Excel::import(new AdditionalKeywordImport, $keywords);

});
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

    Route::resource('countries', CountryController::class);
    Route::get('countries/change_status/{country}', [CountryController::class, 'change_status'])->name('countries.change_status');
    Route::resource('cities', CityController::class);
    Route::resource('keywords', KeywordController::class);
    Route::resource('users', UserController::class);

    Route::get('cities/change_status/{city}', [CityController::class, 'change_status'])->name('cities.change_status');

    Route::post('countries/import', [CountryController::class,'import'])->name('countries.import');
    Route::post('cities/import', [CityController::class,'import'])->name('cities.import');
    Route::post('keywords/import', [KeywordController::class,'import'])->name('keywords.import');


});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
