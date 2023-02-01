<?php
/*
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2023-01-17 22:37:17
 * @LastEditors: iowen
 * @LastEditTime: 2023-01-28 21:44:44
 * @FilePath: \onenav\inc\functions\io-user.php
 * @Description: 
 */

/**
 * 获取隐藏的手机号码
 * @param string $phone
 * @return string
 */
function io_hide_phone($phone){
    if (strlen($phone) > 10) {
        return substr_replace($phone, '****', 3, 4);
    }
    return $phone;
}

/**
 * 隐藏邮箱号码
 * @param string $email
 * @return string
 */
function io_hide_email($email){
    $email_args = explode('@', $email);

    if (isset($email_args[0])) {
        return substr($email_args[0], 0, 1) . '****' . substr($email_args[0], -1) . '@' . $email_args[1];
    }

    return $email;
}
/**
 * 获取用户的手机号码
 * @param int $user_id
 * @param bool $hide
 * @return mixed
 */
function io_get_user_phone($user_id, $hide = true)
{
    $phone = get_user_meta($user_id, 'phone_number', true);

    if (!$phone) {
        return false;
    }

    if ($hide) {
        return io_hide_phone($phone);
    }

    return $phone;
}

/**
 * 根据meta获取用户
 * 检查手机号是否存在
 * @param mixed $value  meta 值，如：手机号
 * @param mixed $field
 * @return mixed
 */
function io_get_user_by( $value, $field = 'phone')
{
    $cache = wp_cache_get($value, 'user_by_' . $field, true);
    if (false !== $cache) {
        return $cache;
    }

    $query = new WP_User_Query(array('meta_key' => 'phone_number', 'meta_value' => $value));

    if (!is_wp_error($query) && !empty($query->get_results())) {
        $user = $query->get_results()[0];
        wp_cache_set($value, $user, 'user_by_' . $field);
        return $user;
    } else {
        return false;
    }
}

/**
 * 绑定手机号，清空对应缓存
 * @param mixed $user_id
 * @param mixed $new_phone
 * @param mixed $old_phone
 * @return void
 */
function io_bind_phone_del_cache($user_id, $new_phone, $old_phone)
{
    wp_cache_delete($old_phone, 'user_by_phone');
}
add_action('io_user_update_bind_phone', 'io_bind_phone_del_cache', 10, 3);

/**
 * 强制绑定邮箱或手机
 * @return void
 */
function io_redirect_user_bind_page()
{
    $is_bind   = io_get_option('bind_email');
    $bind_type = io_get_option('bind_type');

    if ( $is_bind!='must' || empty($bind_type) || is_super_admin() ) {
        return;
    }

    $user        = wp_get_current_user();
    $tab         = !empty($_GET['action']) ? $_GET['action'] : '';
    $redirect_to = !empty($_GET['redirect_to']) ? $_GET['redirect_to'] : home_url();
    if (!empty($user->ID) && !is_admin() && 'bind' != $tab) {
        //已经登录
        $email = $user->user_email;
        if (!$email && in_array('email', $bind_type)) {
            $bind_url = add_query_arg('redirect_to', $redirect_to, home_url('/login/?action=bind&type=bind'));
            wp_safe_redirect($bind_url);
            exit;
        }

        $phone = io_get_user_phone($user->ID);
        if (!$phone && in_array('phone', $bind_type)) {
            $bind_url = add_query_arg('redirect_to', $redirect_to, home_url('/login/?action=bind&type=bind'));
            wp_safe_redirect($bind_url);
            exit;
        }
    }
}
add_action('template_redirect', 'io_redirect_user_bind_page');
/**
 * 绑定提示
 * @return void
 */
function add_remind_bind(){
    $user = wp_get_current_user();
    $is_bind   = io_get_option('bind_email');
    if(!$user->ID || $is_bind!='bind' || is_404() || !io_get_option('remind_bind')) {
        return; 
    }
    $bind_type = io_get_option('bind_type');
    $title     = '';
    $email     = $user->user_email;
    if (!$email && in_array('email', $bind_type)) {
        $title = __('你没有绑定邮箱！','i_theme');
    } else {
        $phone = io_get_user_phone($user->ID);
        if (!$phone && in_array('phone', $bind_type)) {
            $title = __('你没有绑定手机号！','i_theme');
        }else{
            return; 
        }
    }

    if( !io_get_option('remind_only') || (  io_get_option('remind_only') && !isset($_COOKIE['io_remind_only'])) || (isset($_COOKIE['io_remind_only'])&&  $_COOKIE['io_remind_only']!="1") ){ 
        ?>
        <div id='io-remind-bind' class="io-bomb">
            <div class="io-bomb-overlay"></div>
            <div class="io-bomb-body text-center" style="max-width:260px">
                <div class="io-bomb-content rounded bg-white"> 
                <i class="iconfont icon-tishi icon-8x text-success"></i> 
                            <p class="text-md mt-3"><?php echo $title ?></p> 
                            <a href="<?php echo home_url('/login/?action=bind&type=bind') ?>" class="btn btn-danger mt-3 popup-bind-close"><?php _e('前往绑定','i_theme') ?></a>
                </div>
                <div class="btn-close-bomb mt-2 text-center">
                    <i class="iconfont popup-bind-close icon-close-circle"></i>
                </div>
            </div>
            <script>  
                $(document).ready(function(){
                    <?php echo io_get_option('remind_only')?"if(getCookie('io_remind_only')!=1)":"" ?>
                        $('#io-remind-bind').addClass('io-bomb-open');
                });
                $(document).on('click','.popup-bind-close',function() {
                    $('#io-remind-bind').removeClass('io-bomb-open').addClass('io-bomb-close');
                    <?php echo (io_get_option('remind_only')?'setCookie("io_remind_only",1,1);':'') ?>
                    setTimeout(function(){
                        $('#io-remind-bind').remove(); 
                    },600);
                });
            </script>
        </div>
    <?php
    }
}
add_action( 'wp_footer', 'add_remind_bind' );


function io_bind_oauth_html( $user_id ){
    if (!$user_id) {
        return;
    }

    $btn  = '';
    $rurl = esc_url(home_url('/user/security'));

    $args = get_social_type_data();

    foreach ($args as $arg) {
        $name     = $arg['name'];
        $type     = $arg['type'];
        $class    = $arg['class'];
        $name_key = $arg['name_key'];
        $icon     = '<i class="iconfont ' . $arg['icon'] . '"></i>';
        if ('alipay' == $type) {
            if (wp_is_mobile() && !strpos($_SERVER['HTTP_USER_AGENT'], 'Alipay')) {
                continue;
            }
        }

        $bind_href = io_get_oauth_login_url($type, $rurl);
        if ($bind_href) {
            $meta_key = $type;
            if ('weixin_gzh' == $type) {
                $meta_key = 'wechat_'.io_get_option('open_weixin_gzh_key', 'gzh', 'type');
            }
            $oauth_info = get_user_meta($user_id, $meta_key . '_getUserInfo', true);
            $oauth_id   = get_user_meta($user_id, $meta_key . '_openid', true);
            $_btn       = '';
            if ( $oauth_id ) {
                $name .= !empty($oauth_info['name']) ? ' ' . esc_attr($oauth_info['name']) : (!empty($oauth_info[$name_key]) ? ' ' . esc_attr($oauth_info[$name_key]) : '帐号');
                $_btn = '<a data-toggle="tooltip" href="javascript:;" openid="' . esc_attr($oauth_id) . '" title="解绑' . $name . '" user-id="' . $user_id . '" data-type="' . $type . '" data-action="unbound_open_id" class="btn btn-outline-primary btn-block unbound-open-id '.$class.'">' . $icon . ' 已绑定' . $name . '</a>';
            } else {
                if ('weixin_gzh' === $type) {
                    $class .= ' qrcode-signin';
                }
                $_btn = '<a data-toggle="tooltip" title="绑定' . $name . '" href="' . esc_url(add_query_arg(array('bind' => $type), $bind_href)) . '" class="btn btn-outline-success btn-block '.$class.'">' . $icon . ' 绑定' . $name . '帐号</a>';
            }
            $btn .= '<div class="col-6 my-2">'.$_btn.'</div>';
        }
    }
    if (io_get_option('open_prk')) {
        $list = io_get_option('open_prk_list');
        $bind_href = io_get_oauth_login_url('prk', io_get_current_url());
        if (is_array($list)) {
            foreach ($list as $type) {
                $oauth_info = get_user_meta($user_id, 'io_' . $type . '_getUserInfo', true);
                $oauth_id = get_user_meta($user_id, 'io_' . $type . '_openid', true);
                $name = get_open_login_name($type);
                $_btn = '';
                $ico  = $type;
                if('wx'===$type){
                    $ico = 'wechat';
                }
                if('sina'===$type){
                    $ico = 'weibo';
                }
                $icon = '<i class="iconfont icon-' . $ico . '"></i>';
                if ($oauth_info && $oauth_id) {
                    $name .= !empty($oauth_info['nickname']) ? ' ' . esc_attr($oauth_info['nickname']) : '帐号';
                    $_btn = '<a data-toggle="tooltip" href="javascript:;" openid="' . esc_attr($oauth_id) . '" title="解绑' . $name . '" user-id="' . $user_id . '" data-type="io_' . $type . '" data-action="unbound_open_id" class="btn btn-outline-primary btn-block unbound-open-id">' . $icon . ' 已绑定' . $name . '</a>';
                } else {
                    $_btn = '<a data-toggle="tooltip" title="绑定' . $name . '" href="' . esc_url(add_query_arg(array('type' => $type), $bind_href)) . '" class="btn btn-outline-success btn-block ' . $class . '">' . $icon . ' 绑定' . $name . '帐号</a>';
                }
                $btn .= '<div class="col-6 my-2">'.$_btn.'</div>';
            }
        }
    }

    $html .= '<div class="text-lg pb-3 border-bottom border-light border-2w mb-3">'.__('账号绑定','i_theme').'</div>';
    $html .= '<div class="row">';
    $html .= $btn;
    $html .= '</div>';
    
    if (io_get_oauth_login_url('weixin_gzh')) {
        $type = io_get_option('open_weixin_gzh_key', 'gzh', 'type');
        $html .= get_weixin_qr_js($type,true,false);
    }
    return $html;
}