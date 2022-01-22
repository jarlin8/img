<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( is_single() ) : ?>
<article id="post-<?php the_ID(); ?>" <?php aos_a(); ?> <?php post_class('ms bk'); ?>>
<?php else : ?>
<?php if (zm_get_option('post_no_margin')) { ?>
<article id="post-<?php the_ID(); ?>" <?php aos_a(); ?> <?php post_class('post ms bk doclose scl'); ?>>
<?php } else { ?>
<article id="post-<?php the_ID(); ?>" <?php aos_a(); ?> <?php post_class('post ms bk scl'); ?>>
<?php } ?>
<?php endif; ?>

	<?php header_title(); ?>
		<?php if ( is_single() ) : ?>
			<?php if ( get_post_meta($post->ID, 'header_img', true) ) { ?>
				<div class="entry-title-clear"></div>
			<?php } else { ?>
				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
			<?php } ?>
		<?php else : ?>
			<span class="title-l"></span>
			<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
		<?php endif; ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php if ( ! is_single() ) : ?>
			<figure class="content-image">
				<?php format_image_thumbnail(); ?>
				<span class="post-format fd"><i class="be be-picture"></i></span>
				<div class="clear"></div>
			</figure>
			<?php if ( has_excerpt('') || word_num() > 0 ) { ?>
				<div class="archive-content archive-content-image">
					<?php begin_trim_words(); ?>
				</div>
			<?php } ?>
			<div class="clear"></div>
			<span class="entry-meta-no lbm"><?php begin_format_meta(); ?><span class="img-number"><?php echo get_post_images_number().' ' ?> <?php _e( '张图片', 'begin' ); ?></span></span>

		<?php else : ?>

			<?php if ( ! get_post_meta($post->ID, 'header_img', true) ) : ?>
			<?php if (zm_get_option('meta_b')) {
				begin_single_meta();
			} else {
				begin_entry_meta();
			} ?>
			<?php endif; ?>

			<?php if (zm_get_option('all_more') && !get_post_meta($post->ID, 'not_more', true)) { ?>
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
	</div><!-- .entry-content -->

</article><!-- #post -->

<?php be_tags(); ?>