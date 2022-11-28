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
use App\Models\StopSubscription;
use App\Models\Subscription;
use App\Models\SubscriptionDay;
use App\Models\SubscriptionDetail;
use App\Services\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SubscriptionDataExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        $data = $this->tableCookingToday();

        return view('exports.subscription_data', [
            'data' => $data,
        ]);
    }

    private function tableCookingToday()
    {
        if (request('page') == 0){
            $subscriptions = Subscription::query()
                ->with(['package', 'customer', 'delivery', 'subscribeDetails', 'subscribeDays', 'stopSubscriptions'])
                ->limit(2000)->get();
        }else{
            $subscriptions = Subscription::query()->with(['package', 'customer', 'delivery', 'subscribeDetails', 'subscribeDays', 'stopSubscriptions'])->skip(request('page') * 2000)->limit(2000)->get();
        }

        $data = collect($subscriptions)->map(function ($details) {
            $customer = Customer::query()->where('id', $details['customer_id'])->first();
            $stopSubscription = StopSubscription::query()->where('subscription_id', $details['id'])->latest()->first();
            $package = Package::query()->where('id', $details['package_id'])->first();
            $excludeMainIngredients = ExcludeIngredient::query()->where('subscription_id', $details['id'])->get();
            $excludeMainNotIngredients = ExcludeNotIngredient::query()->where('subscription_id', $details['id'])->get();
            $personalDesires = PersonalDesires::query()->where('subscription_id', $details['id'])->first();
            $deliveries = Delivery::query()->where('subscription_id', $details['id'])->first();
            $excludeRecipes = ExcludeRecipie::query()->where('subscription_id', $details['id'])->get();

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
                    'start_date' => Carbon::parse($details['start_date'])->format('Y-m-d'),
                    'end_date' => Carbon::parse($details['end_date'])->format('Y-m-d'),
                    'remind_days' => $details->subscribeDetails()->whereDate('subscription_dates', '>=', today())->count() ?? 0,
                    'subscribe_is_ended' => @$details['status_subscription']['is_ended_text'],
                    'stop_subscription' => [
                        'start_date' => $stopSubscription->start_date ?? '-',
                        'end_date' => $stopSubscription->end_date ?? '-',
                    ],
                    'days' =>[
                        'st' => SubscriptionDay::query()->where('subscription_id', $details['id'])->where('day', 6)->exists() ? 1 :  0,
                        'su' => SubscriptionDay::query()->where('subscription_id', $details['id'])->where('day', 0)->exists() ? 1 :  0,
                        'mo' => SubscriptionDay::query()->where('subscription_id', $details['id'])->where('day', 1)->exists() ? 1 :  0,
                        'tu' => SubscriptionDay::query()->where('subscription_id', $details['id'])->where('day', 2)->exists() ? 1 :  0,
                        'we' => SubscriptionDay::query()->where('subscription_id', $details['id'])->where('day', 3)->exists() ? 1 :  0,
                        'th' => SubscriptionDay::query()->where('subscription_id', $details['id'])->where('day', 4)->exists() ? 1 :  0,
                    ]
                ],
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
                'active' => $details['active'] ?? '-'
            ];
        });

        return $data;
    }
}
