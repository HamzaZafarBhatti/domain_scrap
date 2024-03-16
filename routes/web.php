<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AvailableDomainController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\KeywordController;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\DomainTldController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\JobDoneController;
use App\Http\Controllers\NicheController;
use App\Http\Controllers\SubNicheController;
use App\Http\Controllers\UserController;
use App\Imports\AdditionalKeywordImport;
use App\Imports\CityImport;
use App\Imports\CountrtyImport;
use Illuminate\Support\Facades\Log;
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
Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);

Route::get('/', function () {
    return redirect()->route('dashboard');
});
Route::get('test', function () {
    Log::info('Test log');
    Log::info(json_encode(request()->all()));
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
    Route::post('cities/get_by_country', [CityController::class, 'get_by_country'])->name('cities.get_by_country');
    Route::resource('keywords', KeywordController::class);
    Route::get('keywords/change_status/{keyword}', [KeywordController::class, 'change_status'])->name('keywords.change_status');
    Route::resource('users', UserController::class);
    Route::get('domain-tld/change_status/{domain_tld}', [DomainTldController::class, 'change_status'])->name('domain-tld.change_status');

    Route::post('countries/import', [CountryController::class,'import'])->name('countries.import');
    Route::post('cities/import', [CityController::class,'import'])->name('cities.import');
    Route::post('keywords/import', [KeywordController::class,'import'])->name('keywords.import');
    Route::post('niches/import', [NicheController::class,'import'])->name('niches.import');
    Route::post('sub-niches/import', [SubNicheController::class,'import'])->name('sub-niches.import');
    Route::post('domain-tld/import', [DomainTldController::class,'import'])->name('domain-tld.import');

    Route::resource('available-domains', AvailableDomainController::class);
    Route::resource('sub-niches', SubNicheController::class);
    Route::get('sub-niches/change_status/{sub_niche}', [SubNicheController::class, 'change_status'])->name('sub-niches.change_status');
    Route::get('job-done', [JobDoneController::class,'index'])->name('job-done.index');
    Route::resource('domain-tld', DomainTldController::class);

    Route::controller(JobController::class)->name('job.')->prefix('job')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/start', 'start')->name('start');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
