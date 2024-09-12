<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * category Template: 标题列表
 */
get_header(); ?>

<section id="category-list" class="content-area category-list">
	<main id="main" class="be-main site-main domargin" role="main">
	<?php get_template_part( 'template/cat-top' ); ?>
		<?php if ( ( zm_get_option( 'no_child' ) ) && is_category() ) { ?>
			<?php 
				$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
				query_posts( array( 'category__in' => array( get_query_var( 'cat' ) ), 'paged' => $paged ) );
			?>
		<?php } ?>

		<?php if ( zm_get_option( 'order_btu' ) ) { ?><?php be_order(); ?><?php } ?>

		<?php if ( have_posts() ) : ?>

		<?php while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php aos_a(); ?> <?php post_class('post bk doclose scl'); ?>>
				<span class="archive-list-inf"><time datetime="<?php echo get_the_date('Y-m-d'); ?> <?php echo get_the_time('H:i:s'); ?>"><?php the_time( 'm/d' ) ?></time></span>
				<?php the_title( sprintf( '<h2 class="entry-title"><a class="srm" href="%s" rel="bookmark">' . t_mark(), esc_url( get_permalink() ) ), '</a></h2>' ); ?>
			</article>
		<?php endwhile; ?>

		<?php else : ?>
			<?php get_template_part( 'template/content', 'none' ); ?>

		<?php endif; ?>

	</main>

	<div class="pagenav-clear"><?php begin_pagenav(); ?></div>

</section>

<?php get_footer(); ?>