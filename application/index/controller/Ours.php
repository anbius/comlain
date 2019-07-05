<?php

namespace app\index\controller;

/**
 * 联系我们
 * User: longshangyun@wenwu
 * Date: 2019
 */
class Ours extends Base
{
    protected function initialize()
    {
        parent::initialize();

        $this->assign('navitem',7);
    }


    public function index()
    {

        return $this->fetch();
    }
}