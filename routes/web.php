<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SurvivorController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Models\Pool;
use App\Http\Controllers\PickemController;
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
Route::get('/sesh', [HomeController::class, 'testSesh']);
Route::get('/winner', [HomeController::class, 'winner']);
Route::get('/', [HomeController::class, 'index'])->name('viewHome');
Route::middleware([
    'auth:sanctum', 'web',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {

//Route::get('/pickem', [PickemController::class, 'index'])->name('pickem');

Route::get('/contact-us', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact-us/submit', [ContactController::class, 'store'])->name('contact.post');
Route::get('/player-survey', [ContactController::class, 'viewSurvey'])->name('survey.index');
Route::post('/player-survey/submit', [ContactController::class, 'storeSurvey'])->name('survey.post');


 

Route::get('/survivor-test', [SurvivorController::class, 'viewTest']);
Route::get('/survivor-demo', [SurvivorController::class, 'viewDemo'])->name('survivor-demo');
Route::get('/pools', [SurvivorController::class, 'subscribe'])->name('survivor-plans');
Route::get('/survivor/guest', [SurvivorController::class, 'signup']);
Route::post('/guest/survivor', [SurvivorController::class, 'newUserOrder'])->name('orderguest');
Route::post('/purchase/survivor', [SurvivorController::class, 'order'])->name('ordersurvivor');



Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/referal', [HomeController::class, 'referalInfo'])->name('refer.view');
    Route::get('/my-pools', [SurvivorController::class, 'myPools'])->name('mypools');
    Route::get('/pickem', [PickemController::class, 'index'])->name('pickem');

    });

    Route::group(['middleware' => ['auth:sanctum', 'web'], 'prefix' => 'survivor'], function() {


    Route::get('{pool:name}', [SurvivorController::class, 'showByPool'])
    //->middleware('can:view,pool')
    ->name('survivor');

    Route::get('{pool:name}/eliminated', [SurvivorController::class, 'viewEliminated'])
    //->middleware('can:viewEliminated,pool')
    ->name('survivor.eliminated');

  


  
Route::get('/survivor', function () {
return view('dashboard');
});


});

