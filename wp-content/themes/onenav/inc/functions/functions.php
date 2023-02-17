<?php
/*
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2022-02-09 21:11:15
 * @LastEditors: iowen
 * @LastEditTime: 2023-02-16 15:51:55
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
    'io-oauth',
    'io-site'
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
            if ( io_get_option('sites_sortable',false)){
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
                return array('status' => 1, 'msg' => __('发送成功，请前往邮箱查看！', 'i_theme'));
            } else {
                return array('status' => 3, 'msg' => __('发送验证码失败，请稍后再尝试。', 'i_theme'));
            }
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
    }
}

/**
 * 验证码判断
 * @param mixed $type
 * @param mixed $to
 * @param mixed $code_name
 * @return bool
 */
function io_ajax_is_captcha($type, $to = '', $code_name = 'verification_code'){
    if (empty($to)) {
        io_error('{"status":2,"msg":"'.__('参数错误!','i_theme').'"}'); 
    } 
    $name = array(
        'email' => __('邮箱', 'i_theme'),
        'phone' => __('手机号', 'i_theme'),
    );
    if (empty($_REQUEST[$code_name])) {
        io_error('{"status":2,"msg":"'.sprintf(__('请输入%s验证码','i_theme'),$name[$type]).'"}'); 
    } 
    $is_captcha = io_is_captcha($type, $to, $_REQUEST[$code_name]);
    if ($is_captcha['error']) {
        io_error('{"status":3,"msg":"' . $is_captcha['msg'] . '"}');
    }

    return true;
}
/**
 * 获取二维码图片url
 * @param mixed $data
 * @param mixed $size
 * @param mixed $margin
 * @return string
 */
function get_qr_url($data, $size, $margin = 10){
    if (io_get_option('qr_api','local') === 'local') {
        return home_url("/qr/?text={$data}&size={$size}&margin={$margin}");
    } else {
        return str_ireplace(array('$size', '$url'), array($size, $data), io_get_option('qr_url',''));
    }
}

/**
 * 获取验证码input
 * @param mixed $id
 * @return mixed
 */
function get_captcha_input_html($id = '', $class = 'form-control'){
    if(!LOGIN_007) return true;
    $captcha_type = io_get_option('captcha_type','null');
    $input = '';
    switch ($captcha_type) {
        case 'image':
            $input = '<div class="image-captcha-group'.( in_array($id,array('io_submit_link','ajax_comment'))?'':' mb-2').'">';
            $input .= '<input captcha-type="image" type="text" size="6" name="image_captcha" class="'.$class.'" placeholder="'.__('图形验证码','i_theme').'" autocomplete="off">';
            $input .= '<input type="hidden" name="image_id" value="' . $id . '">';
            $input .= '<span class="image-captcha" data-id="' . $id . '" data-toggle="tooltip" title="'.__('点击刷新','i_theme').'"></span>';
            $input .= '</div>';
            break;
        case 'tcaptcha':
            $option = io_get_option('tcaptcha_option');
            if (!empty($option['appid']) && !empty($option['secret_key'])) {
                $input = '<input captcha-type="tcaptcha" type="hidden" name="captcha_type" value="tcaptcha" data-appid="' . $option['appid'] . '">';
            }
            break;
        case 'geetest':
            $option = io_get_option('geetest_option');
            if (!empty($option['id']) && !empty($option['key'])) {
                $input = '<input captcha-type="geetest" type="hidden" name="captcha_type" value="geetest" data-appid="' . $option['id'] . '">';
            }
            break;
        case 'vaptcha':
            $option = io_get_option('vaptcha_option');
            if (!empty($option['id']) && !empty($option['key'])) {
                $input = '<input captcha-type="vaptcha" type="hidden" name="captcha_type" value="vaptcha" data-appid="' . $option['id'] . '" data-scene="' . (char_to_num($id)%5) . '">';
            }
            break;
    }
    io_add_captcha_js_html($captcha_type);
    return $input;
}
function io_add_captcha_js_html($status = ''){
    $status = $status ?: io_get_option('captcha_type', 'null');
    if ($status != 'null') {
        add_captcha_js();
        wp_enqueue_script('captcha');
    }
}

/**
 * 验证码是否有效
 * @param mixed $type
 * @param mixed $to
 * @param mixed $code
 * @return array
 */
function io_is_captcha($type, $to, $code){
    $name = array(
        'email' => __('邮箱', 'i_theme'),
        'phone' => __('手机号', 'i_theme'),
    );
    if(!session_id()) session_start(); 

    if (empty($_SESSION['reg_mail_token']) || $_SESSION['reg_mail_token'] != $code || empty($_SESSION['new_mail']) || $_SESSION['new_mail'] != $to) {
        return array('error' => 1, 'msg' => sprintf( __('%s验证码错误！', 'i_theme'),$name[$type]));
    } else {
        if (!empty($_SESSION['code_time'])) {
            $time_x = strtotime(current_time('mysql')) - strtotime($_SESSION['code_time']);
            if ($time_x > 1800) {//30分钟有效
                return array('error' => 1, 'msg' => sprintf( __('%s验证码已过期', 'i_theme'),$name[$type]));
            }
        }
        return array('error' => 0, 'msg' => sprintf( __('%s验证码效验成功', 'i_theme'),$name[$type]));
    }
}
/**
 * 删除验证码
 * @return void
 */
function io_remove_captcha(){
    if(!session_id()) session_start(); 
    unset($_SESSION['new_mail']);
    unset($_SESSION['reg_mail_token']);
    unset($_SESSION['code_time']);
}
/**
 * 人机验证
 * @param mixed $id
 * @return bool
 */
function io_ajax_is_robots($id=''){
    if(!LOGIN_007) return true;
    $captcha_type = io_get_option('captcha_type','null');
    switch ($captcha_type) {
        case 'image':
            $id = isset($_REQUEST['image_id']) ? esc_sql($_REQUEST['image_id']) : '';
            $id = $id ?: (!empty($_REQUEST['action']) ? $_REQUEST['action'] : 'code');
            if(!session_id()) session_start();
            if (empty($_REQUEST['image_captcha']) || strlen($_REQUEST['image_captcha']) < 4) {
                echo (json_encode(array('status' => 2, 'msg' => '请输入图形验证码')));
                exit();
            }
            if (empty($_SESSION['captcha_img_code_' . $id]) || empty($_SESSION['captcha_img_time_' . $id])) {
                echo (json_encode(array('status' => 3, 'msg' => '环境异常，请刷新后重试')));
                exit();
            }
            if ($_SESSION['captcha_img_code_' . $id] !== strtolower($_REQUEST['image_captcha'])) {
                echo (json_encode(array('status' => 3, 'msg' => '图形验证码错误')));
                exit();
            }
            if (($_SESSION['captcha_img_time_' . $id] + 300) < time()) {
                echo (json_encode(array('status' => 3, 'msg' => '图形验证码已过期')));
                unset($_SESSION['captcha_img_code_' . $id]);
                unset($_SESSION['captcha_img_time_' . $id]);
                exit();
            }
            break;
        case 'tcaptcha':
            if (empty($_REQUEST['captcha']['ticket']) || empty($_REQUEST['captcha']['randstr'])) {
                echo (json_encode(array('status' => 2, 'msg' => '人机验证失败!')));
                exit();
            }
            $tencent007 = io_tcaptcha_verification($_REQUEST['captcha']['ticket'], $_REQUEST['captcha']['randstr']);
            if($tencent007['error']){
                echo (json_encode(array('status' => 2, 'msg' => $tencent007['msg'])));
                exit();
            }
            break;
        case 'geetest':
            if (empty($_REQUEST['captcha']['ticket']) || empty($_REQUEST['captcha']['lot_number'])) {
                echo (json_encode(array('status' => 2, 'msg' => '人机验证失败!')));
                exit();
            }
            $verification = io_geetest_verification($_REQUEST['captcha']);
            if ($verification['error']) {
                echo (json_encode(array('status' => 2, 'msg' => $verification['msg'])));
                exit();
            }
            break;
        case 'vaptcha':
            if (empty($_REQUEST['captcha']['ticket']) || empty($_REQUEST['captcha']['server'])) {
                echo (json_encode(array('status' => 2, 'msg' => '人机验证失败!')));
                exit();
            }
            $verification = io_vaptcha_verification($_REQUEST['captcha']);
            if ($verification['error']) {
                echo (json_encode(array('status' => 2, 'msg' => $verification['msg'])));
                exit();
            }
            break;
    }
    return true;
}

/**
 * 腾讯请求服务器验证
 */
function io_tcaptcha_verification($Ticket,$Randstr){
    $option         = io_get_option('tcaptcha_option');
    $AppSecretKey   = $option['secret_key'];  
    $appid          = $option['appid'];  
    $UserIP         = IOTOOLS::get_ip();
    $http           = new Yurun\Util\HttpRequest;
    if(!empty($option['api_secret_id'])){
        $url = "https://captcha.tencentcloudapi.com";
        $params = array(
            "Action"       => 'DescribeCaptchaResult',
            "Version"      => '2019-07-22',
            "CaptchaType"  => 9,
            "Ticket"       => $Ticket,
            "UserIp"       => $UserIP,
            "Randstr"      => $Randstr,
            "CaptchaAppId" => (int)$appid,
            "AppSecretKey" => $AppSecretKey,
            "Timestamp"    => time(),
            "Nonce"        => rand(),
            "SecretId"     => $option['api_secret_id'],
        );
        $params["Signature"] = tcaptcha_calculate_sig($params,$option['api_secret_key']);

        $result = [];
        $result['response'] = 0;

        $response = $http->post($url, $params);
        $ret      = $response->json(true);

        if(!isset($ret['Response'])){
            $result['err_msg'] = $ret;
        } else {
            $resp = $ret['Response'];
            if (!empty($resp['Error']['Message'])) {
                $result['err_msg'] = $resp['Error']['Message'];
            } elseif (isset($resp['CaptchaMsg'])) {
                if ($resp['CaptchaCode'] === 1 || strtolower($resp['CaptchaMsg']) === 'ok') {
                    $result['response'] = 1;
                } elseif ($resp['CaptchaMsg']) {
                    $result['err_msg'] = $resp['CaptchaMsg'];
                }
            } else {
                $result['err_msg'] = $ret;
            }
        }
    } else {
        $url = "https://ssl.captcha.qq.com/ticket/verify";
        $params = array(
            "aid"          => $appid,
            "AppSecretKey" => $AppSecretKey,
            "Ticket"       => $Ticket,
            "Randstr"      => $Randstr,
            "UserIP"       => $UserIP
        );
        $response = $http->get($url, $params);
        $result   = $response->json(true);
    }
    if($result){
        if($result['response'] == 1){
            
            return array(
                'error'=>0,
                'msg'  => ''
            );
        }else{
            return array(
                'error'=>1,
                'msg'  => $result['err_msg']
            );
        }
    }else{
        return array(
            'error'=>1,
            'msg'  => __('请求失败,请再试一次！','i_theme')
        );
    }
}
/**
 * 腾讯验证码签名
 * @param mixed $param
 * @param mixed $secretKey
 * @return string
 */
function tcaptcha_calculate_sig($param,$secretKey) { 
    $tmpParam = [];
    ksort($param);
    foreach ($param as $key => $value) {
        array_push($tmpParam, $key . "=" . $value);
    }
    $strParam  = join("&", $tmpParam);
    $signStr   = 'POSTcaptcha.tencentcloudapi.com/?' . $strParam;
    $signature = base64_encode(hash_hmac('SHA1', $signStr, $secretKey, true));
    return $signature;
}

/**
 * 极验行为验
 * @param mixed $data
 * @return array
 */
function io_geetest_verification($data){
    $option         = io_get_option('geetest_option');
    $api_server     = "http://gcaptcha4.geetest.com/validate?captcha_id=" . $option['id'];
    $captcha_key    = $option['key'];
    $lot_number     = $data['lot_number'];
    $captcha_output = $data['captcha_output'];
    $pass_token     = $data['ticket'];
    $gen_time       = $data['gen_time'];
    $sign_token     = hash_hmac('sha256', $lot_number, $captcha_key);

    $query = array(
        "lot_number"     => $lot_number,
        "captcha_output" => $captcha_output,
        "pass_token"     => $pass_token,
        "gen_time"       => $gen_time,
        "sign_token"     => $sign_token,
    );

    $http     = new Yurun\Util\HttpRequest;
    $response = $http->post($api_server, $query);
    $result   = $response->json(true);

    if (!isset($result['result'])) {
        return array('error' => 1, 'msg' => '验证失败');
    }

    if ($result['result'] === 'success') {
        return array('error' => 0);
    }

    return array('error' => 1, 'msg' => '验证失败' . ((!empty($result['reason']) ? '：' . $result['reason'] : '')) . ((!empty($result['msg']) ? '：' . $result['msg'] : '')));
}


/**
 * vaptcha
 * @param mixed $data
 * @return array
 */
function io_vaptcha_verification($data){
    $option    = io_get_option('vaptcha_option');
    $api_server = $data['server'];
    $token      = $data['ticket'];
    $user_ip    = IOTOOLS::get_ip(); 

    $query = array(
        "id"        => $option['id'],
        "secretkey" => $option['key'],
        "scene"     => 0,
        "token"     => $token,
        "ip"        => $user_ip,
    );

    $http     = new Yurun\Util\HttpRequest;
    $response = $http->post($api_server, $query);
    $result   = $response->json(true);

    if (!isset($result['success'])) {
        return array('error' => 1, 'msg' => '验证失败');
    }

    if ($result['success']) {
        return array('error' => 0);
    }

    return array('error' => 1, 'msg' => '验证失败' .  (!empty($result['msg']) ? '：' . $result['msg'] : ''));
}

/**
 * 内容可见度权限
 * 
 * @param mixed $query
 * @return void
 */
function io_posts_purview_query_var_filter( $query ){
    global $current_user, $pagenow; 
    if ( "upload.php" == $pagenow || isset($_REQUEST['action']) && 'query-attachments' === $_REQUEST['action']) {
        return;
    }
    $post_type = $query->get('post_type');
    $types = array('sites', 'post', 'app', 'book');
    if (is_array($post_type))
        $post_type = $post_type[0];
    if (empty($post_type) && is_single()) {
        $post_type = 'post';
    }
    if (
        (is_admin() && defined('DOING_AJAX') && DOING_AJAX) ||
        (!is_admin() && (
            ($post_type && in_array($post_type, $types)) || ($query->is_main_query() && is_archive())
        ))
    ) {
        $query->set('meta_query', get_post_user_purview_level_query_var());
    } 
}
add_action('pre_get_posts', 'io_posts_purview_query_var_filter');//pre_get_posts parse_query

function io_user_purview_level_query_var_filter( $args ){
    $args['meta_query'] = get_post_user_purview_level_query_var();
    return $args;
}
add_action('io_blog_post_query_var_filters', 'io_user_purview_level_query_var_filter');
add_action('io_archive_query_var_filters', 'io_user_purview_level_query_var_filter');

/**
 * 获取query_var
 * 
 * @return array
 */
function get_post_user_purview_level_query_var(){
    $args = array(
        array(
            'key'     => '_user_purview_level',
            'value'   => array('user','all'),
            'compare' => 'IN'
        )
    );
    $option = io_get_option('global_remove','point');
    $user   = wp_get_current_user();
        if(!$user->ID && in_array($option, array('admin', 'user'))){
            $args = array(
                array(
                    'key'     => '_user_purview_level',
                    'value'   => 'all',
                    'compare' => '='
                )
            );
        } else {
            if (user_can($user->ID, 'manage_options')) {
                $args = array(
                    array(
                        'key'     => '_user_purview_level',
                        'value'   => array('admin','user','all'),
                        'compare' => 'IN'
                    )
                );
            } else {
                // TODO 其他用户权限 VIP 等
                $args = array(
                    array(
                        'key'     => '_user_purview_level',
                        'value'   => array('user','all'),
                        'compare' => 'IN'
                    )
                );
            }
        }
    return $args;
}

/**
 * 用户授权说明提示，操作引导
 * 
 * @param string $post_type
 * @param bool $echo
 * @return void|string
 */
function get_user_level_directions_html($post_type, $echo = true){
    $title      = get_the_title();
    switch ($post_type) {
        case 'site':
            $sites_type = get_post_meta(get_the_ID(), '_sites_type', true);
            $link_url   = get_post_meta(get_the_ID(), '_sites_link', true);
            $thumbnail  = get_site_thumbnail($title, $link_url, $sites_type, false);
            break;
        case 'app':
            $thumbnail = get_post_meta_img(get_the_ID(), '_app_ico', true);
            break;
        case 'book':
            $thumbnail = get_post_meta_img(get_the_ID(), '_thumbnail', true);
            break;
        default:
            $thumbnail = io_theme_get_thumb();
    }
    $html = '<main class="content" role="main">
        <div class="content-wrap">
            <div class="content-layout">';
    $html .= '<div class="user-level-box io-radius mb-5"> 
    <div class="user-level-header modal-header-bg text-center p-3">
        <div class="m-3"><i class="iconfont icon-version icon-3x"></i></div>
        <div class="m-2">' . __('权限不足','i_theme') . '</div>
    </div>
    <div class="user-level-body p-3 d-flex">
        <div class="card-thumbnail img-type-' . $post_type . ' mr-3 d-none d-md-block">
            <div class="h-100 img-box">
                <img src="' . $thumbnail . '" alt="' . $title . '">
            </div> 
        </div> 
        <div class="d-flex flex-fill flex-column"> 
            <div class="list-body text-center text-md-left my-3 my-md-0">
                <h1 class="h5">' . $title . '</h1>
                <div class="mt-2 text-xs text-muted"><i class="iconfont icon-tishi mr-1"></i>' . __('此内容已隐藏，请登录后查看！','i_theme') . '</div>
            </div> 
            <div class="text-center text-md-right my-3 my-md-0"> 
                <a href="' . esc_url(wp_login_url(io_get_current_url())) . '" class="btn btn-dark custom_btn-d btn-lg"><i class="iconfont icon-user mr-2"></i>' . __('登录查看','i_theme') . '</a> 
            </div>    
        </div> 
    </div>
</div>';
    if ($echo)
        echo $html;
    else
        return $html;
}

/**
 * 判断是否已经评论
 * @param mixed $user_id
 * @param mixed $post_id
 * @return bool|null|string
 */
function io_user_is_commented($user_id = 0, $post_id = 0){
    if (!$user_id) {
        $user_id = get_current_user_id();
    }

    if (!$post_id) {
        $post_id = get_the_ID();
    }

    $WHERE = '';
    if ($user_id) {
        $WHERE = "`user_id`={$user_id}";
    } elseif (isset($_COOKIE['comment_author_email_' . COOKIEHASH])) {
        $email = str_replace('%40', '@', $_COOKIE['comment_author_email_' . COOKIEHASH]);
        $WHERE = "`comment_author_email`='{$email}'";
    } else {
        return false;
    }

    global $wpdb;
    $query = "SELECT `comment_ID` FROM {$wpdb->comments} WHERE `comment_post_ID`={$post_id} and `comment_approved`='1' and $WHERE LIMIT 1";
    return $wpdb->get_var($query);
}

/**
 * 获取模态框的炫彩头部
 * @param mixed $class
 * @param mixed $icon
 * @param mixed $title
 * @return string
 */
function io_get_modal_header($class = 'jb-blue', $icon = '', $title = ''){
    $html = '<div class="modal-header modal-header-bg ' . $class . '">';
    $html .= '<button type="button" class="close io-close" data-dismiss="modal" aria-label="Close"><i class="iconfont icon-close-circle text-xl"></i></button>';
    $html .= '<div class="text-center">';
    $html .= $icon ? '<i class="iconfont ' . $icon . ' icon-2x"></i>' : '';
    $html .= $title ? '<div class="mt-2 text-lg">' . $title . '</div>' : '';
    $html .= '</div>';
    $html .= '</div>';
    return $html;
}