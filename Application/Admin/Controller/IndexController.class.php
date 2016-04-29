<?php
namespace Admin\Controller;
use Common\Controller\BaseController;

class IndexController extends BaseController {

    public function index(){
        $this->getLogin();
        $this->redirect('Person/index');    
    }
    //注册
    public function signup(){
        $this->display();
    }

    public function signuphandle(){
        $admin = M('Admin');
        $community = M('Community');

        $result = $admin->where("name='".I('post.name')."'")->find();
        //用户名已存在
        if($result){
            $this->redirect("signup");
        }else{
            $c_data['name'] = I('post.community_name');
            $c_data['address'] = I('post.community_address');
            $c_data['create_time'] = date('Y-m-d');

            $community_id = $community->data($c_data)->add();

            $data['name'] = I('post.name');
            $data['password'] = I('post.password');
            $data['email'] = I('post.email');
            $data['phone'] = I('post.phone');
            $data['community_id'] = $community_id;
            $data['create_time'] = date('Y-m-d H:i:s');

            $admin->data($data)->add();
            $this->redirect('login');
        }    
        
    }

    //登陆
    public function login(){
        $this->display();
    }

    public function loginhandle(){
        $admin = M('Admin');

        $condition['name'] = I('post.name');
        $condition['password'] = I('post.password');

        $result = $admin->where($condition)->find();
        if($result){
            session('username', $result['name']);
            session('userid', $result['id']);
            session('community_id', $result['community_id']);
            $this->redirect('Index/index');
        }else{
            $this->redirect('login');
        }
    }

    //退出登录
    public function logout(){
        session(null);
        $this->redirect("login");
    }

    public function test(){
        $data = I('post.name');
        $this->ajaxReturn($data);
    }
}