<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    const FILLABLE = ['subscription_id', 'customer_id', 'bank', 'number_money_transfer', 'amount', 'bank_name_id', 'transfer_date'];

    protected $fillable = self::FILLABLE;

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function bankName()
    {
        return $this->belongsTo(BankName::class);
    }
}
