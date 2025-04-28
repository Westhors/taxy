<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'gender' => $this->gender,
            'avatar' => $this->avatar,
            // 'email_verified_at' => $this->email_verified_at,
            // 'createdAt' => $this->created_at ? $this->created_at->format('Y-M-d H:i:s A') : null,
            // 'updatedAt' => $this->updated_at ? $this->updated_at->format('Y-M-d H:i:s A') : null,
            // 'deletedAt' => $this->deleted_at ? $this->deleted_at->format('Y-M-d H:i:s A') : null,
            // 'deleted' => isset($this->deleted_at),
        ];
    }
}