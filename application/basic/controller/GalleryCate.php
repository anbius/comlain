<?php

namespace app\basic\controller;

use library\Controller;
use library\tools\Data;
use think\Db;

/**
 * 企业相册分类管理
 * User: longshangyun@wenwu
 * Date: 2019
 */
class GalleryCate extends Controller
{

    /**
     * 定义当前操作表名
     * @var string
     */
    public $table = 'BasicGalleryCate';

    /**
     * 分类列表
     */
    public function index()
    {
        $this->title = '企业相册分类';
        $this->_query($this->table)->where(['is_deleted' => '1'])->order('status desc,sort desc')->page(false);
    }

    /**
     * 添加菜单
     */
    public function add()
    {
        $this->title = '添加企业相册分类';
        return $this->_form($this->table, 'form');
    }

    /**
     * 编辑菜单
     */
    public function edit()
    {
        $this->title = '编辑企业相册分类';
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

    /**
     * 列表数据处理
     */
    protected function _index_page_filter(&$data)
    {
        foreach ($data as &$vo) {
            $vo['ids'] = join(',', Data::getArrSubIds($data, $vo['id']));
        }
        $data = Data::arr2table($data);
    }

    /**
     * 表单数据前缀方法
     */
    protected function _form_filter(&$vo)
    {
        if ($this->request->isGet()) {
            // 上级菜单处理
            $_menus = Db::name($this->table)->where(['status' => '1'])->order('sort asc,id asc')->select();
            $_menus[] = ['title' => '顶级菜单', 'id' => '0', 'pid' => '-1'];
            $menus = Data::arr2table($_menus);
            foreach ($menus as $key => &$menu) if (substr_count($menu['path'], '-') > 1) unset($menus[$key]); # 移除三级以下的菜单
            elseif (isset($vo['pid']) && $vo['pid'] !== '' && $cur = "-{$vo['pid']}-{$vo['id']}")
                if (stripos("{$menu['path']}-", "{$cur}-") !== false || $menu['path'] === $cur) unset($menus[$key]); # 移除与自己相关联的菜单
            // 选择自己的上级菜单
            if (!isset($vo['pid']) && $this->request->get('pid', '0')) $vo['pid'] = $this->request->get('pid', '0');
            // 读取系统功能节点
            $nodes = \app\admin\service\Auth::get();
            foreach ($nodes as $key => $node) {
                if (empty($node['is_menu'])) unset($nodes[$key]);
                unset($nodes[$key]['pnode'], $nodes[$key]['is_login'], $nodes[$key]['is_menu'], $nodes[$key]['is_auth']);
            }
            list($this->menus, $this->nodes) = [$menus, array_values($nodes)];

            $this->assign('cates', $menus);
        }
    }

    protected function _add_form_filter(&$data)
    {
        if($this->request->isPost())
        {
            $data['status'] = 1;
            $data['sort'] = 0;
            $data['is_deleted'] = 1;
            $data['create_by'] = session('user.id');
            $data['create_at'] = time();
        }
    }
}
