<?php

namespace app\middleware\Auth;

use app\model\Node;
use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

class NodeAuth implements MiddlewareInterface
{
    public function process(Request $request, callable $handler): Response
    {
        if (
            !$request->node = Node::where('panel_token', explode(' ', $request->header('authorization'))[1])->first()
        ) return json(['code' => 401, 'msg' => '节点通信密钥错误。'])->withStatus(401);
        return $handler($request);
    }
}
