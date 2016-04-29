<?php

namespace Api\Common\Controller;
use Think\Controller\RestController;

class BasicController extends RestController{

    public function _initialize() {
        
    }
    //检查参数是否完整
    protected function checkParameters(array $parameters, $method='get'){
        foreach ($parameters as $parameter) {
            $value = I($method.'.'.$parameter); 
            if($value == null){
                $this->ajaxReturn(array(
                    'status' => '0',
                    'message' => '缺少必要参数'
                ));
            }
        }
        return $this;
    }
    //验证用户身份
    protected function checkAuth(){
        $userid = I('get.userid');
        $token = I('get.token');
        if($userid == null || $token == null){
            $this->ajaxReturn(json_error(1001, '缺少userid或token'));
        }
        $user = D('user');
        $condition['id'] = $userid;
        $condition['token'] = $token;
        $result = $user->where($condition)->find();
        if ($result) {
            return $this;
        }else{
            $this->ajaxReturn(json_error(1002, '用户不存在或token过期,请重新登录'));
        }  
    }

    //验证业主身份
    protected function checkOwner($userid, $community_id){
        $owner = D('Owner');
        $condition['user_id'] = $userid;
        $condition['community_id'] = $community_id;
        $result = $owner->where($condition)->field('status')->find();
        
        if($result['status'] == null){
            $this->ajaxReturn(json_error(4001, '不是该社区的认证业主,无权限操作'));
        }   
        if($result['status'] == 0){
            $this->ajaxReturn(json_error(4002, '业主身份认证中,无权限操作'));
        }
        //是认证业主
        return $this;
    }
}