<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( is_front_page() && !is_paged() ) { ?>
	<?php 
		if ( current_user_can( 'administrator' ) && empty( get_bookmarks() ) ) {
			if ( be_get_option( 'home_much_links' ) == 'btn' ) {
				echo '<div class="much-links-main links-box">首页设置 → 页脚链接<a class="link-add" href="' . home_url() . '/wp-admin/link-add.php" target="_blank"><i class="be be-edit"></i> 添加链接</a></div>';
			}
		}
	?>
	<?php if ( ! be_get_option( 'layout' ) || ( be_get_option( "layout" ) == 'group' ) ) { ?>
		<?php if ( ! be_get_option( 'footer_link_no' ) || ( ! wp_is_mobile() ) ) { ?>
			<?php if ( ! be_get_option( 'home_much_links' ) || ( be_get_option( 'home_much_links' ) == 'much' ) ) { ?>
				<div class="much-links-main links-group">
					<?php group_much_links(); ?>
				</div>
			<?php } ?>
			<?php if ( be_get_option( 'home_much_links' ) == 'btn' ) { ?>
				<div class="links-group">
					<?php links_footer(); ?>
				</div>
			<?php } ?>
		<?php } ?>
	<?php } else { ?>
		<?php if ( ! be_get_option( 'footer_link_no' ) || ( ! wp_is_mobile() ) ) { ?>
			<?php if ( ! be_get_option( 'home_much_links' ) || ( be_get_option( 'home_much_links' ) == 'much' ) ) { ?>
				<div class="much-links-main links-box">
					<?php much_links(); ?>
				</div>
			<?php } ?>
			<?php if ( be_get_option( 'home_much_links' ) == 'btn' ) { ?>
				<div class="links-box">
					<?php links_footer(); ?>
				</div>
			<?php } ?>
		<?php } ?>
	<?php } ?>
<?php } ?>