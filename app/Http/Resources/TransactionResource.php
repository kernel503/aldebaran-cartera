<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            'amount' => $this->amount,
            'description' => $this->description,
            'created' => $this->created_at->format('Y-m-d'),
            'color' => ($this->amount > 0 ? "teal darken-1" : "deep-orange lighten-2"),
        ];
    }
}
