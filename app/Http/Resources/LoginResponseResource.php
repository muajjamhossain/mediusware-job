<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginResponseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user' => $this['user'],
            'token' => $this['token'],
            // if want to be specific with DTO
            // 'name' => $this['user']->name,
            // 'email' => $this['user']->email,
            // 'email_verified_at' => isset($this['user']->email_verified_at) ? true : false,
        ];
    }
}
