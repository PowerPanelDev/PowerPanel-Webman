<?php

/**
 * This file is part of webman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author    walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link      http://www.workerman.net/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */

use app\controller\Admin\AdminUserController;
use app\controller\Admin\AppController as AdminAppController;
use app\controller\Admin\AppGameController as AdminAppGameController;
use app\controller\Admin\AppVersionController as AdminAppVersionController;
use app\controller\Admin\InstanceController as AdminInstanceController;
use app\controller\Admin\NodeController as AdminNodeController;
use app\controller\Admin\NodeGroupController as AdminNodeGroupController;
use app\controller\AdminController;
use app\controller\Auth;
use app\controller\Instance;
use app\controller\Instance\File;
use app\controller\NodeAPI;
use app\middleware\Auth\AdminAuth;
use app\middleware\Auth\NodeAuth;
use app\middleware\Auth\PublicAuth;
use Webman\Route;

use app\middleware\CSRFValidate;
use app\middleware\InstanceAuth;

Route::group('/api/public', function () {
    Route::get('/auth',         [Auth::class, 'GetStatus']);
    Route::post('/auth/login',  [Auth::class, 'Login']);
});

Route::group('/api/public', function () {
    Route::get('/ins', [Instance::class, 'GetList']);
})->middleware([CSRFValidate::class, PublicAuth::class]);

Route::group('/api/public/ins/{insId:\d+}', function () {
    Route::get('',          [Instance::class, 'GetDetail'])->setParams(['relationship' => 'detail']);
    Route::get('/console',  [Instance::class, 'GetConsole']);
    Route::put('/rename',   [Instance::class, 'Rename'])->setParams(['relationship' => 'rename']);

    Route::get('/files',                [File::class, 'GetList'])->setParams(['relationship' => 'file.list']);
    Route::post('/files/rename',        [File::class, 'Rename'])->setParams(['relationship' => 'file.rename']);
    Route::post('/files/compress',      [File::class, 'Compress'])->setParams(['relationship' => 'file.compress']);
    Route::post('/files/decompress',    [File::class, 'Decompress'])->setParams(['relationship' => 'file.decompress']);
    Route::post('/files/delete',        [File::class, 'Delete'])->setParams(['relationship' => 'file.delete']);
    Route::post('/files/permission',    [File::class, 'GetPermission'])->setParams(['relationship' => 'file.permission.get']);
    Route::put('/files/permission',     [File::class, 'SetPermission'])->setParams(['relationship' => 'file.permission.set']);
    Route::post('/files/download',      [File::class, 'Download'])->setParams(['relationship' => 'file.download']);
    Route::post('/files/upload',        [File::class, 'Upload'])->setParams(['relationship' => 'file.upload']);
    Route::post('/files/create',        [File::class, 'Create'])->setParams(['relationship' => 'file.create']);
    Route::post('/files/read',          [File::class, 'Read'])->setParams(['relationship' => 'file.read']);
    Route::post('/files/save',          [File::class, 'Save'])->setParams(['relationship' => 'file.save']);
})->middleware([CSRFValidate::class, PublicAuth::class, InstanceAuth::class]);

Route::group('/api/admin', function () {
    Route::get('',                              [AdminController::class, 'GetData']);
    Route::get('/ins',                          [AdminInstanceController::class, 'GetList']);

    Route::get('/user',                         [AdminUserController::class, 'GetList']);
    Route::post('/user',                        [AdminUserController::class, 'Create']);
    Route::get('/user/{userId:\d+}',            [AdminUserController::class, 'GetDetail']);
    Route::put('/user/{userId:\d+}',            [AdminUserController::class, 'Update']);
    Route::delete('/user/{userId:\d+}',         [AdminUserController::class, 'Delete']);

    Route::get('/node',                         [AdminNodeController::class, 'GetList']);
    Route::post('/node',                        [AdminNodeController::class, 'Create']);
    Route::get('/node/{nodeId:\d+}',            [AdminNodeController::class, 'GetDetail']);
    Route::put('/node/{nodeId:\d+}',            [AdminNodeController::class, 'Update']);
    Route::delete('/node/{nodeId:\d+}',         [AdminNodeController::class, 'Delete']);

    Route::get('/node/group',                   [AdminNodeGroupController::class, 'GetList']);
    Route::post('/node/group',                  [AdminNodeGroupController::class, 'Create']);
    Route::get('/node/group/{groupId:\d+}',     [AdminNodeGroupController::class, 'GetDetail']);
    Route::put('/node/group/{groupId:\d+}',     [AdminNodeGroupController::class, 'Update']);
    Route::delete('/node/group/{groupId:\d+}',  [AdminNodeGroupController::class, 'Delete']);

    Route::get('/app',                          [AdminAppController::class, 'GetList']);
    Route::post('/app',                         [AdminAppController::class, 'Create']);
    Route::get('/app/{appId:\d+}',              [AdminAppController::class, 'GetDetail']);
    Route::put('/app/{appId:\d+}',              [AdminAppController::class, 'Update']);
    Route::delete('/app/{appId:\d+}',           [AdminAppController::class, 'Delete']);

    Route::get('/app/game',                     [AdminAppGameController::class, 'GetList']);
    Route::post('/app/game',                    [AdminAppGameController::class, 'Create']);
    Route::get('/app/game/{gameId:\d+}',        [AdminAppGameController::class, 'GetDetail']);
    Route::put('/app/game/{gameId:\d+}',        [AdminAppGameController::class, 'Update']);
    Route::delete('/app/game/{gameId:\d+}',     [AdminAppGameController::class, 'Delete']);

    Route::get('/app/version',                  [AdminAppVersionController::class, 'GetList']);
    Route::post('/app/version',                 [AdminAppVersionController::class, 'Create']);
    Route::get('/app/version/{appId:\d+}',      [AdminAppVersionController::class, 'GetDetail']);
    Route::put('/app/version/{appId:\d+}',      [AdminAppVersionController::class, 'Update']);
    Route::delete('/app/version/{appId:\d+}',   [AdminAppVersionController::class, 'Delete']);
})->middleware([CSRFValidate::class, AdminAuth::class]);

Route::group('/api/node', function () {
    Route::get('/ins',          [NodeAPI::class, 'GetList']);
    Route::post('/ins/detail',  [NodeAPI::class, 'GetDetail']);
    Route::put('/ins/stats',    [NodeAPI::class, 'UpdateStats']);
})->middleware([NodeAuth::class]);

Route::group('/api/debug', function () {
    Route::get('/session', function ($request) {
        return json($request->session()->all());
    });
});

Route::disableDefaultRoute();
