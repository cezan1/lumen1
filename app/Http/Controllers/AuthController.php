<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\Utils\RedisHelper;
use App\Utils\ResponseHelper;
use App\Constants\ResponseCode;

class AuthController extends BaseController
{
    /**
     * 用户注册
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',//每个用户的邮箱必须唯一
            'password' => 'required|string|min:6',
        ], [
            'name.required' => '姓名字段为必填项。',
            'email.required' => '邮箱字段为必填项。',
            'password.required' => '密码字段为必填项。',
            'email.unique' => '该邮箱已被注册。',//每个用户的邮箱必须唯一
            'password.min' => '密码长度不能小于6位。',//密码长度不能小于6位
            'email.email' => '邮箱格式错误。',
        ], [
            'name' => '姓名',
            'email' => '邮箱',
            'password' => '密码',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return ResponseHelper::successResponse($user);
    }

    /**
     * 用户登录
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ], [
            'email.required' => '邮箱字段为必填项。',
            'password.required' => '密码字段为必填项。',
        ]);

        $credentials = $request->only(['email', 'password']);
        if (! $token = Auth::attempt($credentials)) {
            return ResponseHelper::errorResponse(ResponseCode::PASSWORD_ERROR);
        }

        $user = Auth::user();
        RedisHelper::set("user:{$user->id}:token", $token, 1800);

        return ResponseHelper::successResponse($this->respondWithToken($token)->getData(), '登录成功');
    }

    /**
     * 获取当前用户信息
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserInfo()
    {
        return ResponseHelper::successResponse(Auth::user(), '获取用户信息成功');
    }

    /**
     * 退出登录
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $user = Auth::user();
        if ($user) {
            RedisHelper::delete("user:{$user->id}:token");
        }
        Auth::logout();
        return ResponseHelper::successResponse([], '退出登录成功');
    }

    /**
     * 刷新令牌
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        $user = Auth::user();
        $newToken = Auth::refresh();
        RedisHelper::set("user:{$user->id}:token", $newToken, 1800);
        return ResponseHelper::successResponse($this->respondWithToken($newToken)->getData(), '令牌刷新成功');
    }

    /**
     * 获取令牌响应
     *
     * @param  string $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ]);
    }
}