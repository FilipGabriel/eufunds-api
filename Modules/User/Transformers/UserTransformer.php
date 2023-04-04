<?php

namespace Modules\User\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class UserTransformer extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'email' => $this->resource->email,
            'phone'   => $this->resource->phone,
            'about' => $this->resource->about,
            'role' => $this->getRoleName($this->resource),
            'logo' => $this->resource->user_logo->path
        ];
    }

    private function getRoleName($user)
    {
        if($user->isCustomer()) {
            return null;
        }

        return $user->roles->first()->name;
    }
}
