<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Get icons from admin ajax
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'zmop_get_icons' ) ) {
  function zmop_get_icons() {

    $nonce = ( ! empty( $_POST[ 'nonce' ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ 'nonce' ] ) ) : '';

    if ( ! wp_verify_nonce( $nonce, 'zmop_icon_nonce' ) ) {
      wp_send_json_error( array( 'error' => esc_html__( 'Error: Invalid nonce verification.', 'zmop' ) ) );
    }

    ob_start();

    $icon_library = ( apply_filters( 'zmop_fa4', false ) ) ? 'fa4' : 'fa5';

    ZMOP::include_plugin_file( 'fields/icon/'. $icon_library .'-icons.php' );

    $icon_lists = apply_filters( 'zmop_field_icon_add_icons', zmop_get_default_icons() );

    if ( ! empty( $icon_lists ) ) {

      foreach ( $icon_lists as $list ) {

        echo ( count( $icon_lists ) >= 2 ) ? '<div class="zmop-icon-title">'. esc_attr( $list['title'] ) .'</div>' : '';

        foreach ( $list['icons'] as $icon ) {
          echo '<i title="'. esc_attr( $icon ) .'" class="'. esc_attr( $icon ) .'"></i>';
        }

      }

    } else {

      echo '<div class="zmop-error-text">'. esc_html__( 'No data available.', 'zmop' ) .'</div>';

    }

    $content = ob_get_clean();

    wp_send_json_success( array( 'content' => $content ) );

  }
  add_action( 'wp_ajax_zmop-get-icons', 'zmop_get_icons' );
}

/**
 *
 * Export
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'zmop_export' ) ) {
  function zmop_export() {

    $nonce  = ( ! empty( $_GET[ 'nonce' ] ) ) ? sanitize_text_field( wp_unslash( $_GET[ 'nonce' ] ) ) : '';
    $unique = ( ! empty( $_GET[ 'unique' ] ) ) ? sanitize_text_field( wp_unslash( $_GET[ 'unique' ] ) ) : '';

    if ( ! wp_verify_nonce( $nonce, 'zmop_backup_nonce' ) ) {
      die( esc_html__( 'Error: Invalid nonce verification.', 'zmop' ) );
    }

    if ( empty( $unique ) ) {
      die( esc_html__( 'Error: Invalid key.', 'zmop' ) );
    }

    // Export
    header('Content-Type: application/json');
    header('Content-disposition: attachment; filename=主题选项备份-'. gmdate( 'Y-m-d' ) .'.json');
    header('Content-Transfer-Encoding: binary');
    header('Pragma: no-cache');
    header('Expires: 0');

    echo json_encode( get_option( $unique ) );

    die();

  }
  add_action( 'wp_ajax_zmop-export', 'zmop_export' );
}

/**
 *
 * 导出首页
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'zmop_export_be' ) ) {
	function zmop_export_be() {

		$nonce  = ( ! empty( $_GET[ 'nonce' ] ) ) ? sanitize_text_field( wp_unslash( $_GET[ 'nonce' ] ) ) : '';
		$unique = ( ! empty( $_GET[ 'unique' ] ) ) ? sanitize_text_field( wp_unslash( $_GET[ 'unique' ] ) ) : '';

		if ( ! wp_verify_nonce( $nonce, 'zmop_backup_nonce' ) ) {
			die( esc_html__( 'Error: Invalid nonce verification.', 'zmop' ) );
		}

		if ( empty( $unique ) ) {
			die( esc_html__( 'Error: Invalid key.', 'zmop' ) );
		}

		// Export
		header('Content-Type: application/json');
		header('Content-disposition: attachment; filename=首页设置备份-'. gmdate( 'Y-m-d' ) .'.json');
		header('Content-Transfer-Encoding: binary');
		header('Pragma: no-cache');
		header('Expires: 0');

		echo json_encode( get_option( $unique ) );

		die();

	}
	add_action( 'wp_ajax_zmop-export-be', 'zmop_export_be' );
}

/**
 *
 * 导出公司主页
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'zmop_export_co' ) ) {
	function zmop_export_co() {

		$nonce  = ( ! empty( $_GET[ 'nonce' ] ) ) ? sanitize_text_field( wp_unslash( $_GET[ 'nonce' ] ) ) : '';
		$unique = ( ! empty( $_GET[ 'unique' ] ) ) ? sanitize_text_field( wp_unslash( $_GET[ 'unique' ] ) ) : '';

		if ( ! wp_verify_nonce( $nonce, 'zmop_backup_nonce' ) ) {
			die( esc_html__( 'Error: Invalid nonce verification.', 'zmop' ) );
		}

		if ( empty( $unique ) ) {
			die( esc_html__( 'Error: Invalid key.', 'zmop' ) );
		}

		// Export
		header('Content-Type: application/json');
		header('Content-disposition: attachment; filename=公司主页备份-'. gmdate( 'Y-m-d' ) .'.json');
		header('Content-Transfer-Encoding: binary');
		header('Pragma: no-cache');
		header('Expires: 0');

		echo json_encode( get_option( $unique ) );

		die();

	}
	add_action( 'wp_ajax_zmop-export-co', 'zmop_export_co' );
}

/**
 *
 * 导出辅助设置
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'zmop_export_sub' ) ) {
	function zmop_export_sub() {

		$nonce  = ( ! empty( $_GET[ 'nonce' ] ) ) ? sanitize_text_field( wp_unslash( $_GET[ 'nonce' ] ) ) : '';
		$unique = ( ! empty( $_GET[ 'unique' ] ) ) ? sanitize_text_field( wp_unslash( $_GET[ 'unique' ] ) ) : '';

		if ( ! wp_verify_nonce( $nonce, 'zmop_backup_nonce' ) ) {
			die( esc_html__( 'Error: Invalid nonce verification.', 'zmop' ) );
		}

		if ( empty( $unique ) ) {
			die( esc_html__( 'Error: Invalid key.', 'zmop' ) );
		}

		// Export
		header('Content-Type: application/json');
		header('Content-disposition: attachment; filename=辅助设置备份-'. gmdate( 'Y-m-d' ) .'.json');
		header('Content-Transfer-Encoding: binary');
		header('Pragma: no-cache');
		header('Expires: 0');

		echo json_encode( get_option( $unique ) );

		die();

	}
	add_action( 'wp_ajax_zmop-export-sub', 'zmop_export_sub' );
}

/**
 *
 * Import Ajax
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'zmop_import_ajax' ) ) {
  function zmop_import_ajax() {

    $nonce  = ( ! empty( $_POST[ 'nonce' ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ 'nonce' ] ) ) : '';
    $unique = ( ! empty( $_POST[ 'unique' ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ 'unique' ] ) ) : '';
    $data   = ( ! empty( $_POST[ 'data' ] ) ) ? wp_kses_post_deep( json_decode( wp_unslash( trim( $_POST[ 'data' ] ) ), true ) ) : array();

    if ( ! wp_verify_nonce( $nonce, 'zmop_backup_nonce' ) ) {
      wp_send_json_error( array( 'error' => esc_html__( 'Error: Invalid nonce verification.', 'zmop' ) ) );
    }

    if ( empty( $unique ) ) {
      wp_send_json_error( array( 'error' => esc_html__( 'Error: Invalid key.', 'zmop' ) ) );
    }

    if ( empty( $data ) || ! is_array( $data ) ) {
      wp_send_json_error( array( 'error' => esc_html__( 'Error: The response is not a valid JSON response.', 'zmop' ) ) );
    }

    // Success
    update_option( $unique, $data );

    wp_send_json_success();

  }
  add_action( 'wp_ajax_zmop-import', 'zmop_import_ajax' );
}

/**
 *
 * Reset Ajax
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'zmop_reset_ajax' ) ) {
  function zmop_reset_ajax() {

    $nonce  = ( ! empty( $_POST[ 'nonce' ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ 'nonce' ] ) ) : '';
    $unique = ( ! empty( $_POST[ 'unique' ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ 'unique' ] ) ) : '';

    if ( ! wp_verify_nonce( $nonce, 'zmop_backup_nonce' ) ) {
      wp_send_json_error( array( 'error' => esc_html__( 'Error: Invalid nonce verification.', 'zmop' ) ) );
    }

    // Success
    delete_option( $unique );

    wp_send_json_success();

  }
  add_action( 'wp_ajax_zmop-reset', 'zmop_reset_ajax' );
}

/**
 *
 * Chosen Ajax
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'zmop_chosen_ajax' ) ) {
  function zmop_chosen_ajax() {

    $nonce = ( ! empty( $_POST[ 'nonce' ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ 'nonce' ] ) ) : '';
    $type  = ( ! empty( $_POST[ 'type' ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ 'type' ] ) ) : '';
    $term  = ( ! empty( $_POST[ 'term' ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ 'term' ] ) ) : '';
    $query = ( ! empty( $_POST[ 'query_args' ] ) ) ? wp_kses_post_deep( $_POST[ 'query_args' ] ) : array();

    if ( ! wp_verify_nonce( $nonce, 'zmop_chosen_ajax_nonce' ) ) {
      wp_send_json_error( array( 'error' => esc_html__( 'Error: Invalid nonce verification.', 'zmop' ) ) );
    }

    if ( empty( $type ) || empty( $term ) ) {
      wp_send_json_error( array( 'error' => esc_html__( 'Error: Invalid term ID.', 'zmop' ) ) );
    }

    $capability = apply_filters( 'zmop_chosen_ajax_capability', 'manage_options' );

    if ( ! current_user_can( $capability ) ) {
      wp_send_json_error( array( 'error' => esc_html__( 'Error: You do not have permission to do that.', 'zmop' ) ) );
    }

    // Success
    $options = ZMOP_Fields::field_data( $type, $term, $query );

    wp_send_json_success( $options );

  }
  add_action( 'wp_ajax_zmop-chosen', 'zmop_chosen_ajax' );
}

/**
 *
 * WP Customize custom panel
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'WP_Customize_Panel_ZMOP' ) && class_exists( 'WP_Customize_Panel' ) ) {
  class WP_Customize_Panel_ZMOP extends WP_Customize_Panel {
    public $type = 'zmop';
  }
}

/**
 *
 * WP Customize custom section
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'WP_Customize_Section_ZMOP' ) && class_exists( 'WP_Customize_Section' ) ) {
  class WP_Customize_Section_ZMOP extends WP_Customize_Section {
    public $type = 'zmop';
  }
}

/**
 *
 * WP Customize custom control
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'WP_Customize_Control_ZMOP' ) && class_exists( 'WP_Customize_Control' ) ) {
  class WP_Customize_Control_ZMOP extends WP_Customize_Control {

    public $type   = 'zmop';
    public $field  = '';
    public $unique = '';

    public function render() {

      $depend  = '';
      $visible = '';

      if ( ! empty( $this->field['dependency'] ) ) {

        $dependency      = $this->field['dependency'];
        $depend_visible  = '';
        $data_controller = '';
        $data_condition  = '';
        $data_value      = '';
        $data_global     = '';

        if ( is_array( $dependency[0] ) ) {
          $data_controller = implode( '|', array_column( $dependency, 0 ) );
          $data_condition  = implode( '|', array_column( $dependency, 1 ) );
          $data_value      = implode( '|', array_column( $dependency, 2 ) );
          $data_global     = implode( '|', array_column( $dependency, 3 ) );
          $depend_visible  = implode( '|', array_column( $dependency, 4 ) );
        } else {
          $data_controller = ( ! empty( $dependency[0] ) ) ? $dependency[0] : '';
          $data_condition  = ( ! empty( $dependency[1] ) ) ? $dependency[1] : '';
          $data_value      = ( ! empty( $dependency[2] ) ) ? $dependency[2] : '';
          $data_global     = ( ! empty( $dependency[3] ) ) ? $dependency[3] : '';
          $depend_visible  = ( ! empty( $dependency[4] ) ) ? $dependency[4] : '';
        }

        $depend .= ' data-controller="'. esc_attr( $data_controller ) .'"';
        $depend .= ' data-condition="'. esc_attr( $data_condition ) .'"';
        $depend .= ' data-value="'. esc_attr( $data_value ) .'"';
        $depend .= ( ! empty( $data_global ) ) ? ' data-depend-global="true"' : '';

        $visible  = ' zmop-dependency-control';
        $visible .= ( ! empty( $depend_visible ) ) ? ' zmop-depend-visible' : ' zmop-depend-hidden';

      }

      $id    = 'customize-control-' . str_replace( array( '[', ']' ), array( '-', '' ), $this->id );
      $class = 'customize-control customize-control-'. $this->type . $visible;

      echo '<li id="'. esc_attr( $id ) .'" class="'. esc_attr( $class ) .'"'. $depend .'>';
      $this->render_field_content();
      echo '</li>';

    }

    public function render_field_content() {

      $complex = apply_filters( 'zmop_customize_complex_fields', array(
        'accordion',
        'background',
        'border',
        'button_set',
        'checkbox',
        'color_group',
        'dimensions',
        'fieldset',
        'group',
        'image_select',
        'link',
        'link_color',
        'media',
        'palette',
        'repeater',
        'sortable',
        'sorter',
        'spacing',
        'switcher',
        'tabbed',
      ) );

      $field_id   = ( ! empty( $this->field['id'] ) ) ? $this->field['id'] : '';
      $custom     = ( ! empty( $this->field['customizer'] ) ) ? true : false;
      $is_complex = ( in_array( $this->field['type'], $complex ) ) ? true : false;
      $class      = ( $is_complex || $custom ) ? ' zmop-customize-complex' : '';
      $atts       = ( $is_complex || $custom ) ? ' data-unique-id="'. esc_attr( $this->unique ) .'" data-option-id="'. esc_attr( $field_id ) .'"' : '';

      if ( ! $is_complex && ! $custom ) {
        $this->field['attributes']['data-customize-setting-link'] = $this->settings['default']->id;
      }

      $this->field['name'] = $this->settings['default']->id;

      $this->field['dependency'] = array();

      echo '<div class="zmop-customize-field'. esc_attr( $class ) .'"'. $atts .'>';

      ZMOP::field( $this->field, $this->value(), $this->unique, 'customize' );

      echo '</div>';

    }

  }
}

/**
 *
 * Array search key & value
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'zmop_array_search' ) ) {
  function zmop_array_search( $array, $key, $value ) {

    $results = array();

    if ( is_array( $array ) ) {
      if ( isset( $array[$key] ) && $array[$key] == $value ) {
        $results[] = $array;
      }

      foreach ( $array as $sub_array ) {
        $results = array_merge( $results, zmop_array_search( $sub_array, $key, $value ) );
      }

    }

    return $results;

  }
}

/**
 *
 * Between Microtime
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'zmop_timeout' ) ) {
  function zmop_timeout( $timenow, $starttime, $timeout = 30 ) {
    return ( ( $timenow - $starttime ) < $timeout ) ? true : false;
  }
}

/**
 *
 * Check for wp editor api
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'zmop_wp_editor_api' ) ) {
  function zmop_wp_editor_api() {
    global $wp_version;
    return version_compare( $wp_version, '4.8', '>=' );
  }
}

/**
 *
 * Sanitize
 * Replace letter a to letter b
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'zmop_sanitize_replace_a_to_b' ) ) {
  function zmop_sanitize_replace_a_to_b( $value ) {
    return str_replace( 'a', 'b', $value );
  }
}

/**
 *
 * Sanitize title
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'zmop_sanitize_title' ) ) {
  function zmop_sanitize_title( $value ) {
    return sanitize_title( $value );
  }
}

/**
 *
 * Email validate
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'zmop_validate_email' ) ) {
  function zmop_validate_email( $value ) {

    if ( ! filter_var( $value, FILTER_VALIDATE_EMAIL ) ) {
      return esc_html__( 'Please enter a valid email address.', 'zmop' );
    }

  }
}

/**
 *
 * Numeric validate
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'zmop_validate_numeric' ) ) {
  function zmop_validate_numeric( $value ) {

    if ( ! is_numeric( $value ) ) {
      return esc_html__( 'Please enter a valid number.', 'zmop' );
    }

  }
}

/**
 *
 * Required validate
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'zmop_validate_required' ) ) {
  function zmop_validate_required( $value ) {

    if ( empty( $value ) ) {
      return esc_html__( 'This field is required.', 'zmop' );
    }

  }
}

/**
 *
 * URL validate
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'zmop_validate_url' ) ) {
  function zmop_validate_url( $value ) {

    if ( ! filter_var( $value, FILTER_VALIDATE_URL ) ) {
      return esc_html__( 'Please enter a valid URL.', 'zmop' );
    }

  }
}

/**
 *
 * Email validate for Customizer
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'zmop_customize_validate_email' ) ) {
  function zmop_customize_validate_email( $validity, $value, $wp_customize ) {

    if ( ! sanitize_email( $value ) ) {
      $validity->add( 'required', esc_html__( 'Please enter a valid email address.', 'zmop' ) );
    }

    return $validity;

  }
}

/**
 *
 * Numeric validate for Customizer
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'zmop_customize_validate_numeric' ) ) {
  function zmop_customize_validate_numeric( $validity, $value, $wp_customize ) {

    if ( ! is_numeric( $value ) ) {
      $validity->add( 'required', esc_html__( 'Please enter a valid number.', 'zmop' ) );
    }

    return $validity;

  }
}

/**
 *
 * Required validate for Customizer
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'zmop_customize_validate_required' ) ) {
  function zmop_customize_validate_required( $validity, $value, $wp_customize ) {

    if ( empty( $value ) ) {
      $validity->add( 'required', esc_html__( 'This field is required.', 'zmop' ) );
    }

    return $validity;

  }
}

/**
 *
 * URL validate for Customizer
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'zmop_customize_validate_url' ) ) {
  function zmop_customize_validate_url( $validity, $value, $wp_customize ) {

    if ( ! filter_var( $value, FILTER_VALIDATE_URL ) ) {
      $validity->add( 'required', esc_html__( 'Please enter a valid URL.', 'zmop' ) );
    }

    return $validity;

  }
}

/**
 *
 * Custom Walker for Nav Menu Edit
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'ZMOP_Walker_Nav_Menu_Edit' ) && class_exists( 'Walker_Nav_Menu_Edit' ) ) {
  class ZMOP_Walker_Nav_Menu_Edit extends Walker_Nav_Menu_Edit {

    public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

      $html = '';

      parent::start_el( $html, $item, $depth, $args, $id );

      ob_start();
      do_action( 'wp_nav_menu_item_custom_fields', $item->ID, $item, $depth, $args );
      $custom_fields = ob_get_clean();

      $output .= preg_replace( '/(?=<(fieldset|p)[^>]+class="[^"]*field-move)/', $custom_fields, $html );

    }

  }
}

// 菜单
if ( current_user_can( 'manage_options' ) ) {
	add_action( 'admin_bar_menu', 'begin_options_menu', 88 );
}
function begin_options_menu( $wp_admin_bar ) {
	$wp_admin_bar->add_menu( array(
		'id'    => 'beginptions',
		'title' => '<i class="cx cx-begin"></i>主题选项',
		'href'  => home_url( '/' ) . 'wp-admin/admin.php?page=begin-options'
	) );

	$wp_admin_bar->add_menu( array(
		'parent' => 'beginptions',
		'id'     => 'begin-options',
		'title'  => '<i class="cx cx-begin"></i>首页设置',
		'href'   => home_url( '/' ) . 'wp-admin/admin.php?page=be-options'
	) );

	$wp_admin_bar->add_menu( array(
		'parent' => 'beginptions',
		'id'     => 'be-options',
		'title'  => '<i class="cx cx-begin"></i>公司主页',
		'href'   => home_url( '/' ) . 'wp-admin/admin.php?page=co-options'
	) );

	$wp_admin_bar->add_menu( array(
		'parent' => 'beginptions',
		'id'     => 'cx-options',
		'title'  => '<i class="cx cx-begin"></i>辅助设置',
		'href'   => home_url( '/' ) . 'wp-admin/admin.php?page=cx-options'
	) );
}