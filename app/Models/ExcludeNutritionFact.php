<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExcludeNutritionFact extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'nutrition_fact_id'];
}
