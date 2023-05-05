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
            'role' => $this->resource->roles->pluck('name'),
            'logo' => $this->resource->user_logo->path,
            'manager_name' => $this->resource->manager_name,
            'manager_email' => $this->resource->manager_email
        ];
    }
}
