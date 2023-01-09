<?php

namespace App\Http\Controllers;

use App\AuctionParticipants;
use App\Http\Resources\Auction\AuctionParticipants as AuctionParticipantsResources;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class AuctionParticipantsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return AuctionParticipantsResources::collection(Auctionparticipants::all());
    }

    public function indexByAuctionType($auctionType)
    {
        return AuctionParticipantsResources::collection(Auctionparticipants::where('auction_type', $auctionType));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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
