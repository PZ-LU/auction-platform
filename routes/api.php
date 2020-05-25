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
        // Auth
        Route::get('user', 'AuthController@user');
        Route::post('logout', 'AuthController@logout');
        Route::post('userUpdate', 'UserController@update');
        Route::post('userUpdateAvatar', 'UserController@updateAvatar');
        Route::delete('userDelete', 'UserController@delete');

        // Offers
        Route::prefix('offer')->group(function () {
            Route::post('get', 'OfferController@getUserOffers');   
            Route::post('add', 'OfferController@store');
            Route::post('delete_soft', 'OfferController@softDelete');
        });
        Route::prefix('offers')->group(function () {
            Route::get('/', 'OfferController@getServiceOffers');
            Route::post('setFavorite', 'OfferController@changeFavorite');
        });

        // Auction
        Route::prefix('auction')->group(function () {
            Route::post('addParticipant', 'AuctionParticipantsController@store');
            Route::post('checkBid', 'CommercialAuctionController@checkBid');
            Route::post('add', 'AuctionController@store');
            Route::post('finish', 'AuctionController@finishAuction');
        });
        Route::post('auctions', 'AuctionController@getUserAuctions');

        // Users
        Route::prefix('users')->group(function () {
            Route::get('/', 'UserController@index')->middleware('isAdmin');
            Route::get('service', 'UserController@showAll')->middleware('isAdmin');
            Route::get('/{id}', 'UserController@show')->middleware('isAdminOrSelf');
        });
        Route::prefix('user')->group(function () {
            Route::post('setStatus', 'UserController@setStatus');
            Route::post('setRole', 'UserController@setRole');
        });
    });
});

Route::prefix('offers')->group(function () {
    Route::get('/getAll', 'OfferController@getAll');   
    Route::get('/{filters?}', 'OfferController@index');   
    Route::get('/favorites/{user_id?}', 'OfferController@getFavoriteOffers');   
});
Route::get('/offer/{id}', 'OfferController@show');
Route::get('/offer_media/{id}', 'OfferController@media');
Route::get('/offer_categories', 'OfferCategoryController@index');

Route::get('/auctions/{status?}', 'AuctionController@index');