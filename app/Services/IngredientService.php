<?php


namespace App\Services;


use App\Http\Resources\IngredientResource;
use App\Models\Ingredient;

class IngredientService extends BaseService
{

    static protected $model = Ingredient::class;
    static protected $resource = IngredientResource::class;

    public function create($data)
    {
        $ingredient = $this->newModel()->query()->create([
            'name' => @$data['name'],
            'description' => @$data['description'],
            'cost' => @$data['cost'] ?? 0,
            'unit_id' => @$data['unit_id'],
            'main' => @$data['main'],
            'division_id' => @$data['division_id']
        ]);

        // Form Data Passing
        /*$requestData = collect(request('ingredients'))->only(['nutriotion_fact_id', 'unit_ids', 'value']);

       return $requestData->transpose()->map(function ($nutritionFact) use ($ingredient) {
            $ingredient->nutriotionFacts()->attach($nutritionFact[0] ,
                    [
                        'unit_id' => $nutritionFact[1],
                        'value' => $nutritionFact[2],
                    ]);
        });*/

        if (request()->has('nutriotion_fact') and request('nutriotion_fact'))
        {
            collect(request('nutriotion_fact'))->map(function ($nutritionFact) use ($ingredient) {
                $ingredient->nutriotionFacts()->attach($nutritionFact['nutriotion_fact_id'] ,
                    [
                        'unit_id' => $nutritionFact['unit_ids'],
                        'value' => $nutritionFact['unit_ids'],
                    ]);
            });
        }
        return;
    }

    public function update($id, $data)
    {
        $ingredient = $this->find($id);
        $ingredient->update([
            'name' => @$data['name'],
            'description' => @$data['description'],
            'cost' => @$data['cost'],
            'unit_id' => @$data['unit_id'],
            'main' => @$data['main'],
            'division_id' => @$data['division_id']
        ]);

        $ingredient1 = $this->find($id);


        $ingredient1->nutriotionFacts()->detach();
        return  collect(request('nutriotion_fact'))->map(function ($nutritionFact) use ($ingredient) {
            $ingredient->nutriotionFacts()->attach($nutritionFact['nutriotion_fact_id'] ,
                [
                    'unit_id' => $nutritionFact['unit_ids'],
                    'value' => $nutritionFact['value'],
                ]);
        });
    }
}
