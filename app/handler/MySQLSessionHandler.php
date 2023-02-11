<?php

namespace app\handler;

use support\Db;

/**
 * 适用于 Webman 的使用 MySQL 作为 Session 存储引擎的 Handler
 * 
 * Class MySQLSessionHandler
 */
class MySQLSessionHandler implements \Workerman\Protocols\Http\Session\SessionHandlerInterface
{
    public function open($save_path, $name)
    {
        return true;
    }

    /**
     * 返回从数据库获取到的 Session 原始数据
     *
     * @param String $sessionId
     * @return String
     */
    public function read($sessionId)
    {
        return Db::table('session')->where('id', $sessionId)->value('data');
    }

    /**
     * 向数据库更新 Session 数据和时间
     *
     * @param String $sessionId
     * @return String
     */
    public function write($sessionId, $sessionData)
    {
        return Db::table('session')
            ->updateOrInsert([
                'id' => $sessionId,
            ], [
                'data' => $sessionData,
                'update_time' => time()
            ]);
    }

    /**
     * 更新 Session 更新时间
     *
     * @param String $id
     * @param String $data
     * @return true
     */
    public function updateTimestamp($id, $data = NULL)
    {
        return true;
    }

    public function close()
    {
        return true;
    }

    /**
     * 从数据库清除指定 SessionId 的所有数据
     *
     * @param String $sessionId
     * @return Boolean
     */
    public function destroy($sessionId)
    {
        return Db::table('session')
            ->where('id', $sessionId)
            ->delete();
    }

    /**
     * 从数据库清理超过 $maxLifeTime 时间未更新的 Session 记录
     *
     * @param Int $maxLifeTime
     * @return true
     */
    public function gc($maxLifeTime)
    {
        $minUpdateTime = time() - $maxLifeTime;
        Db::table('session')
            ->where('update_time', '<', $minUpdateTime)
            ->delete();
        return true;
    }
}
