<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * category Template: 标题列表
 */
get_header(); ?>

<section id="category-list" class="content-area category-list">
	<main id="main" class="be-main site-main domargin" role="main">
		<?php get_template_part( 'template/cat-top' ); ?>
		<?php be_exclude_child_cats(); ?>

		<?php if ( have_posts() ) : ?>

		<?php while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" class="post-item-list post doclose scl" <?php aos_a(); ?>>
				<span class="archive-list-inf">
					<time datetime="<?php echo get_the_date('Y-m-d'); ?> <?php echo get_the_time('H:i:s'); ?>">
						<?php if ( zm_get_option( 'cat_layout_date_time' ) ) { ?>
							<?php the_time( 'm月d日' ) ?> <span><?php echo get_the_time('H:i'); ?></span>
						<?php } else { ?>
							<?php the_time( 'm/d' ) ?>
						<?php } ?>
					</time>
				</span>
				<?php the_title( sprintf( '<h2 class="entry-title"><a class="srm" href="%s" rel="bookmark" ' . goal() . '>' . t_mark(), esc_url( get_permalink() ) ), '</a></h2>' ); ?>
			</article>
		<?php endwhile; ?>

		<?php else : ?>
			<?php get_template_part( 'template/content', 'none' ); ?>

		<?php endif; ?>

	</main>

	<div class="pagenav-clear"><?php begin_pagenav(); ?></div>

</section>

<?php get_footer(); ?>