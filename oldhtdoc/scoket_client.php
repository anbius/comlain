<?php
header('content-type:text/html;charset=gbk');
//����һ��socket�׽���
$socket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
/****************����socket����ѡ����������������ʡ��*************/
//�����׽��������ʱʱ��1�룬������΢�뵥λ��ʱʱ�䣬����Ϊ�㣬��ʾ������
socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array("sec" => 1, "usec" => 0));
//�����׽��������ʱʱ��Ϊ6��
socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, array("sec" => 6, "usec" => 0));
/****************����socket����ѡ����������������ʡ��*************/

//���ӷ���˵��׽�������һ������ʹ�ͻ�����������˵��׽���������ϵ
if(socket_connect($socket,'127.0.0.1',8888) == false){
    echo 'connect fail massege:'.socket_strerror(socket_last_error());
}else{
    $message = 'l love you �Ұ��� socket';
    //תΪGBK���룬�����������⣬��Ҫ����ı������������ÿ���˵ı��붼��ͬ
    //$message = mb_convert_encoding($message,'GBK','UTF-8');
    //������д���ַ�����Ϣ

    if(socket_write($socket,$message,strlen($message)) == false){
        echo 'fail to write'.socket_strerror(socket_last_error());

    }else{
        echo 'client write success'.PHP_EOL;
        //��ȡ����˷��������׽�����Ϣ
        while($callback = socket_read($socket,1024)){
            echo 'server return message is:'.PHP_EOL.$callback;
        }
    }
}
socket_close($socket);//������ϣ��ر��׽���