<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( is_single() ) : ?>
<article id="post-<?php the_ID(); ?>" class="post-item post ms">
<?php else : ?>
<article id="post-<?php the_ID(); ?>" class="post-item-list post format-quote post-default ms<?php if ( zm_get_option( 'post_no_margin' ) ) { ?> doclose scl<?php } ?>" <?php aos_a(); ?>>
<?php endif; ?>
	<?php if ( ! is_single() ) { ?><?php get_template_part( 'template/new' ); ?><?php } ?>
	<?php if ( ! is_single() ) : ?>
		<figure class="thumbnail">
			<?php echo zm_thumbnail(); ?>
			<span class="cat<?php if ( zm_get_option( 'no_thumbnail_cat' ) ) { ?> cat-roll<?php } ?><?php if ( zm_get_option( 'merge_cat' ) ) { ?> merge-cat<?php } ?>"><?php zm_category(); ?></span>
				<span class="post-format fd"><i class="<?php echo zm_get_option( 'format_software_ico' ); ?>" aria-hidden="true"></i></span>
		</figure>
	<?php endif; ?>

	<?php if ( ! is_single() ) { ?><div class="post-area"><?php } ?>
		<?php header_title(); ?>
			<?php if ( is_single() ) : ?>
				<?php if ( ! get_post_meta( get_the_ID(), 'img_title', true ) && ! get_post_meta( get_the_ID(), 'show_title', true ) ) { ?>
					<?php the_title( '<h1 class="entry-title">', t_mark() . '</h1>' ); ?>
				<?php } ?>
			<?php else : ?>
				<?php the_title( sprintf( '<h2 class="entry-title">' . be_sticky() . cat_sticky() . '<a href="%s" rel="bookmark" ' . goal() . '>' . t_mark(), esc_url( get_permalink() ) ), '</a></h2>' ); ?>
			<?php endif; ?>
		</header>

		<?php if ( is_single() ) : ?><?php begin_single_meta(); ?><?php endif; ?>

		<div class="entry-content">
			<?php if ( ! is_single() ) : ?>
				<div class="archive-content">
					<?php begin_trim_words(); ?>
				</div>
				<div class="clear"></div>
				<?php title_l(); ?>
				<span class="entry-meta lbm<?php vr(); ?>">
					<?php begin_entry_meta(); ?>
				</span>
		</div>
		<?php else : ?>
			<?php if (zm_get_option('all_more') && !get_post_meta(get_the_ID(), 'not_more', true)) { ?>
				<div class="single-content<?php if (word_num() > 800) { ?> more-content more-area<?php } ?>">
			<?php } else { ?>
				<div class="single-content">
			<?php } ?>
				<?php begin_abstract(); ?>

				<?php get_template_part('ad/ads', 'single'); ?>
				<div class="videos-content">
					<div class="video-img-box">
						<div class="video-img">
							<?php echo zm_thumbnail(); ?>
						</div>
					</div>
					<div class="format-videos-inf">
						<span class="category"><strong><?php _e( '所属分类', 'begin' ); ?>：</strong><?php the_category( '&nbsp;&nbsp;' ); ?></span>
						<span>
							<?php $file_os = get_post_meta(get_the_ID(), 'file_os', true); ?>
							<?php if ( get_post_meta( get_the_ID(), 'be_file_os', true ) && get_post_meta( get_the_ID(), 'file_os', true ) ) { ?>
								<strong><?php _e( '应用平台', 'begin' ); ?>：</strong>
							<?php } ?>
							<?php echo $file_os; ?>
						</span>
						<span>
							<?php $file_inf = get_post_meta(get_the_ID(), 'file_inf', true); ?>
							<?php if ( get_post_meta( get_the_ID(), 'be_file_inf', true ) && get_post_meta( get_the_ID(), 'file_inf', true ) ) { ?>
								<strong><?php _e( '资源版本', 'begin' ); ?>：</strong>
							<?php } ?>
							<?php echo $file_inf; ?>
						</span>
						<span class="date"><strong><?php _e( '最后更新', 'begin' ); ?>：</strong><?php the_modified_time('Y年n月j日 H:s'); ?></span>
					</div>
					<div class="clear"></div>
				</div>
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