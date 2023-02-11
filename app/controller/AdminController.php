<?php

namespace app\controller;

use app\model\Instance;
use app\model\InstanceStats;
use support\Request;

class AdminController
{
    public function GetData(Request $request)
    {
        return json([
            'code' => 200,
            'attributes' => [
                'node' => [
                    [
                        'id' => 1,
                        'name' => '测试1',
                        'load' => .66
                    ], [
                        'id' => 2,
                        'name' => '测试2',
                        'load' => .5
                    ]
                ],
                'instance' => [
                    'count' => [
                        'total' => Instance::count(),
                        'running' => Instance::with(['stats' => fn ($query) => $query->where('status', InstanceStats::STATUS_RUNNING)])->count()
                    ]
                ]
            ]
        ]);
    }
}
