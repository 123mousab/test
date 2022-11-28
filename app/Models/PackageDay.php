<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class PackageDay extends Model
{
    use HasFactory;

    protected $fillable = ['package_id', 'day', 'occasion', 'status'];

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

}
