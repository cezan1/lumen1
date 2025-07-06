<?php

namespace App\Utils;

use Illuminate\Http\JsonResponse;
use App\Constants\ResponseCode;

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
    public static function successResponse($data = [], $msg = ResponseCode::SUCCESS[1], $code = ResponseCode::SUCCESS[0])
    {
        if (is_array($msg)) {
            $code = $msg[0];
            $msg = $msg[1];
        }
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
    public static function errorResponse($msg = ResponseCode::FAIL[1], $code = ResponseCode::FAIL[0])
    {
        if (is_array($msg)) {
            $code = $msg[0];
            $msg = $msg[1];
        }
        return response()->json([
            'code' => $code,
            'msg' => $msg,
            'data' => []
        ]);
    }
}