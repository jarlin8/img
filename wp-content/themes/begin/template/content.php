<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( is_single() ) : ?>
<article id="post-<?php the_ID(); ?>" <?php aos_a(); ?> <?php post_class( 'ms bk' ); ?>>
<?php else : ?>
<?php if ( zm_get_option( 'post_no_margin' ) ) { ?>
<article id="post-<?php the_ID(); ?>" <?php aos_a(); ?> <?php post_class( 'ms bk doclose scl' ); ?>>
<?php } else { ?>
<article id="post-<?php the_ID(); ?>" <?php aos_a(); ?> <?php post_class( 'ms bk scl' ); ?>>
<?php } ?>
<?php endif; ?>
	<?php if ( ! is_single() ) : ?>

		<?php if ( zm_get_option( 'no_rand_img' ) ) { ?>
			<?php if ( get_post_meta( get_the_ID(), 'thumbnail', true ) ) { ?>
				<figure class="thumbnail">
					<?php zm_thumbnail(); ?>
					<?php if ( zm_get_option( 'no_thumbnail_cat' ) ) { ?><span class="cat cat-roll"><?php } else { ?><span class="cat"><?php } ?><?php zm_category(); ?></span>
				</figure>
			<?php } else { ?>
				<?php if ( nomig() > 0 ) { ?>
					<figure class="thumbnail">
						<?php zm_thumbnail(); ?>
						<?php if ( zm_get_option( 'no_thumbnail_cat' ) ) { ?><span class="cat cat-roll"><?php } else { ?><span class="cat"><?php } ?><?php zm_category(); ?></span>
					</figure>
				<?php } ?>
			<?php } ?>
		<?php } else { ?>
			<figure class="thumbnail">
				<?php zm_thumbnail(); ?>
				<?php if ( zm_get_option( 'no_thumbnail_cat' ) ) { ?><span class="cat cat-roll"><?php } else { ?><span class="cat"><?php } ?><?php zm_category(); ?></span>
			</figure>
		<?php } ?>

	<?php endif; ?>
	<?php header_title(); ?>
		<?php if ( is_single() ) : ?>
			<?php if ( get_post_meta( get_the_ID(), 'header_img', true ) || get_post_meta( get_the_ID(), 'header_bg', true ) ) { ?>
			<?php } else { ?>
				<?php the_title( '<h1 class="entry-title">', t_mark() . '</h1>' ); ?>
			<?php } ?>
		<?php else : ?>
			<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">' . t_mark(), esc_url( get_permalink() ) ), '</a></h2>' ); ?>
		<?php endif; ?>
	</header>

	<div class="entry-content">
		<?php if ( ! is_single() ) : ?>
			<div class="archive-content">
				<?php begin_trim_words(); ?>
			</div>
			<div class="clear"></div>
			<?php title_l(); ?>
			<?php get_template_part( 'template/new' ); ?>
			<?php if (zm_get_option('no_rand_img')) { ?>
				<?php if ( nomig() > 0 || get_post_meta( get_the_ID(), 'thumbnail', true ) ) : ?>
					<span class="entry-meta lbm<?php vr(); ?>">
						<?php begin_entry_meta(); ?>
					</span>
				<?php else : ?>
					<span class="entry-meta-no lbm<?php vr(); ?>">
						<?php begin_format_meta(); ?>
					</span>
				<?php endif; ?>
			<?php } else { ?>
				<span class="entry-meta lbm<?php vr(); ?>">
					<?php begin_entry_meta(); ?>
				</span>
			<?php } ?>

		<?php else : ?>

			<?php if ( ! get_post_meta( get_the_ID(), 'header_img', true ) && !get_post_meta( get_the_ID(), 'header_bg', true ) ) : ?>
			<?php if ( zm_get_option('meta_b') ) {
				begin_single_meta();
			} else {
				begin_entry_meta();
			} ?>
			<?php endif; ?>

			<?php if ( zm_get_option( 'all_more' ) && !get_post_meta( get_the_ID(), 'not_more', true ) ) { ?>
				<div class="single-content<?php if ( word_num() > 800 ) { ?> more-content more-area<?php } ?>">
			<?php } else { ?>
				<div class="single-content">
			<?php } ?>
				<?php begin_abstract(); ?>
				<?php get_template_part( 'ad/ads', 'single' ); ?>
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