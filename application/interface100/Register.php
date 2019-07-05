<?php
/**
 * Created by PhpStorm.
 * User: sniper
 * Date: 2019/7/5
 * Time: 16:58
 */
namespace app\interface100;

use library\lsy\Base as BaseService;
use think\Controller;
use think\Db;

/**
 * Class Register
 * banner管理
 */
class Register extends Controller
{
    protected function initialize()
    {
       //$this->registerModel = D('register');
    }
    /*
     * 注册
     * */
    public function Register()
    {
        $data = $this->request->get();



        $return = ['message'=>BaseService::$ERR['SYS']['HANDLE_SUCCESS'],'data'=>['banners'=>$result]];

        return $return;
    }


    /**
     * 登陆
     */
    public function login()
    {
        $data = $this->request->get();
/**/
     //   $user=Db::name('admin')->where('username','=',$data['username'])->find();
        if($user){
            if($user['password'] == md5($data['password'])){
                session('username',$user['username']);
                session('uid',$user['id']);
                return 3; //信息正确
            }else{
                return 2;//密码错误
            }
        }else{
            return 1;//用户不存在
        }
      /**/

        $return = ['message'=>BaseService::$ERR['SYS']['HANDLE_SUCCESS'],'data'=>$result];

        return $return;
    }
}