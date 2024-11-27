<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="g-row show-white">
	<div class="g-col">
		<div class="section-box show-area">
			<div class="group-title" <?php aos_a(); ?>>
				<?php $s_c_t = get_post_meta( get_the_ID(), 's_c_t', true ); ?>
				<h1><?php echo $s_c_t; ?></h1>
				<?php 
					echo '<div class="begin-single-meta begin-single-meta-c">';
					echo '<span class="single-meta-area">';
					echo '<span class="meta-date">';
					echo '<time datetime="';
					echo get_the_date('Y-m-d');
					echo ' ' . get_the_time('H:i:s');
					echo '">';
					time_ago( $time_type ='posts' );
					echo '</time></span>';
					if (zm_get_option('post_cat')) {
						echo '<span class="meta-cat">';
						echo get_the_term_list( $post->ID, 'products', '' );
						echo '</span>';
					}
					views_span();

					if ( zm_get_option( 'post_modified' ) ) {
						echo '<span class="meta-modified"><i class="be be-edit ri"></i>';
						echo '<time datetime="';
						echo the_modified_time( 'Y-m-d H:i:s' );
						echo '">';
						the_modified_date();
						echo '</time></span>';
					}

					edit_post_link('<i class="be be-editor"></i>', '<span class="edit-link">', '</span>' );
					echo '</span>';
				?>
				<div class="clear"></div>
			</div>
			<?php while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php aos_a(); ?>>
					<div class="entry-content">
						<div class="single-content">
							<?php the_content(); ?>
						</div>
						<?php begin_link_pages(); ?>
						<?php echo bedown_show(); ?>
						<?php logic_notice(); ?>
						<?php if ( get_post_meta( get_the_ID(), 'down_link_much', true ) ) : ?><style>.down-link {float: left;}</style><?php endif; ?>
						<div class="clear"></div>
					</div>
				</article>
			<?php endwhile; ?>
		</div>
		<div class="clear"></div>
	</div>
</div>