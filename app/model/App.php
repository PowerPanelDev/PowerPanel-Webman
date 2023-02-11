<?php

namespace app\model;

use support\Model;

class App extends Model
{
    protected $table = 'app';
    protected $fillable = [
        'game_id',
        'name',
        'description',
        'data_path',
        'working_path',
        'images',
        'config',
        'startup',
        'skip_install',
        'install_image',
        'install_script'
    ];

    public function version()
    {
        return $this->hasMany(AppVersion::class, 'app_id', 'id');
    }

    public function instances()
    {
        return $this->hasMany(Instance::class, 'app_id', 'id');
    }

    public function versions()
    {
        return $this->hasMany(AppVersion::class, 'app_id', 'id');
    }

    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id', 'id');
    }
}
