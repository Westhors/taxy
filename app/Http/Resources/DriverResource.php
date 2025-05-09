<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DriverResource extends JsonResource
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
            'name' => $this->name ?? null,
            'email' => $this->email ?? null,
            'phone' => $this->phone ?? null,
            'gender' => $this->gender ?? null,
            // 'avatar' => $this->avatar ? asset('storage/' . $this->avatar) : null,
            'avatar' => $this->avatar ?? null,
            'city_id' => $this->city_id ?? null,
            'district_id' => $this->district_id ?? null,
        ];
    }
}
