<?php

namespace app\admin\controller;

use library\Controller;
use think\Console;

/**
 * 系统参数配置
 * Class Config
 * @package app\admin\controller
 */
class Config extends Controller
{
    /**
     * 默认数据模型
     * @var string
     */
    protected $table = 'SystemConfig';

    /**
     * 系统参数配置
     * @return mixed
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function info()
    {
        $this->applyCsrfToken();
        if ($this->request->isGet()) {
            $this->title = '系统参数配置';
            $this->fetch();
        } else {
            foreach ($this->request->post() as $k => $v) sysconf($k, $v);
            $this->success('系统参数配置成功！');
        }
    }

    /**
     * 文件存储配置
     * @return string
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function file()
    {
        $this->applyCsrfToken();
        if ($this->request->isGet()) {
            $this->fetch('file', [
                'title' => '文件存储配置',
                'point' => [
                    'oss-cn-hangzhou.aliyuncs.com'    => '华东 1 杭州',
                    'oss-cn-shanghai.aliyuncs.com'    => '华东 2 上海',
                    'oss-cn-qingdao.aliyuncs.com'     => '华北 1 青岛',
                    'oss-cn-beijing.aliyuncs.com'     => '华北 2 北京',
                    'oss-cn-zhangjiakou.aliyuncs.com' => '华北 3 张家口',
                    'oss-cn-huhehaote.aliyuncs.com'   => '华北 5 呼和浩特',
                    'oss-cn-shenzhen.aliyuncs.com'    => '华南 1 深圳',
                    'oss-cn-hongkong.aliyuncs.com'    => '香港 1',
                    'oss-us-west-1.aliyuncs.com'      => '美国西部 1 硅谷',
                    'oss-us-east-1.aliyuncs.com'      => '美国东部 1 弗吉尼亚',
                    'oss-ap-southeast-1.aliyuncs.com' => '亚太东南 1 新加坡',
                    'oss-ap-southeast-2.aliyuncs.com' => '亚太东南 2 悉尼',
                    'oss-ap-southeast-3.aliyuncs.com' => '亚太东南 3 吉隆坡',
                    'oss-ap-southeast-5.aliyuncs.com' => '亚太东南 5 雅加达',
                    'oss-ap-northeast-1.aliyuncs.com' => '亚太东北 1 日本',
                    'oss-ap-south-1.aliyuncs.com'     => '亚太南部 1 孟买',
                    'oss-eu-central-1.aliyuncs.com'   => '欧洲中部 1 法兰克福',
                    'oss-eu-west-1.aliyuncs.com'      => '英国 1 伦敦',
                    'oss-me-east-1.aliyuncs.com'      => '中东东部 1 迪拜',
                ],
            ]);
        } else {
            $post = $this->request->post();
            if (isset($post['storage_type']) && $post['storage_type'] === 'local') {
                $exts = array_unique(explode(',', $post['storage_local_exts']));
                if (in_array('php', $exts)) $this->error('禁止上传可执行文件到本地服务器！');
                $post['storage_local_exts'] = join(',', $exts);
            }
            foreach ($post as $key => $value) sysconf($key, $value);
            if (isset($post['storage_type']) && $post['storage_type'] === 'oss') {
                try {
                    $local = sysconf('storage_oss_domain');
                    $bucket = $this->request->post('storage_oss_bucket');
                    $domain = \library\File::instance('oss')->setBucket($bucket);
                    if (empty($local) || stripos($local, '.aliyuncs.com') !== false) {
                        sysconf('storage_oss_domain', $domain);
                    }
                    $this->success('阿里云OSS存储动态配置成功！');
                } catch (\think\exception\HttpResponseException $exception) {
                    throw $exception;
                } catch (\Exception $e) {
                    $this->error("阿里云OSS存储配置失效，{$e->getMessage()}");
                }
            } else {
                $this->success('文件存储配置成功！');
            }
        }
    }

    /**
     * 清理系统运行缓存
     */
    public function clearRuntime()
    {
        if (!\app\admin\service\Auth::isLogin()) {
            $this->error('需要登录才能操作哦！');
        }
        $this->list = [
            [
                'title'   => 'Clean up running cached files',
                'message' => nl2br(Console::call('clear')->fetch()),
            ], [
                'title'   => 'Clean up invalid session files',
                'message' => nl2br(Console::call('xclean:session')->fetch()),
            ],
        ];
        $this->fetch('admin@index/command');
    }

    /**
     * 压缩发布系统
     */
    public function buildOptimize()
    {
        if (!\app\admin\service\Auth::isLogin()) {
            $this->error('需要登录才能操作哦！');
        }
        $this->list = [
            //TODO 非二三级域名绑定admin，不可开启此选择 @1.1
//            [
//                'title'   => 'Build route cache',
//                'message' => nl2br(Console::call('optimize:route')->fetch()),
//            ],
            [
                'title'   => 'Build database schema cache',
                'message' => nl2br(Console::call('optimize:schema')->fetch()),
            ], [
                'title'   => 'Optimizes PSR0 and PSR4 packages',
                'message' => nl2br(Console::call('optimize:autoload')->fetch()),
            ], [
                'title'   => 'Build config and common file cache',
                'message' => nl2br(Console::call('optimize:config')->fetch()),
            ],
        ];
        $this->fetch('admin@index/command');
    }
}