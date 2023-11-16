<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymenResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'      => $this->id,
            'amount'  => $this->amount,
            'paid_on' => $this->paid_on,
            'details' => $this->details,
            'created_at' => $this->created_at,
        ];
    }
}
