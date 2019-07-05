<?php

namespace app\index\controller;

use think\Controller;
use think\Db;

/**
 * 友情链接
 * User: longshangyun@wenwu
 * Date: 2019
 */
class Links extends Controller
{
    public function lkWords()
    {
        return $this->assign('lkWords',$this->_lists(2));
    }

    public function lkImages()
    {
        return $this->assign('lkImages',$this->_lists(1));
    }


    private function _lists($type = 0, $limit = 0)
    {
        $result = array();

        $where['type'] = $type;
        $where['is_deleted'] = 1;
        $where['status'] = 1;

        $photoData = Db::name('BasicLinks')->field('id,title,local_url,link_url')->where($where)->order('sort desc');
        if(!empty($limit)){
            $photoData -> limit($limit);
        }
        $photoDatas = $photoData -> select();
        if($photoDatas)
        {
            foreach($photoDatas as $photoDataVal){
                $res['title'] = $photoDataVal['title'] ? out($photoDataVal['title']) : '';
                $res['localUrl'] = $photoDataVal['local_url'] ? out($photoDataVal['local_url']) : '';
                $res['linkUrl'] = $photoDataVal['link_url'] ? http_out($photoDataVal['link_url']) : '';

                $result[] = $res;
            }
        }

        return $result;
    }
}