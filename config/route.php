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
use app\middleware\Auth\APIAuth;
use app\middleware\Auth\InstanceAuth;
use app\middleware\Auth\NodeAuth;
use Webman\Route;

Route::group('/api/public', function () {
    Route::get('/auth',         [Auth::class, 'GetStatus']);
    Route::post('/auth/login',  [Auth::class, 'Login']);
    Route::get('/auth/logout',  [Auth::class, 'Logout']);
});

Route::group('/api/public', function () {
    Route::get('/ins', [Instance::class, 'GetList'])->setParams(['permission' => 'ins.list']);
})->middleware([APIAuth::class]);

Route::group('/api/public/ins/{insId:\d+}', function () {
    Route::get('',          [Instance::class, 'GetDetail'])->setParams(['permission' => 'ins.detail', 'relationship' => 'detail']);
    Route::get('/console',  [Instance::class, 'GetConsole'])->setParams(['permission' => 'ins.console']);
    Route::put('/rename',   [Instance::class, 'Rename'])->setParams(['permission' => 'ins.rename', 'relationship' => 'rename']);

    Route::get('/files',                [File::class, 'GetList'])->setParams(['permission' => 'ins.file.list', 'relationship' => 'file.list']);
    Route::post('/files/rename',        [File::class, 'Rename'])->setParams(['permission' => 'ins.file.rename', 'relationship' => 'file.rename']);
    Route::post('/files/compress',      [File::class, 'Compress'])->setParams(['permission' => 'ins.file.compress', 'relationship' => 'file.compress']);
    Route::post('/files/decompress',    [File::class, 'Decompress'])->setParams(['permission' => 'ins.file.decompress', 'relationship' => 'file.decompress']);
    Route::post('/files/delete',        [File::class, 'Delete'])->setParams(['permission' => 'ins.file.delete', 'relationship' => 'file.delete']);
    Route::post('/files/permission',    [File::class, 'GetPermission'])->setParams(['permission' => 'ins.file.permission.get', 'relationship' => 'file.permission.get']);
    Route::put('/files/permission',     [File::class, 'SetPermission'])->setParams(['permission' => 'ins.file.permission.set', 'relationship' => 'file.permission.set']);
    Route::post('/files/download',      [File::class, 'Download'])->setParams(['permission' => 'ins.file.download', 'relationship' => 'file.download']);
    Route::post('/files/upload',        [File::class, 'Upload'])->setParams(['permission' => 'ins.file.upload', 'relationship' => 'file.upload']);
    Route::post('/files/create',        [File::class, 'Create'])->setParams(['permission' => 'ins.file.create', 'relationship' => 'file.create']);
    Route::post('/files/read',          [File::class, 'Read'])->setParams(['permission' => 'ins.file.read', 'relationship' => 'file.read']);
    Route::post('/files/save',          [File::class, 'Save'])->setParams(['permission' => 'ins.file.save', 'relationship' => 'file.save']);
})->middleware([APIAuth::class, InstanceAuth::class]);

Route::group('/api/admin', function () {
    Route::get('',                              [AdminController::class, 'GetData'])->setParams(['permission' => 'admin.detail']);
    Route::get('/ins',                          [AdminInstanceController::class, 'GetList'])->setParams(['permission' => 'admin.ins.list']);

    Route::get('/user',                         [AdminUserController::class, 'GetList'])->setParams(['permission' => 'admin.user.list']);
    Route::post('/user',                        [AdminUserController::class, 'Create'])->setParams(['permission' => 'admin.user.create']);
    Route::get('/user/{userId:\d+}',            [AdminUserController::class, 'GetDetail'])->setParams(['permission' => 'admin.user.detail']);
    Route::put('/user/{userId:\d+}',            [AdminUserController::class, 'Update'])->setParams(['permission' => 'admin.user.update']);
    Route::delete('/user/{userId:\d+}',         [AdminUserController::class, 'Delete'])->setParams(['permission' => 'admin.user.delete']);

    Route::get('/node',                         [AdminNodeController::class, 'GetList'])->setParams(['permission' => 'admin.node.list']);
    Route::post('/node',                        [AdminNodeController::class, 'Create'])->setParams(['permission' => 'admin.node.create']);
    Route::get('/node/{nodeId:\d+}',            [AdminNodeController::class, 'GetDetail'])->setParams(['permission' => 'admin.node.detail']);
    Route::put('/node/{nodeId:\d+}',            [AdminNodeController::class, 'Update'])->setParams(['permission' => 'admin.node.update']);
    Route::delete('/node/{nodeId:\d+}',         [AdminNodeController::class, 'Delete'])->setParams(['permission' => 'admin.node.delete']);

    Route::get('/node/group',                   [AdminNodeGroupController::class, 'GetList'])->setParams(['permission' => 'admin.node.group.list']);
    Route::post('/node/group',                  [AdminNodeGroupController::class, 'Create'])->setParams(['permission' => 'admin.node.group.create']);
    Route::get('/node/group/{groupId:\d+}',     [AdminNodeGroupController::class, 'GetDetail'])->setParams(['permission' => 'admin.node.group.detail']);
    Route::put('/node/group/{groupId:\d+}',     [AdminNodeGroupController::class, 'Update'])->setParams(['permission' => 'admin.node.group.update']);
    Route::delete('/node/group/{groupId:\d+}',  [AdminNodeGroupController::class, 'Delete'])->setParams(['permission' => 'admin.node.group.delete']);

    Route::get('/app',                          [AdminAppController::class, 'GetList'])->setParams(['permission' => 'admin.app.list']);
    Route::post('/app',                         [AdminAppController::class, 'Create'])->setParams(['permission' => 'admin.app.create']);
    Route::get('/app/{appId:\d+}',              [AdminAppController::class, 'GetDetail'])->setParams(['permission' => 'admin.app.detail']);
    Route::put('/app/{appId:\d+}',              [AdminAppController::class, 'Update'])->setParams(['permission' => 'admin.app.update']);
    Route::delete('/app/{appId:\d+}',           [AdminAppController::class, 'Delete'])->setParams(['permission' => 'admin.app.delete']);

    Route::get('/app/game',                     [AdminAppGameController::class, 'GetList'])->setParams(['permission' => 'admin.app.game.list']);
    Route::post('/app/game',                    [AdminAppGameController::class, 'Create'])->setParams(['permission' => 'admin.app.game.create']);
    Route::get('/app/game/{gameId:\d+}',        [AdminAppGameController::class, 'GetDetail'])->setParams(['permission' => 'admin.app.game.detail']);
    Route::put('/app/game/{gameId:\d+}',        [AdminAppGameController::class, 'Update'])->setParams(['permission' => 'admin.app.game.update']);
    Route::delete('/app/game/{gameId:\d+}',     [AdminAppGameController::class, 'Delete'])->setParams(['permission' => 'admin.app.game.delete']);

    Route::get('/app/version',                  [AdminAppVersionController::class, 'GetList'])->setParams(['permission' => 'admin.app.version.list']);
    Route::post('/app/version',                 [AdminAppVersionController::class, 'Create'])->setParams(['permission' => 'admin.app.version.create']);
    Route::get('/app/version/{appId:\d+}',      [AdminAppVersionController::class, 'GetDetail'])->setParams(['permission' => 'admin.app.version.detail']);
    Route::put('/app/version/{appId:\d+}',      [AdminAppVersionController::class, 'Update'])->setParams(['permission' => 'admin.app.version.update']);
    Route::delete('/app/version/{appId:\d+}',   [AdminAppVersionController::class, 'Delete'])->setParams(['permission' => 'admin.app.version.delete']);
})->middleware([APIAuth::class]);

Route::group('/api/node', function () {
    Route::get('/config',       [NodeAPI::class, 'GetConfig']);
    Route::get('/ins',          [NodeAPI::class, 'GetList']);
    Route::post('/ins/detail',  [NodeAPI::class, 'GetDetail']);
    Route::put('/ins/stats',    [NodeAPI::class, 'UpdateStats']);
})->middleware([NodeAuth::class]);

Route::options('[{path:.+}]', function () {
    return response();
});

Route::group('/api/debug', function () {
    Route::get('/session', function ($request) {
        return json($request->session()->all());
    });
});

Route::disableDefaultRoute();
