<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * category Template: 网格布局
 */
get_header(); ?>

<section id="primary" class="content-area cms-news-grid-container cat-square">
	<main id="main" class="be-main site-main" role="main">
		<?php if ( ( zm_get_option( 'no_child' ) ) && is_category() ) { ?>
			<?php 
				$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
				query_posts( array( 'category__in' => array( get_query_var( 'cat' ) ), 'paged' => $paged ) );
			?>
		<?php } ?>

		<?php if ( zm_get_option( 'order_btu' ) ) { ?><?php be_order(); ?><?php } ?>

		<?php while ( have_posts() ) : the_post(); ?>

		<article id="post-<?php the_ID(); ?>" <?php aos_a(); ?> <?php post_class('glc ms bk scl'); ?>>
			<?php get_template_part( 'template/new' ); ?>

			<figure class="thumbnail">
				<?php zm_thumbnail(); ?>
			</figure>

			<header class="entry-header">
				<?php the_title( sprintf( '<h2 class="entry-title over"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
			</header>

			<div class="entry-content">
				<div class="archive-content">
					<?php if (has_excerpt('')){
							echo wp_trim_words( get_the_excerpt(), 30, '...' );
						} else {
							$content = get_the_content();
							$content = wp_strip_all_tags(str_replace(array('[',']'),array('<','>'),$content));
							echo wp_trim_words( $content, 35, '...' );
				        }
					?>
				</div>
				<span class="entry-meta lbm">
					<?php begin_entry_meta(); ?>
				</span>
				<div class="clear"></div>
			</div>
		</article>
		<?php endwhile; ?>
		<div class="square-clear"><?php begin_pagenav(); ?></div>
	</main>

</section>
<?php get_sidebar(); ?>
<?php get_footer(); ?>