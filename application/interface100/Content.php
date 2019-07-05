<?php

namespace app\interface100;

use think\Controller;
use think\Db;
use library\lsy\Base as BaseService;
use app\basic\service\News as NewsService;
use app\basic\service\Common as CommonService;
use library\lsy\Idworks;

/**
 * Class Content
 * 发现
 */
class Content extends Controller
{
    //限制数量
    private $limit = 6;
    private $homepageLimit = 3;
    private $commentLimit = 10;

    /**
     * 发现首页推荐
     * @return array
     */
    public function homePage()
    {
        $where['is_homepage'] = 1;
        $where['is_deleted'] = 1;
        $where['status'] = 1;

        $result = array();
        if ($contentArr = Db::name('BasicNews')->field('id,title,digest,local_url,images,is_detail,likes_num,comment_num,create_at')->where($where)->order('sort desc,id desc')->limit($this->homepageLimit)->select())
        {
            foreach ($contentArr as $contentVal)
            {
                $returnArr['id'] = $contentVal['id'];
                $returnArr['title'] = $contentVal['title'] ? out($contentVal['title']) : '';
                $returnArr['digest'] = $contentVal['digest'] ? text_out($contentVal['digest']) : '';
                $returnArr['imgUrl'] = $contentVal['local_url'] ? out($contentVal['local_url']) : '';
                $returnArr['images'] = $contentVal['images'] ? (explode('|',out($contentVal['images']))) : [];
                $returnArr['likeNum'] = $contentVal['likes_num'];
                $returnArr['commentNum'] = $contentVal['comment_num'];
                $returnArr['isDetail'] = $contentVal['is_detail'];
                $returnArr['created'] = $contentVal['create_at'] ? date_time($contentVal['create_at'],'n月j日 G:i:s') : '';
                $returnArr['comment'] = [];

                if(($returnArr['commentNum'] > 0) && ($notesMain = Db::name('InfoContentCommain')->field('id,main_user_id,main_user_name,arg_user_id,arg_user_name,content')->where(['cid'=>$returnArr['id'],'is_deleted'=>1,'status'=>1])->limit($this->commentLimit)->select()))
                {
                    $returnArrComment = array();
                    foreach($notesMain as $notesMainKeys => $notesMainVals)
                    {
                        $returnArrComment['id'] = $notesMainVals['id'];
                        $returnArrComment['mId'] = $notesMainVals['main_user_id'];
                        $returnArrComment['mName'] = out($notesMainVals['main_user_name']);
                        $returnArrComment['nId'] = $notesMainVals['arg_user_id'];
                        $returnArrComment['nName'] = out($notesMainVals['arg_user_name']);
                        $returnArrComment['content'] = text_out($notesMainVals['content']);

                        $returnArr['comment'][] = $returnArrComment;
                    }
                }

                $result['lists'][] = $returnArr;
            }
        }

        return $result;
    }


    /**
     * 分页请求
     * @return array
     */
    public function lists()
    {
        $data = $this->request->get();
        $type = isset($data['catOne']) && is_numeric($data['catOne']) ? abs(intval($data['catOne'])) : 0;
        $page = isset($data['page']) && is_numeric($data['page']) ? abs(intval($data['page'])) : 1;

        $limit = $this->limit;
        $start = ($page - 1) * $limit;

        if(!empty($type)){$where['cat_1'] = $type;}
        $where['is_deleted'] = 1;
        $where['status'] = 1;

        $result = array();
        $result['pages'] = CommonService::totalPages(Db::name('BasicNews')->where($where)->count('id'),$this->limit);
        if ($result['pages'] && ($contentArr = Db::name('BasicNews')->field('id,title,digest,local_url,images,is_detail,likes_num,comment_num,create_at')->where($where)->order('sort desc,id desc')->limit($start,$this->limit)->select()))
        {
            foreach ($contentArr as $contentVal)
            {
                $returnArr['id'] = $contentVal['id'];
                $returnArr['title'] = $contentVal['title'] ? out($contentVal['title']) : '';
                $returnArr['digest'] = $contentVal['digest'] ? text_out($contentVal['digest']) : '';
                $returnArr['imgUrl'] = $contentVal['local_url'] ? out($contentVal['local_url']) : '';
                $returnArr['images'] = $contentVal['images'] ? (explode('|',out($contentVal['images']))) : [];
                $returnArr['likeNum'] = $contentVal['likes_num'];
                $returnArr['commentNum'] = $contentVal['comment_num'];
                $returnArr['isDetail'] = $contentVal['is_detail'];
                $returnArr['created'] = $contentVal['create_at'] ? date_time($contentVal['create_at'],'n月j日 G:i:s') : '';
                $returnArr['comment'] = [];

                if(($returnArr['commentNum'] > 0) && ($notesMain = Db::name('InfoContentCommain')->field('id,main_user_id,main_user_name,arg_user_id,arg_user_name,content')->where(['cid'=>$returnArr['id'],'is_deleted'=>1,'status'=>1])->limit($this->commentLimit)->select()))
                {
                    $returnArrComment = array();
                    foreach($notesMain as $notesMainKeys => $notesMainVals)
                    {
                        $returnArrComment['id'] = $notesMainVals['id'];
                        $returnArrComment['mId'] = $notesMainVals['main_user_id'];
                        $returnArrComment['mName'] = out($notesMainVals['main_user_name']);
                        $returnArrComment['nId'] = $notesMainVals['arg_user_id'];
                        $returnArrComment['nName'] = out($notesMainVals['arg_user_name']);
                        $returnArrComment['content'] = text_out($notesMainVals['content']);

                        $returnArr['comment'][] = $returnArrComment;
                    }
                }

                $result['lists'][] = $returnArr;
            }
        }

        return ['message'=>BaseService::$ERR['SYS']['HANDLE_SUCCESS'],'data'=>$result];
    }


    /**
     * 详情
     * @return array
     */
    public function details()
    {
        $data = $this->request->get();

        $where['id'] = isset($data['id']) && is_numeric($data['id']) ? trim($data['id']) : 0;
        $where['is_deleted'] = 1;
        $where['status'] = 1;

        $result = array();
        if (!empty($where['id']) && ($contentDatas = Db::name('BasicNews')->field('id,title,detail,reads,create_at')->where($where)->find()))
        {
            $result['details']['id'] = $contentDatas['id'];
            $result['details']['title'] = $contentDatas['title'] ? out($contentDatas['title']) : '';
            $result['details']['detail'] = $contentDatas['detail'] ? html_out($contentDatas['detail']) : '';
            $result['details']['created'] = $contentDatas['create_at'] ? date_time($contentDatas['create_at']) : '';

            NewsService::recordContentInfo(['type'=>400,'ctId'=>$contentDatas['id'],'ctTitle'=>$result['details']['title'],'reads'=>$contentDatas['reads']]);
        }

        return ['message'=>BaseService::$ERR['SYS']['HANDLE_SUCCESS'],'data'=>$result];
    }


    /**
     * 获取更多评论
     * @return array
     */
    public function commentList()
    {
        $data = $this->request->get();

        $where['id'] = isset($data['id']) && is_numeric($data['id']) ? trim($data['id']) : 0;
        $where['is_deleted'] = 1;
        $where['status'] = 1;

        $page = isset($data['page']) && is_numeric($data['page']) && ($data['page'] > 1) ? abs(intval($data['page'])) : 2;
        $limit = $this->commentLimit;
        $start = ($page - 1) * $limit;

        $result = array();
        if (!empty($where['id']) && ($contentDatas = Db::name('BasicNews')->field('id,comment_num')->where($where)->find()))
        {
            $commainWhere = ['cid'=>$where['id'],'is_deleted'=>1,'status'=>1];
            if(($contentDatas['comment_num'] > 0) && ($result['pages'] = CommonService::totalPages(Db::name('InfoContentCommain')->where($commainWhere)->count('id'),$this->commentLimit)) && ($notesMain = Db::name('InfoContentCommain')->field('id,main_user_id,main_user_name,arg_user_id,arg_user_name,content')->where($commainWhere)->limit($start,$this->commentLimit)->select()))
            {
                $returnArrComment = array();
                foreach($notesMain as $notesMainKeys => $notesMainVals)
                {
                    $returnArrComment['id'] = $notesMainVals['id'];
                    $returnArrComment['mId'] = $notesMainVals['main_user_id'];
                    $returnArrComment['mName'] = out($notesMainVals['main_user_name']);
                    $returnArrComment['nId'] = $notesMainVals['arg_user_id'];
                    $returnArrComment['nName'] = out($notesMainVals['arg_user_name']);
                    $returnArrComment['content'] = text_out($notesMainVals['content']);

                    $result['comment'][] = $returnArrComment;
                }
            }
        }

        return ['message'=>BaseService::$ERR['SYS']['HANDLE_SUCCESS'],'data'=>$result];
    }


    /**
     * 点赞
     */
    public function likes()
    {
        $return = array('message'=>BaseService::$ERR['SYS']['HANDLE_ERROR']);

        $data = $this->request->get();
        $id = isset($data['id']) && is_numeric($data['id']) ? strval($data['id']) : 0;

        if(!empty($id) && ($userReturn = Common::checkUserStatus($data)) && $userReturn['id'] && ($voteData = Db::name('BasicNews')->field('likes_num,reads')->where(['id'=>$id,'is_deleted'=>1,'status'=>1])->find()))
        {
            if(!Db::name('InfoContentLikes')->where(['user_id'=>$userReturn['id'],'cid'=>$id,'is_deleted'=>1,'status'=>1])->find())
            {
                $record['id'] = Idworks::uniqueID();
                $record['cid'] = $id;
                $record['user_id'] = $userReturn['id'];
                $record['user_name'] = $userReturn['nickname'];
                $record['type'] = 1;
                $record['status'] = 1;
                $record['is_deleted'] = 1;
                $record['create_at'] = time();

                if(Db::name('InfoContentLikes')->insert($record))
                {
                    $update['likes_num'] = $voteData['likes_num'] + 1;
                    $update['reads'] = $voteData['reads'] + 1;

                    if(Db::name('BasicNews')->where(['id'=>$id])->update($update)){$return = ['message'=>BaseService::$ERR['SYS']['HANDLE_SUCCESS']];}
                }
            }else{
                $return = ['message'=>BaseService::$ERR['INFO']['NOTES_LIKES_EXIST']];
            }
        }else{
            $return = ['message'=>BaseService::$ERR['USER']['LOGIN_SESSION_EXPIRE']];
        }

        return $return;
    }


    /**
     * 提交评论
     */
    public function commentMain()
    {
        $return = array('message'=>BaseService::$ERR['SYS']['HANDLE_ERROR']);

        $data = $this->request->post();

        $id = isset($data['id']) && is_numeric($data['id']) ? strval($data['id']) : 0;
        $content = isset($data['content']) && preg_match('/\S/',$data['content']) ? trim($data['content']) : '';
        $argUserId = isset($data['argUserId']) && is_numeric($data['argUserId']) ? strval($data['argUserId']) : 0;
        $argUserName = isset($data['argUserName']) ? in($data['argUserName']) : '';

        if(!empty($id) && !empty($content) && ($userReturn = Common::checkUserStatus($data)) && $userReturn['id'] && ($voteData = Db::name('BasicNews')->field('comment_num,reads')->where(['id'=>$id,'is_deleted'=>1,'status'=>1])->find()))
        {
            $record['id'] = Idworks::uniqueID();
            $record['cid'] = $id;
            $record['main_user_id'] = $userReturn['id'];
            $record['main_user_name'] = $userReturn['nickname'];
            $record['arg_user_id'] = $argUserId;
            $record['arg_user_name'] = $argUserName;
            $record['content'] = text_in($content);
            $record['flag_1'] = 1;
            $record['type'] = 1;
            $record['status'] = 1;
            $record['is_deleted'] = 1;
            $record['create_at'] = time();

            if(Db::name('InfoContentCommain')->insert($record))
            {
                $update['comment_num'] = $voteData['comment_num'] + 1;
                $update['reads'] = $voteData['reads'] + 1;

                if(Db::name('BasicNews')->where(['id'=>$id])->update($update))
                {
                    $return = ['message'=>BaseService::$ERR['SYS']['HANDLE_SUCCESS']];
                }
            }
        }

        return $return;
    }
}