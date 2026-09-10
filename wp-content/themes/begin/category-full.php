<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * category Template: 通长缩略图
 */
get_header(); ?>

	<section id="primary" class="content-area category-full">
		<main id="main" class="be-main site-main" role="main">

			<?php be_exclude_child_cats(); ?>

			<?php while ( have_posts() ) : the_post(); ?>
			<div class="boxs1">
				<article id="post-<?php the_ID(); ?>" class="post-item-list post full-text ms scl" <?php aos_a(); ?>>
					<div class="full-thumbnail">
						<div class="full-cat"><?php zm_category(); ?></div>
						<?php echo zm_full_thumbnail(); ?>
						<header class="full-header">
							<div class="clear"></div>
							<?php the_title( sprintf( '<h2 class="entry-title-img"><a href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
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
					<div class="full-meta">
						<div class="full-entry-meta lbm">
							<?php begin_entry_meta(); ?>
							<span class="full-entry-more"><a href="<?php the_permalink(); ?>" rel="bookmark" <?php echo goal(); ?>><i class="be be-more"></i></a></span>
						</div>
					</div>
					<div class="clear"></div>
				</article>
			</div>
			<div class="tg-full" <?php aos_a(); ?>><?php get_template_part('ad/ads', 'archive'); ?></div>

			<?php endwhile; ?>

		</main>

		<?php begin_pagenav(); ?>

	</section>

<?php get_sidebar(); ?>
<?php get_footer(); ?>