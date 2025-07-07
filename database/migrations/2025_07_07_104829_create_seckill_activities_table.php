<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeckillActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seckill_activities', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->comment('秒杀活动名称');
            $table->text('user_ids')->comment('参与抢购的用户ID，多个ID用逗号分隔');
            $table->unsignedBigInteger('product_id')->comment('参与的单个商品ID');
            $table->unsignedInteger('product_quantity')->default(0)->comment('活动商品数量');
            $table->timestamp('start_time')->nullable()->comment('活动开始时间');
            $table->timestamp('end_time')->nullable()->comment('活动结束时间');
            $table->tinyInteger('status')->default(0)->comment('活动状态');
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
        Schema::dropIfExists('seckill_activities');
    }
}
