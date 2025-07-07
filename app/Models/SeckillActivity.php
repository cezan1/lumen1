<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeckillActivity extends Model
{
    use HasFactory;

    /**
     * 与模型关联的表名
     */
    protected $table = 'seckill_activities';

    /**
     * 可批量赋值的属性。
     */
    protected $fillable = ['name', 'user_ids', 'product_id', 'product_quantity', 'start_time', 'end_time', 'status'];

    /**
     * 不可批量赋值的属性。
     */
    protected $guarded = ['id'];

}