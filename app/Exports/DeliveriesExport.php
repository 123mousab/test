<?php

namespace App\Exports;

use App\Models\Admin;
use App\Models\Customer;
use App\Models\Delivery;
use App\Models\SubscriptionDetail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DeliveriesExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $subscriptionDetails = SubscriptionDetail::query()->with(['subscription'])->whereDate('subscription_dates', request('date', now()))->get();

        $data = collect($subscriptionDetails)->map(function ($details) {
            $customer = Customer::query()->where('id', $details->subscription->customer_id)->firstOrFail();
            $deliveries = Delivery::query()->where('subscription_id', $details['subscription_id'])->firstOrFail();

            return [
                'customer' => [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'mobile' => $customer->mobile,
                ],
                'deliveries' => [
                    'city' => @$deliveries->city->name,
                    'delegate_name' => @$deliveries->city->delegate_name,
                    'branch' => @$deliveries->city->branch,
                    'home_address' => @$deliveries->home_address,
                    'home_number' => @$deliveries->home_number,
                    'period' => @$deliveries->periodRelation->name,
                    'notes' => @$deliveries->notes,
                ]
            ];
        });

        return $data;
    }


    public function headings(): array
    {
        return [
            '#',
            'name',
            'mobile',
            'city',
            'delegate_name',
            'branch',
            'home_address',
            'home_number',
            'period',
            'notes'
        ];
    }
}
