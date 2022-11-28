<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use App\Services\CompanyService;
use App\Services\Response;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    private  $request = CompanyRequest::class;


    public function index()
    {
        $items = $this->service()->newModel()->filter()->paginate(10);

        return Response::success(200, $items)->mapInto(CompanyResource::class)->withPagination()->send();
    }

    public function listAll()
    {
        $items = Company::all();

        return Response::success(200, $items)->mapInto(CompanyResource::class)->send();
    }


    public function store()
    {
        $rules = $this->getAttribute()->companyRequest();
        $message = $this->getAttribute()->companyMessage();
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
        $company =  new CompanyResource(Company::query()->findOrFail($id));
        return Response::success(null,$company)->send();
    }


    public function update($id)
    {
        $rules = $this->getAttribute()->companyRequest();
        $message = $this->getAttribute()->companyMessage();
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
        return new CompanyService();
    }
}
