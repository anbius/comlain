<?php

namespace app\mobile\controller;

/**
 * 案例
 * User: longshangyun@wenwu
 * Date: 2019/2/28
 * Time: 18:24
 */
class Ours extends Base
{

    public function index()
    {
        $this->assign('title','我们-');

        return $this->fetch();
    }
}