<?php
namespace Admin\Controller;
use Common\Controller\BaseController;

class PropertyController extends BaseController
{
   public function index(){
        $this->getLogin();
        $condition['community_id'] = session("community_id");
        $property = D("repair");
        $list = $property->where($condition)->order('time desc')->select();
        $this->assign('list', $list);
        $this->display();
    }
}