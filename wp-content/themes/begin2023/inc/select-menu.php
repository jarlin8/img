<?php
class Be_Select_Menu {
	private $begm_option;
	private $menus;
	private $menusloc;
	public function __construct() {
		add_action( 'admin_init', array( $this, 'admin_init' ), 1 );
		add_action( 'wp', array( $this,'init' ) );
	}

	public function admin_init() {
		$this->menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );
		$this->menusloc = get_registered_nav_menus();
		add_action( 'wp_ajax_begm_listitems', array( $this, 'begm_listitems' ) );
		add_action( 'wp_ajax_nopriv_begm_listitems', array( $this, 'begm_listitems' ) );
		add_action('add_meta_boxes', array( $this, 'begm_metabox' ) );
		add_action('save_post', array( $this, 'save_begm_postdata' ) );
		$this->taxonomies_metabox();
	}

	public function set_option() {
		$begm_data = get_post_meta( get_the_ID(), "_begm_post_meta", 1 );
		$this->begm_option = isset( $begm_data ) ? $begm_data : "";
	}

	public function init() {
		$this->set_option();
		add_filter( 'wp_nav_menu_args', array( $this, 'begm_menu_args' ), 10 );
		add_filter( 'nav_menu_css_class', array( $this, 'begm_nav_class' ), 10, 3 );
	}

	public function begm_metabox() {
		global $shortname;
		$screens    = array();
		$args       = array( 'public' => true );
		$output     = 'names';
		$operator   = 'and';
		$post_types = get_post_types( $args, $output, $operator );
		foreach ( $post_types as $post_type ) {
			add_meta_box( 'begm_sectionid', '选择菜单', array( $this, 'begm_meta_box' ), $post_type, 'side' );
		}
	}

	public function begm_meta_box() {
		$this->set_option();
		require_once get_template_directory() . '/inc/select-menu-class.php';
	}

	public function save_begm_postdata( $post_id ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}
		if ( isset( $_POST['post_type'] ) ) {
			if ( 'page' == $_POST['post_type'] ) {
				if ( !current_user_can( 'edit_page', $post_id ) ) {
					return $post_id;
				}
			} else {
				if ( !current_user_can( 'edit_post', $post_id ) ) {
					return $post_id;
				}
			}
			$begm_data = $_POST['begm_option'];
			if ( isset( $begm_data ) ) {
				update_post_meta( $post_id, '_begm_post_meta', $begm_data );
			}
		}
	}

	public function begm_menu_args( $args ) {
		if ( is_archive() ) {
			global $wp_query;
			$t_id = $wp_query->get_queried_object_id();
			$this->begm_option = get_option( "taxonomy_$t_id" );
		}
		if ( is_array( $this->begm_option ) ) {
			extract( $this->begm_option );
			if ( $begm_location == $args['theme_location'] ) {
				if ( isset( $begm_menu ) and $begm_menu != "" ) {
					$args['menu'] = $begm_menu;
				}
			}
		}
		return $args;
	}

	public function begm_listitems() {
		if ( $_REQUEST['menuid'] != "" ) {
			echo wp_nav_menu( array( 'walker' => new Begm_Walker(), "menu" => $_REQUEST['menuid'] ) );
		}
		exit;
	}

	public function begm_nav_class( $classes, $item, $args ) {
		if ( is_array( $this->begm_option ) and !empty( $this->begm_option['begm_menulist'] ) ) {
			if ( $this->begm_option['begm_location'] == $args->theme_location ) {
				if ( !in_array($item->ID, $this->begm_option['begm_menulist'] ) ) {
					$classes[] = "select-menu-page";
				}
			}
		}
		return $classes;
	}

	public function taxonomies_metabox() {
		$reg_tax = get_taxonomies();
		$exclude = array( 'nav_menu', 'link_category', 'post_format' );
		foreach ( $reg_tax as $taxonomy ) {
			if ( !in_array( $taxonomy, $exclude ) ) {
				add_action($taxonomy."_add_form_fields", array( $this,"taxonomy_meta_box" ), 10 );
				add_action($taxonomy."_edit_form_fields", array( $this,'taxonomy_edit_meta_field' ), 10, 2 );
				add_action('create_'.$taxonomy, array( $this, 'save_taxonomy_meta_box' ), 10, 2 );
				add_action('edited_'.$taxonomy, array( $this, 'save_taxonomy_meta_box' ), 10, 2 );
			}
		}
	}

	public function save_taxonomy_meta_box( $t_id ) {
		if ( isset( $_POST['begm_option'] ) ) {
			$term_meta = get_option( "taxonomy_$t_id" );
			$cat_keys = array_keys( $_POST['begm_option'] );
			foreach ( $cat_keys as $key ) {
				if ( isset( $_POST['begm_option'][$key] ) ) {
					$term_meta[$key] = $_POST['begm_option'][$key];
				}
			}
			update_option( "taxonomy_$t_id", $term_meta );
		}
	}

	public function taxonomy_meta_box() {
		?>
		<div class="form-field">
			<label for="term_meta[custom_term_meta]">选择菜单</label>
			<?php require_once get_template_directory() . '/inc/select-menu-class.php'; ?>
		</div>
		<?php
	}

	public function taxonomy_edit_meta_field( $term ) {
		$t_id = $term->term_id;
		$this->begm_option =  get_option( "taxonomy_$t_id" );
		?>
		<tr class="form-field">
			<th scope="row" valign="top">
				<label for="term_meta[custom_term_meta]">选择菜单</label>
			</th>
			<td>
				<?php require_once get_template_directory() . '/inc/select-menu-class.php'; ?>
			</td>
		</tr>
		<?php
	}
}

class Begm_Walker extends Walker_Nav_Menu {
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		global $wp_query;
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		$class_names = $value = '';
		$classes = empty( $item->classes ) ? array() : ( array ) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;
		$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = ' class="' . esc_attr( $class_names ) . '"';
		$id = apply_filters('nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args);
		$id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';
		$output .= $indent . '<li' . $id . $value . $class_names .'>';
		$attributes  = ! empty( $item->attr_title ) ? ' title="' . esc_attr( $item->attr_title ) . '"' : '';
		$attributes .= ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) . '"' : '';
		$attributes .= ! empty( $item->xfn ) ? ' rel="' . esc_attr( $item->xfn ) . '"' : '';
		$attributes .= ! empty( $item->url ) ? ' href="' . esc_attr( $item->url ) . '"' : '';
		$screen  = get_current_screen();

		if ( is_admin() ) {
			if ( isset( $screen->taxonomy ) and isset( $_GET['tag_ID'] ) ) {
				$t_id = $_GET['tag_ID'];
				$begm_postOption = get_option( "taxonomy_$t_id" );
			} elseif ( isset( $screen->post_type ) ) {
				$begm_postOption =get_post_meta( get_the_ID(), "_begm_post_meta", 1 );
			}
		} else {
			$begm_postOption =get_post_meta( get_the_ID(), "_begm_post_meta", 1 );
		}

		$checked ="";
		if ( empty($begm_postOption['begm_menulist'] ) ) {
			$checked ='checked="checked"';
		} elseif ( in_array( $item->ID, $begm_postOption['begm_menulist'] ) ) {
			$checked ='checked="checked"';
		}

		$item_output = $args->before;
		if ( isset( $item->title ) ) {
			$item_output .= '<input type="checkbox" ' . $checked . ' name="begm_option[begm_menulist][]" value="' . $item->ID . '">';
		}

		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		$item_output .= '';
		$item_output .= $args->after;
		$output .= $item_output;
	}
}

new Be_Select_Menu();