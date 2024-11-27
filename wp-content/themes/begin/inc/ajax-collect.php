<?php
if ( ! defined( 'ABSPATH' ) ) exit;
// 杂志推荐TAB
// 最新
function cms_collect_new() {
	$rec = ( array ) be_get_option( 'rectab' );
	$new_type   = $rec['cms_collect_new_type'];
	$post_types = array_diff( get_post_types( array( 'public' => true ) ), ( array ) $new_type );
	$notcat     = isset( $rec['cms_collect_not_new'] ) ? $rec['cms_collect_not_new'] : '';
	$notpost    = isset( $rec['cms_collect_not_new_post'] ) ? $rec['cms_collect_not_new_post'] : '';
	$goal       = ! empty($rec['cms_collect_new_blank']) ? 'target="_blank"' : '';
	$args = array(
		'post_type'           => $post_types,
		'posts_per_page'      => $rec['cms_collect_new_n'],
		'post_status'         => 'publish',
		'ignore_sticky_posts' => true,
		'orderby'             => 'date',
		'order'               => 'DESC',
		'category__not_in'    => explode( ',', $notcat ),
		'post__not_in'        => explode( ',', $notpost ),
	);

	$query = new WP_Query( $args );

	if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
?>
	<article id="post-<?php the_ID(); ?>" class="collect-item <?php if ( ! empty( $rec['cms_collect_new_img'] ) ) { ?>collect-img<?php } else { ?>collect-list<?php } ?>" <?php aos_a(); ?>>
		<?php if ( ! empty( $rec['cms_collect_new_img'] ) ) { ?>
			<figure class="thumbnail">
				<?php echo zm_thumbnail(); ?>
			</figure>
		<?php } ?>
		<div class="collect-area">
			<h2 class="collect-title"><a href="<?php the_permalink(); ?>" rel="bookmark" <?php echo $goal; ?>><?php the_title(); ?></a></h2>
			<div class="collect-meta"><?php echo collect_meta_new(); ?></div>
		</div>
	</article>
	<div class="collect-loading"><span class="dual-ring"></span><span class="dual-ring"></span></div>
<?php
	endwhile;endif;
	wp_reset_postdata();
	die();
}

add_action( 'wp_ajax_load_new', 'cms_collect_new' );
add_action( 'wp_ajax_nopriv_load_new', 'cms_collect_new' );
// 热门
function cms_collect_views() {
	$rec = ( array ) be_get_option( 'rectab' );
	$views_type = $rec['cms_collect_views_type'];
	$post_types = array_diff( get_post_types( array( 'public' => true ) ), ( array ) $views_type );
	$notcat     = isset( $rec['cms_collect_not_views'] ) ? $rec['cms_collect_not_views'] : '';
	$days       = $rec['cms_collect_views_days'];
	$n          = isset( $rec['cms_collect_views_n'] ) ? $rec['cms_collect_views_n'] : '';
	$notpost    = isset( $rec['cms_collect_not_views_post'] ) ? $rec['cms_collect_not_views_post'] : '';
	$goal       = ! empty($rec['cms_collect_views_blank']) ? 'target="_blank"' : '';
	$args = array(
		'post_type'           => $post_types,
		'posts_per_page'      => $n,
		'post_status'         => 'publish',
		'ignore_sticky_posts' => true,
		'meta_key'            => 'views',
		'orderby'             => 'meta_value_num',
		'order'               => 'DESC',
		'category__not_in'    => explode( ',', $notcat ),
		'post__not_in'        => explode( ',', $notpost ),
		'date_query'          => array(
			array(
				'after'       => $days . ' days ago'
			)
		)
	);

	$query = new WP_Query( $args );

	if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
?>
	<article id="post-<?php the_ID(); ?>" class="collect-item <?php if ( ! empty( $rec['cms_collect_views_img'] ) ) { ?>collect-img<?php } else { ?>collect-list<?php } ?>" <?php aos_a(); ?>>
		<?php if ( ! empty( $rec['cms_collect_views_img'] ) ) { ?>
			<figure class="thumbnail">
				<?php echo zm_thumbnail(); ?>
			</figure>
		<?php } ?>
		<div class="collect-area">
			<h2 class="collect-title"><a href="<?php the_permalink(); ?>" rel="bookmark" <?php echo $goal; ?>><?php the_title(); ?></a></h2>
			<div class="collect-meta"><?php echo collect_meta(); ?></div>
		</div>
	</article>
	<div class="collect-loading"><span class="dual-ring"></span><span class="dual-ring"></span></div>
<?php
	endwhile;endif;
	wp_reset_postdata();
	die();
}

add_action( 'wp_ajax_load_views', 'cms_collect_views' );
add_action( 'wp_ajax_nopriv_load_views', 'cms_collect_views' );

// 热评
function cms_collect_comment() {
	$rec = ( array ) be_get_option( 'rectab' );
	$post_types = array_diff( get_post_types( array( 'public' => true ) ), array( 'page','question', 'answer' ) );
	$days       = $rec['cms_collect_comment_days'];
	$notpost    = isset( $rec['cms_collect_not_comment_post'] ) ? $rec['cms_collect_not_comment_post'] : '';
	$goal       = ! empty($rec['cms_collect_comment_blank']) ? 'target="_blank"' : '';
	$args = array(
		'post_type'           => $post_types,
		'posts_per_page'      => $rec['cms_collect_comment_n'],
		'post_status'         => 'publish',
		'ignore_sticky_posts' => true,
		'orderby'             => 'comment_count',
		'order'               => 'DESC',
		'post__not_in'        => explode( ',', $notpost ),
		'date_query'          => array(
			array(
				'after'       => $days . ' days ago'
			)
		)
	);

	$query = new WP_Query( $args );

	if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
?>
	<article id="post-<?php the_ID(); ?>" class="collect-item <?php if ( ! empty( $rec['cms_collect_comment_img'] ) ) { ?>collect-img<?php } else { ?>collect-list<?php } ?>" <?php aos_a(); ?>>
		<?php if ( ! empty( $rec['cms_collect_comment_img'] ) ) { ?>
			<figure class="thumbnail">
				<?php echo zm_thumbnail(); ?>
			</figure>
		<?php } ?>
		<div class="collect-area">
			<h2 class="collect-title"><a href="<?php the_permalink(); ?>" rel="bookmark" <?php echo $goal; ?>><?php the_title(); ?></a></h2>
			<div class="collect-meta collect-meta-comment"><?php echo collect_meta_comment(); ?></div>
		</div>
	</article>
	<div class="collect-loading"><span class="dual-ring"></span><span class="dual-ring"></span></div>
<?php
	endwhile;endif;
	wp_reset_postdata();
	die();
}

add_action( 'wp_ajax_load_comment', 'cms_collect_comment' );
add_action( 'wp_ajax_nopriv_load_comment', 'cms_collect_comment' );


// 推荐
function cms_collect_cat() {
	$rec  = ( array ) be_get_option( 'rectab' );
	$goal = ! empty($rec['cms_collect_cat_blank']) ? 'target="_blank"' : '';
	$args = array(
		'cat'                 => $rec['cms_collect_cat_id'],
		'post_type'           => 'any',
		'posts_per_page'      => $rec['cms_collect_cat_n'],
		'post_status'         => 'publish',
		'ignore_sticky_posts' => true,
		'orderby'             => 'date',
		'order'               => 'DESC',
	);

	$query = new WP_Query( $args );

	if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
?>
	<article id="post-<?php the_ID(); ?>" class="collect-item <?php if ( ! empty( $rec['cms_collect_cat_img'] ) ) { ?>collect-img<?php } else { ?>collect-list<?php } ?>" <?php aos_a(); ?>>
		<?php if ( ! empty( $rec['cms_collect_cat_img'] ) ) { ?>
			<figure class="thumbnail">
				<?php echo zm_thumbnail(); ?>
			</figure>
		<?php } ?>
		<div class="collect-area">
			<h2 class="collect-title"><a href="<?php the_permalink(); ?>" rel="bookmark" <?php echo $goal; ?>><?php the_title(); ?></a></h2>
			<div class="collect-meta"><?php echo collect_meta(); ?></div>
		</div>
	</article>
	<div class="collect-loading"><span class="dual-ring"></span><span class="dual-ring"></span></div>
<?php
	endwhile;endif;
	wp_reset_postdata();
	die();
}
add_action( 'wp_ajax_load_cat', 'cms_collect_cat' );
add_action( 'wp_ajax_nopriv_load_cat', 'cms_collect_cat' );


// 会员
function cms_collect_asset() {
	$rec = ( array ) be_get_option( 'rectab' );
	$post_types = array_diff( get_post_types( array( 'public' => true ) ), array( 'page' ) );
	$days = $rec['cms_collect_asset_days'];
	$orderby = ( $rec['cms_collect_asset_key'] == 'down_price' ) ? 'date' : 'meta_value_num';
	$goal    = ! empty($rec['cms_collect_asset_blank']) ? 'target="_blank"' : '';
	if ( is_plugin_active( 'erphpdown/erphpdown.php' ) ) {
		$meta_key = $rec['cms_collect_asset_key'];
	} else {
		$meta_key = $rec['cms_collect_asset_zan'];
	}
	$args = array(
		'post_type'           => $post_types,
		'posts_per_page'      => $rec['cms_collect_asset_n'],
		'post_status'         => 'publish',
		'ignore_sticky_posts' => true,
		'orderby'             => 'date',
		'order'               => 'DESC',
		'meta_query' => array(
			array(
				'key'     => $meta_key,
				'value'   => 0,
				'compare' => '>',
				'type'    => 'numeric'
			)
		),

		'date_query'          => array(
			array(
				'after'       => $days . ' days ago'
			)
		)
	);

	$query = new WP_Query($args);

	if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
?>
	<article id="post-<?php the_ID(); ?>" class="collect-item <?php if ( ! empty( $rec['cms_collect_asset_img'] ) ) { ?>collect-img<?php } else { ?>collect-list<?php } ?>" <?php aos_a(); ?>>
		<?php if ( ! empty( $rec['cms_collect_asset_img'] ) ) { ?>
			<figure class="thumbnail">
				<?php echo zm_thumbnail(); ?>
			</figure>
		<?php } ?>
		<div class="collect-area">
			<h2 class="collect-title"><a href="<?php the_permalink(); ?>" rel="bookmark" <?php echo $goal; ?>><?php the_title(); ?></a></h2>
			<div class="collect-meta"><?php echo collect_meta_vip(); ?></div>
		</div>
	</article>
	<div class="collect-loading"><span class="dual-ring"></span><span class="dual-ring"></span></div>
<?php
	endwhile;endif;
	wp_reset_postdata();
	die();
}

add_action( 'wp_ajax_load_asset', 'cms_collect_asset' );
add_action( 'wp_ajax_nopriv_load_asset', 'cms_collect_asset' );

// 问答
function cms_collect_qa() {
	$rec = ( array ) be_get_option( 'rectab' );
	$goal = ! empty($rec['cms_collect_qa_blank']) ? 'target="_blank"' : '';
	$args = array(
		'post_type'           => 'question',
		'posts_per_page'      => $rec['cms_collect_qa_n'],
		'post_status'         => 'publish',
		'ignore_sticky_posts' => true,
		'orderby'             => 'date',
		'order'               => 'DESC',
	);

	$query = new WP_Query( $args );

	if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
?>
	<article id="post-<?php the_ID(); ?>" class="collect-item collect-item-qa <?php if ( ! empty( $rec['cms_collect_qa_img'] ) ) { ?>collect-img<?php } else { ?>collect-list<?php } ?>" <?php aos_a(); ?>>
		<?php if ( ! empty( $rec['cms_collect_qa_img'] ) ) { ?>
			<figure class="thumbnail">
				<?php echo zm_thumbnail(); ?>
			</figure>
		<?php } ?>
		<div class="collect-area">
			<h2 class="collect-title"><a href="<?php the_permalink(); ?>" rel="bookmark" <?php echo $goal; ?>><i class="be be-skyatlas"></i> <?php the_title(); ?></a></h2>
			<div class="collect-meta collect-meta-qa"><?php echo collect_meta_qa(); ?></div>
		</div>
	</article>
	<div class="collect-loading"><span class="dual-ring"></span><span class="dual-ring"></span></div>
<?php
	endwhile;endif;
	wp_reset_postdata();
	die();
}

add_action( 'wp_ajax_load_qa', 'cms_collect_qa' );
add_action( 'wp_ajax_nopriv_load_qa', 'cms_collect_qa' );