<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MenuResource extends JsonResource
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
            'first_group' => collect($this->menuFirstGroup)->map(function ($menuDetail){
                return [
                    'menu_id' => $menuDetail['id'],
                    'ingredient_id' => (int)@$menuDetail['ingredient_id'],
                    'cuisine_id' => (int)@$menuDetail['cuisine_id'],
                    'recipie_id' => (int)@$menuDetail['recipie_id'],
                    'ingredient_name' => @$menuDetail->ingredient->name,
                    'cuisine_name' => @$menuDetail->cuisine->name,
                    'recipie_name' => @$menuDetail->recipie->name,
                    'type' => @@$menuDetail['type'],
                ];
            }),
            'second_group' => collect($this->menuSecondGroup)->map(function ($menuDetail){
                return [
                    'menu_id' => $menuDetail['id'],
                    'ingredient_id' => (int)@$menuDetail['ingredient_id'],
                    'cuisine_id' => (int)@$menuDetail['cuisine_id'],
                    'recipie_id' => (int)@$menuDetail['recipie_id'],
                    'ingredient_name' => @$menuDetail->ingredient->name,
                    'cuisine_name' => @$menuDetail->cuisine->name,
                    'recipie_name' => @$menuDetail->recipie->name,
                    'type' => @@$menuDetail['type'],
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
