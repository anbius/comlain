<?php
/**
 * Created by PhpStorm.
 * User: sniper
 * Date: 2019/7/5
 * Time: 16:58
 */
namespace app\interface100;

use library\lsy\Base as BaseService;
use app\basic\service\Common as Common;
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

        $dataRegister = $this->request->post();
        //普通投诉用户注册 默认字段
        //ip/index/app.html?edition=1.0.0&interface=Banner&api=lists
//        $data = [
//            'userName'=>'sniper',
//            'password'=>'sniper',
//            'mobile'=>'15853197991'
//        ];
        $where['userName']      = $dataRegister['userName'];
        $insertData['userName'] = $dataRegister['userName'];
        $insertData['password'] = md5($dataRegister['password']);
        $insertData['mobile']   = $dataRegister['mobile'];
        /*检查唯一性 用户名注册*/
        $judge = Db::name('register_user')->where($where)->find();
        if($judge){
            $code    = 201;
            $message ='用户名重复';
            $res    = '失败';
            $return = ['code'=>$code,'message'=>$message,'res'=>$res];

            return json_encode($return);
        }
        $result =  Db::name('register_user')->data($insertData)->insert();
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
        $data = $this->request->post();
        $inputPassword = md5($data['password']);
        $login['userName'] = $data['userName'] ;
        $login['password'] = $inputPassword ;
        $user = Db::name('register_user')->field('*')->where($login)->find();
     //   $user=Db::name('admin')->where('username','=',$data['username'])->find();
        $userInfo = [];
        if($user['id']){
            if($user['password'] == $inputPassword){
                $message = '登陆成功';
                $userInfo = $user;
                $code  = 3;
            }else{
                $code  = 2;
                $message = '密码错误';
                //密码错误
            }
        }else{
            $code    = 1;
            $message = '用户不存在';
           //用户不存在
        }
        return Common::jsonTo($code,$message,$userInfo);
    }
}