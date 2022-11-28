<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    const FILLABLE = [
        'customer_id',
        'subscription_id',
        'city_id',
        'branch_id',
        'driver_id',
        'company_id',
        'group_name_id',
        'home_address',
        'period',
        'home_number',
        'company',
        'group',
        'address',
        'image',
        'notes',
        'status'
    ];

    protected $fillable = self::FILLABLE;


    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function compnay()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function groupName()
    {
        return $this->belongsTo(GroupName::class);
    }

    public function periodRelation()
    {
        return $this->belongsTo(Period::class, 'period');
    }
}
