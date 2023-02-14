<?php

namespace app\controller\Instance;

use app\class\Request;
use support\Response;
use Throwable;

class File
{
    public function GetList(Request $request): Response
    {
        try {
            return json([
                'code' => 200,
                'data' => $request->getInstance()->getFileHandler()->list($request->get('path'))
            ]);
        } catch (Throwable $th) {
            return json(['code' => $th->getCode() ?: 500, 'msg' => $th->getMessage()])->withStatus($th->getCode() ?: 500);
        }
    }

    public function Rename(Request $request)
    {
        try {
            $request->getInstance()->getFileHandler()->rename($request->post('from'), $request->post('to'));
            return json(['code' => 200]);
        } catch (Throwable $th) {
            return json(['code' => $th->getCode() ?: 500, 'msg' => $th->getMessage()])->withStatus($th->getCode() ?: 500);
        }
    }

    public function Compress(Request $request)
    {
        try {
            $request->getInstance()->getFileHandler()->compress($request->post('base'), $request->post('targets'));
            return json(['code' => 200]);
        } catch (Throwable $th) {
            return json(['code' => $th->getCode() ?: 500, 'msg' => $th->getMessage()])->withStatus($th->getCode() ?: 500);
        }
    }

    public function Decompress(Request $request)
    {
        try {
            $request->getInstance()->getFileHandler()->decompress($request->post('path'));
            return json(['code' => 200]);
        } catch (Throwable $th) {
            return json(['code' => $th->getCode() ?: 500, 'msg' => $th->getMessage()])->withStatus($th->getCode() ?: 500);
        }
    }

    public function Delete(Request $request)
    {
        try {
            $request->getInstance()->getFileHandler()->delete($request->post('base'), $request->post('targets'));
            return json(['code' => 200]);
        } catch (Throwable $th) {
            return json(['code' => $th->getCode() ?: 500, 'msg' => $th->getMessage()])->withStatus($th->getCode() ?: 500);
        }
    }

    public function GetPermission(Request $request)
    {
        try {
            return json([
                'code' => 200,
                'attributes' => [
                    'permission' => $request->getInstance()->getFileHandler()->permission($request->post('path'))
                ]
            ]);
        } catch (Throwable $th) {
            return json(['code' => $th->getCode() ?: 500, 'msg' => $th->getMessage()])->withStatus($th->getCode() ?: 500);
        }
    }

    public function SetPermission(Request $request)
    {
        try {
            $request->getInstance()->getFileHandler()->permission($request->post('path'), $request->post('permission'));
            return json([
                'code' => 200
            ]);
        } catch (Throwable $th) {
            return json(['code' => $th->getCode() ?: 500, 'msg' => $th->getMessage()])->withStatus($th->getCode() ?: 500);
        }
    }

    public function Download(Request $request)
    {
        try {
            $token = $request->getInstance()->getFileHandler()->download($request->post('path'));
            return json([
                'code' => 200,
                'attributes' => [
                    'url' => $token->node->getAddress() . '/api/public/files/download?token=' . $token->token
                ]
            ]);
        } catch (Throwable $th) {
            return json(['code' => $th->getCode() ?: 500, 'msg' => $th->getMessage()])->withStatus($th->getCode() ?: 500);
        }
    }

    public function Upload(Request $request)
    {
        try {
            $token = $request->getInstance()->getFileHandler()->upload($request->post('base'));
            return json([
                'code' => 200,
                'attributes' => [
                    'url' => $token->node->getAddress() . '/api/public/files/upload?token=' . $token->token,
                    'max_slice_size' => json_decode($token->node->addition, true)['max_upload_slice_size']
                ]
            ]);
        } catch (Throwable $th) {
            return json(['code' => $th->getCode() ?: 500, 'msg' => $th->getMessage()])->withStatus($th->getCode() ?: 500);
        }
    }

    public function Create(Request $request)
    {
        try {
            $request->getInstance()->getFileHandler()->create($request->post('base'), $request->post('type'), $request->post('name'));
            return json(['code' => 200]);
        } catch (Throwable $th) {
            return json(['code' => $th->getCode() ?: 500, 'msg' => $th->getMessage()])->withStatus($th->getCode() ?: 500);
        }
    }

    public function Read(Request $request)
    {
        try {
            return json([
                'code' => 200,
                'attributes' => [
                    'content' => base64_encode($request->getInstance()->getFileHandler()->read($request->post('path')))
                ]
            ]);
        } catch (Throwable $th) {
            return json(['code' => $th->getCode() ?: 500, 'msg' => $th->getMessage()])->withStatus($th->getCode() ?: 500);
        }
    }

    public function Save(Request $request)
    {
        try {
            $request->getInstance()->getFileHandler()->save($request->post('path'), $request->post('content'));
            return json([
                'code' => 200
            ]);
        } catch (Throwable $th) {
            return json(['code' => $th->getCode() ?: 500, 'msg' => $th->getMessage()])->withStatus($th->getCode() ?: 500);
        }
    }
}
