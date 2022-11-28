<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Actions\Subscribe;
use App\Http\Controllers\Admin\DataTransfer\SubscribeDataTransfer;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubscribeRequest;
use App\Http\Resources\BankNameResource;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\GroupNameResource;
use App\Http\Resources\PeriodResource;
use App\Http\Resources\RecipieResource;
use App\Http\Resources\SubscribeResource;
use App\Models\Bank;
use App\Models\BankName;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Delivery;
use App\Models\ExcludeIngredient;
use App\Models\ExcludeNotIngredient;
use App\Models\ExcludeRecipie;
use App\Models\FeatureGroupSubscriptionRecipie;
use App\Models\GiveSubscription;
use App\Models\GroupName;
use App\Models\GroupSubscription;
use App\Models\Ingredient;
use App\Models\Kitchen;
use App\Models\Measurement;
use App\Models\Menu;
use App\Models\Package;
use App\Models\PackageCustomerSubscription;
use App\Models\PackageDay;
use App\Models\Period;
use App\Models\PersonalDesires;
use App\Models\StopSubscription;
use App\Models\Subscription;
use App\Models\SubscriptionDay;
use App\Models\SubscriptionDetail;
use App\Models\Transaction;
use App\Services\Response;
use App\Services\SubscribeService;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PDF;

class SubscribeController extends Controller
{

    public function listAll()
    {
        $items = Subscription::all();

        return Response::success(200, $items)->mapInto(SubscribeResource::class)->send();
    }

    public function subscribe(SubscribeDataTransfer $subscribeDataTransfer)
    {
        SubscribeService::subscribe($subscribeDataTransfer);
        return Response::success(201)->withMessage('completed successfully')->send();
    }

    public function detailPackages($package_id)
    {
        $item = ($this->service()->newResourceWith(Package::query()->find($package_id)))->details();
        return Response::success(200, $item)->send();
    }

    // مكونات غير رئيسية
    public function listSecondGroupIngredients()
    {
        $ingredients = RecipieResource::collection(Ingredient::query()->where('main', 0)->orderBy('id')->get())->map->listIngredients();
        return Response::success(200, $ingredients)->send();
    }

    // مكونات رئيسية للوجبات الرئيسية
    public function listFirstGroupIngredients()
    {
        $ingredients = RecipieResource::collection(Ingredient::query()->where('main', 1)->orderBy('id')->get())->map->listIngredients();
        return Response::success(200, $ingredients)->send();
    }

    public function tableCookingToday()
    {
        $subscriptionDetails = SubscriptionDetail::query()->where('subscription_id', '>', 42)->whereHas('subscription', function ($query) {
            return $query->where('status', 1);
        })->whereDate('subscription_dates', request('date'))->get();

        $data = collect($subscriptionDetails)->map(function ($details) {
            $customer = Customer::query()->where('id', $details->subscription->customer_id)->first();
            $package = Package::query()->where('id', $details->subscription->package_id)->first();
            $groupSubscription = GroupSubscription::query()->where('subscription_id', $details['subscription_id'])->get();
//            $featureGroupSubscription = FeatureGroupSubscriptionRecipie::query()->where('subscription_id', $details['subscription_id'])->get();
            $excludeMainIngredients = ExcludeIngredient::query()->where('subscription_id', $details['subscription_id'])->get();
            $excludeMainNotIngredients = ExcludeNotIngredient::query()->where('subscription_id', $details['subscription_id'])->get();
            $personalDesires = PersonalDesires::query()->where('subscription_id', $details['subscription_id'])->first();
            $menu = Menu::query()->whereDate('cooking_date', request('date'))->first();
            $deliveries = Delivery::query()->where('subscription_id', $details['subscription_id'])->first();
            $excludeRecipes = ExcludeRecipie::query()->where('subscription_id', $details['subscription_id'])->get();

            $numberOfMeal = $package->number_of_meals;
            $reserved = 0;
            $reservedList[] = null;

            $reservedGroup = 0;
            $reservedGroupList[] = null;
            $transferData = new SubscribeDataTransfer();
            $transferData->setSubscribeId($details['subscription_id']);

            // هندلة المميزات
            $itemGroupMenu = @collect($menu->menuGroupDetails)->map(function ($menuFirstGroup) use ($excludeMainNotIngredients, $transferData) {
                $arr1 = collect(@$excludeMainNotIngredients)->pluck('ingredient_id')->toArray();
                $arr2 = DB::table('recipie_ingeredients')->where('recipie_id', $menuFirstGroup->recipie_id)->pluck('ingredient_id')->toArray();
                $result = !empty(array_intersect($arr1, $arr2));


                return [
                    'id' => @$menuFirstGroup->id,
                    'recipie_id' => @$menuFirstGroup->recipie_id,
                    'recipie_name' => @$menuFirstGroup->recipie->name,
                    'group_id' => @$menuFirstGroup->group_id,
                    'group_name' => @$menuFirstGroup->group->name,
                    'cuisine_id' => @$menuFirstGroup->cuisine_id,
                    'cuisine_name' => @$menuFirstGroup->cuisine->name,
                    'excluded' => $result,
                ];
            })->filter(function ($exclude) {
                return $exclude['excluded'] != true;
            })->values();

            $newItemGroupMenu = collect($itemGroupMenu)->map(function ($itemGroup) use ($groupSubscription, $transferData) {
                $itemGroupSub = @(collect(@$groupSubscription)->pluck('group_id'))->contains($itemGroup['group_id']);
                if ($itemGroupSub) {
                    return [
                        'id' => @$itemGroup['id'],
                        'recipie_id' => @$itemGroup['recipie_id'],
                        'recipie_name' => @$itemGroup['recipie_name'],
                        'group_id' => @$itemGroup['group_id'],
                        'group_name' => @$itemGroup['group_name'],
                        'cuisine_id' => @$itemGroup['cuisine_id'],
                        'cuisine_name' => @$itemGroup['cuisine_name'],
                        'quantity' => GroupSubscription::query()->where('subscription_id', $transferData->getSubscribeId())
                            ->where('group_id', @$itemGroup['group_id'])->first()->quantity,
                        'excluded' => @$itemGroup['excluded'],
                    ];
                }
            })->filter(function ($list) {
                return $list != null;
            })->groupBy('group_id');

            $itemGroupMenu2 = collect($newItemGroupMenu)->each(function ($item) use ($excludeRecipes, $transferData) {
                return collect($item)->each(function ($data) use ($excludeRecipes, $transferData) {
                    return collect($excludeRecipes)->each(function ($exRec) use ($data, $transferData) {
                        if ($data['recipie_id'] == $exRec['recipie_id']) {
                            $transferData->setGroup1($data);
                        } else {
                            $transferData->setGroup2($data);
                        }
                    });
                });
            })->values();

            if ($excludeRecipes->count() > 0) {
                $newArrayOfGroup = array_merge($transferData->getGroup1(), $transferData->getGroup2());
                $newItemGroupMenu = collect($newArrayOfGroup)->groupBy('group_id')->map(function ($item) {
                    return $item->first();
                })->values();
            } else {
                $newItemGroupMenu = collect($itemGroupMenu2)->map(function ($item) {
                    return $item->first();
                });
            }

            $transferData->listGroupMenu = $newItemGroupMenu;

            $itemMenu = @collect($menu->menuIngredientDetails)->map(function ($menuFirstGroup) use ($excludeMainIngredients, $excludeMainNotIngredients, $transferData) {
                $arr1 = collect(@$excludeMainNotIngredients)->pluck('ingredient_id')->toArray();
                $arr2 = DB::table('recipie_ingeredients')->where('recipie_id', $menuFirstGroup->recipie_carb_id)->pluck('ingredient_id')->toArray();
                $arr3 = DB::table('recipie_ingeredients')->where('recipie_id', $menuFirstGroup->recipie_protein_id)->pluck('ingredient_id')->toArray();
                $result = !empty(array_intersect($arr1, $arr2));
                $result2 = !empty(array_intersect($arr1, $arr3));

                if (!$result2) {
                    $transferData->setListIngredientId(@$menuFirstGroup->ingredient_id);
                    $transferData->setListIngredientName(@$menuFirstGroup->ingredient->name);
                    $transferData->setListProteinId(@$menuFirstGroup->recipie_protein_id);
                    $transferData->setListProteinName(@$menuFirstGroup->recipieProtein->name);
                    $transferData->setListProteinCuisineId(@$menuFirstGroup->cuisine_protein_id);
                    $transferData->setListProteinCuisineName(@$menuFirstGroup->cuisineProtein->name);
                }
                if (!$result) {
                    $transferData->setListCarbId(@$menuFirstGroup->recipie_carb_id);
                    $transferData->setListCarbName(@$menuFirstGroup->recipieCarb->name);
                    $transferData->setListCarbCuisineId(@$menuFirstGroup->cuisine_carb_id);
                    $transferData->setListCarbCuisineName(@$menuFirstGroup->cuisineCarb->name);
                }

                return [
                    'ingredient_id' => @$menuFirstGroup->ingredient_id,
                    'ingredient_name' => @$menuFirstGroup->ingredient->name,
                    'recipie_protein_id' => @$menuFirstGroup->recipie_protein_id,
                    'recipie_protein_name' => @$menuFirstGroup->recipieProtein->name,
                    'cuisine_protein_id' => @$menuFirstGroup->cuisine_protein_id,
                    'cuisine_protein_name' => @$menuFirstGroup->cuisineProtein->name,
                    'recipie_carb_id' => @$menuFirstGroup->recipie_carb_id,
                    'recipie_carb_name' => @$menuFirstGroup->recipieCarb->name,
                    'cuisine_carb_id' => @$menuFirstGroup->cuisine_carb_id,
                    'cuisine_carb_name' => @$menuFirstGroup->cuisineCarb->name,
                    'excluded' => @(collect(@$excludeMainIngredients)->pluck('ingredient_id'))->contains($menuFirstGroup->ingredient_id) || $result || $result2,
                ];
            })->filter(function ($excludeMainIngredients) {
                return $excludeMainIngredients['excluded'] != true;
            })->values();

            if ($itemMenu->count() >= $numberOfMeal) {
                while ($reserved < $numberOfMeal) {
                    foreach ($itemMenu as $item) {
                        if ($reserved < $numberOfMeal) {
                            $reservedList[] = $item;
                            $reserved++;
                        }
                    }
                }

                // test
                $reservedList = collect($reservedList)->filter(function ($list) use ($personalDesires) {
                    return $list != null;
                })->values()->map(function ($item) {
                    return [
                        'ingredient_id' => $item['ingredient_id'],
                        'ingredient_name' => $item['ingredient_name'],
                        'recipie_protein_id' => @$item['recipie_protein_id'],
                        'recipie_protein_name' => @$item['recipie_protein_name'],
                        'cuisine_protein_id' => @$item['cuisine_protein_id'],
                        'cuisine_protein_name' => @$item['cuisine_protein_name'],
                        'recipie_carb_id' => @$item['recipie_carb_id'],
                        'recipie_carb_name' => @$item['recipie_carb_name'],
                        'cuisine_carb_id' => @$item['cuisine_carb_id'],
                        'cuisine_carb_name' => @$item['cuisine_carb_name'],
                        'quantity' => 1,
                    ];
                })->groupBy('ingredient_id')->values()->map(function ($data) use ($personalDesires) {
                    return collect($data)->map(function ($newData) use ($data, $personalDesires) {
                        return [
                            'ingredient_id' => $newData['ingredient_id'],
                            'ingredient_name' => $newData['ingredient_name'],
                            'recipie_protein_id' => @$newData['recipie_protein_id'],
                            'recipie_protein_name' => @$newData['recipie_protein_name'],
                            'cuisine_protein_id' => @$newData['cuisine_protein_id'],
                            'cuisine_protein_name' => @$newData['cuisine_protein_name'],
                            'recipie_carb_id' => @$newData['recipie_carb_id'],
                            'recipie_carb_name' => @$newData['recipie_carb_name'],
                            'cuisine_carb_id' => @$newData['cuisine_carb_id'],
                            'cuisine_carb_name' => @$newData['cuisine_carb_name'],
                            'quantity' => $data->count(),
                            'carb' => $data->count() * @$personalDesires->carbohydrates,
                            'protein' => $data->count() * @$personalDesires->protein,
                        ];
                    })->first();
                });
            } else {

                if (!empty($transferData->getListCarbId()) && !empty($transferData->getListIngredientId())) {
                    while ($reserved < $numberOfMeal) {
                        if ($reserved < $numberOfMeal) {
                            $proteinIndex = array_rand($transferData->getListIngredientId());
                            $carbIndex = array_rand($transferData->getListCarbId());
                            $item['ingredient_id'] = $transferData->getListIngredientId()[$reserved] ?? $transferData->getListIngredientId()[$proteinIndex];
                            $item['ingredient_name'] = $transferData->getListIngredientName()[$reserved] ?? $transferData->getListIngredientName()[$proteinIndex];
                            $item['recipie_protein_id'] = $transferData->getListProteinId()[$reserved] ?? $transferData->getListProteinId()[$proteinIndex];
                            $item['recipie_protein_name'] = $transferData->getListProteinName()[$reserved] ?? $transferData->getListProteinName()[$proteinIndex];
                            $item['cuisine_protein_id'] = $transferData->getListProteinCuisineId()[$reserved] ?? $transferData->getListProteinCuisineId()[$proteinIndex];
                            $item['cuisine_protein_name'] = $transferData->getListProteinCuisineName()[$reserved] ?? $transferData->getListProteinCuisineName()[$proteinIndex];
                            $item['recipie_carb_id'] = $transferData->getListCarbId()[$reserved] ?? $transferData->getListCarbId()[$carbIndex];
                            $item['recipie_carb_name'] = $transferData->getListCarbName()[$reserved] ?? $transferData->getListCarbName()[$carbIndex];
                            $item['cuisine_carb_id'] = $transferData->getListCarbCuisineId()[$reserved] ?? $transferData->getListCarbCuisineId()[$carbIndex];
                            $item['cuisine_carb_name'] = $transferData->getListCarbCuisineName()[$reserved] ?? $transferData->getListCarbCuisineName()[$carbIndex];
                            $reservedList[] = $item;
                            $reserved++;
                        }
                    }

                    $reservedList = collect($reservedList)->filter(function ($list) use ($personalDesires) {
                        return $list != null;
                    })->values()->map(function ($item) use ($personalDesires) {
                        return [
                            'ingredient_id' => $item['ingredient_id'],
                            'ingredient_name' => $item['ingredient_name'],
                            'recipie_protein_id' => @$item['recipie_protein_id'],
                            'recipie_protein_name' => @$item['recipie_protein_name'],
                            'cuisine_protein_id' => @$item['cuisine_protein_id'],
                            'cuisine_protein_name' => @$item['cuisine_protein_name'],
                            'recipie_carb_id' => @$item['recipie_carb_id'],
                            'recipie_carb_name' => @$item['recipie_carb_name'],
                            'cuisine_carb_id' => @$item['cuisine_carb_id'],
                            'cuisine_carb_name' => @$item['cuisine_carb_name'],
                            'quantity' => 1,
                            'carb' => @$personalDesires->carbohydrates,
                            'protein' => @$personalDesires->protein,
                        ];
                    });
                } elseif (empty($transferData->getListCarbId()) && !empty($transferData->getListIngredientId())) {
                    while ($reserved < $numberOfMeal) {
                        if ($reserved < $numberOfMeal) {
                            $proteinIndex = array_rand($transferData->getListIngredientId());
                            $item['ingredient_id'] = $transferData->getListIngredientId()[$reserved] ?? $transferData->getListIngredientId()[$proteinIndex];
                            $item['ingredient_name'] = $transferData->getListIngredientName()[$reserved] ?? $transferData->getListIngredientName()[$proteinIndex];
                            $item['recipie_protein_id'] = $transferData->getListProteinId()[$reserved] ?? $transferData->getListProteinId()[$proteinIndex];
                            $item['recipie_protein_name'] = $transferData->getListProteinName()[$reserved] ?? $transferData->getListProteinName()[$proteinIndex];
                            $item['cuisine_protein_id'] = $transferData->getListProteinCuisineId()[$reserved] ?? $transferData->getListProteinCuisineId()[$proteinIndex];
                            $item['cuisine_protein_name'] = $transferData->getListProteinCuisineName()[$reserved] ?? $transferData->getListProteinCuisineName()[$proteinIndex];
                            $item['recipie_carb_id'] = 0;
                            $item['recipie_carb_name'] = 'تم تحويلها للمطبخ';
                            $item['cuisine_carb_id'] = 0;
                            $item['cuisine_carb_name'] = 'تم تحويلها للمطبخ';
                            $reservedList[] = $item;
                            $reserved++;
                        }
                    }

                    $reservedList = collect($reservedList)->filter(function ($list) use ($personalDesires) {
                        return $list != null;
                    })->values()->map(function ($item) use ($personalDesires) {
                        return [
                            'ingredient_id' => $item['ingredient_id'],
                            'ingredient_name' => $item['ingredient_name'],
                            'recipie_protein_id' => @$item['recipie_protein_id'],
                            'recipie_protein_name' => @$item['recipie_protein_name'],
                            'cuisine_protein_id' => @$item['cuisine_protein_id'],
                            'cuisine_protein_name' => @$item['cuisine_protein_name'],
                            'recipie_carb_id' => @$item['recipie_carb_id'],
                            'recipie_carb_name' => @$item['recipie_carb_name'],
                            'cuisine_carb_id' => @$item['cuisine_carb_id'],
                            'cuisine_carb_name' => @$item['cuisine_carb_name'],
                            'quantity' => 1,
                            'carb' => @$personalDesires->carbohydrates,
                            'protein' => @$personalDesires->protein,
                        ];
                    });
                } elseif (empty($transferData->getListIngredientId()) && !empty($transferData->getListCarbId())) {

                    while ($reserved < $numberOfMeal) {
                        if ($reserved < $numberOfMeal) {
                            $carbIndex = array_rand($transferData->getListCarbId());
                            $item['ingredient_id'] = 0;
                            $item['ingredient_name'] = 'تم تحويلها للمطبخ';
                            $item['recipie_protein_id'] = 0;
                            $item['recipie_protein_name'] = 'تم تحويلها للمطبخ';
                            $item['cuisine_protein_id'] = 0;
                            $item['cuisine_protein_name'] = 'تم تحويلها للمطبخ';
                            $item['recipie_carb_id'] = $transferData->getListCarbId()[$reserved] ?? $transferData->getListCarbId()[$carbIndex];
                            $item['recipie_carb_name'] = $transferData->getListCarbName()[$reserved] ?? $transferData->getListCarbName()[$carbIndex];
                            $item['cuisine_carb_id'] = $transferData->getListCarbCuisineId()[$reserved] ?? $transferData->getListCarbCuisineId()[$carbIndex];
                            $item['cuisine_carb_name'] = $transferData->getListCarbCuisineName()[$reserved] ?? $transferData->getListCarbCuisineName()[$carbIndex];
                            $reservedList[] = $item;
                            $reserved++;
                        }
                    }

                    $reservedList = collect($reservedList)->filter(function ($list) use ($personalDesires) {
                        return $list != null;
                    })->values()->map(function ($item) use ($personalDesires) {
                        return [
                            'ingredient_id' => $item['ingredient_id'],
                            'ingredient_name' => $item['ingredient_name'],
                            'recipie_protein_id' => @$item['recipie_protein_id'],
                            'recipie_protein_name' => @$item['recipie_protein_name'],
                            'cuisine_protein_id' => @$item['cuisine_protein_id'],
                            'cuisine_protein_name' => @$item['cuisine_protein_name'],
                            'recipie_carb_id' => @$item['recipie_carb_id'],
                            'recipie_carb_name' => @$item['recipie_carb_name'],
                            'cuisine_carb_id' => @$item['cuisine_carb_id'],
                            'cuisine_carb_name' => @$item['cuisine_carb_name'],
                            'quantity' => 1,
                            'carb' => @$personalDesires->carbohydrates,
                            'protein' => @$personalDesires->protein,
                        ];
                    });
                } elseif (empty($transferData->getListIngredientId()) && !empty($transferData->getListCarbId())) {
                    while ($reserved < $numberOfMeal) {
                        if ($reserved < $numberOfMeal) {
                            $item['ingredient_id'] = 0;
                            $item['ingredient_name'] = 'تم تحويلها للمطبخ';
                            $item['recipie_protein_id'] = 0;
                            $item['recipie_protein_name'] = 'تم تحويلها للمطبخ';
                            $item['cuisine_protein_id'] = 0;
                            $item['cuisine_protein_name'] = 'تم تحويلها للمطبخ';
                            $item['recipie_carb_id'] = 0;
                            $item['recipie_carb_name'] = 'تم تحويلها للمطبخ';
                            $item['cuisine_carb_id'] = 0;
                            $item['cuisine_carb_name'] = 'تم تحويلها للمطبخ';
                            $reservedList[] = $item;
                            $reserved++;
                        }
                    }

                    $reservedList = collect($reservedList)->filter(function ($list) use ($personalDesires) {
                        return $list != null;
                    })->values()->map(function ($item) use ($personalDesires) {
                        return [
                            'ingredient_id' => $item['ingredient_id'],
                            'ingredient_name' => $item['ingredient_name'],
                            'recipie_protein_id' => @$item['recipie_protein_id'],
                            'recipie_protein_name' => @$item['recipie_protein_name'],
                            'cuisine_protein_id' => @$item['cuisine_protein_id'],
                            'cuisine_protein_name' => @$item['cuisine_protein_name'],
                            'recipie_carb_id' => @$item['recipie_carb_id'],
                            'recipie_carb_name' => @$item['recipie_carb_name'],
                            'cuisine_carb_id' => @$item['cuisine_carb_id'],
                            'cuisine_carb_name' => @$item['cuisine_carb_name'],
                            'quantity' => 1,
                            'carb' => @$personalDesires->carbohydrates,
                            'protein' => @$personalDesires->protein,
                        ];
                    });
                }

            }
            return [
                'customer' => [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'mobile' => $customer->mobile ?? '-',
                ],
                'package' => [
                    'id' => $package->id ?? '-',
                    'name' => $package->name ?? '-',
                    'cost' => $package->cost ?? '-',
                    'price' => $package->price ?? '-',
                    'number_of_days' => $package->number_of_days ?? '-',
                    'number_of_meals' => $package->number_of_meals ?? '-',
                    'days' => $package->packageDay->pluck('day') ?? '-'
                ],
                'subscribe' => [
                    'start_date' => Carbon::parse($details->subscription->start_date)->format('Y-m-d'),
                    'end_date' => Carbon::parse($details->subscription->end_date)->format('Y-m-d'),
                ],
                'group_subscription' => collect($groupSubscription)->map(function ($groupSubscription) {
                    return [
                        'group_id' => $groupSubscription->group_id,
                        'group_name' => $groupSubscription->group->name,
                        'quantity' => $groupSubscription->quantity,
                    ];
                }),
                'exclude_main_ingredients' => collect($excludeMainIngredients)->map(function ($excludeMainIngredient) {
                    return [
                        'id' => $excludeMainIngredient->ingredient_id,
                        'name' => $excludeMainIngredient->ingredient->name,
                    ];
                }),
                'personal_desires' => [
                    'notes' => @$personalDesires->notes ?? '-',
                    'exclude_not_main_ingredients' => collect($excludeMainNotIngredients)->map(function ($excludeMainNotIngredient) {
                        return $excludeMainNotIngredient->ingredient->name;
                    }),
                    'recipes' => collect($excludeRecipes)->map(function ($excludeRecipe) {
                        return $excludeRecipe->recipie->name;
                    }),
                ],
                'carb' => @$personalDesires->carbohydrates,
                'protein' => @$personalDesires->protein,
                'menu' => @collect($menu->menuIngredientDetails)->map(function ($menuFirstGroup) use ($excludeMainIngredients, $excludeMainNotIngredients) {
                    $arr1 = collect(@$excludeMainNotIngredients)->pluck('ingredient_id')->toArray();
                    $arr2 = DB::table('recipie_ingeredients')->where('recipie_id', $menuFirstGroup->recipie_carb_id)->pluck('ingredient_id')->toArray();
                    $arr3 = DB::table('recipie_ingeredients')->where('recipie_id', $menuFirstGroup->recipie_protein_id)->pluck('ingredient_id')->toArray();
                    $result = !empty(array_intersect($arr1, $arr2));
                    $result2 = !empty(array_intersect($arr1, $arr3));
                    return [
                        'ingredient_id' => @$menuFirstGroup->ingredient_id,
                        'ingredient_name' => @$menuFirstGroup->ingredient->name,
                        'recipie_protein_id' => @$menuFirstGroup->recipie_protein_id,
                        'recipie_protein_name' => @$menuFirstGroup->recipieProtein->name,
                        'cuisine_protein_id' => @$menuFirstGroup->cuisine_protein_id,
                        'cuisine_protein_name' => @$menuFirstGroup->cuisineProtein->name,
                        'recipie_carb_id' => @$menuFirstGroup->recipie_carb_id,
                        'recipie_carb_name' => @$menuFirstGroup->recipieCarb->name,
                        'cuisine_carb_id' => @$menuFirstGroup->cuisine_carb_id,
                        'cuisine_carb_name' => @$menuFirstGroup->cuisineCarb->name,
                        'excluded' => (collect($excludeMainIngredients)->pluck('ingredient_id'))->contains($menuFirstGroup->ingredient_id) || $result || $result2,
                    ];
                }),
                'menu_selected' =>
                    [
                        'item' => $reservedList,
                        'reserved' => $reserved
                    ],
                'group_menu' => $transferData->listGroupMenu,
                'deliveries' => [
                    'city' => @$deliveries->city->name ?? '-',
                    'delegate_name' => @$deliveries->city->delegate_name ?? '-',
                    'branch' => @$deliveries->branch->name ?? '-',
                    'home_address' => @$deliveries->home_address ?? '-',
                    'home_number' => @$deliveries->home_number ?? '-',
                    'period' => @$deliveries->periodRelation->name ?? '-',
                    'notes' => @$deliveries->notes ?? '-',
                ],
                'keto' => @$personalDesires->keto ?? '-',
                'standard' => @$personalDesires->standard ?? '-',
                'active' => $details->subscription->active ?? '-'
            ];
        });

        return Response::success(200, $data)->withMessage('get successfully data')->send();
    }

    public function reportQuantities()
    {
        $subscriptionDetails = SubscriptionDetail::
        query()->with('subscription'
        )->whereDate('subscription_dates', request('date'));

        $activeSub = collect($subscriptionDetails->get())->filter(function ($sub) {
            return $sub->subscription->is_active && $sub->subscription->check_is_ended;
        })->count();

        $stopSub = collect($subscriptionDetails->get())->map(function ($sub) {
            return StopSubscription::query()->where('subscription_id', $sub->subscription_id)->latest()->first();
        })->filter(function ($list) {
            return $list != null && $list->is_active;
        })->count();

        // result with and without spicy
        $result = collect($subscriptionDetails->get())->map(function ($details) {
            return $this->processQuantity($details)['list1'];
        });

        $newResProtein = collect($result)->flatMap(function ($list) {
            return $list;
        })->groupBy('recipie_protein_id')->values()->map(function ($q) {
            return $this->processRecipeProtein($q);
        });

        $newResCarb = collect($result)->flatMap(function ($list) {
            return $list;
        })->groupBy('recipie_carb_id')->values()->map(function ($q) {
            return $this->processRecipeCarb($q);
        });

        // result with spicy
        $resultWithSpicy = collect($subscriptionDetails->get())->filter(function ($details) {
            return $details->subscription->check_is_spicy;
        })->map(function ($details) {
            return $this->processQuantity($details)['list1'];
        });

        $newResProteinWithSpicy = collect($resultWithSpicy)->flatMap(function ($list) {
            return $list;
        })->groupBy('recipie_protein_id')->values()->map(function ($q) {
            return $this->processRecipeProtein($q);
        });

        $newResCarbWithSpicy = collect($resultWithSpicy)->flatMap(function ($list) {
            return $list;
        })->groupBy('recipie_carb_id')->values()->map(function ($q) {
            return $this->processRecipeCarb($q);
        });


        // result without spicy
        $resultWithoutSpicy = collect($subscriptionDetails->get())->filter(function ($details) {
            return $details->subscription->is_spicy == 0;
        })->map(function ($details) {
            return $this->processQuantity($details)['list1'];
        });

        $newResProteinWithoutSpicy = collect($resultWithoutSpicy)->flatMap(function ($list) {
            return $list;
        })->groupBy('recipie_protein_id')->values()->map(function ($q) {
            return $this->processRecipeProtein($q);
        });

        $newResCarbWithoutSpicy = collect($resultWithoutSpicy)->flatMap(function ($list) {
            return $list;
        })->groupBy('recipie_carb_id')->values()->map(function ($q) {
            return $this->processRecipeCarb($q);
        });

        // result with and without spicy
        $resultGroup = collect($subscriptionDetails->get())->flatMap(function ($details) {
            return $this->processQuantity($details)['list2'];
        })->groupBy('group_id')->map(function ($list) {
            $count = $list->count();
            $item = $list->first();
            return [
                'count' => $count,
                'id' => $item['group_id'],
                'name' => $item['group_name'],
                'recipie_id' => $item['recipie_id'],
                'recipie_name' => $item['recipie_name'],
            ];
        })->values();

        // result with and without spicy
        $listPackages = collect($subscriptionDetails->get())->flatMap(function ($details) {
            return $this->processQuantity($details)['package'];
        })->groupBy('id')->map(function ($list) {
            $count = $list->count();
            $item = $list->first();
            return [
                'count' => $count,
                'name' => $item['name'],
            ];
        })->values();

        $data = [
            'subscribe' => [
                'day' => $subscriptionDetails->count(),
                'active' => $activeSub,
                'stop' => $stopSub,
                'meal_protein' => $newResProtein,
                'meal_carb' => $newResCarb,
                'meal_protein_with_spicy' => $newResProteinWithSpicy,
                'meal_carb_with_spicy' => $newResCarbWithSpicy,
                'meal_protein_without_spicy' => $newResProteinWithoutSpicy,
                'meal_carb_without_spicy' => $newResCarbWithoutSpicy,
                'group' => $resultGroup,
                'list_package' => $listPackages
            ]
        ];

        return Response::success(200, $data)->withMessage('get successfully data')->send();
    }


    public function processQuantity($details)
    {
        $customer = Customer::query()->where('id', $details->subscription->customer_id)->first();
        $package = Package::query()->where('id', $details->subscription->package_id)->first();
        $groupSubscription = GroupSubscription::query()->where('subscription_id', $details['subscription_id'])->get();
        $excludeMainIngredients = ExcludeIngredient::query()->where('subscription_id', $details['subscription_id'])->get();
        $excludeMainNotIngredients = ExcludeNotIngredient::query()->where('subscription_id', $details['subscription_id'])->get();
        @$personalDesires = PersonalDesires::query()->where('subscription_id', $details['subscription_id'])->first();
        $menu = Menu::query()->whereDate('cooking_date', request('date'))->first();
        $deliveries = Delivery::query()->where('subscription_id', $details['subscription_id'])->first();
        $excludeRecipes = ExcludeRecipie::query()->where('subscription_id', $details['subscription_id'])->get();

        $numberOfMeal = $package->number_of_meals;
        $reserved = 0;
        $reservedList[] = null;

        $transferData = new SubscribeDataTransfer();
        $transferData->setSubscribeId($details['subscription_id']);
        $transferData->setListPackage($package);

        $itemGroupMenu = @collect($menu->menuGroupDetails)->map(function ($menuFirstGroup) use ($excludeMainNotIngredients, $transferData) {
            $arr1 = collect(@$excludeMainNotIngredients)->pluck('ingredient_id')->toArray();
            $arr2 = DB::table('recipie_ingeredients')->where('recipie_id', $menuFirstGroup->recipie_id)->pluck('ingredient_id')->toArray();
            $result = !empty(array_intersect($arr1, $arr2));


            return [
                'id' => @$menuFirstGroup->id,
                'recipie_id' => @$menuFirstGroup->recipie_id,
                'recipie_name' => @$menuFirstGroup->recipie->name,
                'group_id' => @$menuFirstGroup->group_id,
                'group_name' => @$menuFirstGroup->group->name,
                'cuisine_id' => @$menuFirstGroup->cuisine_id,
                'cuisine_name' => @$menuFirstGroup->cuisine->name,
                'excluded' => $result,
            ];
        })->filter(function ($exclude) {
            return $exclude['excluded'] != true;
        })->values();

        $newItemGroupMenu = collect($itemGroupMenu)->map(function ($itemGroup) use ($groupSubscription, $transferData) {
            $itemGroupSub = @(collect(@$groupSubscription)->pluck('group_id'))->contains($itemGroup['group_id']);
            if ($itemGroupSub) {
                return [
                    'id' => @$itemGroup['id'],
                    'recipie_id' => @$itemGroup['recipie_id'],
                    'recipie_name' => @$itemGroup['recipie_name'],
                    'group_id' => @$itemGroup['group_id'],
                    'group_name' => @$itemGroup['group_name'],
                    'cuisine_id' => @$itemGroup['cuisine_id'],
                    'cuisine_name' => @$itemGroup['cuisine_name'],
                    'quantity' => GroupSubscription::query()->where('subscription_id', $transferData->getSubscribeId())
                        ->where('group_id', @$itemGroup['group_id'])->first()->quantity,
                    'excluded' => @$itemGroup['excluded'],
                ];
            }
        })->filter(function ($list) {
            return $list != null;
        })->groupBy('group_id')->values();

        $itemGroupMenu2 = collect($newItemGroupMenu)->each(function ($item) use ($excludeRecipes, $transferData) {
            return collect($item)->each(function ($data) use ($excludeRecipes, $transferData) {
                return collect($excludeRecipes)->each(function ($exRec) use ($data, $transferData) {
                    if ($data['recipie_id'] == $exRec['recipie_id']) {
                        $transferData->setGroup1($data);
                    } else {
                        $transferData->setGroup2($data);
                    }
                });
            });
        })->values();

        if ($excludeRecipes->count() > 0) {
            $newArrayOfGroup = array_merge($transferData->getGroup1(), $transferData->getGroup2());
            $newItemGroupMenu = collect($newArrayOfGroup)->groupBy('group_id')->map(function ($item) {
                return $item->first();
            })->values();
        } else {
            $newItemGroupMenu = collect($itemGroupMenu2)->map(function ($item) {
                return $item->first();
            });
        }

        $transferData->listGroupMenu = $newItemGroupMenu;

        $itemMenu = @collect($menu->menuIngredientDetails)->map(function ($menuFirstGroup) use ($excludeMainIngredients, $excludeMainNotIngredients, $transferData) {
            $arr1 = collect(@$excludeMainNotIngredients)->pluck('ingredient_id')->toArray();
            $arr2 = DB::table('recipie_ingeredients')->where('recipie_id', $menuFirstGroup->recipie_carb_id)->pluck('ingredient_id')->toArray();
            $arr3 = DB::table('recipie_ingeredients')->where('recipie_id', $menuFirstGroup->recipie_protein_id)->pluck('ingredient_id')->toArray();
            $result = !empty(array_intersect($arr1, $arr2));
            $result2 = !empty(array_intersect($arr1, $arr3));

            if (!$result2) {
                $transferData->setListIngredientId(@$menuFirstGroup->ingredient_id);
                $transferData->setListIngredientName(@$menuFirstGroup->ingredient->name);
                $transferData->setListProteinId(@$menuFirstGroup->recipie_protein_id);
                $transferData->setListProteinName(@$menuFirstGroup->recipieProtein->name);
                $transferData->setListProteinCuisineId(@$menuFirstGroup->cuisine_protein_id);
                $transferData->setListProteinCuisineName(@$menuFirstGroup->cuisineProtein->name);
            }
            if (!$result) {
                $transferData->setListCarbId(@$menuFirstGroup->recipie_carb_id);
                $transferData->setListCarbName(@$menuFirstGroup->recipieCarb->name);
                $transferData->setListCarbCuisineId(@$menuFirstGroup->cuisine_carb_id);
                $transferData->setListCarbCuisineName(@$menuFirstGroup->cuisineCarb->name);
            }

            return [
                'ingredient_id' => @$menuFirstGroup->ingredient_id,
                'ingredient_name' => @$menuFirstGroup->ingredient->name,
                'recipie_protein_id' => @$menuFirstGroup->recipie_protein_id,
                'recipie_protein_name' => @$menuFirstGroup->recipieProtein->name,
                'cuisine_protein_id' => @$menuFirstGroup->cuisine_protein_id,
                'cuisine_protein_name' => @$menuFirstGroup->cuisineProtein->name,
                'recipie_carb_id' => @$menuFirstGroup->recipie_carb_id,
                'recipie_carb_name' => @$menuFirstGroup->recipieCarb->name,
                'cuisine_carb_id' => @$menuFirstGroup->cuisine_carb_id,
                'cuisine_carb_name' => @$menuFirstGroup->cuisineCarb->name,
                'excluded' => @(collect(@$excludeMainIngredients)->pluck('ingredient_id'))->contains($menuFirstGroup->ingredient_id) || $result || $result2,
            ];
        })->filter(function ($excludeMainIngredients) {
            return $excludeMainIngredients['excluded'] != true;
        })->values();


        if ($itemMenu->count() >= $numberOfMeal) {
            while ($reserved < $numberOfMeal) {
                foreach ($itemMenu as $item) {
                    if ($reserved < $numberOfMeal) {
                        $reservedList[] = $item;
                        $reserved++;
                    }
                }
            }

            // test
            $reservedList = collect($reservedList)->filter(function ($list) use ($personalDesires) {
                return $list != null;
            })->values()->map(function ($item) {
                return [
                    'ingredient_id' => $item['ingredient_id'],
                    'ingredient_name' => $item['ingredient_name'],
                    'recipie_protein_id' => @$item['recipie_protein_id'],
                    'recipie_protein_name' => @$item['recipie_protein_name'],
                    'cuisine_protein_id' => @$item['cuisine_protein_id'],
                    'cuisine_protein_name' => @$item['cuisine_protein_name'],
                    'recipie_carb_id' => @$item['recipie_carb_id'],
                    'recipie_carb_name' => @$item['recipie_carb_name'],
                    'cuisine_carb_id' => @$item['cuisine_carb_id'],
                    'cuisine_carb_name' => @$item['cuisine_carb_name'],
                    'quantity' => 1,
                ];
            })->groupBy('ingredient_id')->values()->map(function ($data) use ($personalDesires) {
                return collect($data)->map(function ($newData) use ($data, $personalDesires) {
                    return [
                        'ingredient_id' => $newData['ingredient_id'],
                        'ingredient_name' => $newData['ingredient_name'],
                        'recipie_protein_id' => @$newData['recipie_protein_id'],
                        'recipie_protein_name' => @$newData['recipie_protein_name'],
                        'cuisine_protein_id' => @$newData['cuisine_protein_id'],
                        'cuisine_protein_name' => @$newData['cuisine_protein_name'],
                        'recipie_carb_id' => @$newData['recipie_carb_id'],
                        'recipie_carb_name' => @$newData['recipie_carb_name'],
                        'cuisine_carb_id' => @$newData['cuisine_carb_id'],
                        'cuisine_carb_name' => @$newData['cuisine_carb_name'],
                        'quantity' => $data->count(),
                        'carb' => $data->count() * @$personalDesires->carbohydrates,
                        'protein' => $data->count() * @$personalDesires->protein,
                    ];
                })->first();
            });
        } else {

            if (!empty($transferData->getListCarbId()) && !empty($transferData->getListIngredientId())) {
                while ($reserved < $numberOfMeal) {
                    if ($reserved < $numberOfMeal) {
                        $proteinIndex = array_rand($transferData->getListIngredientId());
                        $carbIndex = array_rand($transferData->getListCarbId());
                        $item['ingredient_id'] = $transferData->getListIngredientId()[$reserved] ?? $transferData->getListIngredientId()[$proteinIndex];
                        $item['ingredient_name'] = $transferData->getListIngredientName()[$reserved] ?? $transferData->getListIngredientName()[$proteinIndex];
                        $item['recipie_protein_id'] = $transferData->getListProteinId()[$reserved] ?? $transferData->getListProteinId()[$proteinIndex];
                        $item['recipie_protein_name'] = $transferData->getListProteinName()[$reserved] ?? $transferData->getListProteinName()[$proteinIndex];
                        $item['cuisine_protein_id'] = $transferData->getListProteinCuisineId()[$reserved] ?? $transferData->getListProteinCuisineId()[$proteinIndex];
                        $item['cuisine_protein_name'] = $transferData->getListProteinCuisineName()[$reserved] ?? $transferData->getListProteinCuisineName()[$proteinIndex];
                        $item['recipie_carb_id'] = $transferData->getListCarbId()[$reserved] ?? $transferData->getListCarbId()[$carbIndex];
                        $item['recipie_carb_name'] = $transferData->getListCarbName()[$reserved] ?? $transferData->getListCarbName()[$carbIndex];
                        $item['cuisine_carb_id'] = $transferData->getListCarbCuisineId()[$reserved] ?? $transferData->getListCarbCuisineId()[$carbIndex];
                        $item['cuisine_carb_name'] = $transferData->getListCarbCuisineName()[$reserved] ?? $transferData->getListCarbCuisineName()[$carbIndex];
                        $reservedList[] = $item;
                        $reserved++;
                    }
                }

                $reservedList = collect($reservedList)->filter(function ($list) use ($personalDesires) {
                    return $list != null;
                })->values()->map(function ($item) use ($personalDesires) {
                    return [
                        'ingredient_id' => $item['ingredient_id'],
                        'ingredient_name' => $item['ingredient_name'],
                        'recipie_protein_id' => @$item['recipie_protein_id'],
                        'recipie_protein_name' => @$item['recipie_protein_name'],
                        'cuisine_protein_id' => @$item['cuisine_protein_id'],
                        'cuisine_protein_name' => @$item['cuisine_protein_name'],
                        'recipie_carb_id' => @$item['recipie_carb_id'],
                        'recipie_carb_name' => @$item['recipie_carb_name'],
                        'cuisine_carb_id' => @$item['cuisine_carb_id'],
                        'cuisine_carb_name' => @$item['cuisine_carb_name'],
                        'quantity' => 1,
                        'carb' => @$personalDesires->carbohydrates,
                        'protein' => @$personalDesires->protein,
                    ];
                });
            } elseif (empty($transferData->getListCarbId()) || !empty($transferData->getListIngredientId())) {
                while ($reserved < $numberOfMeal) {
                    if ($reserved < $numberOfMeal) {
                        $proteinIndex = array_rand($transferData->getListIngredientId());
                        $item['ingredient_id'] = $transferData->getListIngredientId()[$reserved] ?? $transferData->getListIngredientId()[$proteinIndex];
                        $item['ingredient_name'] = $transferData->getListIngredientName()[$reserved] ?? $transferData->getListIngredientName()[$proteinIndex];
                        $item['recipie_protein_id'] = $transferData->getListProteinId()[$reserved] ?? $transferData->getListProteinId()[$proteinIndex];
                        $item['recipie_protein_name'] = $transferData->getListProteinName()[$reserved] ?? $transferData->getListProteinName()[$proteinIndex];
                        $item['cuisine_protein_id'] = $transferData->getListProteinCuisineId()[$reserved] ?? $transferData->getListProteinCuisineId()[$proteinIndex];
                        $item['cuisine_protein_name'] = $transferData->getListProteinCuisineName()[$reserved] ?? $transferData->getListProteinCuisineName()[$proteinIndex];
                        $item['recipie_carb_id'] = 0;
                        $item['recipie_carb_name'] = 'تم تحويلها للمطبخ';
                        $item['cuisine_carb_id'] = 0;
                        $item['cuisine_carb_name'] = 'تم تحويلها للمطبخ';
                        $reservedList[] = $item;
                        $reserved++;
                    }
                }

                $reservedList = collect($reservedList)->filter(function ($list) use ($personalDesires) {
                    return $list != null;
                })->values()->map(function ($item) use ($personalDesires) {
                    return [
                        'ingredient_id' => $item['ingredient_id'],
                        'ingredient_name' => $item['ingredient_name'],
                        'recipie_protein_id' => @$item['recipie_protein_id'],
                        'recipie_protein_name' => @$item['recipie_protein_name'],
                        'cuisine_protein_id' => @$item['cuisine_protein_id'],
                        'cuisine_protein_name' => @$item['cuisine_protein_name'],
                        'recipie_carb_id' => @$item['recipie_carb_id'],
                        'recipie_carb_name' => @$item['recipie_carb_name'],
                        'cuisine_carb_id' => @$item['cuisine_carb_id'],
                        'cuisine_carb_name' => @$item['cuisine_carb_name'],
                        'quantity' => 1,
                        'carb' => @$personalDesires->carbohydrates,
                        'protein' => @$personalDesires->protein,
                    ];
                });
            } elseif (empty($transferData->getListIngredientId()) || !empty($transferData->getListCarbId())) {

                while ($reserved < $numberOfMeal) {
                    if ($reserved < $numberOfMeal) {
                        $carbIndex = array_rand($transferData->getListCarbId());
                        $item['ingredient_id'] = 0;
                        $item['ingredient_name'] = 'تم تحويلها للمطبخ';
                        $item['recipie_protein_id'] = 0;
                        $item['recipie_protein_name'] = 'تم تحويلها للمطبخ';
                        $item['cuisine_protein_id'] = 0;
                        $item['cuisine_protein_name'] = 'تم تحويلها للمطبخ';
                        $item['recipie_carb_id'] = $transferData->getListCarbId()[$reserved] ?? $transferData->getListCarbId()[$carbIndex];
                        $item['recipie_carb_name'] = $transferData->getListCarbName()[$reserved] ?? $transferData->getListCarbName()[$carbIndex];
                        $item['cuisine_carb_id'] = $transferData->getListCarbCuisineId()[$reserved] ?? $transferData->getListCarbCuisineId()[$carbIndex];
                        $item['cuisine_carb_name'] = $transferData->getListCarbCuisineName()[$reserved] ?? $transferData->getListCarbCuisineName()[$carbIndex];
                        $reservedList[] = $item;
                        $reserved++;
                    }
                }

                $reservedList = collect($reservedList)->filter(function ($list) use ($personalDesires) {
                    return $list != null;
                })->values()->map(function ($item) use ($personalDesires) {
                    return [
                        'ingredient_id' => $item['ingredient_id'],
                        'ingredient_name' => $item['ingredient_name'],
                        'recipie_protein_id' => @$item['recipie_protein_id'],
                        'recipie_protein_name' => @$item['recipie_protein_name'],
                        'cuisine_protein_id' => @$item['cuisine_protein_id'],
                        'cuisine_protein_name' => @$item['cuisine_protein_name'],
                        'recipie_carb_id' => @$item['recipie_carb_id'],
                        'recipie_carb_name' => @$item['recipie_carb_name'],
                        'cuisine_carb_id' => @$item['cuisine_carb_id'],
                        'cuisine_carb_name' => @$item['cuisine_carb_name'],
                        'quantity' => 1,
                        'carb' => @$personalDesires->carbohydrates,
                        'protein' => @$personalDesires->protein,
                    ];
                });
            } elseif (empty($transferData->getListIngredientId()) || !empty($transferData->getListCarbId())) {
                while ($reserved < $numberOfMeal) {
                    if ($reserved < $numberOfMeal) {
                        $item['ingredient_id'] = 0;
                        $item['ingredient_name'] = 'تم تحويلها للمطبخ';
                        $item['recipie_protein_id'] = 0;
                        $item['recipie_protein_name'] = 'تم تحويلها للمطبخ';
                        $item['cuisine_protein_id'] = 0;
                        $item['cuisine_protein_name'] = 'تم تحويلها للمطبخ';
                        $item['recipie_carb_id'] = 0;
                        $item['recipie_carb_name'] = 'تم تحويلها للمطبخ';
                        $item['cuisine_carb_id'] = 0;
                        $item['cuisine_carb_name'] = 'تم تحويلها للمطبخ';
                        $reservedList[] = $item;
                        $reserved++;
                    }
                }

                $reservedList = collect($reservedList)->filter(function ($list) use ($personalDesires) {
                    return $list != null;
                })->values()->map(function ($item) use ($personalDesires) {
                    return [
                        'ingredient_id' => $item['ingredient_id'],
                        'ingredient_name' => $item['ingredient_name'],
                        'recipie_protein_id' => @$item['recipie_protein_id'],
                        'recipie_protein_name' => @$item['recipie_protein_name'],
                        'cuisine_protein_id' => @$item['cuisine_protein_id'],
                        'cuisine_protein_name' => @$item['cuisine_protein_name'],
                        'recipie_carb_id' => @$item['recipie_carb_id'],
                        'recipie_carb_name' => @$item['recipie_carb_name'],
                        'cuisine_carb_id' => @$item['cuisine_carb_id'],
                        'cuisine_carb_name' => @$item['cuisine_carb_name'],
                        'quantity' => 1,
                        'carb' => @$personalDesires->carbohydrates,
                        'protein' => @$personalDesires->protein,
                    ];
                });
            }

        }
        return [
            'list1' => $reservedList,
            'list2' => $transferData->listGroupMenu,
            'package' => $transferData->listPackage,
        ];
    }

    public function processRecipeProtein($q)
    {
        $count = $q->count();
        $p = $q->sum('protein');
        $c = $q->sum('carb');
        return collect($q)->map(function ($q2) use ($count, $p, $c) {
            return [
                'count' => $count,
                'ingredient_name' => $q2['ingredient_name'],
                'recipie_protein_id' => $q2['recipie_protein_id'],
                'recipie_protein_name' => $q2['recipie_protein_name'],
                'sum_protein' => $p,
                'sum_carb' => $c,
            ];
        })->first();
    }

    public function processRecipeCarb($q)
    {
        $count = $q->count();
        $p = $q->sum('protein');
        $c = $q->sum('carb');
        return collect($q)->map(function ($q2) use ($count, $p, $c) {
            return [
                'count' => $count,
                'ingredient_name' => $q2['ingredient_name'],
                'recipie_carb_id' => $q2['recipie_carb_id'],
                'recipie_carb_name' => $q2['recipie_carb_name'],
            ];
        })->first();
    }

    public function reportDeliveries()
    {
        $subscriptionDetails = SubscriptionDetail::query()->with(['subscription'])->whereDate('subscription_dates', request('date'))->get();

        $data = collect($subscriptionDetails)->map(function ($details) {
            $customer = Customer::query()->where('id', $details->subscription->customer_id)->first();
            $deliveries = Delivery::query()->where('subscription_id', $details['subscription_id'])->first();

            return [
                'customer' => [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'mobile' => $customer->mobile,
                ],
                'deliveries' => [
                    'city' => @$deliveries->city->name,
                    'delegate_name' => @$deliveries->city->delegate_name,
                    'branch' => @$deliveries->branch->name,
                    'home_address' => @$deliveries->home_address,
                    'home_number' => @$deliveries->home_number,
                    'period' => @$deliveries->periodRelation->name,
                    'notes' => @$deliveries->notes,
                ]
            ];
        });
        return Response::success(200, $data)->withMessage('get successfully data')->send();
    }

    public function generateCookingToday()
    {
        $subscriptionDetails = SubscriptionDetail::query()->whereDate('subscription_dates', now())->get();
        App::setLocale('ar');
        $data = collect($subscriptionDetails)->map(function ($details) {
            $customer = Customer::query()->where('id', $details->subscription->customer_id)->firstOrFail();
            $protein = PersonalDesires::query()->where('subscription_id', $details['subscription_id'])->firstOrFail();
            $deliveries = Delivery::query()->where('subscription_id', $details['subscription_id'])->firstOrFail();
            $packageCustomerSubscription = PackageCustomerSubscription::query()->where('subscription_id', $details['subscription_id'])->get();
            $excludeRecipes = ExcludeRecipie::query()->where('subscription_id', $details['subscription_id'])->get();
            $excludeIngredients = ExcludeIngredient::query()->where('subscription_id', $details['subscription_id'])->get();
            return [
                'customer_name' => $customer->name,
                'protein' => $protein->protein,
                'carbohydrates' => $protein->carbohydrates,
                'details_recipies' => collect($packageCustomerSubscription)->map(function ($packageCustomer) {
                    return [
                        'group_id' => $packageCustomer['group_id'],
                        'quantity' => $packageCustomer['quantity'],
                        'recipe_name' => Kitchen::query()->whereDate('cooking_date', now())->exists() ? Kitchen::query()->whereDate('cooking_date', now())->firstOrFail()->kithenDetails->where('group_id', $packageCustomer['group_id'])->first()->recipie->name : '',
                    ];
                }),
                'note_recipe' => collect($excludeRecipes)->map(function ($recipes) {
                    return $recipes->recipie->name;
                }),
                'note_ingredients' => collect($excludeIngredients)->map(function ($ingredient) {
                    return $ingredient->ingredient->name;
                }),
                'package_name' => $details->subscription->package->name,
                'delivery' => 'مندوب',
                'period' => $deliveries->periodRelation->name,
                'side' => $deliveries->city->name,
                'area' => $deliveries->branch->name,
            ];
        });
        // return view('cooking.cooking_today',['data'=>$data]);
        $pdf = PDF::loadView('cooking.cooking_today', ['data' => $data], Config::get('configPDF'));
        return $pdf->stream('الطبخ اليومي.pdf');
    }

    public function findCustomer()
    {
        try {
            $customer = Customer::query()->where('mobile', request('mobile'))->first();
            return Response::success(200, (new SubscribeResource($customer))->customerResource())->send();
        } catch (\Exception $exception) {
            return Response::success(404)->withMessage('not found customer')->send();
        }
    }

    public function stopSub($subscribeId)
    {
        StopSubscription::query()->create([
            'subscription_id' => $subscribeId,
            'start_date' => request('start_date'),
            'end_date' => @request('end_date'),
        ]);


//        Subscription::query()->where('id', $subscribeId)->update(['is_ended' => 0]);

        return Response::success(201)->withMessage('created successfully')->send();
    }

    public function startSub($subscribeId)
    {
        Subscription::query()->where('id', $subscribeId)->update(['is_ended' => 1]);

        return Response::success(201)->withMessage('created successfully')->send();
    }

    public function givSub($subscribeId)
    {
        $stopSub = StopSubscription::query()->where('subscription_id', $subscribeId)->latest('id')->first();
        $subscribeDays = SubscriptionDay::query()->where('subscription_id', $subscribeId)->pluck('day');

        $day_list[] = collect($subscribeDays)->map(function ($day) {
            return $day;
        })->all();


        $days_number = SubscriptionDetail::query()
            ->where('subscription_id', $subscribeId)
            ->whereDate('subscription_dates', '>=', $stopSub->start_date)
            ->count();

        SubscriptionDetail::query()
            ->where('subscription_id', $subscribeId)
            ->delete();

//        $start_date = Carbon::now()->addDay()->format('Y-m-d');
        $start_date = Carbon::parse(request('date'))->format('Y-m-d');
        $saved_days = 0;
        $saved_days_list = [];

        do {
            $week_day = Carbon::parse($start_date)->dayOfWeek;
            if (in_array($week_day, $day_list[0])) {
                $saved_days++;
                $saved_days_list[] = $start_date;
            }
            $start_date = Carbon::parse($start_date)->addDay();
        } while ($saved_days < $days_number);

        $validDate = collect($saved_days_list)->map(function ($validDate) {
            return Carbon::parse($validDate)->format('Y-m-d');
        })->all();

        GiveSubscription::query()->create([
            'subscription_id' => $subscribeId,
            'number_of_days' => $days_number,
        ]);

        StopSubscription::query()->where('subscription_id', $subscribeId)->delete();

        Subscription::query()->where('id', $subscribeId)->update([
            'end_date' => last($validDate),
            'is_ended' => 1
        ]);

        collect($validDate)->each(function ($date) use ($subscribeId) {
            SubscriptionDetail::query()->create([
                'subscription_id' => $subscribeId,
                'subscription_dates' => $date,
                'status' => 1
            ]);
        });

        return Response::success(201)->withMessage('created successfully')->send();
    }

    public function listPeriods()
    {
        $items = PeriodResource::collection(Period::query()->get())->map->listPeriods();
        return Response::success(200, $items)->send();
    }

    public function listBankNames()
    {
        $items = BankNameResource::collection(BankName::query()->get())->map->listBankNames();
        return Response::success(200, $items)->send();
    }

    public function listCompanies()
    {
        $items = CompanyResource::collection(Company::query()->get())->map->listCompany();
        return Response::success(200, $items)->send();
    }

    public function listGroupNames()
    {
        $items = GroupNameResource::collection(GroupName::query()->get())->map->listGroupNames();
        return Response::success(200, $items)->send();
    }


    public function getSubscribe()
    {
        $items = Subscription::query()
            ->with(['subscribeDetails', 'stopSubscriptions', 'customer', 'package', 'delivery'])
            ->filter()
            ->paginate(1000);

        return Response::success(200, $items)->mapInto(SubscribeResource::class)->withPagination()->send();
    }

    public function findSubscribe($id)
    {
        $items = (new SubscribeResource(Subscription::query()->where('id', $id)->first()))->newResource();

        return Response::success(200, $items)->send();
    }

    public function updateSubscribe($id, SubscribeDataTransfer $subscribeDataTransfer)
    {
        SubscribeService::updateSubscribe($id, $subscribeDataTransfer);

        $items = (new SubscribeResource(Subscription::query()->where('id', $id)->first()))->newResource();

        return Response::success(200, $items)->send();
    }

    public function destroy($id)
    {
        Subscription::query()->where('id', $id)->delete();
        SubscriptionDetail::query()->where('subscription_id', $id)->delete();
        SubscriptionDay::query()->where('subscription_id', $id)->delete();
        StopSubscription::query()->where('subscription_id', $id)->delete();
        GiveSubscription::query()->where('subscription_id', $id)->delete();
        Bank::query()->where('subscription_id', $id)->delete();
        Measurement::query()->where('subscription_id', $id)->delete();
        ExcludeRecipie::query()->where('subscription_id', $id)->delete();
        ExcludeNotIngredient::query()->where('subscription_id', $id)->delete();
        ExcludeIngredient::query()->where('subscription_id', $id)->delete();
        PersonalDesires::query()->where('subscription_id', $id)->delete();
        Delivery::query()->where('subscription_id', $id)->delete();
        Transaction::query()->where('subscription_id', $id)->delete();
        GroupSubscription::query()->where('subscription_id', $id)->delete();

        return Response::success(200)->withMessage('deleted successfully')->send();
    }

    public function service()
    {
        return new SubscribeService();
    }
}
