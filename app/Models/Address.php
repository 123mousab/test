<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    const FILLABLE = ['customer_id', 'city_id', 'branch_id', 'street_name', 'place_work','group','image','notes','status','default'];

    protected $fillable = self::FILLABLE;


    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function city()
    {
        return $this->belongsTo(City::class);
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
