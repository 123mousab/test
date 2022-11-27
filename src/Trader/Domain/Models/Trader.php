<?php

namespace Src\Trader\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trader extends Model
{
    use HasFactory;

    protected $table = 'TRD_TRADER_TB';

    protected $fillable = [
      'trader_sid','comp_no', 'trader_name_ar','trader_name_en',
        'city_sid','trader_address', 'trader_tel_no','trader_fax_no', 'trader_mobile_no','trader_rep_id',
        'trader_rep_name','trader_rep_mobile_no', 'trader_note','insert_user', 'insert_date',
        'update_user', 'update_date','trader_name_hb', 'trader_active','trader_status', 'benf_sid'
    ];
}
