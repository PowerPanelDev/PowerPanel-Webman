<?php

namespace app\controller;

use app\model\Instance;
use app\model\InstanceStats;
use support\Db;
use support\Request;
use support\Response;

class NodeAPI
{
    public function GetList(Request $request): Response
    {
        return json([
            'code' => 200,
            'attributes' => [
                'list' => Instance::where('node_id', $request->node->id)->get()
            ]
        ]);
    }

    public function UpdateStats(Request $request): Response
    {
        try {
            $values = [];
            // 获取 UUID 和 ID 的对照表
            $chart = Instance::whereIn('uuid', array_keys($request->post()['data']))->get(['uuid', 'id'])
                ->mapWithKeys(fn ($item) => [$item->uuid => $item->id]);
            foreach ($request->post()['data'] as $uuid => $stats) {
                if (!isset($chart[$uuid])) continue;
                $values[] = ['ins_id' => $chart[$uuid]] + $stats;
            }
            InstanceStats::upsert($values, ['ins_id'], ['status', 'disk_usage']);

            // 返回节点上容量超限的容器列表
            return json([
                'code' => 200,
                'data' => Instance::select('uuid')
                    ->where('node_id', $request->node->id)
                    ->whereRelation('stats', 'disk_usage', '>', Db::raw('`disk` * 1024 * 1024'))
                    ->whereRelation('stats', 'status', InstanceStats::STATUS_RUNNING)
                    ->get()
                    ->mapWithKeys(fn ($item) => [$item->uuid])
                    ->toArray()
            ]);
        } catch (\Throwable $th) {
            return json(['code' => $th->getCode() ?: 500, 'msg' => $th->getMessage()])->withStatus($th->getCode() ?: 500);
        }
    }

    public function GetDetail(Request $request)
    {
        try {
            return json([
                'code' => 200,
                'attributes' => Instance::with(['allocation', 'allocations', 'app'])->where('uuid', $request->post()['attributes']['uuid'])->first()
            ]);
        } catch (\Throwable $th) {
            return json(['code' => $th->getCode() ?: 500, 'msg' => $th->getMessage()])->withStatus($th->getCode() ?: 500);
        }
    }
}
