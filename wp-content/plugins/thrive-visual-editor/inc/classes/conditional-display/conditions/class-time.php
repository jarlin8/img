<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-visual-editor
 */

namespace TCB\ConditionalDisplay\Conditions;

use TCB\ConditionalDisplay\Condition;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

class Time extends Date_And_Time_Picker {
	/**
	 * @return string
	 */
	public static function get_key() {
		return 'time';
	}

	/**
	 * @return string
	 */
	public static function get_label() {
		return esc_html__( 'Time comparison', 'thrive-cb' );
	}

	public function apply( $data ) {
		$compared_value = $this->get_value();
		$field_value    = $data['field_value'];

		$formatted_compared_value = $compared_value['hours'] . ':' . $compared_value['minutes'];

		switch ( $this->get_operator() ) {
			case 'before':
				$result = strtotime( $field_value ) <= strtotime( $formatted_compared_value );
				break;
			case 'after':
				$result = strtotime( $field_value ) >= strtotime( $formatted_compared_value );
				break;
			default:
				$result = false;
		}

		return $result;
	}

	public static function get_control_type() {
		return static::get_key();
	}

	public static function get_operators() {
		return [
			'before' => [
				'label' => 'before',
			],
			'after'  => [
				'label' => 'after',
			],
		];
	}
}
