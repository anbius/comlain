<?php

function createRange($number){
    $data = [];
    for($i=0;$i<$number;$i++){
    	yield  time();
       // $data[] = time();
    }
   // return $data;
}

$res = createRange(1);

foreach($res  as $val){
	echo $val.'<br>';
}
// function squares($start, $stop) {  
//     if ($start < $stop) {  
//         for ($i = $start; $i <= $stop; $i++) {  
//             yield $i => $i * $i;  
//         }  
//     }  
//     else {  
//         for ($i = $start; $i >= $stop; $i--) {  
//             yield $i => $i * $i; //迭代生成数组： 键=》值  
//         }  
//     }  
// }  
// foreach (squares(3, 15) as $n => $square) {  
//     echo $n . ‘squared is‘ . $square . ‘<br>‘;  
// }  

//对某一数组进行加权处理  
  
  
//通常方法，如果是百万级别的访问量，这种方法会占用极大内存  
function rand_weight($numbers)  
{  
    $total = 0;  
    foreach ($numbers as $number => $weight) {  
        $total += $weight;  
        //$distribution[$number] = $total;  
        yield $number=>$total;
    }  
    $rand = mt_rand(0, $total-1);  
  



    foreach ($distribution as $num => $weight) {  
        if ($rand < $weight) return $num;  
    }  
}  
$numbers = array('nike' => 200, 'jordan' => 500, 'adiads' => 800);
  $res = rand_weight($numbers);

  var_dump($res);


select DISTINCT(ticketId),ticketTemplateId,subject,descript,custUserId,tagList,servicerUserId,createDT,updateDT,ticketType,priorityLevel,ticketStatus,servicerGroupId,ticketSource,createrId from tickets AS T
            
             INNER JOIN ( SELECT DISTINCT base.attrName, eavColumn47612.attrValue AS `field_7` FROM tfapi_eav_rows as base  
      LEFT JOIN tfapi_eav_attr_varchar AS eavColumn47612 
      ON base.tableId = eavColumn47612.tableId
        AND eavColumn47612.columnId = 47612
        AND base.attrName = eavColumn47612.attrName
      WHERE base.tableId = 8710 ) AS FCA
            ON FCA.attrName = concat('tid8710rid',T.ticketId)
              AND (find_in_set('N20025N', `FCA`.field_7) )
            
            
            where  T.agentId= 18 and T.ticketStatus !=5