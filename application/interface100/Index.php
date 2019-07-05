<?php

namespace app\interface100;

use think\Controller;
use think\Db;
use library\lsy\Base as BaseService;

/**
 * Class Index
 * 首页
 */
class Index extends Controller
{
    public function index()
    {
        $result = [];

        return ['message'=>BaseService::$ERR['SYS']['HANDLE_SUCCESS'],'data'=>$result];
    }
}