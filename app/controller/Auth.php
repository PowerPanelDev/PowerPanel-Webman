<?php

namespace app\controller;

use app\class\Request;
use app\model\User;
use app\util\Validate;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use support\Model;
use support\Response;

class Auth extends Model
{
    public function GetStatus(Request $request): Response
    {
        $session = $request->session();

        return json([
            'code' => 200,
            'status' => $session->has('uid'),
            'attributes' => ($session->has('uid') ? [
                'csrf' => $session->get('csrf'),
                'uid' => $session->get('uid'),
                'name' => $session->get('name'),
                'is_admin' => $session->get('is_admin')
            ] : [])
        ]);
    }

    public function Login(Request $request)
    {
        try {
            $data = $request->validate([
                'name'      => 'required',
                'password'  => 'required|min:6'
            ]);

            $user = User::wherePassword($data['password'])->where('name', $data['name'])->firstOrFail();

            $session = $request->session();
            $session->put([
                'csrf' => substr(md5(uniqid()), 0, 8),
                'uid' => $user->id,
                'name' => $user->name,
                'is_admin' => $user->is_admin
            ]);

            return json([
                'code' => 200
            ]);
        } catch (ModelNotFoundException $e) {
            return json(['code' => 400, 'msg' => '用户名或密码错误。'])->withStatus(400);
        } catch (\Throwable $th) {
            return json(['code' => $th->getCode(), 'msg' => $th->getMessage()])->withStatus($th->getCode());
        }
    }

    public function Logout(Request $request)
    {
        try {
            $request->session()->flush();

            return json(['code' => 200]);
        } catch (\Throwable $th) {
            return json(['code' => $th->getCode(), 'msg' => $th->getMessage()])->withStatus($th->getCode());
        }
    }
}
