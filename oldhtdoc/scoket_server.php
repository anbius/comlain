<?php
//��������˵�socket�׽���,netЭ��ΪIPv4��protocolЭ��ΪTCP
$socket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);

/*�󶨽��յ��׽��������Ͷ˿�,��ͻ������Ӧ*/
if(socket_bind($socket,'127.0.0.1',8888) == false){
    echo 'server bind fail:'.socket_strerror(socket_last_error());
    /*�����127.0.0.1���ڱ����������ԣ�������ж�̨���ԣ�����дIP��ַ*/
}
//�����׽���
if(socket_listen($socket,4)==false){
    echo 'server listen fail:'.socket_strerror(socket_last_error());
}
//�÷��������޻�ȡ�ͻ��˴���������Ϣ
do{
    /*���տͻ��˴���������Ϣ*/
    $accept_resource = socket_accept($socket);
    /*socket_accept�����þ��ǽ���socket_bind()���󶨵��������������׽���*/

    if($accept_resource !== false){
        /*��ȡ�ͻ��˴���������Դ����ת��Ϊ�ַ���*/
        $string = socket_read($accept_resource,1024);
        /*socket_read�����þ��Ƕ���socket_accept()����Դ������ת��Ϊ�ַ���*/

        echo 'server receive is :'.$string.PHP_EOL;//PHP_EOLΪphp�Ļ���Ԥ���峣��
        if($string != false){
            $return_client = 'server receive is : '.$string.PHP_EOL;
            /*��socket_accept���׽���д����Ϣ��Ҳ���ǻ�����Ϣ��socket_bind()���󶨵������ͻ���*/
            socket_write($accept_resource,$return_client,strlen($return_client));
            /*socket_write����������socket_create���׽���д����Ϣ��������socket_accept���׽���д����Ϣ*/
        }else{
            echo 'socket_read is fail';
        }
        /*socket_close�������ǹر�socket_create()����socket_accept()���������׽���*/
        socket_close($accept_resource);
    }
}while(true);
socket_close($socket);