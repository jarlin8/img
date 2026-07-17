<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( zm_get_option( 'single_assets' ) ) {
	global $post;
	$cat   = get_the_category();
	$catid = '';
	if ( ! empty( $cat ) ) {
		$catid = $cat[0]->term_id;
	}
	
?>
<?php if ( zm_get_option( 'single_assets_get' ) !== 'ajax' ) { ?>
<div class="betip single-assets cms-assets-<?php echo zm_get_option( 'single_assets_f' ); ?>">
	<div class="flexbox-grid">
		<?php 
			if ( ! zm_get_option( 'single_assets_order' ) || ( zm_get_option( 'single_assets_order' ) == 'date' ) ) {
				$orderby = 'date';
			}
			if ( zm_get_option( 'single_assets_order' ) == 'rand' ) {
				$orderby = 'rand';
			}

			if ( zm_get_option( 'single_assets_order' ) == 'views' ) {
				$orderby = 'meta_value_num';
			}

			if ( ! zm_get_option( 'single_assets_get' ) || ( zm_get_option( 'single_assets_get' ) == 'cat' ) ) {
				$q = new WP_Query(array(
					'showposts'           => zm_get_option( 'single_assets_n' ),
					'post_type'           => 'post',
					'post_status'         => 'publish',
					'cat'                 => $catid,
					'meta_key'            => 'views',
					'orderby'             => $orderby,
				    'order'               => 'DESC',
					'post__not_in'        => array( $post->ID ),
					'ignore_sticky_posts' => 1
				));
			}

			if ( zm_get_option( 'single_assets_get' ) == 'post' ) {
				$q = new WP_Query(array(
					'post__in'            => explode( ',', zm_get_option( 'single_assets_id' ) ),
					'post_status'         => 'publish',
					'orderby'             => 'post__in', 
					'order'               => 'DESC',
					'ignore_sticky_posts' => 1
				));
			}

			while ($q->have_posts()) : $q->the_post();
			require get_template_directory() . '/template/assets.php';
			endwhile;
			wp_reset_postdata();
		?>
		<div class="clear"></div>
	</div>
	<?php be_help( $text = '主题选项 → 基本设置 → 相关资源' ); ?>
</div>
<?php } else { ?>
<?php 
	echo do_shortcode( '[be_ajax_post style="assets" terms="' . $catid . '" btn="no" posts_per_page="' . zm_get_option( 'single_assets_n' ) . '" more="more"]' );
?>
<?php } ?>
<?php } ?>