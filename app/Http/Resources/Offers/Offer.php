<?php

namespace App\Http\Resources\Offers;

use Illuminate\Http\Resources\Json\JsonResource;

class Offer extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $arrayData = [
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body,
            'contact_phone' => $this->contact_phone,
            'contact_email' => $this->contact_email,
            'preview_image' => $this->preview_image
        ];

        if ($this->parts) {
            $arrayData['parts'] = $this->parts;
        }

        return $arrayData;
    }
}
