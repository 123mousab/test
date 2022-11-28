<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class Step extends Model
{
    use HasFactory, HasTranslations;

    public $translatable = ['title', 'description'];



    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    const FILLABLE = ['title', 'description', 'sequence' , 'image', 'status', 'recipie_id'];

    protected $fillable = self::FILLABLE;


//    // Array of uploadable images. These fields need to be existent in your database table
//    protected $uploadableImages = [
//        'image'
//    ];
//
//    protected $imageResizeTypes = [
//        'medium' => false,
//        'thumb'  => false
//    ];

    public function recipie()
    {
        return $this->belongsTo(Recipie::class);
    }


    public function getImageAttribute($image)
    {
        return $image? Storage::url('steps/' . $image) : null;
    }

    public function deleteImage()
    {
        if(isset($this->attributes['image']) && $this->attributes['image']) {
            Storage::delete('steps/' . $this->attributes['image']);
        }
    }
}
