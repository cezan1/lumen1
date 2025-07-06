<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use App\Utils\RedisHelper;
use App\Constants\ResponseCode;
use App\Utils\ResponseHelper;

class Authenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $guard = $guard ?? 'api';
        // 检查是否有token
        if ($this->auth->guard($guard)->guest()) {
            return ResponseHelper::errorResponse(ResponseCode::UNAUTHORIZED);
        }
        //校验token
        $user = $this->auth->guard($guard)->user();
        $token = $request->bearerToken();
        $storedToken = RedisHelper::get("user:{$user->id}:token");
        if ($token !== $storedToken) {

            return ResponseHelper::errorResponse(ResponseCode::RELOGIN);
        }
        //已登录
        return $next($request);
    }
}
