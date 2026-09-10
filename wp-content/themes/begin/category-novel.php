<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * category Template: 小说书籍
 */
remove_filter( 'be_header_sub', 'top_sub' );
//remove_filter( 'be_header_sub', 'header_img' );
remove_filter( 'be_header_sub', 'header_sub' );
function be_novel( $classes ) {
	$classes[] = 'novel';
	return $classes;
}
add_filter( 'body_class','be_novel' );
get_header(); ?>

<script>
jQuery(document).ready(function($){
	var cat_id = $('.novel-cat-title').data('id');
	var url = $.cookie('be_last_url_' + cat_id);
	var title = $.cookie('be_last_title_' + cat_id);
	$('.continue-reading').attr({
		'href': url,
		'title': title
	});
	$('.read-title').attr('href', url);
	$('.read-title').text(title);

	if(url) {
		$('.continue-reading').removeClass('no-record');
	}
});
</script>

<section id="novel" class="content-area novel-area">
	<main id="main" class="be-main site-main novel-main" role="main">
		<div class="novel-inf">
			<?php
				$terms = get_terms(
					array(
						'include' => get_query_var( 'cat' ),
					)
				);
				foreach ( $terms as $term ) {
			?>
				<div class="novel-cover-box">
					<div class="novel-inf-cover sc" data-src="<?php echo cat_cover_url( $term->term_id ); ?>"></div>
				</div>
			<?php } ?>

			<h2 class="novel-cat-title" data-id="<?php echo get_query_var( 'cat' ); ?>"><?php single_cat_title(); ?></h2>
			<?php if ( be_get_option( 'novel_author_t' ) ) { ?>
				<div class="novel-author">
					<span><?php echo be_get_option('novel_author_t'); ?></span>
					<?php if ( get_option( 'cat-author-' . $term->term_id ) ) { ?>
						<?php echo get_option( 'cat-author-' . $term->term_id ); ?>
					<?php } else { ?>
						<?php _e( '暂无', 'begin' ); ?>
					<?php } ?>
				</div>
			<?php } ?>

			<?php if ( be_get_option( 'novel_status_t' ) ) { ?>
				<div class="novel-status">
					<span><?php echo be_get_option('novel_status_t'); ?></span>
					<?php if ( get_option( 'cat-status-' . $term->term_id ) ) { ?>
						<?php echo get_option( 'cat-status-' . $term->term_id ); ?>
					<?php } else { ?>
						<?php _e( '暂无', 'begin' ); ?>
					<?php } ?>
				</div>
			<?php } ?>

			<?php if ( be_get_option( 'novel_views_t' ) ) { ?>
				<div class="novel-views">
					<span><?php echo be_get_option('novel_views_t'); ?></span>
					<?php
						$args = array(
							'category' => get_query_var( 'cat' ),
							'posts_per_page' => -1 
						);
						$posts = get_posts( $args );
						$views = array();
						$total_views = 0;
						foreach ( $posts as $post ) {
							$views_count = (int) get_post_meta( $post->ID, 'views', true );
							$total_views += $views_count;
						}
						echo $total_views;
					?>
				</div>
			<?php } ?>

			<div class="novel-date">
				<span><?php _e( '发表', 'begin' ); ?></span>
				<?php
					$latest_post = get_posts( array(
						'numberposts' => 1,
						'order'       => 'ASC',
						'category'    => get_query_var( 'cat' )
					));
					if ( $latest_post ) {
						echo get_the_time('Y-m-d', $latest_post[0]);
					}
				?>
			</div>
			<div class="novel-time">
				<span><?php _e( '更新', 'begin' ); ?></span>
				<?php
					$latest_post = get_posts( array(
						'numberposts' => 1,
						'category'    => get_query_var( 'cat' )
					));
					if ( $latest_post ) {
						echo get_the_time('Y-m-d', $latest_post[0]);
					}
				?>
			</div>

			<div class="novel-read-btn-box">
				<?php
					$args = array(
						'category' => get_query_var( 'cat' ),
						'posts_per_page' => 1,
						'order' => 'ASC',
						'orderby' => 'date'
					);
					$posts = get_posts( $args );
					if ( $posts ) {
						echo '<a class="novel-read-btn novel-btn-detail" href="' . get_permalink( $post->ID ) . '" title="' . get_the_title() . '" rel="bookmark">' . sprintf(__( '开始阅读', 'begin' )) . '</a>';
					}
					echo '<a class="novel-read-btn continue-reading no-record" href="#" title="" rel="bookmark">' . sprintf(__( '继续阅读', 'begin' )) . '</a>';
				?>
			</div>
			<div class="clear"></div>
			<div class="novel-read-mark continue-reading fd">
				<span class="dashicons dashicons-book"></span>
				<span><?php _e( '书签', 'begin' ); ?>：</span>
				<a href="#" class="read-title">暂无</a>
			</div>
		</div>

		<div class="tab-down-wrap">
			<div class="tab-down-nav">
				<div class="tab-down-item active"><?php _e( '作品简介', 'begin' ); ?></div>
				<div class="tab-down-item"><?php _e( '章节目录', 'begin' ); ?></div>
			</div>
			<div class="clear"></div>

			<div class="tab-down-content">
				<div class="tab-content-item novel-content-item show">
					<?php
						$args = array(
							'category'        => get_query_var( 'cat' ),
							'posts_per_page'  => 1,
							'order'           => 'ASC',
							'orderby'         => 'date'
						);
						$posts = get_posts( $args );

						$category = get_category( $args['category'] );
						$term_id = $category->term_id;

						if ( get_option( 'cat-message-' . $term_id ) ) {
							echo wpautop( get_option( 'cat-message-' . $term_id ) );
						} else {
							if ( category_description() ) {
								echo the_archive_description();
							} else {
								echo '为分类添加描述或附加信息';
							}
						}
					?>

					<div class="novel-new-digest">
						<?php
							$args = array(
								'showposts' => '1', 
								'tax_query' => array(
									array(
										'taxonomy' => 'category',
										'terms' => get_query_var( 'cat' )
									),
								)
							);
						?>
						<?php $be_query = new WP_Query( $args ); while ( $be_query->have_posts() ) : $be_query->the_post(); ?>
						<?php the_title( sprintf( '<h2 class="novel-new-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a><span class="tab-novel-new">'. sprintf(__( '最新', 'begin' )) . '</span></h2>' ); ?>
						<?php 
							$content = get_the_content();
							$content = strip_shortcodes( $content );
							if ( get_bloginfo( 'language' ) === 'en-US' ) {
								echo begin_strimwidth( strip_tags( $content), 0, '280', '...' );
							} else {
								echo wp_trim_words( $content, '280', '...' );
							}
						?>

						<?php endwhile; ?>
						<?php wp_reset_postdata(); ?>
					</div>
					<div class="clear"></div>
				</div>

				<div class="tab-content-item tab-content-license">
					<?php
						$img = get_posts( array(
							'category'        => get_query_var( 'cat' ),
							'posts_per_page'  => 1000,
							'order'           => 'ASC',
							'orderby'         => 'date'
						) );
					?>
					<?php foreach ( $img as $post ) : setup_postdata( $post ); ?>
					<article class="novel-list-main" <?php aos_a(); ?>>
						<?php the_title( sprintf( '<h2 class="novel-list-title"><a class="get-icon" href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
					</article>
					<?php endforeach; ?>
					<?php wp_reset_postdata(); ?>
					<div class="clear"></div>
				</div>
			</div>
		</div>
	</main>
	<?php logic_notice(); ?>
	<div class="clear"></div>
</section>

<?php $category = get_queried_object(); if ( $category->parent != 0 ) { ?>
<div class="related-novel-cover all-novel">
	<?php
		$category = get_queried_object();
		$terms = get_terms( 
			array(
				'taxonomy'   => 'category',
				'hide_empty' => false,
				'child_of'   => $category->parent,
			)
		);

		$current_cat = get_queried_object();

		$terms = array_filter(
		$terms,
			function( $term ) use ( $current_cat ) {
				return $term->term_id !== $current_cat->term_id;
		} );

		shuffle( $terms );
		$random_terms = array_slice( $terms, 0, 6 );
		foreach ( $random_terms as $term ) {
	?>
		<div class="cms-novel-main">
			<div class="boxs1">
				<div class="cms-novel-box" <?php aos_a(); ?>>
					<div class="cms-novel-cove-img-box">
						<div class="cms-novel-cove-img">
							<a class="thumbs-back sc" href="<?php echo esc_url( get_term_link( $term ) ); ?>" rel="external nofollow">
								<div class="novel-cove-img" data-src="<?php echo cat_cover_url( $term->term_id ); ?>"></div>
							</a>
							<?php if ( be_get_option( 'cms_novel_mark' ) ) { ?>
								<div class="special-mark bz fd"><?php echo be_get_option( 'cms_novel_mark' ); ?></div>
							<?php } ?>
						</div>
					</div>

					<a href="<?php echo esc_url( get_term_link( $term ) ); ?>" rel="bookmark">
						<div class="novel-cover-des">
							<h4 class="cat-novel-title"><?php echo $term->name; ?></h4>
						
							<?php if ( be_get_option( 'cms_novel_author' ) ) { ?>
								<div class="cat-novel-author">
									<?php if ( be_get_option( 'cms_novel_author' ) ) { ?>
										<?php if ( get_option( 'cat-author-' . $term->term_id ) ) { ?>
											<span><?php echo be_get_option('novel_author_t'); ?></span>
											<?php echo get_option( 'cat-author-' . $term->term_id ); ?>
										<?php } ?>
									<?php } ?>
								</div>
							<?php } ?>

							<div class="cms-novel-des">
								<?php 
									$description = category_description( $term->term_id );
									echo mb_strimwidth( $description, 0, 62, '...' ); 
								?>
							</div>
						</div>
					</a>
					<div class="clear"></div>
				</div>
			</div>
		</div>
	<?php } ?>
	<div class="clear"></div>
</div>
<?php } ?>
<?php get_footer(); ?>