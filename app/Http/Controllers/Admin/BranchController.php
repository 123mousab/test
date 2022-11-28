<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Requests\BranchRequest;
use App\Http\Requests\CityRequest;
use App\Http\Resources\BranchResource;
use App\Http\Resources\CityResource;
use App\Models\Branch;
use App\Models\City;
use App\Models\Country;
use App\Services\BranchService;
use App\Services\CityService;
use App\Services\Response;
use Illuminate\Support\Facades\Validator;

class BranchController extends Controller
{
    private  $request = BranchRequest::class;


    public function index()
    {
        $items = $this->service()->newModel()->filter()->paginate(500);
        return Response::success(200, $items)->mapInto(BranchResource::class)->withPagination()->send();
    }

    public function listAll()
    {
        $items = Branch::all();

        return Response::success(200, $items)->mapInto(BranchResource::class)->send();
    }


    public function listCities($id)
    {
        $items = BranchResource::collection(City::query()->where('country_id', $id)->get())->map->listCities();
        return Response::success(200, $items)->send();
    }

    public function listAllCities()
    {
        $items = BranchResource::collection(City::query()->get())->map->listCities();
        return Response::success(200, $items)->send();
    }

    public function listBranches($city_id)
    {
        $items = BranchResource::collection(Branch::query()->where('city_id', $city_id)->get())->map->listCities();
        return Response::success(200, $items)->send();
    }


    public function store()
    {
        $rules = $this->getAttribute()->branchRequest();
        $message = $this->getAttribute()->branchMessage();
        $validator = Validator::make(request()->all(), $rules, $message);
        if ($validator->fails()) {
            return Response::error()->withMessage($validator->errors()->messages())->send();
        }

        $this->service()->newResourceWith($this->service()->create(array_merge(request()->only('name', 'city_id'), [
            'status' => 1
        ])));

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
        $rules = $this->getAttribute()->branchRequest();
        $message = $this->getAttribute()->branchMessage();
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
        return new BranchService();
    }
}
