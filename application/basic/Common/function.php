<?php
/**
 * Created by PhpStorm.
 * User: sniper
 * Date: 2019/7/7
 * Time: 11:58
 */
 function getAreaInfo(){
    $areaInfo = [
        'province'=>[
            ['id'=>10001, 'title'=>'����ʡ']
        ],
        'city'    =>[
            '10001'=>[
                ['id'   =>100011, 'title'=>'��Ҵ��']
            ]
        ],
        'area'    =>[
            '100011'=>[
                ['id'=>'1000111','title'=>'������'],
                ['id'=>'1000112','title'=>'��Ͻ��'],
                ['id'=>'1000113','title'=>'������'],
                ['id'=>'1000114','title'=>'��̨��'],
                ['id'=>'1000115','title'=>'ɽ����'],
                ['id'=>'1000116','title'=>'������'],
                ['id'=>'1000117','title'=>'����ԣ����������'],

            ]
        ]
    ];
    return $areaInfo;
}
