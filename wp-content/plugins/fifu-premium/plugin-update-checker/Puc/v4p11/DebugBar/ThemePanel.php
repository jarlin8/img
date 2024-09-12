<?php

if ( !class_exists('Puc_v4p11_Fifu_DebugBar_ThemePanel', false) && (!class_exists('QM_DB') || is_plugin_active('debug-bar/debug-bar.php')) ):

	class Puc_v4p11_Fifu_DebugBar_ThemePanel extends Puc_v4p11_Fifu_DebugBar_Panel {
		/**
		 * @var Puc_v4p11_Fifu_Theme_UpdateChecker
		 */
		protected $updateChecker;

		protected function displayConfigHeader() {
			$this->row('Theme directory', htmlentities($this->updateChecker->directoryName));
			parent::displayConfigHeader();
		}

		protected function getUpdateFields() {
			return array_merge(parent::getUpdateFields(), array('details_url'));
		}
	}

endif;
