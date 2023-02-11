<?php

namespace app\util;

class Salt
{
    const SALT_PATH = __DIR__ . '/../../config/SALT';

    static public $salt;

    static public function Get()
    {
        if (!self::$salt) {
            if (is_file(self::SALT_PATH)) {
                self::$salt = file_get_contents(self::SALT_PATH);
            } else {
                self::$salt = Random::String(32);
                file_put_contents(self::SALT_PATH, self::$salt);
            }
        }
        return self::$salt;
    }
}
