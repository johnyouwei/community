<?php
namespace Api\Controller;
use Api\Common\Controller\BasicController;

class PropertyController extends BasicController{
    //物业报修
    public function repair(){
        $this->checkAuth();
        $parameters = array('content', 'community_id', 'keys');
        $this->checkParameters($parameters, 'post');

        //是否是该小区的成员,查询认证表
        $this->checkOwner(I('get.userid'), I('post.community_id'));

        //用户地址
        $owner = D('Owner');
        $condition['userid'] = I('get.userid');
        $condition['community_id'] = I('post.community_id');
        $result = $owner->where($condition)->find();

        $repair = D('repair');
        $data['keys'] = I('post.keys');
        $data['content'] = I('post.content');
        $data['community_id'] = I('post.community_id');
        $data['user_id'] = I('get.userid');
        $data['create_time'] = date('Y-m-d H:i:s');
        $data['building'] = $result['building'];
        $data['room'] = $result['room'];
        $data['name'] = $result['name'];
        unset($result);
        $result  = $repair->data($data)->add();
        if($result){
            $result = $repair->where('id='.$result)->find();
            $this->ajaxReturn(json_success($result, 200, '报修成功,等待处理'));
        }else{
            $this->ajaxReturn(json_error(400, '报修失败'));
        }
    }
    //获取报修记录
    public function getRepairs(){
        $this->checkAuth();
        $parameters = array('community_id','page', 'limit');
        $this->checkParameters($parameters, 'get');

        //是否是该小区的成员,查询认证表
        $this->checkOwner(I('get.userid'), I('get.community_id'));

        $repair = D('repair');
        $result = $repair->where()->limit(I('get.limit'))->page(I('get.page'))->select();
        if($result){
            $this->ajaxReturn(json_success($result, 200, '获取成功'));
        }else{
             $this->ajaxReturn(json_error(400, '没有报修记录'));
         }
    }
}