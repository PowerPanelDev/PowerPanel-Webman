<?php

namespace app\model;

use support\Model;

class NodeGroup extends Model
{
    protected $table = 'node_group';
    protected $fillable = ['name', 'description'];

    public function nodes()
    {
        return $this->hasMany(Node::class, 'node_group_id', 'id');
    }

    public function instances()
    {
        return $this->hasManyThrough(Instance::class, Node::class, 'node_group_id', 'node_id', 'id', 'id');
    }
}
