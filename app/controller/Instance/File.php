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
            $instance = getInstance($request);
            if (!$instance->relationship->checkPermission('file.list'))
                throw new \Exception('此账号无权操作此实例。', 401);
            return json([
                'code' => 200,
                'data' => $instance->getFileHandler()->list($request->get('path'))
            ]);
        } catch (Throwable $th) {
            return json(['code' => $th->getCode() ?: 500, 'msg' => $th->getMessage()])->withStatus($th->getCode() ?: 500);
        }
    }

    public function Rename(Request $request)
    {
        try {
            $instance = getInstance($request);
            if (!$instance->relationship->checkPermission('file.rename'))
                throw new \Exception('此账号无权操作此实例。', 401);
            $instance->getFileHandler()->rename($request->post('from'), $request->post('to'));
            return json(['code' => 200]);
        } catch (Throwable $th) {
            return json(['code' => $th->getCode() ?: 500, 'msg' => $th->getMessage()])->withStatus($th->getCode() ?: 500);
        }
    }

    public function Compress(Request $request)
    {
        try {
            $instance = getInstance($request);
            if (!$instance->relationship->checkPermission('file.compress'))
                throw new \Exception('此账号无权操作此实例。', 401);
            $instance->getFileHandler()->compress($request->post('base'), $request->post('targets'));
            return json(['code' => 200]);
        } catch (Throwable $th) {
            return json(['code' => $th->getCode() ?: 500, 'msg' => $th->getMessage()])->withStatus($th->getCode() ?: 500);
        }
    }

    public function Decompress(Request $request)
    {
        try {
            $instance = getInstance($request);
            if (!$instance->relationship->checkPermission('file.decompress'))
                throw new \Exception('此账号无权操作此实例。', 401);
            $instance->getFileHandler()->decompress($request->post('path'));
            return json(['code' => 200]);
        } catch (Throwable $th) {
            return json(['code' => $th->getCode() ?: 500, 'msg' => $th->getMessage()])->withStatus($th->getCode() ?: 500);
        }
    }

    public function Delete(Request $request)
    {
        try {
            $instance = getInstance($request);
            if (!$instance->relationship->checkPermission('file.delete'))
                throw new \Exception('此账号无权操作此实例。', 401);
            $instance->getFileHandler()->delete($request->post('base'), $request->post('targets'));
            return json(['code' => 200]);
        } catch (Throwable $th) {
            return json(['code' => $th->getCode() ?: 500, 'msg' => $th->getMessage()])->withStatus($th->getCode() ?: 500);
        }
    }

    public function GetPermission(Request $request)
    {
        try {
            $instance = getInstance($request);
            if (!$instance->relationship->checkPermission('file.permission.get'))
                throw new \Exception('此账号无权操作此实例。', 401);
            return json([
                'code' => 200,
                'attributes' => [
                    'permission' => $instance->getFileHandler()->permission($request->post('path'))
                ]
            ]);
        } catch (Throwable $th) {
            return json(['code' => $th->getCode() ?: 500, 'msg' => $th->getMessage()])->withStatus($th->getCode() ?: 500);
        }
    }

    public function SetPermission(Request $request)
    {
        try {
            $instance = getInstance($request);
            if (!$instance->relationship->checkPermission('file.permission.get'))
                throw new \Exception('此账号无权操作此实例。', 401);
            $instance->getFileHandler()->permission($request->post('path'), $request->post('permission'));
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
            $instance = getInstance($request);
            if (!$instance->relationship->checkPermission('file.download'))
                throw new \Exception('此账号无权操作此实例。', 401);
            $token = $instance->getFileHandler()->download($request->post('path'));
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
            $instance = getInstance($request);
            if (!$instance->relationship->checkPermission('file.upload'))
                throw new \Exception('此账号无权操作此实例。', 401);
            $token = $instance->getFileHandler()->upload($request->post('base'));
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
            $instance = getInstance($request);
            if (!$instance->relationship->checkPermission('file.create'))
                throw new \Exception('此账号无权操作此实例。', 401);
            $instance->getFileHandler()->create($request->post('base'), $request->post('type'), $request->post('name'));
            return json(['code' => 200]);
        } catch (Throwable $th) {
            return json(['code' => $th->getCode() ?: 500, 'msg' => $th->getMessage()])->withStatus($th->getCode() ?: 500);
        }
    }

    public function Read(Request $request)
    {
        try {
            $instance = getInstance($request);
            if (!$instance->relationship->checkPermission('file.read'))
                throw new \Exception('此账号无权操作此实例。', 401);

            return json([
                'code' => 200,
                'attributes' => [
                    'content' => base64_encode($instance->getFileHandler()->read($request->post('path')))
                ]
            ]);
        } catch (Throwable $th) {
            return json(['code' => $th->getCode() ?: 500, 'msg' => $th->getMessage()])->withStatus($th->getCode() ?: 500);
        }
    }

    public function Save(Request $request)
    {
        try {
            $instance = getInstance($request);
            if (!$instance->relationship->checkPermission('file.read'))
                throw new \Exception('此账号无权操作此实例。', 401);

            $instance->getFileHandler()->save($request->post('path'), $request->post('content'));

            return json([
                'code' => 200
            ]);
        } catch (Throwable $th) {
            return json(['code' => $th->getCode() ?: 500, 'msg' => $th->getMessage()])->withStatus($th->getCode() ?: 500);
        }
    }
}
