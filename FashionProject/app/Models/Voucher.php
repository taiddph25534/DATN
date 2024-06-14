<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $fillable = [
        'code', 'discount_type', 'discount_value', 'expiry_date', 'min_purchase_amount', 
        'point_required', 'max_usage', 'used_count', 'category_id', 'created_count', 
        'remaining_count', 'distribution_channels'
    ];
}

