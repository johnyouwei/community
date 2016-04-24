<?php
namespace Admin\Controller;
use Common\Controller\BaseController;

class PersonController extends BaseController{
    //展示管理员个人信息
    public function index(){
        $admin_id = session("userid");
        $admin = M('Admin');
        $condition['id'] = $admin_id;
        $data = $admin->where($condition)->find();
        $this->assign('data',$data);
        $this->display();
    }
}