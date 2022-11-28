<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use KingOfCode\Upload\Uploadable;
use Spatie\Translatable\HasTranslations;

class NutriotionFact extends Model
{
    use HasFactory, HasTranslations;

    public $translatable = ['name'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    const FILLABLE = ['name', 'status'];

    protected $fillable = self::FILLABLE;


}
