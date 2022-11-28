<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PackageRequest;
use App\Http\Resources\PackageResource;
use App\Models\Package;
use App\Services\PackageService;
use App\Services\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PackageController extends Controller
{
    private  $request = PackageRequest::class;


    public function index()
    {
        $items = $this->service()->newModel()->filter()->paginate(10);
        return Response::success(200, $items)->mapInto(PackageResource::class)->withPagination()->send();
    }

    public function listAll()
    {
        $items = Package::all();

        return Response::success(200, $items)->mapInto(PackageResource::class)->send();
    }



    public function listPackages()
    {
        $items = PackageResource::collection(Package::query()->get())->map->listPackages();
        return Response::success(200, $items)->send();
    }

    public function detailPackages($package_id)
    {
        $item =  ($this->service()->newResourceWith($this->service()->find($package_id)))->details();
        return Response::success(200,$item)->send();
    }



    public function store()
    {
        $rules = $this->getAttribute()->packageRequest();
        $message = $this->getAttribute()->packageMessages();
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
        $rules = $this->getAttribute()->packageRequest();
        $message = $this->getAttribute()->packageMessages();
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
        return new PackageService();
    }
}
