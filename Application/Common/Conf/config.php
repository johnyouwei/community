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
        //查看公告
        array('api-v1/community/:id/notices/page/:page/limit/:limit', 'Api/Notices/get', '', array('method'=>'GET')),

        //用户登陆
        array('api-v1/users/login', 'Api/User/login', '', array('method'=>'POST')),
        
        //获取指定id的用户信息
        array('api-v1/users/:id', 'Api/User/getUserInfo', '', array('method'=>'GET')),

        //更新指定用户id的用户信息
        array('api-v1/users/:id', 'Api/User/update', '', array('method'=>'PUT')),

        //删除用户
        array('api-v1/users/:id', 'Api/User/delete', '', array('method'=>'DELETE')),  

        //用户注册
        array('api-v1/users', 'Api/User/signup', '', array('method'=>'POST')),
    ), 
);