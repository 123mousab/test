<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IngredientNutriotionFact extends Model
{
    use HasFactory;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    const FILLABLE = ['ingredient_id', 'nutrition_fact_id','value' ,'unit_id'];

    protected $fillable = self::FILLABLE;

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }
    public function nutritionFact()
    {
        return $this->belongsTo(NutriotionFact::class);
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
