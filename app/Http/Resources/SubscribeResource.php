<?php

namespace App\Http\Resources;

use App\Models\Bank;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscribeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'customer_name' => $this->customer->name,
            'customer_mobile' => $this->customer->mobile,
            'package_name' => $this->package->name,
            'package_day' => $this->package->number_of_days,
            'start_date' => Carbon::parse($this->start_date)->format('Y-m-d'),
            'end_date' => Carbon::parse($this->end_date)->format('Y-m-d'),
            'is_ended' => $this->is_ended,
            'status' => $this->status,
            'stop_subscription' => [
                'start_date' => $this->stopSubscriptions->first()->start_date ?? null,
                'end_date' => $this->stopSubscriptions->first()->end_date ?? null,
            ],
            'city_id' => (int)@$this->delivery->city_id,
            'city_name' => @$this->delivery->city->name,
            'branch_id' => (int)@$this->delivery->branch_id,
            'branch_name' => @$this->delivery->branch->name,
            'count_subscriptions_day' => (int)$this->remind_days,
            'remind_days' => $this->subscribeDetails()->whereDate('subscription_dates', '>=', now())->count() ?? 0,
            'subscribe_status' => @$this->transfer_status == 1 ? 1 : 2,
            'subscribe_is_ended' => @$this->status_subscription,
//            'subscribe_details' => collect($this->subscribeDetails)->map(function ($details) {
//                return [
//                    'subscription_dates' => Carbon::parse($details['subscription_dates'])->format('Y-m-d'),
//                    'status' => $details['status'],
//                ];
//            }),
        ];
    }

    public function newResource()
    {
        return [
            'customer' => $this->getCustomer(),
            'measurement' => $this->getMeasurement(),
            'bank' => $this->getDataBank(),
            'personal_desires' => $this->getPersonalDesires(),
            'delivery' => $this->getDelivery(),
            'subscribe' => $this->getSubscribe(),
        ];
    }

    private function getCustomer()
    {
        return [
            'name' => @$this->customer->name,
            'mobile' => @$this->customer->mobile,
            'email' => @$this->customer->email,
            'birth_date' => @$this->customer->birth_date,
        ];
    }

    private function getMeasurement()
    {
        return [
            'height' => @$this->measurement->height,
            'weight' => @$this->measurement->weight,
            'muscle' => @$this->measurement->muscle,
            'fluid' => @$this->measurement->fluid,
            'target' => @$this->measurement->target,
        ];
    }

    private function getDataBank()
    {
        $bankData = Bank::query()->where('customer_id', $this->customer_id)->orderBy('id')->first();

        return [
            'bank' => @$bankData->bank,
            'number_money_transfer' => @$bankData->number_money_transfer,
            'amount' => @$bankData->amount,
            'bank_name_id' => (int)@$bankData->bank_name_id,
            'bank_name' => @$bankData->bankName->name,
            'transfer_date' => @$bankData->transfer_date,
        ];
    }

    private function getPersonalDesires()
    {

        return [
            'ingredients' => collect($this->excludeIngredients)->map(function ($data) {
                return (int)$data['ingredient_id'];
            }),
            'not_ingredients' => collect($this->excludeNotIngredients)->map(function ($data) {
                return (int)$data['ingredient_id'];
            }),
            'recipies' => collect($this->excludRecipies)->map(function ($data) {
                return (int)$data['recipie_id'];
            }),
            'protein' => (double)$this->personalDesire->protein ?? 0,
            'carbohydrates' => (double)$this->personalDesire->carbohydrates ?? 0,
            'notes' => $this->personalDesire->notes ?? '-',
        ];
    }

    private function getDelivery()
    {
        return [
            'city_id' => (int)@$this->delivery->city_id,
            'branch_id' => (int)@$this->delivery->branch_id,
            'home_address' => @$this->delivery->home_address,
            'period' => (int)@$this->delivery->period,
            'home_number' => @$this->delivery->home_number,
            'company_id' => (int)@$this->delivery->company_id,
            'group_name_id' => (int)@$this->delivery->group_name_id,
            'company' => @$this->delivery->company,
            'group' => @$this->delivery->group,
            'address' => @$this->delivery->address,
            'notes' => @$this->delivery->notes,
        ];
    }

    private function getGroupSubscribe()
    {
        return collect(@$this->groupSubscribes)->map(function ($data) {
            return [
                'id' => @(int)$data['group_id'],
                'quantity' => $data['quantity'],
            ];
        });
    }

    private function getSubscribe()
    {

        return [
            'package_id' => (int)$this->package_id,
            'discount' => 0,
//            'days' => collect($this->package->packageDay)->map(function ($days){
//                return $days['day'];
//            }),
            'days' => collect($this->subscribeDays)->map(function ($days) {
                return (int)$days['day'];
            }),
//            'subscribe_days' => collect($this->subscribeDays)->map(function ($days){
//                return $days['day'];
//            }),
            'start_date' => $this->start_date,
            'count_subscriptions_day' => (int)$this->remind_days,
            'remind_days' => $this->subscribeDetails()->whereDate('subscription_dates', '>=', now())->count() ?? 0,
            'total' => $this->package->price,
            'subscribe_status' => @$this->transfer_status == 1 ? 1 : 2,
            'group_subscribe' => $this->getGroupSubscribe(),
            'subscribe_is_ended' => @$this->status_subscription,
            'stop_subscription' => [
                'start_date' => $this->stopSubscriptions->first()->start_date ?? null,
                'end_date' => $this->stopSubscriptions->first()->end_date ?? null,
            ],
        ];
    }


    public function customerResource()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'mobile' => $this->mobile,
            'email' => $this->email,
            'birth_date' => $this->birth_date,
        ];
    }

    public function listPackages()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }

    public function details()
    {
        return [
            'id' => $this->id,
            'name_translate' => $this->name,
            'name' => $this->getTranslations('name'),
            'number_of_days' => $this->number_of_days,
            'cost' => $this->cost,
            'price' => $this->price,
            'days' => collect($this->packageDay)->map(function ($day) {
                return "" . $day['day'] . "";

            }),
            'group_subscribe' => collect($this->packageDetails)->map(function ($detail) {
                return [
                    'id' => (int)$detail['group_id'],
                    'quantity' => $detail['quantity']
                ];
            }),
        ];
    }
}
