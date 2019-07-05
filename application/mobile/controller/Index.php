<?php

namespace app\mobile\controller;

use think\Db;

/**
 * 应用入口控制器
 * User: longshangyun@wenwu
 * Date: 2019/2/28
 * Time: 18:24
 */
class Index extends Base
{

    public function index()
    {
        $result['bannerList'] = array();
        $bannerArr = Db::name('BasicPhoto')->field('id,title,local_url,link_url,digest')->where(['type'=>1,'is_deleted'=>1,'status'=>1])->order('sort desc,id desc')->select();
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

        $result['contentHomePageList'] = array();
        $contentArr = Db::name('BasicNews')->field('id,title,digest,local_url,link_url,create_at')->where(['is_homepage'=>1,'is_deleted'=>1,'status'=>1])->order('sort desc,id desc')->limit(6)->select();
        if($contentArr)
        {
            foreach($contentArr as $contentVal)
            {
                $cvArr['id'] = $contentVal['id'];
                $cvArr['title'] = $contentVal['title'] ? out($contentVal['title']) : '';
                $cvArr['digest'] = $contentVal['digest'] ? out($contentVal['digest']) : '';
                $cvArr['localUrl'] = $contentVal['local_url'] ? out($contentVal['local_url']) : '';
                $cvArr['linkUrl'] = $contentVal['link_url'] ? http_out($contentVal['link_url']) : '';
                $cvArr['created'] = $contentVal['create_at'] ? date_time($contentVal['create_at']) : '';

                $result['contentHomePageList'][] = $cvArr;
            }
        }

        $this->assign('title','首页-');

        return $this->fetch('',$result);
    }
}