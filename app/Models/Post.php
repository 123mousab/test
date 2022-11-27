<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content', 'category_id'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeFilter($query)
    {
        $query->when(request('search_category'), function ($query) {
            $query->where('category_id', request('search_category'));
        })->when(request('search_id'), function ($query) {
                $query->where('id', request('search_id'));
        })->when(request('search_title'), function ($query) {
                $query->where('title', 'like', '%' . request('search_title') . '%');
        })->when(request('search_content'), function ($query) {
                $query->where('content', 'like', '%' . request('search_content') . '%');
        });
    }

    public function scopeSearch($query)
    {
        $query ->when(request('search_global'), function ($query) {
            $query->where(function ($q) {
                $q->where('id', request('search_global'))
                    ->orWhere('title', 'like', '%' . request('search_global') . '%')
                    ->orWhere('content', 'like', '%' . request('search_global') . '%');
            });
        });
    }

    public function scopeOrder($query)
    {
        $orderColumn = request('order_column', 'created_at');
        if (!in_array($orderColumn, ['id', 'title', 'created_at'])) {
            $orderColumn = 'created_at';
        }
        $orderDirection = request('order_direction', 'desc');
        if (!in_array($orderDirection, ['asc', 'desc'])) {
            $orderDirection = 'desc';
        }

        $query->orderBy($orderColumn, $orderDirection);
    }
}
