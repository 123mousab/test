<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KitchenDetail extends Model
{
    use HasFactory;

    const FILLABLE = ['kitchen_id', 'group_id' ,'recipie_id','cuisine_id'];
    protected $fillable = self::FILLABLE;


    public function kitchen()
    {
        return $this->belongsTo(Kitchen::class);
    }
    public function group()
    {
        return $this->belongsTo(Group::class);
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
