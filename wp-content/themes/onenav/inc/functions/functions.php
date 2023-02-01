<?php
/*
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2022-02-09 21:11:15
 * @LastEditors: iowen
 * @LastEditTime: 2023-01-24 15:33:11
 * @FilePath: \onenav\inc\functions\functions.php
 * @Description: 
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$functions = array(
    'io-check-link',
    'io-widget-tab',
    'io-widgets',
    'io-login',
    'io-user',
    'io-tools-hotcontent',
    'io-single-site',
    'io-letter-ico',
    'io-tool',
    'io-footer',
    'io-oauth'
);

foreach ($functions as $function) {
    $path = 'inc/functions/' . $function . '.php';
    require get_theme_file_path($path);
}
/**
 * 获取分类排序规则
 * @param string $_order
 * @return array
 */
function get_term_order_args($_order){
    switch ($_order) {
        case 'views':
            $args = array(      
                'meta_key' => 'views',
                'orderby'  => array( 'meta_value_num' => 'DESC', 'date' => 'DESC' ),
            );
            break;
        case '_sites_order': 
            if ( io_get_option('sites_sortable')){
                $args = array(      
                    'orderby' => array( 'menu_order' => 'ASC', 'ID' => 'DESC' ),
                );
            }else{
                $args = array(      
                    'meta_key' => '_sites_order',
                    'orderby'  => array( 'meta_value_num' => 'DESC', 'date' => 'DESC' ),
                );
            }
            break;
        case '_down_count':
            $args = array(      
                'meta_key' => '_down_count',
                'orderby'  => array( 'meta_value_num' => 'DESC', 'date' => 'DESC' ),
            );
        case 'ID':
            $args = array(      
                'orderby' => $_order,
                'order'   => 'DESC',
            );
            break;
        default:
            $args = array(      
                'orderby' => array( $_order => 'DESC', 'ID' => 'DESC' ),
            );
            break;
    }
    return apply_filters('io_term_order_args_filters', $args, $_order);
}

/**
 * 排序
 * @description: 
 * @param string $db 数据库
 * @param object $origins 源数据
 * @param array $data 排序数据
 * @param string $origin_key 排序数据源key
 * @param string $order_key 数据库排序字段
 * @param string $where_key 判断条件
 * @return array
 */
function io_update_obj_order($db,$origins,$data,$origin_key,$order_key,$where_key='id'){
    $results = array(
        'status' => 0,
        'msg'    => '',
    );
    if (!is_array($origins) || count($origins) < 1){
        $results['msg'] = __('数据错误！','i_theme');
        return $results; 
    }
    //创建ID列表
    $objects_ids    = array();
    foreach($origins as $origin)
    {
        $objects_ids[] = (int)$origin->id;   
    }
    $index = 0;
    for($i = 0; $i < count($origins); $i++){
        if(!isset($objects_ids[$i]))
            break;
            
        $objects_ids[$i] = (int)$data[$origin_key][$index];//替换列表id为排序id
        $index++;
    }
    global $wpdb;
    //更新数据库中的菜单顺序
    foreach( $objects_ids as $order => $id ) 
    {
        $update = array(
            $order_key => $order
        );
        $wpdb->update( $db , $update, array($where_key => $id) ); 
    } 
    $results = array(
        'status' => 1,
        'msg'    => __('排序成功！','i_theme'),
    );
    return $results;
}
/**
 * 搜索
 * @return string
 */
function search_results(){   
    global $wp_query;    
    return get_search_query() . '<i class="text-danger px-1">•</i>' . sprintf(__('找到 %s 个相关内容', 'i_theme'), $wp_query->found_posts);
}

/**
 * 发送验证码
 * @param mixed $to 邮箱或者电话号码
 * @param string $type 类型
 * @return mixed
 */
function io_send_captcha($to, $type = 'email')
{
    if(!session_id()) session_start();
    if (!empty($_SESSION['code_time'])) {
        $time_x = strtotime(current_time('mysql')) - strtotime($_SESSION['code_time']);
        if ($time_x < 60) {
            //剩余时间
            return array('status' => 2, 'msg' => (60 - $time_x) . '秒后可重新发送');
        }
    }
    $code = io_get_captcha();
    
    $_SESSION['reg_mail_token'] = $code;
    $_SESSION['new_mail']       = $to;
    $_SESSION['code_time']      = current_time('mysql');
    session_write_close();

    switch ($type) {
        case 'email':
            $result = io_mail('', $to, sprintf(__('「%s」邮箱验证码', 'i_theme'), get_bloginfo('name')), array('date' => date("Y-m-d H:i:s", current_time('timestamp')), 'code' => $code), 'verification-code');

            if (is_array($result)) {
                return array('status' => 3, 'msg' => $result['msg']);
            } elseif ($result) {
                return array('status' => 1, 'msg' => __('邮箱验证码发送成功，请前往邮箱查看！', 'i_theme'));
            } else {
                return array('status' => 3, 'msg' => __('发送验证码失败，请稍后再尝试。', 'i_theme'));
            }
            break;
        case 'phone':
            $result = IOSMS::send($to, $code);
            if (!empty($result['result'])) {
                $result['error'] = 0;
                $result['msg'] = __('短信已发送', 'i_theme');
            }
            $ret = array('status' => 1, 'msg' => $result['msg']);
            if ($result['error'] == 1) {
                $ret['status'] = 3;
            }
            return $ret;
            break;
    }
}
/**
 * 获取二维码图片url
 * @param mixed $data
 * @param mixed $size
 * @param mixed $margin
 * @return string
 */
function get_qr_url($data, $size, $margin = 10){
    if (io_get_option('qr_api') === 'local') {
        return home_url("/qr/?text={$data}&size={$size}&margin={$margin}");
    } else {
        return str_ireplace(array('$size', '$url'), array($size, $data), io_get_option('qr_url'));
    }
}
