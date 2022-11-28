<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuGroupDetail extends Model
{
    use HasFactory;
    const FILLABLE = ['menu_id', 'group_id' ,'recipie_id','cuisine_id'];

    protected $fillable = self::FILLABLE;

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function recipie()
    {
        return $this->belongsTo(Recipie::class, 'recipie_id');
    }

    public function cuisine()
    {
        return $this->belongsTo(Cuisine::class, 'cuisine_id');
    }
}
