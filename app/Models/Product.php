<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'name', 'description', 'price', 'stock', 'image', 'status', 'category_id'
    ];

    /**
     * 获取商品所属的分类
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}