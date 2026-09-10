<?php
// 超级菜单

// menus custom [menucode image="图片链接" ids="分类ID" post="页面ID"]
function be_menus_set_cat( $atts = array(), $content = null ) {
	extract( shortcode_atts( array(
		'html' => '',
		'image' => '',
		'ids'   => '',
		'post'     => ''
	), $atts ) );

		$terms = get_terms(
			array(
				'include'    => explode( ',', $ids ),
				'taxonomy'   => array( 'category','taobao', 'gallery', 'videos', 'products', 'notice' ),
				'orderby'    => 'include',
				'order'      => 'ASC',
				'hide_empty' => 0
			)
		);
		$html = '';
		$html .= '</a><span class="menus-set-cat-box"><span class="menus-set-cat-img menus-team-cat">';
		$html .= '<img alt="catimg" src="' . $image . '">';
		$html .= '</span><span class="menus-set-cat-name-box menus-team-cat"><span class="menus-set-cat-name">';
		if ( ! strtolower( $ids ) == '' ) {
			foreach ( $terms as $term ) {
				$html .= '<a href="' . esc_url( get_term_link( $term ) ) . '" rel="bookmark"><span class="be-menu-custom-title-ico"></span>' . $term->name . '</a>';
			}
		}

		if ( ! strtolower( $post ) == '' ) {
			$post_ids = explode( ',', $post );
			foreach ( $post_ids as $post_id ) {
				$post = get_post( $post_id );
				$html .= '<a href="' . esc_url( get_permalink( $post_id ) ) . '" rel="bookmark"><span class="be-menu-custom-title-ico"></span>' . $post->post_title . '</a>';
			}
		}

	$html .= '</span></span><span class="clear"></span></span><a class="menus-set-name-hide">';
	return $html;
}

// menus cover [menucodecover ids="分类ID"]
function be_menus_set_cover( $atts = array(), $content = null ) {
	extract( shortcode_atts( array(
		'html' => '',
		'image' => cat_cover_url(),
		'ids'   => ''
	), $atts ) );

	$terms = get_terms(
		array(
			'include'    => explode( ',', $ids ),
			'taxonomy'   => array( 'category','taobao', 'gallery', 'videos', 'products', 'notice' ),
			'orderby'    => 'include',
			'order'      => 'ASC',
			'hide_empty' => 0
		)
	);

	$html = '</a></li>';

	foreach ( $terms as $term ) {

		$html .= '<li class="be-menu-custom-img be-menu-custom-explain">';
		$html .= '<a href="' . esc_url( get_term_link( $term ) ) . '" rel="bookmark">';
		$html .= '<span class="be-menu-custom-title">' . $term->name . '</span>';
		$html .= '<span class="be-menu-img"><img alt="catimg" src="' . cat_cover_url( $term->term_id ) . '"></span>';
		$html .= '<span class="be-menu-explain">' . category_description( $term->term_id ) . '</span>';
		$html .= '</a>';
		$html .= '</li>';
	}
	$html .= '<li class="menus-set-cover-hide"><a>';
	return $html;
}

// menus special [menucodespecial ids="专题页面ID"]
function be_menus_set_special( $atts = array(), $content = null ) {
	global $post;
	extract( shortcode_atts( array(
		'html' => '',
		'image' => cat_cover_url(),
		'ids'   => ''
	), $atts ) );


	$posts = get_posts( array(
		'post_type' => 'any',
		'orderby' => 'include',
		'order'      => 'ASC',
		'include' => $ids,
		'ignore_sticky_posts' => 1
	) ); 

	$html = '</a></li>';
	$html .= '</a>';

	foreach( $posts as $post ) : setup_postdata( $post );
	$description = get_post_meta( get_the_ID(), 'description', true );
	$image = get_post_meta( get_the_ID(), 'thumbnail', true );
	$html .= '<li class="be-menu-custom-img be-menu-custom-explain">';
	$html .= '<a href="' . get_permalink() . '" rel="bookmark">';
	$html .= '<span class="be-menu-custom-title"><span class="be-menu-custom-title-ico"></span>' . get_the_title() . '</span>';

	$html .= '<span class="be-menu-img"><img alt="catimg" src="' . $image . '"></span>';
	$html .= '<span class="be-menu-explain">' . $description . '</span>';
	$html .= '</a>';
	$html .= '</li>';

	endforeach;
	wp_reset_postdata();

	$html .= '<li class="menus-set-cover-hide"><a>';
	return $html;
}

// menus custom 
// [menupost image="图片链接" cat_ids="左侧分类ID" post_ids="右侧文章/页面ID" des="1" words_n="文章摘要字数"]
function be_menus_set_post( $atts = array(), $content = null ) {
	global $post;
	extract( shortcode_atts( array(
		'html'      => '',
		'image'     => '',
		'cat_ids'   => '',
		'post_ids'  => '',
		'des'       => '',
		'words_n'   => ''
	), $atts ) );

	$terms = get_terms(
		array(
			'include'    => explode( ',', $cat_ids ),
			'taxonomy'   => array( 'category','taobao', 'gallery', 'videos', 'products', 'notice' ),
			'orderby'    => 'include',
			'order'      => 'ASC',
			'hide_empty' => 0
		)
	);

	$args = array(
		'post__in'            => explode( ',', $post_ids ),
		'post_type'           => 'any',
		'orderby'             => 'post__in', 
		'order'               => 'DESC',
		'ignore_sticky_posts' => 1
	);
	$the_query = new WP_Query( $args );

	$html = '</a>';
	$html .= '<span class="menu-mix-box">';

	$html .= '<span class="menu-mix-cat-box menu-mix-team">';
	$html .= '<span class="menus-mix-img menus-team-cat">';
	$html .= '<img alt="catimg" src="' . $image . '">';
	$html .= '</span>';

	$html .= '<span class="menus-mix-cat-name">';
	foreach ( $terms as $term ) {
		$html .= '<a href="' . esc_url( get_term_link( $term ) ) . '" rel="bookmark"><span class="be-menu-custom-title-ico"></span>' . $term->name . '</a>';
	}

	$html .= '</span>';
	$html .= '</span>';

	$html .= '<span class="menu-mix-post-box  menu-mix-team">';

	if ( $the_query->have_posts() ) :
		while ( $the_query->have_posts() ) : $the_query->the_post();
			$html .= '<span class="menu-mix-post">';
			$html .= '<a class="menu-mix-post-main" href="' . get_permalink() . '">';
			$html .= '<span class="be-menu-custom-title-ico"></span>';
			$html .= '<span class="menu-mix-post-title">' . get_the_title() . '<span class="clear"></span></span>';

			$html .= be_menu_thumbnail();

			if ( strtolower( $des ) === '1' ) {
				$content = $post->post_content;
				preg_match_all( '/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER );
				$n = count( $strResult[1] );

				if ( get_post_meta( get_the_ID(), 'thumbnail', true ) || $n > 0 ) {
					$html .= '<span class="menu-mix-post-content menu-post-content-img">';
				} else {
					$html .= '<span class="menu-mix-post-content">';
				}
				$content = get_the_content();
				$content = strip_shortcodes( $content );
				$html .= wp_trim_words( $content, $words_n, '...' );
				$html .= '</span>';
			}
			$html .= '</a></span>';

		endwhile;
	endif;

	wp_reset_postdata();

	$html .= '</span>';
	$html .= '<span class="clear"></span></span>';
	$html .= '<a class="menus-set-name-hide">';

	return $html;
}

// code custom 
// [menucodea image="图片地址" buturl="链接一" url1="链接一" url2="链接二" url3="链接三" url4="链接四" url5="链接五"  but="" title1="标题一" title2="标题二" title3="标题三" title4="标题四" title5=""]
function be_menus_custom_a( $atts = array(), $content = null ) {
	extract( shortcode_atts( array(
		'html'   => '',
		'image'  => '',
		'url1'   => '',
		'url2'   => '',
		'url3'   => '',
		'url4'   => '',
		'url5'   => '',
		'buturl' => '',
		'title1' => '',
		'title2' => '',
		'title3' => '',
		'title4' => '',
		'title5' => '',
		'but' => ''

	), $atts ) );


	$html = '</a>';
	$html .= '<span class="menus-set-cat-box menu-only-btn">';
	$html .= '<span class="menus-set-cat-img menus-team-cat">';
	$html .= '<img alt="catimg" src="' . $image . '">';
	$html .= '</span>';

	$html .= '<span class="menus-set-cat-name-box menus-team-cat">';
	$html .= '<span class="menus-set-cat-name">';

	if ( !strtolower( $but ) == '' ) {
		$html .= '<span class="be-menu-custom-title-but">';
		$html .= '<a href="' . $buturl . '" rel="bookmark">' . $but . '</a>';
		$html .= '</span>';
	}
	if ( !strtolower( $title1 ) == '' ) {
		$html .= '<a href="' . $url1 . '" rel="bookmark"><span class="be-menu-custom-title-ico"></span>' . $title1 . '</a>';
	}
	if ( !strtolower( $title2 ) == '' ) {
		$html .= '<a href="' . $url2 . '" rel="bookmark"><span class="be-menu-custom-title-ico"></span>' . $title2 . '</a>';
	}
	if ( !strtolower( $title3 ) == '' ) {
		$html .= '<a href="' . $url3 . '" rel="bookmark"><span class="be-menu-custom-title-ico"></span>' . $title3 . '</a>';
	}
	if ( !strtolower( $title4 ) == '' ) {
		$html .= '<a href="' . $url4 . '" rel="bookmark"><span class="be-menu-custom-title-ico"></span>' . $title4 . '</a>';
	}
	if ( !strtolower( $title5 ) == '' ) {
		$html .= '<a href="' . $url5 . '" rel="bookmark"><span class="be-menu-custom-title-ico"></span>' . $title5 . '</a>';
	}

	$html .= '</span>';
	$html .= '</span>';
	$html .= '<span class="clear"></span></span>';
	$html .= '<a class="menus-set-name-hide">';
	return $html;
}

// menus cat mod
// [menucatmod cat_ids="分类ID"]
function be_menus_cat_mod( $atts = array(), $content = null ) {
	global $post;
	extract( shortcode_atts( array(
		'html'      => '',
		'cat_ids'   => ''

	), $atts ) );

	$html = '</a>';
	$html .= '<span class="be-menus-cat-mod-box">';

	$args = new WP_Query(array(
		'post__not_in' => array($post->ID),
		'ignore_sticky_posts' => 1,
		'showposts' => '1',
		'category__and' => $cat_ids
	));

	while ($args->have_posts()) : $args->the_post();

	$html .= '<figure class="menus-cat-mod-thumbnail">';
	$html .= '<a href="' . get_category_link( $cat_ids ) . '">';
	$html .= be_menu_thumbnail();
	$html .= '</a>';
	$html .= '</figure>';
	endwhile;
	wp_reset_postdata();

	$html .= '<span class="menus-cat-mod-name">';
	$html .= '<a class="menus-mod-ico" href="' . get_category_link( $cat_ids ) . '"><i class="dashicons dashicons-image-filter"></i>';
	$html .= get_cat_name( $cat_ids );
	$html .= '</a>';
	$html .= '</span>';

	$args = new WP_Query(array(
	'post__not_in' => array($post->ID),
		'ignore_sticky_posts' => 1,
		'showposts' => 5,
		'category__and' => $cat_ids
	));

	while ($args->have_posts()) : $args->the_post();
	$html .= '<a class="be-menus-cat-mod-list menus-mod-ico" href="' . esc_url( get_permalink() ) . '" rel="bookmark">';
	$html .= '<span class="the-icon">';
	$html .= get_the_title();
	$html .= '</span>';
	$html .= '</a>';
	endwhile;
	wp_reset_postdata();
	$html .= '<span class="clear"></span></span>';
	$html .= '<a class="menus-set-name-hide">';
	return $html;
}

// menus hot mod
// [menuhotmod number="数量" cat_ids="分类ID" month="限定月数"]
function be_menus_hot_mod( $atts = array(), $content = null ) {
	global $post;
	extract( shortcode_atts( array(
		'html'    => '',
		'month'   => '',
		'number'  => '',
		'cat_ids' => ''
	), $atts ) );

	$html = '</a>';
	$html .= '<span class="be-menus-cat-mod-box">';

	$args = array(
		'ignore_sticky_posts' => 1,
		'post_status'         => 'publish',
		'showposts'           => 1,
		'category__and'       => $cat_ids,
		'meta_key'            => 'views',
		'orderby'             => 'meta_value_num',
		'order'               => 'DESC',
		'date_query'          => array(
			array(
				'after'       => $month . 'month ago',
				'before'      => 'today',
				'inclusive'   => true
			)
		)
	);

	$the_query = new WP_Query( $args );
	if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post();

	$html .= '<figure class="menus-cat-mod-thumbnail">';
	if ( strtolower( $cat_ids ) !== '0' ) {
		$html .= '<a href="' . get_category_link( $cat_ids ) . '">';
	} else {
		$html .= '<a href="' . esc_url( get_permalink() ) . '">';
	}
	$html .= be_menu_thumbnail();
	$html .= '</a>';
	$html .= '</figure>';
	endwhile; endif;
	wp_reset_postdata();

	$html .= '<span class="menus-hot-mod-clear"></span>';
	$args = array(
		'ignore_sticky_posts' => 1,
		'post_status'         => 'publish',
		'showposts'           => $number,
		'category__and'       => $cat_ids,
		'meta_key'            => 'views',
		'orderby'             => 'meta_value_num',
		'order'               => 'DESC',
		'date_query'          => array(
			array(
				'after'       => $month . 'month ago',
				'before'      => 'today',
				'inclusive'   => true
			)
		)
	);

	$the_query = new WP_Query( $args );
	$i = 1;
	if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post();
	$html .= '<a class="be-menus-cat-mod-list srm" href="' . esc_url( get_permalink() ) . '" rel="bookmark">';
	$html .= '<span class="the-icon">';
	$html .= '<span class="list-num list-num-' . ( $i ) . '">';
	$html .= ($i++);
	$html .= '</span>';
	$html .= get_the_title();
	$html .= '</span>';
	$html .= '</a>';
	endwhile; endif;
	wp_reset_postdata();
	$html .= '<span class="clear"></span></span>';
	$html .= '<a class="menus-set-name-hide">';
	return $html;
}

// menus rec 
// [menurecmod post_ids="文章ID"]
function be_menus_rec_mod( $atts = array(), $content = null ) {
	global $post;
	extract( shortcode_atts( array(
		'html'     => '',
		'post_ids' => ''
	), $atts ) );

	$html = '</a>';
	$html .= '<span class="be-menus-cat-mod-box">';

	$args = array(
		'post__in'  => explode( ',', $post_ids ),
		'ignore_sticky_posts' => 1,
		'showposts'           => 1,
		'orderby'   => 'post__in',
		'order'     => 'ASC'
	);

	$the_query = new WP_Query( $args );
	if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post();

	$html .= '<figure class="menus-cat-mod-thumbnail">';
	$html .= '<a href="' . esc_url( get_permalink() ) . '">';
	$html .= be_menu_thumbnail();
	$html .= '</a>';
	$html .= '</figure>';
	endwhile; endif;
	wp_reset_postdata();

	$html .= '<span class="menus-hot-mod-clear"></span>';
	$args = array(
		'post__in'  => explode( ',', $post_ids ),
		'ignore_sticky_posts' => 1,
		'orderby'   => 'post__in',
		'order'     => 'ASC'
	);

	$the_query = new WP_Query( $args );
	$i = 1;
	if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post();
	$html .= '<a class="be-menus-cat-mod-list srm" href="' . esc_url( get_permalink() ) . '" rel="bookmark">';
	$html .= '<span class="the-icon">';
	$html .= '<span class="list-num list-num-' . ( $i ) . '">';
	$html .= ($i++);
	$html .= '</span>';
	$html .= get_the_title();
	$html .= '</span>';
	$html .= '</a>';
	endwhile; endif;
	wp_reset_postdata();
	$html .= '<span class="clear"></span></span>';
	$html .= '<a class="menus-set-name-hide">';
	return $html;
}

// columns button
function btn_columns( $atts = array(), $content=null ) {
	extract( shortcode_atts( array(), $atts ) );
	return '<div class="btn-columns-box">' . do_shortcode( do_shortcode( $content ) ) . '</div>';
}

// 菜单分类列表
// [menucatlist cat_ids="分类ID" number="分章数"]
function be_menus_cat_list( $atts = array(), $content = null ) {
	global $post;
	extract( shortcode_atts( array(
		'cat_ids' => '', 
		'number' => ''
	), $atts ) );

	$html = '</a>';
	$html .= '<span class="menu-cat-list-box">';
	$menu_cat = explode( ',', $cat_ids ); foreach ( $menu_cat as $category ) {
		$posts = get_posts( array(
			'posts_per_page' => $number,
			'post_status'    => 'publish',
			'cat'            => $category
		) );

		$html .= '<span class="menu-list-item">';
		$html .= '<h3 class="menu-list-cat-title">';
		$html .= '<a href="';
		$html .= get_category_link( $category );
		$html .= '">';
		$html .= get_cat_name( $category );
		$html .= '</a>';
		$html .= '</h3>';
		$html .= '<span class="menu-list-title">';
		foreach ( $posts as $post ) : setup_postdata( $post );
		$html .= '<a class="srm" href="';
		$html .= get_permalink();
		$html .= '" rel="bookmark">';
		$html .= '<span class="be-menu-custom-title-ico"></span>';
		$html .= get_the_title();
		$html .= '</a>';
		endforeach;
		wp_reset_postdata();
		$html .= '</span>';
		$html .= '</span>';
	}
	$html .= '</span>';
	return $html;
}

// 分类图文混排
// [superlist page_ids="页面ID" cat_ids="分类ID" post_ids="页面ID" column="分栏" number="数量" cat="图上按钮分类ID" image="图片链接"]
function super_mix_shortcode( $atts = array(), $content = null ) {
	global $post;
	extract( shortcode_atts( array(
		'html'      => '',
		'cat_ids'   => '',
		'column'   => '',
		'number'    => '',
		'post_ids'  => '',
		'page_ids'  => '',
		'image'     => '',
		'cat'       => '',
	), $atts ) );

	if ( $column == 2 ) {
		$column = '2';
	} elseif ( $column == 3 ) {
		$column = '3';
	} elseif ( $column == 4 ) {
		$column = '4';
	}

	$mix = empty( $page_ids ) ? ' mix-big-m' : '';

	$html= '</a>';
	$html .= '<span class="super-mix-big' .  $mix . '">';
	$html .= '<span class="super-mix-cat">';

	// 页面
	if ( ! empty( $page_ids ) ) {
		$page_ids = explode( ',', $page_ids );
		foreach ( $page_ids as $page_id ) {
			$page = get_post( $page_id );
			$post = $page;
			$page_img = get_post_meta( $page_id, 'page_img', true );
			$post_img = get_post_meta( $page_id, 'post_img', true );

			if ( $page ) {
				$html .= '<span class="super-mix-item mix-item-img mix-item' .$column . '">';
				if ( $page_img ) {
					$html .= '<span class="super-mix-title-img">';
					$html .= '<a href="#"></a>';
					$html .= '<a href="' . get_permalink( $page_id ) . '" class="super-title-img"><img src="' . esc_url( $page_img ) . '" alt=' . $page->post_title . '></a>';

					$html .= '<span class="super-mix-img-btn">';
					$html .= '<a href="#"></a>';
					$html .= '<a href="' . get_permalink( $page_id ) . '">' . $page->post_title . '</a>';
					$html .= '</span>';
					$html .= '</span>';
				} else {
					$html .= '<span class="super-mix-title-img">';
					$html .= '<a href="#"></a>';
					$html .= '<a href="' . get_permalink( $page_id ) . '" class="super-title-img"><img src="' . esc_url( $post_img ) . '" alt=' . $page->post_title . '></a>';
					$html .= '</span>';
					$html .= '<span class="super-img-title">';
					$html .= '<a href="#"></a>';
					$html .= '<a href="' . get_permalink( $page_id ) . '">' . $page->post_title . '</a>';
					$html .= '</span>';
				}
				$html .= '</span>';
			}
		}
	}

	// 分类
	$category_ids = explode( ',', $cat_ids );
	foreach ( $category_ids as $category_id ) {
		$args = array(
			'post_type'      => 'post',
			'posts_per_page' => $number,
			'category__in'   => array( $category_id ),
			'orderby'        => 'category__in',
			'order'          => 'DESC'
		);
		$query = new WP_Query( $args );

		if ( $query->have_posts() ) {
			$category = get_category( $category_id );
			$html .= '<span class="super-mix-item mix-item' . $column . '">';
				$html .= '<a href="#"></a>';
				$html .= '<a href="' . get_category_link( $category_id ) . '" class="super-mix-name">' . $category->name . '</a>';
				$html .= '<span class="super-mix-title">';
					$html .= '<a href="#"></a>';
					while ( $query->have_posts() ) {
						$query->the_post();
						$html .= '<a class="be-menus-cat-mod-list menus-mod-ico" href="' . esc_url( get_permalink() ) . '" rel="bookmark">';
						$html .= '<span class="the-icon">';
						$html .= get_the_title();
						$html .= '</span>';
						$html .= '</a>';

					}
				$html .= '</span>';
			$html .= '</span>';
		}
	    wp_reset_postdata();
	}

	// 文章
	if ( ! empty( $post_ids ) ) {
		$html .= '<span class="super-mix-item mix-item' . $column . '">';
		$post_ids = explode( ',', $post_ids );
		$html .= '<span class="super-mix-title">';
		$html .= '<a href="#"></a>';
		foreach ( $post_ids as $post_id ) {
			$post = get_post( $post_id );
			if ( $post ) {
				$html .= '<a class="be-menus-cat-mod-list menus-mod-ico" href="' .  get_permalink( $post_id ) . '" rel="bookmark">';
				$html .= '<span class="the-icon">';
				$html .= $post->post_title;
				$html .= '</span>';
				$html .= '</a>';
			}
		}

		$html .= '</span>';
		$html .= '</span>';
	}
	$html .= '</span>';

	if ( ! empty( $image ) ) {
	// 图片
		if ( ! empty( $cat ) ) {
			$category = get_term( $cat, 'category' );
			$alt = $category->name;
		} else {
			$alt = 'category';
		}
		
		$html .= '<span class="super-mix-img">';
		$html .= '<img src="' . $image . '" alt="' . $alt . '">';
	}

	if ( ! empty( $cat ) ) {
		// 按钮
		$html .= '<span class="super-mix-img-btn">';

		$category = get_term( $cat, 'category' );
		if ( ! is_wp_error( $category ) ) {
			$category_name = $category->name;
			$category_link = get_term_link( $category );
			$html .= '<a href="#"></a>';
			$html .= '<a href="' . esc_url( $category_link ) . '">' . esc_html( $category_name ) . '</a>';
		} else {
		    $html .= '分类不存在';
		}

		$html .= '</span>';
	}

	$html .= '</span>';

	$html .= '</span>';
	$html .= '<a class="menus-set-name-hide">';
	return $html;
}
add_shortcode( 'superlist', 'super_mix_shortcode' );

// 一图分类列表
// [supercover cat_ids="分类ID，多个分类用英文","逗号隔开" column="分栏" number="数量" cat="图上按钮分类ID" image="图片链接"]
function super_cover_shortcode( $atts = array(), $content = null ) {
	global $post;
	extract( shortcode_atts( array(
		'html'      => '',
		'cat_ids'   => '',
		'column'    => '',
		'number'    => '',
		'image'     => '',
		'cat'       => '',
		'cover'     => '',
	), $atts ) );

	if ( $column == 2 ) {
		$column = '2';
	} elseif ( $column == 3 ) {
		$column = '3';
	} elseif ( $number == 4 ) {
		$column = '4';
	}

	$html= '</a>';
	$html .= '<span class="super-mix-big super-mix-cover">';
	
		if ( ! empty( $image ) ) {
		// 图片
			if ( ! empty( $cat ) ) {
				$category = get_term( $cat, 'category' );
				$alt = $category->name;
			} else {
				$alt = 'category';
			}
			
			$html .= '<span class="super-mix-img">';
			$html .= '<img src="' . $image . '" alt="' . $alt . '">';
		}

		if ( ! empty( $cat ) ) {
			// 按钮
			$html .= '<span class="super-mix-img-btn">';

			$category = get_term( $cat, 'category' );
			if ( ! is_wp_error( $category ) ) {
				$category_name = $category->name;
				$category_link = get_term_link( $category );
				$html .= '<a href="#"></a>';
				$html .= '<a href="' . esc_url( $category_link ) . '">' . esc_html( $category_name ) . '</a>';
			} else {
			    $html .= '分类不存在';
			}

			$html .= '</span>';
		}

		$html .= '</span>';
		
		$html .= '<span class="super-mix-cat">';

			// 分类
			$category_ids = explode( ',', $cat_ids );
			foreach ( $category_ids as $category_id ) {
				$args = array(
					'post_type'      => 'post',
					'posts_per_page' => $number,
					'category__in'   => array( $category_id ),
					'orderby'        => 'category__in',
					'order'          => 'DESC'
				);
				$query = new WP_Query( $args );

				if ( $query->have_posts() ) {
					$category = get_category( $category_id );
					$html .= '<span class="super-mix-item mix-item' . $column . '">';
						$html .= '<a href="#"></a>';
						$html .= '<a href="' . get_category_link( $category_id ) . '" class="super-mix-name">' . $category->name . '</a>';
						$html .= '<span class="super-mix-title">';
							$html .= '<a href="#"></a>';
							while ( $query->have_posts() ) {
								$query->the_post();
								$html .= '<a class="be-menus-cat-mod-list menus-mod-ico" href="' . esc_url( get_permalink() ) . '" rel="bookmark">';
								$html .= '<span class="the-icon">';
								$html .= get_the_title();
								$html .= '</span>';
								$html .= '</a>';

							}
						$html .= '</span>';
					$html .= '</span>';
				}
			    wp_reset_postdata();
			}
		$html .= '</span>';

	$html .= '</span>';
	$html .= '<a class="menus-set-name-hide">';
	return $html;
}
add_shortcode( 'supercover', 'super_cover_shortcode' );

// 三图分类列表
// [superlist cat_id="左图按钮分类ID" image="左图片链接" cat_list_id="中间分类列表ID" number="中间分类列表文章数量" cat_a_id="右a按钮分类ID" cat_b_id="右b按钮分类ID" cat_a_img="右a图片链接" cat_b_img="右b图片链接"]
add_shortcode( 'supercatlist', 'super_catlist_shortcode' );
function super_catlist_shortcode( $atts = array(), $content = null ) {
	global $post;
	extract( shortcode_atts( array(
		'html'        => '',
		'cat_a_id'    => '',
		'cat_b_id'    => '',
		'cat_a_img'   => '',
		'cat_b_img'   => '',
		'cat_list_id' => '',
		'cat_id'      => '',
		'number'      => '',
		'image'       => '',
	), $atts ) );

	$html = '</a>';
	$html .= '<span class="super-mix-big super-mix-big-catimg">';
		$html .= '<span class="super-mix-cat">';

			// 左a分类
			if ( ! empty( $cat_a_id ) ) {
				$category = get_term( $cat_a_id, 'category' );

				if ( ! is_wp_error( $category ) && isset( $category->name ) ) {
					$category_name = $category->name;
					$category_link = get_term_link( $category );
				} else {
					$category_name = '分类不存在';
					$category_link = '#';
				}
			} else {
				$category_name = 'category';
				$category_link = '#';
			}

			$html .= '<span class="super-mix-item mix-item-img mix-item4">';
			$html .= '<span class="super-mix-title-img">';
			$html .= '<a href="#"></a>';
			$html .= '<a href="' . esc_url( $category_link ) . '" class="super-title-img"><img src="' . esc_url( $cat_a_img ) . '" alt="' . esc_attr( $category_name ) . '"></a>';
			$html .= '<span class="super-mix-img-btn">';
			$html .= '<a href="#"></a>';
			$html .= '<a href="' . esc_url( $category_link ) . '">' . esc_html( $category_name ) . '</a>';
			$html .= '</span>';
			$html .= '</span>';
			$html .= '</span>';


			// 左b分类
			if ( ! empty( $cat_b_id ) ) {
				$category = get_term( $cat_b_id, 'category' );

				if ( ! is_wp_error( $category ) && isset( $category->name ) ) {
					$category_name = $category->name;
					$category_link = get_term_link( $category );
				} else {
					$category_name = '分类不存在';
					$category_link = '#';
				}
			} else {
				$category_name = 'category';
				$category_link = '#';
			}

			$html .= '<span class="super-mix-item mix-item-img mix-item4">';
			$html .= '<span class="super-mix-title-img">';
			$html .= '<a href="#"></a>';
			$html .= '<a href="' . esc_url( $category_link ) . '" class="super-title-img"><img src="' . esc_url( $cat_b_img ) . '" alt="' . esc_attr( $category_name ) . '"></a>';
			$html .= '<span class="super-mix-img-btn">';
			$html .= '<a href="#"></a>';
			$html .= '<a href="' . esc_url( $category_link ) . '">' . esc_html( $category_name ) . '</a>';
			$html .= '</span>';
			$html .= '</span>';
			$html .= '</span>';

			// 分类文章
			$args = array(
				'post_type'      => 'post',
				'posts_per_page' => $number,
				'category__in'   => $cat_list_id,
				'orderby'        => 'category__in',
				'order'          => 'DESC'
			);
			$query = new WP_Query( $args );

			if ( $query->have_posts() ) {
				$html .= '<span class="super-mix-item mix-item4">';
					$html .= '<a href="#"></a>';
					$category = get_category( $cat_list_id );

					if ( $category ) {
						$category_name = $category->name; 
						$category_link = get_category_link( $cat_list_id );
						$html .= '<a href="' . esc_url( $category_link ) . '" class="super-mix-name">' . esc_html( $category_name ) . '</a>';
					} else {
						$html .= '<a href="#" class="super-mix-name">分类不存在</a>';
					}

					$html .= '<span class="super-mix-title">';
						$html .= '<a href="#"></a>';
						while ( $query->have_posts() ) {
							$query->the_post();
							$html .= '<a class="be-menus-cat-mod-list menus-mod-ico" href="' . esc_url( get_permalink() ) . '" rel="bookmark">';
							$html .= '<span class="the-icon">';
							$html .= get_the_title();
							$html .= '</span>';
							$html .= '</a>';
						}
					$html .= '</span>';
				$html .= '</span>';
			}
		    wp_reset_postdata();
		$html .= '</span>';

		$html .= '<span class="super-mix-img-box">';
		if ( ! empty( $image ) ) {
			if ( ! empty( $cat_id ) ) {
				// 右图片
				$category = get_term( $cat_id, 'category' );
				if ( ! is_wp_error( $category ) && $category !== null ) {
					$alt = $category->name;
				} else {
					$alt = 'category';
				}
			} else {
				$alt = 'category';
			}

			$html .= '<span class="super-mix-img">';
			$category = get_term( $cat_id, 'category' );
			if ( ! is_wp_error( $category ) && $category !== null ) {
				$category_link = get_term_link( $category );
				$html .= '<a href="#"></a>';
				$html .= '<a class="super-mix-img-link" href="' . esc_url( $category_link ) . '"></a>';
			}
			$html .= '<img src="' . $image . '" alt="' . esc_attr( $alt ) . '">';
			if ( ! empty( $cat_id ) ) {
				// 右按钮
				$html .= '<span class="super-mix-img-btn">';
				$category = get_term( $cat_id, 'category' );
				if ( ! is_wp_error( $category ) && $category !== null ) {
					$category_name = $category->name;
					$category_link = get_term_link( $category );

					$html .= '<a href="#"></a>';
					$html .= '<a href="' . esc_url( $category_link ) . '">' . esc_html( $category_name ) . '</a>';
			    } else {
					$html .= '<a href="#"></a>';
					$html .= '<a href="#">分类不存在</a>';
				}
				$html .= '</span>';
			}
			$html .= '</span>';
		}
		$html .= '</span>';
	$html .= '</span>';
	$html .= '<a class="menus-set-name-hide">';
	return $html;
}