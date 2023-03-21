<?php

/**
 * Plugin Name:       AIKit
 * Plugin URI:        https://getaikit.com
 * Description:       AIKit is your WordPress AI assistant, powered by OpenAI's GPT-3 & DALL.E 2.
 * Version:           3.7.0
 * Requires at least: 5.8
 * Requires PHP:      7.4
 * Domain Path:       /languages
 */

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/includes/constants.php';
require __DIR__ . '/includes/openai/prompt-manager.php';
require __DIR__ . '/includes/openai/initial-prompts.php';
require __DIR__ . '/includes/import-export.php';
require __DIR__ . '/includes/admin.php';
require __DIR__ . '/includes/openai/requests.php';


function aikit_block_assets( $hook ) {

    $dependencies = require __DIR__ . '/fe/build/index.asset.php';

	aikit_add_inline_js_object();

    wp_register_style( 'aikit_index_css', plugin_dir_url( __FILE__ ) . 'fe/build/style-index.css', false, $dependencies['version'] );
    wp_enqueue_style ( 'aikit_index_css' );
}

add_action( 'enqueue_block_assets', 'aikit_block_assets' );


add_action( 'init', 'aikit_load_textdomain' );

function aikit_load_textdomain() {

    // get current language
    $currentLanguage = get_locale();

    if (strlen($currentLanguage) > 2) {
        $currentLanguage = explode('_', $currentLanguage)[0];
    }

    // load language regardless of locale
    load_textdomain( 'aikit', __DIR__ . "/languages/$currentLanguage.mo" );
}

/* Add admin notice */
add_action( 'admin_notices', 'aikit_admin_configure_notice' );


/**
 * Admin Notice on Activation.
 * @since 0.1.0
 */
function aikit_admin_configure_notice() {

    global $pagenow;

    if ($pagenow !== 'plugins.php') {
        return;
    }

    $openAiKey = get_option( 'aikit_setting_openai_key' );

    if (strlen($openAiKey) == 0) {
        ?>
        <div id="aikit-notice" class="updated notice is-dismissible">

            <div class="aikit-notice-txt">
                <p>
                    <?php echo esc_html__('Thank you for using AIKit! Please consider entering your OpenAI key in order to start leveraging AI content generation.', 'aikit')?>
                </p>
            </div>
            <div class="aikit-btn-container">
                <a href="<?php echo admin_url( 'admin.php?page=aikit' ); ?>" id="aikit-btn"><?php echo esc_html__('Configure AIKit', 'aikit')?></a>
            </div>
        </div>
        <?php
    }
}


function aikit_init() {
    // Register our script just like we would enqueue it - for WordPress references
    $dependencies = require __DIR__ . '/fe/build/index.asset.php';
    wp_register_script( 'aikit_index_js', plugin_dir_url( __FILE__ ) . 'fe/build/index.js', $dependencies['dependencies'], $dependencies['version'] );

    wp_set_script_translations( 'aikit_index_js', 'aikit', plugin_dir_path( __FILE__ ) . 'languages' );

    if (aikit_get_plugin_version() !== get_option('aikit_plugin_version')) {
        aikit_set_default_settings();
        update_option('aikit_plugin_version', aikit_get_plugin_version());
    }
}

add_action( 'init', 'aikit_init' );

// register an uninstall hook
register_uninstall_hook( __FILE__, 'aikit_uninstall' );

function aikit_uninstall() {
    delete_option( 'aikit_setting_openai_key' );
    delete_option( 'aikit_plugin_version' );
    delete_option( 'aikit_setting_openai_key_valid' );
    delete_option( 'aikit_setting_openai_language' );
    delete_option( 'aikit_setting_openai_model' );
    delete_option( 'aikit_setting_openai_available_models' );
    delete_option( 'aikit_setting_autocompleted_text_background_color' );
    delete_option( 'aikit_setting_openai_max_tokens_multiplier' );
    delete_option( 'aikit_setting_images_size_small' );
    delete_option( 'aikit_setting_images_size_medium' );
    delete_option( 'aikit_setting_images_size_large' );
    delete_option( 'aikit_setting_images_counts' );
    delete_option( 'aikit_setting_images_styles' );
    delete_option( 'aikit_setting_elementor_supported' );

    delete_option( 'aikit_prompts' );

    $aiKitPromptManager = AIKit_Prompt_Manager::get_instance();
	$languages = AIKit_Admin::instance(
		$aiKitPromptManager,
        AIKit_Import_Export_Manager::get_instance($aiKitPromptManager)
    )->get_languages();

    foreach ($languages as $language => $obj) {
        delete_option( 'aikit_prompts_' . $language );
    }
}

register_activation_hook(
	__FILE__,
	'aikit_on_activation'
);

function aikit_on_activation() {
    aikit_set_default_settings();
}

function aikit_set_default_settings () {

    delete_option('aikit_setting_images_styles');
	if (get_option('aikit_setting_images_size_small') === false) {
		update_option('aikit_setting_images_size_small', AIKIT_DEFAULT_SETTING_IMAGES_SIZES_SMALL);
	}

	if (get_option('aikit_setting_images_size_medium') === false) {
		update_option('aikit_setting_images_size_medium', AIKIT_DEFAULT_SETTING_IMAGES_SIZES_MEDIUM);
	}

	if (get_option('aikit_setting_images_size_large') === false) {
		update_option('aikit_setting_images_size_large', AIKIT_DEFAULT_SETTING_IMAGES_SIZES_LARGE);
	}

	if (get_option('aikit_setting_images_counts') === false) {
		update_option('aikit_setting_images_counts', AIKIT_DEFAULT_SETTING_IMAGES_COUNTS);
	}

    if (get_option('aikit_setting_images_styles') === false) {
		update_option('aikit_setting_images_styles', AIKIT_DEFAULT_SETTING_IMAGES_STYLES);
	}

    if (get_option('aikit_setting_openai_language') === false) {
        update_option('aikit_setting_openai_language', AIKIT_DEFAULT_SETTING_SELECTED_LANGUAGE);
    }

    if (get_option('aikit_setting_elementor_supported') === false) {
        update_option('aikit_setting_elementor_supported', AIKIT_DEFAULT_SETTING_ELEMENTOR_SUPPORTED);
    }
}

function aikit_add_inline_js_object () {
    $aikit_build_plugin_js_config = aikit_build_plugin_js_config();

	wp_add_inline_script( 'aikit_index_js', 'var aikit =' . json_encode($aikit_build_plugin_js_config) );

	wp_enqueue_script( 'aikit_index_js');
}

function aikit_build_plugin_js_config() {
    $isOpenAIKeyValid = boolval(get_option( 'aikit_setting_openai_key_valid' ));
    $selectedLanguage = get_option( 'aikit_setting_openai_language' );

    $availableSizes = [];

    if (get_option('aikit_setting_images_size_small')) {
        $availableSizes['small'] = '256x256';
    }

    if (get_option('aikit_setting_images_size_medium')) {
        $availableSizes['medium'] = '512x512';
    }

    if (get_option('aikit_setting_images_size_large')) {
        $availableSizes['large'] = '1024x1024';
    }

    $nonce = wp_create_nonce('wp_rest' );
    $aiKitScriptVars = array(
        'nonce'  =>  $nonce,
        'siteUrl' => get_site_url(),
        'pluginUrl' => plugin_dir_url( __FILE__ ),
        'autocompletedTextBackgroundColor' => get_option('aikit_setting_autocompleted_text_background_color'),
        'isOpenAIKeyValid' => $isOpenAIKeyValid,
        'selectedLanguage' => $selectedLanguage,
        'prompts' => AIKit_Prompt_Manager::get_instance()->get_prompts_for_frontend($selectedLanguage),
        'imageGenerationOptions' => [
            'counts' => explode(',', get_option('aikit_setting_images_counts')),
            'sizes' => $availableSizes,
        ],
    );

    return $aiKitScriptVars;
}

function aikit_get_plugin_version() {
	$plugin_data = array();
	if ( !function_exists( 'get_plugin_data' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	}
	$plugin_data = get_plugin_data( __FILE__ );

    $plugin_version = $plugin_data['Version'] ?? false;

    return $plugin_version;
}

/**
 * Classic Editor
 */

function aikit_classic_buttons($buttons) {
	array_push($buttons, 'aikit_classic_button_text', 'aikit_classic_button_images');

	return $buttons;
}
add_filter('mce_buttons', 'aikit_classic_buttons');

function aikit_classic_mce_css($mce_css) {
	if (! empty($mce_css)) {
		$mce_css .= ',';
	}
	$mce_css .= plugins_url('includes/css/classic.css', __FILE__);

	return $mce_css;
}
add_filter('mce_css', 'aikit_classic_mce_css');

function aikit_classic_mce_plugin($plugin_array) {
    global $pagenow;

    if ($pagenow == 'post.php' || $pagenow == 'post-new.php') {
	    $plugin_array['aikit_classic'] = plugins_url('includes/js/classic.js', __FILE__);
    }

	return $plugin_array;
}
add_filter('mce_external_plugins', 'aikit_classic_mce_plugin');


add_action('admin_head', 'aikit_classic_mce_inline_script');

function aikit_classic_mce_inline_script() {
    global $pagenow;

    if ($pagenow !== 'post.php' && $pagenow !== 'post-new.php') {
        return;
    }

	aikit_add_inline_js_object();
}

add_action('admin_enqueue_scripts', 'aikit_classic_mce_enqueue_scripts');

function aikit_classic_mce_enqueue_scripts() {
    global $pagenow;

    if ($pagenow !== 'post.php' && $pagenow !== 'post-new.php' ) {
        return;
    }

    wp_enqueue_style('aikit_classic_mce_css', plugins_url('includes/css/classic.css', __FILE__));
}

/**
 * Elementor
 */

function register_aikit_elementor_widget( $widgets_manager ) {

    if (!get_option('aikit_setting_elementor_supported')) {
        return;
    }

    require_once __DIR__ . '/includes/integration/elementor-editor.php';

    $widgets_manager->register( new \AIKit_Elementor_Editor() );

}

add_action( 'elementor/widgets/register', 'register_aikit_elementor_widget' );


function register_aikit_new_controls( $controls_manager ) {

    if (!get_option('aikit_setting_elementor_supported')) {
        return;
    }

    require_once __DIR__ . '/includes/integration/hidden-control.php';

    $controls_manager->register( new AIKit_Elementor_Editor_Control(
        aikit_build_plugin_js_config()
    ) );

}

add_action( 'elementor/controls/register', 'register_aikit_new_controls' );
