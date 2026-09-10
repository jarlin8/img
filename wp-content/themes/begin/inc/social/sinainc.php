<?php 
class BeginloginWEIBO {
	function login($appid, $callback) {
		$_SESSION['rurl'] = $_REQUEST ["beginloginurl"];
		$_SESSION ['state'] = md5 ( uniqid ( rand (), true ) ); //CSRF protection
		$login_url = "https://api.weibo.com/oauth2/authorize?client_id=".$appid."&response_type=code&redirect_uri=".$callback."&state=".$_SESSION['state'];
		header ( "Location:$login_url" );
	}

	function callback($appid,$appkey,$path){
		if ($_REQUEST ['state'] == $_SESSION ['state']) {
			$url = "https://api.weibo.com/oauth2/access_token";
			$data = "client_id=".$appid."&client_secret=".$appkey."&grant_type=authorization_code&redirect_uri=".$path."&code=".$_REQUEST ["code"];
			$output = json_decode(beginlogin_post($url,$data));
			$_SESSION['sina_access_token'] = $output->access_token;
			$_SESSION['sina_openid'] = $output->uid;
		}else{
			echo ("The state does not match. You may be a victim of CSRF.");
			exit;
		}
	}

	function get_user_info() {
		$get_user_info = "https://api.weibo.com/2/users/show.json?uid=".$_SESSION['sina_openid']."&access_token=".$_SESSION['sina_access_token'];
		return beginlogin_get_url_contents ( $get_user_info );
	}

	function get_user_id() {
		$get_user_id = "https://api.weibo.com/2/account/get_uid.json?access_token=".$_SESSION['sina_access_token'];
		return beginlogin_get_url_contents ( $get_user_id );
	}

	function sina_cb(){
		global $wpdb;
		$uidArray = json_decode($this->get_user_id());
		if (isset($_SESSION['sina_openid']) && isset($_SESSION['sina_access_token']) && $uidArray->uid == $wpdb->escape($_SESSION['sina_openid'])){

			$user_ID = $wpdb->get_var("SELECT ID FROM $wpdb->users WHERE sinaid='".$wpdb->escape($_SESSION['sina_openid'])."'");
			if ($user_ID) {
				wp_set_auth_cookie($user_ID,true,false);
				wp_redirect($_SESSION['rurl']);
				exit();
			}else{
				$a= microtime()*1000000;
				$pass = wp_create_nonce(rand(10,1000));
				$str = json_decode($this->get_user_info());
				$login_name = "weibo_".wp_create_nonce($a);
				$username = $str->screen_name;
				$description = $str->description;
				$userdata=array(
					'user_login'   => $login_name,
					'user_email'   => '',
					'display_name' => $username,
					'user_pass'    => $pass,
					'role'         => get_option('default_role'),
					'nickname'     => $username,
					'description'  => $description
				);
				$user_id = wp_insert_user( $userdata );
				if ( is_wp_error( $user_id ) ) {
					echo $user_id->get_error_message();
				}else{
					$ff = $wpdb->query("UPDATE $wpdb->users SET sinaid = '".$_SESSION['sina_openid']."' WHERE ID = '$user_id'");
					if ($ff) {
						update_user_meta($user_id, 'avatar', $str->avatar_large);
						wp_set_auth_cookie($user_id,true,false);
						wp_redirect($_SESSION['rurl']);
					}
				}
				exit();
			}
		}
	}
}