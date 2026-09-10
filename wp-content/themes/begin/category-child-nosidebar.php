<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * category Template: 子分类
 */
get_header(); ?>

<style type="text/css">
#primary {
	width: 100%;
}

.child-box {
	margin: 0 -5px;
}

.ch3 {
	padding: 0 5px;
	float: left;
	width: 50%;
	transition-duration: .5s;
}

.child-post {
	position: relative;
	background: var(--be-bg-white);
	overflow: hidden;
	margin: 0 0 10px 0;
	border-radius: 8px;
	box-shadow: 0 0 0 1px var(--be-shadow);
}

.child-inf {
	float: right;
	color: #999;
}

.ch3 .cat-title {
	background: var(--be-bg-grey-f8);
	height: 38px;
	line-height: 38px;
	margin: 1px 1px 0 1px;
	border-radius: 8px 8px 0 0;
}

.ch3 .cat-title a {
	float: left;
	width: 100%;
	height: 38px;
	line-height: 38px;
}

.child-list {
	padding: 15px;
}

.child-list li {
	line-height: 230%;
	width: 84%;
	white-space: nowrap;
	word-wrap: normal;
	text-overflow: ellipsis;
	overflow: hidden;
}

@media screen and (max-width:900px) {
	.child-box {
		margin-top: 5px;
	}
	.child-inf {
		display: none;
	}
	.child-inf {
		display: none;
	}
	.child-list li {
		width: 99%;
	}
}

@media screen and (max-width:700px) {
	.ch3 {
		width: 100%;
	}
}
</style>

<section id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<div class="child-box">
			<?php
				global $cat;
				$cats = get_categories(array(
					'child_of'   => $cat,
					'parent'     => $cat,
					'hide_empty' => 0
				 ));
				foreach( $cats as $the_cat ){
					$posts = get_posts(array(
						'category'    => $the_cat->cat_ID,
						'numberposts' => 8,
					));
					if (!empty($posts)){
						echo '<div class="ch3" ';
						echo aos_a();
						echo '>';
						echo '<div class="child-post ms">';
						echo '<h3 class="child-title cat-title"><a href="' . get_category_link( $the_cat ) . '" rel="bookmark" ' . goal() . '>';
						echo title_i();
						echo $the_cat->name;
						echo more_i();
						echo '</a></h3><div class="clear"></div>';
						echo '
							<ul class="child-list">';
								foreach( $posts as $post ){
									echo '<i class="child-inf">' . mysql2date( 'd/m', $post->post_date ) . '</i>
									<li class="list-title"><a class="srm" href="' . get_permalink( $post->ID ) . '" rel="bookmark" ' . goal() . '>' . $post->post_title . '</a></li>';
								}
							echo '</ul>';
						echo '</div></div>';
					}
				}
			?>
		</div>
	</main><!-- .site-main -->
</section><!-- .content-area -->

<?php get_footer(); ?>