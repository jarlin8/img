<?php if ( ! defined( 'ABSPATH' ) ) { die; }
/**
 *
 * Field: backup
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'ZMOP_Field_backup' ) ) {
  class ZMOP_Field_backup extends ZMOP_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $unique = $this->unique;
      $nonce  = wp_create_nonce( 'zmop_backup_nonce' );
      $export = add_query_arg( array( 'action' => 'zmop-export', 'unique' => $unique, 'nonce' => $nonce ), admin_url( 'admin-ajax.php' ) );

      echo $this->field_before();

      //echo '<textarea readonly="readonly" class="zmop-export-data">'. esc_attr( json_encode( get_option( $unique ) ) ) .'</textarea>';
      //echo '<div class="zmop-field-import zmop-field-content"></div>';
      echo '<textarea name="zmop_import_data" class="zmop-import-data" placeholder="复制数据粘贴到此，执行导入！"></textarea>';
      echo '<a href="'. esc_url( $export ) .'" class="button button-primary zmop-export" target="_blank">导出选项</a>';
      echo '<button type="submit" class="button button-primary zmop-confirm zmop-import" data-unique="'. esc_attr( $unique ) .'" data-nonce="'. esc_attr( $nonce ) .'">导入选项</button>';
      echo $this->field_after();
    }

  }
}

/**
 *
 * Field: 首页备份
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'ZMOP_Field_backup_be' ) ) {
	class ZMOP_Field_backup_be extends ZMOP_Fields {

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render() {

			$unique = $this->unique;
			$nonce  = wp_create_nonce( 'zmop_backup_nonce' );
			$export = add_query_arg( array( 'action' => 'zmop-export-be', 'unique' => $unique, 'nonce' => $nonce ), admin_url( 'admin-ajax.php' ) );

			echo $this->field_before();

			//echo '<div class="zmop-field-import zmop-field-content"></div>';
			echo '<textarea name="zmop_import_data" class="zmop-import-data" placeholder="复制数据粘贴到此，执行导入！"></textarea>';
			echo '<a href="'. esc_url( $export ) .'" class="button button-primary zmop-export" target="_blank">导出设置</a>';
			echo '<button type="submit" class="button button-primary zmop-confirm zmop-import" data-unique="'. esc_attr( $unique ) .'" data-nonce="'. esc_attr( $nonce ) .'">导入设置</button>';
			echo $this->field_after();
		}
	}
}

/**
 *
 * Field: 公司主页
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'ZMOP_Field_backup_co' ) ) {
	class ZMOP_Field_backup_co extends ZMOP_Fields {

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render() {

			$unique = $this->unique;
			$nonce  = wp_create_nonce( 'zmop_backup_nonce' );
			$export = add_query_arg( array( 'action' => 'zmop-export-co', 'unique' => $unique, 'nonce' => $nonce ), admin_url( 'admin-ajax.php' ) );

			echo $this->field_before();

			//echo '<div class="zmop-field-import zmop-field-content"></div>';
			echo '<textarea name="zmop_import_data" class="zmop-import-data" placeholder="复制数据粘贴到此，执行导入！"></textarea>';
			echo '<a href="'. esc_url( $export ) .'" class="button button-primary zmop-export" target="_blank">导出设置</a>';
			echo '<button type="submit" class="button button-primary zmop-confirm zmop-import" data-unique="'. esc_attr( $unique ) .'" data-nonce="'. esc_attr( $nonce ) .'">导入设置</button>';
			echo $this->field_after();
		}
	}
}

/**
 *
 * Field: 辅助设置
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'ZMOP_Field_backup_sub' ) ) {
	class ZMOP_Field_backup_sub extends ZMOP_Fields {

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render() {

			$unique = $this->unique;
			$nonce  = wp_create_nonce( 'zmop_backup_nonce' );
			$export = add_query_arg( array( 'action' => 'zmop-export-sub', 'unique' => $unique, 'nonce' => $nonce ), admin_url( 'admin-ajax.php' ) );

			echo $this->field_before();

			//echo '<div class="zmop-field-import zmop-field-content"></div>';
			echo '<textarea name="zmop_import_data" class="zmop-import-data" placeholder="复制数据粘贴到此，执行导入！"></textarea>';
			echo '<a href="'. esc_url( $export ) .'" class="button button-primary zmop-export" target="_blank">导出设置</a>';
			echo '<button type="submit" class="button button-primary zmop-confirm zmop-import" data-unique="'. esc_attr( $unique ) .'" data-nonce="'. esc_attr( $nonce ) .'">导入设置</button>';
			echo $this->field_after();
		}
	}
}

/**
 *
 * Field: callback
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'ZMOP_Field_callback' ) ) {
  class ZMOP_Field_callback extends ZMOP_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      if ( isset( $this->field['function'] ) && is_callable( $this->field['function'] ) ) {

        $args = ( isset( $this->field['args'] ) ) ? $this->field['args'] : null;

        call_user_func( $this->field['function'], $args );

      }

    }

  }
}

/**
 *
 * Field: color
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'ZMOP_Field_color' ) ) {
  class ZMOP_Field_color extends ZMOP_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $default_attr = ( ! empty( $this->field['default'] ) ) ? ' data-default-color="'. esc_attr( $this->field['default'] ) .'"' : '';

      echo $this->field_before();
      echo '<input type="text" name="'. esc_attr( $this->field_name() ) .'" value="'. esc_attr( $this->value ) .'" class="zmop-color"'. $default_attr . $this->field_attributes() .'/>';
      echo $this->field_after();

    }

    public function output() {

      $output    = '';
      $elements  = ( is_array( $this->field['output'] ) ) ? $this->field['output'] : array_filter( (array) $this->field['output'] );
      $important = ( ! empty( $this->field['output_important'] ) ) ? '!important' : '';
      $mode      = ( ! empty( $this->field['output_mode'] ) ) ? $this->field['output_mode'] : 'color';

      if ( ! empty( $elements ) && isset( $this->value ) && $this->value !== '' ) {
        foreach ( $elements as $key_property => $element ) {
          if ( is_numeric( $key_property ) ) {
            $output = implode( ',', $elements ) .'{'. $mode .':'. $this->value . $important .';}';
            break;
          } else {
            $output .= $element .'{'. $key_property .':'. $this->value . $important .'}';
          }
        }
      }

      $this->parent->output_css .= $output;

      return $output;

    }

  }
}

/**
 *
 * Field: content
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'ZMOP_Field_content' ) ) {
  class ZMOP_Field_content extends ZMOP_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      if ( ! empty( $this->field['content'] ) ) {

        echo $this->field['content'];

      }

    }

  }
}

/**
 *
 * Field: fieldset
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'ZMOP_Field_fieldset' ) ) {
  class ZMOP_Field_fieldset extends ZMOP_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      echo $this->field_before();

      echo '<div class="zmop-fieldset-content" data-depend-id="'. esc_attr( $this->field['id'] ) .'">';

      foreach ( $this->field['fields'] as $field ) {

        $field_id      = ( isset( $field['id'] ) ) ? $field['id'] : '';
        $field_default = ( isset( $field['default'] ) ) ? $field['default'] : '';
        $field_value   = ( isset( $this->value[$field_id] ) ) ? $this->value[$field_id] : $field_default;
        $unique_id     = ( ! empty( $this->unique ) ) ? $this->unique .'['. $this->field['id'] .']' : $this->field['id'];

        ZMOP::field( $field, $field_value, $unique_id, 'field/fieldset' );

      }

      echo '</div>';

      echo $this->field_after();

    }

  }
}

/**
 *
 * Field: number
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'ZMOP_Field_number' ) ) {
  class ZMOP_Field_number extends ZMOP_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $args = wp_parse_args( $this->field, array(
        'min'  => 'any',
        'max'  => 'any',
        'step' => 'any',
        'unit' => '',
      ) );

      echo $this->field_before();
      echo '<div class="zmop--wrap">';
      echo '<input type="number" name="'. esc_attr( $this->field_name() ) .'" value="'. esc_attr( $this->value ) .'"'. $this->field_attributes() .' min="'. esc_attr( $args['min'] ) .'" max="'. esc_attr( $args['max'] ) .'" step="'. esc_attr( $args['step'] ) .'"/>';
      echo ( ! empty( $args['unit'] ) ) ? '<span class="zmop--unit">'. esc_attr( $args['unit'] ) .'</span>' : '';
      echo '</div>';
      echo $this->field_after();

    }

    public function output() {

      $output    = '';
      $elements  = ( is_array( $this->field['output'] ) ) ? $this->field['output'] : array_filter( (array) $this->field['output'] );
      $important = ( ! empty( $this->field['output_important'] ) ) ? '!important' : '';
      $mode      = ( ! empty( $this->field['output_mode'] ) ) ? $this->field['output_mode'] : 'width';
      $unit      = ( ! empty( $this->field['unit'] ) ) ? $this->field['unit'] : 'px';

      if ( ! empty( $elements ) && isset( $this->value ) && $this->value !== '' ) {
        foreach ( $elements as $key_property => $element ) {
          if ( is_numeric( $key_property ) ) {
            if ( $mode ) {
              $output = implode( ',', $elements ) .'{'. $mode .':'. $this->value . $unit . $important .';}';
            }
            break;
          } else {
            $output .= $element .'{'. $key_property .':'. $this->value . $unit . $important .'}';
          }
        }
      }

      $this->parent->output_css .= $output;

      return $output;

    }

  }
}

/**
 *
 * Field: radio
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'ZMOP_Field_radio' ) ) {
  class ZMOP_Field_radio extends ZMOP_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $args = wp_parse_args( $this->field, array(
        'inline'     => false,
        'query_args' => array(),
      ) );

      $inline_class = ( $args['inline'] ) ? ' class="zmop--inline-list"' : '';

      echo $this->field_before();

      if ( isset( $this->field['options'] ) ) {

        $options = $this->field['options'];
        $options = ( is_array( $options ) ) ? $options : array_filter( $this->field_data( $options, false, $args['query_args'] ) );

        if ( is_array( $options ) && ! empty( $options ) ) {

          echo '<ul'. $inline_class .'>';

          foreach ( $options as $option_key => $option_value ) {

            if ( is_array( $option_value ) && ! empty( $option_value ) ) {

              echo '<li>';
                echo '<ul>';
                  echo '<li><strong>'. esc_attr( $option_key ) .'</strong></li>';
                  foreach ( $option_value as $sub_key => $sub_value ) {
                    $checked = ( $sub_key == $this->value ) ? ' checked' : '';
                    echo '<li>';
                    echo '<label>';
                    echo '<input type="radio" name="'. esc_attr( $this->field_name() ) .'" value="'. esc_attr( $sub_key ) .'"'. $this->field_attributes() . esc_attr( $checked ) .'/>';
                    echo '<span class="zmop--text">'. esc_attr( $sub_value ) .'</span>';
                    echo '</label>';
                    echo '</li>';
                  }
                echo '</ul>';
              echo '</li>';

            } else {

              $checked = ( $option_key == $this->value ) ? ' checked' : '';

              echo '<li>';
              echo '<label>';
              echo '<input type="radio" name="'. esc_attr( $this->field_name() ) .'" value="'. esc_attr( $option_key ) .'"'. $this->field_attributes() . esc_attr( $checked ) .'/>';
              echo '<span class="zmop--text">'. esc_attr( $option_value ) .'</span>';
              echo '</label>';
              echo '</li>';

            }

          }

          echo '</ul>';

        } else {

          echo ( ! empty( $this->field['empty_message'] ) ) ? esc_attr( $this->field['empty_message'] ) : '没有可用数据';

        }

      } else {

        $label = ( isset( $this->field['label'] ) ) ? $this->field['label'] : '';
        echo '<label><input type="radio" name="'. esc_attr( $this->field_name() ) .'" value="1"'. $this->field_attributes() . esc_attr( checked( $this->value, 1, false ) ) .'/>';
        echo ( ! empty( $this->field['label'] ) ) ? '<span class="zmop--text">'. esc_attr( $this->field['label'] ) .'</span>' : '';
        echo '</label>';

      }

      echo $this->field_after();

    }

  }
}

/**
 *
 * Field: select
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'ZMOP_Field_select' ) ) {
  class ZMOP_Field_select extends ZMOP_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $args = wp_parse_args( $this->field, array(
        'placeholder' => '',
        'chosen'      => false,
        'multiple'    => false,
        'sortable'    => false,
        'ajax'        => false,
        'settings'    => array(),
        'query_args'  => array(),
      ) );

      $this->value = ( is_array( $this->value ) ) ? $this->value : array_filter( (array) $this->value );

      echo $this->field_before();

      if ( isset( $this->field['options'] ) ) {

        if ( ! empty( $args['ajax'] ) ) {
          $args['settings']['data']['type']  = $args['options'];
          $args['settings']['data']['nonce'] = wp_create_nonce( 'zmop_chosen_ajax_nonce' );
          if ( ! empty( $args['query_args'] ) ) {
            $args['settings']['data']['query_args'] = $args['query_args'];
          }
        }

        $chosen_rtl       = ( is_rtl() ) ? ' chosen-rtl' : '';
        $multiple_name    = ( $args['multiple'] ) ? '[]' : '';
        $multiple_attr    = ( $args['multiple'] ) ? ' multiple="multiple"' : '';
        $chosen_sortable  = ( $args['chosen'] && $args['sortable'] ) ? ' zmop-chosen-sortable' : '';
        $chosen_ajax      = ( $args['chosen'] && $args['ajax'] ) ? ' zmop-chosen-ajax' : '';
        $placeholder_attr = ( $args['chosen'] && $args['placeholder'] ) ? ' data-placeholder="'. esc_attr( $args['placeholder'] ) .'"' : '';
        $field_class      = ( $args['chosen'] ) ? ' class="zmop-chosen'. esc_attr( $chosen_rtl . $chosen_sortable . $chosen_ajax ) .'"' : '';
        $field_name       = $this->field_name( $multiple_name );
        $field_attr       = $this->field_attributes();
        $maybe_options    = $this->field['options'];
        $chosen_data_attr = ( $args['chosen'] && ! empty( $args['settings'] ) ) ? ' data-chosen-settings="'. esc_attr( json_encode( $args['settings'] ) ) .'"' : '';

        if ( is_string( $maybe_options ) && ! empty( $args['chosen'] ) && ! empty( $args['ajax'] ) ) {
          $options = $this->field_wp_query_data_title( $maybe_options, $this->value );
        } else if ( is_string( $maybe_options ) ) {
          $options = $this->field_data( $maybe_options, false, $args['query_args'] );
        } else {
          $options = $maybe_options;
        }

        if ( ( is_array( $options ) && ! empty( $options ) ) || ( ! empty( $args['chosen'] ) && ! empty( $args['ajax'] ) ) ) {

          if ( ! empty( $args['chosen'] ) && ! empty( $args['multiple'] ) ) {

            echo '<select name="'. $field_name .'" class="zmop-hide-select hidden"'. $multiple_attr . $field_attr .'>';
            foreach ( $this->value as $option_key ) {
              echo '<option value="'. esc_attr( $option_key ) .'" selected>'. esc_attr( $option_key ) .'</option>';
            }
            echo '</select>';

            $field_name = '_pseudo';
            $field_attr = '';

          }

          // These attributes has been serialized above.
          echo '<select name="'. esc_attr( $field_name ) .'"'. $field_class . $multiple_attr . $placeholder_attr . $field_attr . $chosen_data_attr .'>';

          if ( $args['placeholder'] && empty( $args['multiple'] ) ) {
            if ( ! empty( $args['chosen'] ) ) {
              echo '<option value=""></option>';
            } else {
              echo '<option value="">'. esc_attr( $args['placeholder'] ) .'</option>';
            }
          }

          foreach ( $options as $option_key => $option ) {

            if ( is_array( $option ) && ! empty( $option ) ) {

              echo '<optgroup label="'. esc_attr( $option_key ) .'">';

              foreach ( $option as $sub_key => $sub_value ) {
                $selected = ( in_array( $sub_key, $this->value ) ) ? ' selected' : '';
                echo '<option value="'. esc_attr( $sub_key ) .'" '. esc_attr( $selected ) .'>'. esc_attr( $sub_value ) .'</option>';
              }

              echo '</optgroup>';

            } else {
              $selected = ( in_array( $option_key, $this->value ) ) ? ' selected' : '';
              echo '<option value="'. esc_attr( $option_key ) .'" '. esc_attr( $selected ) .'>'. esc_attr( $option ) .'</option>';
            }

          }

          echo '</select>';

        } else {

          echo ( ! empty( $this->field['empty_message'] ) ) ? esc_attr( $this->field['empty_message'] ) : '没有可用数据';

        }

      }

      echo $this->field_after();

    }

    public function enqueue() {

      if ( ! wp_script_is( 'jquery-ui-sortable' ) ) {
        wp_enqueue_script( 'jquery-ui-sortable' );
      }

    }

  }
}

/**
 *
 * Field: subheading
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'ZMOP_Field_subheading' ) ) {
  class ZMOP_Field_subheading extends ZMOP_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      echo ( ! empty( $this->field['content'] ) ) ? $this->field['content'] : '';

    }

  }
}

/**
 *
 * Field: switcher
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'ZMOP_Field_switcher' ) ) {
  class ZMOP_Field_switcher extends ZMOP_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $active     = ( ! empty( $this->value ) ) ? ' zmop--active' : '';
      $text_on    = ( ! empty( $this->field['text_on'] ) ) ? $this->field['text_on'] : esc_html__( 'On', 'zmop' );
      $text_off   = ( ! empty( $this->field['text_off'] ) ) ? $this->field['text_off'] : esc_html__( 'Off', 'zmop' );
      $text_width = ( ! empty( $this->field['text_width'] ) ) ? ' style="width: '. esc_attr( $this->field['text_width'] ) .'px;"': '';

      echo $this->field_before();

      echo '<div class="zmop--switcher'. esc_attr( $active ) .'"'. $text_width .'>';
      echo '<span class="zmop--on">'. esc_attr( $text_on ) .'</span>';
      echo '<span class="zmop--off">'. esc_attr( $text_off ) .'</span>';
      echo '<span class="zmop--ball"></span>';
      echo '<input type="hidden" name="'. esc_attr( $this->field_name() ) .'" value="'. esc_attr( $this->value ) .'"'. $this->field_attributes() .' />';
      echo '</div>';

      echo ( ! empty( $this->field['label'] ) ) ? '<span class="zmop--label">'. esc_attr( $this->field['label'] ) . '</span>' : '';

      echo $this->field_after();

    }

  }
}

/**
 *
 * Field: text
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'ZMOP_Field_text' ) ) {
  class ZMOP_Field_text extends ZMOP_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $type = ( ! empty( $this->field['attributes']['type'] ) ) ? $this->field['attributes']['type'] : 'text';

      echo $this->field_before();

      echo '<input type="'. esc_attr( $type ) .'" name="'. esc_attr( $this->field_name() ) .'" value="'. esc_attr( $this->value ) .'"'. $this->field_attributes() .' />';

      echo $this->field_after();

    }

  }
}

/**
 *
 * Field: textarea
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'ZMOP_Field_textarea' ) ) {
  class ZMOP_Field_textarea extends ZMOP_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      echo $this->field_before();
      echo $this->shortcoder();
      echo '<textarea name="'. esc_attr( $this->field_name() ) .'"'. $this->field_attributes() .'>'. $this->value .'</textarea>';
      echo $this->field_after();

    }

    public function shortcoder() {

      if ( ! empty( $this->field['shortcoder'] ) ) {

        $instances = ( is_array( $this->field['shortcoder'] ) ) ? $this->field['shortcoder'] : array_filter( (array) $this->field['shortcoder'] );

        foreach ( $instances as $instance_key ) {

          if ( isset( ZMOP::$shortcode_instances[$instance_key] ) ) {

            $button_title = ZMOP::$shortcode_instances[$instance_key]['button_title'];

            echo '<a href="#" class="button button-primary zmop-shortcode-button" data-modal-id="'. esc_attr( $instance_key ) .'">'. $button_title .'</a>';

          }

        }

      }

    }
  }
}

/**
 *
 * Field: upload
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'ZMOP_Field_upload' ) ) {
  class ZMOP_Field_upload extends ZMOP_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $args = wp_parse_args( $this->field, array(
        'library'        => array(),
        'preview'        => false,
        'preview_width'  => '',
        'preview_height' => '',
        'button_title'   => '上传',
        'remove_title'   => '删除',
      ) );

      echo $this->field_before();

      $library = ( is_array( $args['library'] ) ) ? $args['library'] : array_filter( (array) $args['library'] );
      $library = ( ! empty( $library ) ) ? implode(',', $library ) : '';
      $hidden  = ( empty( $this->value ) ) ? ' hidden' : '';

      if ( ! empty( $args['preview'] ) ) {

        $preview_type   = ( ! empty( $this->value ) ) ? strtolower( substr( strrchr( $this->value, '.' ), 1 ) ) : '';
        $preview_src    = ( ! empty( $preview_type ) && in_array( $preview_type, array( 'jpg', 'jpeg', 'gif', 'png', 'svg', 'webp' ) ) ) ? $this->value : '';
        $preview_width  = ( ! empty( $args['preview_width'] ) ) ? 'max-width:'. esc_attr( $args['preview_width'] ) .'px;' : '';
        $preview_height = ( ! empty( $args['preview_height'] ) ) ? 'max-height:'. esc_attr( $args['preview_height'] ) .'px;' : '';
        $preview_style  = ( ! empty( $preview_width ) || ! empty( $preview_height ) ) ? ' style="'. esc_attr( $preview_width . $preview_height ) .'"': '';
        $preview_hidden = ( empty( $preview_src ) ) ? ' hidden' : '';

        echo '<div class="zmop--preview'. esc_attr( $preview_hidden ) .'">';
        echo '<div class="zmop-image-preview"'. $preview_style .'>';
        echo '<i class="zmop--remove dashicons dashicons-dismiss"></i><span><img src="'. esc_url( $preview_src ) .'" class="zmop--src" /></span>';
        echo '</div>';
        echo '</div>';

      }

      echo '<div class="zmop--wrap">';
      echo '<input type="text" name="'. esc_attr( $this->field_name() ) .'" value="'. esc_attr( $this->value ) .'"'. $this->field_attributes() .'/>';
      echo '<a href="#" class="button button-primary zmop--button" data-library="'. esc_attr( $library ) .'">'. $args['button_title'] .'</a>';
      echo '<a href="#" class="button button-secondary zmop-warning-primary zmop--remove'. esc_attr( $hidden ) .'">'. $args['remove_title'] .'</a>';
      echo '</div>';

      echo $this->field_after();

    }
  }
}

/**
 *
 * Field: wp_editor
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'ZMOP_Field_wp_editor' ) ) {
  class ZMOP_Field_wp_editor extends ZMOP_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $args = wp_parse_args( $this->field, array(
        'tinymce'       => true,
        'quicktags'     => true,
        'media_buttons' => true,
        'wpautop'       => false,
        'height'        => '',
      ) );

      $attributes = array(
        'rows'         => 10,
        'class'        => 'wp-editor-area',
        'autocomplete' => 'off',
      );

      $editor_height = ( ! empty( $args['height'] ) ) ? ' style="height:'. esc_attr( $args['height'] ) .';"' : '';

      $editor_settings  = array(
        'tinymce'       => $args['tinymce'],
        'quicktags'     => $args['quicktags'],
        'media_buttons' => $args['media_buttons'],
        'wpautop'       => $args['wpautop'],
      );

      echo $this->field_before();

      echo ( zmop_wp_editor_api() ) ? '<div class="zmop-wp-editor" data-editor-settings="'. esc_attr( json_encode( $editor_settings ) ) .'">' : '';

      echo '<textarea name="'. esc_attr( $this->field_name() ) .'"'. $this->field_attributes( $attributes ) . $editor_height .'>'. $this->value .'</textarea>';

      echo ( zmop_wp_editor_api() ) ? '</div>' : '';

      echo $this->field_after();

    }

    public function enqueue() {

      if ( zmop_wp_editor_api() && function_exists( 'wp_enqueue_editor' ) ) {

        wp_enqueue_editor();

        $this->setup_wp_editor_settings();

        add_action( 'print_default_editor_scripts', array( $this, 'setup_wp_editor_media_buttons' ) );

      }

    }

    // Setup wp editor media buttons
    public function setup_wp_editor_media_buttons() {

      if ( ! function_exists( 'media_buttons' ) ) {
        return;
      }

      ob_start();
        echo '<div class="wp-media-buttons">';
          do_action( 'media_buttons' );
        echo '</div>';
      $media_buttons = ob_get_clean();

      echo '<script type="text/javascript">';
      echo 'var zmop_media_buttons = '. json_encode( $media_buttons ) .';';
      echo '</script>';

    }

    // Setup wp editor settings
    public function setup_wp_editor_settings() {

      if ( zmop_wp_editor_api() && class_exists( '_WP_Editors') ) {

        $defaults = apply_filters( 'zmop_wp_editor', array(
          'tinymce' => array(
            'wp_skip_init' => true
          ),
        ) );

        $setup = _WP_Editors::parse_settings( 'zmop_wp_editor', $defaults );

        _WP_Editors::editor_settings( 'zmop_wp_editor', $setup );

      }

    }

  }
}

/**
 *
 * Field: background
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'ZMOP_Field_background' ) ) {
  class ZMOP_Field_background extends ZMOP_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $args                             = wp_parse_args( $this->field, array(
        'background_color'              => true,
        'background_image'              => true,
        'background_position'           => true,
        'background_repeat'             => true,
        'background_attachment'         => true,
        'background_size'               => true,
        'background_origin'             => false,
        'background_clip'               => false,
        'background_blend_mode'         => false,
        'background_gradient'           => false,
        'background_gradient_color'     => true,
        'background_gradient_direction' => true,
        'background_image_preview'      => true,
        'background_auto_attributes'    => false,
        'compact'                       => false,
        'background_image_library'      => 'image',
        'background_image_placeholder'  => '没有可用数据',
      ) );

      if ( $args['compact'] ) {
        $args['background_color']           = false;
        $args['background_auto_attributes'] = true;
      }

      $default_value                    = array(
        'background-color'              => '',
        'background-image'              => '',
        'background-position'           => '',
        'background-repeat'             => '',
        'background-attachment'         => '',
        'background-size'               => '',
        'background-origin'             => '',
        'background-clip'               => '',
        'background-blend-mode'         => '',
        'background-gradient-color'     => '',
        'background-gradient-direction' => '',
      );

      $default_value = ( ! empty( $this->field['default'] ) ) ? wp_parse_args( $this->field['default'], $default_value ) : $default_value;

      $this->value = wp_parse_args( $this->value, $default_value );

      echo $this->field_before();

      echo '<div class="zmop--background-colors">';

      //
      // Background Color
      if ( ! empty( $args['background_color'] ) ) {

        echo '<div class="zmop--color">';

        echo ( ! empty( $args['background_gradient'] ) ) ? '<div class="zmop--title">'. esc_html__( 'From', 'zmop' ) .'</div>' : '';

        ZMOP::field( array(
          'id'      => 'background-color',
          'type'    => 'color',
          'default' => $default_value['background-color'],
        ), $this->value['background-color'], $this->field_name(), 'field/background' );

        echo '</div>';

      }

      //
      // Background Gradient Color
      if ( ! empty( $args['background_gradient_color'] ) && ! empty( $args['background_gradient'] ) ) {

        echo '<div class="zmop--color">';

        echo ( ! empty( $args['background_gradient'] ) ) ? '<div class="zmop--title">'. esc_html__( 'To', 'zmop' ) .'</div>' : '';

        ZMOP::field( array(
          'id'      => 'background-gradient-color',
          'type'    => 'color',
          'default' => $default_value['background-gradient-color'],
        ), $this->value['background-gradient-color'], $this->field_name(), 'field/background' );

        echo '</div>';

      }

      //
      // Background Gradient Direction
      if ( ! empty( $args['background_gradient_direction'] ) && ! empty( $args['background_gradient'] ) ) {

        echo '<div class="zmop--color">';

        echo ( ! empty( $args['background_gradient'] ) ) ? '<div class="zmop---title">'. esc_html__( 'Direction', 'zmop' ) .'</div>' : '';

        ZMOP::field( array(
          'id'          => 'background-gradient-direction',
          'type'        => 'select',
          'options'     => array(
            ''          => esc_html__( 'Gradient Direction', 'zmop' ),
            'to bottom' => esc_html__( '&#8659; top to bottom', 'zmop' ),
            'to right'  => esc_html__( '&#8658; left to right', 'zmop' ),
            '135deg'    => esc_html__( '&#8664; corner top to right', 'zmop' ),
            '-135deg'   => esc_html__( '&#8665; corner top to left', 'zmop' ),
          ),
        ), $this->value['background-gradient-direction'], $this->field_name(), 'field/background' );

        echo '</div>';

      }

      echo '</div>';

      //
      // Background Image
      if ( ! empty( $args['background_image'] ) ) {

        echo '<div class="zmop--background-image">';

        ZMOP::field( array(
          'id'          => 'background-image',
          'type'        => 'media',
          'class'       => 'zmop-assign-field-background',
          'library'     => $args['background_image_library'],
          'preview'     => $args['background_image_preview'],
          'placeholder' => $args['background_image_placeholder'],
          'attributes'  => array( 'data-depend-id' => $this->field['id'] ),
        ), $this->value['background-image'], $this->field_name(), 'field/background' );

        echo '</div>';

      }

      $auto_class   = ( ! empty( $args['background_auto_attributes'] ) ) ? ' zmop--auto-attributes' : '';
      $hidden_class = ( ! empty( $args['background_auto_attributes'] ) && empty( $this->value['background-image']['url'] ) ) ? ' zmop--attributes-hidden' : '';

      echo '<div class="zmop--background-attributes'. esc_attr( $auto_class . $hidden_class ) .'">';

      //
      // Background Position
      if ( ! empty( $args['background_position'] ) ) {

        ZMOP::field( array(
          'id'              => 'background-position',
          'type'            => 'select',
          'options'         => array(
            ''              => esc_html__( 'Background Position', 'zmop' ),
            'left top'      => esc_html__( 'Left Top', 'zmop' ),
            'left center'   => esc_html__( 'Left Center', 'zmop' ),
            'left bottom'   => esc_html__( 'Left Bottom', 'zmop' ),
            'center top'    => esc_html__( 'Center Top', 'zmop' ),
            'center center' => esc_html__( 'Center Center', 'zmop' ),
            'center bottom' => esc_html__( 'Center Bottom', 'zmop' ),
            'right top'     => esc_html__( 'Right Top', 'zmop' ),
            'right center'  => esc_html__( 'Right Center', 'zmop' ),
            'right bottom'  => esc_html__( 'Right Bottom', 'zmop' ),
          ),
        ), $this->value['background-position'], $this->field_name(), 'field/background' );

      }

      //
      // Background Repeat
      if ( ! empty( $args['background_repeat'] ) ) {

        ZMOP::field( array(
          'id'          => 'background-repeat',
          'type'        => 'select',
          'options'     => array(
            ''          => esc_html__( 'Background Repeat', 'zmop' ),
            'repeat'    => esc_html__( 'Repeat', 'zmop' ),
            'no-repeat' => esc_html__( 'No Repeat', 'zmop' ),
            'repeat-x'  => esc_html__( 'Repeat Horizontally', 'zmop' ),
            'repeat-y'  => esc_html__( 'Repeat Vertically', 'zmop' ),
          ),
        ), $this->value['background-repeat'], $this->field_name(), 'field/background' );

      }

      //
      // Background Attachment
      if ( ! empty( $args['background_attachment'] ) ) {

        ZMOP::field( array(
          'id'       => 'background-attachment',
          'type'     => 'select',
          'options'  => array(
            ''       => esc_html__( 'Background Attachment', 'zmop' ),
            'scroll' => esc_html__( 'Scroll', 'zmop' ),
            'fixed'  => esc_html__( 'Fixed', 'zmop' ),
          ),
        ), $this->value['background-attachment'], $this->field_name(), 'field/background' );

      }

      //
      // Background Size
      if ( ! empty( $args['background_size'] ) ) {

        ZMOP::field( array(
          'id'        => 'background-size',
          'type'      => 'select',
          'options'   => array(
            ''        => esc_html__( 'Background Size', 'zmop' ),
            'cover'   => esc_html__( 'Cover', 'zmop' ),
            'contain' => esc_html__( 'Contain', 'zmop' ),
            'auto'    => esc_html__( 'Auto', 'zmop' ),
          ),
        ), $this->value['background-size'], $this->field_name(), 'field/background' );

      }

      //
      // Background Origin
      if ( ! empty( $args['background_origin'] ) ) {

        ZMOP::field( array(
          'id'            => 'background-origin',
          'type'          => 'select',
          'options'       => array(
            ''            => esc_html__( 'Background Origin', 'zmop' ),
            'padding-box' => esc_html__( 'Padding Box', 'zmop' ),
            'border-box'  => esc_html__( 'Border Box', 'zmop' ),
            'content-box' => esc_html__( 'Content Box', 'zmop' ),
          ),
        ), $this->value['background-origin'], $this->field_name(), 'field/background' );

      }

      //
      // Background Clip
      if ( ! empty( $args['background_clip'] ) ) {

        ZMOP::field( array(
          'id'            => 'background-clip',
          'type'          => 'select',
          'options'       => array(
            ''            => esc_html__( 'Background Clip', 'zmop' ),
            'border-box'  => esc_html__( 'Border Box', 'zmop' ),
            'padding-box' => esc_html__( 'Padding Box', 'zmop' ),
            'content-box' => esc_html__( 'Content Box', 'zmop' ),
          ),
        ), $this->value['background-clip'], $this->field_name(), 'field/background' );

      }

      //
      // Background Blend Mode
      if ( ! empty( $args['background_blend_mode'] ) ) {

        ZMOP::field( array(
          'id'            => 'background-blend-mode',
          'type'          => 'select',
          'options'       => array(
            ''            => esc_html__( 'Background Blend Mode', 'zmop' ),
            'normal'      => esc_html__( 'Normal', 'zmop' ),
            'multiply'    => esc_html__( 'Multiply', 'zmop' ),
            'screen'      => esc_html__( 'Screen', 'zmop' ),
            'overlay'     => esc_html__( 'Overlay', 'zmop' ),
            'darken'      => esc_html__( 'Darken', 'zmop' ),
            'lighten'     => esc_html__( 'Lighten', 'zmop' ),
            'color-dodge' => esc_html__( 'Color Dodge', 'zmop' ),
            'saturation'  => esc_html__( 'Saturation', 'zmop' ),
            'color'       => esc_html__( 'Color', 'zmop' ),
            'luminosity'  => esc_html__( 'Luminosity', 'zmop' ),
          ),
        ), $this->value['background-blend-mode'], $this->field_name(), 'field/background' );

      }

      echo '</div>';

      echo $this->field_after();

    }

    public function output() {

      $output    = '';
      $bg_image  = array();
      $important = ( ! empty( $this->field['output_important'] ) ) ? '!important' : '';
      $element   = ( is_array( $this->field['output'] ) ) ? join( ',', $this->field['output'] ) : $this->field['output'];

      // Background image and gradient
      $background_color        = ( ! empty( $this->value['background-color']              ) ) ? $this->value['background-color']              : '';
      $background_gd_color     = ( ! empty( $this->value['background-gradient-color']     ) ) ? $this->value['background-gradient-color']     : '';
      $background_gd_direction = ( ! empty( $this->value['background-gradient-direction'] ) ) ? $this->value['background-gradient-direction'] : '';
      $background_image        = ( ! empty( $this->value['background-image']['url']       ) ) ? $this->value['background-image']['url']       : '';


      if ( $background_color && $background_gd_color ) {
        $gd_direction   = ( $background_gd_direction ) ? $background_gd_direction .',' : '';
        $bg_image[] = 'linear-gradient('. $gd_direction . $background_color .','. $background_gd_color .')';
        unset( $this->value['background-color'] );
      }

      if ( $background_image ) {
        $bg_image[] = 'url('. $background_image .')';
      }

      if ( ! empty( $bg_image ) ) {
        $output .= 'background-image:'. implode( ',', $bg_image ) . $important .';';
      }

      // Common background properties
      $properties = array( 'color', 'position', 'repeat', 'attachment', 'size', 'origin', 'clip', 'blend-mode' );

      foreach ( $properties as $property ) {
        $property = 'background-'. $property;
        if ( ! empty( $this->value[$property] ) ) {
          $output .= $property .':'. $this->value[$property] . $important .';';
        }
      }

      if ( $output ) {
        $output = $element .'{'. $output .'}';
      }

      $this->parent->output_css .= $output;

      return $output;

    }

  }
}

/**
 *
 * Field: checkbox
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'ZMOP_Field_checkbox' ) ) {
  class ZMOP_Field_checkbox extends ZMOP_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $args = wp_parse_args( $this->field, array(
        'inline'     => false,
        'all'        => false,
        'query_args' => array(),
      ) );

      $inline_class = ( $args['inline'] ) ? ' class="zmop--inline-list"' : '';
      $selectall = ( $args['all'] ) ? '<li><label><input type="checkbox" class="selectall" style="display: none;"><span class="all-text">全选</span></label></li>' : '';

      echo $this->field_before();

      if ( isset( $this->field['options'] ) ) {

        $value   = ( is_array( $this->value ) ) ? $this->value : array_filter( (array) $this->value );
        $options = $this->field['options'];
        $options = ( is_array( $options ) ) ? $options : array_filter( $this->field_data( $options, false, $args['query_args'] ) );

        if ( is_array( $options ) && ! empty( $options ) ) {

          echo '<ul'. $inline_class .'>';
          echo $selectall;

          foreach ( $options as $option_key => $option_value ) {

            if ( is_array( $option_value ) && ! empty( $option_value ) ) {

              echo '<li>';
                echo '<ul>';
                  echo '<li><strong>'. esc_attr( $option_key ) .'</strong></li>';
                  foreach ( $option_value as $sub_key => $sub_value ) {
                    $checked = ( in_array( $sub_key, $value ) ) ? ' checked' : '';
                    echo '<li>';
                    echo '<label>';
                    echo '<input type="checkbox" name="'. esc_attr( $this->field_name( '[]' ) ) .'" value="'. esc_attr( $sub_key ) .'"'. $this->field_attributes() . esc_attr( $checked ) .'/>';
                    echo '<span class="zmop--text">'. esc_attr( $sub_value ) .'</span>';
                    echo '</label>';
                    echo '</li>';
                  }
                echo '</ul>';
              echo '</li>';

            } else {

              $checked = ( in_array( $option_key, $value ) ) ? ' checked' : '';

              echo '<li>';
              echo '<label>';
              echo '<input type="checkbox" class="item" name="'. esc_attr( $this->field_name( '[]' ) ) .'" value="'. esc_attr( $option_key ) .'"'. $this->field_attributes() . esc_attr( $checked ) .'/>';
              echo '<span class="zmop--text">'. esc_attr( $option_value ) .'</span>';
              echo '</label>';
              echo '</li>';

            }

          }

          echo '</ul>';

        } else {

          echo ( ! empty( $this->field['empty_message'] ) ) ? esc_attr( $this->field['empty_message'] ) : esc_html__( 'No data available.', 'zmop' );

        }

      } else {

        echo '<label class="zmop-checkbox">';
        echo '<input type="hidden" name="'. esc_attr( $this->field_name() ) .'" value="'. $this->value .'" class="zmop--input"'. $this->field_attributes() .'/>';
        echo '<input type="checkbox" name="_pseudo" class="zmop--checkbox"'. esc_attr( checked( $this->value, 1, false ) ) . $this->field_attributes() .'/>';
        echo ( ! empty( $this->field['label'] ) ) ? '<span class="zmop--text">'. esc_attr( $this->field['label'] ) .'</span>' : '';
        echo '</label>';

      }

      echo $this->field_after();

    }

  }
}

/**
 *
 * Field: group
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'ZMOP_Field_group' ) ) {
  class ZMOP_Field_group extends ZMOP_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $args = wp_parse_args( $this->field, array(
        'max'                       => 0,
        'min'                       => 0,
        'fields'                    => array(),
        'button_title'              => '<i class="dashicons dashicons-insert"></i>',
        'accordion_title_prefix'    => '',
        'accordion_title_number'    => false,
        'accordion_title_auto'      => true,
        'accordion_title_by'        => array(),
        'accordion_title_by_prefix' => ' ',
      ) );

      $title_prefix    = ( ! empty( $args['accordion_title_prefix'] ) ) ? $args['accordion_title_prefix'] : '';
      $title_number    = ( ! empty( $args['accordion_title_number'] ) ) ? true : false;
      $title_auto      = ( ! empty( $args['accordion_title_auto'] ) ) ? true : false;
      $title_first     = ( isset( $this->field['fields'][0]['id'] ) ) ? $this->field['fields'][0]['id'] : $this->field['fields'][1]['id'];
      $title_by        = ( is_array( $args['accordion_title_by'] ) ) ? $args['accordion_title_by'] : (array) $args['accordion_title_by'];
      $title_by        = ( empty( $title_by ) ) ? array( $title_first ) : $title_by;
      $title_by_prefix = ( ! empty( $args['accordion_title_by_prefix'] ) ) ? $args['accordion_title_by_prefix'] : '';

      if ( preg_match( '/'. preg_quote( '['. $this->field['id'] .']' ) .'/', $this->unique ) ) {

        echo '<div class="zmop-notice zmop-notice-danger">'. esc_html__( 'Error: Field ID conflict.', 'zmop' ) .'</div>';

      } else {

        echo $this->field_before();

        echo '<div class="zmop-cloneable-item zmop-cloneable-hidden" data-depend-id="'. esc_attr( $this->field['id'] ) .'">';

          echo '<div class="zmop-cloneable-helper">';
          echo '<i class="zmop-cloneable-sort dashicons dashicons-move"></i>';
          echo '<i class="zmop-cloneable-clone dashicons dashicons-admin-page"></i>';
          echo '<i class="zmop-cloneable-remove zmop-confirm dashicons dashicons-no" data-confirm="'. esc_html__( '确定要删除吗？', 'zmop' ) .'"></i>';
          echo '</div>';

          echo '<h4 class="zmop-cloneable-title">';
          echo '<span class="zmop-cloneable-text">';
          echo ( $title_number ) ? '<span class="zmop-cloneable-title-number"></span>' : '';
          echo ( $title_prefix ) ? '<span class="zmop-cloneable-title-prefix">'. esc_attr( $title_prefix ) .'</span>' : '';
          echo ( $title_auto ) ? '<span class="zmop-cloneable-value"><span class="zmop-cloneable-placeholder"></span></span>' : '';
          echo '</span>';
          echo '</h4>';

          echo '<div class="zmop-cloneable-content">';
          foreach ( $this->field['fields'] as $field ) {

            $field_default = ( isset( $field['default'] ) ) ? $field['default'] : '';
            $field_unique  = ( ! empty( $this->unique ) ) ? $this->unique .'['. $this->field['id'] .'][0]' : $this->field['id'] .'[0]';

            ZMOP::field( $field, $field_default, '___'. $field_unique, 'field/group' );

          }
          echo '</div>';

        echo '</div>';

        echo '<div class="zmop-cloneable-wrapper zmop-data-wrapper" data-title-by="'. esc_attr( json_encode( $title_by ) ) .'" data-title-by-prefix="'. esc_attr( $title_by_prefix ) .'" data-title-number="'. esc_attr( $title_number ) .'" data-field-id="['. esc_attr( $this->field['id'] ) .']" data-max="'. esc_attr( $args['max'] ) .'" data-min="'. esc_attr( $args['min'] ) .'">';

        if ( ! empty( $this->value ) ) {

          $num = 0;

          foreach ( $this->value as $value ) {

            $title = '';

            if ( ! empty( $title_by ) ) {

              $titles = array();

              foreach ( $title_by as $title_key ) {
                if ( isset( $value[ $title_key ] ) ) {
                  $titles[] = $value[ $title_key ];
                }
              }

              $title = join( $title_by_prefix, $titles );

            }

            $title = ( is_array( $title ) ) ? reset( $title ) : $title;

            echo '<div class="zmop-cloneable-item">';

              echo '<div class="zmop-cloneable-helper">';
              echo '<i class="zmop-cloneable-sort dashicons dashicons-move"></i>';
              echo '<i class="zmop-cloneable-clone dashicons dashicons-admin-page"></i>';
              echo '<i class="zmop-cloneable-remove zmop-confirm dashicons dashicons-no" data-confirm="确定要删除吗？"></i>';
              echo '</div>';

              echo '<h4 class="zmop-cloneable-title">';
              echo '<span class="zmop-cloneable-text">';
              echo ( $title_number ) ? '<span class="zmop-cloneable-title-number">'. esc_attr( $num+1 ) .'.</span>' : '';
              echo ( $title_prefix ) ? '<span class="zmop-cloneable-title-prefix">'. esc_attr( $title_prefix ) .'</span>' : '';
              echo ( $title_auto ) ? '<span class="zmop-cloneable-value">' . esc_attr( $title ) .'</span>' : '';
              echo '</span>';
              echo '</h4>';

              echo '<div class="zmop-cloneable-content">';

              foreach ( $this->field['fields'] as $field ) {

                $field_unique = ( ! empty( $this->unique ) ) ? $this->unique .'['. $this->field['id'] .']['. $num .']' : $this->field['id'] .'['. $num .']';
                $field_value  = ( isset( $field['id'] ) && isset( $value[$field['id']] ) ) ? $value[$field['id']] : '';

                ZMOP::field( $field, $field_value, $field_unique, 'field/group' );

              }

              echo '</div>';

            echo '</div>';

            $num++;

          }

        }

        echo '</div>';

        echo '<div class="zmop-cloneable-alert zmop-cloneable-max">不能再添加了</div>';
        echo '<div class="zmop-cloneable-alert zmop-cloneable-min">不能删除</div>';
        echo '<a href="#" class="button button-primary zmop-cloneable-add">'. $args['button_title'] .'</a>';

        echo $this->field_after();

      }

    }

    public function enqueue() {

      if ( ! wp_script_is( 'jquery-ui-accordion' ) ) {
        wp_enqueue_script( 'jquery-ui-accordion' );
      }

      if ( ! wp_script_is( 'jquery-ui-sortable' ) ) {
        wp_enqueue_script( 'jquery-ui-sortable' );
      }

    }

  }
}

/**
 *
 * Field: repeater
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'ZMOP_Field_repeater' ) ) {
	class ZMOP_Field_repeater extends ZMOP_Fields {

	public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
		parent::__construct( $field, $value, $unique, $where, $parent );
	}

	public function render() {

		$args = wp_parse_args( $this->field, array(
			'max'          => 0,
			'min'          => 0,
			'button_title' => '<i class="dashicons dashicons-insert"></i>',
		) );

		if ( preg_match( '/'. preg_quote( '['. $this->field['id'] .']' ) .'/', $this->unique ) ) {

			echo '<div class="zmop-notice zmop-notice-danger">'. esc_html__( 'Error: Field ID conflict.', 'zmop' ) .'</div>';

		} else {

			echo $this->field_before();

		echo '<div class="zmop-repeater-item zmop-repeater-hidden" data-depend-id="'. esc_attr( $this->field['id'] ) .'">';
        echo '<div class="zmop-repeater-content">';
        foreach ( $this->field['fields'] as $field ) {

          $field_default = ( isset( $field['default'] ) ) ? $field['default'] : '';
          $field_unique  = ( ! empty( $this->unique ) ) ? $this->unique .'['. $this->field['id'] .'][0]' : $this->field['id'] .'[0]';

          ZMOP::field( $field, $field_default, '___'. $field_unique, 'field/repeater' );

        }
        echo '</div>';
        echo '<div class="zmop-repeater-helper">';
        echo '<div class="zmop-repeater-helper-inner">';
        echo '<i class="zmop-repeater-sort dashicons dashicons-move"></i>';
        echo '<i class="zmop-repeater-clone dashicons dashicons-admin-page"></i>';
        echo '<i class="zmop-repeater-remove zmop-confirm dashicons dashicons-no" data-confirm="'. esc_html__( '确定要删除吗？', 'zmop' ) .'"></i>';
        echo '</div>';
        echo '</div>';
        echo '</div>';

        echo '<div class="zmop-repeater-wrapper zmop-data-wrapper" data-field-id="['. esc_attr( $this->field['id'] ) .']" data-max="'. esc_attr( $args['max'] ) .'" data-min="'. esc_attr( $args['min'] ) .'">';

        if ( ! empty( $this->value ) && is_array( $this->value ) ) {

          $num = 0;

          foreach ( $this->value as $key => $value ) {

            echo '<div class="zmop-repeater-item">';
            echo '<div class="zmop-repeater-content">';
            foreach ( $this->field['fields'] as $field ) {

              $field_unique = ( ! empty( $this->unique ) ) ? $this->unique .'['. $this->field['id'] .']['. $num .']' : $this->field['id'] .'['. $num .']';
              $field_value  = ( isset( $field['id'] ) && isset( $this->value[$key][$field['id']] ) ) ? $this->value[$key][$field['id']] : '';

              ZMOP::field( $field, $field_value, $field_unique, 'field/repeater' );

            }
            echo '</div>';
            echo '<div class="zmop-repeater-helper">';
            echo '<div class="zmop-repeater-helper-inner">';
            echo '<i class="zmop-repeater-sort dashicons dashicons-move"></i>';
            echo '<i class="zmop-repeater-clone dashicons dashicons-admin-page"></i>';
            echo '<i class="zmop-repeater-remove zmop-confirm dashicons dashicons-no" data-confirm="'. esc_html__( '确定要删除吗？', 'zmop' ) .'"></i>';
            echo '</div>';
            echo '</div>';
            echo '</div>';

            $num++;

          }

        }

        echo '</div>';

        echo '<div class="zmop-repeater-alert zmop-repeater-max">'. esc_html__( 'You cannot add more.', 'zmop' ) .'</div>';
        echo '<div class="zmop-repeater-alert zmop-repeater-min">'. esc_html__( 'You cannot remove more.', 'zmop' ) .'</div>';
        echo '<a href="#" class="button button-primary zmop-repeater-add">'. $args['button_title'] .'</a>';

        echo $this->field_after();

      }

    }

    public function enqueue() {

      if ( ! wp_script_is( 'jquery-ui-sortable' ) ) {
        wp_enqueue_script( 'jquery-ui-sortable' );
      }

    }

  }
}

/**
 *
 * Field: tabbed
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'ZMOP_Field_tabbed' ) ) {
  class ZMOP_Field_tabbed extends ZMOP_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $unallows = array( 'tabbed' );

      echo $this->field_before();

      echo '<div class="zmop-tabbed-nav" data-depend-id="'. esc_attr( $this->field['id'] ) .'">';
      foreach ( $this->field['tabs'] as $key => $tab ) {

        $tabbed_icon   = ( ! empty( $tab['icon'] ) ) ? '<i class="zmop--icon '. esc_attr( $tab['icon'] ) .'"></i>' : '';
        $tabbed_active = ( empty( $key ) ) ? 'zmop-tabbed-active' : '';

        echo '<a href="#" class="'. esc_attr( $tabbed_active ) .'"">'. $tabbed_icon . esc_attr( $tab['title'] ) .'</a>';

      }
      echo '</div>';

      echo '<div class="zmop-tabbed-contents">';
      foreach ( $this->field['tabs'] as $key => $tab ) {

        $tabbed_hidden = ( ! empty( $key ) ) ? ' hidden' : '';

        echo '<div class="zmop-tabbed-content'. esc_attr( $tabbed_hidden ) .'">';

        foreach ( $tab['fields'] as $field ) {

          if ( in_array( $field['type'], $unallows ) ) { $field['_notice'] = true; }

          $field_id      = ( isset( $field['id'] ) ) ? $field['id'] : '';
          $field_default = ( isset( $field['default'] ) ) ? $field['default'] : '';
          $field_value   = ( isset( $this->value[$field_id] ) ) ? $this->value[$field_id] : $field_default;
          $unique_id     = ( ! empty( $this->unique ) ) ? $this->unique .'['. $this->field['id'] .']' : $this->field['id'];

          ZMOP::field( $field, $field_value, $unique_id, 'field/tabbed' );

        }

        echo '</div>';

      }
      echo '</div>';

      echo $this->field_after();

    }

  }
}

