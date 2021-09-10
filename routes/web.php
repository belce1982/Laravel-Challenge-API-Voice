<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VoiceController;

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
Route::group(['middleware' => 'auth'], function () {
    Route::get('/voice', [VoiceController::class, 'index'])
        ->name('voice.index');
    Route::post('/voice', [VoiceController::class, 'voice'])
        ->name('voice.voice');
});
    
require __DIR__.'/auth.php';
