<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Measurement extends Model
{
    use HasFactory;

    const FILLABLE = ['customer_id','subscription_id' ,'height', 'weight', 'muscle', 'fluid', 'fats', 'target'];

    protected $fillable = self::FILLABLE;

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function getTargetTextAttribute($value)
    {

        $target =[
            0 =>    __('common.amplify'), // تضخيم
            1 =>    __('common.weight_loss'), // تنقيص وزن
            2 =>    __('common.body_drying'), // تنشيف
        ];
        return $target[$this->target];
    }
}
