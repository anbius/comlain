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
    /**ͼƬ�ϴ�
     * @return string
     */
    public function upload(){
        $file = request()->file('image');
        // �ƶ������Ӧ�ø�Ŀ¼/uploads/ Ŀ¼��
        $info = $file->validate(['size'=>15678,'ext'=>'jpg,png,gif'])->move( '../uploads');
        if($info){
            // �ɹ��ϴ��� ��ȡ�ϴ���Ϣ
            // ��� jpg
            echo $info->getExtension();
            // ��� 20160820/42a79759f284b767dfcb2a0197904287.jpg
            echo $info->getSaveName();
            // ��� 42a79759f284b767dfcb2a0197904287.jpg
            echo $info->getFilename();
        }else{
            // �ϴ�ʧ�ܻ�ȡ������Ϣ
            echo $file->getError();
        }
    }
    /*
     * ��ȡ�ò����µ�����Ͷ�����ݰ��� Ͷ����Դ����
     * */
    public function getAllComplainInfobyId()
    {
        $data = $this->request->get();
        $where['id']        = $data['id'];
        $where['is_delete'] = 0;
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