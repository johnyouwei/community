<?php

namespace Api\Common\Controller;
use Think\Controller\RestController;

class BasicController extends RestController{

    public function _initialize() {
    }

    protected function checkParameters(array $parameters, $method='get'){
        foreach ($parameters as $parameter) {
            $value = I($method.'.'.$parameter); 
            if($value == null){
                $this->ajaxReturn(array(
                    'status' => '400',
                    'message' => '参数不完整'
                ));
            }
        }
        return $this;
    }
}