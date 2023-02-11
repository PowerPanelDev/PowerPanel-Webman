<?php

namespace app\middleware;

use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

class CSRFValidate implements MiddlewareInterface
{
    public function process(Request $request, callable $handler): Response
    {
        if (
            $request->session()->has('csrf') &&
            ($request->post('csrf') == $request->session()->get('csrf') || $request->get('csrf') == $request->session()->get('csrf'))
        ) return $handler($request);
        return json(['code' => 401, 'msg' => 'CSRF 校验未通过。']);
    }
}
