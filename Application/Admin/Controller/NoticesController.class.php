<?php
namespace Admin\Controller;
use Think\Controller;
class NoticesController extends Controller {

    public function index(){
        $notices = M('notices');
        $condition['community_id'] = 1;
        $condition['admin_id'] = 1;
        $list = $notices->where($condition)->select();
        $this->assign('list',$list);
        $this->display();
    }

    public function notices(){
        $this->display('Notices/notices');
    }

    //公告写入到数据库
    public function post(){
        $notices = M('notices');
        $data['title'] = I('post.title');
        $data['content'] = I('post.content');
        $data['published_at'] = I('post.time');
        $data['community_id'] = 1;
        $data['admin_id'] = 1;
        
        $notices->data($data)->add();
        echo "success";
    }
}