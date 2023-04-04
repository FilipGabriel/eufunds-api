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
            'username' => $this->resource->username,
            'about' => $this->resource->about,
            'role' => $this->getRoleName($this->resource),
            'logo' => $this->resource->user_logo->path,
            'experience' => optional($this->resource->profile)->experience,
            'education' => optional($this->resource->profile)->education,
            'skills' => optional($this->resource->profile)->skills,
            'languages' => optional($this->resource->profile)->languages,
            'certificates' => optional($this->resource->profile)->certificates,
            'driving_license' => optional($this->resource->profile)->driving_license,
            'volunteering' => optional($this->resource->profile)->volunteering,
            'profile_public'   => $this->resource->profile_public,
            'phone_public'   => $this->resource->phone_public,
            'email_public'   => $this->resource->email_public,
            'urls' => [
                'delete_url' => route('api.users.destroy', $this->resource->id),
            ],
            'devices' => $this->resource->userDevices,
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
