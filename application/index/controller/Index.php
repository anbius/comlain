<?php

namespace app\index\controller;

use think\Db;

/**
 * 首页
 * User: longshangyun@wenwu
 * Date: 2019
 */
class Index extends Base
{
    protected function initialize()
    {
        parent::initialize();

        $this->assign('navitem',1);
    }


    public function index()
    {
        $result = array();
        $bannerArr = Db::name('BasicPhoto')->field('id,title,local_url,link_url,digest')->where(['type'=>1,'is_deleted'=>1,'status'=>1])->order('sort desc,id desc')->select(8);
        if($bannerArr)
        {
            foreach($bannerArr as $bannerVal)
            {
                $bvArr['id'] = $bannerVal['id'];
                $bvArr['title'] = $bannerVal['title'] ? out($bannerVal['title']) : '';
                $bvArr['localUrl'] = $bannerVal['local_url'] ? out($bannerVal['local_url']) : '';
                $bvArr['linkUrl'] = $bannerVal['link_url'] ? http_out($bannerVal['link_url']) : '';
                $bvArr['detailFlag'] = $bannerVal['digest'] ? 1 : 0;

                $result['bannerList'][] = $bvArr;
            }
        }

        $contentArr = Db::name('BasicNews')->field('id,title,digest,local_url,link_url,create_at')->where(['is_homepage'=>1,'is_deleted'=>1,'status'=>1])->order('sort desc,id desc')->limit(4)->select();
        if($contentArr)
        {
            foreach($contentArr as $contentVal)
            {
                $cvArr['id'] = $contentVal['id'];
                $cvArr['title'] = $contentVal['title'] ? out($contentVal['title']) : '';
                $cvArr['digest'] = $contentVal['digest'] ? out($contentVal['digest']) : '';
                $cvArr['localUrl'] = $contentVal['local_url'] ? out($contentVal['local_url']) : '';
                $cvArr['linkUrl'] = $contentVal['link_url'] ? http_out($contentVal['link_url']) : '';
                $cvArr['createdYear'] = $contentVal['create_at'] ? date_time($contentVal['create_at'],'Y-m') : '';
                $cvArr['createdDate'] = $contentVal['create_at'] ? date_time($contentVal['create_at'],'d') : '';

                $result['newsHomePageList'][] = $cvArr;
            }
        }

        $contentArr = Db::name('BasicProduct')->field('id,title,digest,local_url,link_url,create_at')->where(['is_homepage'=>1,'is_deleted'=>1,'status'=>1])->order('sort desc,id desc')->limit(8)->select();
        if($contentArr)
        {
            foreach($contentArr as $contentVal)
            {
                $cvArr['id'] = $contentVal['id'];
                $cvArr['title'] = $contentVal['title'] ? out($contentVal['title']) : '';
                $cvArr['digest'] = $contentVal['digest'] ? out($contentVal['digest']) : '';
                $cvArr['localUrl'] = $contentVal['local_url'] ? out($contentVal['local_url']) : '';
                $cvArr['linkUrl'] = $contentVal['link_url'] ? http_out($contentVal['link_url']) : '';
                $cvArr['created'] = $contentVal['create_at'] ? date_time($contentVal['create_at'],'Y-m-d') : '';

                $result['productHomePageList'][] = $cvArr;
            }
        }

        $contentArr = Db::name('BasicGallery')->field('id,title,digest,local_url,link_url,create_at')->where(['is_homepage'=>1,'is_deleted'=>1,'status'=>1])->order('sort desc,id desc')->limit(6)->select();
        if($contentArr)
        {
            foreach($contentArr as $contentVal)
            {
                $cvArr['id'] = $contentVal['id'];
                $cvArr['title'] = $contentVal['title'] ? out($contentVal['title']) : '';
                $cvArr['digest'] = $contentVal['digest'] ? out($contentVal['digest']) : '';
                $cvArr['localUrl'] = $contentVal['local_url'] ? out($contentVal['local_url']) : '';
                $cvArr['linkUrl'] = $contentVal['link_url'] ? http_out($contentVal['link_url']) : '';
                $cvArr['created'] = $contentVal['create_at'] ? date_time($contentVal['create_at'],'Y-m-d') : '';

                $result['galleryHomePageList'][] = $cvArr;
            }
        }

        return $this->fetch('',$result);
    }
}
