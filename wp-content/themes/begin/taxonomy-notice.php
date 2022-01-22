<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
get_header(); ?>

<section id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
		<?php if (!zm_get_option('notice_m') || (zm_get_option('notice_m') == 'notice_s')) { ?>
			<article id="post-<?php the_ID(); ?>" <?php aos_a(); ?> <?php post_class('post ms bk shuo-site scl'); ?>>
				<div class="entry-content shuo-entry">
					<div class="shuo-content">
						<?php the_content(); ?>
					</div>
					<div class="shuo-meta">
						<div class="today-date ms">
							<div class="today-m"><?php the_time( 'm月' ); ?></div>
							<div class="today-d da"><?php the_time( 'd' ); ?></div>
						</div>
					</div>
					<div class="entry-meta-no">
						<span class="meta-author-avatar shuo-avatar">
							<?php 
								if (zm_get_option('cache_avatar')) {
									echo begin_avatar( get_the_author_meta('email'), '96', '', get_the_author() );
								} else {
									echo get_avatar( get_the_author_meta('email'), '96', '', get_the_author() );
								}
							?>
						</span>
						<span class="shuo-inf shuo-author"><?php the_author(); ?></span>
						<span class="shuo-inf shuo-time"><?php echo get_the_time('H:i:s'); ?></span>
					</div>
				</div>
			</article>
		<?php } ?>

		<?php if (zm_get_option('notice_m') == 'notice_d') { ?>
			<article id="post-<?php the_ID(); ?>" <?php aos_a(); ?> <?php post_class('post ms bk scl'); ?>>
				<?php 
					$content = $post->post_content;
					preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
					$n = count($strResult[1]);
					if($n > 0) { ?>
					<figure class="thumbnail">
						<?php zm_thumbnail(); ?>
						<?php if (zm_get_option('no_thumbnail_cat')) { ?><span class="cat cat-roll"><?php } else { ?><span class="cat"><?php } ?><?php echo get_the_term_list( $post->ID,  'notice', '' ); ?></span>
					</figure>
				<?php } ?>

				<?php header_title(); ?>
					<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
				</header><!-- .entry-header -->

				<div class="entry-content">
						<div class="archive-content">
							<?php begin_trim_words(); ?>
						</div>
						<div class="clear"></div>
						<span class="title-l"></span>

							<?php 
								$content = $post->post_content;
								preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
								$n = count($strResult[1]);
								if( $n > 0 || get_post_meta($post->ID, 'thumbnail', true) ) : ?>
								<span class="entry-meta lbm">
									<?php begin_entry_meta(); ?>
								</span>
							<?php else : ?>
								<span class="entry-meta-no lbm">
									<?php begin_format_meta(); ?>
								</span>
							<?php endif; ?>

					<div class="clear"></div>
				</div>

				<?php if ( ! is_single() ) : ?>
					<?php entry_more(); ?>
				<?php endif; ?>
			</article>
		<?php } ?>

		<?php endwhile; ?>

		<?php else : ?>
			<article class="post" <?php aos_a(); ?>>
				<div class="archive-content">
					<p><?php _e( '暂无文章', 'begin' ); ?></p>
				</div>
				<div class="clear"></div>
			</article>
		<?php endif; ?>

	</main><!-- .site-main -->

	<div class="pagenav-clear"><?php begin_pagenav(); ?></div>

</section><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>