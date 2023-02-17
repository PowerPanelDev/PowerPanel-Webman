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
        'host',
        'api_port',
        'ws_port',
        'enable_tls',
        'os',
        'memory',
        'memory_overallocate',
        'disk',
        'disk_overallocate',
        'addition'
    ];
    protected $attributes = [
        'addition' => '{"instance_data_path": "/data/power-data", "max_upload_slice_size": 10485760}'
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

    public function getAddress($type = 'api')
    {
        $scheme = ($type == 'api' ? 'http' : 'ws') . ($this->enable_tls ? 's' : '');
        $host = $this->host;
        $port = $type == 'api' ? $this->api_port : $this->ws_port;

        return $scheme . '://' . $host . ':' . $port;
    }
}
