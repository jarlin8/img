<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( be_get_option( 'cms_new_code_cat' ) ) { ?>
	<div class="cms-new-code betip">
		<?php if ( be_get_option( 'cms_new_code_new' ) && be_get_option( 'cms_new_code_no_cat_btn' ) !== 'yes' ) { ?>
			<div class="marked-ico" <?php aos_a(); ?>><?php _e( '最新', 'begin' ); ?></div>
		<?php } ?>
		<?php 
			if ( be_get_option( 'cms_new_code_id' ) ) {
				$cat_ids = implode( ',', be_get_option( 'cms_new_code_id' ) );
			} else {
				$cat_ids = '';
			}
			if ( ! be_get_option( 'cms_new_code_cat_chil' ) || ( be_get_option( 'cms_new_code_cat_chil' ) == 'true' ) ) {
				$children = 'true';
			}
			if ( be_get_option( 'cms_new_code_cat_chil' ) == 'false' ) {
				$children = 'false';
			}

			$top = be_get_option( 'cms_no_top_new' ) ? '1' : '0';

			echo do_shortcode( '[be_ajax_post terms="' . $cat_ids . '" column="' . be_get_option( 'cms_new_code_f' ) . '" posts_per_page="' . be_get_option( 'cms_new_code_n' ) . '" style="' . be_get_option( 'cms_new_code_style' ) . '" btn="' . be_get_option( 'cms_new_code_no_cat_btn' ) . '" prev_next="' . be_get_option( 'cms_new_prev_next_btn' ) . '" children="' . $children . '" sticky="0" top="' .$top . '"]' );
		?>

		<?php if ( be_get_option( 'cms_new_code_post_img' ) ) { ?>
			<div class="line-four" <?php aos_a(); ?>>
				<?php require get_template_directory() . '/cms/cms-post-img.php'; ?>
			</div>
		<?php } ?>
		<?php cms_help( $text = '首页设置 → 杂志布局 → 最新分类', $number = 'cms_new_code_s' ); ?>
	</div>
<?php } ?>