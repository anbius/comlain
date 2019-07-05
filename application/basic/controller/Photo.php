<?php

namespace app\basic\controller;

use library\Controller;
use library\lsy\Idworks;
use think\Db;

/**
 * 图片管理
 * User: longshangyun@wenwu
 * Date: 2019/2/24
 * Time: 12:16
 */
class Photo extends Controller
{
    public $table = 'BasicPhoto';

    /**
     * banner管理
     */
    public function banner()
    {
        $this->title = 'banner管理';
        $this->_query($this->table)->like('title')->timeBetween('create_at')->where(['is_deleted' => '1'])->order('status desc,sort desc,id desc')->page();
    }

    /**
     * 添加banner
     */
    public function add()
    {
        $this->title = 'banner添加';
        return $this->_form($this->table, 'form');
    }

    /**
     * 编辑banner
     */
    public function edit()
    {
        $this->title = 'banner编辑';
        return $this->_form($this->table, 'form');
    }

    /**
     * 删除
     */
    public function del()
    {
        $this->applyCsrfToken();
        $this->_delete($this->table);
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

    protected function _add_form_filter(&$data)
    {
        if($this->request->isPost())
        {
            $data['id'] = Idworks::uniqueID();
            $data['type'] = 1;
            $data['sort'] = 0;
            $data['status'] = 1;
            $data['is_deleted'] = 1;
            $data['create_by'] = session('user.id');
            $data['create_at'] = time();
        }
    }

    protected function _form_filter(&$data)
    {
        if ($this->request->isPost()) {
            empty($data['local_url']) && $this->error('请上传图片');
            if(!empty($data['link_url'])){if(!($data['link_url'] = http_in($data['link_url']))){$this->error('URL链接不符合预定规则！');}}else{$data['link_url'] = '';}
            $data['title'] = empty($data['title']) ? '' : in($data['title']);
            if(empty($data['digest'])){$data['digest'] = text_in(mb_substr(trim(strip_tags(str_replace(["\r\n","\n","&nbsp;", ' '], '', $data['detail']))), 0, 500));}else{$data['digest'] = text_in($data['digest']);}
            $data['detail'] = empty($data['detail']) ? '' : html_in($data['detail']);
        }
    }

    protected function _form_result($result)
    {
        if ($result !== false) {
            list($base, $spm, $url) = [url('@admin'), $this->request->get('spm'), url('basic/photo/banner')];
            $this->success('数据保存成功！', "{$base}#{$url}?spm={$spm}");
        }
    }
}