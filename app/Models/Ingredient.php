<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class Ingredient extends Model
{
    use HasFactory, HasTranslations;

    public $translatable = ['name', 'description'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // main => 1 مكون رئيسي ,
    // 0 => مكون غير رئيسي
    const FILLABLE = ['name','description' ,'image',
        'cost', 'unit_id', 'division_id', 'status', 'main'];

    protected $fillable = self::FILLABLE;

    /*
   |--------------------------------------------------------------------------
   | RELATIONS
   |--------------------------------------------------------------------------
   */
    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function nutriotionFacts()
    {
        return $this->belongsToMany(NutriotionFact::class, 'ingredient_nutriotion_facts')
            ->withPivot('value', 'unit_id');
    }

    public function getImageAttribute($value)
    {
        return !is_null($value) ? asset(Storage::url($value)) : '';
    }

    public function scopeFilter($query)
    {
        $sortField = \request('sort_field', 'created_at');

        if (!in_array($sortField, ['id', 'name', 'created_at'])) {
            $sortField = 'created_at';
        }

        $sortDirection = \request('sort_direction', 'asc');

        if (!in_array($sortDirection, ['desc', 'asc'])) {
            $sortDirection = 'desc';
        }

        $filled = array_filter(\request()->only([
            'name'
        ]));

        $query->when(count($filled) > 0, function ($query) use ($filled) {
            foreach ($filled as $column => $value) {
                $query->where($column, 'LIKE', '%' . $value . '%');
            }

        })->when(\request('name', '') != '', function ($query) {
            $query->where('name->en', 'like','%'.request('name').'%')
                ->orWhere('name->ar', 'like','%'.request('name').'%');
        })->when(\request('search', '') != '', function ($query) {
            $search = '%' . request('search') . '%';
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', $search);
            });
        })->when(\request('unit_id', '') != '', function ($query) {
            $query->whereIn('unit_id', Unit::query()->where('id', request('unit_id'))->pluck('id'));
        })->when(\request('division_id', '') != '', function ($query) {
            $query->whereIn('division_id', Division::query()->where('id', request('division_id'))->pluck('id'));
        })->when(\request('status', '') != '', function ($query) {
            $query->whereIn('status', explode(',', request('status')));
        })->when(\request('main', '') != '', function ($query) {
            $query->whereIn('main', explode(',', request('main')));
        })->when(\request('fromDate', '') != '', function ($query) {
            $query->whereDate('created_at', '>=', request('fromDate'));
        })->when(\request('toDate', '') != '', function ($query) {
            $query->whereDate('created_at', '<=', request('toDate'));
        })->orderBy($sortField, $sortDirection);
    }
}
