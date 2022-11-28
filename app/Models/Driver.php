<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    const FILLABLE = ['name','status', 'mobile', 'email'];
    protected $fillable = self::FILLABLE;
}
