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



class Complain extends Controller
{
    public $table = 'complain';

    /**
     * 内容列表 普通投诉 登陆用户 能看到所有区域本部门的投诉  但是只能处理 自己所属账号地区的投诉
     */
    public function index()
    {
        $userData = session('user');
        $this->title = '投诉管理';
        $where['status']    = 1;
        $where['is_delete'] = 0;
        $where['is_supervise'] = 0;
        if($userData['belong'] && $userData['belong']!=5){//投诉归属部门  5 为环境保护部门
            $where['complainBelong'] = $userData['belong'];
        }
      //  $this->_query($this->table)->like('title')->equal('type')->where(['is_deleted' => '1'])->timeBetween('create_at')->order('status desc,sort desc,id desc')->page();
        $this->_query($this->table)->like('complainTitle')->equal('complainSource')->where($where)->order('complainBelong desc,id desc')->page();
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
        $this->applyCsrfToken();
        $this->_delete($this->table);
    }

    /**
     * 禁用
     */
    public function forbid()
    {
        $this->applyCsrfToken();
        $this->_save($this->table, ['status' => '0']);
    }

    /**
     * 启用
     */
    public function resume()
    {
        $this->applyCsrfToken();
        $this->_save($this->table, ['status' => '1']);
    }

    public function supervise(){
//        $this->applyCsrfToken();
        $this->_save($this->table, ['is_supervise' => '1']);
    }

    /**
     * 处理数据
     * @param $data
     */
    protected function _page_filter(&$data)
    {
        $areaInfo = Common::getAreaInfo();
        $msg = array(
            'list'=>$areaInfo
        );
        /**
         * todo
         * 查询时候分类查询数据 按照投诉类型
         */
         $search = [
             ['id'=>1,'complainSource'=>'普通投诉'],
             ['id'=>2,'complainSource'=>'重大投诉']
         ];
        $this->assign('cates',$search);
        //是否有督办权限
        $userData = session('user');
        $belong = $userData['belong'];
        if($belong == Common::AllSeeId() || $userData['username']=='admin' ){
            $showSupvise = 1;
        }else{
            $showSupvise  = 0;
        }
        $this->assign('showSupvise',$showSupvise);
       // echo Db::name('complain')->getLastSql();die;
        $sectionArr = Common::section();

        foreach($data as &$val){
            if(!isset($val['sort']))$val['sort'] =[];
            if($val['complainArea']){
                $val['complainArea'] = $this->getTurmAreaName($val['complainArea'],$areaInfo);
            }
            $val['is_accept']      = $val['is_accept'] ?'已受理':'未受理';
            $val['complainBelong'] = Common::choseSection($val['complainBelong'],$sectionArr);;
        }
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
            $data['id'] = Idworks::uniqueID();
            $data['sort'] = 0;
            $data['reads'] = 0;
            $data['status'] = isset($data['status']) ? intval($data['status']) : 1;
            $data['is_deleted'] = 1;
            $data['create_by'] = session('user.id');
        }
    }

    protected function _form_filter(&$data)
    {
        if($this->request->isGet())
        {
           // $this->assign('isHomepage',1);
            $departInfo = Common::section();
            $this->assign('department',$departInfo);
            $user = session('user');
            $area = $user['area'];
            $complainArea = $data['complainArea'];
            $complainAreaArr = explode(',',$complainArea);
            if(!$area){
                $area = '0,0,0';
            }
            $areaArr = explode(',',$area);
            if(empty($areaArr)){
                $areaArr[2] = '';
            }
            if(intval($complainAreaArr[2]) == intval($areaArr[2])){
                $res = 1;
            }else{
                $res = 0;
            }
           if($user['username']=='admin'){
               $res = 1;
           }
            $this->assign('showDeal',$res);

        }

        if ($this->request->isPost())
        {
            $data['complainBelong'] = empty($data['complainBelong']) ? 0 : abs($data['complainBelong']);
            $data['complainDealResult'] = empty($data['complainDealResult']) ? ' ' : $data['complainDealResult'];
            if($data['complainDealResult']){
                $data['is_deal'] = 1;
                $data['dealDT'] = date('Y-m-d');
            }
        }
    }

    protected function _form_result($result)
    {
        if ($result !== false)
        {
            list($base, $spm, $url) = [url('@admin'), $this->request->get('spm'), url('basic/complain/index')];
            $this->success('数据保存成功！', "{$base}#{$url}?spm={$spm}");
        }
    }
}