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
 * ע���½
 */
class Register extends Controller
{
    protected function initialize()
    {
       //$this->registerModel = D('register');
    }
    /*
     * ע��
     * */
    public function Register()
    {
        $file = request();
        echo '<pre>';
        print_r($file);
        die;
        //todo ������� ��֤ �Ƿ��ظ�  ����MD5һ��
        //��ͨͶ���û�ע�� Ĭ���ֶ�
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
        /*���Ψһ�� �û���ע��*/
        $judge = Db::name('register_user')->where($where)->find();
        if($judge){
            $result = 4;
            return  ['message'=>BaseService::$ERR['USER']['HANDLE_SUCCESS'],'data'=>['reigster'=>$result]];
        }
        $result =  Db::name('register_user')->data($data)->insert();
        echo '<pre>';
        print_r($result);
        die;

        $return = ['message'=>BaseService::$ERR['SYS']['HANDLE_SUCCESS'],'data'=>['reigster'=>$result]];

        return $return;
    }


    /**
     * ��½
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
               //�������
            }
        }else{
            $message = BaseService::$ERR['USER']['REGISTER_USER_ERROR'];
            $result  = 1;
           //�û�������
        }
      /**/
        $return = ['message'=>$message,'data'=>$result];
        return $return;
    }
}