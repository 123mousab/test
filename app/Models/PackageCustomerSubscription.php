<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageCustomerSubscription extends Model
{
    use HasFactory;

    protected $fillable =['customer_id', 'subscription_id' ,'package_id','group_id','quantity'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
