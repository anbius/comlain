<?php
/**
 * Created by PhpStorm.
 * User: sniper
 * Date: 2019/7/8
 * Time: 22:07
 */

namespace app\interface100;

use library\lsy\Base as BaseService;
use app\basic\service\Common as Common;
use think\Controller;
use think\Db;

/**
 * 投诉表
 *
 */
class ComplainInfo extends Controller
{
    //限制数量
    private $limit = 5;
    private $commentLimit = 10;
    protected function initialize()
    {
        //$this->registerModel = D('register');
    }

    /**
     * 添加投诉接口
     */
    public function insertComplainInfo(){
        $complainInfo = $this->request->post();
//        $complainInfo=[
//            'complainTitle'=>'1',
//            'complainContent'=>'投诉内容',
//            'complainArea'=>'100011,100012,1000123',
//            'complainSource'=>1,
//            'complainBelong'=>5,
//            'complainUser'=>2,
//            'image'=>'',
//        ];
        $insertData['complainTitle']   = $complainInfo['complainTitle'];
        $insertData['complainContent'] = $complainInfo['complainContent'];
        $insertData['complainArea']    = $complainInfo['complainArea'];//投诉地区
     //   $insertData['complainSource']  = $complainInfo['complainSource']; //投诉普通 重大还是 1,普通  2  重大
        $insertData['complainBelong']  = $complainInfo['complainBelong'];  //投诉所属部门
        $insertData['complainUser']    = $complainInfo['complainUser'];  //登录人id
        $insertData['image']           = $complainInfo['image'];//图片地址  多张 中间用，隔开
        $insertData['createDT']        = date('Y-m-d ',time());//

        $res = Db::name('complain')->data($insertData)->insert();
        if($res){
            $message ='成功';
            $code    = 200;
            $complainId = Db::name('complain')->getLastInsID();
        }else{
            $message ='失败';
            $code    = 201;
            $complainId = 0;
        }
        $dataArr['id'] = $complainId;
        return Common::jsonTo($code,$message,$dataArr);
    }
    /**
     * 处理 头数接口 是否受理  是否 重大
     */
    public function dealComplainStatusbyId(){
        $data = $this->request->post();
        $updataId = $data['id'];
        $updata['is_accept'] = $data['is_accept'];
        $updata['complainSource']   = $data['complainSource'];
        $updata['unaccept']   = $data['unaccept'];
        $res = Db::name('complain')->where('id',$updataId)->data($updata)->update();
        if($res){
            $code = 200;
            $message ='更新成功';
            $data = [];
        }else{
            $code = 201;
            $message ='更新失败';
            $data = [];
        }
        return Common::jsonTo($code,$message,$data);
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
     *获取 某个 具体投诉的 详情
     *
     * */
    public function getAllComplainInfobyId()
    {
        $data = $this->request->get();
      //  $data['id'] = 5;
        $where['id']        = $data['id'];
        $where['is_delete'] = 0;
        $sectionArr = Common::section();
        $complainInfo =  Db::name('complain')->field('*')->where($where)->find();
        $complainInfo['is_accept']        =  $complainInfo['is_accept'] ? '已受理':'未受理';
        $complainInfo['is_deal']          =  $complainInfo['is_deal'] ? '处理':'未处理';
        $complainInfo['complainSource']   =  $complainInfo['complainSource']== '1' ? '普通':'重大';
        $complainInfo['is_supervise']     =  $complainInfo['is_supervise'] ? '监督':'';
        $complainInfo['complainBelong']   =  Common::choseSection($complainInfo['complainBelong'],$sectionArr);
        $complainInfo['complainTurnFrom'] =  Common::choseSection($complainInfo['complainTurnFrom'],$sectionArr);
        $complainInfo['complainArea']     =  Common::getTurmAreaName($complainInfo['complainArea'],$sectionArr);
        $code    = '200';
        $message = '成功';
        return Common::jsonTo($code,$message,$complainInfo);
    }

    /**
     *  填写处理意见
     */
    public function dealWithComplain(){
        $data = $this->request->post();

    }
    /**
     * 获取投诉列表内容 分页参数 一页 5条
     */

    public function getAreaAllComplainInfo(){
        $data = $this->request->post();
//        $data = [
//            'complainBelong'=>3,
//            'complainArea'=>'10001,100011,1000114',
//        ];
        $area     = $data['complainArea'];
        $page  = isset($data['page']) && is_numeric($data['page']) ? abs(intval($data['page'])) : 1;
        $limit = $this->limit;
        $start = ($page - 1) * $limit;
        $where['complainBelong'] = $data['complainBelong']; //所属地区
        $where['status']    = 1;
        $where['is_delete'] = 0;
        $where['complainArea'] = $area;
        $result = [];
        $result['total'] = Db::name('complain')->where($where)->count('id');
        $result['pages'] = Common::totalPages($result['total'],$this->limit);
        if($result['pages']){
            $complainInfo = Db::name('complain')->field("*")->order("id desc")->limit($start,$this->limit)->select();
//            echo Db::name('complain')->getLastSql();die;
            foreach($complainInfo as &$complainInfoVal){
                switch($complainInfoVal['is_accept']){
                    case 0:
                        $complainInfoVal['is_accept']  = "未受理";
                        break;
                    case 1:
                        $complainInfoVal['is_accept']  = "已受理";
                        break;
                    case 2:
                        $complainInfoVal['is_accept']  = "拒绝受理";
                        break;
                }
                $complainInfoVal['is_deal']          =  $complainInfoVal['is_deal'] ? '处理':'未处理';
                $complainInfoVal['complainSource']   =  $complainInfoVal['complainSource']== '1' ? '普通':'重大';
                $complainInfoVal['is_supervise']     =  $complainInfoVal['is_supervise'] ? '监督':'';
                $complainInfoVal['complainBelong']   =  Common::choseSection($complainInfoVal['complainBelong']);
                $complainInfoVal['complainTurnFrom'] =  Common::choseSection($complainInfoVal['complainTurnFrom']);
                $complainInfoVal['complainArea']     =  Common::getTurmAreaName($complainInfoVal['complainArea']);
            }
            unset($complainInfoVal);
            $result['lists'][] = $complainInfo;
        }

            $code    = 200;
            $message = '请求成功';echo '<pre>';return  Common::jsonTo($code,$message,$result);

    }

}