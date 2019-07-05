<?php

namespace app\index\controller;

use think\Db;
use library\lsy\Base as BaseLsy;
use library\lsy\Idworks;
use library\lsy\Tncode as TncodeLsy;

/**
 * 留言反馈
 * User: longshangyun@wenwu
 * Date: 2019
 */
class Feedback extends Base
{
    protected function initialize()
    {
        parent::initialize();

        $this->assign('navitem',6);
    }


    public function index()
    {
        return view();
    }


    public function commit()
    {
        $message = BaseLsy::$ERR['SYS']['HANDLE_ERROR'];

        $data = $this->request->post();

        $title = isset($data['title']) && preg_match(BaseLsy::$pregMatchBlank,$data['title']) ? in(trim($data['title'])) : '';
        $username = isset($data['username']) && preg_match(BaseLsy::$pregMatchBlank,$data['username']) ? in(trim($data['username'])) : '';
        $phone = isset($data['phone']) && preg_match(BaseLsy::$pregMatchBlank,$data['phone']) ? in(trim($data['phone'])) : '';
        $email = isset($data['email']) && preg_match(BaseLsy::$pregMatchEmail,$data['email']) ? in(trim($data['email'])) : '';
        $address = isset($data['address']) && preg_match(BaseLsy::$pregMatchBlank,$data['address']) ? in(trim($data['address'])) : '';
        $comment = isset($data['content']) && preg_match(BaseLsy::$pregMatchBlank,$data['content']) ? text_in(trim($data['content'])) : '';

        $tn = new TncodeLsy();
        $tncode = session('tncode.check','',$tn->tcd);

        if(!empty($tncode) && !empty($title) && !empty($comment))
        {
            $record['id'] = Idworks::uniqueID();
            $record['title'] = $title;
            $record['username'] = $username;
            $record['phone'] = $phone;
            $record['email'] = $email;
            $record['address'] = $address;
            $record['comment'] = $comment;

            $clientIp = get_client_ip();
            $record['http_ip'] = $clientIp['http_ip'];
            $record['http_region'] = $clientIp['http_region'];

            $clientData = get_client_data();
            $record['http_agent'] = $clientData['http_agent'];

            $record['type'] = 0;
            $record['sort'] = 0;
            $record['status'] = 1;
            $record['is_deleted'] = 1;
            $record['create_at'] = time();

            if(Db::name('BasicFeedback')->insert($record))
            {
                $message = BaseLsy::$ERR['SYS']['HANDLE_SUCCESS'];
            }
        }

        return ajax_feedback(['message'=>$message,'data'=>[]]);
    }
}
