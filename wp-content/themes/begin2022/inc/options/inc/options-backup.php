<?php
if ( ! defined( 'ABSPATH' ) ) exit;
class OptionsFramework_Backup {
	private $admin_page;
	private $token;

	public function __construct() {
		$this->admin_page = '';
		$this->token = 'begin-options-backup';
	}

	function init () {
		if ( is_admin() && ( get_option( 'framework_woo_backupmenu_disable' ) != 'true' ) ) {
			add_action( 'admin_menu', array( $this, 'register_admin_screen' ), 20 );
		}
	}

	function register_admin_screen () {
		$this->admin_page = add_submenu_page('themes.php', __( '备份选项', 'begin' ), '<span class="menu-icon"> ' . __( '备份选项', 'begin' ) . '</span>', 'manage_options', $this->token, array( $this, 'admin_screen' ) );
		add_action( 'load-' . $this->admin_page, array( $this, 'admin_screen_logic' ) );
		add_action( 'admin_notices', array( $this, 'admin_notices' ), 10 );
	}

	function admin_screen () {
			$export_type = 'all';
			if ( isset( $_POST['export-type'] ) ) {
				$export_type = esc_attr( $_POST['export-type'] );
			}
		?>

		<div class="wrap">
			<h2 style="margin: 10px 0;"><span class="dashicons dashicons-update" style="font-size: 34px;color: #555;margin-right: 20px;vertical-align: -26%;"></span><?php _e( '备份恢复主题设置', 'begin' ); ?></h2>

			<div class="export" style="background: #fff;max-width: 830px;margin-bottom: 10px;padding: 5px 25px 10px 25px;border-radius: 3px;border: 1px solid #dadada;">
				<h3><?php _e( '导出备份' ); ?></h3>
				<p><?php echo sprintf( __( '导出主题选项设置', 'begin' ) ); ?></p>
				<form method="post" action="<?php echo admin_url( 'admin.php?page=' . $this->token ); ?>">
					<?php wp_nonce_field( 'OptionsFramework-backup-export' ); ?>
					<input type="hidden" name="OptionsFramework-backup-export" value="1" />
					<input type="submit" class="button button-primary" value="<?php _e( '导出', 'theme-textdomain' ); ?>" />
				</form>
				<p></p>
			</div>

			<div class="import" style="background: #fff;max-width: 830px;padding: 5px 25px 10px 25px;border-radius: 3px;border: 1px solid #dadada;">
				<h3><?php _e( '导入备份' ); ?></h3>
				<p><?php echo sprintf( __( '导入主题选项设置', 'begin' ) ); ?></p>
				<form enctype="multipart/form-data" method="post" action="<?php echo admin_url( 'admin.php?page=' . $this->token ); ?>">
					<?php wp_nonce_field( 'OptionsFramework-backup-import' ); ?>
					<input type="file" id="OptionsFramework-import-file" name="OptionsFramework-import-file" size="25" />
					<input type="hidden" name="OptionsFramework-backup-import" value="1" />
					<input type="submit" class="button button-primary" value="<?php _e( '上传并导入' ); ?>" />
				</form>
				<p><?php _e( '将替换目前的主题选项设置，导入的设置与当前相同将不会导入', 'begin' ); ?></p>
			</div>

		</div>
	<?php }

	function admin_notices () {
		if ( ! isset( $_GET['page'] ) || ( $_GET['page'] != $this->token ) ) { return; }
		if ( isset( $_GET['error'] ) && $_GET['error'] == 'true' ) {
			echo '<div id="message" class="error" style="max-width: 840px;padding: 0 18px;"><p>' . sprintf(__( '导入失败，请重试！', 'begin' )) . '</p></div>';
		} else if ( isset( $_GET['error-export'] ) && $_GET['error-export'] == 'true' ) {  
			echo '<div id="message" class="error" style="max-width: 840px;padding: 0 18px;"><p>' . sprintf(__( '导入失败，请重试！', 'begin' )) . '</p></div>';
		} else if ( isset( $_GET['invalid'] ) && $_GET['invalid'] == 'true' ) {  
			echo '<div id="message" class="error" style="max-width: 840px;padding: 0 18px;"><p>' . sprintf(__( '导入失败，请重试！', 'begin' )) . '</p></div>';
		} else if ( isset( $_GET['imported'] ) && $_GET['imported'] == 'true' ) {  
			echo '<div id="message" class="updated" style="max-width: 840px;padding: 0 18px;"><p>' . sprintf( __( '导入成功！%s主题选项%s', 'begin' ), '<a style="text-decoration: none;" href="' . admin_url( 'admin.php?page=begin-options' ) . '" rel="external nofollow" >', '</a>' ) . '</p></div>';
		}
	}

	function admin_screen_logic () {
		if ( ! isset( $_POST['OptionsFramework-backup-export'] ) && isset( $_POST['OptionsFramework-backup-import'] ) && ( $_POST['OptionsFramework-backup-import'] == true ) ) {
			$this->import();
		}
		if ( ! isset( $_POST['OptionsFramework-backup-import'] ) && isset( $_POST['OptionsFramework-backup-export'] ) && ( $_POST['OptionsFramework-backup-export'] == true ) ) {
			$this->export();
		}
	}

	function import() {
		check_admin_referer( 'OptionsFramework-backup-import' );
		if ( ! isset( $_FILES['OptionsFramework-import-file'] ) || $_FILES['OptionsFramework-import-file']['error'] ) {
			$upload = '';
		} else {
			$upload = file_get_contents( $_FILES['OptionsFramework-import-file']['tmp_name'] );
		}
		$datafile = json_decode( $upload, true );
		if ( ! $datafile || $_FILES['OptionsFramework-import-file']['error'] ) {
			wp_redirect( admin_url( 'admin.php?page=' . $this->token . '&error=true' ) );
			exit;
		}

		if ( ! isset( $datafile['OptionsFramework-backup-validator'] ) ) {
			wp_redirect( admin_url( 'admin.php?page=' . $this->token . '&invalid=true' ) );
			exit;
		} else {
			unset( $datafile['OptionsFramework-backup-validator'] );
		}

		$optionsframework_data = get_option('optionsframework');
		$optionsframework_name = $optionsframework_data['id'];

		if ( update_option( $optionsframework_name, $datafile ) ) {
			wp_redirect( admin_url( 'admin.php?page=' . $this->token . '&imported=true' ) );
			exit;
		} else {
			var_dump($optionsframework_name);
			wp_redirect( admin_url( 'admin.php?page=' . $this->token . '&error=true' ) );
			exit;
		}
	}

	function export() {
		global $wpdb;
		check_admin_referer( 'OptionsFramework-backup-export' );

		$optionsframework_settings = get_option('optionsframework');
		$database_options = get_option( $optionsframework_settings['id'] );

		if ( $database_options == '' ) {
			wp_redirect( admin_url( 'admin.php?page=' . $this->token . '&error-export=true' ) );
			return;
		}

		if ( ! $database_options ) { return; }
		$database_options['OptionsFramework-backup-validator'] = date( 'Y-m-d h:i:s' );
		$output = json_encode( (array)$database_options );
		header( 'Content-Description: File Transfer' );
		header( 'Cache-Control: public, must-revalidate' );
		header( 'Pragma: hack' );
		header( 'Content-Type: text/plain' );
		header( 'Content-Disposition: attachment; filename="' . $this->token . '-' . date( 'Ymd-His' ) . '.json"' );
		header( 'Content-Length: ' . strlen( $output ) );
		echo $output;
		exit;
	}
}

function backup_options( $options ){
	$options[] = array(
		'name' => '备份选项',
		'type' => 'heading'
	);

	$options[] = array(
		'desc' => '
		<p>' . sprintf(__( '用于备份恢复主题选项设置', 'begin' )) . '</p>
		<a style="color: #fff; display: table; float: left; margin-top: 20px; padding: 3px 15px;" class="button-primary" href="'.admin_url('themes.php?page=begin-options-backup').'" >' . sprintf(__( '备份恢复设置', 'begin' )) . '</a>', 
		'type' => 'info'
	);
	return $options;
}
add_filter( 'of_options', 'backup_options', 9999 );