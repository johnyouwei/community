<?php
namespace Api\Controller;
use Api\Common\Controller\BasicController;

class PropertyController extends BasicController{

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
        $data['user_id'] = I('get.id');
        $data['create_time'] = date('Y-m-d H:i:s');
        $data['building'] = $result['building'];
        $data['room'] = $result['room'];
        $data['name'] = $result['name'];
        unset($result);
        $result  = $repair->data($data)->add();
        if($result){
            unset($data);
            $data['status'] = 0;
            $this->ajaxReturn(json_success($data, 200, '报修成功,等待处理'));
        }else{
            $this->ajaxReturn(json_error(400, '报修失败'));
        }
    }
}