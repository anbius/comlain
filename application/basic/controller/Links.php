<?php

namespace app\basic\controller;

use library\Controller;
use think\Db;

/**
 * 图片管理
 * User: longshangyun@wenwu
 * Date: 2019/2/24
 * Time: 12:16
 */
class Links extends Controller
{
    public $table = 'BasicLinks';

    /**
     * 友情链接管理
     */
    public function index()
    {
        $this->title = '友情链接管理';
        $this->_query($this->table)->like('title')->equal('type')->where(['is_deleted' => '1'])->timeBetween('create_at')->order('status desc,sort desc,id desc')->page();
    }

    /**
     * 添加友情链接
     */
    public function add()
    {
        $this->title = '友情链接添加';
        return $this->_form($this->table, 'form');
    }

    /**
     * 编辑友情链接
     */
    public function edit()
    {
        $this->title = '友情链接编辑';
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
            $data['sort'] = 0;
            $data['status'] = 1;
            $data['is_deleted'] = 1;
            $data['create_by'] = session('user.id');
            $data['create_at'] = time();
        }
    }

    protected function _form_filter(&$data)
    {
        if ($this->request->isPost())
        {
            //1图形2文字
            !empty($data['type']) && ($data['type'] == 1) && empty($data['local_url']) && $this->error('请上传图片');
            $data['title'] = !empty($data['title']) ? in($data['title']) : $this->error('标题不能为空');
            if(!empty($data['local_url'])){$data['local_url'] = in($data['local_url']);}
            if(!empty($data['link_url'])){if(!($data['link_url'] = http_in($data['link_url']))){$this->error('URL链接不符合预定规则！');}}else{$data['link_url'] = '';}
        }
    }

    protected function _form_result($result)
    {
        if ($result !== false) {
            list($base, $spm, $url) = [url('@admin'), $this->request->get('spm'), url('basic/links/index')];
            $this->success('数据保存成功！', "{$base}#{$url}?spm={$spm}");
        }
    }
}