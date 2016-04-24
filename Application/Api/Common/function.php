<?php

/**
 * 封装处理处理成功的json格式
 * @param string #msg 提示消息
 * @param number #code 状态码
 * @param array #data 返回数据
 * @return multitype : array
 */
function json_success(array $data, $code=200, $message=''){
    return array(
        'status' => $code,
        'message' => $message,
        'data' => $data
    );
}

/**
 * 封装处理错误的json格式
 * @param string #msg 提示消息
 * @param number #code 状态码
 * @param array #data 返回数据
 * @return multitype : array
 */
function json_error($code=400, $message=''){
    return array(
        'status' => $code,
        'message' => $message,
    );
}

//token
function get_token(){
    $data = time().rand();
    return md5(time().$data);
}