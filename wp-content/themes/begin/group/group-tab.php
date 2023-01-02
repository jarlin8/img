<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( zm_get_option( 'group_tab' ) ) { ?>
<div class="g-row g-line group-tabs-line sort" name="<?php echo zm_get_option( 'group_tab_s' ); ?>" <?php aos(); ?>>
	<div class="g-col">
		<div class="group-tabs-content ms bk<?php if ( ! zm_get_option( 'group_tab_img_meta' ) ) { ?> group-tab-img-meta<?php } ?>">
			<?php if ( !zm_get_option('group_tab_t') == '' ) { ?>
				<div class="group-title" <?php aos_b(); ?>>
					<?php if ( ! zm_get_option('group_tab_t') == '' ) { ?>
						<h3><?php echo zm_get_option('group_tab_t'); ?></h3>
					<?php } ?>
				</div>
			<?php } ?>

			<?php 
				if ( ! zm_get_option( 'group_tabs_mode' ) || ( zm_get_option( 'group_tabs_mode' ) == 'photo' ) ) {
					$style = 'photo';
				}
				if ( zm_get_option( 'group_tabs_mode' ) == 'grid' ) {
					$style = 'grid';
				}
				if ( zm_get_option( 'group_tabs_mode' ) == 'title' ) {
					$style = 'title';
				}
				echo do_shortcode( '[be_ajax_post style="' . $style .'" terms="' . zm_get_option( 'group_tab_cat_id' ) . '" posts_per_page="' . zm_get_option( 'group_tab_n' ) . '" column="' . zm_get_option( 'stab_f' ) . '" btn_all="no"]' );
			?>

			<div class="clear"></div>
		</div>
	</div>
</div>
<?php } ?>