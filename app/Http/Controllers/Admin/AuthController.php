<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Resources\AdminResource;
use App\Models\Admin;
use App\Services\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    public function index()
    {
        $items = Admin::query()->orderByDesc('id')->paginate(10);

        return Response::success(200, $items)->mapInto(AdminResource::class)->withPagination()->send();
    }

    public function listAll()
    {
        $items = Admin::all();

        return Response::success(200, $items)->mapInto(AdminResource::class)->send();
    }


    public function find($id)
    {
        $item =  Admin::query()->where('id', $id)->first();
        return Response::success(200, new AdminResource($item))->send();
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => ['required', Rule::unique('admins')],
            'email' => ['required','email' ,Rule::unique('admins')],
            'password' => ['required', 'string', 'min:6'],
            'confirm_password' => ['required_with:password', 'same:password', 'min:6'],
        ];

        $message = [
            'email.required' => __('common.required'),
            'email.email' => __('common.email_validation'),
            'email.unique' => __('common.email_exists'),
            'password.required' => __('common.required'),
            'password.string' => __('common.password_string'),
            'password.min' => __('common.password_min'),
            'confirm_password.required_with' => __('common.required'),
            'confirm_password.same' => __('common.password_same'),
            'confirm_password.min' => __('common.password_min'),
        ];

        $validator = Validator::make(request()->all(), $rules, $message);
        if ($validator->fails()) {
            return Response::error(422)->withData($validator->errors()->first())->withMessage($validator->errors()->first())->send();
        }

        $admin = Admin::query()->create(array_merge(\request()->all(), ['password' => bcrypt(\request('password'))]));

        $roles = Role::query()->whereIn('id', \request('roles'))->pluck('name');

        $admin->assignRole($roles);

        return Response::success(200)->withMessage('successfully created admin')->send();
    }

    public function destroy($id)
    {
        Admin::query()->where('id', $id)->delete();

        DB::table('model_has_roles')->where('model_id', $id)->delete();

        return Response::success(200)->withMessage('deleted successfully')->send();
    }

    public function roles()
    {
        $items = Role::query()->get()->map(fn($role) => [
            'id' => $role['id'],
            'name' => $role['name'],
        ]);

        return Response::success(200, $items)->send();
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $user['admin'] = Admin::query()->where('email', $request->email)->first() ?? Admin::query()->where('name', $request->email)->first();

        if (! $user['admin'] || ! Hash::check($request->password,  $user['admin']->password)) {
            return Response::error()->withErrors('اسم المستخدم او كلمة المرور غير صحيحة')->withMessage('The provided credentials are incorrect.')->send();
        }

        $roles = collect( $user['admin']->roles)->map(function ($role){
            return $role['name'];
        });

        $user['roles'] = $roles ?? [];


        return Response::success(200, ['user' => $user, 'token' =>  $user['admin']->createToken('admin')->plainTextToken])->send();
    }

    public function logout()
    {
        \request()->user('admin')->tokens()->delete();
        return Response::success()->withMessage('logout successfully')->send();
    }
}
