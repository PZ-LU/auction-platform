<?php

namespace App\Http\Controllers;

use App\AuctionParticipants;
use App\Http\Resources\Auction\AuctionParticipants as AuctionParticipantsResources;
use App\Http\Resources\Auction\Auction as AuctionResource;
use App\Http\Controllers\CommercialAuctionController;
use App\Http\Controllers\AuctionParticipantsController;
use App\CommercialAuction;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Jobs\ProcessAuctionParticipantCommercial;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;


class AuctionParticipantsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return AuctionParticipantsResources::collection(AuctionParticipants::all());
    }

    public function indexByAuctionType($auctionType)
    {
        return AuctionParticipantsResources::collection(AuctionParticipants::where('auction_type', $auctionType));
    }

    public static function storeCommercial($request)
    {
        $auctionId = $request['auction_id'];
        $amount = $request['amount'];
        // Info comes from endpoint so check if auction exists
        $auctionCollection = (new CommercialAuctionController)->getDetailsByAuctionId($auctionId);
        if (count($auctionCollection) == 0) return;
        $auction = $auctionCollection[0];
        $commercialAuctionController = new CommercialAuctionController();
        $highestBid = $commercialAuctionController->getLastBid($auction->auction_id);

        // Compare latest auction info with incoming bidding
        if ($highestBid && $amount <= $highestBid) return;

        // Check if user is replacing their bid
        $existingParticipant = (new AuctionParticipantsController)->show($auctionId, $request['user_id']);
        $participant = null;
        if ($existingParticipant) {
            $participant = $existingParticipant;
        } else {
            $participant = new AuctionParticipants;
            $participant->auction_id = $auctionId;
            $participant->user_id = $request['user_id'];
        }
        $participant->amount = $amount;
        $participant->save();
        $commercialAuctionController->placeBid($request);
    }

    public function dispatchStoreCommercial(Request $request)
    {
        ProcessAuctionParticipantCommercial::dispatch($request->all())->onQueue('high');
        return response()->json(['status' => 'success'], 200);
    }

    public function storeCharity(Request $request)
    {
        $participant = new AuctionParticipants;
        $participant->auction_id = $request->auction_id;
        $participant->user_id = $request->user_id;
        $participant->amount = $request->amount;
        $participant->save();

        return response()->json(['status' => 'success'], 200);
    }

    /**
     * Display the specified resource: Auction <-> Participant.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($auctionId, $userId)
    {
        return (new AuctionParticipants)->where('auction_id', $auctionId)->where('user_id', $userId)->first();
    }
}
