<?php
namespace Api\Controller;
use Think\Controller;

class NoticesController extends Controller{
    //获取某小区所有公告
    function getAll($id){
        $Notices = M('Notices');
        $condition['community_id'] = $id;
        $data = $Notices->where($condition)->select();
        
        //判空
        if(!empty($data)){
            $result['data'] = $data;
            $result['status'] = 1;
            $this->ajaxReturn($result);
        }else{
            $result['status'] = 0;
            $this->ajaxReturn($result);
        }    
    }
}
