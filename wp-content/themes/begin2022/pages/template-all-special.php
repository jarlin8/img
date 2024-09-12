<?php
/*
Template Name: 所有专题
*/
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php get_header(); ?>

<section id="primary-cover" class="content-area">
	<main id="main" class="site-main" role="main">
		<div class="cat-cover-box">
			<?php $posts = get_posts( array( 'post_type' => 'any', 'orderby' => 'menu_order', 'meta_key' => 'special', 'ignore_sticky_posts' => 1, 'showposts' => 1000 ) ); if($posts) : foreach( $posts as $post ) : setup_postdata( $post ); ?>
			<div class="cover4x grid-cat-<?php echo zm_get_option('img_f'); ?>">
				<div class="cat-cover-main ms bk" <?php aos_a(); ?>>
					<div class="cat-cover-img thumbs-b lazy">
						<?php $image = get_post_meta($post->ID, 'thumbnail', true); ?>
						<a class="thumbs-back sc" href="<?php echo get_permalink(); ?>" rel="bookmark" data-src="<?php echo $image; ?>">
							<div class="special-mark bz"><?php _e( '专题', 'begin' ); ?></div>
							<?php 
								$special = get_post_meta($post->ID, 'special', true);
								echo '<div class="special-count bgt">';
								echo get_tag_post_count( $special );
								echo  _e( '篇', 'begin' );
								echo '</div>';
							?>
							<div class="cover-des-box"><div class="cover-des"><?php $description = get_post_meta($post->ID, 'description', true);{echo $description;} ?></div></div>
						</a>
						<div class="clear"></div>
					</div>
					<a href="<?php echo get_permalink(); ?>" rel="bookmark"><h4 class="cat-cover-title"><?php the_title(); ?></h4></a>
				</div>
			</div>
			<?php endforeach; endif; ?>
			<?php wp_reset_query(); ?>
			<div class="clear"></div>
		</div>
	</main>
	<div class="clear"></div>
</section>

<?php get_footer(); ?>