<?php
if ( ! defined( 'ABSPATH' ) ) exit;
// 创建数据表
function be_invitation_code_install(){
	global $wpdb;
	$table_name = $wpdb->prefix . 'be_invitation';
	$charset_collate = $wpdb->get_charset_collate();

	if ( $wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name ) :
    $sql = " CREATE TABLE `".$wpdb->prefix."be_invitation` (
      `id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
      `code` varchar(40) NOT NULL,
      `max` INT NOT NULL,
      `users` varchar(255),
      `expiration` datetime,
      `status` varchar(20),
      UNIQUE (code)
      ) $charset_collate;";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
	endif;
}

add_action( 'after_switch_theme', 'be_invitation_code_install' );

if ( is_admin() && zm_get_option( 'code_data' ) ) {
	add_action( 'init', 'be_invitation_code_install' );
}

// 函数
// insert a invite code.
function be_insert_invitation_code( $code, $max = 1, $users = '', $expiration = '', $status = 'available' ){
	global $wpdb;

	if ($code==''){
		return false;
	}
	$code = trim($code);

	if (!in_array($status,array('available','disabled','finish','expired'))){
		$status = 'available';
	}

	$table_name = $wpdb->prefix . 'be_invitation';
	$sql = "insert ignore into $table_name ( code, max, users, expiration, status ) values( '$code', '$max', '', '$expiration', '$status' )";

	$result = $wpdb->query($sql);

	if ($result){
		return true;
	}else{
		return false;
	}
}

// update a invite code.
function be_update_invitation_code( $id, $key, $value ){
	global $wpdb;

	if ($id==''){
		return false;
	}

	$table_name = $wpdb->prefix . 'be_invitation';
	$sql = "update $table_name set $key='$value' where id='$id'";
	$result = $wpdb->query($sql);

	if ($result){
		return true;
	}else{
		return false;
	}
}

// update a invite code status.
function be_update_invitation_status( $id ){
	global $wpdb;

	if ($id==''){
		return false;
	}

	$table_name = $wpdb->prefix . 'be_invitation';
	$sql = "select * from $table_name where id='$id'";

	$code = $wpdb->get_row($sql,'ARRAY_A');
	if (!empty($code)){
		$users = array();
		if (!empty($code['users'])){
			$users = be_code_users_string_to_array($code['users']);
		}

		$used = count($users);

		if ( ($code['max']<=$used) && ($code['status']=='available') ){
			$code['status'] = 'finish';
			be_update_invitation_code( $code['id'], 'status', 'finish' );
		}

		$expiration = '';
		if ( !empty( $code['expiration'] ) && $code['expiration']!='0000-00-00 00:00:00' ){
			$expiration = date_i18n( get_option( 'date_format' ).' '.get_option( 'time_format' ), strtotime($code['expiration']) );

			$now = time() + ( get_option( 'gmt_offset' ) * 3600 );

			if ( ($now >= strtotime($code['expiration'])) && ($code['status'] == 'available') ){
				$code['status'] = 'expired';
				be_update_invitation_code( $code['id'], 'status', 'expired' );
			}
		}
		return true;
	}else{
		return false;
	}
}

// operation a invite code.
function be_operation_invitation_code( $id, $action ){
	global $wpdb;
	$id = (int)$id;
	if (!$id){
		return false;
	}
	if (!in_array($action,array('delete','deactive','active'))){
		return false;
	}
	if ($action =='delete'){
		$result = be_delete_invitation_code($id);
	}
	if ($action =='deactive'){
		$result = be_update_invitation_code( $id, 'status', 'disabled' );
	}
	if ($action =='active'){
		$result = be_update_invitation_code( $id, 'status', 'available' );
	}
	if ($result){
		return true;
	} else{
		return false;
	}
}


// delete a invite code.
function be_delete_invitation_code( $id ){
	global $wpdb;
	if ($id==''){
		return false;
	}
	$table_name = $wpdb->prefix . 'be_invitation';
	$sql = "delete from $table_name where id='$id'";
	$result = $wpdb->query($sql);
	if ($result){
		return true;
	} else{
		return false;
	}
}


// Check invitation code
function be_check_invitation_code( $code ){
	global $wpdb;
	$table_name = $wpdb->prefix . 'be_invitation';
	$sql = "select * from $table_name where code='$code'";
	$result = $wpdb->get_row($sql,'ARRAY_A');
	if (!empty($result)){
		if ( in_array( $result['status'], array('available','disabled','finish','expired') ) ){
			return $result['status'];
		} else{
			return false;
		}
	} else{
		return false;
	}
}

// get invitation
function be_get_invitation_codes( $args ){
	global $wpdb;
	$defaults = array(
		'paged'    => 1,
		'per_page' => 100,
		'status'   => '',
		's'        => ''
	);
	$args = wp_parse_args( $args, $defaults );
	$page = (int)$args['paged'];
	if (!$page){
		$page = 1;
	}
	$per_page = (int)$args['per_page'];
	if (!$per_page){
		$per_page = 50;
	}
	$begin = $per_page*($page-1);
	$end = $per_page*$page;
	$sql_where = '';
	if ( in_array( $args['status'], array('available','disabled','finish','expired')) ){
		$sql_where = " where status='{$args['status']}'";
	}
	if ( $args['s'] !='' ){
		if ($sql_where!=''){
			$sql_where .= " and code like '%{$args['s']}%'";
		} else{
			$sql_where .= " where code like '%{$args['s']}%'";
		}
	}
	$table_name = $wpdb->prefix . 'be_invitation';
	$sql = "select * from $table_name $sql_where order by ID desc limit $begin,$end";
	$results = $wpdb->get_results($sql,'ARRAY_A');
	return $results;
}

// Count invitation code
function be_count_invitation_code( $args = array() ){
	global $wpdb;
	$defaults = array(
		'status'   => '',
		's'        => ''
	  );

	$args = wp_parse_args( $args, $defaults );
	$sql_where = '';
	if ( in_array( $args['status'], array('available','disabled','finish','expired')) ){
		$sql_where = " where status='{$args['status']}'";
	}
	if ( $args['s'] !='' ){
		if ($sql_where!=''){
			$sql_where .= " and code like '%{$args['s']}%'";
		} else{
			$sql_where .= " where code like '%{$args['s']}%'";
		}
	}
	$table_name = $wpdb->prefix . 'be_invitation';
	$sql = "select count(*) from $table_name $sql_where";
	$results = $wpdb->get_var($sql);
	return $results;
}

function be_get_invitation_code_by_code($code){
	global $wpdb;
	$table_name = $wpdb->prefix . 'be_invitation';
	$sql = "select * from $table_name where code='$code'";
	$result = $wpdb->get_row($sql,'ARRAY_A');
	return $result;
}

function be_code_users_string_to_array( $str ){
	if (is_string($str)){
		$arr = explode( ',', $str );
		$arr = array_filter($arr);
		return $arr;
	} else{
		return $str;
	}
}

function be_code_users_array_to_string( $arr ){
	if (is_array($arr)){
		$arr = array_filter($arr);
		$str = implode($arr);
		return $str;
	} else{
		return $arr;
	}
}

// 设置
if (!class_exists('WP_List_Table')) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
class Be_Invitation_Code_List_Table extends WP_List_Table {
	function __construct(){
		parent::__construct( array(
			'singular'  => '邀请码',
			'plural'    => '邀请码',
			'ajax'      => false
		) );
	}
	function column_default( $item, $column_name ) {
		switch ( $column_name ){
			case 'code':
			case 'counter':
			case 'users':
			case 'expiration':
			case 'status':
			case 'actions':
			return $item[ $column_name ];
			default:
			return print_r($item,true);
		}
	}

	function column_cb( $item ) {
		return sprintf( '<input type="checkbox" name="%1$s[]" value="%2$s" />', 'invitationcode', $item['id'] );
	}

	function get_columns() {
		$columns = array(
			'cb'         => '<input type="checkbox" />',
			'code'       => '邀请码',
			'counter'    => '次数/已用',
			'users'      => '用户',
			'expiration' => '过期时间',
			'status'     => '状态',
			'actions'    => '操作'
		);
		return $columns;
	}

	function get_bulk_actions() {
		$actions = array(
			'active'   => '启用',
			'deactive' =>  '禁用',
			'delete'   => '删除'
		);
		return $actions;
	}

	function format_datas( $codes ) {
		$datas = array();
		foreach( $codes as $code ){
			$item_array = array();
			$users = array();
			if (!empty($code['users'])){
				$users = be_code_users_string_to_array($code['users']);
			}
			$used = count($users);
			if ( ($code['max']<=$used) && ($code['status']=='available') ){
				$code['status'] = 'finish';
				be_update_invitation_code( $code['id'], 'status', 'finish' );
			}
			$user_output = array();
			foreach( $users as $user_id ){
				$user = get_user_by('id', $user_id);
				if (!empty($user)){
					$user_output[] = '<a href="'.network_admin_url( 'user-edit.php?user_id='.$user->ID ).'">'.$user->user_login .'</a>';
				}
			}
			$expiration = '';
			if ( !empty( $code['expiration'] ) && $code['expiration']!='0000-00-00 00:00:00' ){
				$expiration = date_i18n( get_option( 'date_format' ).' '.get_option( 'time_format' ), strtotime($code['expiration']) );
				$now = time() + ( get_option( 'gmt_offset' ) * 3600 );
				if ( ($now >= strtotime($code['expiration'])) && ($code['status'] == 'available') ){
					$code['status'] = 'expired';
					be_update_invitation_code( $code['id'], 'status', 'expired' );
				}
			}
			$status = '';
			switch($code['status']){
				case 'available':
					$status = '<span class="available">可用</span>';
				break;
				case 'disabled':
					$status = '<span class="disabled">已禁用</span>';
				break;
				case 'finish':
					$status = '<span class="finish">用完</span>';
				break;
				case 'expired':
					$status = '<span class="expired">过期</span>';
				break;
				default:
					$status = '';
			}
			$actions = '<a href="'. wp_nonce_url( network_admin_url( 'admin.php?page=be_invitation_code&action=delete&invitationcode[0]='.$code['id'] ), 'invitationcode_operate' ).'">删除</a>';
			if ( $code['status'] == 'disabled' ){
				$actions .= ' | <a href="'.wp_nonce_url( network_admin_url( 'admin.php?page=be_invitation_code&action=active&invitationcode[0]='.$code['id'] ), 'invitationcode_operate' ).'">启用</a>';
			}
			if ( $code['status'] == 'available'){
				$actions .= ' | <a href="'.wp_nonce_url( network_admin_url( 'admin.php?page=be_invitation_code&action=deactive&invitationcode[0]='.$code['id'] ), 'invitationcode_operate' ).'">禁用</a>';
			}
			$item_array['id'] = $code['id'];
			$item_array['code'] = $code['code'];
			$item_array['counter'] = $code['max'].'/'.$used;
			$item_array['users'] = implode( ',', $user_output );
			$item_array['expiration'] = $expiration;
			$item_array['status'] = $status;
			$item_array['actions'] = $actions;
			$datas[] = $item_array;
		}
		return $datas;
	}
	function prepare_items() {
		$this->_column_headers = $this->get_column_info();
		$this->process_bulk_action();
		$per_page     = $this->get_items_per_page( 'customers_per_page', 30 );
		$current_page = $this->get_pagenum();
		$total_items  = 0;
		$args = array(
			'per_page' => $per_page,
			'paged' => $current_page,
			);
		if ( !empty( $_GET['s'] ) ){
			$words = trim($_GET['s']);
			if ($words != ''){
				$args['s'] = strtoupper( trim($_GET['s']) );
			}
		}
		if ( !empty( $_GET['status'] ) && in_array( trim($_GET['status']), array('available','disabled','finish','expired') ) ){
			$args['status'] = trim($_GET['status']);
		}
		$total_items  = be_count_invitation_code($args);
		$datas = be_get_invitation_codes($args);
		$this->items = $this->format_datas($datas);
		$this->set_pagination_args( array(
			'total_items' => $total_items,
			'per_page'    => $per_page,
			'total_pages' => ceil($total_items/$per_page)
		) );
	}
}
class be_invitation_code_admin {
	static public $instance;
	public $invitation_code_obj;
	private function __construct(){
		add_filter( 'set-screen-option', array( $this, 'set_screen' ), 10, 3 );
		add_action( is_multisite() ? 'network_admin_menu' : 'admin_menu', array( $this, 'be_invitation_code_menu') );
		//add_action( 'admin_menu', array( $this, 'be_invitation_code_menu') );
	}
	private function __clone() {
	}
	function be_invitation_code_menu() {
		$hook = add_menu_page('邀请码','邀请码', 'manage_options', 'be_invitation_code', array(&$this, 'be_invitation_code_list'), 'dashicons-id', 58);
		add_submenu_page('be_invitation_code', '添加', '添加', 'manage_options', 'be_invitation_code_add', array(&$this, 'be_invitation_code_add'));
		add_submenu_page('be_invitation_code', '选项', '选项', 'manage_options', 'be_invitation_code_options', array(&$this, 'be_invitation_code_options'));
		add_action( "load-$hook", array( $this, 'be_invitation_code_update' ) );
		add_action( "load-$hook", array( $this, 'screen_option' ) );
	}
	function set_screen( $status, $option, $value ) {
		return $value;
	}
	function screen_option() {
		$option = 'per_page';
		$args   = array(
			'label'   => '每页邀请码数',
			'default' => 30,
			'option'  => 'customers_per_page'
		);
		add_screen_option( $option, $args );
		$this->be_invitation_code_obj = new Be_Invitation_Code_List_Table();
	}
	function be_invitation_code_update() {
		if ( ( isset( $_GET['action'] ) && in_array($_GET['action'],array('active', 'deactive', 'delete') ) ) || ( isset( $_GET['action2'] ) && in_array($_GET['action2'],array('active', 'deactive', 'delete') ) ) ) {
			if ( isset( $_GET['action'] ) && in_array($_GET['action'],array('active', 'deactive', 'delete') ) ){
				$action = $_GET['action'];
			}
			if ( isset( $_GET['action2'] ) && in_array($_GET['action2'],array('active', 'deactive', 'delete') ) ){
				$action = $_GET['action2'];
			}
			$success = array();
			$failed = array();
			$code_ids = esc_sql( $_GET['invitationcode'] );
			foreach ( $code_ids as $id ) {
				$re = be_operation_invitation_code( $id, $action );
				if ($re){
					$success[] = $id;
				} else{
					$failed[] = $id;
				}
			}
			$query = array( 'page'=>'be_invitation_code' );
			$query['paged'] = get_query_var( 'paged', 1 );
			if ( !empty($success) ){
				$query['status'] = 'success';
				$query['success'] = implode( ',', $success );
			}
			if ( !empty($failed) ){
				$query['status'] = 'failed';
				$query['failed'] = implode( ',', $failed );
			}
			$redirect_to = add_query_arg( $query, network_admin_url('admin.php') );
			wp_safe_redirect( $redirect_to );
			exit();
		}
	}
	function be_invitation_code_list(){
		$all = be_count_invitation_code();
		$available = be_count_invitation_code( array( 'status'=>'available' ) );
		$disabled = be_count_invitation_code( array( 'status'=>'disabled' ) );
		$finish = be_count_invitation_code( array( 'status'=>'finish' ) );
		$expired = be_count_invitation_code( array( 'status'=>'expired' ) );
		?>
		<div class="wrap">
			<h1 class="wp-heading-inline">邀请码</h1>
			<a href="<?php echo network_admin_url( 'admin.php?page=be_invitation_code_add' ); ?>" class="page-title-action">添加</a>
			<?php
				if ( ! empty( $_GET['s'] ) ) {
					printf( '<span class="subtitle">搜索&#8220;%s&#8221;结果</span>', esc_html( $_GET['s'] ) );
				}
			?>
			<hr class="wp-header-end">
			<?php
				if ( isset($_GET['status']) && trim($_GET['status'])!='' ){
					if ( trim($_GET['status'])=='success' ){
				?>
				<div id="message" class="notice notice-success">操作成功</div>
				 <?php
					} elseif (trim($_GET['status'])=='failed'){
						?>
						<div id="message" class="notice notice-error">操作失败</div>
						<?php
					}
				}
			?>

			<ul class="subsubsub">
				<?php
				if ( !empty( $_GET['status'] ) && in_array( trim($_GET['status']), array('available','disabled','finish','expired') ) ){
				  $now = trim($_GET['status']);
				}else{
				  $now = 'all';
				}
				$current = 'class="current"';
				?>
				<li class="all"><a <?php if ($now=='all'){ echo $current; } ?> href="<?php echo network_admin_url( 'admin.php?page=be_invitation_code' ); ?>">全部<span class="count"> ( <?php echo $all; ?> )</span></a> |</li>
				<li class="available"><a <?php if ($now=='available'){ echo $current; } ?> href="<?php echo network_admin_url( 'admin.php?page=be_invitation_code&status=available' ); ?>">可用<span class="count"> ( <?php echo $available; ?> )</span></a> |</li>
				<li class="disabled"><a <?php if ($now=='disabled'){ echo $current; } ?> href="<?php echo network_admin_url( 'admin.php?page=be_invitation_code&status=disabled' ); ?>">已禁用<span class="count"> ( <?php echo $disabled; ?> )</span></a> |</li>
				<li class="finish"><a <?php if ($now=='finish'){ echo $current; } ?> href="<?php echo network_admin_url( 'admin.php?page=be_invitation_code&status=finish' ); ?>">用完<span class="count"> ( <?php echo $finish; ?> )</span></a> |</li>
				<li class="expired"><a <?php if ($now=='expired'){ echo $current; } ?> href="<?php echo network_admin_url( 'admin.php?page=be_invitation_code&status=expired' ); ?>">过期<span class="count"> ( <?php echo $expired; ?> )</span></a></li>
			</ul>

			<form id="invitation-code-filter" method="get">
				<p class="search-box">
					<label class="screen-reader-text" for="code-search-input">搜索:</label>
					<input type="search" id="code-search-input" name="s" value="" />
					<?php submit_button( '搜索', 'button', false, false, array('id' => 'search-submit') ); ?>
				</p>

				<input type="hidden" name="page" value="be_invitation_code" />
				<?php
					$this->be_invitation_code_obj->prepare_items();
					$this->be_invitation_code_obj->display();
				?>
			</form>
			<p>可使用的邀请码</p>
			<textarea cols="30" rows="6"><?php be_invitation_codes_all(); ?></textarea>
		</div>
		<?php
	}

	function be_invitation_code_generate(){
		$code_tem = array();
		if (isset($_REQUEST['submit']) && isset($_REQUEST['be_invitation_code_field']) && check_admin_referer('be_invitation_code_action', 'be_invitation_code_field') ) {
			$code_prefix = '';
			if (!empty($_POST['code_prefix'])){
				$code_prefix = sanitize_text_field($_POST['code_prefix']);
			}
			$code_length = '';
			if (!empty($_POST['code_length'])){
				$code_length = (int)$_POST['code_length'];
			}
			if (!$code_length){
				$code_length = 8;
			}
			$code_number = 1;
			if (!empty($_POST['code_number'])){
				$code_number = (int)$_POST['code_number'];
			}
			if (!$code_number){
				$code_number = 1;
			}
			$code_counter = '';
			if (!empty($_POST['code_counter'])){
				$code_counter = (int)$_POST['code_counter'];
			}
			if (!$code_counter){
				$code_counter = 1;
			}
			$code_expiration = '';
			if (!empty($_POST['code_expiration'])){
				$code_expiration = strtotime(sanitize_text_field($_POST['code_expiration']));
				$code_expiration = date( "Y-m-d H:i:s", $code_expiration );
			}
			$i=1;
			while ( $i <= $code_number ){
				$tem = strtoupper( $code_prefix . wp_generate_password( $code_length, false ) );
				$re = be_insert_invitation_code( $tem, $code_counter, '', $code_expiration, 'available');
				if ($re){
					$i++;
					$code_tem[] = $tem;
				}
			}
		}
		return $code_tem;
	}

	function be_invitation_code_add(){
		$code_added = $this->be_invitation_code_generate();
	?>
	<div class="wrap invitation_code">
		<h1 class="wp-heading-inline">添加邀请码</h1>
		<a class="page-title-action" href="<?php echo network_admin_url( 'admin.php?page=be_invitation_code' ); ?>">全部邀请码</a>
		<hr class="wp-header-end">
		<?php
			if (!empty($code_added)){
		?>
		<div id="message" class="notice notice-success">
			<p>成功添加下列邀请码：</p>
			<?php
				echo '<p>';
				$i=0;
				foreach($code_added as $t){
					$i++;
					echo $t.'<br />';
					if ($i==50){
						echo '......';
						break;
					}
				}
				echo '</p>';
			?>
		</div>
		<?php
		}
		?>
		<form action="" method="post">
			<table class="form-table">
				<tbody>
					<tr>
						<th><label for="code_prefix">前缀</label></th>
						<td>
							<input type="text" id="code_prefix" size="20" name="code_prefix" value="" style="text-transform: uppercase;"/>
							<p class="description">可选，自定义前缀</p>
						</td>
					</tr>
					<tr>
						<th><label for="code_length">长度</label></th>
						<td>
							<input type="number" size="20" id="code_length" name="code_length" value="8" />
							<p class="description">前缀不计算在内</p>
						</td>
					</tr>
					<tr>
						<th><label for="code_number">数量</label></th>
						<td>
							<input type="number" size="20" id="code_number" name="code_number" value="1" />
							<p class="description">生成邀请码数量</p>
						</td>
					</tr>
					<tr>
						<th><label for="code_counter">次数</label></th>
						<td>
							<input type="number" size="20" id="code_counter" name="code_counter" value="1" />
							<p class="description">邀请码使用次数</p>
						</td>
					</tr>
					<tr>
						<th><label for="code_expiration">时间</label></th>
						<td>
							<input type="text" id="code_expiration" size="20" name="code_expiration" value="" style="text-transform: uppercase;"/>
							<p class="description">可选，过期时间，格式：YYYY-MM-DD H:i</p>
						</td>
					</tr>
				</tbody>
			</table>
			<p class="submit">
				<?php wp_nonce_field( 'be_invitation_code_action','be_invitation_code_field' ); ?>
				<input type="submit" name="submit" id="submit" class="button button-primary" value="添加邀请码">
			</p>
		</form>
	</div>
	<?php
	}

	function be_invitation_code_options_update(){
		if ( isset( $_POST['be_invitation_code_options_field'] ) && wp_verify_nonce( $_POST['be_invitation_code_options_field'], 'be_invitation_code_options_action' )  ) {

			$new_options = $old_options = get_option('be_invitation_code');
			if ( empty($new_options['get']) ){
				$new_options['show_get'] = '';
			}
			if ( empty($new_options['get_invite']) ){
				$new_options['get_invite'] = '';
			}

			if ( !empty($_POST['show_get']) ){
				$show_get = sanitize_text_field($_POST['show_get']);
				if ( $new_options['show_get'] != $show_get ){
					$new_options['show_get'] = $show_get;
				}
			}

			if ( !empty($_POST['get_invite']) ){
				$v = trim( strip_tags($_POST['get_invite'],'<a>') );
				$v = htmlspecialchars($v, ENT_COMPAT);

				if ( $new_options['get_invite'] != $v ){
					$new_options['get_invite'] = $v;
				}
			}else{
				$new_options['get_invite'] = '';
			}

			if ( $new_options != $old_options ){
				update_option('be_invitation_code', $new_options);
				$return = array(
					'status' => true,
					'msg' => '设置已保存',
				);
			}
		}

		if (isset($return)){
			return $return;
		}else{
			return NULL;
		}
	}

	function be_invitation_code_options(){
		$return = $this->be_invitation_code_options_update();

		$be_invitation_code_options = get_option('be_invitation_code');
		$show_get = 0;
		if ( !empty($be_invitation_code_options['show_get']) && $be_invitation_code_options['show_get']=='yes' ){
			$show_get = 1;
		}

		$get_invite = '';
		if ( !empty($be_invitation_code_options['get_invite']) ){
			$get_invite = stripslashes($be_invitation_code_options['get_invite']);
			$get_invite = htmlspecialchars_decode($get_invite, ENT_QUOTES);
		}
	?>

	<div class="wrap">
		<h1 class="wp-heading-inline">邀请码选项</h1>
		<a href="<?php echo network_admin_url( 'admin.php?page=be_invitation_code_add' ); ?>" class="page-title-action">添加</a>
		<a class="page-title-action" href="<?php echo network_admin_url( 'admin.php?page=be_invitation_code' ); ?>">全部邀请码</a>
		<hr class="wp-header-end">
			<?php
				if (!empty($return)){
					if ($return['status']){
						$c = 'notice-success';
					}else{
						$c = 'notice-error';
					}
			?>
			<div id="message" class="notice <?php echo $c; ?>">
				<p><?php echo $return['msg']; ?></p>
			</div>
		<?php
		}
		?>
		<form action="" method="post">
			<table class="form-table">
				<tbody>
					<tr>
						<th><label for="code_prefix">获取邀请码按钮</label></th>
						<td>
							<p>
								<label><input name="show_get" type="radio" value="yes" <?php if ($show_get){ echo 'checked="checked"'; } ?>>显示</label>
								<label><input name="show_get" type="radio" value="no" <?php if (!$show_get){ echo 'checked="checked"'; } ?> >不显示</label>
							</p>
						</td>
					</tr>
					<tr>
						<th><label for="code_length">获取邀请码链接</label></th>
						<td>
							<label><input type="text" size="60" name="get_invite" id="get_invite" value="<?php echo $get_invite; ?>" /></label>
						</td>
					</tr>
					<tr>
						<th><label for="code_length">前端显示邀请码</label></th>
						<td>新建页面 → 添加短代码 [be_reg_codes] 并发表</td>
					</tr>
				</tbody>
			</table>
			<p class="submit">
				<?php wp_nonce_field( 'be_invitation_code_options_action','be_invitation_code_options_field' ); ?>
				<input type="submit" name="submit" id="submit" class="button button-primary" value="更新设置">
			</p>
		</form>
	</div>
	<?php
	}

	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
		self::$instance = new self();
	}

		return self::$instance;
	}
}

be_invitation_code_admin::get_instance();

// 前端
add_action('register_form','be_invitation_code_form');
function be_invitation_code_form() { ?>
	<div class="invitation-box zml-ico">
	<input name="be_invitation_code" type="text" id="be_invitation_code" class="be_invitation_code input dah bk" style="text-transform: uppercase" required="required" placeholder="<?php _e( '邀请码', 'begin' ); ?>" onfocus="this.placeholder=''" onblur="this.placeholder='<?php _e( '邀请码', 'begin' ); ?>'" />

	<?php
		$be_invitation_code_options = get_option('be_invitation_code');
		if ( !empty($be_invitation_code_options['show_get']) && $be_invitation_code_options['show_get']=='yes' ){
			if ( !empty($be_invitation_code_options['get_invite']) ){
				$get_invite = stripslashes($be_invitation_code_options['get_invite']);
				$get_invite = htmlspecialchars_decode($get_invite, ENT_QUOTES);
				echo '<div class="to-code dah">';
				echo '<a href="'.$get_invite.'" target="_blank">'.sprintf(__( '获取', 'begin' )).'</a>';
				echo '</div>';
			}
		}
	echo '</div>';
}

add_filter( 'registration_errors', 'be_invitation_code_errors', 10, 3 );
function be_invitation_code_errors( $errors, $sanitized_user_login, $user_email ) {

	$be_invitation_code = '';

	if ( empty( $_POST['be_invitation_code'] ) || ! empty( $_POST['be_invitation_code'] ) && sanitize_text_field( $_POST['be_invitation_code'] ) == '' ) {
		$errors->add( 'be_invitation_code_error', __( '请输入邀请码', 'begin' ) );
		return $errors;
	}else{
		$be_invitation_code = sanitize_text_field( $_POST['be_invitation_code'] );
	}

	$status = be_check_invitation_code($be_invitation_code);

	if (!$status){
		$errors->add( 'be_invitation_code_error', __( '错误的邀请码', 'begin' ) );
		return $errors;
	}

	if ($status=='disabled'){
		$errors->add( 'be_invitation_code_error', __( '邀请码不可用', 'begin' ) );
		return $errors;
	}

	if ($status=='finish'){
		$errors->add( 'be_invitation_code_error', __( '邀请码已超使用次数', 'begin' ) );
		return $errors;
	}

	if ($status=='expired'){
		$errors->add( 'be_invitation_code_error', __( '邀请码已经过期', 'begin' ) );
		return $errors;
	}

	return $errors;
}

add_action( 'user_register', 'be_register_invitation_code' );
function be_register_invitation_code( $user_id ){
	if ( !empty( $_POST['be_invitation_code'] ) && sanitize_text_field( $_POST['be_invitation_code'] ) != '' ) {
		$code = sanitize_text_field( $_POST['be_invitation_code'] );
		$result = be_get_invitation_code_by_code($code);

		if (!empty($result)){
			$code_users = array();
			$code_id = $result['id'];
			$code_users = explode( ',', $result['users'] );
			$code_users[] = $user_id;
			$code_users = array_filter($code_users);
			$new_users = implode(',',$code_users);

			be_update_invitation_code( $code_id, 'users', $new_users );
			be_update_invitation_status( $code_id );
			add_user_meta( $user_id, 'be_invitation_code', $result['code'], true );
		}
	}
}

// 全部
function be_invitation_codes_all() {
	$args = array(
		'status'=>'available',
		'per_page' => '10000',
	);

	$be_invitation_codes = be_get_invitation_codes( $args );
	foreach ( $be_invitation_codes as $code ) {
		echo "\n", $code['code'];
	}
}

// 邀请码
function be_invite_list() { ?>
<div class="codes-list-box">
	<div class="codes-list">
		<?php
			$args = array(
				'status'=>'available',
				'per_page' => '10000',
			);
			$be_invitation_codes = be_get_invitation_codes( $args );
			foreach ( $be_invitation_codes as $code ) { ?>
				<div class="bk"><?php echo $code['code']; ?></div>
			<?php }
		?>
	</div>
</div>
<?php }

add_shortcode('be_reg_codes', 'be_invite_list');