<?php

namespace app\admin\controller;

use library\Controller;
use think\Db;

/**
 * 系统消息管理
 * Class Message
 * @package app\admin\controller
 */
class Message extends Controller
{
    /**
     * 指定数据表
     * @var string
     */
    protected $table = 'SystemMessage';

    /***
     * 系统消息管理
     */
    public function index()
    {
        $this->title = '系统消息管理';
        $this->_query($this->table)->like('title,desc')->equal('read_state')->dateBetween('create_at,read_at')->order('id desc')->page();
    }

    /**
     * 设置消息状态
     */
    public function state()
    {
        $this->_save($this->table, ['read_state' => '1', 'read_at' => date('Y-m-d H:i:s')]);
    }

    /**
     * 删除系统消息
     */
    public function del()
    {
        $this->_delete($this->table);
    }

    /**
     * 清理所有消息
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function clear()
    {
        if (Db::name($this->table)->whereRaw('1=1')->delete() !== false) {
            $this->success('系统消息清理成功！');
        } else {
            $this->error('系统消息清理失败，请稍候再试！');
        }
    }

    /**
     * 设置消息开关
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function onoff()
    {
        sysconf('system_message_state', sysconf('system_message_state') ? 0 : 1);
        if (sysconf('system_message_state')) {
            $this->success('系统消息提示开启成功！');
        } else {
            $this->success('系统消息提示关闭成功！');
        }
    }

}