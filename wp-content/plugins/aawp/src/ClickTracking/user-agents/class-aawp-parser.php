<?php

class AAWP_Parser {
	/**
	 * User Agent String
	 * @var string
	 */
	public $ua;

	/** @var string */
	public $type = '';

	/** @var string */
	public $os = '';

	/** @var string */
	public $bot = false;

	/** @var array */
	public $data = [];

	public function __construct( $ua ) {
		$this->ua = $ua;

		$this->load_dependencies();

		$this->get_device( $this->ua );

	}

	private function load_dependencies() {
		
		require_once AAWP_PLUGIN_DIR . 'src/ClickTracking/user-agents/class-aawp-bot.php';
		require_once AAWP_PLUGIN_DIR . 'src/ClickTracking/user-agents/class-aawp-device.php';
		require_once AAWP_PLUGIN_DIR . 'src/ClickTracking/user-agents/class-aawp-os.php';
	}

	public function get_device( $ua ) {
		$bot       = new AAWP_Bot( $ua );
		$this->bot = $bot->is_bot;

		$device = new AAWP_Device( $ua );
		if ( $device ) {
			$this->type = isset( $device->device->type ) ? $device->device->type : 'Unknown Device';
			$this->os   = isset( $device->device->os ) ? $device->device->os : 'Unknown Os';
			$this->data = isset( $device->device->data ) ? $device->device->data : null;
		}

		$os = new AAWP_OS( $ua );
		if ( $os ) {
			$name       = isset( $os->name ) ? $os->name : 'Unknown OS';
			$version    = isset( $os->version ) ? ' ' . $os->version : '';
			$this->os   = $name . $version;
			$this->type = isset( $os->type ) ? $os->type : $this->type;
		}
	}

}