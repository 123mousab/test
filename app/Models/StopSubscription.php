<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StopSubscription extends Model
{
    use HasFactory;

    const FILLABLE = ['subscription_id', 'start_date', 'end_date'];

    protected $fillable = self::FILLABLE;

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function getIsActiveAttribute()
    {
        return Carbon::now()->greaterThan(Carbon::parse($this->end_date));
    }
}
