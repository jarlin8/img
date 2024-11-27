<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
get_header(); ?>
<div id="primary" class="content-area<?php if ( ! is_active_sidebar( 'qa-sidebar' ) ) { ?> no-sidebar<?php } ?>">
	<main id="main" class="be-main site-main<?php if (get_post_meta(get_the_ID(), 'sub_section', true) ) { ?> sub-h<?php } ?>" role="main">

		<?php while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" class="post-item post ms">
				<header class="entry-header">
					<?php the_title( '<h1 class="entry-title">','</h1>' ); ?>
				</header>
				<div class="entry-content">
					<div class="be-question-meta">
						<?php
							ap_question_metas();
							if ( zm_get_option( 'font_add' ) ) {
								echo '<span class="fontadd"><i class="dashicons dashicons-editor-textcolor"></i><i class="xico dashicons dashicons-plus-alt2"></i></span>';
							}
							echo '<span class="s-hide' . cur() . '" title="' . sprintf(__( '侧边栏', 'begin' ) ) . '"><span class="off-side"></span></span>';
						?>
					</div>
					<div class="single-content">
						<?php begin_abstract(); ?>
						<?php get_template_part( 'ad/ads', 'single' ); ?>
						<?php the_content(); ?>
					</div>
					<?php dynamic_sidebar( 'single-foot' ); ?>
					<?php logic_notice(); ?>

					<div class="clear"></div>
				</div>
			</article>
			<?php if ( is_active_sidebar( 'qa-footer' ) ) { ?>
				<div id="single-widget" class="betip single-widget-<?php echo zm_get_option( 'single_e_f' ); ?>">
					<div class="single-wt" <?php aos_a(); ?>>
						<?php dynamic_sidebar( 'qa-footer' ); ?>
					</div>
					<div class="clear"></div>
				</div>
			<?php } ?>
		<?php endwhile; ?>
	</main>
</div>

<?php if ( is_active_sidebar( 'qa-sidebar' ) ) { ?>
<div id="sidebar" class="widget-area all-sidebar">
	<div class="cms-widget" <?php aos_a(); ?>>
		<?php dynamic_sidebar( 'qa-sidebar' ); ?>
	</div>
</div>
<?php } ?>

<div class="clear"></div>
<?php get_footer(); ?>