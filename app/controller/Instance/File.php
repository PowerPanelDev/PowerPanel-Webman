<?php

namespace app\controller\Instance;

use support\Request;
use support\Response;
use Throwable;

class File
{
    public function GetList(Request $request): Response
    {
        try {
            return json([
                'code' => 200,
                'data' => getInstance($request)->getFileHandler()->list($request->get('path'))
            ]);
        } catch (Throwable $th) {
            return json(['code' => $th->getCode() ?: 500, 'msg' => $th->getMessage()])->withStatus($th->getCode() ?: 500);
        }
    }

    public function Rename(Request $request)
    {
        try {
            getInstance($request)->getFileHandler()->rename($request->post('from'), $request->post('to'));
            return json(['code' => 200]);
        } catch (Throwable $th) {
            return json(['code' => $th->getCode() ?: 500, 'msg' => $th->getMessage()])->withStatus($th->getCode() ?: 500);
        }
    }

    public function Compress(Request $request)
    {
        try {
            getInstance($request)->getFileHandler()->compress($request->post('base'), $request->post('targets'));
            return json(['code' => 200]);
        } catch (Throwable $th) {
            return json(['code' => $th->getCode() ?: 500, 'msg' => $th->getMessage()])->withStatus($th->getCode() ?: 500);
        }
    }

    public function Decompress(Request $request)
    {
        try {
            getInstance($request)->getFileHandler()->decompress($request->post('path'));
            return json(['code' => 200]);
        } catch (Throwable $th) {
            return json(['code' => $th->getCode() ?: 500, 'msg' => $th->getMessage()])->withStatus($th->getCode() ?: 500);
        }
    }

    public function Delete(Request $request)
    {
        try {
            getInstance($request)->getFileHandler()->delete($request->post('base'), $request->post('targets'));
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
                    'permission' => getInstance($request)->getFileHandler()->permission($request->post('path'))
                ]
            ]);
        } catch (Throwable $th) {
            return json(['code' => $th->getCode() ?: 500, 'msg' => $th->getMessage()])->withStatus($th->getCode() ?: 500);
        }
    }

    public function SetPermission(Request $request)
    {
        try {
            getInstance($request)->getFileHandler()->permission($request->post('path'), $request->post('permission'));
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
            $token = getInstance($request)->getFileHandler()->download($request->post('path'));
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
            $token = getInstance($request)->getFileHandler()->upload($request->post('base'));
            return json([
                'code' => 200,
                'attributes' => [
                    'url' => $token->node->getAddress() . '/api/public/files/upload?token=' . $token->token,
                    'max_slice_size' => $token->node->max_upload_slice_size
                ]
            ]);
        } catch (Throwable $th) {
            return json(['code' => $th->getCode() ?: 500, 'msg' => $th->getMessage()])->withStatus($th->getCode() ?: 500);
        }
    }

    public function Create(Request $request)
    {
        try {
            getInstance($request)->getFileHandler()->create($request->post('base'), $request->post('type'), $request->post('name'));
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
                    'content' => base64_encode(getInstance($request)->getFileHandler()->read($request->post('path')))
                ]
            ]);
        } catch (Throwable $th) {
            return json(['code' => $th->getCode() ?: 500, 'msg' => $th->getMessage()])->withStatus($th->getCode() ?: 500);
        }
    }

    public function Save(Request $request)
    {
        try {
            getInstance($request)->getFileHandler()->save($request->post('path'), $request->post('content'));
            return json([
                'code' => 200
            ]);
        } catch (Throwable $th) {
            return json(['code' => $th->getCode() ?: 500, 'msg' => $th->getMessage()])->withStatus($th->getCode() ?: 500);
        }
    }
}
