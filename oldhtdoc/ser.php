<?php
header('content-type:text/html;charset=gbk');
$path = '/imgA/all.jpg';
$topath = '/imgB/1.jpg';
echo "<br>" . '这是第 ' . __LINE__ . "行" . "" . "<hr>";
//echo copy($path,$topath);
echo "<br>" . '这是第 ' . __LINE__ . "行" . __DIR__ . "<hr>";
if(!is_dir(__DIR__.'/imgC')){
   mkdir(__DIR__.'/imgC');
    echo "<br>" . '这是第 ' . __LINE__ . "行" . "吹昂见了" . "<hr>";
}
echo copy( __DIR__.'/imgA/all.jpg', __DIR__.'/imgC/2.jpg');
