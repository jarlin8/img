<?php
if ( ! defined( 'ABSPATH' ) ) exit;
// 音频过滤函数
class BeAudioIgniter_Sanitizer {
	// 播放器模式
	public static function player_type( $value ) {
		$choices = BeAudioIgniter()->get_player_types();
		if ( array_key_exists( $value, $choices ) ) {
			return $value;
		}

		return 'full';
	}

	public static function player_style( $value ) {
		$choices = BeAudioIgniter()->get_player_styles();
		if ( array_key_exists( $value, $choices ) ) {
			return $value;
		}

		return 'white';
	}
	// 循环播放
	public static function metabox_playlist( $post_tracks, $post_id = null ) {
		if ( empty( $post_tracks ) || ! is_array( $post_tracks ) ) {
			return array();
		}

		$tracks = array();

		foreach ( $post_tracks as $uid => $track_data ) {
			$track = self::playlist_track( $track_data, $post_id, $uid );
			if ( false !== $track ) {
				$tracks[] = $track;
			}
		}

		return apply_filters( 'audioigniter_sanitize_playlist', $tracks, $post_tracks, $post_id );
	}

	public static function playlist_track( $track, $post_id = null, $track_uid = '' ) {
		$track = wp_parse_args( $track, BeAudioIgniter::get_default_track_values() );

		$sanitized_track = array();

		$sanitized_track['cover_id']                = intval( $track['cover_id'] );
		$sanitized_track['title']                   = sanitize_text_field( $track['title'] );
		$sanitized_track['artist']                  = sanitize_text_field( $track['artist'] );
		$sanitized_track['track_url']               = esc_url_raw( $track['track_url'] );
		$sanitized_track['cover_link']              = esc_url_raw( $track['cover_link'] );

		$sanitized_track = array_map( 'trim', $sanitized_track );

		$tmp = array_filter( $sanitized_track );
		if ( empty( $tmp ) ) {
			$sanitized_track = false;
		}

		return apply_filters( 'audioigniter_sanitize_playlist_track', $sanitized_track, $track, $post_id, $track_uid );
	}

	// 复选框
	public static function checkbox( $input ) {
		if ( 1 == $input ) {
			return 1;
		}

		return '';
	}

	// 复选框
	public static function checkbox_ref( &$input ) {
		if ( 1 == $input ) {
			return 1;
		}

		return '';
	}

	// 整数输入
	public static function intval_or_empty( $input ) {
		if ( is_null( $input ) || false === $input || '' === $input ) {
			return '';
		}

		if ( 0 == $input ) {
			return 0;
		}

		return intval( $input );
	}

	// 颜色
	public static function hex_color( $str, $return_hash = true, $return_fail = '' ) {
		if ( false === $str || empty( $str ) || 'false' === $str ) {
			return $return_fail;
		}

		// Allow keywords and predefined colors
		if ( in_array( $str, array( 'transparent', 'initial', 'inherit', 'black', 'silver', 'gray', 'grey', 'white', 'maroon', 'red', 'purple', 'fuchsia', 'green', 'lime', 'olive', 'yellow', 'navy', 'blue', 'teal', 'aqua', 'orange', 'aliceblue', 'antiquewhite', 'aquamarine', 'azure', 'beige', 'bisque', 'blanchedalmond', 'blueviolet', 'brown', 'burlywood', 'cadetblue', 'chartreuse', 'chocolate', 'coral', 'cornflowerblue', 'cornsilk', 'crimson', 'darkblue', 'darkcyan', 'darkgoldenrod', 'darkgray', 'darkgrey', 'darkgreen', 'darkkhaki', 'darkmagenta', 'darkolivegreen', 'darkorange', 'darkorchid', 'darkred', 'darksalmon', 'darkseagreen', 'darkslateblue', 'darkslategray', 'darkslategrey', 'darkturquoise', 'darkviolet', 'deeppink', 'deepskyblue', 'dimgray', 'dimgrey', 'dodgerblue', 'firebrick', 'floralwhite', 'forestgreen', 'gainsboro', 'ghostwhite', 'gold', 'goldenrod', 'greenyellow', 'grey', 'honeydew', 'hotpink', 'indianred', 'indigo', 'ivory', 'khaki', 'lavender', 'lavenderblush', 'lawngreen', 'lemonchiffon', 'lightblue', 'lightcoral', 'lightcyan', 'lightgoldenrodyellow', 'lightgray', 'lightgreen', 'lightgrey', 'lightpink', 'lightsalmon', 'lightseagreen', 'lightskyblue', 'lightslategray', 'lightslategrey', 'lightsteelblue', 'lightyellow', 'limegreen', 'linen', 'mediumaquamarine', 'mediumblue', 'mediumorchid', 'mediumpurple', 'mediumseagreen', 'mediumslateblue', 'mediumspringgreen', 'mediumturquoise', 'mediumvioletred', 'midnightblue', 'mintcream', 'mistyrose', 'moccasin', 'navajowhite', 'oldlace', 'olivedrab', 'orangered', 'orchid', 'palegoldenrod', 'palegreen', 'paleturquoise', 'palevioletred', 'papayawhip', 'peachpuff', 'peru', 'pink', 'plum', 'powderblue', 'rosybrown', 'royalblue', 'saddlebrown', 'salmon', 'sandybrown', 'seagreen', 'seashell', 'sienna', 'skyblue', 'slateblue', 'slategray', 'slategrey', 'snow', 'springgreen', 'steelblue', 'tan', 'thistle', 'tomato', 'turquoise', 'violet', 'wheat', 'whitesmoke', 'yellowgreen', 'rebeccapurple' ), true ) ) {
			return $str;
		}

		// Include the hash if not there.
		// The regex below depends on in.
		if ( substr( $str, 0, 1 ) !== '#' ) {
			$str = '#' . $str;
		}

		preg_match( '/(#)([0-9a-fA-F]{6})/', $str, $matches );

		if ( count( $matches ) === 3 ) {
			if ( $return_hash ) {
				return $matches[1] . $matches[2];
			} else {
				return $matches[2];
			}
		}

		return $return_fail;
	}

	// CSS 颜色
	public static function rgba_color( $color, $return_hash = true, $return_fail = '' ) {
		if ( false === $color || empty( $color ) || 'false' === $color ) {
			return $return_fail;
		}

		// Allow keywords and predefined colors
		if ( in_array( $color, array( 'transparent', 'initial', 'inherit', 'black', 'silver', 'gray', 'grey', 'white', 'maroon', 'red', 'purple', 'fuchsia', 'green', 'lime', 'olive', 'yellow', 'navy', 'blue', 'teal', 'aqua', 'orange', 'aliceblue', 'antiquewhite', 'aquamarine', 'azure', 'beige', 'bisque', 'blanchedalmond', 'blueviolet', 'brown', 'burlywood', 'cadetblue', 'chartreuse', 'chocolate', 'coral', 'cornflowerblue', 'cornsilk', 'crimson', 'darkblue', 'darkcyan', 'darkgoldenrod', 'darkgray', 'darkgrey', 'darkgreen', 'darkkhaki', 'darkmagenta', 'darkolivegreen', 'darkorange', 'darkorchid', 'darkred', 'darksalmon', 'darkseagreen', 'darkslateblue', 'darkslategray', 'darkslategrey', 'darkturquoise', 'darkviolet', 'deeppink', 'deepskyblue', 'dimgray', 'dimgrey', 'dodgerblue', 'firebrick', 'floralwhite', 'forestgreen', 'gainsboro', 'ghostwhite', 'gold', 'goldenrod', 'greenyellow', 'grey', 'honeydew', 'hotpink', 'indianred', 'indigo', 'ivory', 'khaki', 'lavender', 'lavenderblush', 'lawngreen', 'lemonchiffon', 'lightblue', 'lightcoral', 'lightcyan', 'lightgoldenrodyellow', 'lightgray', 'lightgreen', 'lightgrey', 'lightpink', 'lightsalmon', 'lightseagreen', 'lightskyblue', 'lightslategray', 'lightslategrey', 'lightsteelblue', 'lightyellow', 'limegreen', 'linen', 'mediumaquamarine', 'mediumblue', 'mediumorchid', 'mediumpurple', 'mediumseagreen', 'mediumslateblue', 'mediumspringgreen', 'mediumturquoise', 'mediumvioletred', 'midnightblue', 'mintcream', 'mistyrose', 'moccasin', 'navajowhite', 'oldlace', 'olivedrab', 'orangered', 'orchid', 'palegoldenrod', 'palegreen', 'paleturquoise', 'palevioletred', 'papayawhip', 'peachpuff', 'peru', 'pink', 'plum', 'powderblue', 'rosybrown', 'royalblue', 'saddlebrown', 'salmon', 'sandybrown', 'seagreen', 'seashell', 'sienna', 'skyblue', 'slateblue', 'slategray', 'slategrey', 'snow', 'springgreen', 'steelblue', 'tan', 'thistle', 'tomato', 'turquoise', 'violet', 'wheat', 'whitesmoke', 'yellowgreen', 'rebeccapurple' ), true ) ) {
			return $color;
		}

		preg_match( '/rgba\(\s*(\d{1,3}\.?\d*\%?)\s*,\s*(\d{1,3}\.?\d*\%?)\s*,\s*(\d{1,3}\.?\d*\%?)\s*,\s*(\d{1}\.?\d*\%?)\s*\)/', $color, $rgba_matches );
		if ( ! empty( $rgba_matches ) && 5 === count( $rgba_matches ) ) {
			for ( $i = 1; $i < 4; $i++ ) {
				if ( strpos( $rgba_matches[ $i ], '%' ) !== false ) {
					$rgba_matches[ $i ] = self::float_0_100( $rgba_matches[ $i ] );
				} else {
					$rgba_matches[ $i ] = self::int_0_255( $rgba_matches[ $i ] );
				}
			}
			$rgba_matches[4] = self::float_0_1( $rgba_matches[ $i ] );
			return sprintf( 'rgba(%s, %s, %s, %s)', $rgba_matches[1], $rgba_matches[2], $rgba_matches[3], $rgba_matches[4] );
		}

		// Not a color function either. Let's see if it's a hex color.

		// Include the hash if not there.
		// The regex below depends on in.
		if ( substr( $color, 0, 1 ) !== '#' ) {
			$color = '#' . $color;
		}

		preg_match( '/(#)([0-9a-fA-F]{6})/', $color, $matches );

		if ( 3 === count( $matches ) ) {
			if ( $return_hash ) {
				return $matches[1] . $matches[2];
			} else {
				return $matches[2];
			}
		}

		return $return_fail;
	}

	// 清理一个百分比值
	public static function float_0_100( $value ) {
		$value = str_replace( '%', '', $value );
		if ( floatval( $value ) > 100 ) {
			$value = 100;
		} elseif ( floatval( $value ) < 0 ) {
			$value = 0;
		}

		return floatval( $value ) . '%';
	}

	// 清理CSS 颜色值
	public static function int_0_255( $value ) {
		if ( intval( $value ) > 255 ) {
			$value = 255;
		} elseif ( intval( $value ) < 0 ) {
			$value = 0;
		}

		return intval( $value );
	}

	// 清除CSS不透明度值0 - 1
	public static function float_0_1( $value ) {
		if ( floatval( $value ) > 1 ) {
			$value = 1;
		} elseif ( floatval( $value ) < 0 ) {
			$value = 0;
		}

		return floatval( $value );
	}

	// 移除不是有效数据属性名的元素。
	public static function html_data_attributes_array( $array ) {
		$keys       = array_keys( $array );
		$key_prefix = 'data-';

		// Remove keys that are not data attributes.
		foreach ( $keys as $key ) {
			if ( substr( $key, 0, strlen( $key_prefix ) ) !== $key_prefix ) {
				unset( $array[ $key ] );
			}
		}

		return $array;
	}

	// 当值为空或null时返回false。
	public static function array_filter_empty_null( $value ) {
		if ( '' === $value || is_null( $value ) ) {
			return false;
		}

		return true;
	}
}