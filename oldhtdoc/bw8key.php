<?php
$secret = 'ef484604591be233';
$date   =  intval(time()/10);
$pin    =  9637;
$merge  = $date.$secret.$pin;
$keyAll = md5($merge);
$key = substr($keyAll,0,6);
echo 'wangky:'.$key;

?>