<?php

namespace app\model;

use support\Model;

class UserPermission extends Model
{
    public array $parsed;

    protected $table = 'user_permission';

    public function checkPermission($permission)
    {
        if (!isset($this->parsed)) $this->parsed = json_decode($this->permission, true);
        // 检查是否存在全字匹配
        if (in_array($permission, $this->parsed)) return true;
        // 检查是否存在全部权限
        return in_array(explode('.', $permission, 2)[0] == 'admin' ? 'admin.all' : 'all', $this->parsed);
    }
}
