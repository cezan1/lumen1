<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Utils\ResponseHelper;

class OrderService
{
    /**
     * 创建订单方法
     * @param int $userId 用户 ID
     * @param int $productId 商品 ID
     * @param int $activityId 活动 ID
     * @return Order|\Illuminate\Http\JsonResponse
     */
    public static function createOrder(int $userId, int $productId, int $activityId)
    {
        try {
            // 生成订单编号，使用时间戳加随机数确保唯一性
            $orderNumber = time() . rand(1000, 9999);
            
            // 此处可根据实际业务逻辑计算订单总金额，示例中简单根据商品价格计算，后续可扩展
            $product = Product::find($productId);
            $totalAmount = $product ? $product->price : 0;

            $orderData = [
                'user_id' => $userId,
                'activity_id' => $activityId,
                'order_number' => $orderNumber,
                'total_amount' => $totalAmount,
                'status' => 'unpaid',
                'payment_method' => null,
                'payment_time' => null,
                'shipping_address' => null,
                'remark' => "活动 ID 为 $activityId 的抢购订单",
                'shipping_fee' => 0,
                'discount_amount' => 0,
                'invoice_info' => null,
                'cancel_reason' => null,
            ];

            $order = Order::create($orderData);
            return $order;
        } catch (\Exception $e) {
            return ResponseHelper::errorResponse('创建订单失败: ' . $e->getMessage(), 500);
        }
    }
}