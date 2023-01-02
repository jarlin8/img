<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
get_header(); ?>

<section id="primary" class="content-area be-qa" <?php aos_a(); ?>>
	<main id="main" class="site-main<?php if (zm_get_option('post_no_margin')) { ?> domargin<?php } ?>" role="main">
	<?php get_template_part( 'template/cat-top' ); ?>
		<?php if ( ( zm_get_option( 'no_child' ) ) && is_category() ) { ?>
			<?php 
				$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
				query_posts( array( 'category__in' => array( get_query_var( 'cat' ) ), 'paged' => $paged ) );
			?>
		<?php } ?>

		<?php 
		$cat_term_id = get_category_id($cat);
		$cat_taxonomy = get_category($cat)->taxonomy;
		if (sizeof( get_term_children($cat_term_id, $cat_taxonomy)) != 0 ) { ?>
					<ul class="qa-child-cat da bk ms">
					<?php
						$categories = get_the_category();
						foreach( $categories as $category ){
							if($category->parent != 0){
								$parent_category = get_term( $category->parent );

								global $cat;
								$parent = get_category($cat);
								if( $parent->category_parent != 0 ) {
									$cat_id = $parent->category_parent;
									$parent = get_category( $cat_id );
									if( $parent->category_parent != 0 ) {
										$cat_id = $parent->category_parent;
									} else {
										echo '<li class="qa-parent-cat">';
									}
								} else {
									echo '<li class="qa-all-cat">';
								}
		
								echo '<a href="' . esc_url( get_category_link($parent_category->term_id)) . '">' . esc_html($parent_category->name) . ' </a>';
								echo '</li>';
								break;
							}
							echo '<span class="qa-all-cat">';
							echo the_category();
							echo '</span>';
						}
					?>
					<?php wp_list_categories('child_of=' . get_category_id($cat) . '&depth=0&hierarchical=0&hide_empty=0&title_li=&use_desc_for_title=0&order=ASC');?>
					<ul class="clear"></ul>
				</ul>

		<?php } ?>


		<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<?php if (zm_get_option('post_no_margin')) { ?>
			<article id="post-<?php the_ID(); ?>" <?php aos_a(); ?> <?php post_class('post ms bk doclose scl'); ?>>
			<?php } else { ?>
			<article id="post-<?php the_ID(); ?>" <?php aos_a(); ?> <?php post_class('post ms bk scl'); ?>>
			<?php } ?>
				<?php 
					echo '<div class="qa-cat-avatar load">';
					// echo '<a href="' . get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ) . '">';
					echo '<a href="' . get_permalink() . '" rel="external nofollow">';
						if (zm_get_option('cache_avatar')) {
						echo begin_avatar( get_the_author_meta('email'), '96', '', get_the_author() );
						} else {
							echo be_avatar_author();
						}
					echo '</a>';
					echo '</div>';
				?>

				<div class="qa-cat-content">
					<header class="qa-header">
						<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
					</header>
		
					<div class="qa-cat-meta">
						<?php 
							echo '<span class="qa-meta qa-cat">';
							the_category( ' ' );
							echo '</span>';

							echo '<span class="qa-meta qa-time"><span class="qa-meta-class"></span>';
							time_ago( $time_type ='post' );
							echo '</span>';

							qa_get_comment_last();

							echo '<span class="qa-meta qa-r">';
								if (!zm_get_option('close_comments')) {
									echo '<span class="qa-meta qa-comment">';
										comments_popup_link( '<span class="no-comment"><i>' . sprintf(__( '回复', 'begin' )) . '</i>' . sprintf(__( '0', 'begin' )) . '</span>', '<i>' . sprintf(__( '回复', 'begin' )) . '</i>1 ', '<i>' . sprintf(__( '回复', 'begin' )) . '</i>%' );
									echo '</span>';
								}
								views_qa();
							echo '</span>';

						?>
						<div class="clear"></div>
					</div>
				</div>
			</article>
		<?php endwhile; ?>

		<?php else : ?>
			<?php get_template_part( 'template/content', 'none' ); ?>
		<?php endif; ?>
	</main>
	<div class="pagenav-clear"><?php begin_pagenav(); ?></div>
</section>

<?php get_sidebar(); ?>
<?php get_footer(); ?>