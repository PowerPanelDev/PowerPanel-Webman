<?php

namespace app\controller\Admin;

use app\model\NodeGroup;
use app\util\Validate;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use support\Request;

class NodeGroupController
{
    static public $rules = [
        'id'            => 'nullable|integer',
        'name'          => 'required',
        'description'   => 'nullable'
    ];

    public function GetList(Request $request)
    {
        return json([
            'code' => 200,
            'data' => NodeGroup::select(['id', 'name', 'created_at', 'updated_at'])
                ->withCount('nodes')
                ->withCount('instances')
                ->get()
        ]);
    }

    public function Create(Request $request)
    {
        try {
            NodeGroup::create(Validate::Input($request, self::$rules));
            return json(['code' => 200]);
        } catch (\Throwable $th) {
            return json(['code' => $th->getCode() ?: 500, 'msg' => $th->getMessage()])->withStatus($th->getCode() ?: 500);
        }
    }

    public function GetDetail(Request $request, Int $groupId)
    {
        try {
            return json([
                'code' => 200,
                'attributes' => NodeGroup::findOrFail($groupId)
            ]);
        } catch (ModelNotFoundException $e) {
            return json(['code' => 400, 'msg' => '节点组不存在。'])->withStatus(400);
        } catch (\Throwable $th) {
            return json(['code' => $th->getCode() ?: 500, 'msg' => $th->getMessage()])->withStatus($th->getCode() ?: 500);
        }
    }

    public function Update(Request $request, int $groupId)
    {
        try {
            NodeGroup::findOrFail($groupId)->fill(Validate::Input($request, self::$rules))->save();

            return json(['code' => 200]);
        } catch (ModelNotFoundException $e) {
            return json(['code' => 400, 'msg' => '节点组不存在。'])->withStatus(400);
        } catch (\Throwable $th) {
            return json(['code' => $th->getCode() ?: 500, 'msg' => $th->getMessage()])->withStatus($th->getCode() ?: 500);
        }
    }

    public function Delete(Request $request, int $groupId)
    {
        try {
            $group = NodeGroup::withCount(['nodes'])->findOrFail($groupId);
            if ($group->nodes_count > 0) throw new \Exception('无法删除带有节点的节点组。', 400);
            $group->delete();

            return json(['code' => 200]);
        } catch (ModelNotFoundException $e) {
            return json(['code' => 400, 'msg' => '节点组不存在。'])->withStatus(400);
        } catch (\Throwable $th) {
            return json(['code' => $th->getCode() ?: 500, 'msg' => $th->getMessage()])->withStatus($th->getCode() ?: 500);
        }
    }
}
