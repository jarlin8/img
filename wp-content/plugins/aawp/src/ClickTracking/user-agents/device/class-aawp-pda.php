<?php

class AAWP_Pda {
	public function __construct( $ua ) {
		$this->detectPDA( $ua );
	}

	private function detectPDA( $u ) {
		$this->type = 'pda';
	}
}