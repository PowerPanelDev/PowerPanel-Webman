<?php

namespace app\model;

use support\Model;

class Game extends Model
{
    protected $table = 'game';
    protected $fillable = ['name', 'description'];

    public function apps()
    {
        return $this->hasMany(App::class, 'game_id', 'id');
    }

    public function instances()
    {
        return $this->hasManyThrough(Instance::class, App::class, 'game_id', 'app_id', 'id', 'id');
    }
}
