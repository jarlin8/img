<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
get_header(); ?>
<?php if (!zm_get_option('search_the') || (zm_get_option("search_the") == 'search_list')){ ?>
<!-- list -->
	<section id="primary" class="content-area search-site">
		<main id="main" class="site-main" role="main">
			<?php if ( have_posts() ) : ?>
				<div class="search-page search-page-title bk">
					<?php while ( have_posts() ) : the_post(); ?>
						<article class="search-entry-title scl"><a href="<?php the_permalink(); ?>"><?php the_title(); ?><span class="search-inf"><?php time_ago( $time_type ='post' ); ?></span></a></article>
					<?php endwhile; ?>
				</div>
			<?php else : ?>
				<?php get_template_part( 'template/content', 'none' ); ?>
				<?php remove_footer(); ?>
			<?php endif; ?>
		</main>
		<?php begin_pagenav(); ?>
	</section>
<?php } ?>

<?php if (zm_get_option('search_the') == 'search_img'){ ?>
<!-- img -->
<?php if ( have_posts() ) : ?>
	<section id="picture" class="content-area grid-cat-<?php echo zm_get_option('img_f'); ?>">
		<main id="main" class="site-main" role="main">
				<?php while ( have_posts() ) : the_post(); ?>
				<article class="picture scl" <?php aos_a(); ?>>
					<div class="picture-box ms sup bk">
						<figure class="picture-img">
							<?php if (zm_get_option('hide_box')) { ?>
								<a rel="bookmark" href="<?php echo esc_url( get_permalink() ); ?>"><div class="hide-box"></div></a>
								<a rel="bookmark" href="<?php echo esc_url( get_permalink() ); ?>"><div class="hide-excerpt"><?php if (has_excerpt('')){ echo wp_trim_words( get_the_excerpt(), 62, '...' ); } else { echo wp_trim_words( get_the_content(), 72, '...' ); } ?></div></a>
							<?php } ?>
							<?php zm_thumbnail(); ?>
						</figure>
						<?php the_title( sprintf( '<h2 class="grid-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
						<span class="grid-inf">
							<span class="g-cat"><i class="be be-folder ri"></i><?php zm_category(); ?></span>
							<span class="grid-inf-l">
								<span class="date"><i class="be be-schedule ri"></i><?php the_time( 'm/d' ); ?></span>
								<?php views_span(); ?>
								<?php if ( get_post_meta($post->ID, 'zm_like', true) ) : ?><span class="grid-like"><span class="be be-thumbs-up-o">&nbsp;<?php zm_get_current_count(); ?></span></span><?php endif; ?>
							</span>
			 			</span>
			 			<div class="clear"></div>
					</div>
				</article>
				<?php endwhile;?>
		</main>
		<?php begin_pagenav(); ?>
		<div class="clear"></div>
	</section>
<?php else : ?>
	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<div class="search-page bk">
			<?php get_template_part( 'template/content', 'none' ); ?>
		</div>
		</main>
	</section>
	<?php remove_footer(); ?>
<?php endif; ?>
<?php } ?>

<?php if (zm_get_option('search_the') == 'search_normal'){ ?>
<!-- normal -->
	<section id="primary" class="content-area">
		<?php if ( have_posts() ) : ?>
			<main id="main" class="site-main<?php if (zm_get_option('post_no_margin')) { ?> domargin<?php } ?>" role="main">
				<?php while ( have_posts() ) : the_post(); ?>
					<?php get_template_part( 'template/content', get_post_format() ); ?>
					<?php get_template_part('ad/ads', 'archive'); ?>
				<?php endwhile; ?>
			</main>
		<?php else : ?>
			<div class="search-page bk">
				<?php get_template_part( 'template/content', 'none' ); ?>
			</div>
			<?php remove_footer(); ?>
		<?php endif; ?>
		<div class="pagenav-clear"><?php begin_pagenav(); ?><div class="clear"></div></div>
		<div class="clear"></div>
	</section>
<?php } ?>

<?php get_footer(); ?>