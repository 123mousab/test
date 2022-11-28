<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NutritionalNeed extends Model
{
    use HasFactory;

    const FILLABLE = ['costumer_id', 'nutration_fact_id', 'quantity'];
    protected $fillable = self::FILLABLE;

    public function Customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
