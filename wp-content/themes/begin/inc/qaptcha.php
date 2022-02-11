<?php
if ( ! defined( 'ABSPATH' ) ) exit;
function qaptcha_footer() {
	if (is_singular()) {
		$outer = '';
		$fpath =  get_bloginfo("template_url"). '/inc/';
		$outer.= '<script type="text/javascript">var QaptchaJqueryPage="' . $fpath . 'qaptcha.jquery.php"</script>'."\n";
		echo $outer;
	}
}

function qaptcha_comment($comment) {
	if(!session_id()) session_start();
	if ( isset($_SESSION['qaptcha']) && $_SESSION['qaptcha']) {
		unset($_SESSION['qaptcha']);
		return($comment);
	} else {
		if (isset($_POST['isajaxtype']) && $_POST['isajaxtype'] > -1) {
			die("<i class='be be-info'></i>" . sprintf(__( '滑动解锁才能提交', 'begin' )) ."");
		} else {
			if(function_exists('err'))
				err("<i class='be be-info'></i>". sprintf(__( '滑动解锁才能提交', 'begin' )) ."");
			else
				err("<i class='be be-info'></i>" . sprintf(__( '滑动解锁才能提交', 'begin' )) ."");
		}
	}
	session_write_close();
}
if ( !is_admin() ) {
/**
if (zm_get_option('comment_ajax') && zm_get_option('qt')) { 
add_action( 'comment_form', 'qaptcha_form' );
}
function qaptcha_form(){
	echo '<div class="qaptcha" title="'. sprintf(__( '滑动解锁', 'begin' )) .'"></div><div class="clear"></div>';
}
**/
add_action('wp_footer', 'qaptcha_footer');
add_action('preprocess_comment', 'qaptcha_comment');
}