<?php
/**
 * Created by PhpStorm.
 * User: sniper
 * Date: 2019/7/9
 * Time: 16:45
 */
namespace app\interface100;

use library\lsy\Base as BaseService;
use app\basic\service\Common as Common;
use think\Controller;
use think\Db;

/**
 * Class DepartViewInfo
 * 部门详情 接口
 */
class DepartViewInfo extends Controller
{
    protected function initialize()
    {
        //$this->registerModel = D('register');
    }

    public function getViewInfo()
    {
        $data = $this->request->get();
        $data['belongId'] = 5;
        $where['belongId'] = $data['belongId'];
        $viewInfo = Db::name('belong_detail')->field('content')->where($where)->find();
        $code     = 200;
        $message  = '成功';
        $result   = [
            'code'=>$code,
            'message'=>$message,
            'data'=>$viewInfo
        ];
        $res = json_encode($result,JSON_HEX_TAG);
        return $res;
    }

}