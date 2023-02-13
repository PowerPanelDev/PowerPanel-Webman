<?php

use app\class\Request;
use app\model\Instance;
use app\model\User;

function getInstance(Request $request): Instance
{
    return $request->instance;
}

function getUser(Request $request): User
{
    return $request->user;
}
