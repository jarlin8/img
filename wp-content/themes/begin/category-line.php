<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * category Template: 时间轴
 */
get_header(); ?>
<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/line.css" />
<section id="timeline" class="container">
	<main id="main" class="be-main site-main" role="main">
		<?php be_exclude_child_cats(); ?>

		<?php while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" class="post-item-list post timeline-block scl" <?php aos_a(); ?>>
				<div class="timeline-time">
					<?php the_time( 'd' ) ?>
				</div>
				<div class="timeline-content boxs1">
					<div class="jt"></div>

					<?php if (zm_get_option('no_rand_img')) { ?>
							<?php 
								$content = $post->post_content;
								preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
								$n = count($strResult[1]);
								if ($n > 0) { ?>
								<figure class="thumbnail timeline-thum">
									<?php echo zm_thumbnail(); ?>
								</figure>
							<?php } ?>

					<?php } else { ?>
						<figure class="thumbnail timeline-thum">
							<?php echo zm_thumbnail(); ?>
						</figure>
					<?php } ?>

					<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
					<?php begin_trim_words(); ?>
					<span class="date"><?php the_time( 'Y年m月' ); ?></span>
					<div class="clear"></div>
					<div class="timeline-meta lbm"><?php timeline_meta(); ?></div>
				</div>
			</article>
		<?php endwhile; ?>
	</main>
	<div class="other-nav"><?php begin_pagenav(); ?></div>
<div class="clear"></div>
</section>
<?php get_footer(); ?>