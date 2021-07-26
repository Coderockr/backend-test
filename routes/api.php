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
        Route::post('/store', 'Api\\EventController@store');
        Route::get('/{id}/edit', 'Api\\EventController@edit');
        Route::put('/{id}/update', 'Api\\EventController@update');
        Route::put('/{id}/cancel', 'Api\\EventController@cancel');

        Route::post('/{id}/invite/all-friends', 'Api\\EventController@inviteAllFriends');
        Route::post('/{id}/invite/selected-friends', 'Api\\EventController@inviteSelectedFriends');
    });

    Route::group(['prefix' => 'events/my-invitations'], function($router) {
        Route::get('/', 'Api\\EventInvitationController@pending');
        Route::put('/{event_id}/confirm', 'Api\\EventInvitationController@confirm');
        Route::put('/{event_id}/reject', 'Api\\EventInvitationController@reject');
    });

    Route::group(['prefix' => 'friendship'], function($router) {
        Route::get('/friends', 'Api\\FriendshipController@friends');
        Route::post('/{email}/invite', 'Api\\FriendshipController@invite');

        Route::group(['prefix' => 'invitations'], function($router) {
            Route::get('/pending', 'Api\\FriendshipController@pending');
            Route::put('/{user_id}/confirm', 'Api\\FriendshipController@confirm');
            Route::put('/{user_id}/reject', 'Api\\FriendshipController@reject');
        });

        Route::delete('/{friend_id}/remove', 'Api\\FriendshipController@remove');
    });

});

Route::group(['prefix' => 'events'], function($router) {
    Route::get('/', 'Api\\PublicEventController@index');
    Route::get('/{id}/show', 'Api\\PublicEventController@show');
});

// Not Found
Route::fallback(function(){
    return response()->json(['status' => 404, 'message' => 'Resource not found.'], 404);
});