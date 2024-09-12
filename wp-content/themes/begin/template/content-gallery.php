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
			<?php if ( get_post_meta(get_the_ID(), 'header_img', true) ) { ?>
				<div class="entry-title-clear"></div>
			<?php } else { ?>
				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
			<?php } ?>
		<?php else : ?>
		<?php endif; ?>
	</header>

	<div class="entry-content">
		<?php if ( ! is_single() ) : ?>
			<div class="full-thumbnail">
				<?php zm_full_thumbnail(); ?>
				<header class="full-header bgt">
					<?php the_title( sprintf( '<h2 class="entry-title-img bgt"><a class="bgt hz" href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
				</header>
			</div>

			<div class="gallery-archive-content">
				<?php begin_trim_words(); ?>
			</div>

			<div class="clear"></div>
			<span class="entry-meta lbm">
				<?php begin_entry_meta(); ?>
				<span class="format-gallery-meta"><i class="be be-sort ri"></i><?php zm_category(); ?></span>
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
	</div>
</article>

<?php be_tags(); ?>