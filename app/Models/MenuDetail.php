<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuDetail extends Model
{
    use HasFactory;

    const FILLABLE = ['menu_id', 'ingredient_id' ,'cuisine_id','recipie_id', 'type'];
    protected $fillable = self::FILLABLE;


    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }
    public function recipie()
    {
        return $this->belongsTo(Recipie::class);
    }
    public function cuisine()
    {
        return $this->belongsTo(Cuisine::class);
    }
}
