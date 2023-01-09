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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'preview_image' => $this->preview_image,
            'status' => $this->status
        ];

        // Conditionally append author or tag data
        if ($this->author_info) {
            $arrayData['author_info'] = $this->author_info;
        }

        if ($this->author_id) {
            $arrayData['author_id'] = $this->author_id;
        }

        if ($this->tags) {
            $arrayData['tags'] = $this->tags;
        }

        return $arrayData;
    }
}
