<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiveSubscription extends Model
{
    use HasFactory;

    const FILLABLE = ['subscription_id', 'number_of_days'];

    protected $fillable = self::FILLABLE;

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
