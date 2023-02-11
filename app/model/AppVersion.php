<?php

namespace app\model;

use support\Model;

class AppVersion extends Model
{
    protected $table = 'app_version';
    protected $fillable = ['app_id', 'name', 'version'];

    public function app()
    {
        return $this->belongsTo(App::class, 'app_id', 'id');
    }

    public function instances()
    {
        return $this->hasMany(Instance::class, 'app_version_id', 'id');
    }
}
