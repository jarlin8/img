<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( is_single() ) : ?>
<article id="post-<?php the_ID(); ?>" class="post-item post ms">
<?php else : ?>
<?php boxsstart(); ?>
<article id="post-<?php the_ID(); ?>" class="post-item-list post format-image ms<?php if ( zm_get_option( 'post_no_margin' ) ) { ?> doclose scl<?php } ?>" <?php aos_a(); ?>>
<?php endif; ?>

	<?php header_title(); ?>
		<?php if ( is_single() ) : ?>
			<?php if ( ! get_post_meta( get_the_ID(), 'img_title', true ) && ! get_post_meta( get_the_ID(), 'show_title', true ) ) { ?>
				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
			<?php } ?>
		<?php else : ?>
			<?php title_l(); ?>
			<?php the_title( sprintf( '<h2 class="entry-title">' . be_sticky() . cat_sticky() . '<a href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
		<?php endif; ?>
	</header>

	<div class="entry-content">
		<?php if ( ! is_single() ) : ?>
			<figure class="content-image">
				<?php echo format_image_thumbnail(); ?>
				<span class="post-format fd"><i class="<?php echo zm_get_option( 'format_picture_ico' ); ?>"></i></span>
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

			<?php if ( ( ! get_post_meta( get_the_ID(), 'header_img', true ) || get_post_meta( get_the_ID(), 'no_show_title', true ) ) && ( ! get_post_meta( get_the_ID(), 'header_bg', true ) ||  get_post_meta( get_the_ID(), 'no_img_title', true ) ) ) { ?>
				<?php begin_single_meta(); ?>
			<?php } ?>

			<?php if (zm_get_option('all_more') && !get_post_meta(get_the_ID(), 'not_more', true)) { ?>
				<div class="single-content<?php if (word_num() > 800) { ?> more-content more-area<?php } ?>">
			<?php } else { ?>
				<div class="single-content">
			<?php } ?>
				<?php begin_abstract(); ?>
				<?php get_template_part('ad/ads', 'single'); ?>
				<?php the_content(); ?>
			</div>

			<?php dynamic_sidebar( 'single-foot' ); ?>

			<?php logic_notice(); ?>
			<?php content_support(); ?>

		<?php endif; ?>
	</div>

</article>
<?php boxsend(); ?>
<?php be_tags(); ?>