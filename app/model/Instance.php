<?php

namespace app\model;

use app\client\NodeClient;
use app\handler\Instance\FileHandler;
use app\handler\Instance\TokenHandler;
use app\model\Node;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use support\Model;

class Instance extends Model
{
    protected $table = 'instance';
    protected $fillable = [
        'uuid',
        'name',
        'descriptions',
        'node_id',
        'node_allocation_id',
        'app_id',
        'app_version_id',
        'cpu',
        'memory',
        'swap',
        'disk'
    ];
    public NodeClient $client;

    public function relationship(): HasOne
    {
        // 未预加载时 checkPermission 会返回其他用户关系 为确保安全须预加载
        if ($this->id) throw new \Exception('此关联模型只能通过预加载方式加载。');
        return $this->hasOne(InstanceRelationship::class, 'ins_id', 'id');
    }

    public function relationships(): HasMany
    {
        return $this->hasMany(InstanceRelationship::class, 'ins_id', 'id');
    }

    public function stats()
    {
        return $this->hasOne(InstanceStats::class, 'ins_id', 'id');
    }

    public function node(): BelongsTo
    {
        return $this->belongsTo(Node::class, 'node_id', 'id');
    }

    public function allocation(): HasOne
    {
        return $this->hasOne(NodeAllocation::class, 'id', 'node_allocation_id');
    }

    public function allocations()
    {
        return $this->hasMany(NodeAllocation::class, 'ins_id', 'id');
    }

    public function app(): BelongsTo
    {
        return $this->belongsTo(App::class, 'app_id', 'id');
    }

    public function version(): BelongsTo
    {
        return $this->belongsTo(AppVersion::class, 'app_version_id', 'id');
    }

    public function getClient()
    {
        if (!isset($this->client)) $this->client = (new NodeClient($this->node));
        return $this->client;
    }

    public function getTokenHandler(): TokenHandler
    {
        return new TokenHandler($this);
    }

    public function getFileHandler(): FileHandler
    {
        return new FileHandler($this);
    }

    public function rename(string $name)
    {
        $this->name = $name;
        $this->save();
    }
}
