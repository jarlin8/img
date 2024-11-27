<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * category Template: 子分类图片
 */
get_header(); ?>

<style type="text/css">
#primary {
	width: 100%;
}

.child-box {
	margin: 0 -5px;
}

.child-cat {
	display: none;
}

.grid-cat-title {
	width: 100%;
	margin: 10px 0;
	padding: 0 0 0 5px;
}

.child-img-box {
	position: relative;
	float: left;
	padding: 5px;
}

.child-img {
	background: #fff;
	margin: 0 0 10px 0;
	overflow: hidden;
	border-radius: 8px;
	border: 1px solid #e7e7e7;
}

.child-img-box img {
	float: left;
	width: auto;
	height: auto;
	max-width: 100%;
}

@media screen and (max-width:600px) {
	.child-box {
		margin: 0;
	}
}
</style>
<section id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<article class="child-box">
			<?php
				global $cat;
				$cats = get_categories(array(
					'child_of' => $cat,
					'parent' => $cat,
					'hide_empty' => 0
				 ));
				foreach($cats as $the_cat){
					$posts = get_posts(array(
						'category' => $the_cat->cat_ID,
						'numberposts' => 8,
					));
					if (!empty($posts)){
						echo '<h3 class="grid-cat-title" data-aos="zoom-in"><a href="'.get_category_link($the_cat).'">'.$the_cat->name.'</a></h3><div class="clear"></div>';
						foreach($posts as $post) {
							echo '<div class="child-img-box child-img-'. zm_get_option('img_f') .'" ';
							echo aos_a();
							echo '>';
							echo '<div class="child-img sup ms bk">';
							echo '<figure class="picture-img">';
							zm_thumbnail();
							echo '</figure>';
							echo '<h2 class="grid-title"><a href="'.get_permalink($post->ID).'">'.$post->post_title.'</a></h2></div></div>';
						}
					}
				}
				echo '<div class="clear"></div>';
			?>
		</article>
	</main><!-- .site-main -->
</section><!-- .content-area -->
<?php get_footer(); ?>