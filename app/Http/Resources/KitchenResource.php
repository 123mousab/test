<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class KitchenResource extends JsonResource
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
            'cooking_date' => $this->cooking_date->format('Y-m-d'),
            'groups' => collect($this->kithenDetails)->map(function ($kitchenDetail){
                return [
                    'kitchen_detail_id' => $kitchenDetail['id'],
                    'group_id' => @$kitchenDetail['group_id'],
                    'cuisine_id' => @$kitchenDetail['cuisine_id'],
                    'recipie_id' => @$kitchenDetail['recipie_id'],
                    'group_name' => @$kitchenDetail->group->name,
                    'cuisine_name' => @$kitchenDetail->cuisine->name,
                    'recipie_name' => @$kitchenDetail->recipie->name,
                ];
            })
        ];
    }

    public function listRecipies()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
