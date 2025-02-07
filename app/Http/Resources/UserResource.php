<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\User
 */
class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'email_verified' => $this->email_verified_at !== null,
            'image' => null,
            'cap_name' => $this->getCapName(),
        ];
    }

    private function getCapName(): string
    {
        $names = explode(' ', $this->name);
        $initials = array_map(fn($name) => substr($name, 0, 1), $names);
        return implode('', array_slice($initials, 0, 3));
    }
}
