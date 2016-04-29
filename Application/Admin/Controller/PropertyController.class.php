<?php
namespace Admin\Controller;
use Common\Controller\BaseController;

class PropertyController extends BaseController
{
   public function index(){
        $this->getLogin();
        $condition['community_id'] = session("community_id");
        $property = D("repair");
        $list = $property->where($condition)->order('status,create_time desc')->select();
        $this->assign('list', $list);
        $this->display();
    }

    //确认维修完成
    public function finish(){
        $repairIds = I('post.repairs');
        $repair = D('repair');
        foreach ($repairIds as $repairId) {
            $data['status'] = 1;
            $repair->where('id='.$repairId)->save($data);
        }
    }

    //删除维修记录
    public function delete(){
        $repairIds = I('post.repairs');
        $repair = D('repair');
        foreach ($repairIds as $repairId) {
            $repair->where('id='.$repairId)->delete();
        }
    }

}