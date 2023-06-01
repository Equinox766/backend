<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->resource->id,
            'name'      => $this->resource->name,
            'email'     => $this->resource->email,
            'phone'     => $this->resource->phone,
            'address'   => $this->resource->address,
            'surname'   => $this->resource->surname,
            'website'   => $this->resource->website,
            'birthdate' => $this->resource->birthdate ?
                $this->resource->birthdate->format('Y-m-d') :
                NULL,
            'avatar'    => $this->resource->avatar ?
                env("APP_URL")."storage/".$this->resource->avatar :
                'https://cdn-icons-png.flaticon.com/512/3135/3135715.png',
        ];
    }
}
