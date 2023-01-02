<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( is_single() ) : ?>
<article id="post-<?php the_ID(); ?>" <?php aos_a(); ?> <?php post_class('ms bk'); ?>>
<?php else : ?>
<?php if ( zm_get_option( 'post_no_margin' ) ) { ?>
<article id="post-<?php the_ID(); ?>" <?php aos_a(); ?> <?php post_class('post ms bk doclose scl'); ?>>
<?php } else { ?>
<article id="post-<?php the_ID(); ?>" <?php aos_a(); ?> <?php post_class('post ms bk scl'); ?>>
<?php } ?>
<?php endif; ?>

	<?php header_title(); ?>
		<?php if ( is_single() ) : ?>
			<?php the_title( '<h1 class="entry-title">', t_mark() . '</h1>' ); ?>
		<?php else : ?>
			<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">' . t_mark(), esc_url( get_permalink() ) ), '</a></h2>' ); ?>
		<?php endif; ?>
	</header>

	<div class="entry-content">
		<?php if ( ! is_single() ) : ?>
			<div class="archive-content">
				<?php begin_trim_words(); ?>
			</div>
			<?php title_l(); ?>
			<?php get_template_part( 'template/new' ); ?>
			<div class="clear"></div>
			<span class="entry-meta lbm<?php vr(); ?>">
				<?php begin_format_meta(); ?>
			</span>
		<?php else : ?>

			<?php if ( ! get_post_meta(get_the_ID(), 'header_img', true) ) : ?>
			<?php if (zm_get_option('meta_b')) {
				begin_single_meta();
			} else {
				begin_entry_meta();
			} ?>
			<?php endif; ?>

			<?php if (zm_get_option('all_more') && !get_post_meta(get_the_ID(), 'not_more', true)) { ?>
				<div class="single-content<?php if (word_num() > 800) { ?> more-content more-area<?php } ?>">
			<?php } else { ?>
				<div class="single-content">
			<?php } ?>
				<?php begin_abstract(); ?>
				<?php get_template_part('ad/ads', 'single'); ?>
				<?php the_content(); ?>
			</div>
			<?php content_support(); ?>

		<?php endif; ?>
		<div class="clear"></div>
	</div>

	<?php if ( ! is_single() ) : ?>
		<?php entry_more(); ?>
	<?php endif; ?>
</article>

<?php be_tags(); ?>