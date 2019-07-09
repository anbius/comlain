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
 * ?????
 */
class AreaInfo extends Controller
{
    protected function initialize()
    {
        //$this->registerModel = D('register');
    }
    /*
     * ???
     * */
    public function AreaInfo()
    {
         $areainfo = [

         ];

        return $areainfo;
    }

    public function getAreaInfoById(){
       //
        $data = $this->request->get();

    }
}