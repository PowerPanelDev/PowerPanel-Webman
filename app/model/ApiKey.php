<?php

namespace app\model;

use support\Model;

class ApiKey extends Model
{
    protected $table = 'api_key';
    protected $fillable = ['uid'];

    const CREATED_AT = null;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function checkPermission($permission)
    {
        return $this->user->permission->checkPermission($permission);
    }
}
