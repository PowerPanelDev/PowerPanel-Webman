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

    public function GetDetail(Request $request): Response
    {
        try {
            return json([
                'code' => 200,
                'attributes' => getInstance($request)->load('allocation')
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
            if (!$relationships) throw new \Exception('实例权限不足。', 401);

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
        try {;
            getInstance($request)->rename($request->post('name'));
            return json(['code' => 200]);
        } catch (Throwable $th) {
            return json(['code' => $th->getCode() ?: 500, 'msg' => $th->getMessage()])->withStatus($th->getCode() ?: 500);
        }
    }
}
