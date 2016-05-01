<?php
namespace Api\Controller;
use Api\Common\Controller\BasicController;

class UserController extends BasicController{
    //用户注册
    public function signup(){
        //检查参数完整性
        $parameters = array('username', 'password');
        $this->checkParameters($parameters, 'post');

        //判断用户名是否唯一
        $user = D("User");
        $result = $user->where("username='".I('post.username')."'")->find();
        if($result){
            $this->ajaxReturn(json_error(400, '用户名已存在'));
        }

        //用户名唯一,新建用户
        $data['username'] = I('post.username');
        $data['password'] = I('post.password'); 
        $data['email'] = I('post.email');
        $data['create_time'] = date('Y-m-d H:i:s');

        $result = $user->data($data)->add();

        if($result){
            $data = array(
                'userid' => $result
            );
             $this->ajaxReturn(json_success($data, 200, '注册成功'));
        }else{

            $this->ajaxReturn(json_error(401, '注册失败'));
        }   
    }

    //用户登陆
    public function login(){
        $parameters = array('username', 'password');
        $this->checkParameters($parameters, 'post');

        $user = D('User');
        $condition['username'] = I('post.username');
        $condition['password'] = I('post.password');
        $result = $user->where($condition)->find();

        if($result != null){
            $token = get_token();
            //token写入用户表
            $userid['id'] = $result['id'];
            $data['token'] = $token;
            $user->where($userid)->data($data)->save();
            $data['userid'] = $result['id'];
            
            $this->ajaxReturn(json_success($data, 200, '登陆成功'));
        }else{

            $this->ajaxReturn(json_error(400, '用户名或密码错误'));
        }  
    }

    //注销账号(退出登录)
    public function logout(){
        $this->checkAuth();
        $user = D('user');
        $data['token'] = null;
        $result = $user->where('id='.I('get.userid'))->data($data)->save();
        if($result){
            $this->ajaxReturn(json_success($data, 200, '账号注销成功'));
        }else{
            $this->ajaxReturn(json_success($data, 400, '账号注销失败'));
        }
    }

    //获取指定id用户信息
    public function getUserInfo(){
        $this->checkAuth();
        $parameters = array('id');
        $this->checkParameters($parameters, 'get');

        //查询的用户id要和userid一致,即用户只能查询自己的信息
        if(I('get.id') != I('get.userid')){
            $this->ajaxReturn(json_error(401, '没有权限'));
        }

        $user = D('User');
        $condition['id'] = I('get.id');
        $result = $user->where($condition)->find();

        if($result != null){
            unset($result['password']);
            unset($result['token']);
            $this->ajaxReturn(json_success($result, 200, '成功获取'));
        }else{
            $this->ajaxReturn(json_error(400, '查询失败'));
        }
    }

    //更新指定用户id的用户信息
    public function update(){
        $this->checkAuth();
        $parameters = array('id');
        $this->checkParameters($parameters, 'get');

        //查询的用户id要和userid一致,即用户只能查询自己的信息
        if(I('get.id') != I('get.userid')){
            $this->ajaxReturn(json_error(400, '没有权限'));
        }

        $user = D('User');
        $condition['id'] = I('get.id');

        $data = I('put.');
        unset($data['id']);
        unset($data['username']);
        unset($data['password']);
        unset($data['token']);
        unset($data['create_time']);

        $result = $user->where($condition)->data($data)->save();

        if($result){
            //获取更新的字段
            foreach ($data as $key => $val) {
                $str = $str.','.$key;
            }
            $field = substr($str,1,strlen($str)-1);    
            $info = $user->where($condition)->field($field)->find();
            //返回更新的字段
            $this->ajaxReturn(json_success($info, 200, '更新成功'));
        }else{
            $this->ajaxReturn(json_error(201, '数据没有变化'));
        }
    }
    //修改密码
    public function updatePassword(){
        $this->checkAuth();
        $parameters = array('oldPassword', 'newPassword');
        $this->checkParameters($parameters, 'put');

        $user = D('user');
        $condition['id'] = I('get.userid');
        $condition['password'] = I('put.oldPassword');
        $result = $user->where($condition)->find();
        if($result){
            $data['password'] = I('put.newPassword');
            $data['token'] = null;
            $user->where('id='.I('get.userid'))->data($data)->save();
            $this->ajaxReturn(json_success(null, 200, '修改成功'));
        }else{
            $this->ajaxReturn(json_error(400, '原密码不正确'));
        }
    }

    //认证成为业主
    public function beOwner(){
        $this->checkAuth();
        $parameters = array('community_id', 'building', 'room', 'name');
        $this->checkParameters($parameters, 'post');

        $data['community_id'] = I("post.community_id");
        $data['user_id'] = I('get.userid');

        $community = D('community');
        $result = $community->where('id='.I("post.community_id"))->find();
        
        if(!$result){
            $this->ajaxReturn(json_error(401, '请求的小区不存在'));
        }
        $owner = D('Owner');
        unset($result);
        $result = $owner->where($data)->find();
        if($result){
            if($result['status'] == 1){
                $this->ajaxReturn(json_error(200, '认证通过,无需再次认证'));
            }else{
                $this->ajaxReturn(json_error(201, '已提交认证申请,请等待管理员认证'));
            } 
        }
        $data['name'] = I('post.name');
        $data['building'] = I('post.building');
        $data['room'] = I('post.room'); 
        $data['create_time'] = date("Y-m-d H:i:s");  

        $result = $owner->data($data)->add(); 
        if($result){
            unset($data);
            $data['status'] = 0;
            $this->ajaxReturn(json_success($data, 200, '提交成功,等待认证'));
        }else{
           $this->ajaxReturn(json_error(400, '提交失败')); 
        }
    }
    //获取某用户已认证的小区
    public function getCommunitys(){
        $this->checkAuth();
        $parameters = array('id'); //用户id
        $this->checkParameters($parameters, 'get');

        //查询的用户id要和userid一致,即用户只能查询自己的信息
        if(I('get.id') != I('get.userid')){
            $this->ajaxReturn(json_error(401, '没有权限'));
        }

        $community = D('community');
        $result = $community->join('cn_owner ON cn_community.id = cn_owner.community_id AND cn_owner.status = 1')->field('id,community_name')->select();
        if($result){
            $this->ajaxReturn(json_success($result, 200, '获取成功'));
        }else{
            $this->ajaxReturn(json_error(400, '没有已认证的小区'));
        }
    }
}