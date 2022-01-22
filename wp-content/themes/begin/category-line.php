<?php
if ( ! defined( 'ABSPATH' ) ) exit;
get_header(); ?>
<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/line.css" />
<section id="timeline" class="container">
	<main id="main" class="site-main" role="main">
		<?php if ( ( zm_get_option( 'no_child' ) ) && is_category() ) { ?>
			<?php 
				$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
				query_posts( array( 'category__in' => array( get_query_var( 'cat' ) ), 'paged' => $paged ) );
			?>
		<?php } ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php aos_a(); ?> <?php post_class('timeline-block scl'); ?>>
				<div class="timeline-time da bk">
					<?php the_time( 'd' ) ?>
				</div>
				<div class="timeline-content sup da bk">
					<div class="jt"></div>

					<?php if (zm_get_option('no_rand_img')) { ?>
							<?php 
								$content = $post->post_content;
								preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
								$n = count($strResult[1]);
								if($n > 0) { ?>
								<figure class="thumbnail timeline-thum">
									<?php zm_thumbnail(); ?>
								</figure>
							<?php } ?>

					<?php } else { ?>
						<figure class="thumbnail timeline-thum">
							<?php zm_thumbnail(); ?>
						</figure>
					<?php } ?>

					<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
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