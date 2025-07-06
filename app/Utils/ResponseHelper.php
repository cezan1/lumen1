<?php

namespace App\Utils;

use Illuminate\Http\JsonResponse;

class ResponseHelper
{
    /**
     * 成功响应
     *
     * @param mixed $data
     * @param string $msg
     * @param int $code
     * @return JsonResponse
     */
    public static function successResponse($data = [], $msg = '操作成功', $code = 200)
    {
        return response()->json([
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        ]);
    }

    /**
     * 错误响应
     *
     * @param string $msg
     * @param int $code
     * @return JsonResponse
     */
    public static function errorResponse($msg = '操作失败', $code = 400)
    {
        return response()->json([
            'code' => $code,
            'msg' => $msg,
            'data' => []
        ]);
    }
}