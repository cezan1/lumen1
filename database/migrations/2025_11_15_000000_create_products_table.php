<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->comment('商品名称');
            $table->text('description')->nullable()->comment('商品描述');
            $table->decimal('price', 10, 2)->comment('商品价格');
            $table->integer('stock')->default(0)->comment('库存数量');
            $table->string('image')->nullable()->comment('商品图片');
            $table->unsignedTinyInteger('status')->default(1)->comment('商品状态，1-上架，2-下架');
            $table->unsignedBigInteger('category_id')->nullable()->comment('分类 ID');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}