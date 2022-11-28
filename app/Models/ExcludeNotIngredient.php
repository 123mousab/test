<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExcludeNotIngredient extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id','subscription_id' ,'ingredient_id'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class ,'ingredient_id');
    }
}
