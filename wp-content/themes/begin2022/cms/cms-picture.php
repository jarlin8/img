<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( zm_get_option( 'picture_box' ) ) { ?>
	<div class="line-four sort" name="<?php echo zm_get_option( 'picture_s' ); ?>">
		<?php if ( zm_get_option( 'img_id' ) ) { ?>
			<?php $display_categories = explode( ',',zm_get_option( 'img_id' ) ); foreach ( $display_categories as $category ) { ?>
			<?php if (zm_get_option( 'no_cat_child' ) ) { ?>
				<?php query_posts( array( 'cat' => $category ) ); ?>
				<?php query_posts( array( 'showposts' => zm_get_option( 'picture_n' ), 'cat' => $category, 'category__in' => array( get_query_var( 'cat' ) ), 'post__not_in' => $do_not_duplicate ) ); ?>
			<?php } else { ?>
				<?php query_posts( array( 'showposts' => zm_get_option( 'picture_n' ), 'cat' => $category, 'post__not_in' => $do_not_duplicate ) ); ?>
			<?php } ?>
				<div class="cms-picture-box">
					<h3 class="cms-picture-cat-title"><a href="<?php echo get_category_link( $category ); ?>"><?php single_cat_title(); ?></a></h3>
					<?php while ( have_posts() ) : the_post(); ?>
						<div id="post-<?php the_ID(); ?>" class="xl4 xm4">
							<div class="picture-cms ms bk" <?php aos_a(); ?>>
								<figure class="picture-cms-img">
									<?php zm_thumbnail(); ?>
								</figure>
								<?php the_title( sprintf( '<h2 class="picture-cms-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
							</div>
						</div>
					<?php endwhile; ?>
					<?php wp_reset_query(); ?>
					<div class="clear"></div>
				</div>
			<?php } ?>
		<?php } ?>

		<div class="clear"></div>

		<?php if ( zm_get_option( 'picture_id' ) ) { ?>
			<?php
			$tax = 'gallery';
			$tax_terms = get_terms( $tax, array( 'orderby' => 'menu_order', 'order' => 'ASC', 'include' => explode( ',',zm_get_option('picture_id' ) ) ) );
			if ( $tax_terms ) { ?>
				<?php foreach ( $tax_terms as $tax_term ) { ?>
					<?php
						$args = array(
							'post_type'        => 'picture',
							"$tax"             => $tax_term->slug,
							'post_status'      => 'publish',
							'posts_per_page'   => zm_get_option( 'picture_n' ),
							'orderby'          => 'date', 
							'order'            => 'DESC', 
							'caller_get_posts' => 1
						);
						$be_query = new WP_Query( $args );
					?>

					<?php if ( $be_query->have_posts() ) { ?>
						<div class="cms-picture-box">
							<?php
								$args = array(
									"$tax"             => $tax_term->slug,
									'posts_per_page'   => 1,
								);
								$catquery = new WP_Query( $args );
							?>
							<?php while ( $catquery->have_posts() ) : $catquery->the_post(); ?>
								<h3 class="cms-picture-cat-title"><?php echo get_the_term_list( $post->ID, 'gallery', '' ); ?></h3>
							<?php endwhile; wp_reset_query(); ?>

							<?php while ( $be_query->have_posts() ) : $be_query->the_post(); ?>

							<div id="post-<?php the_ID(); ?>" class="xl4 xm4 ">
								<div class="picture-cms ms bk" <?php aos_a(); ?>>
									<figure class="picture-cms-img">
										<?php img_thumbnail(); ?>
									</figure>
									<?php the_title( sprintf( '<h2 class="picture-cms-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
								</div>
							</div>
							<?php endwhile; wp_reset_query(); ?>
							<div class="clear"></div>
						</div>
					<?php } ?>
				<?php } ?>
			<?php } ?>
			<div class="clear"></div>
		<?php } ?>
	</div>
<?php } ?>