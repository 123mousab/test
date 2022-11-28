<?php

namespace App\Exports;

use App\Http\Controllers\Admin\DataTransfer\SubscribeDataTransfer;
use App\Models\Customer;
use App\Models\Delivery;
use App\Models\ExcludeIngredient;
use App\Models\ExcludeNotIngredient;
use App\Models\ExcludeRecipie;
use App\Models\GroupSubscription;
use App\Models\Menu;
use App\Models\Package;
use App\Models\PersonalDesires;
use App\Models\StopSubscription;
use App\Models\SubscriptionDetail;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;


class QuantitiesExport implements FromView, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $subscriptionDetails = SubscriptionDetail::query()->with('subscription'
        )->whereDate('subscription_dates', request('date', today()));

        $activeSub = collect($subscriptionDetails->get())->filter(function ($sub){
            return $sub->subscription->is_active && $sub->subscription->check_is_ended;
        })->count();

        $stopSub = collect($subscriptionDetails->get())->map(function ($sub){
            return StopSubscription::query()->where('subscription_id', $sub->subscription_id)->latest()->first();
        })->filter(function ($list) {
            return $list != null && $list->is_active;
        })->count();

        // result with and without spicy
        $result = collect($subscriptionDetails->get())->map(function ($details) {
            return $this->processQuantity($details)['list1'];
        });

        $newResProtein = collect($result)->flatMap(function ($list){
            return $list;
        })->groupBy('recipie_protein_id')->values()->map(function ($q){
            return $this->processRecipeProtein($q);
        });

        $newResCarb = collect($result)->flatMap(function ($list){
            return $list;
        })->groupBy('recipie_carb_id')->values()->map(function ($q){
            return $this->processRecipeCarb($q);
        });

        // result with spicy
        $resultWithSpicy = collect($subscriptionDetails->get())->filter(function ($details) {
            return $details->subscription->check_is_spicy;
        })->map(function ($details){
            return $this->processQuantity($details)['list1'];
        });


        $newResProteinWithSpicy = collect($resultWithSpicy)->flatMap(function ($list){
            return $list;
        })->groupBy('recipie_protein_id')->values()->map(function ($q){
            return $this->processRecipeProtein($q);
        });

        $newResCarbWithSpicy = collect($resultWithSpicy)->flatMap(function ($list){
            return $list;
        })->groupBy('recipie_carb_id')->values()->map(function ($q){
            return $this->processRecipeCarb($q);
        });


        // result without spicy
        $resultWithoutSpicy = collect($subscriptionDetails->get())->filter(function ($details) {
            return $details->subscription->is_spicy == 0;
        })->map(function ($details){
            return $this->processQuantity($details)['list1'];
        });

        $newResProteinWithoutSpicy = collect($resultWithoutSpicy)->flatMap(function ($list){
            return $list;
        })->groupBy('recipie_protein_id')->values()->map(function ($q){
            return $this->processRecipeProtein($q);
        });

        $newResCarbWithoutSpicy = collect($resultWithoutSpicy)->flatMap(function ($list){
            return $list;
        })->groupBy('recipie_carb_id')->values()->map(function ($q){
            return $this->processRecipeCarb($q);
        });

        // result with and without spicy
        $resultGroup =  collect($subscriptionDetails->get())->flatMap(function ($details) {
            return $this->processQuantity($details)['list2'];
        })->groupBy('recipie_id')->map(function ($data){
            $sumQuantity = $data->sum('quantity');
            $item = $data->first();
            return [
                'recipie_id' => $item['recipie_id'],
                'recipie_name' => $item['recipie_name'],
                'group_id' => $item['group_id'],
                'group_name' => $item['group_name'],
                'sum_quantity' => $sumQuantity
            ];
        })->values()->all();

        // result with and without spicy
        $listPackages = collect($subscriptionDetails->get())->flatMap(function ($details) {
            return $this->processQuantity($details)['package'];
        })->groupBy('id')->map(function ($list){
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

        return view('exports.quantities_report', [
            'data' => $data
        ]);
    }

    public function processQuantity($details)
    {
        $customer = Customer::query()->where('id', $details->subscription->customer_id)->first();
        $package = Package::query()->where('id', $details->subscription->package_id)->first();
        $groupSubscription = GroupSubscription::query()->where('subscription_id', $details['subscription_id'])->get();
        $excludeMainIngredients = ExcludeIngredient::query()->where('subscription_id', $details['subscription_id'])->get();
        $excludeMainNotIngredients = ExcludeNotIngredient::query()->where('subscription_id', $details['subscription_id'])->get();
        $personalDesires = PersonalDesires::query()->where('subscription_id', $details['subscription_id'])->first();
        $menu = Menu::query()->whereDate('cooking_date', request('date', today()))->first();
        $deliveries = Delivery::query()->where('subscription_id', $details['subscription_id'])->first();
        $excludeRecipes = ExcludeRecipie::query()->where('subscription_id', $details['subscription_id'])->get();

        $numberOfMeal = $package->number_of_meals;
        $reserved = 0;
        $reservedList[] = null;

        $transferData = new SubscribeDataTransfer();
        $transferData->setSubscribeId($details['subscription_id']);
        $transferData->setListPackage($package);

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
                        'carb' => $personalDesires->carbohydrates,
                        'protein' => $personalDesires->protein,
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
                        'carb' => $personalDesires->carbohydrates,
                        'protein' => $personalDesires->protein,
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
                        'carb' => $personalDesires->carbohydrates,
                        'protein' => $personalDesires->protein,
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
                        'carb' => $personalDesires->carbohydrates,
                        'protein' => $personalDesires->protein,
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
        return collect($q)->map(function ($q2) use ($count, $p, $c){
            return [
                'count' => $count,
                'ingredient_name' => $q2['ingredient_name'] ?? '',
                'recipie_protein_id' => $q2['recipie_protein_id'] ?? '',
                'recipie_protein_name' => $q2['recipie_protein_name']?? '',
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
        return collect($q)->map(function ($q2) use ($count, $p, $c){
            return [
                'count' => $count,
                'ingredient_name' => $q2['ingredient_name'] ?? '',
                'recipie_carb_id' => $q2['recipie_carb_id'] ?? '',
                'recipie_carb_name' => $q2['recipie_carb_name'] ?? '',
            ];
        })->first();
    }
}
