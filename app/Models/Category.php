<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'name', 'description', 'parent_id'
    ];

    /**
     * 获取该分类下的商品
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}