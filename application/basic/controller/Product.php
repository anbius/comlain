<?php

namespace app\basic\controller;

use library\Controller;
use library\lsy\Idworks;
use library\tools\Data;
use app\basic\service\Product as ProductService;
use think\Db;

/**
 * 产品展示
 * User: longshangyun@wenwu
 * Date: 2019
 */
class Product extends Controller
{
    public $table = 'BasicProduct';

    /**
     * 内容列表
     */
    public function index()
    {
        $this->title = '产品展示';
        $this->_query($this->table)->like('title')->equal('type')->where(['is_deleted' => '1'])->timeBetween('create_at')->order('status desc,sort desc,id desc')->page();
    }

    /**
     * 添加产品展示
     */
    public function add()
    {
        $this->title = '产品展示添加';
        return $this->_form($this->table, 'form');
    }

    /**
     * 编辑产品展示
     */
    public function edit()
    {
        $this->title = '产品展示编辑';
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

    protected function _page_filter(&$data)
    {
        $serviceArr = ProductService::getContentCateAll();
        $this->assign('cates',Data::arr2table($serviceArr));

        $serviceColumnArr = array_column($serviceArr,'title','id');
        foreach($data as &$vo){
            if(!empty($vo['cat_1']) && isset($serviceColumnArr[$vo['cat_1']])){$vo['cat1'] = $serviceColumnArr[$vo['cat_1']];}else{$vo['cat1'] = '';}
        }
    }

    protected function _add_form_filter(&$data)
    {
        if($this->request->isPost())
        {
            $data['id'] = Idworks::uniqueID();
            $data['sort'] = 0;
            $data['reads'] = 0;
            $data['status'] = isset($data['status']) ? intval($data['status']) : 1;
            $data['is_deleted'] = 1;
            $data['create_by'] = session('user.id');
        }
    }

    protected function _form_filter(&$data)
    {
        if($this->request->isGet())
        {
            //TODO 控制是否将此信息在首页显示
            $this->assign('isHomepage',1);
            $this->assign('cates',Data::arr2table(ProductService::getContentCateAll()));
        }

        if ($this->request->isPost())
        {
            $data['type'] = empty($data['type']) ? 0 : abs($data['type']);
            $data['is_homepage'] = isset($data['is_homepage']) ? intval($data['is_homepage']) : 0;
            empty($data['local_url']) && $this->error('请上传图片');
            $data['local_url'] = empty($data['local_url']) ? '' : in($data['local_url']);
            if(!empty($data['link_url'])){if(!($data['link_url'] = http_in($data['link_url']))){$this->error('URL链接不符合预定规则！');}}else{$data['link_url'] = '';}
            empty($data['detail']) && $this->error('内容不能为空');
            $data['title'] = empty($data['title']) ? '' : in($data['title']);
            $data['keywords'] = empty($data['keywords']) ? '' : in(str_replace('，',',',$data['keywords']));
            if(empty($data['digest'])){$data['digest'] = text_in(mb_substr(trim(strip_tags(str_replace(["\r\n","\n","&nbsp;", ' '], '', $data['detail']))), 0, 500));}else{$data['digest'] = text_in($data['digest']);}
            $data['detail'] = empty($data['detail']) ? '' : html_in($data['detail']);
            $data['create_at'] = !empty($data['create_at']) ? strtotime($data['create_at']) : time();

            $contentCateArr = array_reverse(ProductService::sameContentCate($data['type']));
            $data['cat_1'] = isset($contentCateArr[0]) ? intval($contentCateArr[0]) : 0;
            $data['cat_2'] = isset($contentCateArr[1]) ? intval($contentCateArr[1]) : 0;
            $data['cat_3'] = isset($contentCateArr[2]) ? intval($contentCateArr[2]) : 0;
        }
    }

    protected function _form_result($result)
    {
        if ($result !== false)
        {
            list($base, $spm, $url) = [url('@admin'), $this->request->get('spm'), url('basic/product/index')];
            $this->success('数据保存成功！', "{$base}#{$url}?spm={$spm}");
        }
    }
}