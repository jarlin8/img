<?php
function be_ajax_post_item_shortcode( $atts, $content = null ) {
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
			'nav'            => '',
			'mid'            => '',
			'style'          => 'photo',
			'boxs'           => 'boxs',
			'listimg'        => '',
			'column'         => '',
			'infinite'       => '',
			'animation'      => '',
			'item_id'        => '',
			'slider'         => '',
			'tags'           => '',
			'sites'          => '',
			'special'        => '',
			'prev_next'      => 'true',
			'img'            => '',
			'sticky'         => '',
			'top'            => '',
			'children'       => 'true',
			'author'         => '',
			'name'           => '',
			'moretext'       => '',
		),
		$atts
	);

	// 获取组数
	extract( $shortcode_atts );
	ob_start();

	// 分类
	if ( $special == 'true' ) {
		$taxonomy = 'special';
	}
	elseif ( $tags == 'tag' ) {
		$taxonomy = 'post_tag';
	}
	elseif ( $sites == 'true' ) {
		$taxonomy = 'favorites';
	} else { 
		$taxonomy = 'category';
	}

	$args = array(
		'hide_empty' => $hide_empty,
		'taxonomy'   => $taxonomy,
		'orderby'    => 'menu_order',
		'order'      => 'ASC',
		'include'    => $terms ? $terms : $cat,
	);

	$terms = get_terms( $args ); ?>

	<?php
		if ( $slider ) {
			global $wpdb, $post;
			require get_template_directory() . '/template/slider.php';
		}
	?>

	<div class="apc-ajax-post-item-wrap ajax-cat-post-wrap" data-more="<?php echo esc_attr( $more ); ?>" data-apc-ajax-post-item='<?php echo json_encode( $shortcode_atts ); ?>'>
		<?php if ( $show_filter == "yes" && $terms && ! is_wp_error( $terms ) ) { ?>
			<?php if ( $name == 'true' ) { ?>
				<div class="acx-cat-title<?php if ( zm_get_option( 'ajax_cat_btn_flow' ) &&  wp_is_mobile() ) { ?> acx-filter-div-flow<?php } ?><?php if ( $btn == 'no' ) { ?> acx-btn-no<?php } ?>" data-layout="<?php echo $layout; ?>">
					<?php foreach( $terms as $term ) {
						// 无文章不显示
						if ( $term->count == 0 ) {
							continue;
						}
						// 文章数
						$query = new WP_Query(array(
							'tax_query' => array(
								array(
									'taxonomy' => 'favorites',
									'terms'    => $term->term_id,
								),
							),
							'fields' => 'ids',
							'posts_per_page' => -1
						));
					?>
						<div id="turn<?php echo $term->term_id; ?>" class="group-title" data_id="<?php echo $term->term_id; ?>" <?php aos_a(); ?>>
							<h3 class="sites-cat-name"><a href="<?php echo esc_url( get_term_link( $term ) ); ?>" target="_blank" <?php echo goal(); ?>><?php echo $term->name; ?></a></h3>
							<?php if ( cx_get_option( 'sites_count' ) ) { ?>
								<div class="sites-posts-count"><?php _e( '收录', 'begin' ); ?>&nbsp;<?php echo $query->found_posts; ?></div>
							<?php } ?>
							<?php if ( cx_get_option( 'sites_des' ) ) { ?>
								<?php if ( ! empty( $term->description ) ) { ?>
								<div class="sites-cat-des">
									<?php echo $term->description; ?>
								</div>
								<?php } ?>
							<?php } ?>
							<div class="bea-texonomy"></div>
							<div class="clear"></div>
						</div>
					<?php } ?>
				</div>
			<?php } else { ?>
				<div class="acx-filter-div<?php if ( zm_get_option( 'ajax_cat_btn_flow' ) &&  wp_is_mobile() ) { ?> acx-filter-div-flow<?php } ?><?php if ( $btn == 'no' ) { ?> acx-btn-no<?php } ?>" data-layout="<?php echo $layout; ?>">
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
		<?php } ?>

		<div class="acx-ajax-container">
			<div class="acx-loader">
				<div class="dual-ring"></div>
			</div>
			<div class="beall-filter-result">
				<?php echo be_ajax_post_item_output( be_ajax_get_shortcode_atts( $shortcode_atts ) ); ?>
			</div>
		</div>
	</div>

	<?php return ob_get_clean();
}
add_shortcode( 'be_ajax_post','be_ajax_post_item_shortcode' );

// 判断
function be_ajax_get_shortcode_atts( $jsonData ) {

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

	if ( isset( $jsonData['moretext'] ) ) {
		$data['moretext'] = sanitize_text_field( $jsonData['moretext'] );
	}

	if ( isset( $jsonData['mid'] ) ) {
		$data['mid'] = sanitize_text_field( $jsonData['mid'] );
	}

	if ( isset( $jsonData['img'] ) ) {
		$data['img'] = sanitize_text_field( $jsonData['img'] );
	}

	if ( isset( $jsonData['sticky'] ) ) {
		$data['sticky'] = sanitize_text_field( $jsonData['sticky'] );
	}

	if ( isset( $jsonData['top'] ) ) {
		$data['top'] = sanitize_text_field( $jsonData['top'] );
	}

	if ( isset( $jsonData['prev_next'] ) ) {
		$data['prev_next'] = sanitize_text_field( $jsonData['prev_next'] );
	}

	if ( isset( $jsonData['style'] ) ) {
		$data['style'] = sanitize_text_field( $jsonData['style'] );
	}

	if ( isset( $jsonData['boxs'] ) ) {
		$data['boxs'] = sanitize_text_field( $jsonData['boxs'] );
	}

	if ( isset( $jsonData['nav'] ) ) {
		$data['nav'] = sanitize_text_field( $jsonData['nav'] );
	}

	if ( isset( $jsonData['listimg'] ) ) {
		$data['listimg'] = sanitize_text_field( $jsonData['listimg'] );
	}

	if ( isset( $jsonData['tags'] ) ) {
		$data['tags'] = sanitize_text_field( $jsonData['tags'] );
	}

	if ( isset( $jsonData['sites'] ) ) {
		$data['sites'] = sanitize_text_field( $jsonData['sites'] );
	}

	if ( isset( $jsonData['special'] ) ) {
		$data['special'] = sanitize_text_field( $jsonData['special'] );
	}

	if ( isset( $jsonData['column'] ) ) {
		$data['column'] = sanitize_text_field( $jsonData['column'] );
	}

	if ( isset( $jsonData['children'] ) ) {
		$data['children'] = sanitize_text_field( $jsonData['children'] );
	}

	if ( isset( $jsonData['animation'] ) && $jsonData['animation'] == "true" ) {
		$data['animation'] = 'apc-has-animation';
	}

	if ( isset( $jsonData['infinite'] ) && $jsonData['infinite'] == "true" ) {
		$data['infinite'] = 'infinite-scroll';
	}

	$special = '';
	$tags    = '';
	$sites   = '';
	$terms   = '';
	if ( isset( $jsonData['cat'] ) && ! empty( $jsonData['cat'] ) ) {
		$terms = explode( ',', $jsonData['cat'] );
	} elseif ( isset( $jsonData['terms'] ) && ! empty( $jsonData['terms'] ) ) {
		$terms = explode( ',', $jsonData['terms'] );
	}

	// Tax Query
	if ( $special == 'true' ) {
		if ( ! empty( $terms ) ) {
			$data['tax_query'] = [
				'special' => $terms,
			];
		}
	} 
	elseif ( $tags == 'tag' ) {
		if ( ! empty( $terms ) ) {
			$data['tax_query'] = [
				'post_tag' => $terms,
			];
		}
	}
	elseif ( $sites == 'true' ) {
		if ( ! empty( $terms ) ) {
			$data['tax_query'] = [
				'taxonomy' => $terms,
			];
		}
	} else { 
		if ( ! empty( $terms ) ) {
			$data['tax_query'] = [
				'category' => $terms,
			];
		}
	}

	if ( $sites == 'true' ) {
		$post_type = 'sites';
	} else {
		$post_type = 'post';
	}

	return $data;
}

// Ajax
add_action( 'wp_ajax_be_ajax_filter_posts', 'be_ajax_post_item_functions' );
add_action( 'wp_ajax_nopriv_be_ajax_filter_posts', 'be_ajax_post_item_functions' );

// Load Posts
function be_ajax_post_item_functions() {
	$data = [];
	$term_ID = isset( $_POST['term_ID'] ) ? sanitize_text_field( intval($_POST['term_ID']) ) : '';

	if ( isset( $_POST[ 'paged' ] ) ? $_POST[ 'paged' ] : '' ) {
		$dataPaged = intval( $_POST['paged'] );
	} else {
		$dataPaged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
	}

	$jsonData = json_decode( str_replace( '\\', '', $_POST['jsonData'] ), true );
	
	// 合并数据
	$data = array_merge( be_ajax_get_shortcode_atts( $jsonData ), $data );

	if ( isset( $_POST['paged'] ) ) {
		$data['paged'] = sanitize_text_field( $_POST['paged'] );
	}

	if ( ! empty( $term_ID ) && $term_ID != -1 ) {
		$data['tax_query'] = [
			'category' => [$term_ID],
		];
	}

	echo be_ajax_post_item_output( $data );
	die();
}

// 文章
function be_ajax_post_item_output( $args = [] ) {
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
		'moretext'       => '',
		'mid'            => '',
		'img'            => '',
		'prev_next'      => 'true',
		'style'          => '',
		'boxs'          => '',
		'nav'            => '',
		'listimg'        => '',
		'tags'           => '',
		'sites'          => '',
		'special'        => '',
		'column'         => '',
		'animation'      => '',
		'infinite'       => '',
		'tax_query'      => [
		'category'       => []
		],
	]);

	$column     = sanitize_text_field( $args['column'] );
	$style      = sanitize_text_field( $args['style'] );
	$boxs       = sanitize_text_field( $args['boxs'] );
	$nav        = sanitize_text_field( $args['nav'] );
	$listimg    = sanitize_text_field( $args['listimg'] );
	$tags       = sanitize_text_field( $args['tags'] );
	$sites      = sanitize_text_field( $args['sites'] );
	$special    = sanitize_text_field( $args['special'] );
	$prev_next  = sanitize_text_field( $args['prev_next'] );
	$children   = sanitize_text_field( $args['children'] );
	$more       = sanitize_text_field( $args['more'] );
	$moretext   = sanitize_text_field( $args['moretext'] );

	$mid        = sanitize_text_field( $args['mid'] );
	$img        = sanitize_text_field( $args['img'] );
	$dataPaged  = sanitize_text_field( $args['paged'] );

	if ( $args['sticky'] == "" ) {
		$sticky = '1';
	} else {
		$sticky = '0';
	}

	if ( is_single() ) {
		$itself = get_the_ID();
	} else {
		$itself = '';
	}

	if ( is_author() ) {
		$author_id = get_queried_object()->ID;
	} else {
		$author_id  = '';
	}

	$post_type  = '';

	$query_args = array(
		'post_type'    => $post_type,
		'post_status'  => 'publish',
		'author'       => $author_id,
		'paged'        => $args['paged'],
		'post__not_in' => array( $itself ),
		'ignore_sticky_posts' => $sticky,
	);

	// 网址排序
	if ( $sites == 'true' ) {
		$query_args['meta_key'] = 'sites_order';
		$query_args['orderby']  = 'meta_value';
		$query_args['order']    = 'DESC';
	}

	$imglist_args = array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'paged'          => $args['paged'],
		'posts_per_page' => '4',
		'ignore_sticky_posts' => true,
	);

	// 排除
	global $wpdb, $post; $do_not_duplicate[] = '';
	if ( be_get_option( 'news' ) ) {
		$sticky = be_get_option( 'news_grid_sticky' ) ? '0' : '1';
		$notcat = be_get_option( 'not_news_n' ) ? implode( ',', be_get_option( 'not_news_n') ) : '';
		$top_id = be_get_option( 'cms_top' ) ? explode( ',', be_get_option( 'cms_top_id' ) ) : '';

		$news = array(
			'posts_per_page'      => be_get_option( 'news_n' ),
			'category__not_in'    => explode( ',', $notcat ),
			'post__not_in'        => $top_id,
			'ignore_sticky_posts' => $sticky,
		);

		$recent = new WP_Query($news);
		while($recent->have_posts()) : $recent->the_post(); $do_not_duplicate[] = $post->ID;
		endwhile;
		wp_reset_postdata();
	}

	if ( $args['top'] == 1 ) {
		$top_id = be_get_option( 'cms_top' ) ? explode( ',', be_get_option( 'cms_top_id' ) ) : [];
		$exclude_posts = array_merge( $do_not_duplicate, $top_id );
		$query_args['post__not_in'] = $exclude_posts;
		$imglist_args['post__not_in'] = $exclude_posts;
	}
	// 排除结束

	if ( ! empty( $args['posts_per_page'] ) ) {
		$query_args['posts_per_page'] = intval( $args['posts_per_page'] );
	}

	if ( ! empty( $args['meta_key'] ) ) {
		$query_args['meta_key'] = sanitize_text_field( $args['meta_key'] );
	}

	if ( ! empty( $args['orderby'] ) ) {
		if ( $sites !== 'true' ) {
			$query_args['orderby'] = sanitize_text_field( $args['orderby'] );
		}
	}

	if ( ! empty( $args['order'] ) ) {
		$query_args['order'] = sanitize_text_field( $args['order'] );
	}

	$tax_query = [];

	if ( ! empty( $args['tax_query'] ) && is_array( $args['tax_query'] ) ) {
		foreach ( $args['tax_query'] as $taxonomy => $terms ) {
			if ( $special == 'true' ) {
				if ( ! empty( $terms ) ) {
					$tax_query[] =[
						'taxonomy' => 'special',
						'field'    => 'term_id',
						'terms'    => $terms,
					];
				}
			} 
			elseif ( $tags == 'tag' ) {
				if ( ! empty( $terms ) ) {
					$tax_query[] =[
						'taxonomy' => 'post_tag',
						'field'    => 'term_id',
						'terms'    => $terms,
					];
				}
				}
			elseif ( $sites == 'true' ) {

				if ( ! empty( $terms ) ) {
					$tax_query[] =[
						'taxonomy' => 'favorites',
						'field'    => 'term_id',
						'terms'    => $terms,
						'include_children' => false,
					];
				}
			} else { 
				if ( ! empty( $terms ) ) {
					if ( $children == 'true' ) {
						$children   = true;
					} else {
						$children   = false;
					}

					$tax_query[] =[
						'taxonomy' => $taxonomy,
						'field'    => 'term_id',
						'terms'    => $terms,
						'include_children' => $children,
					];
				}
			}
		}
	}

	if ( ! empty( $tax_query ) ) {
		$query_args['tax_query'] = $tax_query;
		$imglist_args['tax_query'] = $tax_query;
	}

	// 文章查询
	$query = new WP_Query( $query_args );
	$imglist = new WP_Query( $imglist_args );
	ob_start();

	echo ( $more == 'full' ) ? '<div class="apc-postitem-wrapper">' : '';
	echo ( $more == 'more' ) ? '<div class="apc-postitem-wrapper">' : '';
	?>
	<?php if ( $style == 'title' ) { ?>
		<section class="apc-title-box content-area apc-title-cat-<?php if ( $column ){ ?><?php echo $column; ?><?php } else { ?>2<?php } ?>">
			<div class="<?php echo esc_attr( "apc-post-item apc_layout_{$args['layout']} {$args['animation']}" ); ?>">
				<?php while( $query->have_posts() ) : $query->the_post(); ?>
					<?php if ( $args['layout'] == "1" ){ ?>
						<div class="apc-title-item">
							<a class="apc-title-cat-title" href="<?php echo get_permalink(); ?>" rel="bookmark">
								<div class="boxs<?php if ( $boxs == 'boxs' ) { ?> boxs1<?php } ?>">
									<article id="post-<?php the_ID(); ?>" class="post-item-list post ms" <?php aos_a(); ?>>
										<div class="apc-title-date-box">
											<div class="apc-title-date">
												<div class="apc-title-date-main gdz">
													<time datetime="<?php echo get_the_date( 'Y-m-d' ); ?> <?php echo get_the_time( 'H:i:s' ); ?>">
														<div class="apc-title-day"><?php echo the_time( 'd' ); ?></div>
														<div class="apc-title-moon"><?php echo the_time( 'm' ); ?></div>
													</time>
												</div>
												<div class="apc-title-meta lbm">
													<?php views_span(); ?>
												</div>
											</div>
										</div>
										<header class="apc-title-header">
											<h2 class="apc-grid-cat-title-h2 over"><span class="gdm"><?php the_title(); ?></span></h2>
										</header>
										<div class="clear"></div>
									</article>
								</div>
							</a>
						</div>
					<?php } else if ( $args['layout'] == 2 ) { ?>
					<?php } ?>
				<?php endwhile; ?>
			</div>
		<?php } ?>

		<?php if ( $style == 'list' ) { ?>
			<section class="apc-list-box content-area apc-title-cat-1">
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

		<?php if ( $style == 'imglist' ) { ?>
			<section class="apc-imglist-box content-area apc-imglist-cat">
				<?php if ( be_get_option( 'cms_cat_tab_img' ) && $listimg == 'yes' ) { ?>
					<div class="picture-area content-area grid-cat-4">
						<div class="<?php echo esc_attr( "apc-post-item apc_layout_{$args['layout']} {$args['animation']}" ); ?>">
							<?php while( $imglist->have_posts() ) : $imglist->the_post(); ?>
								<?php if ( $args['layout'] == "1" ) { ?>
									<article id="post-<?php the_ID(); ?>" class="post-item-list post picture scl" <?php aos_b(); ?>>
										<div class="picture-box">
											<figure class="picture-img gdz">
												<?php if ( $args['img'] == "1" ) { ?>
													<?php echo zm_grid_thumbnail(); ?>
												<?php } else { ?>
													<?php echo zm_thumbnail(); ?>
												<?php } ?>
											</figure>
											<div class="clear"></div>
										</div>
									</article>
								<?php } else if ( $args['layout'] == 2 ) { ?>
								<?php } ?>
							<?php endwhile; ?>
							<div class="clear"></div>
						</div>
					</div>
				<?php } ?>

				<ul class="lic <?php echo esc_attr( "apc-post-item apc_layout_{$args['layout']} {$args['animation']}" ); $s=0; ?>">
					<?php while( $query->have_posts() ) : $query->the_post(); $s++; ?>
						<?php if ( $args['layout'] == "1" ) { ?>
						<?php the_title( sprintf( '<li class="apc-img-list-title high-'. mt_rand(1, $s) .'"><h2 class="cms-list-title"><a class="srm" href="%s" rel="bookmark" ' . goal() . '><span class="gdm">' . t_mark(), esc_url( get_permalink() ) ), '</span></a></h2></li>' ); ?>
						<?php } else if ( $args['layout'] == 2 ) { ?>
						<?php } ?>
					<?php endwhile; ?>
				</ul>
		<?php } ?>

		<?php if ( $style == 'qa' ) { ?>
			<section class="apc-qa-box content-area">
				<div class="domargin <?php echo esc_attr( "apc-post-item apc_layout_{$args['layout']} {$args['animation']}" ); ?>">
					<?php while( $query->have_posts() ) : $query->the_post(); ?>
						<?php if ( $args['layout'] == "1" ) { ?>
							<div class="apc-qa-item">
								<?php beqa_article(); ?>
							</div>
						<?php } else if ( $args['layout'] == 2 ) { ?>
						<?php } ?>
					<?php endwhile; ?>
				</div>
		<?php } ?>

		<?php if ( $style == 'grid' ) { ?>
			<section class="apc-grid-box content-area apc-grid-cat-<?php if ( $column ){ ?><?php echo $column; ?><?php } else { ?>3<?php } ?>">
				<div class="<?php echo esc_attr( "apc-post-item apc_layout_{$args['layout']} {$args['animation']}" ); ?>">
					<?php while( $query->have_posts() ) : $query->the_post(); ?>
						<?php if ( $args['layout'] == "1" ) { ?>
							<div class="apc-grid-item">
								<div class="boxs<?php if ( $boxs == 'boxs' ) { ?> boxs1<?php } ?>">
									<article id="post-<?php the_ID(); ?>" class="post-item-list post ms" <?php aos_a(); ?>>
										<figure class="apc-grid-thumbnail thumbnail gdz">
											<?php echo zm_thumbnail(); ?>
										</figure>
										<header class="apc-grid-header">
											<?php the_title( sprintf( '<h2 class="apc-grid-cat-title over gdz">' . be_sticky() . '<a href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
										</header>
										<div class="apc-grid-content">
											<span class="apc-grid-meta lbm gdz">
												<span class="date">
													<time datetime="<?php echo get_the_date( 'Y-m-d' ); ?> <?php echo get_the_time( 'H:i:s' ); ?>"><?php time_ago( $time_type ='post' ); ?></time>
												</span>
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

		<?php if ( $style == 'photo' ) { ?>
			<section class="picture-area content-area grid-cat-<?php if ( $column ){ ?><?php echo $column; ?><?php } else { ?><?php echo be_get_option( 'img_f' ); ?><?php } ?>">
			<div class="<?php echo esc_attr( "apc-post-item apc_layout_{$args['layout']} {$args['animation']}" ); ?>">
				<?php while( $query->have_posts() ) : $query->the_post(); ?>
					<?php if ( $args['layout'] == "1" ) { ?>

							<article id="post-<?php the_ID(); ?>" class="post-item-list post picture scl" <?php aos_b(); ?>>
								<div class="boxs<?php if ( $boxs == 'boxs' ) { ?> boxs1<?php } ?>">
									<div class="picture-box<?php echo fill_class(); ?>"<?php echo be_img_fill(); ?>>
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
								</div>
							</article>

					<?php } else if ( $args['layout'] == 2 ) { ?>
					<?php } ?>
				<?php endwhile; ?>
			</div>
		<?php } ?>

		<?php if ( $style == 'img' ) { ?>
			<section class="picture-area content-area grid-cat-<?php if ( $column ){ ?><?php echo $column; ?><?php } else { ?><?php echo be_get_option( 'img_f' ); ?><?php } ?>">
			<div class="<?php echo esc_attr( "apc-post-item apc_layout_{$args['layout']} {$args['animation']}" ); ?>">
				<?php 
				$tabs = ( array ) co_get_option( 'group_tab_items' );
				foreach ( $tabs as $items ) {
					if ( ! empty( $items['group_tab_sub_name'] ) ) { ?>
						<article id="post-<?php the_ID(); ?>" class="post-item-list post picture scl<?php if ( ! empty( $items['group_tab_sub_big'] ) ) { ?> sub-big<?php } ?>" <?php aos_b(); ?>>
							<div class="picture-box sup<?php echo fill_class(); ?>"<?php echo be_img_fill(); ?>>
								<figure class="picture-img<?php if ( empty( $items['group_tab_sub_big'] ) ) { ?> picture-img-sub<?php } ?> gdz">
									<div class="thumbs-b lazy">
										<a class="thumbs-back sc" rel="bookmark" target="_blank" href="<?php echo $items['group_tab_sub_btn_url']; ?>" style="background-image: url(<?php echo $items['group_tab_sub_thumb']; ?>);"></a>
									</div>

									<div class="inf-cat-sub" style="color: <?php echo $items['group_tab_sub_color']; ?>">
										<div class="img-cat-sub"><?php echo $items['group_tab_sub_name']; ?></div>
										<div class="img-des-sub"><?php echo $items['group_tab_sub_des']; ?></div>
										<div class="img-btn-sub"><a href="<?php echo $items['group_tab_sub_btn_url']; ?>" rel="bookmark"><?php echo $items['group_tab_sub_btn']; ?></a></div>
									</div>

								</figure>
								<div class="clear"></div>
							</div>
						</article>
					<?php } ?>
				<?php } ?>


				<?php while( $query->have_posts() ) : $query->the_post(); ?>
					<?php if ( $args['layout'] == "1" ) { ?>
						<article id="post-<?php the_ID(); ?>" class="post-item-list post picture scl" <?php aos_b(); ?>>
							<div class="picture-box sup<?php echo fill_class(); ?>"<?php echo be_img_fill(); ?>>
								<figure class="picture-img gdz">
									<?php if ( $args['img'] == "1" ) { ?>
										<?php echo zm_grid_thumbnail(); ?>
									<?php } else { ?>
										<?php echo zm_thumbnail(); ?>
									<?php } ?>
								</figure>

								<?php $sub = get_post_meta( get_the_ID(), 'title_sub', true ) ? '<span>' . get_post_meta( get_the_ID(), 'title_sub', true ) . '</span>' : ''; ?>
								<?php the_title(sprintf('<h2 class="grid-title grid-title-sub gdz"><a href="%s" rel="bookmark" %s>', esc_url(get_permalink()), goal()), $sub . '</a></h2>'); ?>
								<?php grid_inf(); ?>

								<div class="clear"></div>
							</div>
						</article>
					<?php } else if ( $args['layout'] == 2 ) { ?>
					<?php } ?>
				<?php endwhile; ?>
			</div>
		<?php } ?>

		<?php if ( $style == 'falls' ){ ?>
			<section class="content-area apc-fall">
			<div class="be-main fall-main post-fall <?php echo esc_attr( "apc-post-item apc_layout_{$args['layout']} {$args['animation']}" ); ?>">
				<?php while( $query->have_posts() ) : $query->the_post(); ?>
					<?php if ( $args['layout'] == "1" ) { ?>
						<article id="post-<?php the_ID(); ?>" class="post-item-list fall scl fall-off">
							<div class="boxs<?php if ( $boxs == 'boxs' ) { ?> boxs1<?php } ?>">
								<div class="fall-box load<?php echo fill_class(); ?>"<?php echo be_img_fill(); ?>>
									<?php 
									global $post;
									$content = $post->post_content;
									preg_match_all( '/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?( ?:(?: |\\t|\\r|\\n)+.*? )?>/sim', $content, $strResult, PREG_PATTERN_ORDER );
									$n = count($strResult[1]);	
									if ( $n > 0 ) { ?>
										<figure class="fall-img">
											<?php echo zm_waterfall_img(); ?>
											<?php if ( has_post_format('video') ) { ?><a rel="bookmark" <?php echo goal(); ?> href="<?php echo esc_url( get_permalink() ); ?>"><i class="be be-play"></i></a><?php } ?>
											<?php if ( has_post_format('quote') ) { ?><div class="img-ico"><a rel="bookmark" <?php echo goal(); ?> href="<?php echo esc_url( get_permalink() ); ?>"><i class="be be-display"></i></a></div><?php } ?>
											<?php if ( has_post_format('image') ) { ?><div class="img-ico"><a rel="bookmark" <?php echo goal(); ?> href="<?php echo esc_url( get_permalink() ); ?>"><i class="be be-picture"></i></a></div><?php } ?>
										</figure>
										<?php the_title( sprintf( '<h2 class="fall-title"><a href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
									<?php } else { ?>
										<?php the_title( sprintf( '<h2 class="fall-title fall-title-img"><a href="%s" rel="bookmark" ' . goal() . '>', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
										<div class="archive-content-fall">
											<?php begin_trim_words(); ?>
										</div>
									<?php } ?>
									<?php if ( zm_get_option( 'fall_inf' ) ) { ?><?php fall_inf(); ?><?php } ?>
								 	<div class="clear"></div>
								</div>
							</div>
						</article>
					<?php } else if ( $args['layout'] == 2 ) { ?>
					<?php } ?>
				<?php endwhile; ?>
			</div>
		<?php } ?>

		<?php if ( $style == 'assets' ) { ?>
			<section class="apc-content-area-norm">
			<div class="<?php echo esc_attr( "apc-post-item apc_layout_{$args['layout']} {$args['animation']}" ); ?>">
				<div class="betip single-assets cms-assets-4">
					<div class="flexbox-grid">
						<?php 
							while( $query->have_posts() ) : $query->the_post();
							require get_template_directory() . '/template/assets.php';
							endwhile;
							wp_reset_postdata();
						?>
						<div class="clear"></div>
					</div>
					<?php be_help( $text = '主题选项 → 基本设置 → 相关资源' ); ?>
				</div>
			</div>
		<?php } ?>

		<?php if ( $style == 'sites' ) { ?>
			<section class="apc-sites-box content-area">
				<div class="<?php echo esc_attr( "apc-post-item apc_layout_{$args['layout']} {$args['animation']}" ); ?>">
					<?php while ( $query->have_posts() ) : $query->the_post(); ?>
						<div class="sites-area sites-<?php echo cx_get_option( 'site_f' ); ?>">
							<?php sites_favorites(); ?>
						</div>
					<?php endwhile; wp_reset_postdata(); ?>
					<div class="clear"></div>
				</div>
		<?php } ?>

		<div class="clear"></div>
		<?php if ( ! $nav == 'no' ) { ?>
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
					if ( $mid == '1' ) {
						if ( wp_is_mobile() ) {
							$mid_size = '0';
						} else {
							$mid_size = '2';
						}
					} else {
						$mid_size = zm_get_option( 'mid_size' );
					}
					$paged          = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;
					$paginate_links = paginate_links( array(
						'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
						'format'    => '?paged=%#%',
						'current'   => max( 1, $dataPaged ),
						'prev_next' => $prevnext,
						'prev_text' => '<span>' . $dataPrev . '</span>',
						'next_text' => '<span>' . $dataNext . '</span>',
						'mid_size'  => $mid_size,
						'total'     => $query->max_num_pages
					) );

					if ( $moretext == 'true' ) {
						$even = '<span class="moretext">' . sprintf(__( '更多', 'begin' )) . '</span>';
					} else {
						$even = '<i class="be be-more"></i>';
					}

					if ( $more == 'full' ) {
						if ( $paginate_links && $dataPaged < $query->max_num_pages ){
							echo "<div class='clear ajax-navigation'></div><div data-paged='{$dataPaged}' data-next='{$dataNext}' class='{$args['infinite']} apc-post-item-load-more'><span class='apc-load-more'>" . $even . "</span></div>";
						}
						echo "<div class='clear'></div><div class='apc-navigation'>{$paginate_links}</div>";
					} else {
						if ( $more == 'more' ) {
							if ( $paginate_links && $dataPaged < $query->max_num_pages ){
								echo "<div class='clear ajax-navigation'></div><div data-paged='{$dataPaged}' data-next='{$dataNext}' class='{$args['infinite']} apc-post-item-load-more'><span class='apc-load-more'>" . $even . "</span></div>";
							}
						} else {
							echo "<div class='clear'></div><div class='apc-navigation'>{$paginate_links}</div>";
						}
					}
				?>
				<div class="clear"></div>
			</div>
		<?php } ?>
	</section>
	<?php
	wp_reset_postdata();
	echo ( $more == 'more' ) ? '</div>' : '';
	echo ( $more == 'full' ) ? '</div>' : '';

	return ob_get_clean();
}