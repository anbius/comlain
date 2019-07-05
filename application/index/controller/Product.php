<?php

namespace app\index\controller;

use think\Db;
use app\basic\service\Product as ProductService;
use library\lsy\Base as BaseLsy;
use library\lsy\Ismobile;

/**
 * 产品展示
 * User: longshangyun@wenwu
 * Date: 2019
 */
class Product extends Base
{
    //每页限制数量
    private $limit = 8;
    
    protected function initialize()
    {
        parent::initialize();

        $this->assign('navitem',3);
    }


    public function index()
    {
        $data = $this->request->get();
        $cat1 = isset($data['catOne']) && is_numeric($data['catOne']) ? abs(intval($data['catOne'])) : 0;
        if(!empty($cat1)){$where['cat_1'] = $cat1;}
        $where['is_deleted'] = 1;
        $where['status'] = 1;

        $result = array();
        $result['pages'] = $this->_contentTotalPages(Db::name('BasicProduct')->where($where)->count('id'));
        if($result['pages'] && ($contentArr = Db::name('BasicProduct')->field('id,title,local_url,digest,link_url,cat_2,create_at,reads')->where($where)->order('sort desc,id desc')->limit($this->limit)->select()))
        {
            foreach($contentArr as $contentVal)
            {
                $cvArr['id'] = $contentVal['id'];
                $cvArr['title'] = $contentVal['title'] ? out($contentVal['title']) : '';
                $cvArr['localUrl'] = $contentVal['local_url'] ? out($contentVal['local_url']) : '';
                $cvArr['digest'] = $contentVal['digest'] ? out($contentVal['digest']) : '';
                $cvArr['linkUrl'] = $contentVal['link_url'] ? http_out($contentVal['link_url']) : '';
                $cvArr['reads'] = $contentVal['reads'];
                $cvArr['created'] = $contentVal['create_at'] ? date_time($contentVal['create_at']) : '';

                $result['list'][] = $cvArr;
            }
        }

        $result['productSonCate'] = ProductService::getContentCateAllByType();

        return $this->fetch('',$result);
    }


    public function lists()
    {
        $data = $this->request->get();
        $cat1 = isset($data['catOne']) && is_numeric($data['catOne']) ? abs(intval($data['catOne'])) : 0;
        $page = isset($data['page']) && is_numeric($data['page']) ? abs(intval($data['page'])) : 1;

        $limit = $this->limit;
        $start = ($page - 1) * $limit;

        if(!empty($cat1)){$where['cat_1'] = $cat1;}
        $where['is_deleted'] = 1;
        $where['status'] = 1;

        $result = array();
        $result['pages'] = $this->_contentTotalPages(Db::name('BasicProduct')->where($where)->count('id'));
        if($result['pages'] && ($contentArr = Db::name('BasicProduct')->field('id,title,local_url,digest,link_url,cat_2,reads,create_at')->where($where)->order('sort desc,id desc')->limit($start,$limit)->select()))
        {
            foreach($contentArr as $contentVal)
            {
                $cvArr['id'] = $contentVal['id'];
                $cvArr['title'] = $contentVal['title'] ? out($contentVal['title']) : '';
                $cvArr['localUrl'] = $contentVal['local_url'] ? out($contentVal['local_url']) : '';
                $cvArr['digest'] = $contentVal['digest'] ? out($contentVal['digest']) : '';
                $cvArr['linkUrl'] = $contentVal['link_url'] ? http_out($contentVal['link_url']) : '';
                $cvArr['reads'] = $contentVal['reads'];
                $cvArr['created'] = $contentVal['create_at'] ? date_time($contentVal['create_at']) : '';

                $result['lists'][] = $cvArr;
            }
        }

        return ajax_feedback(['message'=>BaseLsy::$ERR['SYS']['HANDLE_SUCCESS'],'data'=>$result]);
    }


    public function details()
    {
        $data = $this->request->get();

        $where['id'] = isset($data['id']) && is_numeric($data['id']) ? trim($data['id']) : 0;
        $where['is_deleted'] = 1;
        $where['status'] = 1;

        $result = array();
        if(!empty($where['id']) && ($contentDatas = Db::name('BasicProduct')->field('id,title,keywords,digest,detail,reads,create_at')->where($where)->find()))
        {
            $result['content']['id'] = $contentDatas['id'];
            $result['content']['title'] = $contentDatas['title'] ? out($contentDatas['title']) : '';
            $result['content']['detail'] = $contentDatas['detail'] ? html_out($contentDatas['detail']) : '';
            $result['content']['reads'] = $contentDatas['reads'];
            $result['content']['created'] = $contentDatas['create_at'] ? date_time($contentDatas['create_at']) : '';

            $result['title'] = $result['content']['title'] . '-';
            $result['keywords'] = ($contentDatas['keywords'] ? out($contentDatas['keywords']) : $result['content']['title']) . '-';
            $result['description'] = text_out($contentDatas['digest']) . '-';

            if(Ismobile::checkByAgent())
            {
                $type = client_type()['wap'];
            }else{
                $type = client_type()['web'];
            }
            ProductService::recordContentInfo(['type'=>$type,'ctId'=>$contentDatas['id'],'ctTitle'=>$result['content']['title'],'reads'=>$contentDatas['reads']]);
        }

        return $this->fetch('',$result);
    }


    protected function _contentTotalPages($total = 0)
    {
        if(empty($total))
        {
            $pages = 0;
        }else{
            $total = is_numeric($total) ? abs(intval($total)) : 0;
            $pages = ceil($total / $this->limit);
        }

        return $pages;
    }
}
