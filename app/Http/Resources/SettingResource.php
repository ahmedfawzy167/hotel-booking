<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
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
            'logo' => asset('storage/' . $this->logo),
            'location' => $this->location,
            'email' => $this->email,
            'phone' => $this->phone,
            'lat' => $this->lat,
            'long' => $this->long,
        ];
    }
}
