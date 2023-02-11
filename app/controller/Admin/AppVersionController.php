<?php

namespace app\controller\Admin;

use app\model\App;
use app\model\AppVersion;
use app\util\Validate;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use support\Request;

class AppVersionController
{
    static public $rules = [
        'id'            => 'nullable|integer',
        'app_id'        => 'required|integer',
        'name'          => 'required',
        'version'       => 'required'
    ];

    public function GetList(Request $request)
    {
        return json([
            'code' => 200,
            'data' => AppVersion::select(['id', 'app_id', 'name', 'version', 'updated_at', 'created_at'])
                ->withCount('instances')
                ->with('app:id,name')
                ->get()
        ]);
    }

    public function Create(Request $request)
    {
        try {
            $data = Validate::Input($request, self::$rules);
            App::findOrFail($data['app_id']);
            AppVersion::create($data);

            return json(['code' => 200]);
        } catch (ModelNotFoundException $e) {
            return json(['code' => 400, 'msg' => '镜像不存在。'])->withStatus(400);
        } catch (\Throwable $th) {
            return json(['code' => $th->getCode() ?: 500, 'msg' => $th->getMessage()])->withStatus($th->getCode() ?: 500);
        }
    }

    public function GetDetail(Request $request, Int $versionId)
    {
        try {
            return json([
                'code' => 200,
                'attributes' => AppVersion::findOrFail($versionId)
            ]);
        } catch (ModelNotFoundException $e) {
            return json(['code' => 400, 'msg' => '版本不存在。'])->withStatus(400);
        } catch (\Throwable $th) {
            return json(['code' => $th->getCode() ?: 500, 'msg' => $th->getMessage()])->withStatus($th->getCode() ?: 500);
        }
    }

    public function Update(Request $request, int $versionId)
    {
        try {
            $data = Validate::Input($request, self::$rules);
            App::findOrFail($data['app_id']);
            AppVersion::findOrFail($versionId)->fill($data)->save();

            return json(['code' => 200]);
        } catch (ModelNotFoundException $e) {
            return json(['code' => 400, 'msg' => '镜像或版本不存在。'])->withStatus(400);
        } catch (\Throwable $th) {
            echo $th;
            return json(['code' => $th->getCode() ?: 500, 'msg' => $th->getMessage()])->withStatus($th->getCode() ?: 500);
        }
    }

    public function Delete(Request $request, int $versionId)
    {
        try {
            $version = AppVersion::withCount(['instances'])->findOrFail($versionId);
            if ($version->instances_count > 0) throw new \Exception('无法删除带有实例的版本。', 400);
            $version->delete();

            return json(['code' => 200]);
        } catch (ModelNotFoundException $e) {
            return json(['code' => 400, 'msg' => '版本不存在。'])->withStatus(400);
        } catch (\Throwable $th) {
            return json(['code' => $th->getCode() ?: 500, 'msg' => $th->getMessage()])->withStatus($th->getCode() ?: 500);
        }
    }
}
