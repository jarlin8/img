<?php
// 自定义图标
class iconfont {
	function __construct(){
		add_filter( 'nav_menu_css_class', array( $this, 'nav_menu_css_class' ) );
		add_filter( 'walker_nav_menu_start_el', array( $this, 'walker_nav_menu_start_el' ), 10, 4 );
	}
	function nav_menu_css_class( $classes ){
		if ( is_array( $classes ) ){
			$tmp_classes = preg_grep( '/^(zm)(-\S+)?$/i', $classes );
			if ( !empty( $tmp_classes ) ){
				$classes = array_values( array_diff( $classes, $tmp_classes ) );
			}
		}
		return $classes;
	}

	protected function replace_item( $item_output, $classes ){
		if ( !in_array( 'zm', $classes ) ){
			array_unshift( $classes, 'zm' );
		}
		$before = true;
		$icon = '<i class="' . implode( ' ', $classes ) . '"></i>';
		preg_match( '/(<a.+>)(.+)(<\/a>)/i', $item_output, $matches );
		if ( 4 === count( $matches ) ){
			$item_output = $matches[1];
			if ( $before ){
				$item_output .= $icon . '<span class="font-text">' . $matches[2] . '</span>';
			} else {
				$item_output .= '<span class="font-text">' . $matches[2] . '</span>' . $icon;
			}
			$item_output .= $matches[3];
		}
		return $item_output;
	}

	function walker_nav_menu_start_el( $item_output, $item, $depth, $args ){
		if ( is_array( $item->classes ) ){
			$classes = preg_grep( '/^(zm)(-\S+)?$/i', $item->classes );
			if ( !empty( $classes ) ){
				$item_output = $this->replace_item( $item_output, $classes );
			}
		}
		return $item_output;
	}
}
new iconfont();

// 图标
class be_font {
	function __construct(){
		add_filter( 'nav_menu_css_class', array( $this, 'nav_menu_css_class' ) );
		add_filter( 'walker_nav_menu_start_el', array( $this, 'walker_nav_menu_start_el' ), 10, 4 );
	}
	function nav_menu_css_class( $classes ){
		if ( is_array( $classes ) ){
			$tmp_classes = preg_grep( '/^(be)(-\S+)?$/i', $classes );
			if ( !empty( $tmp_classes ) ){
				$classes = array_values( array_diff( $classes, $tmp_classes ) );
			}
		}
		return $classes;
	}

	protected function replace_item( $item_output, $classes ){
		if ( !in_array( 'be', $classes ) ){
			array_unshift( $classes, 'be' );
		}
		$before = true;
		$icon = '<i class="' . implode( ' ', $classes ) . '"></i>';
		preg_match( '/(<a.+>)(.+)(<\/a>)/i', $item_output, $matches );
		if ( 4 === count( $matches ) ){
			$item_output = $matches[1];
			if ( $before ){
				$item_output .= $icon . '<span class="font-text">' . $matches[2] . '</span>';
			} else {
				$item_output .= '<span class="font-text">' . $matches[2] . '</span>' . $icon;
			}
			$item_output .= $matches[3];
		}
		return $item_output;
	}

	function walker_nav_menu_start_el( $item_output, $item, $depth, $args ){
		if ( is_array( $item->classes ) ){
			$classes = preg_grep( '/^(be)(-\S+)?$/i', $item->classes );
			if ( !empty( $classes ) ){
				$item_output = $this->replace_item( $item_output, $classes );
			}
		}
		return $item_output;
	}
}
new be_font();

// svg
class Svgfont {
	function __construct(){
		add_filter( 'nav_menu_css_class', array( $this, 'nav_menu_css_class' ) );
		add_filter( 'walker_nav_menu_start_el', array( $this, 'walker_nav_menu_start_el' ), 10, 4 );
	}
	function nav_menu_css_class( $classes ){
		if ( is_array( $classes ) ){
			$tmp_classes = preg_grep( '/^(cx)(-\S+)?$/i', $classes );
			if ( !empty( $tmp_classes ) ){
				$classes = array_values( array_diff( $classes, $tmp_classes ) );
			}
		}
		return $classes;
	}

	protected function replace_item( $item_output, $classes ){
		$before = true;
		$icon = '<svg class="icon" aria-hidden="true"><use xlink:href="#'. implode( ' ', $classes ) . '"></use></svg>';
		preg_match( '/(<a.+>)(.+)(<\/a>)/i', $item_output, $matches );
		if ( 4 === count( $matches ) ){
			$item_output = $matches[1];
			if ( $before ){
				$item_output .= $icon . '<span class="font-text">' . $matches[2] . '</span>';
			} else {
				$item_output .= '<span class="font-text">' . $matches[2] . '</span>' . $icon;
			}
			$item_output .= $matches[3];
		}
		return $item_output;
	}

	function walker_nav_menu_start_el( $item_output, $item, $depth, $args ){
		if ( is_array( $item->classes ) ){
			$classes = preg_grep( '/^(cx)(-\S+)?$/i', $item->classes );
			if ( !empty( $classes ) ){
				$item_output = $this->replace_item( $item_output, $classes );
			}
		}
		return $item_output;
	}
}
new Svgfont();

// wp
class wpfont {
	function __construct(){
		add_filter( 'nav_menu_css_class', array( $this, 'nav_menu_css_class' ) );
		add_filter( 'walker_nav_menu_start_el', array( $this, 'walker_nav_menu_start_el' ), 10, 4 );
	}
	function nav_menu_css_class( $classes ){
		if ( is_array( $classes ) ){
			$tmp_classes = preg_grep( '/^(dashicons)(-\S+)?$/i', $classes );
			if ( !empty( $tmp_classes ) ){
				$classes = array_values( array_diff( $classes, $tmp_classes ) );
			}
		}
		return $classes;
	}

	protected function replace_item( $item_output, $classes ){
		if ( !in_array( 'dashicons', $classes ) ){
			array_unshift( $classes, 'dashicons' );
		}
		$before = true;
		$icon = '<i class="' . implode( ' ', $classes ) . '"></i>';
		preg_match( '/(<a.+>)(.+)(<\/a>)/i', $item_output, $matches );
		if ( 4 === count( $matches ) ){
			$item_output = $matches[1];
			if ( $before ){
				$item_output .= $icon . '<span class="font-text">' . $matches[2] . '</span>';
			} else {
				$item_output .= '<span class="font-text">' . $matches[2] . '</span>' . $icon;
			}
			$item_output .= $matches[3];
		}
		return $item_output;
	}

	function walker_nav_menu_start_el( $item_output, $item, $depth, $args ){
		if ( is_array( $item->classes ) ){
			$classes = preg_grep( '/^(dashicons)(-\S+)?$/i', $item->classes );
			if ( !empty( $classes ) ){
				$item_output = $this->replace_item( $item_output, $classes );
			}
		}
		return $item_output;
	}
}
new wpfont();