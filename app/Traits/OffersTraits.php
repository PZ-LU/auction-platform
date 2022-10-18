<?php

namespace App\Traits;

use App\Http\Controllers\OfferTagController;
use App\Http\Controllers\UserController;

/**
 * 
 */
trait OffersTraits
{
    // Offer Tags
    public function showOfferTags($offerId) {
        return OfferTagController::show($offerId);
    }

    public function storeOfferTags($arrayData, $offerId) {
        return OfferTagController::store($arrayData, $offerId);
    }

    public function getOfferAuthor($userId) {
        return UserController::findById($userId);
    }
}
