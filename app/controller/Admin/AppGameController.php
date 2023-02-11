<?php

namespace app\controller\Admin;

use app\model\Game;
use app\util\Validate;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use support\Request;

class AppGameController
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
            'data' => Game::select(['id', 'name', 'updated_at', 'created_at'])
                ->withCount('apps')
                ->withCount('instances')
                ->get()
        ]);
    }

    public function Create(Request $request)
    {
        try {
            Game::create(Validate::Input($request, self::$rules));
            return json(['code' => 200]);
        } catch (\Throwable $th) {
            return json(['code' => $th->getCode() ?: 500, 'msg' => $th->getMessage()])->withStatus($th->getCode() ?: 500);
        }
    }

    public function GetDetail(Request $request, Int $gameId)
    {
        try {
            return json([
                'code' => 200,
                'attributes' => Game::findOrFail($gameId)
            ]);
        } catch (ModelNotFoundException $e) {
            return json(['code' => 400, 'msg' => '游戏不存在。'])->withStatus(400);
        } catch (\Throwable $th) {
            return json(['code' => $th->getCode() ?: 500, 'msg' => $th->getMessage()])->withStatus($th->getCode() ?: 500);
        }
    }

    public function Update(Request $request, int $gameId)
    {
        try {
            Game::findOrFail($gameId)->fill(Validate::Input($request, self::$rules))->save();

            return json(['code' => 200]);
        } catch (ModelNotFoundException $e) {
            return json(['code' => 400, 'msg' => '游戏不存在。'])->withStatus(400);
        } catch (\Throwable $th) {
            return json(['code' => $th->getCode() ?: 500, 'msg' => $th->getMessage()])->withStatus($th->getCode() ?: 500);
        }
    }

    public function Delete(Request $request, int $gameId)
    {
        try {
            $game = Game::withCount(['apps'])->findOrFail($gameId);
            if ($game->apps_count > 0) throw new \Exception('无法删除带有镜像的游戏。', 400);
            $game->delete();

            return json(['code' => 200]);
        } catch (ModelNotFoundException $e) {
            return json(['code' => 400, 'msg' => '游戏不存在。'])->withStatus(400);
        } catch (\Throwable $th) {
            return json(['code' => $th->getCode() ?: 500, 'msg' => $th->getMessage()])->withStatus($th->getCode() ?: 500);
        }
    }
}
