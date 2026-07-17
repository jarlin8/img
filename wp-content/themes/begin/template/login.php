<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( zm_get_option( 'front_login' ) || get_option( 'comment_registration' ) ) { ?>
<div id="login-layer" class="login-overlay">
	<div id="login" class="login-layer-area">
		<div class="login-main"></div>
	</div>
</div>
<?php } ?>