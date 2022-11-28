<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Admin\Actions\Subscribe;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Division;
use App\Models\Group;
use App\Models\Ingredient;
use App\Models\Package;
use App\Models\Recipie;
use App\Models\Subscription;
use App\Services\Response;


class HomeController extends Controller
{
    public function home()
    {
        $groupCount = Group::query()->count();
        $subscribeCount = Subscription::query()->count();
        $customerCount = Customer::query()->count();
        $packageCount = Package::query()->count();
        $recipesCount = Recipie::query()->count();
        $ingredientCount = Ingredient::query()->count();
        $nonIngredientCount = Ingredient::query()->count();

        return Response::success(200, [
            'groupCount' => $groupCount,
            'subscribeCount' => $subscribeCount,
            'customerCount' => $customerCount,
            'packageCount' => $packageCount,
            'recipesCount' => $recipesCount,
            'ingredientCount' => $ingredientCount,
            'nonIngredientCount' => $nonIngredientCount,
        ])->send();
    }
}
