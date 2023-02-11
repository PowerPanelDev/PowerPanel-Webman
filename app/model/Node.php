<?php

namespace app\model;

use app\util\Random;
use support\Model;

class Node extends Model
{
    protected $table = 'node';
    protected $fillable = [
        'node_group_id',
        'name',
        'description',
        'endpoint',
        'enable_tls',
        'os',
        'memory',
        'memory_overallocate',
        'disk',
        'disk_overallocate',
        'max_upload_slice_size'
    ];
    public $timestamps = true;

    public function group()
    {
        return $this->belongsTo(NodeGroup::class, 'node_group_id', 'id');
    }

    public function instances()
    {
        return $this->hasMany(Instance::class, 'node_id', 'id');
    }

    public function allocations()
    {
        return $this->hasMany(NodeAllocation::class, 'node_id', 'id');
    }

    public function genToken()
    {
        $this->panel_token = Random::String(64);
        $this->node_token = Random::String(64);
    }

    public function getAddress()
    {
        return ($this->enable_tls ? 'https://' : 'http://') . $this->endpoint;
    }
}
