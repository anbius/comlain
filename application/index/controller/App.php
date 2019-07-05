<?php

namespace app\index\controller;

use think\Controller;
use think\Db;
use library\lsy\Base;
use library\tools\Cors;
use think\Exception;

class App extends Controller
{

    public function index()
    {
        try{
            $requestArr = $this->request->get();
            $edition      = isset($requestArr['edition']) ? $requestArr['edition'] : '';
            $interface = isset($requestArr['interface']) ? $requestArr['interface'] : '';
            $api       = isset($requestArr['api']) ? $requestArr['api'] : '';

            if(empty($edition) || empty($interface) || empty($api))
            {
                throw new Exception(Base::$ERR['SYS']['INTERFACE_NOT_EXIST']);
            }

            $editionArray = explode('.', $edition);
            $major = isset($editionArray[0]) ? abs(intval($editionArray[0])) : 0;
            $minor = isset($editionArray[1]) ? abs(intval($editionArray[1])) : 0;
            $futile = isset($editionArray[2]) ? abs(intval($editionArray[2])) : 0;

            if($major < 1 && $minor < 1 && $futile < 1)
            {
                throw new Exception(Base::$ERR['SYS']['INTERFACE_NOTICE']);
            }else{ //1.*版本
                $result = self::invokeFun($interface,$api,$major.$minor.$futile);
                if(!empty($result['header']) && is_array($result['header'])){$header = $result['header'];}else{$header = Cors::getRequestHeader($this->request);}
                unset($result['header']);
                return json($result,200,$header);
            }
        }catch(Exception $e){
            if(stripos('|',$e->getMessage()) !== FALSE)
            {
                $error = explode('|',$e->getMessage());
                $return = json_encode(self::feedback([], $error[0], $error[1]));
            }else{
                $return = json_encode($e->getMessage());
            }

            die($return);
        }
    }


    /**
     * 在远端调用指定的接口，并返回接口调用后返回的数据
     */
    private static function invokeFun($interface, $function, $edition)
    {
        $function = strval($function);
        try{
            $agent = self::makeIter($interface, $edition);
            if(method_exists($agent, $function))
            {
                $return = $agent->$function ();

                $message = explode('|',$return['message']);
                $data = isset($return['data']) ? $return['data'] : [];
                $header = isset($return['header']) ? $return['header'] : [];

                return self::feedback($data,$message[0],$message[1],$header);
            }else{
                throw new Exception (Base::$ERR['SYS']['INTERFACE_WARNING']);
            }
        }catch(Exception $e){
            $error = explode('|', $e->getMessage());
            return self::feedback([], $error[0], $error[1]);
        }
    }


    /**
     * 接口类名称
     */
    private static function makeIter($interface, $edition)
    {
        $tcname    = ucfirst(strval($interface));   //类名
        $tcfname   = dirname(dirname(__DIR__)) . '/interface' . $edition . '/' . $tcname . '.php';//类的文件路径

        try{
            if(file_exists($tcfname))
            {
                include_once($tcfname);

                if(class_exists('app\interface' . $edition. '\\' . $tcname,false))
                {
                    $name = '\app\interface' .$edition. '\\'.$tcname;
                    return new $name();   //生成此类
                }
            }

            throw new Exception (Base::$ERR['SYS']['INTERFACE_ERROR']);
        }catch(Exception $e){
            $error = explode('|', $e->getMessage());
            die(json_encode(self::feedback([], $error[0], $error[1])));
        }
    }


    /**
     * 拼装接口调用后的反馈数据
     */
    private static function feedback($data, $error = 500, $msg = 'faild', $success = false,$header = array())
    {
        if($error == 200){
            $success = true;
        }

        return array(
            'header' => $header,
            'data' => $data,
            'code' => $error,
            'msg' => $msg,
            'success' => $success
        );
    }
}