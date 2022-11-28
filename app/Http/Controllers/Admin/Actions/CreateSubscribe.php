<?php


namespace App\Http\Controllers\Admin\Actions;


use App\Http\Controllers\Admin\DataTransfer\SubscribeDataTransfer;
use App\Models\Bank;
use App\Models\Customer;
use App\Models\Delivery;
use App\Models\ExcludeIngredient;
use App\Models\ExcludeNotIngredient;
use App\Models\ExcludeNutritionFact;
use App\Models\ExcludeRecipie;
use App\Models\GroupSubscription;
use App\Models\Measurement;
use App\Models\Package;
use App\Models\PackageCustomerSubscription;
use App\Models\PackageDay;
use App\Models\PackageDetail;
use App\Models\PersonalDesires;
use App\Models\Subscription;
use App\Models\SubscriptionDay;
use App\Models\SubscriptionDetail;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CreateSubscribe
{
    public function process(SubscribeDataTransfer $subscribeDataTransfer)
    {
        return DB::transaction(function () use ($subscribeDataTransfer) {
            return $this->createCustomer($subscribeDataTransfer)
                ->createSubscriber($subscribeDataTransfer)
                ->createMeasurement($subscribeDataTransfer)
                ->createAccountBank($subscribeDataTransfer)
                ->createTransaction($subscribeDataTransfer)
                ->createPersonalDesires($subscribeDataTransfer)
                ->createDelivery($subscribeDataTransfer);
        });
    }

    private function createCustomer(SubscribeDataTransfer $subscribeDataTransfer)
    {
        $data = collect(request('customer'))->only(['name', 'email', 'mobile', 'birth_date']);
//        $customer = Customer::query()->where('mobile', $data['mobile'])->first();
//
//        if (!$customer)
//        {
            $customer = Customer::query()->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'mobile' => $data['mobile'],
                'birth_date' => $data['birth_date'],
            ]);
//        }
        $subscribeDataTransfer->setCustomerId($customer->id);
        return $this;
    }

    private function createMeasurement(SubscribeDataTransfer $subscribeDataTransfer)
    {
        $data = collect(request('measurement'))->only(['height', 'weight', 'muscle', 'fluid', 'target']);

        Measurement::query()->create([
            'height' => $data['height'],
            'weight' => $data['weight'],
            'muscle' => $data['muscle'],
            'fluid' => $data['fluid'],
            'target' => $data['target'],
            'customer_id' => $subscribeDataTransfer->getCustomerId(),
            'subscription_id' => $subscribeDataTransfer->getSubscribeId()
        ]);
        return $this;
    }

    private function createAccountBank(SubscribeDataTransfer $subscribeDataTransfer)
    {
        $data = collect(request('bank'))->only(['bank', 'number_money_transfer', 'amount', 'fluid', 'target', 'bank_name_id', 'transfer_date']);

        Bank::query()->create([
            'bank_name_id' => $data['bank_name_id'],
            'transfer_date' => $data['transfer_date'],
            'number_money_transfer' => $data['number_money_transfer'],
            'amount' => $data['amount'],
            'customer_id' => $subscribeDataTransfer->getCustomerId(),
            'subscription_id' => $subscribeDataTransfer->getSubscribeId()
        ]);
        return $this;
    }

    private function createTransaction(SubscribeDataTransfer $subscribeDataTransfer)
    {
        $data = collect(request('bank'))->only(['number_money_transfer', 'amount']);

        Transaction::query()->create([
            'number_money_transfer' => $data['number_money_transfer'],
            'amount' => $data['amount'],
            'customer_id' => $subscribeDataTransfer->getCustomerId(),
            'subscription_id' => $subscribeDataTransfer->getSubscribeId()
        ]);
        return $this;
    }

    private function createSubscriber(SubscribeDataTransfer $subscribeDataTransfer)
    {
        $data = collect(request('subscribe'))->only('package_id', 'discount', 'days', 'start_date', 'number_of_days', 'subscribe_status', 'group_subscribe');
//        $packageDataDetails = collect(request('subscribe.quantities'));

        $package = Package::query()->where('id', $data['package_id'])->first();

        $numberOfDays = request('subscribe.number_of_days') ?? $package['number_of_days'];

        $subscription = Subscription::query()->create([
            'customer_id' => $subscribeDataTransfer->getCustomerId(),
            'package_id' => $data['package_id'],
            'transfer_status' => $data['subscribe_status'],
            'discount' => $data['discount'],
            'remind_days' => $numberOfDays,
            'start_date' => $data['start_date'],
            'is_ended' => 1
        ]);

        $days = [
            'Sunday' => 0,
            'Monday' => 1,
            'Tuesday' => 2,
            'Wednesday' => 3,
            'Thursday' => 4,
            'Friday' => 5,
            'Saturday' => 6
        ];

        /* $days = [
             0 => 'Sunday',
             1 => 'Monday',
             2 => 'Tuesday',
             3 => 'Wednesday',
             4 =>'Thursday',
             5 => 'Friday',
             6 =>'Saturday'
         ];*/

        $subscribeDataTransfer->setSubscribeId($subscription->id);
        $subscribeDataTransfer->setCountDays($numberOfDays);

        $packageDays = PackageDay::query()->where('package_id', $data['package_id'])->get();

        $day_list[] = collect($data['days'])->map(function ($day) use ($subscribeDataTransfer){
            SubscriptionDay::query()->create([
                'subscription_id' => $subscribeDataTransfer->getSubscribeId(),
                'day' => $day
            ]);

            return $day;
        })->all();


        $days_number = $subscribeDataTransfer->getCountDays();
        $start_date = request('subscribe.start_date');
        $package_date = Carbon::parse($start_date)->format('Y-m-d');
        $saved_days = 0;
        $saved_days_list = [];

        do {
            $week_day = Carbon::parse($package_date)->dayOfWeek;
            if (in_array($week_day, $day_list[0])) {
                $saved_days++;
                $saved_days_list[] = $package_date;
            }
            $package_date = Carbon::parse($package_date)->addDay();
        } while ($saved_days < $days_number);

        $validDate = collect($saved_days_list)->map(function ($validDate) {
            return Carbon::parse($validDate)->format('Y-m-d');
        })->all();

        Subscription::query()->where('id', $subscribeDataTransfer->getSubscribeId())->update([
            'end_date' => last($validDate)
        ]);

        collect($validDate)->each(function ($date) use ($subscribeDataTransfer) {
            SubscriptionDetail::query()->create([
                'subscription_id' => $subscribeDataTransfer->getSubscribeId(),
                'subscription_dates' => $date,
                'status' => 1
            ]);
        });

       /* $packageDetails = PackageDetail::query()->where('package_id', $data['package_id'])->get();

        collect($packageDetails)->each(function ($detail, $key) use ($subscribeDataTransfer, $packageDataDetails){
            PackageCustomerSubscription::query()->create([
                'customer_id' => $subscribeDataTransfer->getCustomerId(),
                'subscription_id' => $subscribeDataTransfer->getSubscribeId(),
                'package_id' => $detail['package_id'],
                'group_id' => $detail['group_id'],
                'quantity' => $packageDataDetails[$key] ?? 1,
            ]);
        });*/

        if (request()->has('subscribe.group_subscribe'))
        {
            collect(request('subscribe.group_subscribe'))->map(function ($groupSubscribe) use ($subscribeDataTransfer){
                return GroupSubscription::query()->create([
                    'subscription_id' => $subscribeDataTransfer->getSubscribeId(),
                    'group_id' => $groupSubscribe['id'],
                    'quantity' => $groupSubscribe['quantity'] ?? 1,
                ]);
            });
        }

        return $this;
    }

    private function createPersonalDesires(SubscribeDataTransfer $subscribeDataTransfer)
    {
        $data = collect(request('personal_desires'))->only('nutrition_facts', 'ingredients', 'not_ingredients', 'recipies');
        $personalDesires = collect(request('personal_desires'))->only('notes', 'protein', 'carbohydrates');

        if (request()->has('personal_desires.nutrition_facts')){
            collect($data['nutrition_facts'])->map(function ($nutritionFact) use ($subscribeDataTransfer) {
                return ExcludeNutritionFact::query()->create([
                    'customer_id' => $subscribeDataTransfer->getCustomerId(),
                    'nutrition_fact_id' => $nutritionFact
                ]);
            });
        }

        if (request()->has('personal_desires.ingredients')){
            collect($data['ingredients'])->map(function ($nutritionFact) use ($subscribeDataTransfer) {
                return ExcludeIngredient::query()->create([
                    'customer_id' => $subscribeDataTransfer->getCustomerId(),
                    'subscription_id' => $subscribeDataTransfer->getSubscribeId(),
                    'ingredient_id' => $nutritionFact
                ]);
            });
        }

        if (request()->has('personal_desires.not_ingredients')){
            collect($data['not_ingredients'])->map(function ($nutritionFact) use ($subscribeDataTransfer) {
                return ExcludeNotIngredient::query()->create([
                    'customer_id' => $subscribeDataTransfer->getCustomerId(),
                    'subscription_id' => $subscribeDataTransfer->getSubscribeId(),
                    'ingredient_id' => $nutritionFact
                ]);
            });
        }

        // هدولا لاسثثاناءات المميزات وليس الوصفات
        if (request()->has('personal_desires.recipies')){
            collect($data['recipies'])->map(function ($nutritionFact) use ($subscribeDataTransfer) {
                return ExcludeRecipie::query()->create([
                    'customer_id' => $subscribeDataTransfer->getCustomerId(),
                    'subscription_id' => $subscribeDataTransfer->getSubscribeId(),
                    'recipie_id' => $nutritionFact
                ]);
            });
        }

        PersonalDesires::query()->create([
            'customer_id' => $subscribeDataTransfer->getCustomerId(),
            'subscription_id' => $subscribeDataTransfer->getSubscribeId(),
            'notes' => @$personalDesires['notes'],
            'protein' => @$personalDesires['protein'],
            'carbohydrates' => @$personalDesires['carbohydrates'],
        ]);

        return $this;
    }

    private function createDelivery(SubscribeDataTransfer $subscribeDataTransfer)
    {
        $data = collect(request('delivery'))->only('customer_id',
            'city_id', 'branch_id', 'company_id', 'group_name_id' ,'home_address', 'period', 'home_number'
            , 'address', 'notes');

        return Delivery::query()->create([
            'customer_id' => $subscribeDataTransfer->getCustomerId(),
            'subscription_id' => $subscribeDataTransfer->getSubscribeId(),
            'city_id' => $data['city_id'],
            'branch_id' => $data['branch_id'],
            'company_id' => $data['company_id'],
            'group_name_id' => $data['group_name_id'],
            'home_address' => $data['home_address'],
            'period' => $data['period'],
            'home_number' => $data['home_number'],
            'address' => $data['address'],
            'notes' => $data['notes'],
        ]);
    }
}
