<?php

namespace App\Http\Controllers;

use App\CommercialAuction;
use App\Http\Resources\Auction\CommercialAuction as CommercialAuctionResources;
use App\Traits\AuctionTraits;
use Illuminate\Http\Request;

class CommercialAuctionController extends Controller
{
    use AuctionTraits;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return CommercialAuctionResources::collection(CommercialAuction::all());
    }

    // Get info including bids and participation end date
    public function getDetailsByAuctionId($auctionId) {
        return CommercialAuctionResources::collection(CommercialAuction::where('auction_id', $auctionId)->get());
    }

    /**
     * Set bid to the highest provided
     */
    public function checkBid(Request $request) {
        $auction = new CommercialAuction;
        $auction = $auction->where('auction_id', $request->auction_id)->first();

        if (is_null($auction->highest_bid_user_id)) {
            $auction->highest_bid_user_id = $request->user_id;
            $auction->save();
            
            return response()->json([
                'status' => 'success',
                'msg' => 'Bid was null'
            ], 200);
        }

        $currentBiggestBidUser = $this->findAuctionParticipant($request->auction_id, $auction->highest_bid_user_id);
        if ($request->amount > $currentBiggestBidUser->amount) {
            $auction->highest_bid_user_id = $request->user_id;
            $auction->save();

            return response()->json([
                'status' => 'success',
                'msg' => 'Bid replaced'
            ], 200);
        }

        return response()->json([
            'status' => 'success',
            'msg' => 'No changes to the bid'
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($auctionId, $startBid, $endDate)
    {
        $commercialAuction = new CommercialAuction();
        $commercialAuction->auction_id = $auctionId;
        $commercialAuction->start_bid = $startBid;
        $commercialAuction->end_date = $endDate;
        $commercialAuction->save();
    }
}
