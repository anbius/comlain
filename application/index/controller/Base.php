<?php

namespace app\index\controller;

use think\Controller;
use library\lsy\Ismobile;
use app\basic\service\News as NewsService;
use app\basic\service\Product as ProductService;
use app\basic\service\Gallery as GalleryService;

/**
 * 案例
 * User: longshangyun@wenwu
 * Date: 2019/2/28
 * Time: 18:24
 */
class Base extends Controller
{
    protected function initialize()
    {
        // if(Ismobile::checkByAgent())
        // {
        //     header('Location: ' . WEBSITE_WAP . $_SERVER['REQUEST_URI']);
        //     exit;
        // }

        $this->assign('newsCate',NewsService::getContentCateAll());
        $this->assign('productCate',ProductService::getContentCateAll());
        $this->assign('galleryCate',GalleryService::getContentCateAll());
    }
}
