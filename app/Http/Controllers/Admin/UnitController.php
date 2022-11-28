<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Requests\UnitRequest;
use App\Http\Resources\UnitResource;
use App\Models\Unit;
use App\Services\Response;
use App\Services\UnitService;
use Illuminate\Support\Facades\Validator;

class UnitController extends Controller
{
    private  $request = UnitRequest::class;


    public function index()
    {
        $items = $this->service()->newModel()->filter()->paginate(10);

        return Response::success(200, $items)->mapInto(UnitResource::class)->withPagination()->send();
    }

    public function listAll()
    {
        $items = Unit::all();

        return Response::success(200, $items)->mapInto(UnitResource::class)->send();
    }


    public function store()
    {
        $rules = $this->getAttribute()->unitRequest();
        $message = $this->getAttribute()->unitMessage();
        $validator = Validator::make(request()->all(), $rules, $message);
        if ($validator->fails()) {
            return Response::error()->withMessage($validator->errors()->messages())->send();
        }

        $this->service()->newResourceWith($this->service()->create(request()->all()));

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
        $unit =  new UnitResource(Unit::query()->findOrFail($id));
        return Response::success(null,$unit)->send();
    }


    public function update($id)
    {
        $rules = $this->getAttribute()->unitRequest();
        $message = $this->getAttribute()->unitMessage();
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
        return new UnitService();
    }
}
