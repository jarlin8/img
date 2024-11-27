<?php
/*
Template Name: 专题模板
*/
if ( ! defined( 'ABSPATH' ) ) exit;
get_header(); ?>
<?php if ( get_post_meta($post->ID, 'special_img', true) ) { ?>
	<?php special_single_content(); ?>
	<section id="picture" class="content-area grid-cat-<?php echo zm_get_option('img_f'); ?>">
		<main id="main" class="site-main" role="main">
			<?php
				$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;;
				$special = get_post_meta($post->ID, 'special');
				$args = array(
					'tag' => $special, 
				    'ignore_sticky_posts' => 0, 
					'paged' => $paged
				);
				query_posts( $args );
			?>
			<?php while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php aos_a(); ?> <?php post_class('picture'); ?>>
					<div class="picture-box sup ms bk">
						<figure class="picture-img">
							<?php if (zm_get_option('hide_box')) { ?>
								<a rel="bookmark" href="<?php echo esc_url( get_permalink() ); ?>"><div class="hide-box"></div></a>
								<a rel="bookmark" href="<?php echo esc_url( get_permalink() ); ?>"><div class="hide-excerpt"><?php if (has_excerpt('')){ echo wp_trim_words( get_the_excerpt(), 62, '...' ); } else { echo wp_trim_words( get_the_content(), 72, '...' ); } ?></div></a>
							<?php } ?>

							<?php if ( get_post_meta($post->ID, 'direct', true) ) { ?>
								<?php zm_thumbnail_link(); ?>
							<?php } else { ?>
								<?php zm_thumbnail(); ?>
							<?php } ?>

							<?php if ( has_post_format('video') ) { ?><a rel="bookmark" href="<?php echo esc_url( get_permalink() ); ?>"><i class="be be-play"></i></a><?php } ?>
							<?php if ( has_post_format('quote') ) { ?><div class="img-ico"><a rel="bookmark" href="<?php echo esc_url( get_permalink() ); ?>"><i class="be be-display"></i></a></div><?php } ?>
							<?php if ( has_post_format('image') ) { ?><div class="img-ico"><a rel="bookmark" href="<?php echo esc_url( get_permalink() ); ?>"><i class="be be-picture"></i></a></div><?php } ?>
							<?php if ( has_post_format('link') ) { ?><div class="img-ico"><a rel="bookmark" href="<?php echo esc_url( get_permalink() ); ?>"><i class="be be-link"></i></a></div><?php } ?>
						</figure>

						<?php if ( get_post_meta($post->ID, 'direct', true) ) { ?>
							<?php $direct = get_post_meta($post->ID, 'direct', true); ?>
							<h2 class="grid-title"><a href="<?php echo $direct ?>" target="_blank" rel="external nofollow"><?php the_title(); ?></a></h2>
						<?php } else { ?>
							<?php the_title( sprintf( '<h2 class="grid-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
						<?php } ?>

						<span class="grid-inf">
							<?php if ( has_post_format('link') ) { ?>
								<?php if ( get_post_meta($post->ID, 'link_inf', true) ) { ?>
									<span class="link-inf"><?php $link_inf = get_post_meta($post->ID, 'link_inf', true);{ echo $link_inf;}?></span>
								<?php } else { ?>
									<?php if (zm_get_option('meta_author')) { ?><span class="grid-author"><?php grid_author_inf(); ?></span><?php } ?>
									<span class="g-cat"><?php zm_category(); ?></span>
								<?php } ?>
							<?php } else { ?>
								<?php if (zm_get_option('meta_author')) { ?><span class="grid-author"><?php grid_author_inf(); ?></span><?php } ?>
								<span class="g-cat"><?php zm_category(); ?></span>
							<?php } ?>
							<span class="grid-inf-l">
								<?php views_span(); ?>
								<?php if ( !has_post_format('link') ) { ?><span class="date"><i class="be be-schedule ri"></i><?php the_time( 'm/d' ); ?></span><?php } ?>
								<?php if ( get_post_meta($post->ID, 'zm_like', true) ) : ?><span class="grid-like"><span class="be be-thumbs-up-o">&nbsp;<?php zm_get_current_count(); ?></span></span><?php endif; ?>
							</span>
			 			</span>
			 			<div class="clear"></div>
					</div>
				</article>
			<?php endwhile;?>
		</main>
		<div><?php begin_pagenav(); ?></div>
		<?php wp_reset_query(); ?>
		<div class="clear"></div>
	</section>
<?php get_footer(); ?>

<?php } else { ?>

<?php begin_primary_class(); ?>
	<?php special_single_content(); ?>
	<main id="main" class="site-main<?php if (zm_get_option('post_no_margin')) { ?> domargin<?php } ?>" role="main">
		<?php
			$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;;
			$special = get_post_meta($post->ID, 'special');
			$args = array(
				'tag' => $special, 
			    'ignore_sticky_posts' => 0, 
				'paged' => $paged
			);
			query_posts( $args );
		?>
		<?php while ( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'template/content', get_post_format() ); ?>
			<?php get_template_part('ad/ads', 'archive'); ?>
		<?php endwhile; ?>
	</main>

	<div class="pagenav-clear pagenav-special"><?php begin_pagenav(); ?></div>
	<?php wp_reset_query(); ?>
	<div class="clear"></div>
</div>

<?php if ( !get_post_meta($post->ID, 'no_sidebar', true) ) { ?>
<?php get_sidebar(); ?>
<?php } ?>
<?php get_footer(); ?>
<?php } ?>