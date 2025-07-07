<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->comment('商品 ID');
            $table->unsignedBigInteger('user_id')->comment('用户 ID');
            $table->unsignedBigInteger('activity_id')->comment('活动 ID');
            $table->string('order_number', 64)->unique()->comment('订单编号');
            $table->decimal('total_amount', 10, 2)->comment('订单总金额');
            $table->string('status', 20)->comment('订单状态');
            $table->string('payment_method', 20)->nullable()->comment('支付方式');
            $table->timestamp('payment_time')->nullable()->comment('支付时间');
            $table->text('shipping_address')->comment('收货地址');
            $table->text('remark')->nullable()->comment('订单备注');
            $table->decimal('shipping_fee', 10, 2)->default(0)->comment('运费金额');
            $table->decimal('discount_amount', 10, 2)->default(0)->comment('优惠金额');
            $table->text('invoice_info')->nullable()->comment('发票信息');
            $table->text('cancel_reason')->nullable()->comment('订单取消原因');
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
        Schema::dropIfExists('orders');
    }
}
