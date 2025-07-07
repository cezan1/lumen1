<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Utils\RedisHelper;
use App\Services\OrderService;
use App\Models\Product;
use App\Models\SeckillActivity;
use App\Utils\ResponseHelper;
use App\Constants\ResponseCode;
use App\Constants\GeneralCode;
use Illuminate\Support\Facades\Log;
use Predis\Client;
use Illuminate\Support\Facades\DB;

class ProcessSeckillQueue implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;
    /**
     * Redis 连接实例
     * @var \Predis\Client
     */
    protected static $redis;

    //mysql连接实例
    protected static $mysql;

    /**
     * 获取 Redis 连接实例
     * @return \Predis\Client
     */
    protected static function getRedis(): object
    {
        if (!self::$redis) {
            self::$redis = new Client([
                'scheme' => 'tcp',
                'host'   => '127.0.0.1',
                'port'   => 6379,
            ]);
        }
        return self::$redis;
    }

    /**
     * 获取mysql连接实例
     * @return \Illuminate\Database\Connection
     */
    protected static function getMysql(): object
    {
        if (!self::$mysql) {
            self::$mysql = DB::connection();
        }
        return self::$mysql;
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //连接redis
        $redisConn = self::getRedis();
        //连接mysql
        $mysqlConn = self::getMysql();
        Log::info('连接redis', ['redisConn' => $redisConn]);
        while (true) {
            //获取队列数据
            $data = $redisConn->blpop(GeneralCode::SECKILL_QUEUE, 1);
            $data = json_decode($data[1], true);
            // Log::info('队列数据:', ['data' => $data]);
            if (!$data) {
                Log::error('Seckill queue 获取队列数据失败');
                continue;
            }
            $userId = $data['user_id'] ?? null;
            $productId = $data['product_id'] ?? null;
            $activityId = $data['activity_id'] ?? null;
            // Log::info('队列获取数据成功:');
            // Log::info("user_id:" . $userId . "product_id:" . $productId . "activity_id:" . $activityId);
            if (!$userId && !$productId && !$activityId) {
                continue;
            }
            try {
                // 获取商品和活动信息
                $product = $mysqlConn->table('products')->where('id', $productId)->first();
                $seckillActivity = $mysqlConn->table('seckill_activities')->where('id', $activityId)->first();
                //redis活动商品key
                $stockKey = "activity:stock:" . $activityId . ':' . $productId;
                // log::info('活动库存key', ['stockKey' => $stockKey]);
                if ($product && $seckillActivity) {
                    // 扣减redis库存
                    if ($seckillActivity->product_quantity > 0) {
                        // 检查redis库存
                        $stock = $redisConn->get($stockKey);
                        Log::warning('秒杀活动库存 stock: ' . $stock);
                        if ($stock <= 0) {
                            Log::warning('秒杀活动已售罄，活动 ID: ' . $activityId);
                            continue; // 跳过当前循环，继续处理下一个订单
                        }
                        //活动表库存-1
                        $update = $mysqlConn->table('seckill_activities')->where('id', $activityId)->update(
                            ['product_quantity' => $seckillActivity->product_quantity - 1]
                        );
                        Log::info('秒杀活动更新库存: ' . $update);

                        // 创建订单 mysql
                        $orderNumber = "od_" . time() . rand(1000, 9999);
                        $order = $mysqlConn->table('orders')->insert([
                            'user_id' => $userId,
                            'product_id' => $productId,
                            'activity_id' => $activityId,
                            'order_number' => $orderNumber,
                            'total_amount' => $product->price,
                            'status' => 1,
                            'shipping_address' => '测试地址',
                            'remark' => '抢购活动',
                            'shipping_fee' => 0,
                            'discount_amount' => 0,
                            'invoice_info' => '无',
                            'cancel_reason' => '无',
                        ]);
                        Log::info('秒杀订单创建成功', ['order' => $order]);
                    }
                }
            } catch (\Exception $e) {
                // 记录错误日志，可根据实际需求添加日志记录逻辑
                Log::error('处理订单-队列失败: ' . $e->getMessage());
            }
        }
    }
}
