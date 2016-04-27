<?php
namespace Api\Controller;
use Api\Common\Controller\BasicController;

class NoticesController extends BasicController{
    //获取某小区指定数目公告
    function getPage(){
        $this->checkAuth();
        $parameters = array('community_id', 'page', 'limit');
        $this->checkParameters($parameters, 'get');

        //是否是该社区的成员,查询认证表
        $this->checkOwner(I('get.userid'), I('get.community_id'));

        $Notices = D('Notices');
        unset($condition);
        $condition['community_id'] = I('get.community_id');
        $page = I("get.page");
        $limit = I("get.limit");
        $field = 'title,content,published_at';
        unset($result);
        $result = $Notices->where($condition)->limit($limit)->page($page)->order('published_at desc')->field($field)->select();
      
        if(!empty($result)){
            $this->ajaxReturn(json_success($result, 200, '查询成功'));    
        }else{
            $this->ajaxReturn(json_error(201, '没有更多数据了'));
        }
    }
}
