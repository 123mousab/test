<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Requests\CityRequest;
use App\Http\Resources\CityResource;
use App\Models\City;
use App\Models\Country;
use App\Services\CityService;
use App\Services\Response;
use Illuminate\Support\Facades\Validator;

class CityController extends Controller
{
    private  $request = CityRequest::class;


    public function index()
    {
        $items = $this->service()->newModel()->filter()->paginate(50);
        return Response::success(200, $items)->mapInto(CityResource::class)->withPagination()->send();
    }

    public function listAll()
    {
        $items = City::all();

        return Response::success(200, $items)->mapInto(CityResource::class)->send();
    }


    public function listCountries()
    {
        $items = CityResource::collection(Country::query()->get())->map->listCountries();
        return Response::success(200, $items)->send();
    }


    public function store()
    {
        $rules = $this->getAttribute()->cityRequest();
        $message = $this->getAttribute()->cityMessage();
        $validator = Validator::make(request()->all(), $rules, $message);
        if ($validator->fails()) {
            return Response::error()->withMessage($validator->errors()->messages())->send();
        }

        $this->service()->newResourceWith($this->service()->create(request()->all() + ['status' => 1]));

        return Response::success(201)->withMessage('created successfully')->send();
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
        return Response::success(200,$item)->send();
    }


    public function update($id)
    {
        $rules = $this->getAttribute()->cityRequest();
        $message = $this->getAttribute()->cityMessage();
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
        return new CityService();
    }
}
