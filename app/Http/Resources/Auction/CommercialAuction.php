<?php

namespace App\Http\Resources\Auction;

use Illuminate\Http\Resources\Json\JsonResource;

class CommercialAuction extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'auction_id' => $this->auction_id,
            'start_bid' => $this->start_bid,
            'highest_bid_user_id' => $this->highest_bid_user_id,
            'end_date' => $this->end_date,
        ];
    }
}
