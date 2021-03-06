<?php
namespace Admin\Controller;
use Common\Controller\BaseController;

class NoticesController extends BaseController {

    public function index(){
        if(session('?userid')){
            $notices = M('notices');
            $condition['admin_id'] = session('userid');
            $list = $notices->where($condition)->order('id desc')->select();
            $this->assign('list',$list);
            $this->display();
        }else{
            $this->redirect('Index/login');
        }   
    }

    public function notices(){
        $this->display('Notices/notices');
    }

    //公告写入到数据库
    public function post(){
        $notices = M('notices');
        $data['title'] = I('post.title');
        $data['content'] = I('post.content');
        $data['published_at'] = date('Y-m-d');
        $data['community_id'] = session("community_id");
        $data['admin_id'] = session("userid");
        $admin = D('admin');
        $result = $admin->where('id='.$data['admin_id'])->field('name')->find();
        $data['admin_name'] = $result['name'];
        unset($result);
        $result = $notices->data($data)->add();
        
        $this->redirect('Notices/index');
    }

    //删除公告
    public function delete(){
        $notices = M('notices');
        $datas = I('post.notices');
        foreach ($datas as $data) {
            $notices->where('id='.$data)->delete();
        }  
        $this->ajaxReturn();    
    }

    public function getOne(){
        $notice = D('notices');
        $id = I('get.id');
        $result = $notice->where('id='.$id)->find();
        $this->assign("result", $result);
        $this->display('one');
    }
}