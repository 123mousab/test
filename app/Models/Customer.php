<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    const FILLABLE = ['name','status', 'mobile', 'email', 'birth_date'];

    protected $fillable = self::FILLABLE;

    protected $casts = [
        'birth_date' => 'datetime'
    ];

    public function nutrtionFacts()
    {
        return $this->hasMany(ExcludeNutritionFact::class);
    }

    public function ingredients()
    {
        return $this->hasMany(ExcludeIngredient::class);
    }

    public function recipies()
    {
        return $this->hasMany(ExcludeRecipie::class);
    }
}
