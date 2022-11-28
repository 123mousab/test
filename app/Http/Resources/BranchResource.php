<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BranchResource extends JsonResource
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
            'city_id' => @(int)$this->city_id,
            'country_id' => @(int)$this->city->country->id,
            'country_name' => @$this->city->country->name,
            'city_name' => @$this->city->name
        ];
    }

    public function listCities()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
