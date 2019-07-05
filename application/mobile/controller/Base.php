<?php

namespace app\mobile\controller;

use think\Controller;
use library\lsy\Ismobile;

/**
 * 案例
 * User: longshangyun@wenwu
 * Date: 2019/2/28
 * Time: 18:24
 */
class Base extends Controller
{
    protected function initialize()
    {
        parent::initialize();

        if(!Ismobile::checkByAgent())
        {
            header('Location: ' . WEBSITE_PC . $_SERVER['REQUEST_URI']);
            exit;
        }
    }
}