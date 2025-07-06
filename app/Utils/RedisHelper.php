<?php

namespace App\Utils;

use Predis\Client;

class RedisHelper
{
    /**
     * Redis 连接实例
     * @var \Predis\Client
     */
    protected static $redis;

    /**
     * 获取 Redis 连接实例
     * @return \Predis\Client
     */
    protected static function getRedis()
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
     * 设置键值对
     * @param string $key 键名
     * @param mixed $value 键值
     * @param int|null $expire 过期时间（秒），null 表示不过期
     * @return bool
     */
    public static function set(string $key, $value, ?int $expire = null):object
    {
        $result = self::getRedis()->set($key, $value);
        if ($expire && $result) {
            self::getRedis()->expire($key, $expire);
        }
        return $result;
    }

    /**
     * 获取键值
     * @param string $key 键名
     * @return string|null
     */
    public static function get(string $key): ?string
    {
        return self::getRedis()->get($key);
    }

    /**
     * 删除键
     * @param string|array $keys 键名或键名数组
     * @return int 删除的键数量
     */
    public static function delete($keys): int
    {
        return self::getRedis()->del($keys);
    }

    /**
     * 判断键是否存在
     * @param string $key 键名
     * @return bool
     */
    public static function exists(string $key): bool
    {
        return (bool)self::getRedis()->exists($key);
    }

    /**
     * 设置哈希字段值
     * @param string $key 哈希键名
     * @param string $field 字段名
     * @param mixed $value 字段值
     * @return int
     */
    public static function hset(string $key, string $field, $value): int
    {
        return self::getRedis()->hset($key, $field, $value);
    }

    /**
     * 获取哈希字段值
     * @param string $key 哈希键名
     * @param string $field 字段名
     * @return string|null
     */
    public static function hget(string $key, string $field): ?string
    {
        return self::getRedis()->hget($key, $field);
    }

    /**
     * 获取哈希所有字段和值
     * @param string $key 哈希键名
     * @return array
     */
    public static function hgetall(string $key): array
    {
        return self::getRedis()->hgetall($key);
    }

    /**
     * 删除哈希字段
     * @param string $key 哈希键名
     * @param string|array $fields 字段名或字段名数组
     * @return int 删除的字段数量
     */
    public static function hdel(string $key, $fields): int
    {
        return self::getRedis()->hdel($key, $fields);
    }

    /**
     * 向列表左侧添加元素
     * @param string $key 列表键名
     * @param mixed $value 元素值
     * @return int 列表长度
     */
    public static function lpush(string $key, $value): int
    {
        return self::getRedis()->lpush($key, $value);
    }

    /**
     * 从列表左侧弹出元素
     * @param string $key 列表键名
     * @return string|null
     */
    public static function lpop(string $key): ?string
    {
        return self::getRedis()->lpop($key);
    }

    /**
     * 向列表右侧添加元素
     * @param string $key 列表键名
     * @param mixed $value 元素值
     * @return int 列表长度
     */
    public static function rpush(string $key, $value): int
    {
        return self::getRedis()->rpush($key, $value);
    }

    /**
     * 从列表右侧弹出元素
     * @param string $key 列表键名
     * @return string|null
     */
    public static function rpop(string $key): ?string
    {
        return self::getRedis()->rpop($key);
    }
}