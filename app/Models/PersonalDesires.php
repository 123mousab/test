<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalDesires extends Model
{
    use HasFactory;
    const FILLABLE = ['customer_id', 'subscription_id' ,'notes', 'cuisine_id', 'ingredient_id','recipie_id', 'protein', 'carbohydrates'];

    protected $fillable = self::FILLABLE;


    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function cuisine()
    {
        return $this->belongsTo(Cuisine::class);
    }

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }

    public function recipie()
    {
        return $this->belongsTo(Recipie::class);
    }

    public function getKetoAttribute()
    {
        return $this->carbohydrates == 0;
    }

    public function getStandardAttribute()
    {
        return $this->protein == 150 && $this->carbohydrates == 150;
    }
}
