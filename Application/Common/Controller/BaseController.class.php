<?php

namespace Common\Controller;
use Think\Controller;

class BaseController extends Controller{

    public function getLogin(){
        $user = session('userid');
        if($user == NULL){
            $this->redirect('Index/login');
        }else{
            return $this;
        }
    }
}