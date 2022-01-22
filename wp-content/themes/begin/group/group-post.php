<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if (zm_get_option('group_post')) { ?>
<?php
	$posts = get_posts( array(
		'post_type' => 'any',
		'include' => explode(',',zm_get_option('group_post_id') ),
		'ignore_sticky_posts' => 1
	) );
?>
<?php if($posts) : foreach( $posts as $post ) : setup_postdata( $post ); ?>
<div class="g-row g-line grl sort" name="<?php echo zm_get_option('group_post_s'); ?>" <?php aos(); ?>>
	<div class="g-col">
		<div class="group-post-box">
			<article id="post-<?php the_ID(); ?>" class="group-post-list">
				<div class="group-post-img" <?php aos_b(); ?>>
					<?php gr_wd_thumbnail(); ?>
				</div>
				<div class="group-post-content" <?php aos_b(); ?>>
					<?php the_title( sprintf( '<h3 class="group-post-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>

					<div class="group-post-excerpt">
						<?php if (has_excerpt('')){
								echo wp_trim_words( get_the_excerpt(), 240, '...' );
							} else {
								// the_excerpt('');
								$content = get_the_content();
								$content = wp_strip_all_tags(str_replace(array('[',']'),array('<','>'),$content));
								echo wp_trim_words( $content, 240, '...' );
						    }
						?>
					</div>
				</div>
				<div class="clear"></div>
			</article>
		</div>
		<div class="group-post-more bk da"><a href="<?php the_permalink(); ?>" title="<?php _e( '详细查看', 'begin' ); ?>" rel="external nofollow"><i class="be be-more"></i></a></div>
		<div class="clear"></div>
	</div>
</div>
<?php endforeach; endif; ?>
<?php wp_reset_query(); ?>
<?php } ?>