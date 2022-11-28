<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use KingOfCode\Upload\Uploadable;
use Spatie\Translatable\HasTranslations;

class Recipie extends Model
{
    use HasFactory, HasTranslations;

    public $translatable = ['name', 'description'];


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    const FILLABLE = ['name','description', 'total_cost', 'group_id', 'ingredient_primary_id',
        'image', 'protein', 'carb', 'status'];

    protected $fillable = self::FILLABLE;

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function tools()
    {
        return $this->belongsToMany(Tool::class);
    }

    public function cuisines()
    {
        return $this->belongsToMany(Cuisine::class);
    }

    public function steps()
    {
        return $this->hasMany(Step::class);
    }

    // المكونات الاساسية التي تعتمد عليها الوجبات
    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class, 'ingredient_primary_id');
    }

    // هادي العلاقة للمكونات الغير اساسية
    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'recipie_ingeredients')
            ->withPivot('quantity', 'line_cost');
    }

    public function getTotalCostAttribute()
    {
        return $this->ingredients->sum(function ($ingredient) {
            return $ingredient->pivot->quantity * $ingredient->cost;
        });
    }

    public function getImageAttribute($value)
    {
        return !is_null($value) ? asset(Storage::url($value)) : '';
    }

    public function scopeFilter($query)
    {
        $sortField = \request('sort_field', 'created_at');

        if (!in_array($sortField, ['id', 'name', 'total_price', 'status', 'created_at'])) {
            $sortField = 'created_at';
        }

        $sortDirection = \request('sort_direction', 'desc');

        if (!in_array($sortDirection, ['desc', 'asc'])) {
            $sortDirection = 'desc';
        }

        $filled = array_filter(\request()->only([
            'total_price',
        ]));

        $query->when(count($filled) > 0, function ($query) use ($filled) {
            foreach ($filled as $column => $value) {
                $query->where($column, 'LIKE', '%' . $value . '%');
            }

        })->when(\request('group', '') != '', function ($query) {
            $query->whereHas('group', function ($query) {
                return $query->whereIn('group_id', explode(',', request('group')));
            })->with('group');
        })->when(\request('group_id', '') != '', function ($query) {
            $query->whereIn('group_id', Group::query()->whereIn('id', request('group_id'))->pluck('id'));
        })->when(\request('ingredient_primary_id', '') != '', function ($query) {
            $query->whereIn('ingredient_primary_id', Ingredient::query()->where('main', 1)
                ->whereIn('id', request('ingredient_primary_id'))->pluck('id'));
        })->when(\request('name', '') != '', function ($query) {
            $query->where('name->en', 'like','%'.request('name').'%')
                ->orWhere('name->ar', 'like','%'.request('name').'%');
        })->when(\request('search', '') != '', function ($query) {
            $search = '%' . request('search') . '%';
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', $search);
            });
        })->when(\request('status', '') != '', function ($query) {
            $query->whereIn('status', explode(',', request('status')));
        })->when(\request('fromDate', '') != '', function ($query) {
            $query->whereDate('created_at', '>=', request('fromDate'));
        })->when(\request('toDate', '') != '', function ($query) {
            $query->whereDate('created_at', '<=', request('toDate'));
        })->orderBy($sortField, $sortDirection);
    }

    public function getIsProteinTextAttribute()
    {
        if ($this->ingredient_primary_id != null && $this->protein == 1)
        {
            return 'بروتين';
        }elseif ($this->ingredient_primary_id != null && $this->carb == 1)
        {
            return 'كارب';
        }else{
            return '';
        }
    }
}
