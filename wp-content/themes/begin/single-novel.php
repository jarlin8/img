<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
/*
Template Name: 小说模板
Template Post Type: post
*/
remove_filter( 'be_header_sub', 'header_sub' );
add_filter( 'body_class', 'single_novel' );
if ( zm_get_option( 'novel_reading_mode' ) ) {
add_filter( 'body_class', 'reading_mode' );
}

if ( zm_get_option( 'novel_eyecare_mode' ) ) {
add_filter( 'body_class', 'single_eyecare' );
}
get_header(); ?>
<script>
jQuery(document).ready(function($){
	var cat_id = $('.novel-single-btn').data('id');
	$.cookie('be_last_url_' + cat_id, location.href, {
		expires: 60
	});
	$.cookie('be_last_title_' + cat_id, document.getElementsByTagName('h1')[0].innerHTML, {
		expires: 60
	});
});
</script>

	<div id="primary" class="content-area no-sidebar">

		<main id="main" class="be-main novel-main site-main<?php if ( zm_get_option( 'p_first' ) ) { ?> p-em<?php } ?><?php if ( zm_get_option( 'code_css' ) ) { ?> code-css<?php } ?>" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" class="post-item post ms">
					<div class="novel-nav-t"><?php novel_nav(); ?></div>
					<div class="clear"></div>
					<?php header_title(); ?>
						<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
					</header>

					<?php 
						$categories = get_the_category();
						foreach ( $categories as $category ) {
							echo '<a class="novel-back-btn" data-id="' . get_the_category()[0]->cat_ID . '" href="' . get_category_link( $category->cat_ID ) . '">' . sprintf( __( '返回目录', 'begin' ) ) . '</a>';
						}
					?>

					<div class="entry-content">
						<?php begin_single_meta(); ?>

						<div class="single-content">
							<?php the_content(); ?>
						</div>
						<?php begin_link_pages(); ?>
						<?php logic_notice(); ?>
						<div class="novel-nav-b">
							<?php novel_nav(); ?>
							<?php if ( ! be_get_option( 'novel_related' ) ) { ?>
								<?php 
									$categories = get_the_category();
									foreach ( $categories as $category ) {
										echo '<a class="novel-single-btn novel-related-btn" data-id="' . get_the_category()[0]->cat_ID . '" href="' . get_category_link( $category->cat_ID ) . '">' . sprintf( __( '目录', 'begin' ) ) . '</a>';
									}
								?>
							<?php } ?>
						</div>
						<div class="clear"></div>

						<?php if ( be_get_option( 'novel_related' ) ) { ?>
							<div class="novel-related">
								<?php 
									$categories = get_the_category();
									foreach ( $categories as $category ) {
										echo '<a class="novel-single-btn" data-id="' . get_the_category()[0]->cat_ID . '" href="' . get_category_link( $category->cat_ID ) . '"><i class="be be-sort"></i>' . sprintf( __( '章节目录', 'begin' ) ) . '</a>';
									}
								?>

								<ul>
									<?php 
										$catid = '';
										$cat = get_the_category();
										foreach($cat as $key=>$category){
											$catid = $category->term_id;
										}
										$q = new WP_Query( array(
											'posts_per_page'  => 1000,
											'post_type'       => 'post',
											'cat'             => $catid,
											'post__not_in'    => array( $post->ID ),
											'order'           => 'ASC',
											'orderby'         => 'date',
											'ignore_sticky_posts' => 1
										) );
									?>
									<?php while ($q->have_posts()) : $q->the_post(); ?>
										<li class="the-icon"><?php the_title( sprintf( '<a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' ); ?></li>
									<?php endwhile; ?>
									<?php wp_reset_postdata(); ?>
								</ul>
								<div class="clear"></div>
							</div>
						<?php } ?>

						<?php if ( ! zm_get_option( 'be_like_content' ) || ( wp_is_mobile() ) ) { ?>
							<?php be_like(); ?>
						<?php } ?>

						<?php get_template_part( 'ad/ads', 'single-b' ); ?>
						<div class="clear"></div>
					</div>
				</article>

				<?php begin_comments(); ?>

			<?php endwhile; ?>
		</main>
	</div>
<?php get_footer(); ?>