<?php

namespace app\handler\Instance;

use app\model\Token;

class TokenHandler extends Handler
{
    const TYPE_HTTP = 1;
    const TYPE_WS = 2;

    /**
     * 获取 Token 用于客户端->节点通信
     *
     * @return Token
     */
    public function generate(string $type, array $permission, array $data, int $validity = 30)
    {
        $return = $this->client->post('/api/panel/token', [
            'attributes' => [
                'type' => $type,
                'permission' => $permission,
                'data' => $data,
                'created_at' => time(),
                'expire_at' => time() + $validity
            ]
        ]);
        return new Token($return['attributes']['token'], $permission, $this->instance->node);
    }
}
