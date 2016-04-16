<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller {

    public function index(){
        $this->display();
    }

    //注册
    public function signup(){
        $this->display();
    }

    public function signuphandle(){
        $admin = M('Admin');
        $data['name'] = I('post.name');
        $data['password'] = I('post.password');
        $data['email'] = I('post.email');
        $data['phone'] = I('post.phone');
        $data['community_id'] = 1;

        $result = $admin->where("name='".I('post.name')."'")->find();
        //用户名已存在
        if($result){
            $this->redirect("signup");
        }else{
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
            session('username',$result['name']);
            session('userid',$result['id']);
            $this->redirect('Index/index');
        }else{
            $this->redirect('login');
        }
    }
}