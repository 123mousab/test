<?php

namespace App\Exports;

use App\Models\Admin;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Delivery;
use App\Models\SubscriptionDetail;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class NewDeliveriesExport implements FromView
{
    public function view(): View
    {
        $subscriptionDetails = SubscriptionDetail::query()->with(['subscription'])->whereDate('subscription_dates', request('date', now()))->get();

        $data = collect($subscriptionDetails)->map(function ($details) {
            $customer = Customer::query()->where('id', $details->subscription->customer_id)->first();
            $deliveries = Delivery::query()->where('subscription_id', $details['subscription_id'])->first();

            return [
                'customer' => [
                    'id' => $customer->id ?? '-',
                    'name' => $customer->name ?? '-',
                    'mobile' => $customer->mobile ?? '-',
                ],
                'deliveries' => [
                    'city' => @$deliveries->city->name ?? '-',
                    'delegate_name' => @$deliveries->city->delegate_name ?? '-',
                    'branch' => @$deliveries->city->branch ?? '-',
                    'home_address' => @$deliveries->home_address ?? '-',
                    'home_number' => @$deliveries->home_number ?? '-',
                    'company' => Company::query()->where('id', $deliveries['company_id'])->first()->name ?? '-',
                    'period' => @$deliveries->period == 1 ? 'صباحي' : 'مسائي',
                    'notes' => @$deliveries->notes ?? '-',
                ]
            ];
        })->all();

        return view('exports.deliveries_report', [
            'data' => $data
        ]);
    }
}
