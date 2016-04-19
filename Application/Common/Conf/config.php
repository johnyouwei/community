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
        array('api-v1/notices/:id', 'Api/Notices/getAll', '', array('method'=>'GET')),
    ), 
);