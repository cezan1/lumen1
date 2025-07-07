<?php

namespace App\Constants;

class GeneralCode
{
    //抢购相关
    //抢购队列key
    const SECKILL_QUEUE = 'seckill:queue';
    //抢购队列key
    const SECKILL_LOCK = 'seckill:lock';

    // 抢购活动库存的 Redis key
    const SECKILL_STOCK_KEY = 'activity:stock';
}
