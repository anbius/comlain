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
            ['id'=>10001, 'title'=>'甘肃省']
        ],
        'city'    =>[
            '10001'=>[
                ['id'   =>100011, 'title'=>'张掖市']
            ]
        ],
        'area'    =>[
            '100011'=>[
                ['id'=>'1000111','title'=>'甘州区'],
                ['id'=>'1000112','title'=>'市辖区'],
                ['id'=>'1000113','title'=>'临泽县'],
                ['id'=>'1000114','title'=>'高台县'],
                ['id'=>'1000115','title'=>'山丹县'],
                ['id'=>'1000116','title'=>'明乐县'],
                ['id'=>'1000117','title'=>'肃南裕固族自治县'],

            ]
        ]
    ];
    return $areaInfo;
}
