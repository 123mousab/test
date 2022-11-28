<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class PackageDetail extends Model
{
    use HasFactory ;
    protected $fillable =['package_id','group_id','quantity'];

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
