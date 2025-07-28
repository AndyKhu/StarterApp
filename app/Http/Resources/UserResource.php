<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'       => $this->id,
            'username' => $this->username,
            'status'   => (bool) $this->status,
            'role'     => $this->roles->first()?->only(['id', 'name']),
            'role_id' => $this->roles->first()?->id,
            'profile'  => [
                'id'        => $this->profile->id,
                'full_name' => $this->profile->full_name,
                'phone'     => $this->profile->phone,
                'address'   => $this->profile->address,
                'avatar'    => $this->profile->avatar
                    ? asset('storage/' . $this->profile->avatar)
                    : null,
            ],
        ];
    }

    /**
     * A helper method to get a plain array (no "data" wrapper).
     */
    public static function collectionArray($users)
    {
        return parent::collection($users)->resolve();
    }
}
