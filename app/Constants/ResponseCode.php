<?php

namespace App\Constants;

class ResponseCode
{
    // 成功码
    const SUCCESS = [200, '操作成功'];
    //账号相关
    const ACCOUNT_NOT_FOUND = [6001, '账号未找到'];
    const ACCOUNT_PASSWORD_ERROR = [6002, '账号密码错误']; 
    const ACCOUNT_EMAIL_REGISTERED = [6008, '邮箱已被注册'];
    const ACCOUNT_EMAIL_NOT_REGISTERED = [6009, '邮箱未注册'];
    const ACCOUNT_EMAIL_FORMAT_ERROR = [6010, '邮箱格式错误'];
    const ACCOUNT_PASSWORD_FORMAT_ERROR = [6011, '密码格式错误'];
    const ACCOUNT_NAME_FORMAT_ERROR = [6012, '姓名格式错误'];
    const ACCOUNT_PHONE_FORMAT_ERROR = [6013, '手机号格式错误'];
    const ACCOUNT_ADDRESS_FORMAT_ERROR = [6014, '地址格式错误'];
    const ACCOUNT_EMAIL_CODE_ERROR = [6015, '邮箱验证码错误'];
    const ACCOUNT_EMAIL_CODE_NOT_FOUND = [6016, '邮箱验证码不存在'];
    const ACCOUNT_EMAIL_CODE_EXPIRED = [6017, '邮箱验证码已过期'];
    const ACCOUNT_EMAIL_CODE_SEND_ERROR = [6018, '邮箱验证码发送失败'];
    const ACCOUNT_EMAIL_CODE_SEND_SUCCESS = [6019, '邮箱验证码发送成功'];

    // 通用错误码
    const PRODUCT_NOT_FOUND = [2001, '商品未找到'];
    const CATEGORY_NOT_FOUND = [2002, '分类未找到'];
    const CART_NOT_FOUND = [2003, '购物车未找到'];
    const ORDER_NOT_FOUND = [2004, '订单未找到'];
    const PAYMENT_NOT_FOUND = [2005, '支付未找到'];
    const USER_NOT_FOUND = [2006, '用户未找到'];
    const ADDRESS_NOT_FOUND = [2007, '地址未找到'];

    
    // 认证相关错误码
    const UNAUTHORIZED = [401, '未登录或登录状态已过期'];

    // 可按需添加更多错误码
    // const ANOTHER_ERROR = [500, '服务器内部错误'];
}