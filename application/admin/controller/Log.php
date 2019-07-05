<?php

namespace app\admin\controller;

use library\Controller;

/**
 * 系统日志管理
 * Class Log
 * @package app\admin\controller
 */
class Log extends Controller
{

    /**
     * 指定当前数据表
     * @var string
     */
    public $table = 'SystemLog';

    /**
     * 系统操作日志
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $this->title = '系统操作日志';
        $this->_query($this->table)->like('action,node,content,username,geoip')->timeBetween('create_at')->order('id desc')->page();
    }

    /**
     * 列表数据处理
     * @param array $data
     * @throws \Exception
     */
    protected function _index_page_filter(&$data)
    {
        $ip = new \Ip2Region();
        foreach ($data as &$vo) {
            $result = $ip->btreeSearch($vo['geoip']);
            $vo['isp'] = isset($result['region']) ? $result['region'] : '';
            $vo['isp'] = str_replace(['内网IP', '0', '|'], '', $vo['isp']);
        }
    }

    /**
     * 删除系统日志
     */
    public function del()
    {
        $this->applyCsrfToken();
        $this->_delete($this->table);
    }

}