<?php

namespace app\client;

use app\model\Node;

class NodeClient
{
    public Node $node;
    public string $node_token;

    public function __construct(Node $node)
    {
        $this->node = $node;
    }

    protected function buildUrl($uri)
    {
        return $this->node->getAddress() . $uri;
    }

    public function get($uri)
    {
        $ch = curl_init($this->buildUrl($uri));
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->node_token
            ]
        ]);
        $return = json_decode(curl_exec($ch), true);
        curl_close($ch);

        $this->checkResponse($return);

        return $return;
    }

    public function post($uri, $data): array
    {
        $ch = curl_init($this->buildUrl($uri));
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                'Content-type: application/json',
                'Authorization: Bearer ' . $this->node->node_token
            ]
        ]);
        $return = json_decode(curl_exec($ch), true);
        curl_close($ch);

        $this->checkResponse($return);

        return $return;
    }

    public function put($uri, $data): array
    {
        $ch = curl_init($this->buildUrl($uri));
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                'Content-type: application/json',
                'Authorization: Bearer ' . $this->node->node_token
            ]
        ]);
        $return = json_decode(curl_exec($ch), true);
        curl_close($ch);

        $this->checkResponse($return);

        return $return;
    }

    protected function checkResponse(array $return)
    {
        if (!isset($return['code'])) throw new \Exception('节点无响应。', 500);
        if ($return['code'] != 200) throw new \Exception('[Node] ' . $return['msg'], $return['code']);
    }
}
