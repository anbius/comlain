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
 * ×¢²áµÇÂ½
 */
class Register extends Controller
{
    protected function initialize()
    {
       //$this->registerModel = D('register');
    }
    /*
     * ×¢²á
     * */
    public function Register()
    {
        //ip/index/app.html?edition=1.0.0&interface=Banner&api=lists
        $data = $this->request->get();
        $data = [
            'userName'=>'sniper',
            'password'=>'sniper',
            'mobile'=>'15853197991'
        ];
        $userName = $data['userName'];
        $password = $data['password'];
        $mobile   = $data['mobile'];

       $res =  Db::name('register_user')->data($data)->insert();
        echo '<pre>';
        print_r($res);
        die;

        $return = ['message'=>BaseService::$ERR['SYS']['HANDLE_SUCCESS'],'data'=>['banners'=>$result]];

        return $return;
    }


    /**
     * µÇÂ½
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
                return 3; //ĞÅÏ¢ÕıÈ·
            }else{
                return 2;//ÃÜÂë´íÎó
            }
        }else{
            return 1;//ÓÃ»§²»´æÔÚ
        }
      /**/

        $return = ['message'=>BaseService::$ERR['SYS']['HANDLE_SUCCESS'],'data'=>$result];

        return $return;
    }
}