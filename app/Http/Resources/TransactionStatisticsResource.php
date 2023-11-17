<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionStatisticsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'year'        => $this->year,
            'month'       => $this->month,
            'total'       => $this->total,
            'paid'        => $this->paid?? 0,
            'outstanding' => $this->outstanding?? 0,
            'overdue'     => $this->overdue?? 0,
        ];
    }
}
