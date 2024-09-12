<?php
$link_register = wp_registration_url();
$dwqa_register_page = dwqa_register_page();
if(is_numeric($dwqa_register_page) && $dwqa_register_page > 0){
	$link_register = get_permalink($dwqa_register_page);
}
?>
<div class="dwqa-answers-login">
	<div class="dwqa-answers-login-title">
		<?php
			if ( is_singular( 'dwqa-question' )):?>
				<p>
					<?php printf( __( '请选择一种方式登录或者 %1$s注册%2$s 后回答和提问', 'dwqa' ), '<a href="'.$link_register.'">', '</a>' ) ?>
				</p>
			<?php else: ?>
				<p>
					<?php printf( __( '请选择一种方式登录或者 %1$s注册%2$s 后回答和提问', 'dwqa' ), '<a href="'.$link_register.'">', '</a>' ) ?>
				</p>
			<?php endif;
		?>
<span class="act-rehub-login-popup rh-header-icon rh_login_icon_n_btn mobileinmenu " data-type="login"><i class="fas fa-user-check"></i> 直接登录<span>登陆/注册</span></span>
	</div>
</div>