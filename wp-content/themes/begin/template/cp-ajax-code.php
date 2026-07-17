<?php
function cp_ajax_post_item_shortcode( $atts, $content = null ) {
	$posts_per_page = ( get_option( 'posts_per_page', true ) ) ? get_option( 'posts_per_page', true ) : 10;
	$shortcode_atts = shortcode_atts(
		array(
			'show_filter'    => "yes",
			'btn'            => "yes",
			'btn_all'        => "yes",
			'initial'        => "-1",
			'layout'         => '1',
			'post_type'      => 'post',
			'posts_per_page' => $posts_per_page,
			'cat'            => '',
			'terms'          => '',
			'paginate'       => 'no',
			'hide_empty'     => 'true',
			'orderby'        => 'date',
			'order'          => 'DESC',
			'meta_key'       => '', 
			'more'           => '',
			'style'          => 'photo',
			'column'         => '',
			'infinite'       => '',
			'animation'      => '',
			'item_id'        => '',
			'prev_next'      => 'true',
			'img'            => '',
			'sticky'            => '',
		),
		$atts
	);

	// 获取组数
	extract( $shortcode_atts );
	ob_start();

	// 分类
	$taxonomy = 'category';

	$args = array(
		'hide_empty' => $hide_empty,
		'taxonomy'   => $taxonomy,
		'orderby'    => 'menu_order',
		'order'      => 'ASC',
		'include'    => $terms ? $terms : $cat,
	);

	$terms = get_terms( $args ); ?>

	<div class='clear'></div>
	<div class="apc-ajax-post-item-wrap ajax-cat-post-wrap" data-more="<?php echo esc_attr( $more ); ?>" data-apc-ajax-post-item='<?php echo json_encode( $shortcode_atts ); ?>'>
		<?php if ( $show_filter == "yes" && $terms && ! is_wp_error( $terms ) ) { ?>
			<div class="acx-filter-div all-sidebar<?php if ( zm_get_option( 'ajax_cat_btn_flow' ) &&  wp_is_mobile() ) { ?> acx-filter-div-flow<?php } ?><?php if ( $btn == 'no' ) { ?> acx-btn-no<?php } ?>" data-layout="<?php echo $layout; ?>">
				<ul>
					<?php if ( $btn_all != "no" ) : ?>
						<li class="bea-texonomy ms apc-all-btu active" data_id="-1" <?php aos_a(); ?>><i class="be be-sort"></i><?php _e( '全部', 'begin' ); ?></li>
					<?php endif; ?>
					<?php foreach( $terms as $term ) { ?>
						<li class="bea-texonomy ms apc-cat-btu" data_id="<?php echo $term->term_id; ?>" <?php aos_a(); ?>><?php echo $term->name; ?></li>
					<?php } ?>
				</ul>
			</div>
		<?php } ?>

		<div class="acx-ajax-container">
			<div class="acx-loader">
				<div class="dual-ring"></div>
			</div>
			<div class="beall-filter-result">
				<?php echo cp_ajax_post_item_output( cp_ajax_get_shortcode_atts( $shortcode_atts ) ); ?>
			</div>
		</div>
	</div>

	<?php return ob_get_clean();
}
add_shortcode( 'cp_ajax_post','cp_ajax_post_item_shortcode' );

// 判断
function cp_ajax_get_shortcode_atts( $jsonData ) {

	if ( isset( $jsonData['posts_per_page'] ) ){
		$data['posts_per_page'] = intval( $jsonData['posts_per_page'] );
	}

	if ( isset( $jsonData['orderby'] ) ) {
		$data['orderby'] = sanitize_text_field( $jsonData['orderby'] );
	}

	if ( isset( $jsonData['meta_key'] ) ) {
		$data['meta_key'] = sanitize_text_field( $jsonData['meta_key'] );
	}

	if ( isset( $jsonData['order'] ) ) {
		$data['order'] = sanitize_text_field( $jsonData['order'] );
	}

	if ( isset( $jsonData['more'] ) ) {
		$data['more'] = sanitize_text_field( $jsonData['more'] );
	}

	if ( isset( $jsonData['img'] ) ) {
		$data['img'] = sanitize_text_field( $jsonData['img'] );
	}

	if ( isset( $jsonData['sticky'] ) ) {
		$data['sticky'] = sanitize_text_field( $jsonData['sticky'] );
	}

	if ( isset( $jsonData['prev_next'] ) ) {
		$data['prev_next'] = sanitize_text_field( $jsonData['prev_next'] );
	}

	if ( isset( $jsonData['style'] ) ) {
		$data['style'] = sanitize_text_field( $jsonData['style'] );
	}

	if ( isset( $jsonData['column'] ) ) {
		$data['column'] = sanitize_text_field( $jsonData['column'] );
	}

	if ( isset( $jsonData['animation'] ) && $jsonData['animation'] == "true" ) {
		$data['animation'] = 'apc-has-animation';
	}

	if ( isset( $jsonData['infinite'] ) && $jsonData['infinite'] == "true" ) {
		$data['infinite'] = 'infinite-scroll';
	}

	$terms   = '';
	if ( isset( $jsonData['cat'] ) && ! empty( $jsonData['cat'] ) ) {
		$terms = explode( ',', $jsonData['cat'] );
	} elseif ( isset( $jsonData['terms'] ) && ! empty( $jsonData['terms'] ) ) {
		$terms = explode( ',', $jsonData['terms'] );
	}

	// Tax Query
	if ( ! empty( $terms ) ) {
		$data['tax_query'] = [
			'category' => $terms,
		];
	}

	return $data;
}

// Ajax
add_action( 'wp_ajax_be_ajax_filter_posts', 'cp_ajax_post_item_functions' );
add_action( 'wp_ajax_nopriv_be_ajax_filter_posts', 'cp_ajax_post_item_functions' );

// Load Posts
function cp_ajax_post_item_functions() {
	$data = [];
	$term_ID = isset( $_POST['term_ID'] ) ? sanitize_text_field( intval($_POST['term_ID']) ) : '';

	if ( isset( $_POST[ 'paged' ] ) ? $_POST[ 'paged' ] : '' ) {
		$dataPaged = intval( $_POST['paged'] );
	} else {
		$dataPaged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
	}

	$jsonData = json_decode( str_replace( '\\', '', $_POST['jsonData'] ), true );
	
	// 合并数据
	$data = array_merge( cp_ajax_get_shortcode_atts( $jsonData ), $data );

	if ( isset( $_POST['paged'] ) ) {
		$data['paged'] = sanitize_text_field( $_POST['paged'] );
	}

	if ( ! empty( $term_ID ) && $term_ID != -1 ) {
		$data['tax_query'] = [
			'category' => [$term_ID],
		];
	}

	echo cp_ajax_post_item_output( $data );
	die();
}

// 文章
function cp_ajax_post_item_output( $args = [] ) {
	$args = wp_parse_args( $args, [
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'paged'          => get_query_var('paged') ? get_query_var('paged') : 1,
		'posts_per_page' => '',
		'meta_key'       => '', 
		'orderby'        => '',
		'order'          => '',
		'layout'         => '1',
		'more'           => '',
		'img'            => '',
		'prev_next'      => 'true',
		'style'          => '',
		'column'         => '',
		'animation'      => '',
		'infinite'       => '',
		'tax_query'      => [
		'category'       => []
		],
	]);

	if ( $args['sticky'] == "" ) {
		$sticky   = '1';
	} else {
		$sticky   = '0';
	}

	$query_args = array(
		'post_type'    => 'post',
		'post_status'  => 'publish',
		'paged'        => $args['paged'],
		'ignore_sticky_posts' => $sticky,
	);

	if ( ! empty( $args['posts_per_page'] ) ) {
		$query_args['posts_per_page'] = intval( $args['posts_per_page'] );
	}

	if ( ! empty( $args['meta_key'] ) ) {
		$query_args['meta_key'] = sanitize_text_field( $args['meta_key'] );
	}

	if ( ! empty( $args['orderby'] ) ) {
		$query_args['orderby'] = sanitize_text_field( $args['orderby'] );
	}

	if ( ! empty( $args['order'] ) ) {
		$query_args['order'] = sanitize_text_field( $args['order'] );
	}

	$column     = sanitize_text_field( $args['column'] );
	$style      = sanitize_text_field( $args['style'] );
	$prev_next  = sanitize_text_field( $args['prev_next'] );
	$more       = sanitize_text_field( $args['more'] );
	$img        = sanitize_text_field( $args['img'] );
	$dataPaged  = sanitize_text_field( $args['paged'] );

	$tax_query = [];

	if ( ! empty( $args['tax_query'] ) && is_array( $args['tax_query'] ) ) {
		foreach ( $args['tax_query'] as $taxonomy => $terms ) {
			if ( ! empty( $terms ) ) {
				$tax_query[] =[
					'taxonomy' => $taxonomy,
					'field'    => 'term_id',
					'terms'    => $terms,
					'include_children' => false,
				];
			}
		}
	}

	if ( ! empty( $tax_query ) ) {
		$query_args['tax_query'] = $tax_query;
	}

	// 文章查询
	$query = new WP_Query( $query_args );
	ob_start();

	echo ( $more == 'more' ) ? '<div class="apc-postitem-wrapper">' : '';
	?>
		<?php if ( $style == 'grid' ) { ?>
			<section class="apc-grid-box content-area apc-grid-cat-<?php if ( get_post_meta( get_the_ID(), 'cp_column', true ) ){ ?><?php echo get_post_meta( get_the_ID(), 'cp_column', true ); ?><?php } else { ?>3<?php } ?>">
				<div class="<?php echo esc_attr( "apc-post-item apc_layout_{$args['layout']} {$args['animation']}" ); ?>">
					<?php while( $query->have_posts() ) : $query->the_post(); ?>
						<?php if ( $args['layout'] == "1" ) { ?>
							<div class="apc-grid-item">
								<article id="post-<?php the_ID(); ?>" class="post-item-list post ms" <?php aos_a(); ?>>
									<figure class="apc-grid-thumbnail thumbnail gdz">
										<?php echo zm_thumbnail(); ?>
									</figure>
									<header class="apc-grid-header">
										<?php the_title( sprintf( '<h2 class="apc-grid-cat-title over gdz"><a href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
									</header>
									<div class="apc-grid-content">
										<span class="apc-grid-meta lbm gdz">
											<span class="date"><?php time_ago( $time_type ='post' ); ?>&nbsp;</span>
											<?php 
												if ( post_password_required() ) { 
													echo '<span class="comment"><a href=""><i class="icon-scroll-c ri"></i>' . sprintf( __( '密码保护', 'begin' ) ) . '</a></span>';
												} else {
													if ( ! zm_get_option('close_comments' ) ) {
														echo '<span class="comment">';
															comments_popup_link( '<span class="no-comment"><i class="be be-speechbubble ri"></i>' . sprintf( __( '评论', 'begin' ) ) . '</span>', '<i class="be be-speechbubble ri"></i>1 ', '<i class="be be-speechbubble ri"></i>%' );
														echo '</span>';
													}
												}
											?>
											<?php views_span(); ?>
										</span>
									</div>
									<div class="clear"></div>
								</article>
							</div>
						<?php } else if ( $args['layout'] == 2 ) { ?>
						<?php } ?>
					<?php endwhile; ?>
					<div class="clear"></div>
				</div>
		<?php } ?>

		<?php if ( $style == 'default' ) { ?>
			<section class="apc-content-area-norm">
			<div class="<?php echo esc_attr( "apc-post-item apc_layout_{$args['layout']} {$args['animation']}" ); ?>">
				<?php while( $query->have_posts() ) : $query->the_post(); ?>
					<?php if ( $args['layout'] == "1" ) { ?>
						<?php get_template_part( 'template/content', get_post_format() ); ?>
						<?php if ( zm_get_option( 'ad_a' ) ) { ?>
							<?php if ( $query->current_post == 1 ) : ?>
								<div class="tg-box<?php if (zm_get_option('post_no_margin')) { ?> upclose<?php } ?>" <?php aos_a(); ?>>
									<?php if ( wp_is_mobile() ) { ?>
										 <?php if ( zm_get_option('ad_a_c_m') ) { ?><div class="tg-m tg-site"><?php echo stripslashes( zm_get_option('ad_a_c_m') ); ?></div><?php } ?>
									<?php } else { ?>
										 <?php if ( zm_get_option('ad_a_c') ) { ?><div class="tg-pc tg-site"><?php echo stripslashes( zm_get_option('ad_a_c') ); ?></div><?php } ?>
									<?php } ?>
								</div>
							<?php endif; ?>
						<?php } ?>
					<?php } else if ( $args['layout'] == 2 ) { ?>
					<?php } ?>
				<?php endwhile; ?>
			</div>
		<?php } ?>

		<?php if ( $style == 'photo' ){ ?>
		<section class="picture-area content-area grid-cat-<?php if ( get_post_meta( get_the_ID(), 'cp_column', true ) ){ ?><?php echo get_post_meta( get_the_ID(), 'cp_column', true ); ?><?php } else { ?>4<?php } ?>">
			<div class="<?php echo esc_attr( "apc-post-item apc_layout_{$args['layout']} {$args['animation']}" ); ?>">
				<?php while( $query->have_posts() ) : $query->the_post(); ?>
					<?php if ( $args['layout'] == "1" ) { ?>
						<article id="post-<?php the_ID(); ?>" class="post-item-list post picture scl" <?php aos_a(); ?>>
							<div class="picture-box sup ms">
								<figure class="picture-img gdz">
									<?php if ( $args['img'] == "1" ) { ?>
										<?php echo zm_grid_thumbnail(); ?>
									<?php } else { ?>
										<?php echo zm_thumbnail(); ?>
									<?php } ?>
								</figure>
								<?php the_title( sprintf( '<h2 class="grid-title gdz"><a href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
								<?php grid_inf(); ?>
								<div class="clear"></div>
							</div>
						</article>
					<?php } else if ( $args['layout'] == 2 ) { ?>
					<?php } ?>
				<?php endwhile; ?>
			</div>
		<?php } ?>

		<?php if ( $style == 'list' ) { ?>
			<section class="apc-list-box content-area cp-list-box apc-title-cat-1">
				<div class="<?php echo esc_attr( "apc-post-item apc_layout_{$args['layout']} {$args['animation']}" ); ?>">
					<?php while( $query->have_posts() ) : $query->the_post(); ?>
						<?php if ( $args['layout'] == "1" ) { ?>
							<div class="apc-list-item">
								<article id="post-<?php the_ID(); ?>" class="post-item-list post doclose scl" <?php aos_a(); ?>>
									<span class="archive-list-inf"><time datetime="<?php echo get_the_date( 'Y-m-d' ); ?> <?php echo get_the_time('H:i:s'); ?>"><?php the_time( 'm/d' ) ?></time></span>
									<?php the_title( sprintf( '<h2 class="apc-list-entry-title"><a class="srm" href="%s" rel="bookmark" ' . goal() . '>' . t_mark(), esc_url( get_permalink() ) ), '</a></h2>' ); ?>
								</article>
							</div>
						<?php } else if ( $args['layout'] == 2 ) { ?>
						<?php } ?>
					<?php endwhile; ?>
				</div>
		<?php } ?>

		<div class="clear"></div>
		<div class="apc-posts-navigation" <?php aos_b(); ?>>
			<?php
				if ( $args['prev_next'] == "false" ) {
					$prevnext = false;
				} else {
					$prevnext = true;
				}

				$big            = 999999999;
				$dataPrev       = $dataPaged-1;
				$dataNext       = $dataPaged+1;
				$paged          = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;
				$paginate_links = paginate_links( array(
					'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
					'format'    => '?paged=%#%',
					'current'   => max( 1, $dataPaged ),
					'prev_next' => $prevnext,
					'prev_text' => '<span>' . $dataPrev . '</span>',
					'next_text' => '<span>' . $dataNext . '</span>',
					'mid_size'  => zm_get_option( 'mid_size' ),
					'total'     => $query->max_num_pages
				) );

				if ( $more == 'more' ) {
					if ( $paginate_links && $dataPaged < $query->max_num_pages ){
						echo "<div class='clear ajax-navigation'></div><div data-paged='{$dataPaged}' data-next='{$dataNext}' class='{$args['infinite']} apc-post-item-load-more'><span class='apc-load-more'><i class='be be-more'></i></span></div>";
					}
				} else {
					echo "<div class='clear'></div><div class='apc-navigation'>{$paginate_links}</div>";
				}
			?>
			<div class='clear'></div>
		</div>
	</section>
	<?php
	wp_reset_postdata();

	echo ( $more == 'more' ) ? '</div>' : '';

	return ob_get_clean();
}