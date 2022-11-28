<?php

namespace App\Exports;


use App\Http\Controllers\Admin\DataTransfer\SubscribeDataTransfer;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Delivery;
use App\Models\ExcludeIngredient;
use App\Models\ExcludeNotIngredient;
use App\Models\ExcludeRecipie;
use App\Models\Group;
use App\Models\GroupSubscription;
use App\Models\Menu;
use App\Models\Package;
use App\Models\PersonalDesires;
use App\Models\SubscriptionDetail;
use App\Services\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class KitchenTodayExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        $data = $this->tableCookingToday();

        $groups = Group::query()->get();

        $ingredientIdsWithMenuDetails = Menu::query()->where('cooking_date', request('date', today()))->first()->menuIngredientDetails
        ->pluck('ingredient_id')->all();

        $proteinIdsWithMenuDetails = Menu::query()->where('cooking_date', request('date', today()))->first()->menuIngredientDetails
            ->pluck('recipie_protein_id')->all();


        $carbIdsWithMenuDetails = Menu::query()->where('cooking_date', request('date', today()))->first()->menuIngredientDetails
            ->pluck('recipie_carb_id')->all();

        return view('exports.kitchen_today_report', [
            'data' => $data,
            'ingredientIdsWithMenuDetails' => $ingredientIdsWithMenuDetails,
            'proteinIdsWithMenuDetails' => $proteinIdsWithMenuDetails,
            'carbIdsWithMenuDetails' => $carbIdsWithMenuDetails,
            'groups' => $groups
        ]);
    }

    private function tableCookingToday()
    {
        $subscriptionDetails = SubscriptionDetail::query()->whereHas('subscription', function ($query){
            return $query->where('status', 1);
        })->whereDate('subscription_dates', request('date', now()))->get();

        $data = collect($subscriptionDetails)->map(function ($details) {
            $customer = Customer::query()->where('id', $details->subscription->customer_id)->first();
            $package = Package::query()->where('id', $details->subscription->package_id)->first();
            $groupSubscription = GroupSubscription::query()->where('subscription_id', $details['subscription_id'])->orderBy('group_id')->get();
//            $featureGroupSubscription = FeatureGroupSubscriptionRecipie::query()->where('subscription_id', $details['subscription_id'])->get();
            $excludeMainIngredients = ExcludeIngredient::query()->where('subscription_id', $details['subscription_id'])->get();
            $excludeMainNotIngredients = ExcludeNotIngredient::query()->where('subscription_id', $details['subscription_id'])->get();
            $personalDesires = PersonalDesires::query()->where('subscription_id', $details['subscription_id'])->first();
            $menu = Menu::query()->whereDate('cooking_date', request('date', now()))->first();
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

            $itemGroupMenu2 = collect($newItemGroupMenu)->each(function ($item) use ($excludeRecipes, $transferData){
                return collect($item)->each(function ($data) use ($excludeRecipes, $transferData){
                    return collect($excludeRecipes)->each(function ($exRec) use ($data, $transferData){
                        if ($data['recipie_id'] == $exRec['recipie_id']){
                            $transferData->setGroup1($data);
                        }else{
                            $transferData->setGroup2($data);
                        }
                    });
                });
            })->values();

            if($excludeRecipes->count() > 0)
            {
                $newArrayOfGroup = array_merge($transferData->getGroup1(), $transferData->getGroup2());
                $newItemGroupMenu = collect($newArrayOfGroup)->groupBy('group_id')->map(function ($item) {
                    return $item->first();
                })->values();
            }else{
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
                            'carb' => $data->count() * $personalDesires->carbohydrates,
                            'protein' => $data->count() * $personalDesires->protein,
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
                            'carb' => $personalDesires->carbohydrates ?? '-',
                            'protein' => $personalDesires->protein ?? '-',
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
                            'carb' => $personalDesires->carbohydrates ?? '-',
                            'protein' => $personalDesires->protein ?? '-',
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
                            'carb' => $personalDesires->carbohydrates ?? '-',
                            'protein' => $personalDesires->protein ?? '-',
                        ];
                    });
                } elseif (empty($transferData->getListIngredientId()) && empty($transferData->getListCarbId())) {
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
                            'carb' => $personalDesires->carbohydrates ?? '-',
                            'protein' => $personalDesires->protein ?? '-',
                        ];
                    });
                }

            }
            return [
                'customer' => [
                    'id' => $customer->id ?? '-',
                    'name' => $customer->name ?? '-',
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
                    'remind_days' => $details->subscription->subscribeDetails()->whereDate('subscription_dates', '>=', today())->count() ?? 0,
                ],
                'group_subscription' => collect($groupSubscription)->map(function ($groupSubscription) {
                    return [
                        'group_id' => $groupSubscription->group_id ?? '-',
                        'group_name' => $groupSubscription->group->name ?? '-',
                        'quantity' => $groupSubscription->quantity ?? '-',
                    ];
                }),
                'exclude_main_ingredients' => collect($excludeMainIngredients)->map(function ($excludeMainIngredient) {
                    return [
                        'id' => $excludeMainIngredient->ingredient_id ?? '-',
                        'name' => $excludeMainIngredient->ingredient->name ?? '-',
                    ];
                }),
                'personal_desires' => [
                    'notes' => $personalDesires->notes ?? '-',
                    'exclude_not_main_ingredients' => collect($excludeMainNotIngredients)->map(function ($excludeMainNotIngredient) {
                        return $excludeMainNotIngredient->ingredient->name;
                    }),
                    'recipes' => collect($excludeRecipes)->map(function ($excludeRecipe) {
                        return $excludeRecipe->recipie->name;
                    }),
                ],
                'carb' => $personalDesires->carbohydrates ?? '-',
                'protein' => $personalDesires->protein ?? '-',
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
//                        'item' => $reservedList,
                        'item' => collect($reservedList)->groupBy('ingredient_id')->map(function ($item){
                            return array_merge($item->first(), ['ingredient_count' => $item->count()]);
                        })->all(),
                        'protein_item' => collect($reservedList)->groupBy('recipie_protein_id')->map(function ($item){
                            return array_merge($item->first(), ['protein_count' => $item->count()]);
                        })->map(function ($item){
                            return [
                                'recipie_protein_id' => $item['recipie_protein_id'],
                                'recipie_protein_name' => $item['recipie_protein_name'],
                                'protein_count' => $item['protein_count'],
                            ];
                        }),
                        'carb_item' => collect($reservedList)->groupBy('recipie_carb_id')->map(function ($item){
                            return array_merge($item->first(), ['carb_count' => $item->count()]);
                        })->map(function ($item){
                            return [
                                'recipie_carb_id' => $item['recipie_carb_id'],
                                'recipie_carb_name' => $item['recipie_carb_name'],
                                'carb_count' => $item['carb_count'],
                            ];
                        }),
                        'reserved' => $reserved
                    ],
                'group_menu' => collect($transferData->listGroupMenu)->groupBy('group_id')->map(function ($group){
                    return $group->first();
                }),
                'deliveries' => [
                    'city' => @$deliveries->city->name ?? '-',
                    'delegate_name' => @$deliveries->city->delegate_name ?? '-',
                    'branch' => @$deliveries->branch->name ?? '-',
                    'home_address' => @$deliveries->home_address ?? '-',
                    'home_number' => @$deliveries->home_number ?? '-',
                    'period' => @$deliveries->periodRelation->name ?? '-',
                    'notes' => @$deliveries->notes ?? '-',
                    'company' => Company::query()->where('id', $deliveries->company_id)->first()->name ?? '-',
                ],
                'keto' => $personalDesires->keto ?? '-',
                'standard' => $personalDesires->standard ?? '-',
                'active' => $details->subscription->active ?? '-'
            ];
        });

        return $data;
    }
}
