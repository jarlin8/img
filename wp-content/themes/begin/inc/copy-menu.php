<?php
// 复制菜单
class CopyMenu {
	function __construct() {
		add_action( 'admin_menu', array( $this, 'options_page' ) );
	}

	function options_page() {
		add_theme_page(
			'复制菜单',
			'<span class="bem"></span>复制菜单',
			'edit_theme_options',
			'copy-menu',
			array( $this, 'options_screen' )
		);
	}

	// 复制开始
	function  becopy( $id = null, $name = null ) {

		// sanity check
		if ( empty( $id ) || empty( $name ) ) {
			return false;
	}

		$id = intval( $id );
		$name = sanitize_text_field( $name );
		$source = wp_get_nav_menu_object( $id );
		$source_items = wp_get_nav_menu_items( $id );
		$new_id = wp_create_nav_menu( $name );

		if ( ! $new_id ) {
			return false;
		}

		// key is the original db ID, val is the new
		$rel = array();

		$i = 1;
		foreach ( $source_items as $menu_item ) {
			$args = array(
				'menu-item-db-id'       => $menu_item->db_id,
				'menu-item-object-id'   => $menu_item->object_id,
				'menu-item-object'      => $menu_item->object,
				'menu-item-position'    => $i,
				'menu-item-type'        => $menu_item->type,
				'menu-item-title'       => $menu_item->title,
				'menu-item-url'         => $menu_item->url,
				'menu-item-description' => $menu_item->description,
				'menu-item-attr-title'  => $menu_item->attr_title,
				'menu-item-target'      => $menu_item->target,
				'menu-item-classes'     => implode( ' ', $menu_item->classes ),
				'menu-item-xfn'         => $menu_item->xfn,
				'menu-item-status'      => $menu_item->post_status
			);

			$parent_id = wp_update_nav_menu_item( $new_id, 0, $args );

			$rel[$menu_item->db_id] = $parent_id;

			// did it have a parent? if so, we need to update with the NEW ID
			if ( $menu_item->menu_item_parent ) {
				$args['menu-item-parent-id'] = $rel[$menu_item->menu_item_parent];
				$parent_id = wp_update_nav_menu_item( $new_id, $parent_id, $args );
			}

			// allow developers to run any custom functionality they'd like
			do_action( 'duplicate_menu_item', $menu_item, $args );

			$i++;
		}

		return $new_id;
	}

	// 设置
	function options_screen() {
		$nav_menus = wp_get_nav_menus();
	?>
	<div class="wrap">
		<div id="icon-options-general" class="icon32"><br /></div>
		<h2>复制菜单</h2>
		<p>使用后，到主题选项 → 菜单设置 → 关闭复制菜单。</p>
		<?php if ( ! empty( $_POST ) && wp_verify_nonce( $_POST['copy_menu_nonce'], 'copy_menu' ) ) : ?>
			<?php
				$source         = intval( $_POST['source'] );
				$destination    = sanitize_text_field( $_POST['new_menu_name'] );

				$duplicator = new CopyMenu();
				$new_menu_id = $duplicator->becopy( $source, $destination );
			?>

			<div id="message" class="updated">
				<p>
				<?php if ( $new_menu_id ) : ?>
					<a href="nav-menus.php?action=edit&amp;menu=<?php echo absint( $new_menu_id ); ?>">查看 </a> 已复制的菜单。
				<?php else: ?>
					复制菜单时出了点问题，未复制！
				<?php endif; ?>
				</p>
			</div>

			<?php endif; ?>


			<?php if ( empty( $nav_menus ) ) : ?>
				<p>您还没有创建菜单</p>
			<?php else: ?>
				<form method="post" action="">
					<?php wp_nonce_field( 'copy_menu','copy_menu_nonce' ); ?>
					<table class="form-table">
						<tr valign="top">
							<th scope="row">
								<label for="source">复制此菜单</label>
							</th>
							<td>
								<select name="source">
									<?php foreach ( ( array ) $nav_menus as $_nav_menu ) : ?>
										<option value="<?php echo esc_attr($_nav_menu->term_id) ?>">
											<?php echo esc_html( $_nav_menu->name ); ?>
										</option>
									<?php endforeach; ?>
								</select>
								<span style="display:inline-block; padding:0 10px;">并命名为</span>
								<input name="new_menu_name" type="text" id="new_menu_name" value="" class="regular-text" />
							</td>
					</table>
					<p class="submit">
						<input type="submit" name="submit" id="submit" class="button-primary" value="复制" />
					</p>
				</form>
			<?php endif; ?>
		</div>
	<?php }
}

new CopyMenu();