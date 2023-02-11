<?php

use app\model\Instance;
use app\model\User;
use support\Request;

function getInstance(Request $request): Instance
{
    return $request->instance;
}

function getUser(Request $request): User
{
    return $request->user;
}
