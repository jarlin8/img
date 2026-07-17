<?php defined( 'ABSPATH' ) or exit; ?>

<div id="sum-code" class="sum-code">

	<div id="sum-code-variables" class="sum-code-variables">
		<p class="description"><strong><?php _e( 'Available variables', 'shortcodes-ultimate-maker' ); ?></strong></p>
		<ul id="sum-code-variables-list" class="sum-code-variables-list"></ul>
		<p class="description"><?php _e( 'You can use these variables in your code. Variables will be replaced with shortcode content and attribute values. Click a variable to insert it into code editor.', 'shortcodes-ultimate-maker' ); ?></p>
	</div>

	<textarea name="sumk_code" id="sum-code-value" rows="15" class="large-text"><?php echo esc_textarea( base64_decode( get_post_meta( get_the_ID(), 'sumk_code', true ) ) ); ?></textarea>

</div>

<p class="description"><?php printf( __( 'This code will be used to generate the shortcode output. %sLearn more about code editor%s.', 'shortcodes-ultimate-maker' ), '<a href="https://getshortcodes.com/docs/shortcode-creator-user-guide/#code-editor" target="_blank"><nobr>', '</nobr></a>' ); ?></p>
