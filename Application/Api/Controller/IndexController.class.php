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
}