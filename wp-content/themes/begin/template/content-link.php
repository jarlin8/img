<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( is_single() ) : ?>
<article id="post-<?php the_ID(); ?>" class="post-item post ms">
<?php else : ?>
<?php boxsstart(); ?>
<article id="post-<?php the_ID(); ?>" class="post-item-list post format-link post-default ms<?php if ( zm_get_option( 'post_no_margin' ) ) { ?> doclose scl<?php } ?>" <?php aos_a(); ?>>
<?php endif; ?>
	<?php if ( ! is_single() ) { ?><?php get_template_part( 'template/new' ); ?><?php } ?>
	<?php if ( ! is_single() ) : ?>
		<?php $direct = get_post_meta(get_the_ID(), 'direct', true); ?>
		<figure class="thumbnail">
			<?php if ( get_post_meta(get_the_ID(), 'direct', true) ) { ?>
				<?php echo zm_thumbnail_link(); ?>
			<?php } else { ?>
				<?php echo zm_thumbnail(); ?>
			<?php } ?>
			<span class="post-format fd">
				<?php if ( get_post_meta(get_the_ID(), 'direct', true) ) { ?>
					<a href="<?php the_permalink(); ?>" target="_blank"><i class="<?php echo zm_get_option( 'format_link_ico' ); ?>"></i></a>
				<?php } else { ?>
					<i class="<?php echo zm_get_option( 'format_link_ico' ); ?>"></i>
				<?php } ?>
			</span>
		</figure>
	<?php endif; ?>

	<?php if ( ! is_single() ) { ?><div class="post-area"><?php } ?>
		<?php header_title(); ?>
			<?php if ( is_single() ) : ?>
				<?php if ( ! get_post_meta( get_the_ID(), 'img_title', true ) && ! get_post_meta( get_the_ID(), 'show_title', true ) ) { ?>
					<?php the_title( '<h1 class="entry-title">', t_mark() . '</h1>' ); ?>
				<?php } ?>
			<?php else : ?>
				<?php if ( get_post_meta( get_the_ID(), 'go_direct', true ) ) { ?>
					<h2 class="entry-title"><a href="<?php echo $direct; ?>" target="_blank" rel="external nofollow"><?php t_mark(); ?><?php the_title(); ?></a></h2>
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
				<div class="clear"></div>
				<?php title_l(); ?>
				<span class="entry-meta lbm<?php vr(); ?>">
					<?php if ( get_post_meta(get_the_ID(), 'link_inf', true) ) { ?>
					<?php $link_inf = get_post_meta(get_the_ID(), 'link_inf', true); ?>
					<span class="date"><?php time_ago( $time_type ='post' ); ?>&nbsp;</span>
					<span class="link-price"><?php echo $link_inf; ?></span>
					<?php } else { ?>
						<?php begin_entry_meta(); ?>
					<?php } ?>
				</span>
		</div>

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
		<div class="clear"></div>
	</div>

	<?php if ( ! is_single() ) : ?>
		<?php if ( get_post_meta(get_the_ID(), 'direct', true) ) { ?>
		<?php $direct = get_post_meta(get_the_ID(), 'direct', true); ?>
			<?php if ( get_post_meta(get_the_ID(), 'direct_btn', true) ) { ?>
			<?php $direct_btn = get_post_meta(get_the_ID(), 'direct_btn', true); ?>
				<?php if (zm_get_option('more_hide')) { ?><span class="entry-more more-roll ease"><?php } else { ?><span class="entry-more"><?php } ?><a href="<?php echo $direct; ?>" target="_blank" rel="external nofollow"><?php echo $direct_btn; ?></a></span>
			<?php } else { ?>
				<?php if ( zm_get_option( 'direct_w' ) ) { ?><?php if (zm_get_option('more_hide')) { ?><span class="entry-more more-roll ease"><?php } else { ?><span class="entry-more"><?php } ?><a href="<?php echo $direct; ?>" target="_blank" rel="external nofollow"><?php echo zm_get_option('direct_w'); ?></a></span><?php } ?>
			<?php } ?>
		<?php } else { ?>
			<?php if ( zm_get_option( 'more_w' ) ) { ?><?php if (zm_get_option('more_hide')) { ?><span class="entry-more more-roll ease"><?php } else { ?><span class="entry-more"><?php } ?><a href="<?php the_permalink(); ?>" rel="external nofollow"><?php echo zm_get_option('more_w'); ?></a></span><?php } ?>
		<?php } ?>
	<?php endif; ?>
</article>
<?php boxsend(); ?>
<?php be_tags(); ?>