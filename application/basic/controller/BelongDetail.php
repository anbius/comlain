<?php
/**
 * Created by PhpStorm.
 * User: sniper
 * Date: 2019/7/7
 * Time: 10:52
 */

namespace app\basic\Controller;
use library\Controller;
use library\lsy\Idworks;
use library\tools\Data;
use app\basic\service\Common as Common;
use think\Db;



class BelongDetail extends Controller
{
    public $table = 'BelongDetail';

    /**
     * 内容列表 普通投诉 登陆用户 能看到所有区域本部门的投诉  但是只能处理 自己所属账号地区的投诉
     */
    public function index()
    {
        $userData = session('user');
        $this->title = '详情';
       // $userData['belong'] = 5;
        $where['belongId'] = $userData['belong'];
        //  $this->_query($this->table)->like('title')->equal('type')->where(['is_deleted' => '1'])->timeBetween('create_at')->order('status desc,sort desc,id desc')->page();
        $this->_query($this->table)->where($where)->page();
    }

    /**
     * 添加内容
     */
    public function add()
    {
        $this->title = '内容添加';
        return $this->_form($this->table, 'form');
    }

    /**
     * 编辑内容
     */
    public function edit()
    {
        $this->title = '内容编辑';
        return $this->_form($this->table, 'form');
    }

    /**
     * 删除
     */
    public function del()
    {
       // $this->applyCsrfToken();
        $this->_delete($this->table);
    }

    /**
     * 处理数据
     * @param $data
     */
    protected function _page_filter(&$data)
    {

        $userData = session('user');
        $belong = $userData['belong'];
        $sectionArr = Common::section();
        foreach($data as &$val){
            $val['belongId'] = $this->choseSection($val['belongId'],$sectionArr);
        }
        $search['belongId'] = $belong;
        $res = Db::name('belong_detail')->field('id')->where($search)->find();
        if($res){
              $hasdoneDetail = 1;
        }else{
            $hasdoneDetail = 0;
        }
        $this->assign('hasdoneDetail',$hasdoneDetail);

    }
    public function choseSection($belongId,$sectionArr){
        foreach($sectionArr as $seckey=>$section){
            if(intval($belongId) ==intval($section['id'])){
                $belongName = $section['name'];
                break;
            }else{
                $belongName = '暂无';
            }
        }
        return $belongName;
    }
    public function getTurmAreaName($area,$areaInfo){
        $areaArr    = explode(',',$area);
        $provinceId = $areaArr[0];
        $cityId     = $areaArr[1];
        $areaId     = $areaArr[2];
        //省
        foreach($areaInfo['province'] as $proVal){
            if(intval($proVal['id'])==intval($provinceId)){
                $provinceName = $proVal['title'];
                break;
            }
        }
        //市
        foreach($areaInfo['city'] as $cityKey=>$cityArr){
            if($cityKey ==$provinceId ){
                foreach($cityArr as $cityVal){
                    if(intval($cityVal['id']) == intval($cityId)){
                        $cityName = $cityVal['title'];
                        break;
                    }
                }
            }
        }
        //区
        foreach($areaInfo['area'] as $areakey=>$areaArrs){
            if($areakey == $cityId ){
                foreach($areaArrs as $areVal){
                    if(intval($areVal['id']) == intval($areaId)){
                        $areaName = $areVal['title'];
                        break;
                    }else{
                        $areaName = '';
                    }
                }
            }
        }
        $res = $provinceName.','.$cityName.','.$areaName;
        return $res;
    }
    protected function _add_form_filter(&$data)
    {
        if($this->request->isPost())
        {
//            $data['id'] = Idworks::uniqueID();
//            $data['sort'] = 0;
//            $data['reads'] = 0;
//            $data['status'] = isset($data['status']) ? intval($data['status']) : 1;
//            $data['is_deleted'] = 1;
//            $data['create_by'] = session('user.id');
        }
    }

    protected function _form_filter(&$data)
    {

        if($this->request->isPost())
        {
            $userData = session('user');
            $data['belongId'] = $userData['belong'];
//            if($data['id'])unset($data['id']);//todo
        }

    }

    protected function _form_result($result)
    {
        //todo  插入id 出线问题  明天看看
        if ($result !== false)
        {
            list($base, $spm, $url) = [url('@admin'), $this->request->get('spm'), url('basic/belongDetail/index')];
            $this->success('数据保存成功！', "{$base}#{$url}?spm={$spm}");
        }
    }
}