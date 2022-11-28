<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Requests\CuisineRequest;
use App\Http\Resources\CuisineResource;
use App\Http\Resources\RecipieResource;
use App\Models\Cuisine;
use App\Models\Group;
use App\Models\Ingredient;
use App\Models\Tool;
use App\Services\CuisineService;
use App\Services\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CuisineController extends Controller
{
    private  $request = CuisineRequest::class;


    public function index()
    {
        $items = $this->service()->newModel()->filter()->paginate(10);

        return Response::success(200, $items)->mapInto(CuisineResource::class)->withPagination()->send();
    }

    public function listAll()
    {
        $items = Cuisine::all();

        return Response::success(200, $items)->mapInto(CuisineResource::class)->send();
    }


    public function listCuisines()
    {
        $cuisines = RecipieResource::collection(Cuisine::query()->get())->map->listCuisines();
        return Response::success(200, $cuisines)->send();
    }

    public function listTools()
    {
        $cuisines = RecipieResource::collection(Tool::query()->get())->map->listTools();
        return Response::success(200, $cuisines)->send();
    }

    public function listGroups()
    {
        $cuisines = RecipieResource::collection(Group::query()->get())->map->listGroups();
        return Response::success(200, $cuisines)->send();
    }

    public function listIngredients()
    {
        $cuisines = RecipieResource::collection(Ingredient::query()->where('main', 0)->orderBy('id')->get())->map->listIngredients();
        return Response::success(200, $cuisines)->send();
    }

    public function listPrimaryIngredients()
    {
        $ingeredients = RecipieResource::collection(Ingredient::query()->where('main', 1)->orderBy('id')->get())->map->listIngredients();
        return Response::success(200, $ingeredients)->send();
    }


    public function store()
    {
        $rules = $this->getAttribute()->cuisineRequest();
        $message = $this->getAttribute()->cuisineMessage();
        $validator = Validator::make(request()->all(), $rules, $message);
        if ($validator->fails()) {
            return Response::error()->withMessage($validator->errors()->messages())->send();
        }

        $this->service()->newResourceWith($this->service()->create(request()->all()));

        return Response::success(201)->withMessage('created successfully')->send();
    }


    public function uploadImage($id)
    {
        $cuisine = Cuisine::query()->where('id', $id)->first();
        $deleteImage = last(explode('/', $cuisine['image']));
        if (request()->hasFile('image')){
            Storage::delete('public/'.$deleteImage);
        }
        if (request()->hasFile('image')) {
            $image = request()->file('image')->store('public');
            $data['image'] = $image;
        }
        $cuisine->update($data);
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
        $item =  $this->service()->newResourceWith($this->service()->find($id));
        return Response::success(null,$item)->send();
    }


    public function update($id)
    {
        $rules = $this->getAttribute()->cuisineRequest();
        $message = $this->getAttribute()->cuisineMessage();
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
        $this->service()->delete($id);

        return Response::success(200)->withMessage('deleted successfully')->send();
    }

    public function getAttribute()
    {
        return new $this->request;
    }

    public function service()
    {
        return new CuisineService();
    }
}
