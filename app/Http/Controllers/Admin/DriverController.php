<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Requests\DriverRequest;
use App\Http\Resources\DriverResource;
use App\Models\Driver;
use App\Services\DriverService;
use App\Services\Response;
use Illuminate\Support\Facades\Validator;

class DriverController extends Controller
{
    private  $request = DriverRequest::class;


    public function index()
    {
        $items = $this->service()->newModel()->paginate(10);
        return Response::success(200, $items)->mapInto(DriverResource::class)->withPagination()->send();
    }

    public function listAll()
    {
        $items = Driver::all();

        return Response::success(200, $items)->mapInto(DriverResource::class)->send();
    }


    public function listDrivers()
    {
        $items = DriverResource::collection(Driver::query()->get())->map->listDrivers();
        return Response::success(200, $items)->send();
    }

    public function store()
    {
        $rules = $this->getAttribute()->driverRequest();
        $message = $this->getAttribute()->driverMessage();
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
        $item =  $this->service()->newResourceWith($this->service()->find($id));
        return Response::success(200,$item)->send();
    }


    public function update($id)
    {
        $rules = $this->getAttribute()->driverRequest();
        $message = $this->getAttribute()->driverMessage();
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
        return new DriverService();
    }
}
