<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionDetail extends Model
{
    use HasFactory;

    protected $fillable = ['subscription_id', 'subscription_dates', 'status'];

    protected $dates = ['subscription_dates'];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
