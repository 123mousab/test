<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;
    const FILLABLE = ['cooking_date', 'status'];
    protected $fillable = self::FILLABLE;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'cooking_date',
    ];

    public function menuIngredientDetails()
    {
        return $this->hasMany(MenuIngredientDetail::class, 'menu_id');
    }

    public function menuGroupDetails()
    {
        return $this->hasMany(MenuGroupDetail::class, 'menu_id');
    }

    public function menuDetails()
    {
        return $this->hasMany(MenuDetail::class);
    }

    public function menuFirstGroup()
    {
        return $this->menuDetails()->where('type', 1);
    }

    public function menuSecondGroup()
    {
        return $this->menuDetails()->where('type', 0);
    }
}
