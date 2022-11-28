<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name_translate' => $this->name,
            'name' => $this->getTranslations('name'),
            'status' => $this->status,
            'country_id' => @$this->country->id,
            'country_name' => @$this->country->name,
            'delegate_name' => @$this->delegate_name
        ];
    }

    public function listCountries()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
