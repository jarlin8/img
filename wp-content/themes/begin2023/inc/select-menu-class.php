<?php
if ( is_array( $this->begm_option ) ) {
	extract( $this->begm_option );
}
$menus    = $this->menus;
$menusloc = $this->menusloc;
?>

<p></p>

<div class="misc-pub-section be-misc-pub-section">
	<div class="misc-pub-t">主题位置</div>
	<select id="begm_location" name="begm_option[begm_location]">
		<option value="">未选择位置</option>
		<?php
			foreach ( $menusloc as $location => $description ) {
				$selected ="";
				if (is_array( $this->begm_option ) && isset( $begm_location ) && $begm_location  == $location ) {
					$selected = 'selected="selected"';
				}
				echo "<option " . $selected . " value='" . $location . "'>" . $description . "</option>";
			}
		?>
	</select>
</div>

<div class="misc-pub-section be-misc-pub-section">
	<div class="misc-pub-t">指派菜单</div>
	<select id="pmenu_list" name="begm_option[begm_menu]">
		<option value="">未指派菜单</option>
		<?php
			foreach ( $menus as $menu ) {
				$selected ="";
				if ( is_array( $this->begm_option ) && isset( $begm_menu ) && $begm_menu  == $menu->term_id ) {
					$selected = "selected='selected'";
				}
				echo "<option " . $selected . " value='" . $menu->term_id . "'>" . $menu->name . "</option>";
			}
		?>
	</select>
</div>

<div id="plist_menu" class="misc-pub-section">
	<?php
		if ( is_array( $this->begm_option ) && isset( $begm_menu ) and $begm_menu!="" ) {
			echo wp_nav_menu( array( 'walker' => new Begm_Walker(), "menu" => $begm_menu ) );
		}
	?>
</div>

<style>
.be-misc-pub-section {
	padding: 6px 10px 8px 0;
}

.postbox .be-misc-pub-section {
	padding: 6px 10px 8px;
}

.misc-pub-t {
	margin: 0 0 5px;
}
#plist_menu .menu li {
	margin: 10px 8px;
	position: relative;
}
#plist_menu .menu ul {
	padding-left: 10px !important;
}
</style>
<script>
jQuery(document).ready(function() {
	begm_location = jQuery("#begm_location");
	pmenu_list = jQuery("#pmenu_list");

	pmenu_list.change(function(evt) {
		jQuery.post(ajaxurl, {
			action: 'begm_listitems',
			menuid: this.options[evt.target.selectedIndex].value,
			nonce: jQuery.trim(jQuery('#begm-nonce').html())

		},
		function(response) {
			jQuery("#plist_menu").html(response);

		});
	});

	jQuery('.menu-item-has-children input[type=checkbox]').on('click', function() {
		if (this.checked) {
			jQuery(this).parents('li').children('input[type=checkbox]').prop('checked', true);
		}

		jQuery(this).parent().find('input[type=checkbox]').prop('checked', this.checked);
	});

	if (begm_location.val() == "") {
		pmenu_list.val("").trigger("change").prop("disabled", true);

	} else {
		pmenu_list.prop("disabled", false);
	}

	begm_location.change(function() {

		if (jQuery(this).val() == "") {
			pmenu_list.val("").trigger("change").prop("disabled", true);

		} else {
			pmenu_list.prop("disabled", false);
		}
	});

});
</script>