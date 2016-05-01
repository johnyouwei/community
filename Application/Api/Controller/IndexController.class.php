<?php
namespace Api\Controller;
use Api\Common\Controller\BasicController;

class IndexController extends BasicController {
    //获取所有的小区
    public function index(){
        $parameters = array('page', 'limit');
        $this->checkParameters($parameters, 'get');
        $community = D('community');
        $result = $community->limit(I('get.limit'))->page(I('get.page'))->find();
        if($result){
            $this->ajaxReturn(json_success($result, 200, '获取成功'));
        }else{
            $this->ajaxReturn(json_error(201, '没有更多数据了'));
        }   
    }

    public function checkCommunity(){
        $parameters = array("name");
        $this->checkParameters($parameters, 'post');
        $community = D('community');
        $condition ['community_name'] = I('post.name');
        $result = $community->where($condition)->find();
        if($result){
            $data['status'] = 1;
            $this->ajaxReturn(json_success($data, 200, '小区存在'));
        }else{
             $data['status'] = 0;
             $this->ajaxReturn(json_success($data, 400, '小区不存在'));
         }
    }
}