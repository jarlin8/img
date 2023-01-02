<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if (zm_get_option('login')) { ?>
<div class="login-overlay" id="login-layer">
	<div id="login" class="fadeInZoom animated bgt">
		<?php be_login_reg(); ?>
		<div class="off-login dah"></div>
	</div>
</div>
<?php } ?>