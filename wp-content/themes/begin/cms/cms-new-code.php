<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( zm_get_option( 'cms_new_code_cat' ) ) { ?>
	<div class="cms-new-code sort" name="<?php echo zm_get_option( 'cms_new_code_s' ); ?>">
		<?php 
			if ( zm_get_option( 'cms_new_code_id' ) ) {
				$cat_ids = implode( ',', zm_get_option( 'cms_new_code_id' ) );
			} else {
				$cat_ids = '';
			}
			echo do_shortcode( '[be_ajax_post terms="' . $cat_ids . '" column="' . zm_get_option( 'cms_new_code_f' ) . '" posts_per_page="' . zm_get_option( 'cms_new_code_n' ) . '" style="' . zm_get_option( 'cms_new_code_style' ) . '" btn="' . zm_get_option( 'cms_new_code_no_cat_btn' ) . '" prev_next="' . zm_get_option( 'cms_new_prev_next_btn' ) . '" sticky="0"]' );
		?>

		<?php if ( zm_get_option( 'cms_new_code_post_img' ) ) { ?>
			<div class="line-four" <?php aos_a(); ?>>
				<?php require get_template_directory() . '/cms/cms-post-img.php'; ?>
			</div>
		<?php } ?>
		<div class="clear"></div>
		<?php get_template_part( 'ad/ads', 'cms' ); ?>
	</div>
<?php } ?>