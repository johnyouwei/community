<?php
return array(
	//数据库设置
    'DB_TYPE'   => 'mysql',
    'DB_NAME'   => 'community',
    'DB_USER'   => 'root',
    'DB_PWD'    => '',
    'DB_HOST'   => 'localhost',
    'DB_PREFIX' => 'cn_',

    //路由设置
    'URL_MODEL'       =>  2,
    'URL_ROUTER_ON'   => true,
    'URL_ROUTE_RULES' => array(
        //users API
        //认证成为业主
        array('api-v1/users/owners', 'Api/User/beOwner', '', array('method'=>'POST')),
        //获取用户已验证的小区
        array('api-v1/users/owners/:id', 'Api/User/getOwners', '', array('method'=>'GET')),
        //用户登陆
        array('api-v1/users/login', 'Api/User/login', '', array('method'=>'POST')),
        //获取指定id的用户信息
        array('api-v1/users/:id', 'Api/User/getUserInfo', '', array('method'=>'GET')),
        //更新指定用户id的用户信息
        array('api-v1/users/:id', 'Api/User/update', '', array('method'=>'PUT')), 
        //用户注册
        array('api-v1/users', 'Api/User/signup', '', array('method'=>'POST')),

        ////////////////////////////////////////////////////
        //查看公告
        array('api-v1/notices', 'Api/Notices/getPage', '', array('method'=>'GET')),
        ////////////////////////////////////////////////////

        //物业报修
        array('api-v1/repairs', 'Api/Property/repair', '', array('method'=>'POST')),  
        ////////////////////////////////////////////////////

        //回帖
        array('api-v1/psots/replys', 'Api/Posts/reply', '', array('method'=>'POST')),
        //获取某条帖子
        array('api-v1/posts/:id', 'Api/Posts/getOne', '', array('method'=>'GET')),
        //获取指定小区帖子
        array('api-v1/posts', 'Api/Posts/getPage', '', array('method'=>'GET')),
        //发帖
        array('api-v1/posts', 'Api/Posts/publish', '', array('method'=>'POST')),
        //删帖
        array('api-v1/posts', 'Api/Posts/delete', '', array('method'=>'DELETE')),
        //////////////////////////////////////////////////////
    ), 
);