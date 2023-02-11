<?php

namespace app\controller;

use app\handler\Instance\TokenHandler;
use support\Request;
use support\Response;
use Throwable;

class Instance
{
    public function GetList(Request $request): Response
    {
        return json([
            'code' => 200,
            'data' => getUser($request)->instances()->with(['stats' => function ($query) {
                $query->select(['ins_id', 'status']);
            }])->get()
        ]);
    }

    // TODO 检查权限
    public function GetDetail(Request $request): Response
    {
        try {
            $instance = getInstance($request);
            if (!$instance->relationship->checkPermission('detail')) throw new \Exception('此账号无权操作此实例。', 401);
            return json([
                'code' => 200,
                'attributes' => $instance->load('allocation')
            ]);
        } catch (\Throwable $th) {
            return json(['code' => $th->getCode() ?: 500, 'msg' => $th->getMessage()])->withStatus($th->getCode() ?: 500);
        }
    }

    public function GetConsole(Request $request): Response
    {
        try {
            $instance = getInstance($request);

            $relationships = $instance->relationship->checkPermission([
                'console.status.get',
                'console.status.set',
                'console.history',
                'console.read',
                'console.stats',
                'console.write'
            ]);
            if (!$relationships) throw new \Exception('此账号无权连接控制台。', 401);

            $token = $instance
                ->getTokenHandler()
                ->generate(TokenHandler::TYPE_WS, $relationships, ['instance' => $instance->uuid]);
            return json([
                'code' => 200,
                'attributes' => [
                    'endpoint' => ($token->node->enable_tls) ? 'wss://' : 'ws://' . $token->node->endpoint . '/ws/console',
                    'token' => $token->token
                ]
            ]);
        } catch (\Throwable $th) {
            return json(['code' => $th->getCode(), 'msg' => $th->getMessage()])->withStatus($th->getCode());
        }
    }

    public function Rename(Request $request)
    {
        try {
            $instance = getInstance($request);
            if (!$instance->relationship->checkPermission('rename'))
                throw new \Exception('此账号无权操作此实例。', 401);
            $instance->rename($request->post('name'));
            return json(['code' => 200]);
        } catch (Throwable $th) {
            return json(['code' => $th->getCode() ?: 500, 'msg' => $th->getMessage()])->withStatus($th->getCode() ?: 500);
        }
    }
}
