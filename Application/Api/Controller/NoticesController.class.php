<?php
namespace Api\Controller;
use Think\Controller;

class NoticesController extends Controller{
    //获取某小区指定数目公告
    function get(){
        $Notices = M('Notices');
        $condition['community_id'] = I("get.id");
        $page = I("get.page");
        $limit = I("get.limit");
        $field = 'title,content,published_at';
        $result = $Notices->where($condition)->limit($limit)->page($page)->order('published_at desc')->field($field)->select();
      
        if(!empty($result)){
            $this->ajaxReturn(json_success($result, 200, '查询成功'));    
        }else{
            $this->ajaxReturn(json_error(201, '没有更多数据了'));
        }    
    }
}
