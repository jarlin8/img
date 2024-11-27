<?php

class AAWP_Tablet {
	public function __construct( $ua ) {
		$this->detectWebTab( $ua );
	}

	/* WeTab */

	private function detectWebTab( $ua ) {
		if ( preg_match( '/WeTab-Browser /ui', $ua, $match ) ) {
			$this->type = 'tablet';
			$this->data = array(
				'manufacturer' => 'WeTab',
				'model'        => 'WeTab'
			);
		}
	}
}