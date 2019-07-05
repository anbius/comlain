<?php

namespace app\mobile\controller;

/**
 * 案例
 * User: longshangyun@wenwu
 * Date: 2019/2/28
 * Time: 18:24
 */
class Cases extends Base
{

    public function index()
    {
        $this->assign('title','案例-');

        return $this->fetch();
    }


    public function content()
    {

        return $this->fetch();
    }
}