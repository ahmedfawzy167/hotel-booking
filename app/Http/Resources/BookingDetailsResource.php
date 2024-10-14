<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'check_in_date' => $this->check_in_date,
            'check_out_date' => $this->check_out_date,
            'hotel' => $this->hotel->name,
            'Created' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
        ];
    }
}
