<?php

namespace app\wechat\command\fans;

use app\wechat\command\Fans;

/**
 * 粉丝列表指令管理
 * Class FansList
 * @package app\wechat\command\fans
 */
class FansList extends Fans
{
    /**
     * 配置入口
     */
    protected function configure()
    {
        $this->module = ['list'];
        $this->setName('xfans:list')->setDescription('synchronize list of fans');
    }

}