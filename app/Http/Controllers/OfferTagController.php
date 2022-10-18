<?php

namespace App\Http\Controllers;

use Log;

use App\Offer;
use App\OfferTag;
use App\Http\Resources\Offers\OfferTag as OfferTagResources;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class OfferTagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return OfferTagResources::collection(OfferTag::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function store($arrayData, $offerId)
    {
        $decodedArrayData = json_decode($arrayData);
        $insertArray = array();

        foreach ($decodedArrayData as $dataChunk) {
            array_push($insertArray, array(
                'tag' => $dataChunk->label,
                'category' => $dataChunk->category,
                'offer_id' => $offerId
            ));
        }

        OfferTag::insert($insertArray);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\OfferTag  $OfferTag
     * @return \Illuminate\Http\Response
     */
    public static function show($offerId)
    {
        return OfferTagResources::collection(OfferTag::where('offer_id', $offerId)->get());
    }
}
