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

class UpdateSubscribe
{
    protected $subscribeId;

    public function __construct($subscribeId)
    {
        $this->subscribeId = $subscribeId;
    }

    public function process(SubscribeDataTransfer $subscribeDataTransfer)
    {
        return DB::transaction(function () use ($subscribeDataTransfer) {
            return $this
                ->updateCustomer($subscribeDataTransfer)
                ->updateSubscriber($subscribeDataTransfer)
                ->updateMeasurement($subscribeDataTransfer)
                ->updateAccountBank($subscribeDataTransfer)
                ->createTransaction($subscribeDataTransfer)
                ->updatePersonalDesires($subscribeDataTransfer)
                ->updateDelivery($subscribeDataTransfer);
        });
    }

    private function updateCustomer(SubscribeDataTransfer $subscribeDataTransfer)
    {
        $subscription = Subscription::query()->where('id', $this->subscribeId)->firstOrFail();
        $data = collect(request('customer'))->only(['name', 'email', 'mobile', 'birth_date']);
        $customer = Customer::query()->where('id', $subscription->customer_id)->first();

        $customer->update([
            'name' => $data['name'],
            'email' => @$data['email'],
            'mobile' => $data['mobile'],
            'birth_date' => @$data['birth_date'],
        ]);

        $subscribeDataTransfer->setCustomerId($customer->id);

        return $this;
    }

    private function updateMeasurement(SubscribeDataTransfer $subscribeDataTransfer)
    {
        $measurement = Measurement::query()->where('subscription_id', $this->subscribeId)->firstOrFail();

        $data = collect(request('measurement'))->only(['height', 'weight', 'muscle', 'fluid', 'target']);

        $measurement->update([
            'height' => $data['height'],
            'weight' => $data['weight'],
            'muscle' => $data['muscle'],
            'fluid' => $data['fluid'],
            'target' => $data['target'],
            'customer_id' => $subscribeDataTransfer->getCustomerId(),
            'subscription_id' => $this->subscribeId
        ]);

        return $this;
    }

    private function updateAccountBank(SubscribeDataTransfer $subscribeDataTransfer)
    {
        $bank = Bank::query()->where('subscription_id', $this->subscribeId)->firstOrFail();

        $data = collect(request('bank'))->only(['bank', 'number_money_transfer', 'amount', 'fluid', 'target', 'bank_name_id', 'transfer_date']);

        $bank->update([
            'bank_name_id' => $data['bank_name_id'],
            'transfer_date' => $data['transfer_date'],
            'number_money_transfer' => $data['number_money_transfer'],
            'amount' => $data['amount'],
            'customer_id' => $subscribeDataTransfer->getCustomerId(),
            'subscription_id' => $this->subscribeId
        ]);
        return $this;
    }

    private function createTransaction(SubscribeDataTransfer $subscribeDataTransfer)
    {
        $transaction = Transaction::query()->where('subscription_id', $this->subscribeId)->first();

        if ($transaction != null) {
            $data = collect(request('bank'))->only(['number_money_transfer', 'amount']);

            $transaction->update([
                'number_money_transfer' => $data['number_money_transfer'],
                'amount' => $data['amount'],
                'customer_id' => $subscribeDataTransfer->getCustomerId(),
                'subscription_id' => $this->subscribeId
            ]);
        }

        return $this;
    }

    private function updateSubscriber(SubscribeDataTransfer $subscribeDataTransfer)
    {
        $subscription = Subscription::query()->where('id', $this->subscribeId)->firstOrFail();

        $data = collect(request('subscribe'))->only('package_id', 'discount', 'days', 'start_date', 'renew_date', 'renew_day_numbers', 'subscribe_status', 'group_subscribe');

        $package = Package::query()->where('id', $data['package_id'])->first();

        $subscription->update([
            'customer_id' => $subscribeDataTransfer->getCustomerId(),
            'package_id' => $data['package_id'],
            'transfer_status' => $data['subscribe_status'],
            'discount' => $data['discount'],
            'start_date' => $data['start_date'],
        ]);

        if (request()->has('subscribe.group_subscribe')) {
            GroupSubscription::query()->where('subscription_id', $this->subscribeId)->delete();
            collect(request('subscribe.group_subscribe'))->map(function ($groupSubscribe) use ($subscribeDataTransfer) {
                return GroupSubscription::query()->create([
                    'subscription_id' => $this->subscribeId,
                    'group_id' => $groupSubscribe['id'],
                    'quantity' => $groupSubscribe['quantity'] ?? 1,
                ]);
            });
        }


        SubscriptionDay::query()->where('subscription_id',  $subscription->id)->delete();

        $day_list[] = collect($data['days'])->map(function ($day) use ($subscription) {

            SubscriptionDay::query()->create([
                'subscription_id' => $subscription->id,
                'day' => (int)$day
            ]);

            return $day;
        })->all();

        if (request()->has('subscribe.renew_date') && request('subscribe.renew_date') != '' && request('subscribe.renew_date') != null) {

            SubscriptionDetail::query()->where('subscription_id', $this->subscribeId)->delete();


            $subscribeDataTransfer->setCountDays($data['renew_day_numbers']);

            $days_number = $subscribeDataTransfer->getCountDays();
            $start_date = request('subscribe.renew_date');
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

            Subscription::query()->where('id', $this->subscribeId)->update([
                'end_date' => last($validDate)
            ]);

            collect($validDate)->each(function ($date) use ($subscribeDataTransfer) {
                return SubscriptionDetail::query()->create([
                    'subscription_id' => $this->subscribeId,
                    'subscription_dates' => $date,
                    'status' => 1
                ]);
            });

        }

        return $this;
    }

    private function updatePersonalDesires(SubscribeDataTransfer $subscribeDataTransfer)
    {
        ExcludeIngredient::query()->where('subscription_id', $this->subscribeId)->delete();
        ExcludeNotIngredient::query()->where('subscription_id', $this->subscribeId)->delete();
        ExcludeRecipie::query()->where('subscription_id', $this->subscribeId)->delete();
        $personal_desires = PersonalDesires::query()->where('subscription_id', $this->subscribeId)->firstOrFail();

        $data = collect(request('personal_desires'))->only('ingredients', 'not_ingredients', 'recipies');
        $personalDesires = collect(request('personal_desires'))->only('notes', 'protein', 'carbohydrates');


        if (request()->has('personal_desires.ingredients')) {
            collect($data['ingredients'])->map(function ($nutritionFact) use ($subscribeDataTransfer) {
                return ExcludeIngredient::query()->create([
                    'customer_id' => $subscribeDataTransfer->getCustomerId(),
                    'subscription_id' => $this->subscribeId,
                    'ingredient_id' => $nutritionFact
                ]);
            });
        }

        if (request()->has('personal_desires.not_ingredients')) {
            collect($data['not_ingredients'])->map(function ($nutritionFact) use ($subscribeDataTransfer) {
                return ExcludeNotIngredient::query()->create([
                    'customer_id' => $subscribeDataTransfer->getCustomerId(),
                    'subscription_id' => $this->subscribeId,
                    'ingredient_id' => $nutritionFact
                ]);
            });
        }

        // هدولا لاسثثاناءات المميزات وليس الوصفات
        if (request()->has('personal_desires.recipies')) {
            collect($data['recipies'])->map(function ($nutritionFact) use ($subscribeDataTransfer) {
                return ExcludeRecipie::query()->create([
                    'customer_id' => $subscribeDataTransfer->getCustomerId(),
                    'subscription_id' => $this->subscribeId,
                    'recipie_id' => $nutritionFact
                ]);
            });
        }

        $personal_desires->update([
            'customer_id' => $subscribeDataTransfer->getCustomerId(),
            'subscription_id' => $this->subscribeId,
            'notes' => @$personalDesires['notes'],
            'protein' => @$personalDesires['protein'],
            'carbohydrates' => @$personalDesires['carbohydrates'],
        ]);

        return $this;
    }

    private function updateDelivery(SubscribeDataTransfer $subscribeDataTransfer)
    {
        $delivery = Delivery::query()->where('subscription_id', $this->subscribeId)->firstOrFail();

        $data = collect(request('delivery'))->only('customer_id',
            'city_id', 'branch_id', 'company_id', 'group_name_id', 'home_address', 'period', 'home_number'
            , 'address', 'notes');

        return $delivery->update([
            'customer_id' => $subscribeDataTransfer->getCustomerId(),
            'subscription_id' => $this->subscribeId,
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
