<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JsonController;

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


Route::get('/create', [JsonController::class, 'create'])
       ->name('json.create');

Route::middleware('auth:sanctum')->match(['get','post'], '/store', [JsonController::class, 'store'])
        ->name('json.store');

Route::get('/list', [JsonController::class, 'list'])
        ->name('json.list');

Route::get('/read', [JsonController::class, 'read'])
        ->name('json.read');

Route::middleware('auth:sanctum')->match(['get','post'], '/delete', [JsonController::class, 'delete'])
        ->name('json.delete');

