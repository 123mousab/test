<?php


namespace App\Services;


use App\Http\Resources\RecipieResource;
use App\Models\Ingredient;
use App\Models\Recipie;
use App\Models\RecipieIngeredient;

class ReceipieService extends BaseService
{

    static protected $model = Recipie::class;
    static protected $resource = RecipieResource::class;

    /*public function save($data)
    {
        $recipie = $this->create($data);

        $ingredientData = collect(request('ingredient'))->only(['id', 'quantity']);

        $toolData = collect(request('tool'))->only(['id']);

        $cuisineData = collect(request('cuisine'))->only(['id']);


        $ingredientData->transpose()->map(function ($ingredients) use ($recipie) {
        $ingredientCost = Ingredient::query()->where('id', $ingredients[0])->firstOrFail()->cost;
            $recipie->ingredients()->attach($ingredients[0] ,
                [
                    'quantity' => $ingredients[1],
                    'cost' => $ingredientCost,
                    'line_cost' => $ingredientCost * $ingredients[1]
                ]);
        });

        $recipie->tools()->attach($toolData['id']);

        $recipie->cuisines()->attach($cuisineData['id']);

        $total_cost = RecipieIngeredient::query()
            ->where('recipie_id', $recipie->id)->sum('line_cost');

        return $recipie->update([
            'total_cost' => $total_cost
        ]);
    }*/

    public function save($data)
    {
        $recipie = $this->create($data);


        collect(request('ingredient'))->map(function ($ingredients) use ($recipie) {
            $ingredientCost = Ingredient::query()->where('id', $ingredients['id'])->firstOrFail()->cost;
            $recipie->ingredients()->attach($ingredients['id'],
                [
                    'quantity' => $ingredients['quantity'],
                    'cost' => $ingredientCost,
                    'line_cost' => $ingredientCost * $ingredients['quantity']
                ]);
        });

        if (request()->has('tool_id') && request('tool_id')) {
            $toolData = collect(request('tool_id'));

            $recipie->tools()->attach($toolData);
        }

        if (request()->has('cuisine_id') && request('cuisine_id')) {
            $cuisineData = collect(request('cuisine_id'));
            $recipie->cuisines()->attach($cuisineData);
        }


        if (request()->has('ingredient_primary_id') and request('ingredient_primary_id'))
        {
            $ingredientCostPrimaryCost = Ingredient::query()->where('id', request('ingredient_primary_id'))->firstOrFail()->cost ?? 0;
        }else{
            $ingredientCostPrimaryCost = 0;
        }

        if (RecipieIngeredient::query()
            ->where('recipie_id', $recipie->id)->exists())
        {
            $total_cost = RecipieIngeredient::query()
                ->where('recipie_id', $recipie->id)->sum('line_cost');
        }else{
            $total_cost = 0;
        }

        return $recipie->update([
            'total_cost' => $total_cost + $ingredientCostPrimaryCost
        ]);
    }

    public function update($id, $data)
    {
        $recipie = $this->find($id);

        $recipie->update($data);

        $recipie1 = $this->find($id);

        $toolData = collect(request('tool_id'));

        $cuisineData = collect(request('cuisine_id'));

        $recipie1->ingredients()->detach();
        $recipie1->tools()->detach();
        $recipie1->cuisines()->detach();

        if (request()->has('ingredient') && request()->input('ingredient')){
            collect(request('ingredient'))->map(function ($ingredients) use ($recipie) {
                $ingredientCost = Ingredient::query()->where('id', $ingredients['id'])->firstOrFail()->cost;
                $recipie->ingredients()->attach($ingredients['id'],
                    [
                        'quantity' => $ingredients['quantity'],
                        'cost' => $ingredientCost,
                        'line_cost' => $ingredientCost * $ingredients['quantity']
                    ]);
            });
        }

        $recipie1->tools()->attach($toolData);

        $recipie1->cuisines()->attach($cuisineData);

        if (request()->has('ingredient_primary_id') && request('ingredient_primary_id'))
        {
            $ingredientCostPrimaryCost = Ingredient::query()->where('id', request('ingredient_primary_id'))->firstOrFail()->cost ?? 0;

            $total_cost = RecipieIngeredient::query()
                ->where('recipie_id', $recipie1->id)->sum('line_cost');

             $recipie1->update([
                'total_cost' => $total_cost + $ingredientCostPrimaryCost
            ]);
        }

        return $recipie1;
    }
}
