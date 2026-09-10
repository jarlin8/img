<?php
/*
Template Name: 所有专题
*/
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php get_header(); ?>

<section id="primary-cover" class="content-area">
	<main id="main" class="be-main site-main" role="main">
		<div class="cat-cover-box">
			<?php $posts = get_posts( array( 'post_type' => 'any', 'orderby' => 'menu_order', 'meta_key' => 'special', 'ignore_sticky_posts' => 1, 'showposts' => 1000 ) ); if ($posts) : foreach( $posts as $post ) : setup_postdata( $post ); ?>
			<div class="cover4x grid-cat-<?php echo be_get_option( 'img_f' ); ?>">
				<div class="cat-cover-main" <?php aos_a(); ?>>
					<div class="cat-cover-img thumbs-b lazy">
						<?php $image = get_post_meta( get_the_ID(), 'thumbnail', true ); ?>
						<?php if ( zm_get_option( 'lazy_s' ) ) { ?>
							<a class="thumbs-back" href="<?php echo get_permalink(); ?>" rel="bookmark" data-src="<?php echo $image; ?>">
						<?php } else { ?>
							<a class="thumbs-back" href="<?php echo get_permalink(); ?>" rel="bookmark" style="background-image: url(<?php echo $image; ?>);">
						<?php } ?>

							<div class="cover-des-box">
								<?php 
									$special = get_post_meta( get_the_ID(), 'special', true );
									if ( get_post_meta( get_the_ID(), 'special', true ) ) {
										echo '<div class="special-count fd">';
										if ( get_tag_post_count( $special ) > 0 ) {
											echo get_tag_post_count( $special );
											echo  _e( '篇', 'begin' );
										} else {
											echo  _e( '未添加文章', 'begin' );
										};
										echo '</div>';
									}
								?>
								<div class="cover-des">
									<div class="cover-des-main over">
										<?php
										global $post;
										$description = get_post_meta( get_the_ID(), 'description', true );
										if ( get_post_meta( get_the_ID(), 'description', true ) ) { ?>
											<?php echo $description; ?>
										<?php } else { ?>
											<?php the_title(); ?>
										<?php } ?>
									</div>
								</div>
							</div>
						</a>
						<h4 class="cat-cover-title"><?php the_title(); ?></h4>
					</div>
				</div>
			</div>
			<?php endforeach; endif; ?>
			<?php wp_reset_postdata(); ?>
			<div class="clear"></div>
		</div>
	</main>
	<div class="clear"></div>
</section>

<?php get_footer(); ?>