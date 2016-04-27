<?php
namespace Api\Controller;
use Api\Common\Controller\BasicController;

class PostsController extends BasicController{
    //发帖
    public function publish(){
        $this->checkAuth();
        $parameters = array('title', 'content', 'community_id');
        $this->checkParameters($parameters, 'post');

        //是否是该社区的成员,查询认证表
        $this->checkOwner(I('get.userid'), I('post.community_id'));

        $data['title'] = I('post.title');
        $data['content'] = I('post.content');
        $data['community_id'] = I('post.community_id');
        $data['user_id'] = I('get.userid');
        $data['published_at'] = date('Y-m-d H:i:s');

        $user = D('user');
        $result = $user->where('id='.I('get.userid'))->field('username')->find();
        $data['username'] = $result['username'];
        
        $post = D('post');
        $result = $post->data($data)->add();
        if($result){
            $this->ajaxReturn(json_success($data , 200, '发帖成功'));
        }else{
            $this->ajaxReturn(json_error(400, '发帖失败'));
        } 
    }

    //删帖
    public function delete(){
        $this->checkAuth();
        parse_str(file_get_contents("php://input"), $delete);
        $post = D('post');
        $data['id'] = $delete['id'];
        $data['user_id'] = I('get.userid');

        $result = $post->where('id='.$delete['id'])->find();
        //是否是该社区的成员,查询认证表
        $this->checkOwner(I('get.userid'), $result['community_id']);
        
        unset($result);
        $result = $post->where($data)->delete();
        if($result == 1){
            $return['id'] = $delete['id'];
            $this->ajaxReturn(json_success($return, 200, '删帖成功'));
        }else{
            $this->ajaxReturn(json_error(400, '删帖失败'));
        }
    }

    //获取某社区帖子
    public function getPage(){
        $this->checkAuth();
        $parameters = array('community_id', 'page', 'limit');
        $this->checkParameters($parameters, 'get');

        //是否是该社区的成员,查询认证表
        $this->checkOwner(I('get.userid'), I('get.community_id'));

        $post = D('post');
        $condition['community_id'] = I("get.community_id");
        $page = I("get.page");
        $limit = I("get.limit");
        $field = 'id,title,username,published_at';
        $result = $post->where($condition)->limit($limit)->page($page)->order('published_at desc')->field($field)->select();
      
        if(!empty($result)){
            $this->ajaxReturn(json_success($result, 200, '查询成功'));    
        }else{
            $this->ajaxReturn(json_error(201, '没有更多数据了'));
        }    
    }

    //获取指定id帖子
    public function getOne(){
        $this->checkAuth();
        $parameters = array('id');
        $this->checkParameters($parameters, 'get');

        $condition['id'] = I('get.id');

        $post = D('post');
        $result = $post->where($condition)->find();

        if($result){
            //是否是该社区的成员,查询认证表
            $this->checkOwner(I('get.userid'), $result['community_id']);
            unset($result['community_id']);
            unset($result['user_id']);
            $this->ajaxReturn(json_success($result, 200, '获取成功'));
        }else{
            $this->ajaxReturn(json_error(400, '帖子不存在'));
        }
    }

    //回帖
    public function reply(){
        $this->checkAuth();
        $parameters = array('content', 'post_id');
        $this->checkParameters($parameters, 'post');

        //是否是该社区的成员,查询认证表
        $this->checkOwner(I('get.userid'), I('post.community_id'));

        $data['content'] = I('post.content');
        $data['post_id'] = I('post.post_id');
        $data['user_id'] = I('get.userid');
        $data['reply_time'] = date('Y-m-d H:i:s');

        $user = D('user');
        $result = $user->where('id='.I('get.userid'))->field('username')->find();
        $data['username'] = $result['username'];
        
        $reply = D('reply');
        $result = $reply->data($data)->add();
        
        if($result){
            $this->ajaxReturn(json_success($data , 200, '回帖成功'));
        }else{
            $this->ajaxReturn(json_error(400, '回帖失败'));
        }
    }
}