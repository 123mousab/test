<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExcludeRecipie extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'subscription_id' ,'recipie_id'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function recipie()
    {
        return $this->belongsTo(Recipie::class);
    }
}
