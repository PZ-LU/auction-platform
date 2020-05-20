<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth')->group( function () {
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::get('refresh', 'AuthController@refresh');

    Route::group(['middleware' => 'auth:api'], function(){
        Route::get('user', 'AuthController@user');
        Route::post('logout', 'AuthController@logout');
        Route::post('userUpdate', 'UserController@update');
        Route::delete('userDelete', 'UserController@delete');

        Route::prefix('offer')->group(function () {
            Route::post('get', 'OfferController@getUserOffers');   
            Route::post('add', 'OfferController@store');
        });

        Route::prefix('auction')->group(function () {
            Route::post('addParticipant', 'AuctionParticipantsController@store');
            Route::post('checkBid', 'CommercialAuctionController@checkBid');
        });
    });
});

Route::group(['middleware' => 'auth:api'], function () {
    //Users
    Route::get('users', 'UserController@index')->middleware('isAdmin');
    Route::get('users/{id}', 'UserController@show')->middleware('isAdminOrSelf');
});

Route::get('/offers/{filters?}', 'OfferController@index');   
Route::get('/offer/{id}', 'OfferController@show');
Route::get('/offer_media/{id}', 'OfferController@media');
Route::get('/offer_categories', 'OfferCategoryController@index');

Route::get('/auctions/{status?}', 'AuctionController@index');