<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DriverCarResource extends JsonResource
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
            'max_power' => $this->max_power ?? null,
            'fuel' => $this->fuel ?? null,
            'max_speed' => $this->max_speed ?? null,
            'model' => $this->model ?? null,
            'capacity' => $this->capacity ?? null,
            'color' => $this->color ?? null,
            'fuel_type' => $this->fuel_type ?? null,
            'gear_type' => $this->gear_type ?? null,
            'photo_car' => $this->photo_car ?? null,
        ];
    }
}
