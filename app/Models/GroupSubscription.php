<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupSubscription extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'group_id',
        'subscription_id',
        'quantity'
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
