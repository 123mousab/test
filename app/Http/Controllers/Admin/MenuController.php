<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Requests\MenuRequest;
use App\Http\Resources\MenuResource;
use App\Http\Resources\RecipieResource;
use App\Models\Ingredient;
use App\Models\Menu;
use App\Models\MenuDetail;
use App\Models\Recipie;
use App\Services\MenuService;
use App\Services\Response;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{
    private  $request = MenuRequest::class;


    public function index()
    {
        $items = $this->service()->newModel()->paginate(10);

        return Response::success(200, $items)->mapInto(MenuResource::class)->withPagination()->send();
    }

    public function listAll()
    {
        $items = Menu::all();

        return Response::success(200, $items)->mapInto(MenuResource::class)->send();
    }


    // مكونات غير رئيسية
    public function listSecondGroupIngredients()
    {
        $ingredients = RecipieResource::collection(Ingredient::query()->where('main', 0)->orderBy('id')->get())->map->listIngredients();
        return Response::success(200, $ingredients)->send();
    }

    // مكونات رئيسية للوجبات الرئيسية
    public function listFirstGroupIngredients()
    {
        $ingredients = RecipieResource::collection(Ingredient::query()->where('main', 1)->orderBy('id')->get())->map->listIngredients();
        return Response::success(200, $ingredients)->send();
    }


    public function store()
    {
        $rules = $this->getAttribute()->menuRequest();
        $message = $this->getAttribute()->menuMessage();
        $validator = Validator::make(request()->all(), $rules, $message);
        if ($validator->fails()) {
            return Response::error()->withMessage($validator->errors()->messages())->send();
        }

        $this->service()->newResourceWith($this->service()->save(request()->all()));

        return Response::success(201)->withMessage('created successfully')->send();
    }

    public function listRecipies()
    {
        $recipies = MenuResource::collection(Recipie::query()->get())->map->listRecipies();
        return Response::success(200, $recipies)->withMessage('complete successfully')->send();
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


    public function destroy()
    {
        foreach (explode(',',request('ids')) as $key => $value)
        {
            MenuDetail::query()->where('menu_id', $value)->delete();
        }
        $this->service()->delete(\request()->all());

        return Response::success(200)->withMessage('deleted successfully')->send();
    }

    public function getAttribute()
    {
        return new $this->request;
    }

    public function service()
    {
        return new MenuService();
    }
}
