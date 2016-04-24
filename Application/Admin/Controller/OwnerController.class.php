<?php
namespace Admin\Controller;
use Common\Controller\BaseController;

class OwnerController extends BaseController
{
    public function index(){
        $owner = D('Owner');
        $condition['community_id'] = session('community_id');
        $list = $owner->where($condition)->order('create_time desc')->select();  
        $this->assign('list',$list);
        $this->display();    
    }

    //通过业主认证申请
    public function allow(){
        $ownerIds = I('post.owners');
        $owner = D('Owner');
        foreach ($ownerIds as $ownerId) {
            $data['status'] = 1;
            $owner->where('user_id='.$ownerId)->save($data);
        }
    }

    //拒绝业主认证申请
    public function deny(){
        $ownerIds = I('post.owners');
        $owner = D('Owner');
        foreach ($ownerIds as $ownerId) {
            $owner->where('user_id='.$ownerId)->delete();
        }
    }
   
}