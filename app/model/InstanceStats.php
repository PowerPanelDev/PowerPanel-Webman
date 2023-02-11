<?php

namespace app\model;

use support\Model;

class InstanceStats extends Model
{
    const STATUS_INSTALLING = 1;
    const STATUS_STARTING = 11;
    const STATUS_RUNNING = 21;
    const STATUS_STOPPING = 31;
    const STATUS_STOPPED = 41;

    const CREATED_AT = null;
    public $timestamps = true;
    public $incrementing = false;
    protected $table = 'instance_stats';
    protected $primaryKey = 'ins_id';
    protected $fillable = ['status', 'disk_usage'];
}
