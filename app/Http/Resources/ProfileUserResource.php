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
            'name'      => $this->resource->name,
            'email'     => $this->resource->email,
            'phone'     => $this->resource->phone,
            'address'   => $this->resource->address,
            'surname'   => $this->resource->surname,
            'website'   => $this->resource->website,
            'birthdate' => $this->resource->birthdate->format('Y-m-d'),
            'avatar'    => env("APP_URL")."storage/".$this->resource->avatar,
        ];
    }
}
