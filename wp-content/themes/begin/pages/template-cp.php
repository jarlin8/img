<?php
/*
Template Name: 产品展示
*/
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php get_header(); ?>

<div class="ajax-content-area content-area">
	<main id="main" class="site-main ajax-site-main cp-ajax-main<?php if ( get_post_meta( get_the_ID(), 'cp_sidebar_r', true ) ){ ?> cp_sidebar_r<?php } else { ?> cp_sidebar_l<?php } ?>" role="main">
		<?php 
			$more      = get_post_meta( get_the_ID(), 'cp_more', true ) ? 'more' : '';
			$cp_style  = get_post_meta( get_the_ID(), 'cp_style', true ) ? : 'photo';
			$cp_column = get_post_meta( get_the_ID(), 'cp_column', true ) ? : '4';

			echo do_shortcode( '[cp_ajax_post posts_per_page="' . get_post_meta( get_the_ID(), 'cp_number', true ) . '" column="' . $cp_column . '" terms="' . get_post_meta( get_the_ID(), 'cp_cat_id', true ) . '" style="' . $cp_style . '" more="' . $more . '" infinite="' . get_post_meta( get_the_ID(), 'cp_infinite', true ) . '"]' );
		?>
	<div class="clear"></div>
	<?php edit_post_link('<i class="be be-editor"></i>', '<div class="page-edit-link edit-link">', '</div>' ); ?>
	<div class="clear"></div>
	</main>
	<div class="clear"></div>
</div>

<?php get_footer(); ?>