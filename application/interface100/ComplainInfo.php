<?php
/**
 * Created by PhpStorm.
 * User: sniper
 * Date: 2019/7/6
 * Time: 22:07
 */

namespace app\interface100;

use library\lsy\Base as BaseService;
use think\Controller;
use think\Db;

/**
 * Class Register
 * 注册登陆
 */
class ComplainInfo extends Controller
{
    protected function initialize()
    {
        //$this->registerModel = D('register');
    }

    /**
     * 写入 投诉表
     */
    public function insertComplainInfo(){
        // 地区问题 待定
        $complainInfo = $this->request->post();
        $insertData['complainTitle']   = $complainInfo['title'];
        $insertData['complainContent'] = $complainInfo['content'];
        $insertData['complainArea']    = '';//todo
        $insertData['complainSource']  = 0;
        $insertData['complainBelong']  = $complainInfo['complainBelong'];
        $insertData['complainUser']    = $complainInfo['complainUser'];
        $res = Db::name('complain')->data($insertData)->insert();
        //todo deal $res
    }
    /*
     * 获取该部门下的所有投诉内容按照 投诉来源排序
     * */
    public function getAllComplainInfobyId()
    {
        $data = $this->request->get();
        $where['id'] = $data['id'];
        $complainInfo =  Db::name('complain')->field('*')->where($where)->order('sort desc, complainSource desc')->select();
        if(count($complainInfo)){
            foreach($complainInfo as $ckey=>$cval){
                if($cval['image']){
                    //查询 匹配拼装图片路径
                }
            }
        }
        //todo
        return '';

    }
    /**
     *  业务员 处理 投诉数据
     */
    public function dealWithComplain(){
        $data = $this->request->post();

    }

}