<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NewMenuResource extends JsonResource
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
            'first_group' => collect($this->menuIngredientDetails)->map(function ($menuDetail){
                return [
                    'id' => $menuDetail['id'],
                    'menu_id' => (int)$menuDetail['menu_id'],
                    'ingredient_id' => (int)@$menuDetail['ingredient_id'],
                    'recipie_protein_id' => (int)@$menuDetail['recipie_protein_id'],
                    'cuisine_protein_id' => (int)@$menuDetail['cuisine_protein_id'],
                    'recipie_carb_id' => (int)@$menuDetail['recipie_carb_id'],
                    'cuisine_carb_id' => (int)@$menuDetail['cuisine_carb_id'],
                    'ingredient_name' => @$menuDetail->ingredient->name,
                    'recipie_protein_name' => @$menuDetail->recipieProtein->name,
                    'cuisine_protein_name' => @$menuDetail->cuisineProtein->name,
                    'recipie_carb_name' => @$menuDetail->recipieCarb->name,
                    'cuisine_carb_name' => @$menuDetail->cuisineCarb->name,
                ];
            }),
            'second_group' => collect($this->menuGroupDetails)->map(function ($menuDetail){
                return [
                    'id' => $menuDetail['id'],
                    'menu_id' => (int)$menuDetail['menu_id'],
                    'group_id' => (int)@$menuDetail['group_id'],
                    'recipie_id' => (int)@$menuDetail['recipie_id'],
                    'cuisine_id' => (int)@$menuDetail['cuisine_id'],
                    'group_name' => @$menuDetail->group->name,
                    'recipie_name' => @$menuDetail->recipie->name,
                    'cuisine_name' => @$menuDetail->cuisine->name,
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
