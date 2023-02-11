<?php

namespace app\model;

use support\Model;

class InstanceRelationship extends Model
{
    protected $table = 'instance_relationship';
    protected $fillable = ['user_id', 'ins_id', 'is_owner', 'permission'];
    public $timestamps = true;

    public function getPermission()
    {
        return json_decode($this->permission, true);
    }

    /**
     * 检查权限
     *
     * @param array|string $permission 为字符串时返回 boolean 为数组时过滤出有权限的项或返回 false 为空时判断是否存在任意权限
     * @return void
     */
    public function checkPermission(array|string $permission = NULL)
    {
        $permissions = $this->getPermission();
        if ((isset($permissions[0]) && $permissions[0] == 'all') || $this->is_owner == 1)   // 拥有全部权限或为所有者 跳过判断
            return is_array($permission) ? $permission : true;
        if ($permission) {
            // 传入权限节点 判断是否拥有对应权限
            if (is_array($permission)) {
                // 传入多个权限 过滤出有权限的项
                $return = array_values(array_intersect($permission, $permissions));
                return count($return) ? $return : false;
            } else {
                // 传入单个权限
                return in_array($permission, $permissions);
            }
        } else {
            // 未传入参数 判断是否存在任意权限
            return count($permissions) > 0;
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
