<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TravelRequestResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'requester_name'    => $this->requester_name,
            'destination'       => $this->destination,
            'departure_date'    => date_format($this->departure_date, 'd/m/Y'),
            'return_date'       => date_format($this->return_date, 'd/m/Y'),
            'status'            => $this->status
        ];
    }
}
