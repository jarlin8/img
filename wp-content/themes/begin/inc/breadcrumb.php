<?php
function be_breadcrumb() {
	$here_text        = '';
	$home_link        = home_url('/');
	$home_text        =  __( '首页', 'begin' );
	$home_ico         =  '<span class="seat"></span>';
	$home_welcome     =  __( '现在位置', 'begin' );
	$link_before      = '<span>';
	$link_after       = '</span>';
	$link_attr        = '';
	$mainerm          = '';
	$link             = $link_before . '<a' . $link_attr . ' href="%1$s">%2$s</a>' . $link_after;
	$delimiter        = '<i class="be be-arrowright"></i>';
	$before           = '<span class="current">';
	$after            = '</span>';
	$page_addon       = '';
	$breadcrumb_trail = '';
	$category_links   = '';

	global $post;
	$post_type_to_taxonomy = array();

	// 获取所有自定义文章类型
	$args = array( 'public' => true, '_builtin' => false ); // 设置参数以获取非内置的文章类型
	$custom_post_types = get_post_types( $args, 'names', 'and' );

	// 遍历自定义文章类型并获取每个类型的分类
	foreach ( $custom_post_types as $post_type ) {
		// 获取当前文章类型的所有分类
		$taxonomies = get_object_taxonomies( $post_type, 'names' );
		// 只需要第一个分类
		if ( ! empty( $taxonomies ) ) {
			$post_type_to_taxonomy[$post_type] = array_shift( $taxonomies );
		}
	}

	// 获取当前文章的类型
	$current_post_type = get_post_type();
	if ( 'product' == $current_post_type ) {
		$custom_taxonomy = 'product_cat';
	} else {
		$custom_taxonomy = isset( $post_type_to_taxonomy[$current_post_type] ) ? $post_type_to_taxonomy[$current_post_type] : '';
	}

	$wp_the_query   = $GLOBALS['wp_the_query'];
	$queried_object = $wp_the_query->get_queried_object();

	if ( is_singular() ) {
		$post_object = sanitize_post( $queried_object );
		$title          = $post_object->post_title;
		$parent         = $post_object->post_parent;
		$post_type      = $post_object->post_type;
		$post_id        = $post_object->ID;
		$post_link      = $before . $title . $after;
		$parent_string  = '';
		$post_type_link = '';

		if ( 'post' === $post_type ) {
			$categories = get_the_category( $post_id );
			if ( $categories ) {

				$category  = $categories[0];

				$category_links = get_category_parents( $category, true, $delimiter );
				$category_links = str_replace( '<a',   $link_before . '<a' . $link_attr, $category_links );
				$category_links = str_replace( '</a>', '</a>' . $link_after, $category_links );
			}
		}

		if ( !in_array( $post_type, ['post', 'page', 'attachment'] ) ) {
			$post_type_object = get_post_type_object( $post_type );
			$archive_link     = esc_url( get_post_type_archive_link( $post_type ) );

			$taxonomy_exists = taxonomy_exists( $custom_taxonomy );
			if ( empty( $get_last_category ) && !empty( $custom_taxonomy ) && $taxonomy_exists ) {

				if ( $terms = begin_taxonomy_terms( $post->ID, $custom_taxonomy, array( 'orderby' => 'parent', 'order' => 'DESC' ) ) ) {
					$main_term = $terms[0];
					$ancestors = get_ancestors( $main_term->term_id,  $custom_taxonomy );
					$ancestors = array_reverse( $ancestors );
					foreach ( $ancestors as $ancestor ) {
						$ancestor = get_term( $ancestor,  $custom_taxonomy );
						if ( ! is_wp_error( $ancestor ) && $ancestor ) {
							$mainerm = '<a href="' . get_term_link( $ancestor ) . '">' . $ancestor->name . '</a><i class="be be-arrowright"></i>';
						}
					}
					$taxonomy_terms = get_the_terms( $post->ID, $custom_taxonomy );
					$cat_id         = $taxonomy_terms[0]->term_id;
					$cat_link       = get_term_link($taxonomy_terms[0]->term_id, $custom_taxonomy);
					$cat_name       = $taxonomy_terms[0]->name;
					$postlink   = sprintf( $link, $cat_link, $cat_name );
					$post_type_link   = $mainerm . $postlink;
				}
			}
		}

		if ( 0 !== $parent ) {
			$parent_links = [];
			while ( $parent ) {
				$post_parent = get_post( $parent );
				$parent_links[] = sprintf( $link, esc_url( get_permalink( $post_parent->ID ) ), get_the_title( $post_parent->ID ) );
				$parent = $post_parent->post_parent;
			}
			$parent_links = array_reverse( $parent_links );
			$parent_string = implode( $delimiter, $parent_links );
		}

		if ( $parent_string ) {
			$breadcrumb_trail = $parent_string . $delimiter . $post_link;
		} else {
			if ( ! is_front_page() ) {
				if ( wp_is_mobile() && !is_page() ) {
					$breadcrumb_trail = sprintf(__( '正文', 'begin' ));
				} else {
					$breadcrumb_trail = $post_link;
				}
			}
		}

		// 问答插件
		if ( 'question' == get_post_type() ) {
			$qa = '<a href="'. $home_link . 'questions">'.  ap_opt( 'base_page_title' ) .'</a>' . $delimiter;
		} else {
			$qa = '';
		}

		if ( $post_type_link )
			$breadcrumb_trail = $qa . $post_type_link . $delimiter . $breadcrumb_trail;
		if ( $category_links )
			$breadcrumb_trail = $category_links . $breadcrumb_trail;
	}

	if ( is_archive() ) {
		if ( is_category() || is_tag() || is_tax() ) {
			$term_object        = get_term( $queried_object );
			$taxonomy           = $term_object->taxonomy;
			$term_id            = $term_object->term_id;
			if ( zm_get_option( 'cat_des_img' ) ) {
				if ( zm_get_option( 'cat_des' ) ) {
					if ( category_description() ) {
						$term_name = $term_object->name;
					} else {
						$term_name = '<h1 class="cat-name-des">' . $term_object->name . '</h1>';
					}
				} else {
					$term_name = $term_object->name;
				}
			} else {
				$term_name = '<h1 class="cat-name-des">' . $term_object->name . '</h1>';
			}
			$term_parent        = $term_object->parent;
			$taxonomy_object    = get_taxonomy( $taxonomy );

			if ( is_tax( 'filtersa' ) || is_tax( 'filtersb' ) || is_tax( 'filtersc' ) || is_tax( 'filtersd' ) || is_tax( 'filterse' ) || is_tax( 'filtersf' ) ) {
				$current_term_link  = sprintf(__( '筛选', 'begin' )) . $delimiter . $term_name;
			} else {
				$current_term_link  = $before . $term_name . $after;
			}

			$current_term_link  = $before . $term_name . $after;

			$parent_term_string = '';

			if ( 0 !== $term_parent ) {
				$parent_term_links = [];
				while ( $term_parent ) {
					$term = get_term( $term_parent, $taxonomy );
					$parent_term_links[] = sprintf( $link, esc_url( get_term_link( $term ) ), $term->name );
					$term_parent = $term->parent;
				}

				$parent_term_links  = array_reverse( $parent_term_links );
				$parent_term_string = implode( $delimiter, $parent_term_links );
			}

			if ( $parent_term_string ) {
				$breadcrumb_trail = $parent_term_string . $delimiter . $current_term_link;
			} else {
				$breadcrumb_trail = $current_term_link;
			}

		} elseif ( is_author() ) {
			$breadcrumb_trail =$before . $queried_object->data->display_name . sprintf( __( '的主页', 'begin' ) ) . $after;
		} elseif ( is_date() ) {
			$year     = $wp_the_query->query_vars['year'];
			$monthnum = $wp_the_query->query_vars['monthnum'];
			$day      = $wp_the_query->query_vars['day'];

			if ( $monthnum ) {
				$date_time  = DateTime::createFromFormat( '!m', $monthnum );
				$month_name = $date_time->format( 'm' );
			}

			if ( is_year() ) {
				$breadcrumb_trail = $before . $year . $after;
			} elseif ( is_month() ) {
				$year_link        = sprintf( $link, esc_url( get_year_link( $year ) ), $year );
				$breadcrumb_trail = $year_link . $delimiter . $before . $month_name . $after;
			} elseif ( is_day() ) {
				$year_link        = sprintf( $link, esc_url( get_year_link( $year ) ), $year );
				$month_link       = sprintf( $link, esc_url( get_month_link( $year, $monthnum ) ), $month_name );
				$breadcrumb_trail = $year_link . $delimiter . $month_link . $delimiter . $before . $day . $after;
			}

		} elseif ( is_post_type_archive() ) {
			$post_type        = $wp_the_query->query_vars['post_type'];
			$post_type_object = get_post_type_object( $post_type );
			$breadcrumb_trail = $before . $post_type_object->labels->singular_name . $after;
		}
	}

	if ( is_search() ) {
		global $wp_query;
		// 搜索分类名称
		$s = isset( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '';
		$terms = get_terms( 'category', array(
			'name__like' => $s,
			'hide_empty' => true
		) );

		if ( count( $terms ) > 0 && zm_get_option( 'search_option' ) == 'search_default' && zm_get_option( 'search_cat_name' ) ) {
			$catcount = '&nbsp;' . count( $terms )  . '&nbsp;' . __( '分类', 'begin' );
		} else {
			$catcount = '';
		}

		if ( isset( $_GET['s'] ) && isset( $_GET['post_type'] ) && $_GET['post_type'] == 'question' ) {
			$breadcrumb_trail = __( '搜索', 'begin' ) . $before . $delimiter . get_search_query() . $delimiter . '&nbsp;' . __( '结果', 'begin' ) . $after;
		} else {
			$breadcrumb_trail = __( '搜索', 'begin' ) . $before . $delimiter . get_search_query() . $delimiter . $wp_query->found_posts . '&nbsp;' . __( '篇', 'begin' ) . $catcount . $after;
		}
	}

	if ( is_404() ) {
		$breadcrumb_trail = $before . __( '404' ) . $after;
	}

	if ( is_paged() ) {
		$current_page = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : get_query_var( 'page' );
		if ( !is_front_page() || !zm_get_option('bulletin') ) {
			$page_addon   = $before . $delimiter . sprintf( __( '第 %s 页', 'begin' ), number_format_i18n( $current_page ) );
		}
	}

	$output  = '';
	$output .= '<div class="breadcrumb">';

	if ( is_home() || is_front_page() ) {
		if ( zm_get_option('bulletin') ) {
			get_template_part( 'template/bulletin' );
		} else {
			$output .= $home_ico . $home_welcome . $delimiter . $here_text;
			$output .= '<a href="' . $home_link . '" rel="bookmark">' . $home_text . '</a>';
		}
	} else {
		$output .= $home_ico . $here_text;
		$output .= '<span class="home-text"><a href="' . $home_link . '" rel="bookmark">' . $home_text . '</a></span>';
	}

	if ( class_exists( 'DW_Question_Answer' ) ) { 
		$dwcat = be_dw_cat_breadcrumb();
	} else {
		$dwcat = '';
	}

	if ( is_home() || is_front_page() ) {
		$output .=  $dwcat;
	} else {
		$output .= '<span class="home-text">' . $delimiter . '</span>' . $dwcat;
	}
	$output .= $breadcrumb_trail;
	$output .= $page_addon;
	$output .= '</div>';
	echo $output;
}

function be_dw_search() {
	if ( is_search() ) {
		global $wp_query;
		echo '<span class="seat"></span><a href="'. home_url('/') .'" rel="bookmark">'.  __( '首页', 'begin' ) .'</a><i class="be be-arrowright"></i>'. __( '搜索', 'begin' ) .'<span class="current"><i class="be be-arrowright"></i>'. get_search_query() .'<i class="be be-arrowright"></i>'. $wp_query->found_posts . __( '篇', 'begin' ) .'</span></div>';
	}
}

function be_breadcrumbs() {
	if ( class_exists( 'DW_Question_Answer' ) ) { 
		$term = wp_get_post_terms( get_the_ID(), 'dwqa-question_category' );
		if ( $term ) {
			$term = $term[0];
		}
		$search = isset( $_GET['qs'] ) ? esc_html( $_GET['qs'] ) : false;
		$author = isset( $_GET['user'] ) ? esc_html( $_GET['user'] ) : false;

		if ( is_singular( 'dwqa-question' ) || $search || $author || $term ) {
			be_dw_breadcrumb();
			be_dw_search();
		} else {
			be_breadcrumb();
		}
	} else {
		be_breadcrumb();
	}
}

// 结构化数据
if ( zm_get_option( 'breadcrumb_data' ) ) {
add_action( 'wp_head', 'add_breadcrumb_data' );
function add_breadcrumb_data() {
    if ( is_single() || is_page() || is_category() ) {
        echo '<script type="application/ld+json">';
        echo '{';
        echo '"@context": "https://schema.org",';
        echo '"@type": "BreadcrumbList",';
        echo '"itemListElement": [';

        echo '{';
        echo '"@type": "ListItem",';
        echo '"position": 1,';
        echo '"name": "Home",';
        echo '"item": "' . esc_url( home_url( '/' ) ) . '"';
        echo '},';

        $position = 2;

        if ( is_single() ) {
            $categories = get_the_category();
            $category_count = count( $categories );

            foreach ( $categories as $category ) {
                echo '{';
                echo '"@type": "ListItem",';
                echo '"position": ' . $position++ . ',';
                echo '"name": "' . esc_html( $category->name ) . '",';
                echo '"item": "' . esc_url( get_category_link( $category ) ) . '"';
                echo '},';
            }

            echo '{';
            echo '"@type": "ListItem",';
            echo '"position": ' . $position++ . ',';
            echo '"name": "' . esc_html( get_the_title() ) . '",';
            echo '"item": "' . esc_url( get_permalink() ) . '"';
            echo '}';
        } elseif ( is_page() ) {
            echo '{';
            echo '"@type": "ListItem",';
            echo '"position": 2,';
            echo '"name": "' . esc_html( get_the_title() ) . '",';
            echo '"item": "' . esc_url( get_permalink() ) . '"';
            echo '}';
        } elseif ( is_category() ) {
            echo '{';
            echo '"@type": "ListItem",';
            echo '"position": 2,';
            echo '"name": "' . esc_html( single_cat_title( '', false ) ) . '",';
            echo '"item": "' . esc_url( get_category_link(get_query_var( 'cat' ) ) ) . '"';
            echo '}';
        }

        echo ']';
        echo '}';
        echo '</script>';
    }
}
}