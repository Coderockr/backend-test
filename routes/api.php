<?php

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

Route::group(['prefix' => 'auth'], function($router) {
    Route::post('register', 'Api\\AuthController@register');
    Route::post('login', 'Api\\AuthController@login');
});

Route::group(['middleware' => ['apiJwt']], function($router) {
    Route::group(['prefix' => 'auth'], function($router) {
        Route::post('logout', 'Api\\AuthController@logout');
        Route::post('me', 'Api\\AuthController@me');
    });

    Route::group(['prefix' => 'my-events'], function($router) {
        Route::get('/', 'Api\\EventController@index');
        Route::post('/', 'Api\\EventController@store');
        Route::get('/{id}/edit', 'Api\\EventController@edit');
        Route::put('/{id}/update', 'Api\\EventController@update');
        Route::put('/{id}/cancel', 'Api\\EventController@cancel');

        Route::group(['prefix' => 'invite'], function($router) {
            Route::post('/{id}/all-friends', 'Api\\EventController@inviteAllFriends');
            Route::post('/{id}/selected-friends', 'Api\\EventController@inviteSelectedFriends');
        });
    });

    Route::group(['prefix' => 'events/invitations'], function($router) {
        Route::get('/pending', 'Api\\EventInvitationController@pending');
        Route::put('/{id}/confirm', 'Api\\EventInvitationController@confirm');
        Route::put('/{id}/reject', 'Api\\EventInvitationController@reject');
    });

    Route::group(['prefix' => 'friendship'], function($router) {
        Route::post('/invite/{email}', 'Api\\FriendshipController@invite');

        Route::get('/pending', 'Api\\FriendshipController@pending');
        Route::put('/{id}/confirm', 'Api\\FriendshipController@confirm');
        Route::put('/{id}/reject', 'Api\\FriendshipController@reject');
        Route::delete('/{friend_id}/remove', 'Api\\FriendshipController@remove');
    });

    Route::get('/friends', 'Api\\FriendshipController@friends');
});

Route::group(['prefix' => 'events'], function($router) {
    Route::get('/', 'Api\\PublicEventController@index');
    Route::get('/{id}', 'Api\\PublicEventController@show');
});

// Not Found
Route::fallback(function(){
    return response()->json(['status' => 404, 'message' => 'Resource not found.'], 404);
});