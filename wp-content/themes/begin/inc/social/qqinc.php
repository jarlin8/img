<?php 
class BeginloginQQ {
	function login($appid, $scope, $callback) {
		$_SESSION['rurl'] = $_REQUEST ["beginloginurl"];
		$_SESSION ['state'] = md5 ( uniqid ( rand (), true ) ); //CSRF protection
		$login_url = "https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id=" . $appid . "&redirect_uri=" . urlencode ( $callback ) . "&state=" . $_SESSION ['state'] . "&scope=" . $scope;
		header ( "Location:$login_url" );
	}
	function callback($appid,$appkey,$path) {
		if ($_REQUEST ['state'] == $_SESSION ['state']) {
			$token_url = "https://graph.qq.com/oauth2.0/token?grant_type=authorization_code&" . "client_id=" . $appid . "&redirect_uri=" . urlencode ( $path ) . "&client_secret=" . $appkey . "&code=" . $_REQUEST ["code"];

			$response = beginlogin_get_url_contents ( $token_url );
			if (strpos ( $response, "callback" ) !== false) {
				$lpos = strpos ( $response, "(" );
				$rpos = strrpos ( $response, ")" );
				$response = substr ( $response, $lpos + 1, $rpos - $lpos - 1 );
				$msg = json_decode ( $response );
				if (isset ( $msg->error )) {
					echo "<h3>错误代码:</h3>" . $msg->error;
					echo "<h3>信息  :</h3>" . $msg->error_description;
					exit ();
				}
			}

			$params = array ();
			parse_str ( $response, $params );
			$_SESSION ['qq_access_token'] = $params ["access_token"];
		} else {
			echo ("The state does not match. You may be a victim of CSRF.");
			exit;
		}
	}
	function get_openid() {
		$graph_url = "https://graph.qq.com/oauth2.0/me?access_token=" . $_SESSION ['qq_access_token'];
		
		$str = beginlogin_get_url_contents ( $graph_url );
		if (strpos ( $str, "callback" ) !== false) {
			$lpos = strpos ( $str, "(" );
			$rpos = strrpos ( $str, ")" );
			$str = substr ( $str, $lpos + 1, $rpos - $lpos - 1 );
		}

		$user = json_decode ( $str );
		if (isset ( $user->error )) {
			echo "<h3>错误代码:</h3>" . $user->error;
			echo "<h3>信息  :</h3>" . $user->error_description;
			exit ();
		}
		$_SESSION ['qq_openid'] = $user->openid;
	}
	function get_user_info() {
		$get_user_info = "https://graph.qq.com/user/get_user_info?" . "access_token=" . $_SESSION ['qq_access_token'] . "&oauth_consumer_key=".zm_get_option('qq_app_id')."&openid=" . $_SESSION ['qq_openid'] . "&format=json";
		return beginlogin_get_url_contents ( $get_user_info );
	}
	function qq_cb(){
		if (isset($_SESSION['qq_openid'])){
			global $wpdb;
			$user_ID = $wpdb->get_var("SELECT ID FROM $wpdb->users WHERE qqid='".$wpdb->escape($_SESSION['qq_openid'])."'");
			if ($user_ID) {
				wp_set_auth_cookie($user_ID,true,is_ssl());
				wp_redirect($_SESSION['rurl']);
				exit();
			}else{
				$a= microtime()*1000000;
				$pass = wp_create_nonce(rand(10,1000));
				$uinfo = json_decode($this->get_user_info());
				$login_name = "qq_".wp_create_nonce($a);
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
					$ff = $wpdb->query("UPDATE $wpdb->users SET qqid = '".$wpdb->escape($_SESSION['qq_openid'])."' WHERE ID = '$user_id'");
					if ($ff) {
						update_user_meta($user_id, 'avatar', $uinfo->figureurl_qq_2);
						wp_set_auth_cookie($user_id,true,is_ssl());
						wp_redirect($_SESSION['rurl']);
					}
				}
				exit();
			}
		}
	}
}