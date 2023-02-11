<?php

namespace app\middleware;

use app\model\Instance;
use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

class InstanceAuth implements MiddlewareInterface
{
    public function process(Request $request, callable $handler): Response
    {
        $ins = Instance::with(['relationship' => function ($query) use ($request) {
            $query->where('user_id', getUser($request)->id);
        }])->find($request->route->param('insId'));

        if ($ins->relationship && $ins->relationship->checkPermission()) {
            $request->instance = $ins;
            return $handler($request);
        } else return json(['code' => 401, 'msg' => '权限不足。'])->withStatus(401);
    }
}
