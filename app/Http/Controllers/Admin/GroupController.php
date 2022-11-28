<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Requests\GroupRequest;
use App\Http\Resources\GroupResource;
use App\Models\Group;
use App\Services\GroupService;
use App\Services\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class GroupController extends Controller
{
    private  $request = GroupRequest::class;


    public function index()
    {
        $items = $this->service()->newModel()->filter()->paginate(10);

        return Response::success(200, $items)->mapInto(GroupResource::class)->withPagination()->send();
    }

    public function listAll()
    {
        $items = Group::all();

        return Response::success(200, $items)->mapInto(GroupResource::class)->send();
    }


    public function store()
    {
        $rules = $this->getAttribute()->groupRequest();
        $message = $this->getAttribute()->groupMessage();
        $validator = Validator::make(request()->all(), $rules, $message);
        if ($validator->fails()) {
            return Response::error()->withMessage($validator->errors()->messages())->send();
        }

        $this->service()->newResourceWith($this->service()->create(request()->all()));

        return Response::success(201)->withMessage('created successfully')->send();
    }

    public function uploadImage($id)
    {
        $group = Group::query()->where('id', $id)->first();
        $deleteImage = last(explode('/', $group['image']));
        if (request()->hasFile('image')){
            Storage::delete('public/'.$deleteImage);
        }
        if (request()->hasFile('image')) {
            $image = request()->file('image')->store('public');
            $data['image'] = $image;
        }
        $group->update($data);
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
        $rules = $this->getAttribute()->groupRequest();
        $message = $this->getAttribute()->groupMessage();
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
        return new GroupService();
    }
}
