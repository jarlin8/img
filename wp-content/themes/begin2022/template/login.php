<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if (zm_get_option('login')) { ?>
<div class="login-overlay" id="login-layer">
	<div id="login">
		<div id="login-tab" class="fadeInDown animated da bk">
			<?php echo do_shortcode( '[zml]' ); ?>
		</div>
	</div>
</div>
<?php } ?>