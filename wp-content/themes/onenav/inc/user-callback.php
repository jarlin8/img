<?php
/*!
 * Theme Name:One Nav
 * Theme URI:https://www.iotheme.cn/
 * Author:iowen
 * Author URI:https://www.iowen.cn/
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action('wp_ajax_post_star','post_star_users_callback'); 
add_action('wp_ajax_nopriv_post_star','post_star_users_callback');
function post_star_users_callback(){ 
    $user = wp_get_current_user();
    if(!$user->ID) {
        io_error('{"status":2,"msg":"'.__('请先登录才能收藏哦!','i_theme').'"}'); 
    } 
    if (!wp_verify_nonce($_POST['ticket'],"post_star_nonce")){
        io_error('{"status":3,"msg":"'.__('对不起!您没有通过安全检查','i_theme').'"}',true);
    }
    $post_id = absint($_POST["post_id"]); 
    $post_type = esc_sql($_POST["post_type"]); 
    $delete = isset($_POST["delete"])?absint($_POST["delete"]):0;  //1 为执行删除
    if(!$post_id) {
        io_error('{"status":4,"msg":"'.__('文章 id 错误','i_theme').'"}');  
    } 

    $star_user_ids = array_unique(get_post_meta( $post_id, "io_{$post_type}_star_users", false));
    if(in_array($user->ID, $star_user_ids)) {
        if($delete){  
            if(delete_post_meta($post_id, "io_{$post_type}_star_users", $user->ID)) {// io_post_star_users 不唯一, 必须提供第三个参数, 否则该文章下的io_post_star_users的meta全部被删除
                $star_count = get_post_meta($post_id, '_star_count', true); 
                if (!$star_count || !is_numeric($star_count)){
                    update_post_meta($post_id, '_star_count', 0);
                }else{
                    update_post_meta($post_id, '_star_count', ($star_count - 1));
                }
                io_error('{"status":1,"msg":"'.__('取消收藏成功','i_theme').'","count":"'.(count($star_user_ids)-1).'"}');
            }else{
                io_error('{"status":4,"msg":"'.__('取消收藏失败！！！','i_theme').'"}'); 
            }
        }
        io_error('{"status":1,"msg":"'.__('收藏成功','i_theme').'","count":"'.count($star_user_ids).'"}');  
    }else{ 
        if($delete){
            io_error('{"status":4,"msg":"'.__('操作异常！！！','i_theme').'"}'); 
        }
        $add = add_post_meta($post_id, "io_{$post_type}_star_users", $user->ID); //  io_post_star_users 不唯一
        if($add) {
            $star_count = get_post_meta($post_id, '_star_count', true); 
            if (!$star_count || !is_numeric($star_count)){
                update_post_meta($post_id, '_star_count', 1);
            }else{
                update_post_meta($post_id, '_star_count', ($star_count + 1));
            }
            io_error('{"status":1,"msg":"'.__('收藏成功','i_theme').'","count":"'.(count($star_user_ids)+1).'"}');  
        }else{
            io_error('{"status":4,"msg":"'.__('收藏失败！！！','i_theme').'"}');  
        }
    }
} 

//修改用户信息 
add_action('wp_ajax_change_profile', 'change_profile_callback');
function change_profile_callback(){ 
    if (!wp_verify_nonce($_POST['_wpnonce'],'change_profile')){
        io_error('{"status":2,"msg":"'.__('对不起!您没有通过安全检查','i_theme').'"}');
    }
    $user = wp_get_current_user();
    if(!$user->ID) {
        io_error('{"status":2,"msg":"'.__('非法请求!','i_theme').'"}'); 
    } 
    $avatar_type = isset($_POST['avatar'])?esc_sql($_POST['avatar']):'custom';
    $nickname = str_replace(array('<','>','&','"','\'','#','^','*','_','+','$','?','!'), '', esc_sql($_POST['mm_name'])); 
    $legal=is_username_legal($nickname);
    if($legal['error']){
        io_error('{"status":3,"msg":"'.$legal['msg'].'"}');
    }
    $userdata = array();
    $userdata['ID'] = $user->ID;
    
    $userdata['nickname'] = $nickname;
    $userdata['display_name'] = $nickname; 
    $userdata['user_url'] = esc_sql($_POST['mm_url']);
    $userdata['description'] = esc_sql($_POST['mm_desc']);
    
    update_user_meta($userdata['ID'], 'avatar_type', $avatar_type);
    wp_update_user($userdata);
    io_error('{"status":1,"msg":"'.__('资料修改成功！','i_theme').'"}');
    
}

//注册&绑定邮箱 
add_action('wp_ajax_register_after_bind_email', 'register_after_bind_email_callback');
add_action('wp_ajax_nopriv_register_after_bind_email', 'register_after_bind_email_callback');  
function register_after_bind_email_callback(){
    $new_mail  = esc_sql($_POST['user_email']);
    $mm_token  = ($_POST['verification_code']);
    $bind_type = esc_sql($_POST['bind_type']);
    $task      = esc_sql($_POST['task']);
    
    if(empty($new_mail) || empty($mm_token) || empty($bind_type)){
        io_error('{"status":2,"msg":"'.__('请认真填写表单！','i_theme').'"}'); 
    }

    $err = __('邮箱怎么变了！', 'i_theme');
    if($bind_type == 'phone'){
        $err = __('手机号怎么变了！', 'i_theme');
    }

    if(!session_id()) session_start();
    if(isset($_SESSION['new_mail']) && $_SESSION['new_mail'] != $new_mail)
        io_error('{"status":3,"msg":"'.$err.'"}');
    if(!isset($_SESSION['reg_mail_token']) || $mm_token != $_SESSION['reg_mail_token'] )
        io_error('{"status":4,"msg":"'.__('验证码不正确！','i_theme').'"}');

    //执行人机验证
    io_ajax_is_robots();

    if ($task === 'new') {//新增用户
        $args =  maybe_unserialize($_SESSION['temp_oauth']);

        if ($bind_type == 'email') {
            $prename = explode('@', $new_mail)[0];
            $extname = rand(100, 988);
            $login_name = $prename;
            if (username_exists($login_name)) {
                $login_name = $prename . $extname;
                while (username_exists($login_name)) {
                    $extname++;
                }
            }
        }else{
            $login_name = $new_mail;
        }
        $user_pass = wp_generate_password();
        $user_mail = $bind_type == 'email' ? $new_mail : '';

        $user_id = wp_create_user($login_name, $user_pass, $user_mail);
        if (is_wp_error($user_id)) {
            //新建用户出错
            io_error('{"status":3,"msg":"'.$user_id->get_error_message().'"}');  
        } else {
            //新建用户成功
            update_user_meta($user_id, 'oauth_new', $args['type']);
            /**标记为系统新建用户 */
            //更新用户mate
            $args['user_id'] = $user_id;
            $args['login_name'] = $login_name;
            io_oauth_update_user_meta($args, true);

            if ($bind_type == 'phone')
                update_user_meta($user_id, 'phone_number', $new_mail);
            //登录
            $user = get_user_by('id', $user_id);
            wp_set_current_user($user_id, $user->user_login);
            wp_set_auth_cookie($user_id, true);
            do_action('wp_login', $user->user_login, $user);
    
            // 准备返回数据
            unset($_SESSION['new_mail']);
            unset($_SESSION['reg_mail_token']);
            unset($_SESSION['temp_oauth']);
    
            io_error('{"status":1,"msg":"'.__('绑定成功！','i_theme').'","goto":"'.$args['rurl'].'"}'); 
        }
    } else {
        $user = wp_get_current_user();
        if (!$user->ID) {
            io_error('{"status":2,"msg":"' . __('非法请求!', 'i_theme') . '"}');
        }

        if ($bind_type == 'email') {
            $userdata = array(
                'ID'         => $user->ID,
                'user_email' => $new_mail
            );
            $return = wp_update_user($userdata);
            if (is_wp_error($return)) {
                io_error('{"status":3,"msg":"' . $return->get_error_message() . '"}');
            }
        } else {
            update_user_meta($user->ID, 'phone_number', $new_mail);
        }
        unset($_SESSION['new_mail']);
        unset($_SESSION['reg_mail_token']);

        io_error('{"status":1,"msg":"' . __('绑定成功！', 'i_theme') . '"}');
    }
}
/**
 ===========================================================================================
 */
//注册并且绑定邮箱 
add_action('wp_ajax_register_and_bind_email', 'register_and_bind_email_callback');
add_action('wp_ajax_nopriv_register_and_bind_email', 'register_and_bind_email_callback');  
function register_and_bind_email_callback(){
    $new_mail = esc_sql($_POST['user_email']);
    $mm_token = ($_POST['verification_code']);
    $new_pass = $_POST['user_pass'];
    if(empty($new_mail) || empty($mm_token) || empty($new_pass)){
        io_error('{"status":2,"msg":"'.__('请认真填写表单！','i_theme').'"}'); 
    }
    if(!session_id()) session_start();
    if(isset($_SESSION['new_mail']) && $_SESSION['new_mail'] != $new_mail)
        io_error('{"status":3,"msg":"'.__('邮箱怎么变了！','i_theme').'"}');
    if(!isset($_SESSION['reg_mail_token']) || $mm_token != $_SESSION['reg_mail_token'] )
        io_error('{"status":4,"msg":"'.__('验证码不正确！','i_theme').'"}');

    //执行人机验证
    io_ajax_is_robots();
    
    $args    =  maybe_unserialize($_SESSION['temp_oauth']);

    $prename = explode('@', $new_mail)[0];
    $extname = rand(100,988);
    $login_name = $prename;
    if(username_exists($login_name)){
        $login_name = $prename.$extname;
        while(username_exists($login_name)){ $extname++; }
    }
    $user_pass = $new_pass;  

    $user_id = wp_create_user($login_name, $user_pass, $new_mail);
    if (is_wp_error($user_id)) {
        //新建用户出错
        io_error('{"status":3,"msg":"'.$user_id->get_error_message().'"}');  
    } else {
        //新建用户成功
        update_user_meta($user_id, 'oauth_new', $args['type']);
        /**标记为系统新建用户 */
        //更新用户mate
        $args['user_id'] = $user_id;
        $args['login_name'] = $login_name;
        io_oauth_update_user_meta($args, true);

        //标记已经绑定邮箱
        update_user_meta($user_id, 'email_status', 1);
        //登录
        $user = get_user_by('id', $user_id);
        wp_set_current_user($user_id, $user->user_login);
        wp_set_auth_cookie($user_id, true);
        do_action('wp_login', $user->user_login, $user);

        // 准备返回数据
        unset($_SESSION['new_mail']);
        unset($_SESSION['reg_mail_token']);
        unset($_SESSION['temp_oauth']);

        io_error('{"status":1,"msg":"'.__('绑定成功！','i_theme').'","goto":"'.$args['rurl'].'"}'); 
    }
}
/**
 ===========================================================================================
 */

//注册时邮箱&手机号验证
add_action('wp_ajax_nopriv_reg_email_token', 'reg_email_token_callback');  
add_action('wp_ajax_reg_email_token', 'reg_email_token_callback');
function reg_email_token_callback(){
    $to = addslashes(trim($_POST['mm_mail']));
    $reg = reg_form_judgment($to);
    if(!empty($reg['error'])){
        io_error(json_encode($reg['error']));
    }
    $reg_type = $reg['type'];
    $to       = $reg['to'];
    $user = wp_get_current_user();
    if ('email' == $reg_type && (!$user->ID && email_exists($to)) || ($user->ID && email_exists($to) && $user->user_email != $to)) {
        io_error('{"status":3,"msg":"' . __('该电子邮件地址已经被注册，请换一个！', 'i_theme') . '"}');
    }
    if ('phone' == $reg_type && io_get_user_by( $to,'phone')) {
        io_error('{"status":3,"msg":"' . __('该手机号已注册，请换一个！', 'i_theme') . '"}');
    }
    io_error(io_send_captcha($to,$reg_type));    
}

//验证邮箱&手机，发送验证码
add_action('wp_ajax_get_email_token', 'get_email_token_callback');
function get_email_token_callback(){ 
    $user = wp_get_current_user();
    if(!$user->ID) {
        io_error('{"status":2,"msg":"'.__('请先登录!','i_theme').'"}'); 
    }  
    $bind_type  = esc_sql($_POST['bind_type']);
    $user_email = isset($_POST['mm_mail']) ? addslashes(trim($_POST['mm_mail'])) : '';//发送到的用户名 
    $err1       = __('邮箱格式错误！', 'i_theme');
    $err2       = __('该电子邮件地址已经被注册，请换一个！', 'i_theme');
    if( $bind_type === 'phone'){
        $err1 = __('手机号格式错误！', 'i_theme');
        $err2 = __('该手机号已经被注册，请换一个！', 'i_theme');
        if(!IOSMS::is_phone_number($user_email)){
            io_error('{"status":3,"msg":"' . $err1 . '"}');
        }
        if (io_get_user_by( $user_email,'phone'))
            io_error('{"status":3,"msg":"' . $err2 . '"}');
    } else {
        if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
            io_error('{"status":3,"msg":"' . $err1 . '"}');
        }
        if (email_exists($user_email) && $user->user_email != $user_email)
            io_error('{"status":3,"msg":"' . $err2 . '"}');
    }
    io_error(io_send_captcha($user_email, $bind_type));  
}

//修改用户密码 
add_action('wp_ajax_change_safe_info', 'change_safe_info_callback');
function change_safe_info_callback(){ 
    if (!wp_verify_nonce($_POST['change_safe'],'change_safe_info')){
        io_error('{"status":0,"msg":"'.__('对不起!您没有通过安全检查','i_theme').'"}');
    } 
    $new_pass = $_POST['mm_pass_new'];
    $new_pass2 = $_POST['mm_pass_new2'];
    $userdata = array();
    $userid = wp_get_current_user()->ID;
    $userdata['ID'] = $userid;
    if($new_pass!=$new_pass2){ 
        io_error('{"status":2,"msg":"'.__('两次输入的密码不同！','i_theme').'"}');
    }if(strlen($new_pass) < 6){
        io_error('{"status":2,"msg":"'. __( '密码长度至少6位!', 'i_theme' ) .'"}');
    }else{
        if($new_pass) 
            $userdata['user_pass'] = $new_pass;
        if(count($userdata)>1){
            wp_update_user($userdata);
            io_error('{"status":1,"msg":"'.__('密码修改成功！','i_theme').'"}'); 
        }else{
            io_error('{"status":1,"msg":"'.__('没用任何变更！','i_theme').'"}'); 
        }
    } 
}

//修改&验证用户邮箱
add_action('wp_ajax_mail_bind', 'mail_bind_callback');
function mail_bind_callback(){ 
    if (!wp_verify_nonce($_POST['mail_nonce'],'mail_bind')){
        io_error('{"status":0,"msg":"'.__('对不起!您没有通过安全检查','i_theme').'"}');
    } 
    $user = wp_get_current_user();
    if (!$user->ID) {
        io_error('{"status":2,"msg":"' . __('非法请求!', 'i_theme') . '"}');
    }
    $new_mail  = esc_sql($_POST['mm_mail']);
    $mm_token  = esc_sql($_POST['mm_token']);
    $bind_type = esc_sql($_POST['bind_type']);
    $user_id   = $user->ID;
    $err1      = __('邮箱不能为空！', 'i_theme');
    $err2      = __('邮箱怎么变了！', 'i_theme');
    if ($bind_type === 'phone') {
        $err1 = __('手机号不能为空！', 'i_theme');
        $err2 = __('手机号怎么变了！', 'i_theme');
    }

    if(!$mm_token)
        io_error('{"status":2,"msg":"'.__('请输入验证码！','i_theme').'"}');
    if(!$new_mail){ 
        io_error('{"status":2,"msg":"'.$err1.'"}');
    }
    if(!session_id()) @session_start();
    if(isset($_SESSION['new_mail']) && $_SESSION['new_mail'] != $new_mail)
        io_error('{"status":3,"msg":"'.$err2.'"}');
    if(!isset($_SESSION['reg_mail_token']) || $mm_token != $_SESSION['reg_mail_token'] )
        io_error('{"status":4,"msg":"'.__('验证码不正确！','i_theme').'"}');
    if ($bind_type === 'phone') {
        if(io_get_user_by( $new_mail,'phone')){
            io_error('{"status":3,"msg":"' . __('此手机号已存在！', 'i_theme') . '"}');
        }
        update_user_meta($user_id, 'phone_number', $new_mail);
        unset($_SESSION['new_mail']);
        unset($_SESSION['reg_mail_token']);
        io_error('{"status":1,"msg":"' . __('手机号修改成功！', 'i_theme') . '"}');
    } else {
        $userdata = array('ID' => $user_id);
        $email    = $$user->user_email;
        if ($email != $new_mail) {
            if (email_exists($new_mail))
                io_error('{"status":3,"msg":"' . __('此邮箱已存在！', 'i_theme') . '"}');
            else {
                $userdata['user_email'] = esc_sql($_POST['mm_mail']);
            }
        }
        if (count($userdata) > 1) {
            wp_update_user($userdata);
            unset($_SESSION['new_mail']);
            unset($_SESSION['reg_mail_token']);
            io_error('{"status":1,"msg":"' . __('邮箱修改成功！', 'i_theme') . '"}');
        } else {
            io_error('{"status":2,"msg":"' . __('没用任何变更！', 'i_theme') . '"}');
        }
    }
}

//存储书签页设置 
add_action('wp_ajax_save_bookmark_set', 'save_bookmark_set_callback');
function save_bookmark_set_callback(){ 
    if (!wp_verify_nonce($_POST['_wpnonce'],'bookmark_set')){
        io_error('{"status":0,"msg":"'.__('对不起!您没有通过安全检查','i_theme').'"}');
    } 
    $key =  absint(base64_io_decode($_POST['key']));
    $userid = wp_get_current_user()->ID;
    if($key != $userid){
        io_error('{"status":2,"msg":"'.__('无权修改！','i_theme').'"}');
    }
    $share_bookmark = isset($_POST['share-bookmark'])?1:0;
    $hide_title     = isset($_POST['hide-title'])?1:0;
    $is_go          = isset($_POST['is-go'])?1:0;
    $sites_title    = esc_sql($_POST['sites-title']);
    $quick_nav      = esc_sql($_POST['quick-nav']);
    $custom_img     = esc_sql($_POST['custom-img']);
    $bg             = esc_sql($_POST['bg']);

    $userdata = array(
        'share_bookmark'    => $share_bookmark,
        'hide_title'        => $hide_title,
        'is_go'             => $is_go,
        'sites_title'       => $sites_title,
        'quick_nav'         => $quick_nav,
        'bg'                => $bg,
        'custom_img'        => $custom_img,
    ); 
    update_user_meta($userid, 'bookmark_set', maybe_serialize($userdata));
    io_error('{"status":1,"msg":"'.__('保存成功！','i_theme').'"}');
}
