<?php
header('content-type:text/html;charset=gbk');
$path = '/imgA/all.jpg';
$topath = '/imgB/1.jpg';
echo "<br>" . '���ǵ� ' . __LINE__ . "��" . "" . "<hr>";
//echo copy($path,$topath);
echo "<br>" . '���ǵ� ' . __LINE__ . "��" . __DIR__ . "<hr>";
if(!is_dir(__DIR__.'/imgC')){
   mkdir(__DIR__.'/imgC');
    echo "<br>" . '���ǵ� ' . __LINE__ . "��" . "��������" . "<hr>";
}
echo copy( __DIR__.'/imgA/all.jpg', __DIR__.'/imgC/2.jpg');
