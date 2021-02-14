<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\FriendshipController;
use App\Http\Controllers\FriendshipRequestController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvitationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:api')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/events', [EventController::class, 'index'])->name('events');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [EventController::class, 'delete'])->name('events.delete');

    Route::get('/friendship-requests', [FriendshipRequestController::class, 'index'])->name('friendship-requests');
    Route::post('/friendship-requests', [FriendshipRequestController::class, 'store'])->name('friendship-requests.store');

    Route::get('/friendships', [FriendshipController::class, 'index'])->name('friendships');
    Route::delete('/friendships/{friendship}', [FriendshipController::class, 'delete'])->name('friendships.delete');

    Route::get('/invitations', [InvitationController::class, 'index'])->name('invitations');
    Route::post('/invitations', [InvitationController::class, 'store'])->name('invitations.store');
});
