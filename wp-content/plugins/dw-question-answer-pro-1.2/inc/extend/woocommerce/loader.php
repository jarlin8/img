<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'DWQA_Woocommerce' ) ) :

class DWQA_Woocommerce{

	public function __construct() {

		add_action('dwqa_register_middle_setting_field', array($this, 'addSetting'));

		global $dwqa_general_settings;
		if(isset($dwqa_general_settings['use-user-expiration-woo']) && $dwqa_general_settings['use-user-expiration-woo']){
			add_action( 'woocommerce_product_options_general_product_data', array($this, 'createCustomField' ));
			add_action( 'woocommerce_process_product_meta', array($this, 'saveCustomField' ));

			//display on frontend
			add_action( 'woocommerce_before_add_to_cart_button', array($this, 'displayCustomField' ));
			add_action( 'woocommerce_before_checkout_form', array($this, 'showNeedLogin' ), 10, 1 );

			//check on process
			add_action('woocommerce_checkout_process', array($this, 'checkoutProcess'), 10);
			add_action('woocommerce_checkout_update_order_meta', array($this, 'updateOrderInfo'), 10, 2);

			//display item in order
			add_filter('woocommerce_order_item_display_meta_key', array($this, 'orderItemDisplayMetaKey'));

			add_action( 'woocommerce_order_status_completed', array($this, 'orderStatusCompleted') );
		}
		//
	}

	public function addSetting(){
		add_settings_field(
			'dwqa_options[use-user-expiration-woo]',
			__( 'Intergration with Woocommerce', 'dwqa' ),
			array($this, 'useUserExpirationWoo'),
			'dwqa-settings',
			'dwqa-user-expiration-settings'
		);
	}
	public function useUserExpirationWoo(){
		global $dwqa_general_settings;
		
		echo '<p><label for="dwqa_use_user_expiration_woo"><input type="checkbox" name="dwqa_options[use-user-expiration-woo]"  id="dwqa_use_user_expiration_woo" value="1" '.checked( 1, (isset($dwqa_general_settings['use-user-expiration-woo'] ) ? $dwqa_general_settings['use-user-expiration-woo'] : false ) , false ) .'><span class="description">'.__( 'Enable integration with Woocommerce (enable user expiration required)', 'dwqa' ).'</span></label></p>';
	}

	public function orderStatusCompleted($order_id){
		$order = wc_get_order( $order_id );
		if ( ! $order ) {
			return;
		}

		$user_id = $order->get_user_id();
		if(!$user_id){
			return;
		}

		$check = false;

		//get current time
		$old_dwqa_expiration = get_user_meta( $user_id, 'dwqa_expiration', true );
		//if not exist expiration time => get current time
		$old_dwqa_expiration = $old_dwqa_expiration?$old_dwqa_expiration:current_time('timestamp');

		$new_date = date("Y-m-d H:i:s", $old_dwqa_expiration);

		$items = $order->get_items();
		foreach($items as $item){
			$item_id = $item->get_id();
			$item_quantity = $item->get_quantity();
			$dwqa_woo_type = wc_get_order_item_meta($item_id, 'dwqa_woo_type', true);


			if($dwqa_woo_type && $dwqa_woo_type == 'time'){
				$check = true;

				$dwqa_woo_time_unit = wc_get_order_item_meta($item_id, 'dwqa_woo_time_unit', true);
				$dwqa_woo_time_number = wc_get_order_item_meta($item_id, 'dwqa_woo_time_number', true);

				switch ($dwqa_woo_time_unit) {
					case 'day':
						$new_date = date("Y-m-d H:i:s", strtotime($new_date . ' +' . (int)($dwqa_woo_time_number * $item_quantity) . ' days'));
						break;
					case 'month':
						$new_date = date("Y-m-d H:i:s", strtotime($new_date . ' +' . (int)($dwqa_woo_time_number * $item_quantity) . ' months'));
						break;
					case 'month':
						$new_date = date("Y-m-d H:i:s", strtotime($new_date . ' +' . (int)($dwqa_woo_time_number * $item_quantity) . ' year'));
						break;
					
					default:
						
						break;
				}
			}
			
		}

		if($check){
			update_user_meta( $user_id, 'dwqa_expiration', strtotime($new_date) );
		}
	}

	public function orderItemDisplayMetaKey($display_key){
		if($display_key == 'dwqa_woo_time_unit'){
			return __('DWQA Unit', 'dwqa');
		}
		if($display_key == 'dwqa_woo_time_number'){
			return __('DWQA Number', 'dwqa');
		}
		if($display_key == 'dwqa_woo_type'){
			return __('DWQA Type', 'dwqa');
		}
		return $display_key;
	}

	public function updateOrderInfo($order_id, $data){
		$order = wc_get_order( $order_id );
		if ( ! $order ) {
			return;
		}

		$items = $order->get_items();
		foreach($items as $item){
			$item_id = $item->get_id();
			$product_id = $item->get_product_id();
			$dwqa_woo_enable = get_post_meta($product_id, 'dwqa_woo_enable', true);

			if($dwqa_woo_enable){
				$dwqa_woo_type = get_post_meta($product_id, 'dwqa_woo_type', true);

				
				if($dwqa_woo_type == 'time'){
					$dwqa_woo_time_unit = get_post_meta($product_id, 'dwqa_woo_time_unit', true);
					$dwqa_woo_time_number = get_post_meta($product_id, 'dwqa_woo_time_number', true);

					if($dwqa_woo_time_unit && $dwqa_woo_time_number){
						wc_update_order_item_meta( $item_id, 'dwqa_woo_type', $dwqa_woo_type );
						wc_update_order_item_meta( $item_id, 'dwqa_woo_time_unit', $dwqa_woo_time_unit );
						wc_update_order_item_meta( $item_id, 'dwqa_woo_time_number', $dwqa_woo_time_number );
					}
				}
			}
			
		}
	}

	public function checkoutProcess(){
        if(!is_user_logged_in()){

		    if($this->isDWQAInCart()){
		    	$error_notice = apply_filters( 'dwqa_woo_checkout_process_login_message', __( 'Have DWQA product in cart. Need to login!', 'dwqa' ) );
		 		wc_add_notice($error_notice, 'error');
		    }
		}
	}

	public function showNeedLogin($checkout){

		if(!is_user_logged_in()){

		    if($this->isDWQAInCart()){
		    	//check if woo setting have login form
			    if('no' === get_option( 'woocommerce_enable_checkout_login_reminder' )){
			    	//no setting => show login form
			    	?>
					<div class="woocommerce-form-login-toggle dwqa-woo-form-login-toggle">
						<?php wc_print_notice( apply_filters( 'dwqa_woo_checkout_login_message', __( 'Have DWQA product in cart. Need to login!', 'dwqa' ) ) . ' <a href="#" class="showlogin">' . __( 'Click here to login', 'dwqa' ) . '</a>', 'error' ); ?>
					</div>
					<?php

					woocommerce_login_form(
						array(
							'message'  => __( 'Please login to checkout DWQA product', 'woocommerce' ),
							'redirect' => wc_get_page_permalink( 'checkout' ),
							'hidden'   => true,
						)
					);
			    }else{
			    	wc_print_notice( apply_filters( 'dwqa_woo_checkout_login_message', __( 'Have DWQA product in cart. Need to login!', 'dwqa' ) ), 'error' );
			    }
		    }
		}
	}

	private function isDWQAInCart(){
		$cart = WC()->cart->get_cart();
		if(!empty($cart)){
			foreach ( $cart as $cart_item ) {
	   		 	$product = $cart_item['data'];
		 	 	if(!empty($product)){

		 	 		//check have dwqa in cart
		 	 		if($product->get_meta( 'dwqa_woo_enable' )){
		 	 			return true;
		 	 		}
		 	 	}
		    }
	    }
	    return false;
	}

	public function displayCustomField() {
		global $post;
 		// Check for the custom field value
		$product = wc_get_product( $post->ID );

		
		$dwqa_woo_enable = $product->get_meta( 'dwqa_woo_enable' );
		if($dwqa_woo_enable){
			$dwqa_woo_type = $product->get_meta( 'dwqa_woo_type' );

			if($dwqa_woo_type == 'time'){
				$dwqa_woo_time_unit = $product->get_meta( 'dwqa_woo_time_unit' );
				$dwqa_woo_time_number = $product->get_meta( 'dwqa_woo_time_number' );

				if($dwqa_woo_time_unit && $dwqa_woo_time_number){
					printf(
						'<div class="dwqa-woo-wrapper">
							<label for="cfwc-title-field">%s %s</label>
						</div>',
						esc_html( $dwqa_woo_time_number ), esc_html( $dwqa_woo_time_unit )
					);
				}
			}
		}
		

	}

	public function saveCustomField( $post_id ) {
		$product = wc_get_product( $post_id );

		if(isset($_POST['dwqa_woo_enable'])){
			$product->update_meta_data( 'dwqa_woo_enable', sanitize_text_field( $_POST['dwqa_woo_enable'] ) );
		}

		if(isset($_POST['dwqa_woo_type'])){
			$product->update_meta_data( 'dwqa_woo_type', sanitize_text_field( $_POST['dwqa_woo_type'] ) );
		}

		if(isset($_POST['dwqa_woo_time_unit'])){
			$product->update_meta_data( 'dwqa_woo_time_unit', sanitize_text_field( $_POST['dwqa_woo_time_unit'] ) );
		}
		
		if(isset($_POST['dwqa_woo_time_number'])){
			$product->update_meta_data( 'dwqa_woo_time_number', sanitize_text_field( $_POST['dwqa_woo_time_number'] ) );
		}
		
		$product->save();
	}

	public function createCustomField() {

		echo '<div>';
		echo '<p><strong>DWQA</strong></p>';

		$args_enable = array(
			'id' => 'dwqa_woo_enable',
			'label' => __( 'Enable', 'dwqa' ),
			'class' => 'dwqa-woo-enable',
			'description' => __( 'Enable DWQA in Product.', 'dwqa' ),
			'cbvalue' => 1,
		);
		woocommerce_wp_checkbox($args_enable);
		

		$args_number = array(
			'id' => 'dwqa_woo_time_number',
			'label' => __( 'Number', 'dwqa' ),
			'class' => 'dwqa-woo-time-field',
			'desc_tip' => true,
			'description' => __( 'Number of time.', 'dwqa' ),
			'data_type' => 'decimal'
		);
		woocommerce_wp_text_input($args_number);

		$args_time_unit = array(
			'id' => 'dwqa_woo_time_unit',
			'label' => __( 'Time Unit', 'dwqa' ),
			'class' => 'dwqa-woo-time-unit',
			'desc_tip' => true,
			'description' => __( 'Unit of time.', 'dwqa' ),
			'options' => array( 'day' => 'day', 'month' => 'month', 'year' => 'year' )
		);

		woocommerce_wp_select( $args_time_unit );

		$args_type = array(
			'id' => 'dwqa_woo_type',
			'value' => 'time'
		);
		woocommerce_wp_hidden_input($args_type);
		echo '</div>';
	}



	//

	public function registerMetaBoxes() {
	    add_meta_box(
	    	'meta-box-dwqa-woo-role',
	    	__( 'DWQA', 'dwqa' ),
	    	array($this, 'displayRole'),
	    	'product'
	    );
	}
	public function displayRole($post){
		global $dwqa;
		$perms = $dwqa->permission->perms;
		$roles = get_editable_roles();
		
		$post_id = $post->ID;
		$role_selected = get_post_meta($post_id, 'dwqa_role', true);
	?>
	<table class="table widefat">
		<thead>
			<tr>
				<th width="20%">Role</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ( $roles as $key => $role ) : ?>
			<?php if ( $key == 'anonymous' || $key == 'administrator') continue; ?>
			<tr class="group available">
				<td><?php echo $roles[$key]['name'] ?></td>
				<td><input type="radio" name="dwqa_role" value="<?php echo $key;?>" <?php echo ($role_selected && $role_selected==$key?'checked':'')?>></td>
			   
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<p class="reset-button-container align-right" style="text-align:right">
		<button data-type="question" class="button reset-permission" name="dwqa-permission-reset" value="question"><?php _e( 'Reset Default', 'dwqa' ); ?></button>
	</p>
	<?php
	}
}
endif;
