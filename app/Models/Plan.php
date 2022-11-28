<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Plan extends Model
{
    use HasFactory ,HasTranslations;

    public $translatable = ['name','description'];
    const FILLABLE = ['name', 'description', 'price','image','status'];
    protected $fillable = self::FILLABLE;

    public function nutritionFacts()
    {
        return $this->belongsToMany(NutriotionFact::class)->withPivot('quantity');
    }
}
