<?php

namespace App\Traits;

use App\Http\Controllers\AuctionObjectController;
use App\Http\Controllers\AuctionParticipantsController;
use App\Http\Controllers\CharityAuctionController;
use App\Http\Controllers\CommercialAuctionController;
use App\Http\Controllers\UserController;
use App\Http\Resources\Auction\AuctionParticipants as AuctionParticipantsResources;
use App\Http\Resources\Auction\AuctionObject as AuctionObjectResources;
use Illuminate\Http\Request;

trait AuctionTraits
{
    public function indexActive() {
        return [
            'charity' => (new CharityAuctionController)->getActive(),
            'commercial' => (new CommercialAuctionController)->getActive()
        ];
    }

    // Retrieve collection of auction objects for given IDs
    public function getAuctionObjects($auctionsObject_Model, $objectIds) {
        return AuctionObjectResources::collection($auctionsObject_Model->whereIn('id', $objectIds));
    }

    // Retrieve participant of given auction for given user ID
    public function findAuctionParticipant($auctionId, $userId) {
        return (new AuctionParticipantsController)->show($auctionId, $userId);
    }

    // Retrieve collection of participants for given auction
    public function getAuctionParticipants($auctionParticipants_Model, $auctionId) {
        $participantsInfo = $auctionParticipants_Model->where('auction_id', $auctionId);

        $userIds = [];
        foreach ($participantsInfo as $participant_data_chunk) {
            $userIds[] = $participant_data_chunk->user_id;
        }
        $usersInfo = (new UserController)->findByIDs($userIds);

        try {
            // Expand user data for final resource
            foreach ($participantsInfo as $participant){
                foreach ($usersInfo as $userChunk) {
                    if (json_encode($userChunk->id) == $participant->user_id) {
                        $participant->username = $userChunk->username;
                        $participant->email = $userChunk->email;
                        $participant->avatar_path = $userChunk->avatar_path;
                    }
                }
            }
        } catch (\Throwable $err) {
            $participantsInfo->username = $usersInfo[0]->username;
            $participantsInfo->email = $usersInfo[0]->email;
            $participantsInfo->avatar_path = $usersInfo[0]->avatar_path;
        }
        
        return AuctionParticipantsResources::collection($participantsInfo);
    }

    // Get auction data by ID
    public function getAuctionTypeData ($auctionId, $auctionType) {
        switch ($auctionType) {
            case 'charity':
                return (new CharityAuctionController)->getDetailsByAuctionId($auctionId);

            case 'commercial':
                return (new CommercialAuctionController)->getDetailsByAuctionId($auctionId);
        }
    }

    public function createAuctionObject ($objectName, $objectDescription, $objectTypeId, $previewImage) {
        return (new AuctionObjectController)->create($objectName, $objectDescription, $objectTypeId, $previewImage);
    }

    public function createCharityAuction ($auctionId, $goal) {
        (new CharityAuctionController)->create($auctionId, $goal);
    }
    
    public function createCommercialAuction ($auctionId, $startBid, $endDate) {
        (new CommercialAuctionController)->create($auctionId, $startBid, $endDate);
    }
}
