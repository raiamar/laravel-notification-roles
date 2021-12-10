<?php

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
Route::get('show-product', [App\Http\Controllers\HomeController::class,'show_product']);

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::post('/save-token', [App\Http\Controllers\HomeController::class, 'saveToken'])->name('save-token');
Route::post('/send-notification', [App\Http\Controllers\HomeController::class, 'sendNotification'])->name('send.notification');
Route::post('mark-as-read', [App\Http\Controllers\HomeController::class, 'markNotification'])->name('markNotification');
Route::get('check', [App\Http\Controllers\HomeController::class, 'check']);
Route::get('{id}/delete', [App\Http\Controllers\HomeController::class, 'delete']);
Route::get('delete-all', [App\Http\Controllers\HomeController::class, 'delete_all']);


Route::group(['middleware'=>'auth'], function(){
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('{id}/delete-product', [App\Http\Controllers\HomeController::class,'delete_product']);
    Route::group([
        'middleware' => 'is_admin',
        // 'as' => 'admin.',
    ], function(){
        Route::get('{id}/delete', [App\Http\Controllers\HomeController::class, 'delete']);
    });
});




