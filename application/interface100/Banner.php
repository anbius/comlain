<?php

namespace app\interface100;

use library\lsy\Base as BaseService;
use think\Controller;
use think\Db;

/**
 * Class Banner
 * banner管理
 */
class Banner extends Controller
{
    public function lists()
    {
        $data = $this->request->get();

        $where['type'] = isset($data['type']) ? abs(intval($data['type'])) : 1;
        $where['is_deleted'] = 1;
        $where['status'] = 1;

        $result = array();
        $bannerDatas = Db::name('BasicPhoto')->field('id,local_url,digest')->where($where)->order('sort desc, id desc')->limit(6)->select();
        if($bannerDatas)
        {
            foreach($bannerDatas as $keys => $vals)
            {
                $returnArr['id'] = $vals['id'];
                $returnArr['imgUrl'] = $vals['local_url'] ? out($vals['local_url']) : '';
                $returnArr['detailFlag'] = $vals['digest'] ? 1 : 0;

                $result[] = $returnArr;
            }
        }

        $return = ['message'=>BaseService::$ERR['SYS']['HANDLE_SUCCESS'],'data'=>['banners'=>$result]];

        return $return;
    }


    /**
     * 详情
     */
    public function details()
    {
        $data = $this->request->get();

        $where['id'] = isset($data['id']) && is_numeric($data['id']) ? strval($data['id']) : 1;

        $where['is_deleted'] = 1;
        $where['status'] = 1;

        $result['details'] = [];
        if($contentDatas = Db::name('BasicPhoto')->field('id,title,detail,create_at')->where($where)->find())
        {
            $result['details']['id'] = $contentDatas['id'];
            $result['details']['title'] = out($contentDatas['title']);
            $result['details']['detail'] = html_out($contentDatas['detail']);
            $result['details']['created'] = date('Y-m-d H:i:s',$contentDatas['create_at']);
        }

        $return = ['message'=>BaseService::$ERR['SYS']['HANDLE_SUCCESS'],'data'=>$result];

        return $return;
    }
}