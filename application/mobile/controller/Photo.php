<?php

namespace app\mobile\controller;

use think\Db;

/**
 * 图片详情
 * User: longshangyun@wenwu
 * Date: 2019/2/28
 * Time: 18:24
 */
class Photo extends Base
{
    protected function initialize()
    {
        parent::initialize();

        $this->assign('navitem',4);
    }


    public function banner()
    {
        return $this->fetch('baike/details',$this->_details(1,$this->request->get()));
    }


    private function _details($type = 0, $data = array())
    {
        $result = array();
        if(empty($type) || empty($data['id']) || !is_numeric($data['id'])){return $result;}

        $where['id'] = trim($data['id']);
        $where['type'] = $type;
        $where['is_deleted'] = 1;
        $where['status'] = 1;

        if($photoDatas = Db::name('BasicPhoto')->field('id,title,detail,digest,create_at')->where($where)->find())
        {
            $result['content']['title'] = $photoDatas['title'] ? out($photoDatas['title']) : '';
            $result['content']['detail'] = $photoDatas['detail'] ? html_out($photoDatas['detail']) : '';

            $result['title'] = $result['content']['title'] . '-';
            $result['keywords'] = $result['content']['title'] . '-';
            $result['description'] = text_out($photoDatas['digest']) . '-';
        }

        return $result;
    }
}