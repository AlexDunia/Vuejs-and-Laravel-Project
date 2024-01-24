<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ApplicationController;

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

// So, this is a fucntion, that fetches the data itself. It is not the view
// itself, the view is already a single paged application.
// At least that is how I understand it to be so far.
Route::get('/api/users', [UserController::class, 'index']);
Route::post('/post/user', [UserController::class, 'storeuser']);
// we need a conditional here.
// Where thsi view can be zero or more of any characters.
Route::get('{view}', ApplicationController::class)->where('view', '(.*)');

Route::PUT('/api/users/{user}', [UserController::class, 'update']);