<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuIngredientDetail extends Model
{
    use HasFactory;

    const FILLABLE = ['menu_id', 'ingredient_id' ,'recipie_protein_id','cuisine_protein_id', 'recipie_carb_id', 'cuisine_carb_id'];

    protected $fillable = self::FILLABLE;

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class, 'ingredient_id');
    }

    public function recipieProtein()
    {
        return $this->belongsTo(Recipie::class, 'recipie_protein_id');
    }

    public function cuisineProtein()
    {
        return $this->belongsTo(Cuisine::class, 'cuisine_protein_id');
    }

    public function recipieCarb()
    {
        return $this->belongsTo(Recipie::class, 'recipie_carb_id');
    }

    public function cuisineCarb()
    {
        return $this->belongsTo(Cuisine::class, 'cuisine_carb_id');
    }
}
