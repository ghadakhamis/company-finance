<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\TransactionStatus;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'user'             => new UserResource($this->user),
            'due_on'           => $this->due_on,
            'status'           => $this->status,
            'status_label'     => TransactionStatus::getDescription((int) $this->status),
            'amount'           => $this->amount,
            'total_amount'     => $this->total_amount,
            'vat'              => $this->vat,
            'is_vat_inclusive' => $this->is_vat_inclusive,
            'created_at'       => $this->created_at,
            'payments'         => PaymenResource::collection($this->payments),
        ];
    }
}
