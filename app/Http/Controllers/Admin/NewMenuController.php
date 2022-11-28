<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Requests\NewMenuRequest;
use App\Http\Resources\NewMenuResource;
use App\Http\Resources\RecipieResource;
use App\Models\Group;
use App\Models\Ingredient;
use App\Models\Menu;
use App\Models\MenuDetail;
use App\Models\MenuGroupDetail;
use App\Models\MenuIngredientDetail;
use App\Models\Recipie;
use App\Services\NewMenuService;
use App\Services\Response;
use Illuminate\Support\Facades\Validator;

class NewMenuController extends Controller
{
    private $request = NewMenuRequest::class;


    public function index()
    {
        $items = $this->service()->newModel()->paginate(10);

        return Response::success(200, $items)->mapInto(NewMenuResource::class)->withPagination()->send();
    }

    public function listAll()
    {
        $items = Menu::all();

        return Response::success(200, $items)->mapInto(NewMenuResource::class)->send();
    }

    public function listGroups()
    {
        $cuisines = RecipieResource::collection(Group::query()->get())->map->listGroups();
        return Response::success(200, $cuisines)->send();
    }

    public function listRecipiesOfGroup($groupId)
    {
        $recipies = NewMenuResource::collection(Recipie::query()
            ->whereNull('ingredient_primary_id')
            ->where('group_id', $groupId)
            ->get()
        )->map->listRecipies();
        return Response::success(200, $recipies)->withMessage('complete successfully')->send();
    }

    public function listRecipiesOfGroup1()
    {
        $recipies = NewMenuResource::collection(Recipie::query()
            ->whereNull('ingredient_primary_id')
            ->whereNotNull('group_id')
            ->get()
        )->map->listRecipies();
        return Response::success(200, $recipies)->withMessage('complete successfully')->send();
    }

    // مكونات رئيسية للوجبات الرئيسية
    public function listMainIngredients()
    {
        $ingredients = RecipieResource::collection(
            Ingredient::query()->where('main', 1)->orderBy('id')->get()
        )->map->listIngredients();
        return Response::success(200, $ingredients)->send();
    }

    public function listProteinRecipies($ingeredientId)
    {
        $recipies = NewMenuResource::collection(Recipie::query()
            ->where('ingredient_primary_id', $ingeredientId)
            ->where('protein', 1)->get()
        )->map->listRecipies();
        return Response::success(200, $recipies)->withMessage('complete successfully')->send();
    }

    public function listCarbRecipies($ingeredientId)
    {
        $recipies = NewMenuResource::collection(Recipie::query()
//            ->where('ingredient_primary_id', $ingeredientId)
            ->where('carb', 1)->get()
        )->map->listRecipies();
        return Response::success(200, $recipies)->withMessage('complete successfully')->send();
    }

    public function listProteinRecipies1()
    {
        $recipies = NewMenuResource::collection(Recipie::query()
            ->where('protein', 1)->get()
        )->map->listRecipies();
        return Response::success(200, $recipies)->withMessage('complete successfully')->send();
    }

    public function listCarbRecipies1()
    {
        $recipies = NewMenuResource::collection(Recipie::query()
            ->where('carb', 1)->get()
        )->map->listRecipies();
        return Response::success(200, $recipies)->withMessage('complete successfully')->send();
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
        $recipies = NewMenuResource::collection(Recipie::query()->get())->map->listRecipies();
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
        $item = $this->service()->newResourceWith($this->service()->find($id));
        return Response::success(null, $item)->send();
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


    public function destroy($id)
    {

        MenuIngredientDetail::query()->where('menu_id', $id)->delete();
        MenuGroupDetail::query()->where('menu_id', $id)->delete();

        Menu::query()->where('id', $id)->delete();

        return Response::success(200)->withMessage('deleted successfully')->send();
    }

    public function getAttribute()
    {
        return new $this->request;
    }

    public function service()
    {
        return new NewMenuService();
    }
}
