<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( is_single() ) : ?>
<article id="post-<?php the_ID(); ?>" class="post-item post ms">
<?php else : ?>
<?php boxsstart(); ?>
<article id="post-<?php the_ID(); ?>" class="post-item-list post format-aside post-default ms<?php if ( zm_get_option( 'post_no_margin' ) ) { ?> doclose scl<?php } ?>" <?php aos_a(); ?>>
<?php endif; ?>
	<?php if ( ! is_single() ) { ?><?php get_template_part( 'template/new' ); ?><?php } ?>
	<?php if ( ! is_single() ) { ?><div class="post-area"><?php } ?>
		<?php header_title(); ?>
			<?php if ( is_single() ) : ?>
				<?php if ( ! get_post_meta( get_the_ID(), 'img_title', true ) && ! get_post_meta( get_the_ID(), 'show_title', true ) ) { ?>
					<?php the_title( '<h1 class="entry-title">', t_mark() . '</h1>' ); ?>
				<?php } ?>
			<?php else : ?>
				<?php if ( is_search() ) { ?>
					<?php the_title( sprintf( '<h2 class="entry-title">' . be_sticky() . cat_sticky() . '<a href="%s" target="_blank" rel="bookmark">' . t_mark(), esc_url( get_permalink() ) ), '</a></h2>' ); ?>
				<?php } else { ?>
					<?php the_title( sprintf( '<h2 class="entry-title">' . be_sticky() . cat_sticky() . '<a href="%s" rel="bookmark" ' . goal() . '>' . t_mark(), esc_url( get_permalink() ) ), '</a></h2>' ); ?>
				<?php } ?>
			<?php endif; ?>
		</header>

		<div class="entry-content">
			<?php if ( ! is_single() ) : ?>
				<div class="archive-content">
					<?php begin_trim_words(); ?>
				</div>
				<?php title_l(); ?>
				<div class="clear"></div>
				<span class="entry-meta lbm<?php vr(); ?>">
					<?php begin_format_meta(); ?>
				</span>
		</div>
		<?php else : ?>

			<?php if ( ( ! get_post_meta( get_the_ID(), 'header_img', true ) || get_post_meta( get_the_ID(), 'no_show_title', true ) ) && ( ! get_post_meta( get_the_ID(), 'header_bg', true ) ||  get_post_meta( get_the_ID(), 'no_img_title', true ) ) ) { ?>
				<?php begin_single_meta(); ?>
			<?php } ?>

			<?php if ( zm_get_option( 'all_more' ) && ! get_post_meta( get_the_ID(), 'not_more', true ) ) { ?>
				<div class="single-content<?php if ( word_num() > 800 ) { ?> more-content more-area<?php } ?>">
			<?php } else { ?>
				<div class="single-content">
			<?php } ?>
				<?php begin_abstract(); ?>
				<?php get_template_part( 'ad/ads', 'single' ); ?>
				<?php the_content(); ?>
			</div>

			<?php dynamic_sidebar( 'single-foot' ); ?>

			<?php logic_notice(); ?>
			<?php content_support(); ?>

		<?php endif; ?>
		<div class="clear"></div>
	</div>

	<?php if ( ! is_single() ) : ?>
		<?php entry_more(); ?>
	<?php endif; ?>
</article>
<?php boxsend(); ?>
<?php be_tags(); ?>