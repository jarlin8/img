<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-visual-editor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class TCB_Contact_Form_Element
 *
 * Element class
 */
class TCB_Contact_Form_Element extends TCB_Cloud_Template_Element_Abstract {

	/**
	 * Element category that will be displayed in the sidebar
	 *
	 * @return string
	 */
	public function category() {
		return static::get_thrive_advanced_label();
	}

	public function hide() {
		return true;
	}

	/**
	 * Name of the Element in sidebar
	 *
	 * @return string
	 */
	public function name() {

		return __( 'Contact Form', 'thrive-cb' );
	}

	/**
	 * Which svg symbol id to use
	 *
	 * @return string
	 */
	public function icon() {
		return 'contact_form';
	}

	/**
	 * When element is selected in editor this identifier
	 * establishes element _type
	 *
	 * @return string
	 * @see TVE.main.element_selected() TVE._type()
	 *
	 */
	public function identifier() {

		return '.thrv-contact-form';
	}

	public function own_components() {
		return array();
	}

	public function is_placeholder() {
		return false;
	}
}