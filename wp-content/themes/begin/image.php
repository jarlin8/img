<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
get_header(); ?>
<div id="primary" class="content-area no-sidebar">
	<main id="main" class="be-main site-main" role="main">
		<?php while ( have_posts() ) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" class="post-item post">
			<header class="entry-header">
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
			</header>

			<div class="begin-single-meta begin-single-meta-c">
				<span>上传于<?php time_ago( $time_type ='post' ); ?></span>
				<span>
					分辨率
					<?php
						$metadata = wp_get_attachment_metadata();
						if ( $metadata ) {
							printf(
								'<span class="full-size-link"><span class="screen-reader-text">%1$s</span><a href="%2$s" target="_blank">%3$s&times;%4$s</a></span>',
								esc_html_x( 'Full size', '', '' ), 
								esc_url( wp_get_attachment_url() ),
								absint( $metadata['width'] ),
								absint( $metadata['height'] )
							);
						}
					?>
					大小 <?php echo size_format( filesize( get_attached_file(  get_the_ID() ) ), 2 ); ?>
				</span>
				<?php views_span(); ?>
				<?php edit_post_link('<i class="be be-editor"></i>', '<span class="edit-link">', '</span>' ); ?>
			</div>

			<div class="entry-content">
				<div class="single-content">
					<?php
						$image_size = apply_filters( '', '' );
						echo wp_get_attachment_image( get_the_ID(), $image_size );
					?>
					<?php be_like(); ?>
				</div>
				<footer class="single-footer" style="margin-top: 50px;">
					<div class="single-cat-tag">
						<div class="single-cat">附件来自：<a href="<?php echo get_permalink($post->post_parent); ?>" rev="attachment"><?php echo get_the_title($post->post_parent); ?></a></div>
					</div>
				</footer>
				<div class="clear"></div>
			</div>
		</article>
		<?php endwhile; ?>
	</main>
</div>
<?php get_footer(); ?>