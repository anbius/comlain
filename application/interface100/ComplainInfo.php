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
 * ע���½
 */
class ComplainInfo extends Controller
{
    protected function initialize()
    {
        //$this->registerModel = D('register');
    }

    /**
     * д�� Ͷ�߱�
     */
    public function insertComplainInfo(){
        // �������� ����
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
     * ��ȡ�ò����µ�����Ͷ�����ݰ��� Ͷ����Դ����
     * */
    public function getAllComplainInfobyId()
    {
        $data = $this->request->get();
        $where['id'] = $data['id'];
        $complainInfo =  Db::name('complain')->field('*')->where($where)->order('sort desc, complainSource desc')->select();
        if(count($complainInfo)){
            foreach($complainInfo as $ckey=>$cval){
                if($cval['image']){
                    //��ѯ ƥ��ƴװͼƬ·��
                }
            }
        }
        //todo
        return '';

    }
    /**
     *  ҵ��Ա ���� Ͷ������
     */
    public function dealWithComplain(){
        $data = $this->request->post();

    }

}