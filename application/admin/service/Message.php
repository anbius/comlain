<?php

namespace app\admin\service;

use library\lsy\Idworks;
use think\Db;

/**
 * 系统消息管理
 * Class Message
 * @package app\admin\service
 */
class Message
{
    /**
     * 增加系统消息
     * @param string $title 消息标题
     * @param string $desc 消息描述
     * @param string $url 访问链接
     * @param string $node 权限节点
     * @return boolean
     */
    public static function add($title, $desc, $url, $node)
    {
        $code = Idworks::uniqidNumberCode(12);
        $data = ['title' => $title, 'desc' => $desc, 'url' => $url, 'code' => $code, 'node' => $node];
        return Db::name('SystemMessage')->insert($data) !== false;
    }

    /**
     * 系统消息状态更新
     * @param integer $code
     * @return boolean
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public static function set($code)
    {
        $result = Db::name('SystemMessage')->where(['code' => $code, 'read_state' => '0'])->update([
            'read_state' => '1', 'read_at' => date('Y-m-d H:i:s'), 'read_uid' => session('user.id'),
        ]);
        return $result !== false;
    }

    /**
     * 获取消息列表成功
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function gets()
    {
        $where = ['read_state' => '0'];
        $list = Db::name('SystemMessage')->where($where)->order('id desc')->select();
        foreach ($list as $key => $vo) if (!empty($vo['node']) && !auth($vo['node'])) unset($list[$key]);
        return $list;
    }

}