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

Route::post('register', 'AuthController@register');

Route::prefix('auth')->group( function () {
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
            Route::prefix('tags')->group(function () {
                Route::post('addCategory', 'TagCategoryController@storeCategory');
                Route::post('deleteCategory', 'TagCategoryController@deleteCategory');
            });
        });

        // Auction
        Route::prefix('auction')->group(function () {
            Route::post('addParticipant', 'AuctionParticipantsController@store');
            Route::post('checkBid', 'CommercialAuctionController@checkBid');
            Route::post('add', 'AuctionController@store');
            Route::post('finish', 'AuctionController@finishAuction');
            Route::prefix('object')->group(function () {
                Route::post('addType', 'AuctionObjectController@storeType');
                Route::post('deleteType', 'AuctionObjectController@deleteType');
            });
        });
        Route::post('auctions', 'AuctionController@getUserAuctions');

        // Forum
        Route::prefix('forum')->group(function () {
            Route::prefix('topic')->group(function () {
                Route::post('add', 'TopicController@store');
                Route::post('delete', 'TopicController@delete');
            });
            Route::prefix('comment')->group(function () {
                Route::post('add', 'TopicCommentController@store');
                Route::post('delete', 'TopicCommentController@delete');
            });
            Route::prefix('category')->group(function () {
                Route::post('add', 'TopicCategoryController@store');
                Route::post('delete', 'TopicCategoryController@delete');
            });
        });

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
Route::get('/tag_categories', 'TagCategoryController@index');

Route::get('/auctions/{status?}', 'AuctionController@index');
Route::prefix('auction')->group(function () {
    Route::get('/objects', 'AuctionObjectController@index_types');
});

Route::prefix('forum')->group(function () {
    Route::get('/categories', 'TopicCategoryController@index');
    Route::get('/comments/{topic?}', 'TopicCommentController@index');
    Route::get('/topics/{category?}', 'TopicController@index');
});