<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RecipieResource extends JsonResource
{
    /**d
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if (!is_null(@$this->group->id)){
            $type = 2;
        }elseif (!is_null(@$this->ingredient_primary_id)){
            $type = 3;
        }else{
            $type = null;
        }
        return [
            'id' => $this->id,
            'name_translate' => $this->name,
            'name' => $this->getTranslations('name'),
//            'description' => $this->description,
            'description_translate' => $this->description,
            'description' => $this->getTranslations('description'),
            'image' => $this->image,
            'total_cost' => $this->total_cost + @$this->ingredient->cost,
            'status' => $this->status,
            'ingredient_primary_id' => @$this->ingredient_primary_id,
            'ingredient_primary_name' => @$this->ingredient->name,
            'ingredient_primary_cost' => @$this->ingredient->cost,
            'is_protein_text' => $this->is_protein_text,
            'protein' => $this->protein,
            'carb' => $this->carb,
            'type' => $type,
            'group_id' => @$this->group->id,
            'group_name' => @$this->group->name,
            'tool_id' => collect($this->tools)->map(function ($tool){
                return $tool['id'];
            }),
            'tool' => collect($this->tools)->map(function ($tool){
                return [
                    'id' => $tool['id'],
                    'name' => $tool['name'],
                ];
            }),
            'cuisine_id' => collect($this->cuisines)->map(function ($cuisine){
                return $cuisine['id'];
            }),
            'cuisines' => collect($this->cuisines)->map(function ($cuisine){
                return [
                    'id' => $cuisine['id'],
                    'name' => $cuisine['name'],
                ];
            }),
            'ingredient' => collect(@$this->ingredients)->map(function ($ingredient){
                return [
                    'id' => $ingredient['id'],
                    'quantity' => $ingredient->pivot->quantity,
                    'name' => $ingredient['name'],
//                    'cost' => $ingredient['cost'],
//                    'line_cost' => $ingredient->pivot->line_cost,
                ];
            }),
            'not_ingredient' => collect(@$this->ingredients)->map(function ($ingredient){
                return $ingredient['name'];
            }),
        ];
    }

    public function listGroups()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }

    public function listCuisines()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }

    public function listTools()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }

    public function listIngredients()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
