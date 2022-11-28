<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class GroupName extends Model
{
    use HasFactory, HasTranslations;

    public $translatable = ['name'];

    protected $casts = [
        'status' => 'boolean'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'status'
    ];

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
        })->when(\request('status', '') != '', function ($query) {
            $query->whereIn('status', explode(',', request('status')));
        })->when(\request('fromDate', '') != '', function ($query) {
            $query->whereDate('created_at', '>=', request('fromDate'));
        })->when(\request('toDate', '') != '', function ($query) {
            $query->whereDate('created_at', '<=', request('toDate'));
        })->orderBy($sortField, $sortDirection);
    }
}
