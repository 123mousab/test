<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Requests\KitchenRequest;
use App\Http\Resources\KitchenResource;
use App\Models\Kitchen;
use App\Models\KitchenDetail;
use App\Models\Recipie;
use App\Services\KitchenService;
use App\Services\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class KitchenController extends Controller
{
    private  $request = KitchenRequest::class;


    public function index()
    {
        $items = $this->service()->newModel()->paginate(10);

        return Response::success(200, $items)->mapInto(KitchenResource::class)->withPagination()->send();
    }

    public function listAll()
    {
        $items = Kitchen::all();

        return Response::success(200, $items)->mapInto(KitchenResource::class)->send();
    }


    public function store()
    {
        $rules = $this->getAttribute()->kitchenRequest();
        $message = $this->getAttribute()->kitchenMessage();
        $validator = Validator::make(request()->all(), $rules, $message);
        if ($validator->fails()) {
            return Response::error()->withMessage($validator->errors()->messages())->send();
        }

        $this->service()->newResourceWith($this->service()->save(request()->all()));

        return Response::success(201)->withMessage('created successfully')->send();
    }

    public function listRecipies()
    {
        $recipies = KitchenResource::collection(Recipie::query()
            ->get())->map->listRecipies();
        return Response::success(200, $recipies)->withMessage('complete successfully')->send();
    }

    public function listRecipiesOfGroups()
    {
        $recipies = KitchenResource::collection(Recipie::query()
            ->whereNull('ingredient_primary_id')
            ->whereNotNull('group_id')
            ->get())->map->listRecipies();
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
            KitchenDetail::query()->where('kitchen_id', $value)->delete();
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
        return new KitchenService();
    }
}
