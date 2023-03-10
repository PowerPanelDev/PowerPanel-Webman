<?php

namespace app\model;

use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use support\Model;

class User extends Model
{
    protected $table = 'user';
    protected $fillable = ['name', 'email', 'is_admin'];
    public $timestamps = true;

    public function instances(): HasManyThrough
    {
        return $this->hasManyThrough(Instance::class, InstanceRelationship::class, 'user_id', 'id', 'id', 'ins_id');
    }

    public function permission()
    {
        return $this->hasOne(UserPermission::class, 'user_id', 'id');
    }

    public function passwd(string $password)
    {
        $this->password = hash('sha512', $password . getenv('APP_SALT'));
    }

    static public function wherePassword(string $password)
    {
        return self::where('password', hash('sha512', $password . getenv('APP_SALT')));
    }
}
