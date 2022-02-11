<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-visual-editor
 */

namespace TCB\ConditionalDisplay\Conditions;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

class Date extends Date_And_Time_Picker {
	/**
	 * @return string
	 */
	public static function get_key() {
		return 'date';
	}

	/**
	 * @return string
	 */
	public static function get_label() {
		return esc_html__( 'Date comparison', 'thrive-cb' );
	}

	public function apply( $data ) {
		$result = false;

		$field_value = $data['field_value'];

		if ( ! empty( $field_value ) ) {
			if ( $this->get_operator() === 'equals' ) {
				$compared_value = $this->get_value();

				$result = strtotime( date( 'Y/m/d', strtotime( $field_value ) ) )
				          ===
				          strtotime( date( 'Y/m/d', strtotime( $compared_value ) ) );
			} else {
				$result = parent::apply( $data );
			}
		}

		return $result;
	}

	public static function get_control_type() {
		return static::get_key();
	}

	/**
	 * @return array
	 */
	public static function get_validation_data() {
		return [];
	}
}
