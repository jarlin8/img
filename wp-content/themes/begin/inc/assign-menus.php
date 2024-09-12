<?php
if ( ! defined( 'ABSPATH' ) ) exit;
class BE_Conditional_Menus {
	public function __construct() {
		add_action( 'init', array( $this, 'setup' ), 10 );
	}

	public function setup() {
		if ( is_admin() ) {
			add_action( 'load-nav-menus.php', array( $this, 'init' ) );
			add_action( 'after_menu_locations_table', array( $this, 'conditions_dialog' ) );
			add_filter( 'be_cm_conditions_post_types', array( $this, 'exclude_attachments_from_conditions' ) );
			add_action( 'wp_ajax_be_cm_get_conditions', array( $this, 'ajax_get_conditions' ) );
			add_action( 'wp_ajax_be_create_inner_page', array( $this, 'ajax_create_inner_page' ) );
			add_action( 'wp_ajax_be_create_page_pagination', array( $this, 'ajax_create_page_pagination' ) );
			add_action( 'wp_delete_nav_menu', array( $this, 'wp_delete_nav_menu' ) );
		} else {
			add_filter( 'wp_nav_menu_args', array( $this, 'setup_menus' ) );
			add_filter( 'theme_mod_nav_menu_locations', array( $this, 'theme_mod_nav_menu_locations' ), 99 );
		}
	}

	public function get_options() {
		remove_filter( 'theme_mod_nav_menu_locations', array( $this, 'theme_mod_nav_menu_locations' ), 99 );
		$options = get_theme_mod( 'be_conditional_menus', array() );
		$options = wp_parse_args( $options, get_nav_menu_locations() );
		if ( ! is_admin() ) {
			add_filter( 'theme_mod_nav_menu_locations', array( $this, 'theme_mod_nav_menu_locations' ), 99 );
		}
		return $options;
	}

	public function theme_mod_nav_menu_locations( $locations = array() ) {
		if ( ! empty( $locations ) ) {
			$menu_assignments = $this->get_options();
			$hasLng=function_exists( 'pll_current_language' ) && function_exists( 'pll_default_language' );
			foreach( $locations as $location => $menu_id ) {
				if ( empty( $menu_assignments[$location] ) ) continue;

				$menus = $menu_assignments[$location];

				if ( $hasLng===true ) {
					if ( pll_current_language() !== pll_default_language() ) {
						$polylang_location = $location . '___' . pll_current_language();
						
						if ( ! empty( $menu_assignments[$polylang_location] ) ) {
							$menus = $menu_assignments[$polylang_location];
						}
					}
				}

				if ( is_array( $menus ) ) {
					foreach( $menus as $id => $new_menu ) {
						if ( $new_menu['menu'] == '' || $new_menu['condition'] == '' ) {
							continue;
						}
						if ( $this->check_visibility( $new_menu['condition'] ) ) {
							if ( $new_menu[ 'menu' ] == 0 ) {
								unset( $locations[$location] );
							} else {
								$locations[$location] = $new_menu[ 'menu' ];
							}
							continue;
						}
					}
				}
			}
		}

		return $locations;
	}

	public function setup_menus( $args ) {
		$menu_assignments = $this->get_options();
		if ( ! isset( $args['menu'] ) && ! empty( $args['theme_location'] ) && isset( $menu_assignments[ $args['theme_location'] ] ) ) {
			if ( is_array( $menu_assignments[$args['theme_location']] ) && ! empty( $menu_assignments[$args['theme_location']] ) ) {
				foreach( $menu_assignments[$args['theme_location']] as $id => $new_menu ) {
					if ( $new_menu['menu'] == '' || $new_menu['condition'] == '' ) {
						continue;
					}
					if ( $this->check_visibility( $new_menu['condition'] ) ) {
						if ( $new_menu[ 'menu' ] == 0 ) {
							add_filter( 'pre_wp_nav_menu', array( $this, 'disable_menu' ), 10, 2 );
							$args['echo'] = false;
						} else {
							$args['menu'] = $new_menu[ 'menu' ];
							$args['theme_location'] = apply_filters( 'conditional_menus_theme_location', '', $new_menu, $args );
						}
						continue;
					}
				}
			}
		}

		return $args;
	}

	public function disable_menu( $output, $args ) {
		remove_filter( 'pre_wp_nav_menu', array( $this, 'disable_menu' ), 10, 2 );
		return '';
	}

	public function init() {
		if ( isset( $_GET['action'] ) && 'locations' === $_GET['action'] ) {
			$this->save_options();
			add_action( 'admin_enqueue_scripts', array( &$this, 'admin_enqueue' ) );
		}
	}

	public function save_options() {
		if ( isset( $_POST['menu-locations'] ) ) {
			$be_cm = isset( $_POST['be_cm'] ) ? $_POST['be_cm'] : array();
			set_theme_mod( 'be_conditional_menus', $be_cm );
		}
	}

	public function ajax_get_conditions() {
		$selected = array();
		if ( isset( $_POST['selected'] ) ) {
			parse_str( $_POST['selected'], $selected );
		}
		echo $this->get_visibility_options( $selected );
		die;
	}

	public function admin_enqueue() {
		global $_wp_registered_nav_menus;

		wp_enqueue_script( 'assign-menus', get_template_directory_uri() . '/js/assign-menus.js', array( 'jquery', 'jquery-ui-tabs' ), true );
		wp_localize_script( 'assign-menus', 'be_cm', array(
			'nav_menus' => array_keys( $_wp_registered_nav_menus ),
			'options' => $this->get_options(),
			'lang' => array(
				'conditions' =>'选择',
				'add_assignment' =>'<span class="dashicons dashicons-plus-alt2"></span>添加',
				'disable_menu' => '禁用菜单',
			),
		) );
	}

	public function get_conditions_dialog() {
		$output = '
			<div id="be-cm-conditions" class="be-cm-conditions-container be-admin-lightbox tf_clearfix" style="display: none;" data-item="">
				<h3 class="be-cm-title">选择</h3>
				<a href="#" class="be-cm-close"><span class="dashicons dashicons-no-alt"></span></a>
				<div class="lightbox_container">
				</div>
				<a href="#" class="button uncheck-all">取消所有</a>
				<a href="#" class="button button-primary be-cm-save alignright">保存</a>
			</div>
			</div>
			<div id="be-cm-overlay"></div>
		';

		return $output;
	}

	public function conditions_dialog() {
		echo $this->get_conditions_dialog();
	}

	function exclude_attachments_from_conditions( $post_types ) {
		unset( $post_types['attachment'] );
		return $post_types;
	}

	public function check_visibility( $logic ) {
		parse_str( $logic, $logic );
		$query_object = get_queried_object();

		if ( isset( $logic['general']['logged'] ) ) {
			if ( ! is_user_logged_in() ) {
				return false;
			}
			unset( $logic['general']['logged'] );
			if ( empty( $logic['general'] ) ) {
			    unset( $logic['general'] );
			}
		}

		if ( ! empty( $logic['roles'] ) && ! count( array_intersect( wp_get_current_user()->roles, array_keys( $logic['roles'], true ) ) ) ) {
			return false;
		}
		unset( $logic['roles'] );

		if ( ! empty( $logic ) ) {
			if ( ( isset( $logic['general']['home'] ) && is_front_page())
				|| (isset( $logic['general']['404'] ) &&  is_404() )
				|| (isset( $logic['general']['page'] ) &&  is_page() &&  ! is_front_page() )
				|| (isset( $logic['general']['single'] ) && is_single() )
				|| ( isset( $logic['general']['search'] )  && is_search() )
				|| ( isset( $logic['general']['author'] ) && is_author() )
				|| ( isset( $logic['general']['category'] ) && is_category())
				|| ( isset($logic['general']['tag']) && is_tag() )
				|| ( isset($logic['general']['date']) && is_date() )
				|| ( isset($logic['general']['year'])  && is_year())
				|| ( isset($logic['general']['month']) && is_month())
				|| (isset($logic['general']['day']) && is_day())
				|| ( is_singular() && isset( $logic['general'][$query_object->post_type] ) && $query_object->post_type !== 'page' && $query_object->post_type !== 'post' )
				|| ( is_tax() && isset( $logic['general'][$query_object->taxonomy] ) )
				|| ( is_post_type_archive() && isset( $logic['general'][ $query_object->name . '_archive' ] ) )
			) {
				return true;
			} else {
				if ( ! empty( $logic['tax'] ) ) {
					if (is_singular()){
						if ( !empty($logic['tax']['category_single'])){
							if ( empty( $logic['tax']['category_single']['category'] ) ) {
								$cat = get_the_category();
								if (!empty($cat)){
									foreach($cat as $c){
										if ($c->taxonomy === 'category' && isset($logic['tax']['category_single']['category'][$c->slug])){
											return true;
										}
									}
								}
								unset($logic['tax']['category_single']['category']);
							}
							foreach ($logic['tax']['category_single'] as $key => $tax) {
								$terms = get_the_terms( get_the_ID(), $key);
								if ( $terms !== false && !is_wp_error($terms) && is_array($terms) ) {
									foreach ( $terms as $term ) {
										if ( isset($logic['tax']['category_single'][$key][$term->slug]) ){
											return true;
										}
									}
								}
							}
						}
					} else {
						foreach( $logic['tax'] as $tax => $terms ) {
							$terms = array_keys( $terms );
							if ( ( $tax === 'category' && is_category( $terms ) )
								|| ( $tax === 'post_tag' && is_tag( $terms ) )
								|| ( is_tax( $tax, $terms ) )
							) {
								return true;
							}
						}
					}
				}

				if ( ! empty( $logic['post_type'] ) ) {

					foreach( $logic['post_type'] as $post_type => $posts ) {
						$posts = array_keys( $posts );

						if (
							( $post_type === 'post' && is_single( $posts ) )
							|| ( $post_type === 'page' && (
								( 
									( is_page( $posts )
									|| ( isset( $query_object->post_parent ) && $query_object->post_parent > 0 &&
									     ( in_array( '/' . str_replace( strtok( get_home_url(), '?'), '', remove_query_arg( 'lang', get_permalink( $query_object->ID ) ) ), $posts ) ||
									     in_array( str_replace( strtok( get_home_url(), '?'), '', remove_query_arg( 'lang', get_permalink( $query_object->ID ) ) ), $posts ) ||
									     in_array( '/'.$this->child_post_name($query_object).'/', $posts ) )
									  )
								) )
								|| ( ! is_front_page() && is_home() &&  in_array( get_post_field( 'post_name', get_option( 'page_for_posts' ) ), $posts,true ) ) // check for Posts page
								|| ( class_exists( 'WooCommerce' ) && function_exists( 'is_shop' ) && is_shop() && in_array( get_post_field( 'post_name', wc_get_page_id( 'shop' ) ), $posts )  ) // check for WC Shop page
							) )
							|| ( is_singular( $post_type ) && in_array( $query_object->post_name, $posts,true ) )
							|| ( is_singular( $post_type ) && isset( $query_object->post_parent ) && $query_object->post_parent > 0 && in_array( '/'.$this->child_post_name($query_object).'/', $posts,true ) )
							|| ( is_singular( $post_type ) && get_post_type() === $post_type && in_array( 'E_ALL', $posts ) )
						) {
							return true;
						}
					}
				}
			}
			return false;
		}

		return true;
	}

	function ajax_create_page_pagination() {
		$current_page = isset( $_POST['current_page'] ) ? $_POST['current_page'] : 1;
		$num_of_pages = isset( $_POST['num_of_pages'] ) ? $_POST['num_of_pages'] : 0;
		echo $this->create_page_pagination($current_page, $num_of_pages);
		die;
	}

	function create_page_pagination( $current_page, $num_of_pages ) {
		$links_in_the_middle = 4;
		$links_in_the_middle_min_1 = $links_in_the_middle - 1;
		$first_link_in_the_middle   = $current_page - floor( $links_in_the_middle_min_1 / 2 );
		$last_link_in_the_middle    = $current_page + ceil( $links_in_the_middle_min_1 / 2 );
		if ( $first_link_in_the_middle <= 0 ) {
			$first_link_in_the_middle = 1;
		}
		if ( ( $last_link_in_the_middle - $first_link_in_the_middle ) != $links_in_the_middle_min_1 ) {
			$last_link_in_the_middle = $first_link_in_the_middle + $links_in_the_middle_min_1;
		}
		if ( $last_link_in_the_middle > $num_of_pages ) {
			$first_link_in_the_middle = $num_of_pages - $links_in_the_middle_min_1;
			$last_link_in_the_middle  = (int) $num_of_pages;
		}
		if ( $first_link_in_the_middle <= 0 ) {
			$first_link_in_the_middle = 1;
		}
		$pagination = '';
		if ( $current_page != 1 ) {
			$pagination .= '<a href="/page/' . ( $current_page - 1 ) . '" class="prev page-numbers ti-angle-left"/><i class="dashicons dashicons-arrow-left-alt2"></i>';
		}
		if ( $first_link_in_the_middle >= 3 && $links_in_the_middle < $num_of_pages ) {
			$pagination .= '<a href="/page/" class="page-numbers">1</a>';

			if ( $first_link_in_the_middle != 2 ) {
				$pagination .= '<span class="page-numbers extend">...</span>';
			}
		}
		for ( $i = $first_link_in_the_middle; $i <= $last_link_in_the_middle; $i ++ ) {
			if ( $i == $current_page ) {
				$pagination .= '<span class="page-numbers current">' . $i . '</span>';
			} else {
				$pagination .= '<a href="/page/' . $i . '" class="page-numbers">' . $i . '</a>';
			}
		}
		if ( $last_link_in_the_middle < $num_of_pages ) {
			if ( $last_link_in_the_middle != ( $num_of_pages - 1 ) ) {
				$pagination .= '<span class="page-numbers extend">...</span>';
			}
			$pagination .= '<a href="/page/' . $num_of_pages . '" class="page-numbers">' . $num_of_pages . '</a>';
		}
		if ( $current_page != $last_link_in_the_middle ) {
			$pagination .= '<a href="/page/' . ( $current_page + $i ) . '" class="next page-numbers ti-angle-right"><i class="dashicons dashicons-arrow-right-alt2"></i></a>';
		}

		return $pagination;
	}

	function ajax_create_inner_page() {
		$selected = array();
		if ( isset( $_POST['selected'] ) ) {
			parse_str( $_POST['selected'], $selected );
		}
		$type= isset( $_POST['type'] ) ? $_POST['type'] : 'pages';
		echo $this->create_inner_page($type, $selected);
		die;
	}

	function create_inner_page( $type, $selected ) {
		$posts_per_page = 26;
		$output = '';
		$new_checked = array();
		switch ($type) {
			case 'page':
				$key = 'page';
				$posts = get_posts( array( 'post_type' => $key, 'posts_per_page' => -1, 'post_status' => 'publish', 'order' => 'ASC', 'orderby' => 'title',  'no_found_rows' => true) );
				if ( ! empty( $posts ) ) {
					$i = 1;
					$page_id = 1;
					$num_of_single_pages = count($posts);
					$num_of_pages = (int) ceil( $num_of_single_pages / $posts_per_page );
					$output .= '<div class="be-visibility-items-inner" data-items="' . $num_of_single_pages . '" data-pages="' . $num_of_pages . '">';
					$output .= '<label class="tf_cm_select_sub"><input data-type="page" type="checkbox" />应用到子页面</label>';
					$output .= '<div class="be-visibility-items-page be-visibility-items-page-' . $page_id . '">';
					foreach ( $posts as $post ) :
						$data = ' data-slug="'.$post->post_name.'"';
						$post->post_name = $this->child_post_name($post);
					if ( $post->post_parent > 0 ) {
							$post->post_name = '/' . $post->post_name . '/';
							$parent = get_post($post->post_parent);
							$data .= ' data-parent="'.$parent->post_name.'"';
						}
						$checked = isset( $selected['post_type'][ $key ][ $post->post_name ] ) ? checked( $selected['post_type'][ $key ][ $post->post_name ], 'on', false ) : '';
						if (!empty($checked)){
							$new_checked[] = urlencode("post_type[$key][$post->post_name]").'=on';
						}

						$output .= '<label><input'.$data.' type="checkbox" name="post_type[' . $key . '][' . $post->post_name . ']"' . $checked . ' /><span data-tooltip="'.get_permalink($post->ID).'">' . $post->post_title . '</span></label>';
						if ( $i === ($page_id * $posts_per_page) ) {
							$output .= '</div>';
							++$page_id;
							$output .= '<div class="be-visibility-items-page be-visibility-items-page-' . $page_id . ' is-hidden">';
						}
						++$i;
					endforeach;
					$output .= '</div>';
					if ( $num_of_pages > 1 ) {
						$output .= '<div class="be-visibility-pagination">';
						$output .= $this->create_page_pagination( 1, $num_of_pages );
						$output .= '</div>';
					}
					$output .= '</div>';
				}
				break;

			case 'category_single':
				$m_key = 'category_single';
				$taxonomies = get_taxonomies( array( 'public' => true ) );

				if ( ! empty( $taxonomies ) ) {
					$post_id = 1;
					foreach ( $taxonomies  as $key => $tax) {
						$terms = get_terms( $key, array( 'hide_empty' => true ) );
						$output .= '<div id="visibility-tab-' . $key . '" class="be-visibility-inner-tab '. ($post_id > 1 ? 'is-hidden' : '') .'">';
						if ( ! empty( $terms ) ) {
							$i                   = 1;
							$page_id             = 1;
							$num_of_single_pages = count( $terms );
							$num_of_pages        = (int) ceil( $num_of_single_pages / $posts_per_page );
							$output              .= '<div class="be-visibility-items-inner" data-items="' . $num_of_single_pages . '" data-pages="' . $num_of_pages . '">';
							$output              .= '<div class="be-visibility-items-page be-visibility-items-page-' . $page_id . '">';
							foreach ( $terms as $term ) :
								$checked = isset( $selected['tax'][$m_key][$key][ $term->slug ] ) ? checked( $selected['tax'][$m_key][$key][ $term->slug ], 'on', false ) : '';
								if (!empty($checked)){
									$new_checked[] = urlencode("tax[$m_key][$key][$term->slug]").'=on';
								}
								$output  .= '<label><input type="checkbox" name="tax[' . $m_key . '][' . $key . '][' . $term->slug . ']" ' . $checked . ' /><span data-tooltip="'.get_term_link($term).'">' . $term->name . '</span></label>';
								if ( $i === ( $page_id * $posts_per_page ) ) {
									$output .= '</div>';
									$page_id ++;
									$output .= '<div class="be-visibility-items-page be-visibility-items-page-' . $page_id . ' is-hidden">';
								}
								++$i;
							endforeach;
							$output .= '</div>';
							if ( $num_of_pages > 1 ) {
								$output .= '<div class="be-visibility-pagination">';
								$output .= $this->create_page_pagination( 1, $num_of_pages );
								$output .= '</div>';
							}
							$output .= '</div>';
						}
						$output .= '</div></div></div>';
						++$post_id;
					}
					$output .= '</div>';
				}
				break;

			case 'category':
				$key = 'category';
				$terms = get_terms( 'category', array( 'hide_empty' => true ) );
				if ( ! empty( $terms ) ) {
					$i                   = 1;
					$page_id             = 1;
					$num_of_single_pages = count( $terms );
					$num_of_pages        = (int) ceil( $num_of_single_pages / $posts_per_page );
					$output              .= '<div class="be-visibility-items-inner" data-items="' . $num_of_single_pages . '" data-pages="' . $num_of_pages . '">';
					$output		   		 .= '<label class="tf_cm_select_sub"><input data-type="category" type="checkbox" />应用到子分类</label>';
					$output              .= '<div class="be-visibility-items-page be-visibility-items-page-' . $page_id . '">';
					foreach ( $terms as $term ) :
						$data = ' data-slug="'.$term->slug.'"';
						if ( $term->parent != '0' ) {
							$parent  = get_term( $term->parent, $key);
							$data .= ' data-parent="'.$parent->slug.'"';
						}
						$checked = isset( $selected['tax'][ $key ][ $term->slug ] ) ? checked( $selected['tax'][ $key ][ $term->slug ], 'on', false ) : '';
						if (!empty($checked)){
							$new_checked[] = urlencode("tax[$key][$term->slug]").'=on';
						}
						$output  .= '<label><input'.$data.' type="checkbox" name="tax[' . $key . '][' . $term->slug . ']" ' . $checked . ' /><span data-tooltip="'.get_term_link($term).'">' . $term->name . '</span></label>';
						if ( $i === ( $page_id * $posts_per_page ) ) {
							$output .= '</div>';
							$page_id ++;
							$output .= '<div class="be-visibility-items-page be-visibility-items-page-' . $page_id . ' is-hidden">';
						}
						$i++;
					endforeach;
					$output .= '</div>';
					if ( $num_of_pages > 1 ) {
						$output .= '<div class="be-visibility-pagination">';
						$output .= $this->create_page_pagination( 1, $num_of_pages );
						$output .= '</div>';
					}
					$output .= '</div>';
				}
				break;

			default :
				$post_types = apply_filters( 'be_hooks_visibility_post_types', get_post_types( array( 'public' => true ) ) );
				unset( $post_types['page'] );
				$post_types = array_map( 'get_post_type_object', $post_types );
				$post_id = 1;
				foreach ( $post_types as $key => $post_type ) {
					$output .= '<div id="visibility-tab-' . $key . '" class="be-visibility-inner-tab '. ($post_id > 1 ? 'is-hidden' : '') .'">';
					$posts = get_posts( array( 'post_type' => $key, 'posts_per_page' => -1, 'post_status' => 'publish', 'order' => 'ASC', 'orderby' => 'title',  'no_found_rows' => true ) );
					$checked = isset( $selected['post_type'][ $key ][ 'E_ALL' ] ) ? checked( $selected['post_type'][ $key ][ 'E_ALL' ], 'on', false ) : '';
					if (!empty($checked)){
						$new_checked[] = urlencode("post_type[$key][E_ALL]").'=on';
					}
					$output .= '<p><input type="checkbox" name="' . esc_attr( 'post_type[' . $key . '][E_ALL]' ) . '" ' . $checked . ' />应用到所有</p>';
					if ( ! empty( $posts ) ) {
						$i                   = 1;
						$page_id             = 1;
						$num_of_single_pages = count( $posts );
						$num_of_pages        = (int) ceil( $num_of_single_pages / $posts_per_page );
						$output              .= '<div class="be-visibility-items-inner" data-items="' . $num_of_single_pages . '" data-pages="' . $num_of_pages . '">';
						$output              .= '<div class="be-visibility-items-page be-visibility-items-page-' . $page_id . '">';
						foreach ( $posts as $post ) :
							$post->post_name = $this->child_post_name($post);
							if ( $post->post_parent > 0 ) {
								$post->post_name = '/' . $post->post_name . '/';
							}
							$checked = isset( $selected['post_type'][ $key ][ $post->post_name ] ) ? checked( $selected['post_type'][ $key ][ $post->post_name ], 'on', false ) : '';
							if (!empty($checked)){
								$new_checked[] = urlencode("post_type[$key][$post->post_name]").'=on';
							}

							$output .= '<label><input type="checkbox" name="' . esc_attr( 'post_type[' . $key . '][' . $post->post_name . ']' ) . '" ' . $checked . ' /><span data-tooltip="'.get_permalink($post->ID).'">' . esc_html( $post->post_title ) . '</span></label>';
							if ( $i === ( $page_id * $posts_per_page ) ) {
								$output .= '</div>';
								$page_id ++;
								$output .= '<div class="be-visibility-items-page be-visibility-items-page-' . $page_id . ' is-hidden">';
							}
							++$i;
						endforeach;
						$output .= '</div>';
						if ( $num_of_pages > 1 ) {
							$output .= '<div class="be-visibility-pagination">';
							$output .= $this->create_page_pagination( 1, $num_of_pages );
							$output .= '</div>';
						}
					}
					$output .= '</div></div></div>';
					++$post_id;
				}
				$output .= '</div>';
				break;
		}
		wp_reset_postdata();
		$values = explode('&',$_POST['original_values']);
		if (!empty($values) && is_array($values)){
			$values = array_diff($values,$new_checked);
		}
		$values = empty($values) ? '' : implode('&',$values);
		$result = json_encode(array('original_values'=>$values,'html'=>$output));
		return $result;
	}

	private function child_post_name($post) {
		$str = $post->post_name;

		if ( $post->post_parent > 0 ) {
			$parent = get_post($post->post_parent);
			if ( $parent ) {
				$parent->post_name = $this->child_post_name($parent);
				$str = $parent->post_name . '/' . $str;
			}
		}

		return $str;
	}
	public function get_visibility_options( $selected = array() ) {
		$post_types = apply_filters( 'be_hooks_visibility_post_types', get_post_types( array( 'public' => true ) ) );
		unset( $post_types['page'] );
		$post_types = array_map( 'get_post_type_object', $post_types );

		$taxonomies = apply_filters( 'be_hooks_visibility_taxonomies', get_taxonomies( array( 'public' => true ) ) );
		$taxonomies = array_map( 'get_taxonomy', $taxonomies );


		$output = '<form id="visibility-tabs" class="ui-tabs"><ul class="tf_clearfix">';

		$output .= '<li><a href="#visibility-tab-general">全局</a></li>';
		$output .= '<li><a href="#visibility-tab-categories" class="be-visibility-tab" data-type="category">分类归档</a></li>';
		$output .= '<li><a href="#visibility-tab-categories-singles" class="be-visibility-tab" data-type="category_single">分类文章</a></li>';
		$output .= '<li><a href="#visibility-tab-pages" class="be-visibility-tab" data-type="page">页面</a></li>';
		$output .= '<li><a href="#visibility-tab-post-types" class="be-visibility-tab" data-type="post">文章</a></li>';
		$output .= '<li><a href="#visibility-tab-taxonomies">分类法</a></li>';
		$output .= '<li><a href="#visibility-tab-userroles">用户角色</a></li>';
		$output .= '</ul>';

		$output .= '<div id="visibility-tab-general" class="be-visibility-options tf_clearfix">';
			$checked = isset( $selected['general']['home'] ) ? checked( $selected['general']['home'], 'on', false ) : '';
			$output .= '<label><input type="checkbox" name="general[home]" '. $checked .' /><span data-tooltip="'.get_home_url().'">首页</span></label>';
			$checked = isset( $selected['general']['404'] ) ? checked( $selected['general']['404'], 'on', false ) : '';
			$output .= '<label><input type="checkbox" name="general[404]" '. $checked .' />404</label>';
			$checked = isset( $selected['general']['page'] ) ? checked( $selected['general']['page'], 'on', false ) : '';
			$output .= '<label><input type="checkbox" name="general[page]" '. $checked .' />页面</label>';
			$checked = isset( $selected['general']['single'] ) ? checked( $selected['general']['single'], 'on', false ) : '';
			$output .= '<label><input type="checkbox" name="general[single]" '. $checked .' />正文</label>';
			$checked = isset( $selected['general']['search'] ) ? checked( $selected['general']['search'], 'on', false ) : '';
			$output .= '<label><input type="checkbox" name="general[search]" '. $checked .' />搜索</label>';
			$checked = isset( $selected['general']['category'] ) ? checked( $selected['general']['category'], 'on', false ) : '';
			$output .= '<label><input type="checkbox" name="general[category]" '. $checked .' />分类归档</label>';
			$checked = isset( $selected['general']['tag'] ) ? checked( $selected['general']['tag'], 'on', false ) : '';
			$output .= '<label><input type="checkbox" name="general[tag]" '. $checked .' />标签归档</label>';
			$checked = isset( $selected['general']['author'] ) ? checked( $selected['general']['author'], 'on', false ) : '';
			$output .= '<label><input type="checkbox" name="general[author]" '. $checked .' />作者页面</label>';
			$checked = isset($selected['general']['date']) ? checked($selected['general']['date'], 'on', false) : '';
			$output .= '<label><input type="checkbox" name="general[date]" ' . $checked . ' />日期归档</label>';
			$checked = isset($selected['general']['year']) ? checked($selected['general']['year'], 'on', false) : '';
			$output .= '<label><input type="checkbox" name="general[year]" ' . $checked . ' />年份归档</label>';
			$checked = isset($selected['general']['month']) ? checked($selected['general']['month'], 'on', false) : '';
			$output .= '<label><input type="checkbox" name="general[month]" ' . $checked . ' />月份归档</label>';
			$checked = isset($selected['general']['day']) ? checked($selected['general']['day'], 'on', false) : '';
			$output .= '<label><input type="checkbox" name="general[day]" ' . $checked . ' />日归档</label>';
			$checked = isset( $selected['general']['logged'] ) ? checked( $selected['general']['logged'], 'on', false ) : '';
			$output .= '<label><input type="checkbox" name="general[logged]" '. $checked .' />登录状态</label>';

			foreach( get_post_types( array( 'public' => true, 'exclude_from_search' => false, '_builtin' => false ) ) as $key => $post_type ) {
				$post_type = get_post_type_object( $key );
				$checked = isset( $selected['general'][$key] ) ? checked( $selected['general'][$key], 'on', false ) : '';
				$output .= '<label><input type="checkbox" name="general['. $key .']" '. $checked .' />' . $post_type->labels->singular_name . '</label>';
				$checked = isset( $selected['general'][ $key . '_archive' ] ) ? checked( $selected['general'][$key . '_archive'], 'on', false ) : '';
				//$output .= '<label><input type="checkbox" name="general['. $key . '_archive' .']" '. $checked .' />' . sprintf( __( '%s Archive View', 'be-cm' ), $post_type->labels->singular_name ) . '</label>';
			}

			foreach( get_taxonomies( array( 'public' => true, '_builtin' => false ) ) as $key => $tax ) {
				$tax = get_taxonomy( $key );
				$checked = isset( $selected['general'][$key] ) ? checked( $selected['general'][$key], 'on', false ) : '';
				$output .= '<label><input type="checkbox" name="general['. $key .']" '. $checked .' />' . $tax->label . '</label>';
			}

		$output .= '</div>';

		wp_reset_postdata();
		$output .= '<div id="visibility-tab-pages" class="be-visibility-options be-visibility-type-options tf_clearfix" data-type="page">';
		$output .= '</div>';

		$output .= '<div id="visibility-tab-categories-singles" class="be-visibility-options tf_clearfix" data-type="category_single">';
		$output .= '<div id="be-visibility-category-single-inner-tabs" class="be-visibility-inner-tabs">';
		$output .= '<ul class="inline-tabs tf_clearfix">';
		foreach( $taxonomies as $key => $tax ) {
			$output .= '<li><a href="#visibility-tab-' . $key . '">' . $tax->label . '</a></li>';
		}
		$output .= '</ul>';
		$output .= '<div class="be-visibility-type-options tf_clearfix" data-type="category_single"></div>';
		$output .= '</div>';
		$output .= '</div>';

		$output .= '<div id="visibility-tab-categories" class="be-visibility-options be-visibility-type-options tf_clearfix" data-type="category">';
		$output .= '</div>';

		$output .= '<div id="visibility-tab-post-types" class="be-visibility-options tf_clearfix" data-type="post">';
		$output .= '<div id="be-visibility-post-types-inner-tabs" class="be-visibility-inner-tabs">';
		$output .= '<ul class="inline-tabs tf_clearfix">';
			foreach( $post_types as $key => $post_type ) {
				$output .= '<li><a href="#visibility-tab-' . $key . '">' . $post_type->label . '</a></li>';
			}
		$output .= '</ul>';
		$output .= '<div class="be-visibility-type-options tf_clearfix" data-type="post"></div>';
		$output .= '</div>';
		$output .= '</div>';

		unset( $taxonomies['category'] );
		$output .= '<div id="visibility-tab-taxonomies" class="be-visibility-options tf_clearfix">';
			$output .= '<div id="be-visibility-taxonomies-inner-tabs" class="be-visibility-inner-tabs">';
			$output .= '<ul class="inline-tabs tf_clearfix">';
				foreach( $taxonomies as $key => $tax ) {
					$output .= '<li><a href="#visibility-tab-' . $key . '">' . $tax->label . '</a></li>';
				}
			$output .= '</ul>';
			foreach( $taxonomies as $key => $tax ) {
				$output .= '<div id="visibility-tab-'. $key .'" class="tf_clearfix">';
				$terms = get_terms( $key, array( 'hide_empty' => true ) );
				if ( ! empty( $terms ) ) : foreach( $terms as $term ) :
					$checked = isset( $selected['tax'][$key][$term->slug] ) ? checked( $selected['tax'][$key][$term->slug], 'on', false ) : '';
					$output .= '<label><input type="checkbox" name="tax['. $key .']['. $term->slug .']" '. $checked .' /><span data-tooltip="'.get_term_link($term).'">' . $term->name . '</span></label>';
				endforeach; endif;
				$output .= '</div>';
			}
		$output .= '</div>';
		$output .= '</div>';

		$output .= '<div id="visibility-tab-userroles" class="be-visibility-options tf_clearfix">';
		foreach( $GLOBALS['wp_roles']->roles as $key => $role ) {
			$checked = isset( $selected['roles'][$key] ) ? checked( $selected['roles'][$key], 'on', false ) : '';
			$output .= '<label><input type="checkbox" name="roles['. $key .']" '. $checked .' />' . $role['name'] . '</label>';
		}
		$output .= '</div>';

		$output .= '</form>';
		$values = explode( '&',$_POST['selected'] );
		if ( !empty( $values) && is_array( $values ) ){
			foreach ( $values as $k=>$val ){
				if ( 0 === strpos( $val,'general' ) || 0 === strpos( $val,'tax%5Bpost_tag%5D' ) || 0 === strpos( $val,'roles' ) ){
					unset($values[$k]);
				}
			}
			$values = implode('&',$values);
		}else{
			$values = '';
		}
		$output .= '<input type="hidden" id="be-cm-original-conditions" value="'.$values.'"/>';
		return $output;
	}

	function wp_delete_nav_menu( $menu_id ) {
		$options = get_theme_mod( 'be_conditional_menus', array() );
		if ( ! empty( $options ) ) {
			foreach( $options as $location => $assignments ) {
				if ( is_array( $assignments ) && ! empty( $assignments ) ) {
					foreach( $assignments as $key => $menu ) {
						if ( $menu['menu'] == $menu_id ) {
							unset( $options[$location][$key] );
						}
					}
				}
			}
		}
		set_theme_mod( 'be_conditional_menus', $options );
	}
}
$be_cm = new BE_Conditional_Menus;