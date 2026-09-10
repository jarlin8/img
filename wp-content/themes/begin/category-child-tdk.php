<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * category Template: 标签标题
 */
get_header(); ?>
<section id="primary-cover" class="content-area cat-tdk">
	<main id="main" class="site-main" role="main">
		<?php 
			$category = get_queried_object();

			$terms = get_terms( array(
				'taxonomy'   => 'category',
				'parent'     => $category->term_id,
				'hide_empty' => false,
			));
		?>

		<?php if ( count( $terms ) > 0 ) { ?>
			<?php foreach ( $terms as $term ) : ?>
				<div class="cat-container ms" <?php aos_a(); ?>>
					<h3 class="cat-title">
						<a href="<?php echo get_category_link( $term->term_id ); ?>" rel="bookmark" <?php echo goal(); ?>>
							<?php if ( zm_get_option( 'cat_icon' ) ) { ?>
								<?php if ( get_option( 'zm_taxonomy_icon' . $term->term_id ) ) { ?><i class="t-icon <?php echo zm_taxonomy_icon_code( $term->term_id ); ?>"></i><?php } ?>
								<?php if ( get_option( 'zm_taxonomy_svg' . $term->term_id ) ) { ?><svg class="t-svg icon" aria-hidden="true"><use xlink:href="#<?php echo zm_taxonomy_svg_code( $term->term_id ); ?>"></use></svg><?php } ?>
								<?php if ( ! get_option( 'zm_taxonomy_icon' . $term->term_id ) && ! get_option( 'zm_taxonomy_svg'.$term->term_id ) ) { ?><?php title_i(); ?><?php } ?>
							<?php } else { ?>
								<?php title_i(); ?>
							<?php } ?>
							<?php echo $term->name; ?>
							<?php more_i(); ?>
						</a>
					</h3>

					<div class="clear"></div>
					<div class="cat-tdk-main">
						<ul class="cat-tdk-list">
							<?php 
								$posts = get_posts( array(
									'cat'            => $term->term_id,
									'posts_per_page' => be_get_option( 'cat_tdk_n' ),
								));
		
								foreach ( $posts as $post ) : setup_postdata( $post );
									if ( be_get_option( 'cat_tdk_cut_title' ) ) {
										$cut = ' cut';
									} else { 
										$cut = '';
									}
									echo '<li class="cat-tdk-area' . $cut . '">';
									$post_tags = get_the_tags();
									if ( $post_tags ) {
										$first_tag = $post_tags[0];
										echo '<a class="cat-tdk-tag" href="' . get_tag_link( $first_tag->term_id ) . '" rel="bookmark" ' . goal() . '>' . $first_tag->name . '</a>';
									}
									the_title( sprintf( '<h2 class="tdk-entry-title"><a class="cat-tdk-title" href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a></h2>' );
									echo '</li>';
								endforeach;
								wp_reset_postdata();
							?>
						</ul>
						<div class="clear"></div>
					</div>
				</div>
			<?php endforeach; ?>
		<?php } else { ?>

			<?php $category = get_query_var( 'cat' ); ?>
			<div class="cat-container ms" <?php aos_a(); ?>>
				<div class="cat-tdk-main">
					<ul class="cat-tdk-list">
						<?php 
							$posts = get_posts( array(
								'cat'            => $category,
								'posts_per_page' => -1,
							));
			
							foreach ( $posts as $post ) : setup_postdata( $post );
								if ( be_get_option( 'cat_tdk_cut_title' ) ) {
									$cut = ' cut';
								} else { 
									$cut = '';
								}
								echo '<li class="cat-tdk-area' . $cut . '">';
								$post_tags = get_the_tags();
								if ( $post_tags ) {
									$first_tag = $post_tags[0];
									echo '<a class="cat-tdk-tag" href="' . get_tag_link( $first_tag->term_id ) . '">' . $first_tag->name . '</a>';
								}
								the_title( sprintf( '<a class="cat-tdk-title" href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a>' );
								echo '</li>';
							endforeach;
							wp_reset_postdata();
						?>
					</ul>
					<div class="clear"></div>
				</div>
			</div>
		<?php } ?>
	</main>
	<div class="clear"></div>
</section>

<?php get_footer(); ?>