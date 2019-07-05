<?php

namespace app\basic\controller;

use library\Controller;
use think\Db;

/**
 * 留言反馈
 * User: longshangyun@wenwu
 * Date: 2019
 */
class Feedback extends Controller
{
    public $table = 'BasicFeedback';

    /**
     * 留言反馈列表
     */
    public function index()
    {
        $this->title = '留言反馈';
        $this->_query($this->table)->like('title')->equal('type')->where(['is_deleted' => '1'])->timeBetween('create_at')->order('status desc,sort desc,id desc')->page();
    }


    /**
     * 禁用
     */
    public function forbid()
    {
        $this->applyCsrfToken();
        $this->_save($this->table, ['status' => '0']);
    }


    /**
     * 启用
     */
    public function resume()
    {
        $this->applyCsrfToken();
        $this->_save($this->table, ['status' => '1']);
    }
}