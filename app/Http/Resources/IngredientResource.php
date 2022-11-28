<?php

namespace App\Http\Resources;

use App\Models\Unit;
use Illuminate\Http\Resources\Json\JsonResource;

class IngredientResource extends JsonResource
{
    /**d
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
            'image' => $this->image,
            'description_translate' => $this->description,
            'description' => $this->getTranslations('description'),
            'cost' => $this->cost,
            'unit' => @$this->unit->name,
            'unit_id' => @$this->unit_id,
            'division_id' => @$this->division_id,
            'status' => $this->status,
            'main' => $this->main,
            'main_text' => $this->main == 0 ? 'غير اساسي' : 'اساسي',
            'nutriotion_fact' => $this->nutritionFacts()
        ];
    }

    private function nutritionFacts()
    {
        return collect($this->nutriotionFacts)->map(function ($nutrionFact){
            return [
                'nutriotion_fact_id' => $nutrionFact['id'],
                'nutriotion_fact_name' => $nutrionFact['name'],
                'value' => $nutrionFact->pivot['value'],
//                'unit' => Unit::query()->where('id', @$nutrionFact->pivot['unit_id'])->firstOrFail()->name,
                'unit_ids' => @$nutrionFact->pivot['unit_id'],
            ];
        });
    }

    public function listUnit()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }

    public function listDivision()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }

    public function listNutriotionFact()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
