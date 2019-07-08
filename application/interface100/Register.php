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
 * 注册登陆
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
        $result = 1;
        $return = ['code'=>200,'message'=>'成功','res'=>$result];

        echo '<pre>';
print_r($return);
print_r(json_encode($return));
die;
        $file = request();
        echo '<pre>';
        print_r($file);
        die;
        //todo 密码加密 验证 是否重复  密码MD5一下
        //普通投诉用户注册 默认字段
        //ip/index/app.html?edition=1.0.0&interface=Banner&api=lists
        $data = request()->post();
        $data = [
            'userName'=>'sniper',
            'password'=>'sniper',
            'mobile'=>'15853197991'
        ];

        $userName = $data['userName'];
        $password = $data['password'];
        $mobile   = $data['mobile'];
        $where['userName'] = $userName;
        /*检查唯一性 用户名注册*/
        $judge = Db::name('register_user')->where($where)->find();
        if($judge){
            $code    = 201;
            $message ='用户名重复';
            $res    = '失败';
            $return = ['code'=>$code,'message'=>$message,'res'=>$res];

            return json_encode($return);
        }
        $result =  Db::name('register_user')->data($data)->insert();
        if($result){
            $code    = 200;
            $message ='注册成功';
            $res     = '成功';
        }else{
            $code    = 203;
            $message ='注册失败，请稍候再试';
            $res     = '失败';
        }
        $return = ['code'=>$code,'message'=>$message,'res'=>$res];
        return json_encode($return);
    }


    /**
     * 登陆
     */
    public function login()
    {
        $data = $this->request->get();
/**/

        $login['userName'] = $data['userName'];
      //  $login['password'] = $data['password'];
        $user = Db::name('register_user')->field('password')->where($login)->find();
     //   $user=Db::name('admin')->where('username','=',$data['username'])->find();
        if($user){
            if($user['password'] == $data['password']){
                session('username',$user['username']);
                session('uid',$user['id']);
                $message = BaseService::$ERR['USER']['LOGIN_SUCCESS'];
                $result  = 3;
            }else{
                $message = BaseService::$ERR['USER']['LOGIN_USER_ERR'];
                $result  = 2;
               //密码错误
            }
        }else{
            $message = BaseService::$ERR['USER']['REGISTER_USER_ERROR'];
            $result  = 1;
           //用户不存在
        }
      /**/
        $return = ['message'=>$message,'data'=>$result];
        return $return;
    }
}