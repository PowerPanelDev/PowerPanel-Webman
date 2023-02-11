<?php

namespace app\middleware\Auth;

use app\model\User;
use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

class PublicAuth implements MiddlewareInterface
{
    public function process(Request $request, callable $handler): Response
    {
        if (!$request->session()->has('uid')) return json(['code' => 401, 'msg' => '请先登录。'])->withStatus(401);
        $request->user = User::find($request->session()->get('uid'));
        return $handler($request);
    }
}
