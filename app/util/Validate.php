<?php

namespace app\util;

use Rakit\Validation\Validator as RakitValidator;

class Validate
{
    static protected $message = [
        'required'          => '参数 :attribute 必填',
        'required_if'       => '参数 :attribute 此时必填',
        'required_unless'   => '参数 :attribute 此时必填',
        'integer'           => '参数 :attribute 必须为整数型',
        'email'             => '参数 :attribute 必须为邮箱',
        'json'              => '参数 :attribute 必须为 JSON',
        'boolean'           => '参数 :attribute 必须为布尔值',
        'min'               => '参数 :attribute 长度过短',
        'max'               => '参数 :attribute 长度过长',
        'in'                => '参数 :attribute 不属于可选范围'
    ];

    static public function Data(array $data, array $rules)
    {
        $validation = (new RakitValidator(self::$message))
            ->validate($data, $rules);

        $errors = $validation->errors();
        if ($errors->count())
            throw new \Exception(implode('、', $errors->all()) . '。', 400);

        return $validation->getValidData();
    }
}
