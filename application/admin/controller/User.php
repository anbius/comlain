<?php

namespace app\admin\controller;

use library\Controller;
use library\tools\Data;
use app\basic\service\Common as Common;
use think\Db;

/**
 * 系统用户管理
 * Class User
 * @package app\admin\controller
 */
class User extends Controller
{

    /**
     * 指定当前数据表
     * @var string
     */
    public $table = 'SystemUser';

    /**
     * 系统用户管理
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function index()
    {
        $this->title = '系统用户管理';
        $this->_query($this->table)->where(['is_deleted' => '1'])->like('username,phone,mail')->timeBetween('login_at')->equal('status')->page();
    }

    /**
     * 用户授权管理
     * @return mixed
     */
    public function auth()
    {
        $this->applyCsrfToken();
        $this->_form($this->table, 'auth');
    }

    /**
     * 添加系统用户
     * @return mixed
     */
    public function add()
    {
        $this->applyCsrfToken();
        $departInfo = Common::section();
        $this->assign('department',$departInfo);
        $areaInfo = Common::getAreaInfo();
        $area     = $areaInfo['area']['100011'];
        $this->assign('areaArr',$area);
        $this->_form($this->table, 'form');
    }

    /**
     * 编辑系统用户
     * @return mixed
     */
    public function edit()
    {
        $this->applyCsrfToken();
        $departInfo = Common::section();
        $this->assign('department',$departInfo);
        $areaInfo = Common::getAreaInfo();
        $area     = $areaInfo['area']['100011'];
        $this->assign('areaArr',$area);
        $this->_form($this->table, 'form');
    }

    /**
     * 修改用户密码
     * @return mixed
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function pass()
    {
        $this->applyCsrfToken();
        if ($this->request->isGet()) {
            $this->verify = false;
            $this->_form($this->table, 'pass');
        } else {
            $post = $this->request->post();
            if ($post['password'] !== $post['repassword']) {
                $this->error('两次输入的密码不一致！');
            }
            $result = \app\admin\service\Auth::checkPassword($post['password']);
            if (empty($result['code'])) $this->error($result['msg']);
            $data = ['id' => $post['id'], 'password' => md5($post['password'])];
            if (Data::save($this->table, $data, 'id')) {
                $this->success('密码修改成功，下次请使用新密码登录！', '');
            } else {
                $this->error('密码修改失败，请稍候再试！');
            }
        }
    }

    /**
     * 删除系统用户
     */
    public function del()
    {
        if (in_array('10000', explode(',', $this->request->post('id')))) {
            $this->error('系统超级账号禁止删除！');
        }
        if (in_array('10001', explode(',', $this->request->post('id')))) {
            $this->error('陇上云BUG修复账号请勿删除！');
        }
        $this->applyCsrfToken();
        $this->_delete($this->table);
    }

    /**
     * 禁用系统用户
     */
    public function forbid()
    {
        if (in_array('10000', explode(',', $this->request->post('id')))) {
            $this->error('系统超级账号禁止操作！');
        }
        $this->applyCsrfToken();
        $this->_save($this->table, ['status' => '0']);
    }

    /**
     * 启用系统用户
     */
    public function resume()
    {
        $this->applyCsrfToken();
        $this->_save($this->table, ['status' => '1']);
    }

    /**
     * 表单数据处理
     * @param array $data
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function _form_filter(&$data)
    {
        if ($this->request->isPost()) {
            /**/
            if(!isset($data['autherSubmit'])){
                if(!$data['area']){
                    $this->error('请选择地区');
                }
                if(!isset($data['belong']))$data['belong'] = '';
                if(!$data['belong']){
                    $this->error('请选择用户所在部门');
                }
                $data['area'] = $data['province'].','.$data['city'].','.$data['area'];
                unset($data['province']);
                unset($data['city']);
            }
            /**/
            $data['authorize'] = (isset($data['authorize']) && is_array($data['authorize'])) ? join(',', $data['authorize']) : '';
            if (isset($data['id'])) unset($data['username']);
            elseif (Db::name($this->table)->where(['username' => $data['username']])->count() > 0) {
                $this->error('用户账号已经存在，请使用其它账号！');
            }
        } else {
            if(!isset($data['area']))$data['area']='';
            if(!isset($data['belong']))$data['belong']='';
            if(count($data)){
                $areaInfo = explode(',',$data['area']);
                if(count($areaInfo)){
                    if(!isset($areaInfo[2]))$areaInfo[2]=0;
                    $data['area'] = $areaInfo[2];
                }
            }

            $data['authorize'] = explode(',', isset($data['authorize']) ? $data['authorize'] : '');
            $this->assign('authorizes', Db::name('SystemAuth')->where(['status' => '1'])->select());
        }
    }

    protected function _add_form_filter(&$data)
    {
        if($this->request->isPost())
        {
            $data['password'] = md5('123456');
            $data['status'] = 1;
            $data['is_deleted'] = 1;
            $data['create_by'] = session('user.id');
            $data['create_at'] = time();
        }
    }

    protected function _page_filter(&$data)
    {
        $areaInfo = Common::getAreaInfo();
        // echo Db::name('complain')->getLastSql();die;
        $sectionArr = Common::section();

        foreach($data as &$val){
            if($val['area']){
                $val['area'] = $this->getTurmAreaName($val['area'],$areaInfo);
            }
            $val['belong'] = $this->choseSection($val['belong'],$sectionArr);
        }
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

}
