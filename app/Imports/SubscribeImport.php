<?php

namespace App\Imports;

use App\Http\Controllers\Admin\DataTransfer\SubscribeDataTransfer;
use App\Models\Bank;
use App\Models\Customer;
use App\Models\Delivery;
use App\Models\Measurement;
use App\Models\Package;
use App\Models\PersonalDesires;
use App\Models\Subscription;
use App\Models\SubscriptionDay;
use App\Models\SubscriptionDetail;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;

class SubscribeImport implements ToModel
{
    /**
     * @param array $row
     * $row[0] => name
     * $row[1] => mobile
     * $row[2] => number_of_meals
     * $row[3] => transfer_status
     * $row[4] => protein
     * $row[5] => carb
     * $row[6] => notes
     * $row[7] => city
     * $row[8] => subscribe_status
     * $row[9] => period
     * $row[10] => deliverie_notes
     * $row[11] => days
     * $row[12] => deliveries_order
     * $row[13] => package
     * $row[14] => branch
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $subscribeDataTransfer = new SubscribeDataTransfer();

        $customer = Customer::query()->create([
            'name' => $row[0],
            'mobile' => $row[1],
        ]);
        $subscribeDataTransfer->setCustomerId($customer->id);

        // create subscribe
        $package = Package::query()->where('id', $row[13])->first();
        $numberOfDays = $row[2];

        $subscription = Subscription::query()->create([
            'customer_id' => $subscribeDataTransfer->getCustomerId(),
            'package_id' => $row[13],
            'discount' => 0,
            'remind_days' => $numberOfDays,
            'transfer_status' => $row[3],
            'start_date' => now(),
            'status' => $row[8],
            'is_ended' => 1
        ]);

        $subscribeDataTransfer->setSubscribeId($subscription->id);
        $subscribeDataTransfer->setCountDays($numberOfDays);

        $day_list[] = collect(explode(' ', $row[11]))->map(function ($day) use ($subscribeDataTransfer){
            SubscriptionDay::query()->create([
                'subscription_id' => $subscribeDataTransfer->getSubscribeId(),
                'day' => (int)$day
            ]);

            return $day;
        })->all();



        $days_number = $subscribeDataTransfer->getCountDays();
        $start_date = now();
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

        Measurement::query()->create([
            'customer_id' => $subscribeDataTransfer->getCustomerId(),
            'subscription_id' => $subscribeDataTransfer->getSubscribeId()
        ]);


        Bank::query()->create([
            'customer_id' => $subscribeDataTransfer->getCustomerId(),
            'subscription_id' => $subscribeDataTransfer->getSubscribeId()
        ]);

        PersonalDesires::query()->create([
            'customer_id' => $subscribeDataTransfer->getCustomerId(),
            'subscription_id' => $subscribeDataTransfer->getSubscribeId(),
            'notes' => @$row[6],
            'protein' => @$row[4],
            'carbohydrates' => @$row[5],
        ]);

        Delivery::query()->create([
            'customer_id' => $subscribeDataTransfer->getCustomerId(),
            'subscription_id' => $subscribeDataTransfer->getSubscribeId(),
            'city_id' => $row[7],
            'branch_id' => $row[14],
            'period' => $row[9],
            'home_number' => $row[12],
            'notes' => $row[10],
        ]);

        return $customer;
    }
}
