<?php

namespace app\controller\Admin;

use app\class\Request;
use app\model\Node;
use app\model\NodeGroup;
use app\util\Validate;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class NodeController
{
    static public $rules = [
        'id'                    => 'nullable|integer',
        'name'                  => 'required',
        'description'           => 'nullable',
        'host'                  => 'required',
        'api_port'              => 'required|integer|between:1,65535',
        'ws_port'               => 'required|integer|between:1,65535',
        'enable_tls'            => 'required|boolean',
        'os'                    => 'in:linux,windows',
        'memory'                => 'required|integer',
        'memory_overallocate'   => 'required|integer',
        'disk'                  => 'required|integer',
        'disk_overallocate'     => 'required|integer',
        'node_group_id'         => 'required|integer'
    ];

    public function GetList(Request $request)
    {
        return json([
            'code' => 200,
            'data' => Node::select([
                'id', 'node_group_id', 'os', 'name', 'memory', 'memory_overallocate', 'disk', 'disk_overallocate', 'updated_at'
            ])
                ->withSum('instances', 'memory')
                ->withSum('instances', 'disk')
                ->withCount('instances')
                ->with('group:id,name')
                ->get()
        ]);
    }

    public function Create(Request $request)
    {
        try {
            $data = $request->validate(self::$rules);

            NodeGroup::findOrFail($data['node_group_id']);

            $node = new Node($request->validate(self::$rules));
            $node->genToken();
            $node->save();

            return json(['code' => 200]);
        } catch (ModelNotFoundException $e) {
            return json(['code' => 400, 'msg' => '节点组不存在。'])->withStatus(400);
        } catch (\Throwable $th) {
            return json(['code' => $th->getCode() ?: 500, 'msg' => $th->getMessage()])->withStatus($th->getCode() ?: 500);
        }
    }

    public function GetDetail(Request $request, int $nodeId)
    {
        try {
            return json([
                'code' => 200,
                'attributes' => Node::findOrFail($nodeId)
            ]);
        } catch (ModelNotFoundException $e) {
            return json(['code' => 400, 'msg' => '节点不存在。'])->withStatus(400);
        } catch (\Throwable $th) {
            return json(['code' => $th->getCode() ?: 500, 'msg' => $th->getMessage()])->withStatus($th->getCode() ?: 500);
        }
    }

    public function Update(Request $request, int $nodeId)
    {
        try {
            $data = $request->validate(self::$rules);

            NodeGroup::findOrFail($data['node_group_id']);
            Node::findOrFail($nodeId)->fill($data)->save();

            return json([
                'code' => 200
            ]);
        } catch (ModelNotFoundException $e) {
            return json(['code' => 400, 'msg' => '节点或节点组不存在。'])->withStatus(400);
        } catch (\Throwable $th) {
            return json(['code' => $th->getCode() ?: 500, 'msg' => $th->getMessage()])->withStatus($th->getCode() ?: 500);
        }
    }

    public function Delete(Request $request, int $nodeId)
    {
        try {
            $node = Node::withCount('instances')->find($nodeId);
            if ($node->instances_count > 0) throw new \Exception('无法删除带有节点的节点组。', 400);
            $node->delete();

            return json([
                'code' => 200
            ]);
        } catch (ModelNotFoundException $e) {
            return json(['code' => 400, 'msg' => '节点不存在。'])->withStatus(400);
        } catch (\Throwable $th) {
            return json(['code' => $th->getCode() ?: 500, 'msg' => $th->getMessage()])->withStatus($th->getCode() ?: 500);
        }
    }
}
