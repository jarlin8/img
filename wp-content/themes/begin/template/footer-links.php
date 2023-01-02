<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( is_front_page() && !is_paged() ) { ?>
	<?php if ( ! zm_get_option( 'layout' ) || ( zm_get_option( "layout" ) == 'group' ) ) { ?>
		<?php if ( ! zm_get_option( 'footer_link_no' ) || ( ! wp_is_mobile() ) ) { ?>
		<div class="links-group">
			<?php links_footer(); ?>
		</div>
		<?php } ?>
	<?php } else { ?>
		<?php if ( !zm_get_option( 'footer_link_no' ) || ( !wp_is_mobile() ) ) { ?>
			<div class="links-box">
				<?php links_footer(); ?>
			</div>
		<?php } ?>
	<?php } ?>
<?php } ?>