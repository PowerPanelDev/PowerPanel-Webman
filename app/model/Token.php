<?php

namespace app\model;

/**
 * 此模型非 Eloquent 模型
 * 只作为对象使用
 */

class Token
{
    public $token, $permission;
    public Node $node;

    public function __construct($token, array $permission, Node $node)
    {
        $this->token = $token;
        $this->permission = $permission;
        $this->node = $node;
    }
}
