<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
/*
Template Name: 问答模板
Template Post Type: post, page
*/
get_header(); ?>

	<?php begin_primary_class(); ?>

		<main id="main" class="be-main qa-main site-main<?php if ( zm_get_option( 'p_first' ) ) { ?> p-em<?php } ?><?php if ( zm_get_option( 'code_css' ) ) { ?> code-css<?php } ?>" role="main">
			<?php while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" class="post-item post ms" <?php aos_a(); ?>>
					<?php header_title(); ?>
						<?php if ( get_post_meta(get_the_ID(), 'header_img', true) || get_post_meta(get_the_ID(), 'header_bg', true) ) { ?>
						<?php } else { ?>
							<?php if ( get_post_meta(get_the_ID(), 'mark', true) ) { ?>
								<?php the_title( '<h1 class="entry-title">', t_mark() . '</h1>' ); ?>
							<?php } else { ?>
								<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
							<?php } ?>
						<?php } ?>
					</header>

					<div class="entry-content">
						<?php begin_single_meta(); ?>

						<div class="single-content">
							<?php the_content(); ?>
						</div>

						<?php if ( ! zm_get_option( 'be_like_content' ) || ( wp_is_mobile() ) ) { ?>
							<?php be_like(); ?>
						<?php } ?>

						<div class="beqa-comments-title">
							<?php comments_popup_link( '<span class="dashicons dashicons-coffee"></span>' . sprintf(__( '回复', 'begin' )) . ' 0', '<span class="dashicons dashicons-coffee"></span>' . sprintf(__( '回复', 'begin' )) . ' 1 ', '<span class="dashicons dashicons-coffee"></span>' . sprintf(__( '回复', 'begin' )) . ' %' ); ?>
						</div>

						<div class="qa-comments-box"><?php begin_comments(); ?></div>
						<div class="clear"></div>

						<div class="qa-related">
							<h3><?php _e( '您可能喜欢', 'begin' ); ?></h3>
							<ul>
								<?php 
									global $post, $catid;
									$cat = get_the_category();
									foreach($cat as $key=>$category){
										$catid = $category->term_id;
									}
									$q = new WP_Query( array(
										'showposts' => 10,
										'post_type' => 'post',
										'cat' => $catid,
										'post__not_in' => array($post->ID),
										'order' => 'orderby',
										'ignore_sticky_posts' => 1
									) );
								?>
								<?php while ($q->have_posts()) : $q->the_post(); ?>

									<li class="cat-title">
									<?php the_title( sprintf( '<a href="%s" rel="bookmark"><i class="be be-arrowright"></i>', esc_url( get_permalink() ) ), '</a>' ); ?>
									</li>
								<?php endwhile; ?>
								<?php wp_reset_postdata(); ?>
							</ul>
							<div class="clear"></div>
						</div>

						<div class="content-empty"></div>
						<?php get_template_part('ad/ads', 'single-b'); ?>
						<?php if ( ! is_page() ) { ?>
							<footer class="single-footer"><?php begin_single_cat(); ?></footer>
						<?php } ?>
						<div class="clear"></div>
					</div>
				</article>
			<?php endwhile; ?>
		</main>
	</div>

<?php if ( ! get_post_meta( get_the_ID(), 'no_sidebar', true ) && ( ! zm_get_option( 'single_no_sidebar' ) ) ) { ?>
<?php get_sidebar(); ?>
<?php } ?>
<?php get_footer(); ?>