<?php

namespace app\index\controller;

/**
 * 关于我们
 * User: longshangyun@wenwu
 * Date: 2019
 */
class About extends Base
{
    protected function initialize()
    {
        parent::initialize();

        $this->assign('navitem',2);
    }


    public function index()
    {

        return $this->fetch();
    }
}
