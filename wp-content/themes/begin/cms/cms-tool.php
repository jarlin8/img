<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if (zm_get_option('cms_tool')) { ?>
<div class="group-tool-box sort" name="<?php echo zm_get_option('cms_tool_s'); ?>">
	<?php $posts = get_posts( array( 'post_type' => 'any', 'orderby' => 'menu_order', 'meta_key' => 'tool_ico', 'numberposts' => '60') ); if ($posts) : foreach( $posts as $post ) : setup_postdata( $post ); ?>
	<?php 
		$tool_ico = get_post_meta(get_the_ID(), 'tool_ico', true);
		$tool_button = get_post_meta(get_the_ID(), 'tool_button', true);
		$tool_url = get_post_meta(get_the_ID(), 'tool_url', true);
		$tool_ico_svg = get_post_meta(get_the_ID(), 'tool_ico_svg', true);
		$tool_des = get_post_meta(get_the_ID(), 'tool_des', true);
	?>
	<div class="sx4">
		<div class="group-tool edit-buts sup bk" <?php aos_b(); ?>>
			<div class="group-tool-img">
				<div class="group-tool-img-top bgt"></div>
				<div class="img40 lazy"><div class="bgimg" style="background-image: url(<?php get_bgimg(); ?>) !important;"></div></div>
			</div>

			<div class="group-tool-pu bgt">
				<div class="group-tool-ico" <?php aos_b(); ?>>
					<?php if ( get_post_meta(get_the_ID(), 'tool_ico_svg', true) ) { ?>
						<svg class="icon" aria-hidden="true"><use xlink:href="#<?php echo $tool_ico; ?>"></use></svg>
					<?php } else { ?>
						<i class="<?php echo $tool_ico; ?>"></i>
					<?php } ?>
				</div>
				<?php if ( get_post_meta(get_the_ID(), 'tool_button', true) ) { ?>
					<h3 class="group-tool-title"><?php the_title(); ?></h3>
				<?php } else { ?>
					<a href="<?php the_permalink(); ?>" target="_blank" rel="external nofollow"><h3 class="group-tool-title"><?php the_title(); ?></h3></a>
				<?php } ?>

				<?php edit_post_link('<i class="be be-editor"></i>', '<span class="edit-link-but">', '</span>' ); ?>

				<p class="group-tool-p">
					<?php if ( get_post_meta(get_the_ID(), 'tool_des', true) ) { ?>
						<?php echo $tool_des; ?>
					<?php } else { ?>
						<?php 
							$content = get_the_content();
							$content = wp_strip_all_tags(str_replace(array('[',']'),array('<','>'),$content));
							echo wp_trim_words( $content, 42, '...' );
						?>
					<?php } ?>
				</p>

				<?php if ( get_post_meta(get_the_ID(), 'tool_button', true) ) { ?>
					<div class="group-tool-link bgt"><a class="bgt" href="<?php if ( get_post_meta(get_the_ID(), 'tool_url', true) ) { ?><?php echo $tool_url; ?><?php } else { ?><?php the_permalink(); ?><?php } ?>" target="_blank" rel="external nofollow" data-hover="<?php echo $tool_button; ?>"><span><i class="be be-more"></i></span></a></div>
				<?php } ?>
			</div>
		</div>
	</div>
	<?php endforeach; endif; ?>
	<?php wp_reset_query(); ?>
	<div class="clear"></div>
</div>
<?php } ?>