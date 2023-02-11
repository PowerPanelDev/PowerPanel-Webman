<?php

namespace app\handler\Instance;

class FileHandler extends Handler
{
    public function list($path): array
    {
        $return = $this->client->post('/api/panel/files/list', [
            'attributes' => [
                'uuid' => $this->instance->uuid,
                'path' => $path
            ]
        ]);
        return $return['data'];
    }

    public function rename($from, $to): void
    {
        $this->client->post('/api/panel/files/rename', [
            'attributes' => [
                'uuid' => $this->instance->uuid,
                'from' => $from,
                'to' => $to
            ]
        ]);
    }

    public function compress(string $base, array $targets): void
    {
        $this->client->post('/api/panel/files/compress', [
            'attributes' => [
                'uuid' => $this->instance->uuid,
                'base' => $base,
                'targets' => $targets
            ]
        ]);
    }

    public function decompress(string $path): void
    {
        $this->client->post('/api/panel/files/decompress', [
            'attributes' => [
                'uuid' => $this->instance->uuid,
                'path' => $path
            ]
        ]);
    }

    public function delete(string $base, array $targets): void
    {
        $this->client->post('/api/panel/files/delete', [
            'attributes' => [
                'uuid' => $this->instance->uuid,
                'base' => $base,
                'targets' => $targets
            ]
        ]);
    }

    public function create(string $base, string $type, string $name): void
    {
        $this->client->post('/api/panel/files/create', [
            'attributes' => [
                'uuid' => $this->instance->uuid,
                'base' => $base,
                'type' => $type,
                'name' => $name
            ]
        ]);
    }

    public function read(string $path): string
    {
        $return = $this->client->post('/api/panel/files/read', [
            'attributes' => [
                'uuid' => $this->instance->uuid,
                'path' => $path
            ]
        ]);
        return $return['attributes']['content'];
    }

    public function save(string $path, string $content): void
    {
        $this->client->post('/api/panel/files/save', [
            'attributes' => [
                'uuid' => $this->instance->uuid,
                'path' => $path,
                'content' => $content
            ]
        ]);
    }

    /**
     * 设置 / 获取权限
     *
     * @param string $path
     * @param int|null $permission 目标权限 为空则为获取权限
     * @return string|void
     */
    public function permission(string $path, int $permission = NULL)
    {
        if ($permission) {
            // 设置权限
            $this->client->put('/api/panel/files/permission', [
                'attributes' => [
                    'uuid' => $this->instance->uuid,
                    'path' => $path,
                    'permission' => $permission
                ]
            ]);
        } else {
            // 获取权限
            $return = $this->client->post('/api/panel/files/permission', [
                'attributes' => [
                    'uuid' => $this->instance->uuid,
                    'path' => $path
                ]
            ]);
            return $return['attributes']['permission'];
        }
    }

    public function download(string $path)
    {
        $return = $this->instance->getTokenHandler()
            ->generate(TokenHandler::TYPE_HTTP, ['file.download'], [
                'instance' => $this->instance->uuid,
                'path' => $path
            ]);
        return $return;
    }

    public function upload(string $base)
    {
        $return = $this->instance->getTokenHandler()
            ->generate(TokenHandler::TYPE_HTTP, ['file.upload'], [
                'instance' => $this->instance->uuid,
                'base' => $base
            ], 10800);
        return $return;
    }
}
