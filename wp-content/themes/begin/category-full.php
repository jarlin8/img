<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * category Template: 通长缩略图
 */
get_header(); ?>

	<section id="primary" class="content-area category-full">
		<main id="main" class="be-main site-main" role="main">
			<?php if ( ( zm_get_option( 'no_child' ) ) && is_category() ) { ?>
				<?php 
					$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
					query_posts( array( 'category__in' => array( get_query_var( 'cat' ) ), 'paged' => $paged ) );
				?>
			<?php } ?>

			<?php if ( zm_get_option( 'order_btu' ) ) { ?><?php be_order(); ?><?php } ?>

			<?php while ( have_posts() ) : the_post(); ?>

			<article id="post-<?php the_ID(); ?>" <?php aos_a(); ?> <?php post_class('full-text ms bk scl'); ?>>
				<div class="full-thumbnail">
					<div class="full-cat"><?php zm_category(); ?></div>
					<?php zm_full_thumbnail(); ?>
					<header class="full-header bgt">
						<div class="clear"></div>
						<?php the_title( sprintf( '<h2 class="entry-title-img bgt"><a class="bgt hz" href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
					</header>
				</div>
	
				<div class="clear"></div>
				<div class="full-archive-content">
					<?php 
						if (has_excerpt('')){
							echo wp_trim_words( get_the_excerpt(), 88, '...' );
						} else {
							$content = get_the_content();
							$content = wp_strip_all_tags(str_replace(array('[',']'),array('<','>'),$content));
							echo wp_trim_words( $content, 88, '...' );
						}
					?>
				</div>
				<div class="full-meta bkx bgt dah">
					<div class="full-entry-meta bgt lbm">
						<?php begin_entry_meta(); ?>
						<span class="full-entry-more"><a href="<?php the_permalink(); ?>" rel="bookmark"><i class="be be-more"></i></a></span>
					</div>
				</div>
				<div class="clear"></div>
			</article>

			<div class="tg-full" <?php aos_a(); ?>><?php get_template_part('ad/ads', 'archive'); ?></div>

			<?php endwhile; ?>

		</main>

		<?php begin_pagenav(); ?>

	</section>

<?php get_sidebar(); ?>
<?php get_footer(); ?>