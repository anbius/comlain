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
 * 注册登陆
 */
class ComplainInfo extends Controller
{
    protected function initialize()
    {
        //$this->registerModel = D('register');
    }

    /**
     * 写入 投诉表
     */
    public function insertComplainInfo(){
        // 地区问题 待定
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
    /**图片上传
     * @return string
     */
    public function upload(){
        $file = request()->file('image');
        // 移动到框架应用根目录/uploads/ 目录下
        $info = $file->validate(['size'=>15678,'ext'=>'jpg,png,gif'])->move( '../uploads');
        if($info){
            // 成功上传后 获取上传信息
            // 输出 jpg
            echo $info->getExtension();
            // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
            echo $info->getSaveName();
            // 输出 42a79759f284b767dfcb2a0197904287.jpg
            echo $info->getFilename();
        }else{
            // 上传失败获取错误信息
            echo $file->getError();
        }
    }
    /*
     * 获取该部门下的所有投诉内容按照 投诉来源排序
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
                    //查询 匹配拼装图片路径
                }
            }
        }
        //todo
        return '';

    }

    /**
     *  业务员 处理 投诉数据
     */
    public function dealWithComplain(){
        $data = $this->request->post();

    }

}