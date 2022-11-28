<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Requests\DivisionRequest;
use App\Http\Resources\DivisionResource;
use App\Models\Division;
use App\Services\DivisionService;
use App\Services\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DivisionController extends Controller
{
    private  $request = DivisionRequest::class;


    public function index()
    {
        $items = $this->service()
            ->newModel()
            ->filter()
            ->paginate(10);

        return Response::success(200, $items)->mapInto(DivisionResource::class)->withPagination()->send();
    }

    public function listAll()
    {
        $items = Division::all();

        return Response::success(200, $items)->mapInto(DivisionResource::class)->send();
    }


    public function store()
    {
        $rules = $this->getAttribute()->divisionRequest();
        $message = $this->getAttribute()->divisionMessage();
        $validator = Validator::make(request()->all(), $rules, $message);
        if ($validator->fails()) {
            return Response::error()->withMessage($validator->errors()->messages())->send();
        }

        $this->service()->newResourceWith($this->service()->create(request()->all()));

        return Response::success(201)->withMessage('created successfully')->send();
    }

    public function uploadImage($id)
    {
        $division = Division::query()->where('id', $id)->first();
        $deleteImage = last(explode('/', $division['image']));
        if (request()->hasFile('image')){
            Storage::delete('public/'.$deleteImage);
        }
        if (request()->hasFile('image')) {
            $image = request()->file('image')->store('public');
            $data['image'] = $image;
        }
        $division->update($data);
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
        $rules = $this->getAttribute()->divisionRequest();
        $message = $this->getAttribute()->divisionMessage();
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
        return new DivisionService();
    }
}
