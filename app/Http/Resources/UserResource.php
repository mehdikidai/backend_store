<?php

namespace App\Http\Resources;

use App\Enum\Roles;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'avatar' => $this->avatar ?? "https://avatar.iran.liara.run/public",
            'isAdmin' => $this->roles->pluck('name')->contains(Roles::Admin->value),
            'verified' => $this->email_verified_at !== null,
            'roles' => $this->roles->pluck('name')->toArray(),
            'token' => $this->token

        ];
    }
}
