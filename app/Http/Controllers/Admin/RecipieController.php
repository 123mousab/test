<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Requests\RecipieRequest;
use App\Http\Resources\RecipieResource;
use App\Models\Cuisine;
use App\Models\Group;
use App\Models\Ingredient;
use App\Models\Recipie;
use App\Models\Tool;
use App\Services\ReceipieService;
use App\Services\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class RecipieController extends Controller
{
    private  $request = RecipieRequest::class;

    public function index()
    {
        $items = Recipie::query()->filter()->paginate(500);
       return Response::success(200, $items)->mapInto(RecipieResource::class)->withPagination()->send();
    }

    public function listAll()
    {
        $items = Recipie::all();

        return Response::success(200, $items)->mapInto(RecipieResource::class)->send();
    }


    public function store()
    {
        $rules = $this->getAttribute()->recipieRequest();
        $message = $this->getAttribute()->recipieMessage();
        $validator = Validator::make(request()->all(), $rules, $message);
        if ($validator->fails()) {
            return Response::error()->withMessage($validator->errors()->messages())->send();
        }

        $this->service()->newResourceWith($this->service()->save(request()->all()));

        return Response::success(201)->withMessage('created successfully')->send();
    }

    public function uploadImage($id)
    {
        $recipie = Recipie::query()->where('id', $id)->first();
        $deleteImage = last(explode('/', $recipie['image']));
        if (request()->hasFile('image')){
            Storage::delete('public/'.$deleteImage);
        }
        if (request()->hasFile('image')) {
            $image = request()->file('image')->store('public');
            $data['image'] = $image;
        }
        $recipie->update($data);
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
        $rules = $this->getAttribute()->recipieRequest();
        $message = $this->getAttribute()->recipieMessage();
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
            DB::table('recipie_tool')->where('recipie_id', $id)->delete();
            DB::table('recipie_ingeredients')->where('recipie_id', $id)->delete();
            DB::table('cuisine_recipie')->where('recipie_id', $id)->delete();

        Recipie::query()->where('id', $id)->delete();

        return Response::success(200)->withMessage('deleted successfully')->send();
    }

    public function getAttribute()
    {
        return new $this->request;
    }

    public function service()
    {
        return new ReceipieService();
    }
}
