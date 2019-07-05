<?php

namespace app\basic\service;

use think\Db;

/**
 * 产品展示
 * User: longshangyun@wenwu
 * Date: 2019
 */
class Product
{

    /**
     * 获取所有的分类
     */
    public static function getContentCateAll()
    {
        $cateField = 'id,pid,title';
        $cateWhere = ['status' => '1', 'is_deleted' => '1'];
        $cateList = Db::name('BasicProductCate')->where($cateWhere)->order('sort desc,id asc')->column($cateField);

        return $cateList;
    }


    /**
     * 格式化分类
     */
    public static function getContentCateAllByType()
    {
        $cateList = [];
        $cateListAll = self::getContentCateAll();
        if(!empty($cateListAll))
        {
            foreach($cateListAll as $val){
                if($val['pid'] == 0)
                {
                    $cateList[$val['id']]['id'] = $val['id'];
                    $cateList[$val['id']]['title'] = $val['title'];
                    $cateList[$val['id']]['cates'] = [];
                }else{
                    $cateListCate['id'] = $val['id'];
                    $cateListCate['title'] = $val['title'];

                    $cateList[$val['pid']]['cates'][] = $cateListCate;
                }
            }
        }

        return $cateList;
    }


    /**
     * 获取同一类下所有的父类
     */
    public static function sameContentCate($type = 0)
    {
        $cateList[] = $type;
        $flag = true;
        while($flag)
        {
            $cate = Db::name('BasicProductCate')->field('id,pid')->where(['id' => $type, 'status' => '1', 'is_deleted' => '1'])->order('sort desc,id desc')->find();
            if($cate['pid'] == 0)
            {
                $flag = false;
            }else{
                $cateList[] = $type = $cate['pid'];
            }
        }

        return $cateList;
    }


    /**
     * 1、咨询浏览量 + 1
     * 2、记录浏览者信息
     *
     * web-100
     * wap-200
     * app-300
     * applet-400
     */
    public static function recordContentInfo($data)
    {
        $clientIp = get_client_ip();
        $insert['host_node'] = $clientIp['host_node'];
        $insert['http_ip'] = $clientIp['http_ip'];
        $insert['http_region'] = $clientIp['http_region'];

        $clientData = get_client_data();
        $insert['http_agent'] = $clientData['http_agent'];
        $insert['http_uri'] = $clientData['http_uri'];
        $insert['http_host'] = $clientData['http_host'];

        $insert['http_type'] = isset($data['type']) ? $data['type'] : 0;
        $insert['ct_id'] = isset($data['ctId']) ? $data['ctId'] : 0;
        $insert['ct_title'] = isset($data['ctTitle']) ? $data['ctTitle'] : '';
        $insert['user_id'] = isset($data['userId']) ? $data['userId'] : 0;
        $insert['user_nickname'] = isset($data['userNickname']) ? $data['userNickname'] : '';
        $insert['create_at'] = time();

        $updateReads = isset($data['reads']) && is_numeric($data['reads']) ? trim($data['reads']) : '';
        if($data['reads'] !== ''){Db::name('BasicProduct')->where(['id'=>$insert['ct_id']])->update(['reads'=>$updateReads + 1]);}

        Db::name('LogProductSee')->insert($insert);
    }
}