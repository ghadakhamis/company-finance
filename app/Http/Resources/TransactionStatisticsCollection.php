<?php

namespace App\Http\Resources;

use App\Traits\CollectionTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TransactionStatisticsCollection extends ResourceCollection
{
    use CollectionTrait;

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data'         => $this->collection,
            'total'        => $this->total(),
            'current_page' => $this->currentPage(),
            'last_page'    => $this->lastPage(),
            'per_page'     => $this->perPage()
        ];
    }
}
