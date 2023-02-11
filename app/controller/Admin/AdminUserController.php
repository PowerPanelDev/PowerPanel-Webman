<?php

namespace app\controller\Admin;

use app\model\Game;
use app\model\User;
use app\util\Validate;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use support\Db;
use support\Request;

class AdminUserController
{
    static public $rules = [
        'id'            => 'nullable|integer',
        'name'          => 'required|max:32',
        'password'      => 'nullable|min:6',
        'email'         => 'required|max:64',
        'is_admin'      => 'required|boolean'
    ];

    public function GetList(Request $request)
    {
        return json([
            'code' => 200,
            'data' => User::select(['id', 'name', 'email', 'is_admin', 'updated_at', 'created_at'])
                ->withCount('instances')
                ->get()
        ]);
    }

    public function Create(Request $request)
    {
        try {
            $rules = self::$rules;
            $rules['password'] = 'required|min:6';

            $data = Validate::Input($request, $rules);

            if (User::where('name', $data['name'])->orWhere('email', $data['email'])->first())
                throw new \Exception('已存在同名或同邮箱用户。', 400);

            $user = new User();
            $user->fill($data);
            $user->passwd($data['password']);
            $user->save();

            return json(['code' => 200]);
        } catch (\Throwable $th) {
            return json(['code' => $th->getCode() ?: 500, 'msg' => $th->getMessage()])->withStatus($th->getCode() ?: 500);
        }
    }

    public function GetDetail(Request $request, Int $userId)
    {
        try {
            return json([
                'code' => 200,
                'attributes' => User::findOrFail($userId)->makeHidden('password')
            ]);
        } catch (ModelNotFoundException $e) {
            return json(['code' => 400, 'msg' => '用户不存在。'])->withStatus(400);
        } catch (\Throwable $th) {
            return json(['code' => $th->getCode() ?: 500, 'msg' => $th->getMessage()])->withStatus($th->getCode() ?: 500);
        }
    }

    public function Update(Request $request, int $userId)
    {
        try {
            $data = Validate::Input($request, self::$rules);
            $user = User::findOrFail($userId)->fill($data);
            if ($data['password']) $user->passwd($data['password']);
            $user->save();

            return json(['code' => 200]);
        } catch (ModelNotFoundException $e) {
            return json(['code' => 400, 'msg' => '用户不存在。'])->withStatus(400);
        } catch (\Throwable $th) {
            return json(['code' => $th->getCode() ?: 500, 'msg' => $th->getMessage()])->withStatus($th->getCode() ?: 500);
        }
    }

    public function Delete(Request $request, int $userId)
    {
        try {
            $user = User::withCount(['instances'])->findOrFail($userId);
            if ($user->instances_count > 0) throw new \Exception('无法删除拥有实例的用户。', 400);
            $user->delete();

            // TODO 删除其他用户数据

            return json(['code' => 200]);
        } catch (ModelNotFoundException $e) {
            return json(['code' => 400, 'msg' => '用户不存在。'])->withStatus(400);
        } catch (\Throwable $th) {
            return json(['code' => $th->getCode() ?: 500, 'msg' => $th->getMessage()])->withStatus($th->getCode() ?: 500);
        }
    }
}
