<?php
/*
Template Name: 邀请码
*/
if ( ! defined( 'ABSPATH' ) ) exit;
get_header(); ?>

<div id="primary" class="content-area primary-contact">
	<main id="main" class="site-main" role="main">
		<?php while ( have_posts() ) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class('bk da'); ?>>
			<?php if ( get_post_meta($post->ID, 'header_img', true) || get_post_meta($post->ID, 'header_bg', true) ) { ?>
			<?php } else { ?>
				<header class="entry-header">
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				</header><!-- .entry-header -->
			<?php } ?>
			<div class="entry-content">
				<div class="single-content">
					<?php the_content(); ?>
					<?php if (zm_get_option('invitation_code')) { ?>
						<?php be_invite_list(); ?>
					<?php } else { ?>
						<p><?php _e( '未开启邀请码功能', 'begin' ); ?></p>
					<?php } ?>
					<div class="clear"></div>
				</div>
			</div>
		</article>
		<?php endwhile; ?>
	</main>
</div>
<?php get_footer(); ?>