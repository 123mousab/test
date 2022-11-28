<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecipieIngeredient extends Model
{
    use HasFactory;

    public $translatable = ['name'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    const FILLABLE = ['ingeredient_id', 'recipie_id', 'quantity', 'cost', 'line_cost', 'status'];

    protected $fillable = self::FILLABLE;

    public function ingeredient()
    {
        return $this->belongsTo(Ingredient::class);
    }

    public function recipie()
    {
        return $this->belongsTo(Recipie::class);
    }

    public function getLineCostAttribute()
    {
        return  $this->cost * $this->quantity;
    }
}
