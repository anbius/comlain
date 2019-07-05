<?php

$fileName='2.txt';
$fp = fopen($fileName, 'r');
$content= stream_get_line($fp, 15535, "£Ün");
$content = explode('|',$content);
$m=[];
foreach($content as $key=>&$val){
    $val = (int)trim($val,' ');
    if(!$val){
        unset($content[$key]);continue;
    }

    $m = array_merge($m,array($val));

}
$n ='';
foreach($m as $mkey=>$mval){
    if($mkey===$n){
        continue;
    }
    $n= $mkey+1;
    $str='update tickets set ticketTemplateId='.$mval.' where ticketId = '.$m[$n].';';
    file_put_contents("4.txt",$str."\r\n",FILE_APPEND);
}
echo '<pre>';
print_r($m);
die;
