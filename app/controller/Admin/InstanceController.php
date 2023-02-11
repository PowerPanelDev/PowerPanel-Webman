<?php

namespace app\controller\Admin;

use app\model\Instance;
use support\Request;

class InstanceController
{
    public function GetList(Request $request)
    {
        return json([
            'code' => 200,
            'data' => Instance::with([
                'relationship' => fn ($query) => $query->select(['ins_id', 'user_id'])
                    ->with(['user:id,name'])
                    ->where('is_owner', 1),
                'node:id,name',
                'stats:ins_id,status',
                'app:id,name',
                'version:id,name'
            ])->get(['id', 'name', 'node_id', 'app_id', 'app_version_id', 'created_at'])
        ]);
    }
}
