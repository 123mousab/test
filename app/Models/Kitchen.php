<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kitchen extends Model
{
    use HasFactory;
    const FILLABLE = ['cooking_date'];
    protected $fillable = self::FILLABLE;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'cooking_date',
    ];

    public function kithenDetails()
    {
        return $this->hasMany(KitchenDetail::class);
    }

    public function breakfast()
    {
        return $this->kithenDetails()->where('group_id', 100);
    }

    public function soups()
    {
        return $this->kithenDetails()->where('group_id', 200);
    }

    public function salad()
    {
        return $this->kithenDetails()->where('group_id', 300);
    }

    public function meal1()
    {
        return $this->kithenDetails()->where('group_id', 400);
    }

    public function meal2()
    {
        return $this->kithenDetails()->where('group_id', 500);
    }
}
