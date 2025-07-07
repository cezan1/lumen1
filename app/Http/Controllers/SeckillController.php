<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Utils\RedisHelper;
use App\Utils\ResponseHelper;
use App\Constants\ResponseCode;
use App\Constants\GeneralCode;
use App\Models\Order;
use App\Services\OrderService;

class SeckillController extends BaseController
{
    /**
     * 抢购活动库存的 Redis key
     * @param \App\Models\SeckillActivity $seckillActivity
     * @param \App\Models\Product $product
     * @return string
     */
    private function getActivityKey($activityId, $productId): string
    {
        return GeneralCode::SECKILL_STOCK_KEY . ':' . $activityId . ':' . $productId;
    }

    /**
     * 用户成功下单的 Redis key
     * @param int $productId 商品 ID
     * @param int $userId 用户 ID
     * @return string Redis key
     */
    public function getOrderUserKey(int $productId, int $userId): string
    {
        return "seckill:order:$productId:$userId";
    }

    /**
     * 商品抢购接口
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSeckill(Request $request)
    {
        //获取活动id
        $activityId = $request->input('activity_id');
        if (!$activityId) {
            return ResponseHelper::errorResponse('活动id不能为空', 400);
        }
        // 根据活动id获取活动商品
        $activity = \App\Models\SeckillActivity::find($activityId);
        if (!$activity) {
            return ResponseHelper::errorResponse('活动不存在', 400);
        }

        $productId = $activity->product_id;
        //获取登录态的用户id
        $userId = $request->user()->id;
        $product = \App\Models\Product::find($productId);

        // 检查参数是否存在
        if (!$productId || !$userId || !$product || $product->stock <= 0) {
            return ResponseHelper::errorResponse('活动参与有误', 400);
        }

        // 检查用户是否已经抢购过
        $userOrderKey = $this->getOrderUserKey($productId, $userId);
        if (RedisHelper::exists($userOrderKey)) {
            return ResponseHelper::errorResponse('您已经参与过本次抢购，请勿重复操作', 403);
        }

        // 检查redis 活动库存 key是否存在
        $stockKey = $this->getActivityKey($activityId, $productId);
        if (!RedisHelper::exists($stockKey)) {
            return ResponseHelper::errorResponse('商品不存在或活动未开启', 404);
        }

        try {
            // 使用 Redis 原子性操作扣减库存
            $result = RedisHelper::getRedis()->decr($stockKey);
            if ($result >= 0) {

                RedisHelper::set($userOrderKey, 1);
                // 库存扣减成功，创建订单记录 向redis队列中添加抢购信息
                $data = [
                    'user_id' => $userId,
                    'product_id' => $productId,
                    'activity_id' => $activityId,
                ];
                //写入队列
                RedisHelper::lpush(GeneralCode::SECKILL_QUEUE, json_encode($data));

                return ResponseHelper::successResponse([
                    'product_id' => $productId,
                    'user_id' => $userId,
                ], '抢购成功', 200);
            } else {
                // 库存不足，恢复库存
                RedisHelper::getRedis()->incr($stockKey);
                return ResponseHelper::errorResponse('库存不足，抢购失败', 429);
            }
        } catch (\Exception $e) {
            // 发生异常，恢复库存
            RedisHelper::getRedis()->incr($stockKey);
            return ResponseHelper::errorResponse('抢购过程中发生错误: ' . $e->getMessage(), 500);
        }
    }

    /**
     * 查询抢购成功订单接口
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSuccessfulSeckillOrders(Request $request)
    {
        // 此处应添加查询逻辑，如根据用户 ID 查询抢购成功的订单
        // 当前仅返回示例数据，实际开发中需要实现数据库查询
        return ResponseHelper::errorResponse('查询抢购成功订单功能待完善', 500);
    }

    /**
     * 启动抢购活动接口
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function startSeckillActivity(Request $request)
    {
        $activityId = $request->input('activity_id');
        if (!$activityId) {
            return ResponseHelper::errorResponse('活动 ID 不能为空', 400);
        }

        try {
            $seckillActivity = \App\Models\SeckillActivity::find($activityId);
            if (!$seckillActivity) {
                return ResponseHelper::errorResponse('抢购活动不存在', 404);
            }

            $product = \App\Models\Product::find($seckillActivity->product_id);
            if (!$product) {
                return ResponseHelper::errorResponse('关联商品不存在', 404);
            }
            $redisKey = $this->getActivityKey($activityId, $seckillActivity->product_id);
            $stock = $seckillActivity->product_quantity;
            RedisHelper::set($redisKey, $stock, 1800);

            return ResponseHelper::successResponse([], '抢购活动启动成功', 200);
        } catch (\Exception $e) {
            return ResponseHelper::errorResponse('启动抢购活动失败: ' . $e->getMessage(), 500);
        }
    }
}
