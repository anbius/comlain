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
 * ע���½
 */
class AreaInfo extends Controller
{
    protected function initialize()
    {
        //$this->registerModel = D('register');
    }
    /*
     * ע��
     * */
    public function AreaInfo()
    {
         $areainfo = [
            '1'=> '�Ļ����β���',
             '2'=>'���й�������ִ������',
             '3'=>'��������',
             '4'=>'�г��ල����',
             '5'=>'��̬���������ۺϼල����',
             '6'=>'������ҽ���ܲ���',
         ];

        return $areainfo;
    }

    public function getAreaInfoById(){
       //
        $data = $this->request->get();
        //��ȡ ����ĳ��������ϸ��Ϣ
    }
}