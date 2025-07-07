<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'activity_id',
        'order_number',
        'total_amount',
        'status',
        'payment_method',
        'payment_time',
        'shipping_address',
        'remark',
        'shipping_fee',
        'discount_amount',
        'invoice_info',
        'cancel_reason',
        'product_id',
    ];
}
