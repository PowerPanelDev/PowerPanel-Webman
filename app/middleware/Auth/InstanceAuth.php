<?php

namespace app\middleware\Auth;

use app\model\Instance;
use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

class InstanceAuth implements MiddlewareInterface
{
    public function process(Request $request, callable $handler): Response
    {
        $ins = Instance::with(['relationship' => function ($query) use ($request) {
            $query->where('user_id', $request->apiKey->user->id);
        }])->find($request->route->param('insId'));

        // 检查用户是否拥有实例的对应权限
        if (
            $ins->relationship
            && $ins->relationship->checkPermission($request->route->param('relationship'))
        ) {
            $request->instance = $ins;
            return $handler($request);
        } else return json(['code' => 401, 'msg' => '实例权限不足。'])->withStatus(401);
    }
}
