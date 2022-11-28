<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Requests\IngredientRequest;
use App\Http\Resources\IngredientResource;
use App\Http\Resources\RecipieResource;
use App\Models\Division;
use App\Models\Ingredient;
use App\Models\NutriotionFact;
use App\Models\Unit;
use App\Services\IngredientService;
use App\Services\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class IngredientController extends Controller
{
    private $request = IngredientRequest::class;

    public function index()
    {
        $items = $this->service()->newModel()->filter()->orderByDesc('id')->paginate(300);

        return Response::success(200, $items)->mapInto(IngredientResource::class)->withPagination()->send();
    }

    public function listAll()
    {
        $items = Ingredient::all();

        return Response::success(200, $items)->mapInto(IngredientResource::class)->send();
    }


    public function store()
    {
        $rules = $this->getAttribute()->ingredientRequest();
        $message = $this->getAttribute()->ingredientMessage();
        $validator = Validator::make(request()->all(), $rules, $message);
        if ($validator->fails()) {
            return Response::error()->withMessage($validator->errors()->messages())->send();
        }

        $this->service()->newResourceWith($this->service()->create(request()->all()));

        return Response::success(201)->withMessage('created successfully')->send();
    }

    public function uploadImage($id)
    {
        $ingredient = Ingredient::query()->where('id', $id)->first();
        $deleteImage = last(explode('/', $ingredient['image']));

        if (request()->hasFile('image')) {
            Storage::delete('public/' . $deleteImage);
        }

        if (request()->hasFile('image')) {
            $image = request()->file('image')->store('public');
            $data['image'] = $image;
        }
        $ingredient->update($data);
        return Response::success(200)->withMessage('created successfully')->send();
    }

    /**
     * Get the detail of a given model.
     *
     * @param $id
     *
     */
    public function find($id)
    {
        $item = $this->service()->newResourceWith($this->service()->find($id));
        return Response::success(null, $item)->send();
    }


    public function update($id)
    {
        $rules = $this->getAttribute()->ingredientRequest();
        $message = $this->getAttribute()->ingredientMessage();
        $validator = Validator::make(request()->all(), $rules, $message);
        if ($validator->fails()) {
            return Response::error()->withMessage($validator->errors()->messages())->send();
        }

        $this->service()->newResourceWith($this->service()->update($id, request()->all()));

        return Response::success(200)->withMessage('updated successfully')->send();
    }

    /**
     * Update stauts one or many.
     *
     */
    public function updateStatus($id)
    {
        $this->service()->updateStatus($id);

        return Response::success(200)->withMessage('updated status successfully')->send();
    }


    public function destroy($id)
    {

        DB::table('ingredient_nutriotion_facts')->where('ingredient_id', $id)->delete();

        Ingredient::query()->where('id', $id)->delete();

        return Response::success(200)->withMessage('deleted successfully')->send();
    }

    public function getAttribute()
    {
        return new $this->request;
    }

    public function service()
    {
        return new IngredientService();
    }

    public function listUnit()
    {
        $units = IngredientResource::collection(Unit::query()->get())->map->listUnit();
        return Response::success(200, $units)->withMessage('complete successfully')->send();
    }

    public function listDivision()
    {
        $division = IngredientResource::collection(Division::query()->get())->map->listDivision();
        return Response::success(200, $division)->withMessage('complete successfully')->send();
    }

    public function listNutriotionFact()
    {
        $nutrion_facts = IngredientResource::collection(NutriotionFact::query()->get())->map->listNutriotionFact();
        return Response::success(200, $nutrion_facts)->withMessage('complete successfully')->send();
    }
}
