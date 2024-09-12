<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="g-row show-grey dai">
	<div class="g-col">
		<div class="group-title" <?php aos_a(); ?>>
			<h3><?php _e( '相关文章', 'begin' ); ?></h3>
		</div>
		<section id="picture" class="picture-area content-area site-img grid-cat-4">
			<main id="main" class="be-main site-main" role="main">
				<?php 
					$loop = new WP_Query( array( 'post_type' => 'show', 'posts_per_page' => 4, 'post__not_in' => array($post->ID) ) );
					while ( $loop->have_posts() ) : $loop->the_post();
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class('scl'); ?>>
					<div class="picture-box ms bk sup" <?php aos_a(); ?>>
						<figure class="picture-img">
							<?php img_thumbnail(); ?>
						</figure>
						<?php the_title( sprintf( '<h3 class="picture-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
					</div>
				</article>
				<?php endwhile; ?>
			</main>
			<div class="clear"></div>
		</section>
	</div>
</div>