<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeatureGroupSubscriptionRecipie extends Model
{
    use HasFactory;

    protected $fillable = ['subscription_id' ,'recipie_id'];


    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function recipie()
    {
        return $this->belongsTo(Recipie::class);
    }
}
