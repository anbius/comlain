<?php
/**
 * Created by PhpStorm.
 * User: sniper
 * Date: 2019/7/6
 * Time: 19:55
 */
namespace app\interface100;

use library\lsy\Base as BaseService;
use think\Controller;
use think\Db;

/**
 * Class Register
 * 注册登陆
 */
class AreaInfo extends Controller
{
    protected function initialize()
    {
        //$this->registerModel = D('register');
    }
    /*
     * 注册
     * */
    public function AreaInfo()
    {
         $areainfo = [
            '1'=> '文化旅游部门',
             '2'=>'城市管理行政执法部门',
             '3'=>'公安部门',
             '4'=>'市场监督部门',
             '5'=>'生态环境保护综合监督部门',
             '6'=>'畜牧兽医主管部门',
         ];

        return $areainfo;
    }

    public function getAreaInfoById(){
       //
        $data = $this->request->get();
        //获取 具体某个部门详细信息
    }
}