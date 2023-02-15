<?php

namespace app\middleware\Auth;

use app\model\ApiKey;
use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

class APIAuth implements MiddlewareInterface
{
    public function process(Request $request, callable $handler): Response
    {
        if ($request->header('Authorization')) {
            // API 方式
            [$type, $key] = explode(' ', $request->header('Authorization'));
            if ($type != 'Bearer')
                return json(['code' => 400, 'msg' => '不支持此类型的密钥。'])->withStatus(400);

            // 从数据库加载 ApiKey
            $apiKey = ApiKey::with('user.permission')->whereToken(hash('sha512', $key . getenv('APP_SALT')))->first();
            if (!$apiKey)
                return json(['code' => 401, 'msg' => '密钥错误。'])->withStatus(401);
        } else {
            // Session 方式
            $session = $request->session();

            // 检查 CSRF
            if (!$request->session()->has('csrf') || $request->input('csrf') != $request->session()->get('csrf'))
                return json(['code' => 401, 'msg' => 'CSRF 校验未通过。'])->withStatus(401);

            if (!$session->has('uid'))
                return json(['code' => 401, 'msg' => '请先登录。'])->withStatus(401);

            // 虚构一个 ApiKey 对象
            $apiKey = new ApiKey();
            $apiKey->user_id = $session->get('uid');
            $apiKey->load('user.permission');
        }

        if (!$apiKey->checkPermission($request->route->param('permission')))
            return json(['code' => 401, 'msg' => '缺少权限：' . $request->route->param('permission') . '。'])->withStatus(401);

        $request->apiKey = $apiKey;
        return $handler($request);
    }
}
