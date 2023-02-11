<?php

namespace app\middleware\Auth;

use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

class AdminAuth implements MiddlewareInterface
{
    public function process(Request $request, callable $handler): Response
    {
        if (!$request->session()->has('is_admin') || !$request->session()->get('is_admin'))
            return json(['code' => 401, 'msg' => '仅管理员可查看当前页面。'])->withStatus(401);
        return $handler($request);
    }
}
