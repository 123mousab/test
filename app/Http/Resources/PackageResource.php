<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $stringDays = [
            0 => 'Sunday',
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
        ];

        return [
            'id' => $this->id,
            'name_translate' => $this->name,
            'name' => $this->getTranslations('name'),
            'number_of_days' => $this->number_of_days,
            'number_of_meals' => $this->number_of_meals,
            'cost' => $this->cost,
            'price' => $this->price,
            'status' => $this->status,
            'days'=> collect($this->packageDay)->map(function ($day) use($stringDays){
                return (int)$day['day'];

            }),
            'details'=> collect($this->packageDetails)->map(function ($detail){
                return [
                    'id' => (int)$detail['group_id'],
                    'quantity' => $detail['quantity']
                ];
            }),
        ];
    }

    public function details()
    {
        return [
            'id' => $this->id,
            'name_translate' => $this->name,
            'name' => $this->getTranslations('name'),
            'number_of_days' => $this->number_of_days,
            'cost' => $this->cost,
            'price' => $this->price,
            'days'=> collect($this->packageDay)->map(function ($day){
                return (int)$day['day'];

            }),
            'details'=> collect($this->packageDetails)->map(function ($detail){
                return [
                    'id' => (int)$detail['group_id'],
                    'group_name' => $detail->group->name,
                    'quantity' => $detail['quantity']
                ];
            }),
        ];
    }

    public function listPackages()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
