<?php

namespace app\index\controller;

use library\lsy\Tncode as TncodeLsy;
use library\tools\Cors;

/**
 * 验证码
 * User: longshangyun@wenwu
 * Date: 2019
 */
class Tncode
{


    public function __construct()
    {
        Cors::setOptionHandler(request());
    }


    public function make()
    {
        $tn = new TncodeLsy();
        $tn -> make();
    }


    public function check()
    {
        $tn = new TncodeLsy();
        if($tn -> check()){
            session('tncode.check',1,$tn->tcd);
            return 'ok';
        }else{
            session('tncode.check',0,$tn->tcd);
        }

        return 'error';
    }
}