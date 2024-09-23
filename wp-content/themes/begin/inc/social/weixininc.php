<?php 
class BeginloginWEIXIN {
	function login($appid,$appkey,$code){
		if ($_REQUEST ['state'] == 'beginlogin_weixin'){
			$token_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$appkey."&code=".$code."&grant_type=authorization_code";
			$response = beginlogin_get_url_contents ( $token_url );
			$msg = json_decode ( $response );
			if (isset ( $msg->errcode )) {
				echo "<h3>error:</h3>" . $msg->errcode;
				echo "<h3>msg  :</h3>" . $msg->errmsg;
				exit ();
			}else{
				$_SESSION ['weixin_access_token'] = $msg->access_token;
				$_SESSION ['weixin_open_id'] = $msg->openid;
			}
		}else{
			echo ("The state does not match. You may be a victim of CSRF.");
			exit;
		}
	}

	function weixin_cb(){
		global $wpdb;
		$mbt_weixinlogin_backurl = zm_get_option('social_login_url')?zm_get_option('social_login_url'):get_bloginfo('url');
		if (isset($_SESSION ['weixin_open_id'])){
			$user_ID = $wpdb->get_var("SELECT ID FROM $wpdb->users WHERE weixinid='".$wpdb->escape($_SESSION['weixin_open_id'])."'");
			if ($user_ID) {
				wp_set_auth_cookie($user_ID,true,false);
				wp_redirect($mbt_weixinlogin_backurl);
				exit();
			}else{
				$a= microtime()*1000000;
				$uinfo = $this->wx_oauth2_get_user_info($_SESSION ['weixin_access_token'],$_SESSION ['weixin_open_id']);
				$pass = wp_create_nonce(rand(10,1000));
				$login_name = "weixin_".wp_create_nonce($a);
				$username = $uinfo->nickname;
				$userdata=array(
					'user_login'   => $login_name,
					'user_email'   => '',
					'display_name' => $username,
					'nickname'     => $username,
					'user_pass'    => $pass,
					'role'         => get_option('default_role')
				);
				$user_id = wp_insert_user( $userdata );
				if ( is_wp_error( $user_id ) ) {
					echo $user_id->get_error_message();
				}else{
					$ff = $wpdb->query("UPDATE $wpdb->users SET weixinid = '".$wpdb->escape($_SESSION['weixin_open_id'])."' WHERE ID = '$user_id'");
					update_user_meta($user_id, 'avatar', $uinfo->headimgurl);
					wp_set_auth_cookie($user_id,true,false);
					wp_redirect($mbt_weixinlogin_backurl);
				}
				exit();
			}
		}
	}

	function wx_oauth2_get_user_info($access_token, $openid){
		$url = "https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token."&openid=".$openid."&lang=zh_CN";
		$res = beginlogin_get_url_contents($url);
		return json_decode($res);
	}
}