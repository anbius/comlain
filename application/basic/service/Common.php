<?php
/**
 * Created by PhpStorm.
 * User: sniper
 * Date: 2019/7/7
 * Time: 14:19
 */
namespace app\basic\service;

use library\Controller;
use think\Db;
class Common extends Controller{
    public static function getAreaInfo(){
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
                    ['id'=>'1000112','title'=>'市辖区'],
                    ['id'=>'1000111','title'=>'甘州区'],
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
    public static function section(){
        $sectionArr = [
            ['id'=>1,'name'=>'文广旅游部门'],
            ['id'=>2,'name'=>'城管执法部门'],
            ['id'=>3,'name'=>'公安部门'],
            ['id'=>4,'name'=>'市场监督部门'],
            ['id'=>5,'name'=>'生态环境部门'],
            ['id'=>6,'name'=>'畜牧兽医部门']
        ];
        return  $sectionArr;

    }
    public static function AllSeeId(){
           $AllSeeId = 5; //生态环境部门
           return $AllSeeId;
    }

    public static function  choseSection($belongId,$sectionArr=[]){
        if(!$sectionArr)$sectionArr = self::section();
         foreach($sectionArr as $seckey=>$section){
        if(intval($belongId) ==intval($section['id'])){
            $belongName = $section['name'];
            break;
        }else{
            $belongName = '暂无';
        }
    }
         return $belongName;
     }
    public static  function getTurmAreaName($area){
        $areaInfo   = self::getAreaInfo();
        $areaArr    = explode(',',$area);
        $provinceId = $areaArr[0];
        $cityId     = $areaArr[1];
        $areaId     = $areaArr[2];
        //省
        foreach($areaInfo['province'] as $proVal){
            if(intval($proVal['id'])==intval($provinceId)){
                $provinceName = $proVal['title'];
                break;
            }
        }
        //市
        foreach($areaInfo['city'] as $cityKey=>$cityArr){
            if($cityKey ==$provinceId ){
                foreach($cityArr as $cityVal){
                    if(intval($cityVal['id']) == intval($cityId)){
                        $cityName = $cityVal['title'];
                        break;
                    }
                }
            }
        }
        //区
        foreach($areaInfo['area'] as $areakey=>$areaArrs){
            if($areakey == $cityId ){
                foreach($areaArrs as $areVal){
                    if(intval($areVal['id']) == intval($areaId)){
                        $areaName = $areVal['title'];
                        break;
                    }else{
                        $areaName = '';
                    }
                }
            }
        }
        $res = $provinceName.','.$cityName.','.$areaName;
        return $res;
    }

    public static function jsonTo($code,$message,$data){
        $returnData = [
            'code'=>$code,
            'message'=>$message,
            'data'=>$data
        ];
        return json_encode($returnData);
    }

    /**
     * 计算总页数
     * @param int $total
     * @param $limit
     * @return float|int
     */
    public static function totalPages($total = 0, $limit)
    {
        if(empty($total))
        {
            $pages = 0;
        }else{
            $total = is_numeric($total) ? abs(intval($total)) : 0;
            $pages = ceil($total / $limit);
        }

        return $pages;
    }
}