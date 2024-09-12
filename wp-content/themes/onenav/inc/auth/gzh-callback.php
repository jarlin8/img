<?php
/*
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-03-01 10:19:02
 * @LastEditors: iowen
 * @LastEditTime: 2023-01-30 12:01:32
 * @FilePath: \onenav\inc\auth\gzh-callback.php
 * @Description: 
 */
include_once('../../../../../wp-config.php'); 
if(!session_id()) session_start();

$config = io_get_option('open_weixin_gzh_key');

if (!empty($_REQUEST['echostr']) && !empty($_REQUEST['signature'])) {
    //header("Content-type:text/html;charset=utf-8");
    //微信接口校验
    $signature = $_GET["signature"];
    $timestamp = $_GET["timestamp"];
    $nonce     = $_GET["nonce"];
    $token     = $config['token'];
    $tmpArr    = array($token, $timestamp, $nonce);
    
    sort($tmpArr, SORT_STRING);
    $tmpStr = implode($tmpArr);
    $tmpStr = sha1($tmpStr);

    if ($tmpStr == $signature) {
        echo $_REQUEST['echostr'];
    }
    exit();
}

if (io_is_wechat_app()) {
    try {
        $oauth       = new \Yurun\OAuthLogin\Weixin\OAuth2($config['appid'], $config['appkey']);
        $accessToken = $oauth->getAccessToken($_SESSION['state']);
        $userInfo    = $oauth->getUserInfo();
        $openId      = $oauth->openid;
    } catch (Exception $err) {
        $title = get_current_user_id() ? __('绑定失败','i_theme') : __('登录失败','i_theme');
        wp_die(
            '<h1>' .$title. '</h1>' .
                '<p>' . $err->getMessage() . '</p>',
            403
        );
        exit;
    }
    if ($openId && $userInfo) {
        $userInfo['name'] = !empty($userInfo['nickname']) ? $userInfo['nickname'] : '';
    
        $oauth_data = array(
            'type'          => 'wechat_gzh',
            'openid'        => $openId,
            'name'          => $userInfo['name'],
            'avatar'        => !empty($userInfo['headimgurl']) ? $userInfo['headimgurl'] : '',
            'description'   => '',
            'getUserInfo'   => $userInfo,
            'rurl'          => $_SESSION['rurl'], 
        );
    
        $oauth_result = io_oauth_update_user($oauth_data);
    
        io_oauth_login_after_execute($oauth_result);
    
    } else {
        wp_die(
            '<h1>' . __('处理错误') . '</h1>' .
                '<p>' . json_encode($userInfo) . '</p>' .
                '<p>openid:' . $openId . '</p>',
            403
        );
        exit;
    }
    wp_safe_redirect(home_url());
    exit;
}

$oauth  = new ioLoginWechatGZH($config['appid'], $config['appkey'], 'gzh');
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'callback';

switch ($action) {
    case 'callback':
        //接受微信发过来的信息
        $callback = $oauth->callback();
        if ($callback) {
            if (!empty($callback['EventKey']) && !empty($callback['Event']) && in_array($callback['Event'], array('subscribe', 'SCAN'))) {
                $EventKey = str_replace('qrscene_', '', $callback['EventKey']);
                set_transient('wechat_gzh_event_key_' . $EventKey, $callback, 5 * MINUTE_IN_SECONDS);
                //给用户发送消息
                if (!empty($config['subscribe_msg']) && $callback['Event'] == 'subscribe') {
                    echo $oauth->sendMessage($config['subscribe_msg']);
                    exit();
                } elseif (!empty($config['scan_msg']) && $callback['Event'] == 'SCAN') {
                    echo $oauth->sendMessage($config['scan_msg']);
                    exit();
                }
            }
            //自动回复
            $oauth->responseMsg($config['auto_reply']);
            exit();
        }
        break;

    case 'check_callback':
        //前端验证是否回调
        $state = !empty($_REQUEST['state']) ? $_REQUEST['state'] : '';
        if (!$state) { 
            echo (json_encode(array('error' => 1, 'msg' => '参数传入错误'))); 
            exit();
        }
        // 验证 CSRF
        $option = get_transient('wechat_gzh_event_key_' . $state);
        if (!$option) {
            echo (json_encode(array('error' => 1, 'msg' => 'Waiting...'))); 
            exit;
        }
        delete_transient('wechat_gzh_event_key_' . $state);
        // -- CSRF
        $goto_uery_arg = array(
            'action' => 'login',
            'openid' => $option['FromUserName']
        );
        if (!empty($_REQUEST['oauth_rurl'])) $goto_uery_arg['oauth_rurl'] = $_REQUEST['oauth_rurl'];
        echo (json_encode(array('goto' => add_query_arg($goto_uery_arg, get_oauth_callback_url('gzh')), 'option' => $option)));
        exit();
        break;

    case 'login':
        //前台登录或者绑定
        $openId = !empty($_REQUEST['openid']) ? $_REQUEST['openid'] : '';
        if (!$openId) {
            wp_die(__('参数传入错误', 'i_theme'));
        }
        
        try {
            $userInfo = $oauth->getUserInfo($openId); //第三方用户信息
        } catch (Exception $err) {
            $title = get_current_user_id() ? __('绑定失败','i_theme') : __('登录失败','i_theme');
            wp_die(
                '<h1>' .$title. '</h1>' .
                    '<p>' . $err->getMessage() . '</p>',
                403
            );
            exit;
        }
        if (!empty($userInfo['openid'])) {
            $userInfo['name'] = !empty($userInfo['nickname']) ? $userInfo['nickname'] : '';
            $userInfo['avatar'] = !empty($userInfo['headimgurl']) ? $userInfo['headimgurl'] : '';

            $oauth_data = array(
                'type'        => 'wechat_gzh',
                'openid'      => $userInfo['openid'],
                'name'        => $userInfo['name'],
                'avatar'      => $userInfo['avatar'],
                'description' => '',
                'getUserInfo' => $userInfo,
                'rurl'        => $_SESSION['rurl'], 
            );

            $oauth_result = io_oauth_update_user($oauth_data);

            io_oauth_login_after_execute($oauth_result);

        }
        break;
}
wp_safe_redirect(home_url());
exit;
