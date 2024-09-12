<?php
if ( ! defined( 'ABSPATH' ) ) exit;
// menu field
function be_menu_custom_fields( $item_id, $item ) {
	$menu_tree = get_post_meta( $item_id, 'be_menu_tree_checkbox', true );
	$menu_max = get_post_meta( $item_id, 'be_menu_max_checkbox', true );
	$menu_code = get_post_meta( $item_id, 'be_menu_code_checkbox', true );
	$menu_cover = get_post_meta( $item_id, 'be_menu_cover_checkbox', true );
	$menu_explain = get_post_meta( $item_id, 'be_menu_custom_explain', true );
	$menu_img = get_post_meta( $item_id, 'be_menu_custom_img', true );
	$menu_shortcode = get_post_meta( $item_id, 'be_menu_shortcode', true );
	?>

	<p class="be-menu-custom-clear" style="display: block;line-height: 0;">
		<strong>&nbsp;</strong>
	</p>

	<p class="be-menu-custom-label description be-menu-depth be-menu-depth-parent" style="margin: 10px 0 10px;">
		<label for="be-menu-custom-mode-<?php echo esc_attr($item_id); ?>">
			<span class="be-ico-admin">超级菜单样式&nbsp;</span>
			<?php $saved_be_megamenu_mode = esc_attr( $item->be_megamenu_mode ); ?>
			<select id="be-menu-custom-mode-<?php echo esc_attr($item_id); ?>" class="be-edit-menu-item-custom" name="be_megamenu_item_mode[<?php echo esc_attr( $item_id ); ?>]">
				<option value="mega-menu" <?php if ( $saved_be_megamenu_mode == "mega-menu" ){echo( "selected" );} ?>>无</option>
				<option value="be-menu-tree" <?php if ( $saved_be_megamenu_mode == "be-menu-tree" ){echo( "selected" );} ?>>竖向菜单</option>
				<option value="be-menu-max" <?php if ( $saved_be_megamenu_mode == "be-menu-max" ){echo( "selected" );} ?>>横向菜单</option>
				<option value="be-menu-columns be-menu-two-list" <?php if ( $saved_be_megamenu_mode == "be-menu-columns be-menu-two-list" ){echo( "selected" );} ?>>两列菜单</option>
				<option value="be-menu-columns be-menu-multi-list" <?php if ( $saved_be_megamenu_mode == "be-menu-columns be-menu-multi-list" ){echo( "selected" );} ?>>多列菜单</option>
				<option value="be-menu-columns be-menu-multi-list be-menu-multi-ico" <?php if ( $saved_be_megamenu_mode == "be-menu-columns be-menu-multi-list be-menu-multi-ico" ){echo( "selected" );} ?>>多列图标</option>
				<option value="be-menu-code-cat" <?php if ( $saved_be_megamenu_mode == "be-menu-code-cat" ){echo( "selected" );} ?>>短代码分类</option>
				<option value="be-menu-code-cover be-menu-max menu-cover" <?php if ( $saved_be_megamenu_mode == "be-menu-code-cover be-menu-max menu-cover" ){echo( "selected" );} ?>>短代码封面</option>
				<option value="be-menu-max menu-mix" <?php if ( $saved_be_megamenu_mode == "be-menu-max menu-mix" ){echo( "selected" );} ?>>短代码混排</option>
				<option value="be-menu-max menu-list" <?php if ( $saved_be_megamenu_mode == "be-menu-max menu-list" ){echo( "selected" );} ?>>分类列表</option>
			</select>
		</label>
	</p>

	<p class="be-menu-custom-label description be-menu-depth be-menu-depth-parent" style="margin: 10px 0 10px;">
		<label for="be-menu-custom-col-<?php echo esc_attr($item_id); ?>">
			<span class="be-ico-admin">横向菜单分栏&nbsp;</span>
			<?php $saved_be_megamenu_col = esc_attr( $item->be_megamenu_col ); ?>
			<select id="be-menu-custom-col-<?php echo esc_attr($item_id); ?>" class="be-edit-menu-item-custom" name="be_megamenu_item_col[<?php echo esc_attr( $item_id ); ?>]">
				<option value="menu-max-col" <?php if ( $saved_be_megamenu_col == "menu-max-col" ){echo( "selected" );} ?>>无</option>
				<option value="menu-max-col-4" <?php if ( $saved_be_megamenu_col == "menu-max-col-4" ){echo( "selected" );} ?>>4列</option>
				<option value="menu-max-col-5" <?php if ( $saved_be_megamenu_col == "menu-max-col-5" ){echo( "selected" );} ?>>5列</option>
				<option value="menu-max-col-6" <?php if ( $saved_be_megamenu_col == "menu-max-col-6" ){echo( "selected" );} ?>>6列</option>
				<option value="menu-max-col-7" <?php if ( $saved_be_megamenu_col == "menu-max-col-7" ){echo( "selected" );} ?>>7列</option>
			</select>
		</label>
	</p>

	<p class="be-menu-shortcode-label be-ico-admin description be-menu-depth be-menu-depth-child">
		<label for="be-menu-shortcode-<?php echo $item_id; ?>">
			<span>超级菜单短代码</span>
		</label>
		<input type="hidden" class="nav-menu-id" value="<?php echo $item_id; ?>" />
		<span class="logged-input-holder">
			<textarea name="be_menu_shortcode[<?php echo $item_id; ?>]" id="be-menu-shortcode-<?php echo $item_id ;?>" class="widefat edit-menu-item-shortcode" rows="3" cols="20"><?php echo $menu_shortcode; ?></textarea>
		</span>
	</p>

	<p class="be-menu-img-label be-ico-admin description be-menu-depth be-menu-depth-child">
		<label for="be-menu-image-<?php echo $item_id; ?>">
			 <span>超级菜单图片</span>
		 </label>
		 <input type="hidden" class="nav-menu-id" value="<?php echo $item_id; ?>" />
		 <span class="logged-input-holder">
			<input type="text" name="be_menu_image[<?php echo $item_id ;?>]" id="be-menu-image-<?php echo $item_id; ?>" value="<?php echo esc_attr( $menu_img ); ?>" style="width: 100%;">
		</span>
	</p>

	<p class="be-menu-explain-label be-ico-admin description be-menu-depth be-menu-depth-child">
		<label for="be-menu-explain-<?php echo $item_id; ?>">
		 	<span>超级菜单说明</span>
		</label>
		<span class="logged-input-holder">
			<textarea name="be_menu_explain[<?php echo $item_id; ?>]" id="be-menu-explain-<?php echo $item_id ;?>" class="widefat edit-menu-item-description" rows="3" cols="20"><?php echo $menu_explain; ?></textarea>
		</span>
	</p>

	<?php
}

add_action( 'wp_nav_menu_item_custom_fields', 'be_menu_custom_fields', 10, 2 );

// save field
function be_save_menu_custom_item_meta( $menu_id, $menu_item_db_id ) {
	if ( isset( $_POST['be_menu_explain'][$menu_item_db_id] ) ) {
		$sanitized_data = sanitize_text_field( $_POST['be_menu_explain'][$menu_item_db_id] );
		update_post_meta( $menu_item_db_id, 'be_menu_custom_explain', $sanitized_data );
	} else {
		delete_post_meta( $menu_item_db_id, 'be_menu_custom_explain' );
	}

	if ( isset( $_POST['be_menu_image'][$menu_item_db_id] ) ) {
		$sanitized_data = sanitize_text_field( $_POST['be_menu_image'][$menu_item_db_id] );
		update_post_meta( $menu_item_db_id, 'be_menu_custom_img', $sanitized_data );
	} else {
		delete_post_meta( $menu_item_db_id, 'be_menu_custom_img' );
	}

	if ( isset( $_POST['be_menu_shortcode'][$menu_item_db_id] ) ) {
		$sanitized_data = sanitize_text_field( $_POST['be_menu_shortcode'][$menu_item_db_id] );
		update_post_meta( $menu_item_db_id, 'be_menu_shortcode', $sanitized_data );
	} else {
		delete_post_meta( $menu_item_db_id, 'be_menu_shortcode' );
	}

	if ( is_array( $_REQUEST['be_megamenu_item_mode'] ) ) {
		$be_megamenu_mode_value = $_REQUEST['be_megamenu_item_mode'][$menu_item_db_id];
		update_post_meta( $menu_item_db_id, '_be_megamenu_item_mode', $be_megamenu_mode_value );
	}

	if ( is_array( $_REQUEST['be_megamenu_item_col'] ) ) {
		$be_megamenu_col_value = $_REQUEST['be_megamenu_item_col'][$menu_item_db_id];
		update_post_meta( $menu_item_db_id, '_be_megamenu_item_col', $be_megamenu_col_value );
	}
}

add_action( 'wp_update_nav_menu_item', 'be_save_menu_custom_item_meta', 10, 2 );

// save select
add_filter( 'wp_setup_nav_menu_item','megamenu_nav_item' );
function megamenu_nav_item( $menu_item ) {
	$menu_item->be_megamenu_mode = get_post_meta( $menu_item->ID, '_be_megamenu_item_mode', true );
	$menu_item->be_megamenu_col = get_post_meta( $menu_item->ID, '_be_megamenu_item_col', true );
	return $menu_item;
}

// output
function be_menu_custom_output( $title, $item ) {
	if ( is_object( $item ) && isset( $item->ID ) ) {
		$menu_img = get_post_meta( $item->ID, 'be_menu_custom_img', true );
		$menu_explain = get_post_meta( $item->ID, 'be_menu_custom_explain', true );
		$menu_shortcode = get_post_meta( $item->ID, 'be_menu_shortcode', true );

		if ( ! empty( $menu_img ) ) {
			$title = '<span class="be-menu-custom-title"><span class="be-menu-custom-title-ico"></span>'.$title.'</span>';
		}

		if ( ! empty( $menu_img ) ) {
			$title .= '<span class="be-menu-img sc"><img src="' . $menu_img . '" alt="' . $menu_explain . '"></span>';
		}

		if ( ! empty( $menu_explain ) ) {
			$title .= '<span class="be-menu-explain">' . $menu_explain . '</span>';
		}

		if ( ! empty( $menu_shortcode ) ) {
			$title = '<span class="be-menu-custom-title"><span class="be-menu-custom-title-ico"></span>'.$title.'</span>';
		}

		if ( ! empty( $menu_shortcode ) ) {
			$title .= '<span class="show-menu-shortcode">' . do_shortcode( $menu_shortcode ) . '<span class="clear"></span></span>';
		}
	}

	return $title;
}

add_filter( 'nav_menu_item_title', 'be_menu_custom_output', 10, 2 );

// Add class
function be_menu_custom_add_class( $classes, $menu_item ) {
	$menu_img = get_post_meta( $menu_item->ID, 'be_menu_custom_img', true );
	$menu_explain = get_post_meta( $menu_item->ID, 'be_menu_custom_explain', true );
	$menu_shortcode = get_post_meta( $menu_item->ID, 'be_menu_shortcode', true );
	$mega_menu_mode = get_post_meta( $menu_item->ID, '_be_megamenu_item_mode', true );
	$mega_menu_col = get_post_meta( $menu_item->ID, '_be_megamenu_item_col', true );

	if ( ! empty( $mega_menu_mode ) ) {
		$classes[] = $mega_menu_mode;
	}

	if ( ! empty( $mega_menu_col ) ) {
		$classes[] = $mega_menu_col;
	}

	if ( ! empty( $menu_img ) ) {
		$classes[] = 'be-menu-custom-img';
	}

	if ( ! empty( $menu_explain ) ) {
		$classes[] = 'be-menu-custom-explain';
	}

	if ( ! empty( $menu_shortcode ) ) {
		$classes[] = 'be-menu-shortcode';
	}

	return $classes;
}
add_filter( 'nav_menu_css_class', 'be_menu_custom_add_class', 10, 2 );