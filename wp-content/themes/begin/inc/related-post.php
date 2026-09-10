<?php
if ( ! defined( 'ABSPATH' ) ) exit;
// 相关文章
function related_article() { ?>
	<?php if ( ! zm_get_option( 'related_mode' ) || ( zm_get_option( 'related_mode' ) == 'related_normal')) { ?>
	<?php if ( zm_get_option( 'post_no_margin' ) ) { ?>
		<article id="post-<?php the_ID(); ?>" class="post-item-list post ms doclose post-default " <?php aos_a(); ?>>
	<?php } else { ?>
		<article id="post-<?php the_ID(); ?>" class="post-item-list post post-default ms" <?php aos_a(); ?>>
	<?php } ?>

			<figure class="thumbnail">
				<?php echo zm_thumbnail(); ?>
				<span class="cat<?php if ( zm_get_option( 'no_thumbnail_cat' ) ) { ?> cat-roll<?php } ?><?php if ( zm_get_option( 'merge_cat' ) ) { ?> merge-cat<?php } ?>"><?php zm_category(); ?></span>
			</figure>

			<div class="post-area">
				<header class="entry-header">
					<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" target="_blank" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
				</header>
				<div class="entry-content">
					<div class="archive-content">
						<?php if ( has_excerpt('') ){
								echo wp_trim_words( get_the_excerpt(), zm_get_option( 'word_n' ), '...' );
							} else {
								$content = get_the_content();
								$content = strip_shortcodes( $content );
								if ( get_bloginfo( 'language' ) === 'en-US' ) {
									echo begin_strimwidth( strip_tags( $content ), 0, zm_get_option( 'words_n' ), '...' );
								} else {
									echo wp_trim_words( $content, zm_get_option( 'words_n' ), '...' );
								}
							}
						?>
					</div>
					<?php title_l(); ?>
					<span class="entry-meta vr">
						<?php begin_related_meta(); ?>
					</span>
					<?php if ( ! zm_get_option( 'related_img' ) == 'related_inside' ) { ?>
						<?php title_l(); ?>
					<?php } ?>
				</div>
				<div class="clear"></div>
			</div>
		</article>
	<?php } ?>

	<?php if ( zm_get_option( 'related_mode' ) == 'slider_grid') { ?>
		<div class="r4">
			<div class="related-site">
				<figure class="related-site-img">
					<?php echo zm_thumbnail(); ?>
				 </figure>
				<div class="related-title over"><a href="<?php the_permalink(); ?>" target="_blank"><?php the_title(); ?></a></div>
			</div>
		</div>
	<?php } ?>
<?php }

function related_core() {
if (!zm_get_option( 'related_orderby' ) || ( zm_get_option('related_orderby') == 'related_date' ) ) {
	$sorting = 'date';
}

if ( zm_get_option( 'related_orderby' ) == 'related_rand' ) {
	$sorting = 'rand';
}

if ( zm_get_option( 'related_orderby' ) == 'related_modified' ) {
	$sorting = 'modified';
}
echo '<div class="relat-post betip">';
	$post_num = zm_get_option( 'related_n' );
	global $post;
	$tmp_post = $post;
	$tags = ''; $i = 0;
	if ( get_the_tags( $post->ID ) ) {
		foreach ( get_the_tags( $post->ID ) as $tag ) $tags .= $tag->slug . ',';
		$tags = strtr( rtrim( $tags, ',' ), ' ', '-' );
		$myposts = get_posts( 'numberposts=' . $post_num . '&tag=' . $tags . '&exclude=' . $post->ID . '&orderby=' . $sorting );
		foreach( $myposts as $post ) {
			setup_postdata( $post );
			related_article();
			$i += 1;
		}
	}

	if ( $i < $post_num ) {
		$post = $tmp_post; setup_postdata( $post );
		$cats = ''; $post_num -= $i;
		foreach ( get_the_category( $post->ID ) as $cat ) $cats .= $cat->cat_ID . ',';
		$cats = strtr( rtrim( $cats, ',' ), ' ', '-' );
		$myposts = get_posts( 'numberposts=' . $post_num . '&category=' . $cats . '&exclude=' . $post->ID . '&orderby=' . $sorting );
		foreach( $myposts as $post ) {
			setup_postdata( $post );
			related_article();
		}
	}
	$post = $tmp_post; setup_postdata( $post );
	be_help( $text = '主题选项 → 基本设置 → 文章设置 → 相关文章' );
echo '<div class="clear"></div>';
echo '</div>';
}

function relat_post() { ?>
<?php if ( zm_get_option( 'related_img' ) == 'related_inside' ) { ?>
	<?php 
		if ( zm_get_option( 'not_related_cat' ) ) {
			$notcat = implode( ',', zm_get_option( 'not_related_cat' ) );
		} else {
			$notcat = '';
		}
		if ( ! in_category( explode( ',', $notcat ) ) ) {
			echo '<div class="relat-post-box betip">';
			related_core();
			echo '</div>';
		}
	?>
<?php } ?>
<?php }