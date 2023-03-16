<?php

class AIKit_Admin {
/**
     * The single instance of the class.
     *
     * @var AIKit_Admin
     */
    protected static $_instance = null;

    /**
     * @var AIKit_Prompt_Manager
     */
    private $prompt_manager;

    private $languages = [];

    /**
     * Main AIKit_Admin Instance.
     *
     * Ensures only one instance of AIKit_Admin is loaded or can be loaded.
     *
     * @static
     * @return AIKit_Admin - Main instance.
     */
    public static function instance($prompt_manager) {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
            self::$_instance->prompt_manager = $prompt_manager;

            self::$_instance->languages = [
                'en' => [
                    'translatedName' => __('English', 'aikit') . ' (English)',
                    'name' => 'English'
                ],
                'de' => [
                    'translatedName' => __('German', 'aikit') . ' (Deutsch)',
                    'name' => 'Deutsch'
                ],
                'fr' => [
                    'translatedName' => __('French', 'aikit') . ' (Français)',
                    'name' => 'Français',
                ],
                'es' => [
                    'translatedName' => __('Spanish', 'aikit') . ' (Español)',
                    'name' => 'Español',
                ],
                'it' => [
                    'translatedName' => __('Italian', 'aikit') . ' (Italiano)',
                    'name' => 'Italiano',
                ],
                'pt' => [
                    'translatedName' => __('Portuguese', 'aikit') . ' (Português)',
                    'name' => 'Português',
                ],
                'nl' => [
                    'translatedName' => __('Dutch', 'aikit') . ' (Nederlands)',
                    'name' => 'Dutch',
                ],
                'pl' => [
                    'translatedName' => __('Polish', 'aikit') . ' (Polski)',
                    'name' => 'Polski',
                ],
                'ru' => [
                    'translatedName' => __('Russian', 'aikit') . ' (Русский)',
                    'name' => 'Русский',
                ],
                'ja' => [
                    'translatedName' => __('Japanese', 'aikit') . ' (日本語)',
                    'name' => '日本語',
                ],
                'zh' => [
                    'translatedName' => __('Chinese', 'aikit') . ' (中文)',
                    'name' => '中文',
                ],
                'br' => [
                    'translatedName' => __('Brazilian Portuguese', 'aikit') . ' (Português Brasileiro)',
                    'name' => 'Português Brasileiro',
                ],
                'tr' => [
                    'translatedName' => __('Turkish', 'aikit') . ' (Türkçe)',
                    'name' => 'Türkçe',
                ],
                'ar' => [
                    'translatedName' => __('Arabic', 'aikit') . ' (العربية)',
                    'name' => 'العربية',
                ],
                'ko' => [
                    'translatedName' => __('Korean', 'aikit') . ' (한국어)',
                    'name' => '한국어',
                ],
                'hi' => [
                    'translatedName' => __('Hindi', 'aikit') . ' (हिन्दी)',
                    'name' => 'हिन्दी',
                ],
                'id' => [
                    'translatedName' => __('Indonesian', 'aikit') . ' (Bahasa Indonesia)',
                    'name' => 'Bahasa Indonesia',
                ],
                'sv' => [
                    'translatedName' => __('Swedish', 'aikit') . ' (Svenska)',
                    'name' => 'Svenska',
                ],
                'da' => [
                    'translatedName' => __('Danish', 'aikit') . ' (Dansk)',
                    'name' => 'Dansk',
                ],
                'fi' => [
                    'translatedName' => __('Finnish', 'aikit') . ' (Suomi)',
                    'name' => 'Suomi',
                ],
                'no' => [
                    'translatedName' => __('Norwegian', 'aikit') . ' (Norsk)',
                    'name' => 'Norsk',
                ],
                'ro' => [
                    'translatedName' => __('Romanian', 'aikit') . ' (Română)',
                    'name' => 'Română',
                ],
                'ka' => [
                    'translatedName' => __('Georgian', 'aikit') . ' (ქართული)',
                    'name' => 'ქართული',
                ],
                'vi' => [
                    'translatedName' => __('Vietnamese', 'aikit') . ' (Tiếng Việt)',
                    'name' => 'Tiếng Việt',
                ],
                'hu' => [
                    'translatedName' => __('Hungarian', 'aikit') . ' (Magyar)',
                    'name' => 'Magyar',
                ],
            ];
        }
        return self::$_instance;
    }

    /**
     * AIKit_Admin Constructor.
     */
    public function __construct() {
        $this->init();
    }

    /**
     * Initialize the AIKit_Admin class.
     */
    public function init() {
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
    }

    public function get_languages ()
    {
        return $this->languages;
    }

    /**
     * Add options page.
     */
    public function admin_menu() {
        add_menu_page(
            esc_html__('AIKit Settings', 'aikit'),
            esc_html__('AIKit', 'aikit'),
            'manage_options',
            'aikit',
            array( $this, 'options_page' ),
            AIKIT_LOGO_BASE64,
        );

        add_submenu_page(
            'aikit',
            esc_html__('AIKit Settings', 'aikit'),
            esc_html__('Settings', 'aikit'),
            'manage_options',
            'aikit',
            array( $this, 'options_page' )
        );

        add_submenu_page(
                'aikit',
            esc_html__('Add/Edit Prompts (Advanced)', 'aikit'),
            esc_html__('Prompts (Advanced)', 'aikit'),
            'manage_options',
            'aikit_prompts',
            array( $this, 'prompts_page' )
        );
    }

    /**
     * Options page callback.
     */
    public function options_page() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            <form action="options.php" method="post">
                <?php
                // output security fields for the registered setting "aikit_options"
                settings_fields( 'aikit_options' );
                // output setting sections and their fields
                do_settings_sections( 'aikit' );
                // output save settings button
                submit_button( esc_html__( 'Save Settings', 'aikit' ) );
                ?>
            </form>
        </div>
        <?php
    }

    private function reset_prompts() {
        $prompts = AIKIT_INITIAL_PROMPTS;
        $promptsByLang = $this->prompt_manager->build_prompts_by_language($prompts);

        // save all prompts for all languages in a single option
        update_option('aikit_prompts', $prompts);

        foreach ($promptsByLang as $lang => $obj) {
            // save prompts for each language as options
            update_option('aikit_prompts_' . $lang, $obj);
        }
    }

    private function transform_post_request_and_save_prompts() {
        $result = array();
        $postData = $_POST;
        $postPrompts = json_decode(stripslashes($postData['prompts']), true);

        foreach ($postPrompts as $operationId => $obj) {

            foreach ($obj['languages'] as $lang => $langObj) {
                $result[$operationId]['languages'][$lang] = array(
                    'menuTitle' => stripslashes($langObj['menu_title']),
                    'prompt' => stripslashes($langObj['prompt']),
                );
            }

            if ($obj['word_length_type'] === 'fixed') {
                $result[$operationId]['wordLength'] = array(
                    'type' => AIKIT_WORD_LENGTH_TYPE_FIXED,
                    'value' => max(intval($obj['word_length_fixed']), 0),
                );
            } else {
                $result[$operationId]['wordLength'] = array(
                    'type' => AIKIT_WORD_LENGTH_TYPE_WORD_COUNT_MULTIPLIER,
                    'value' => max(intval($obj['word_length_relative']), 1),
                );
            }

            $result[$operationId]['requiresTextSelection'] = $obj['requires_text_selection'] === 'on';

            $result[$operationId]['icon'] = $this->get_icon_for_prompt($operationId);
            $result[$operationId]['generatedTextPlacement'] = $this->get_generated_text_placement_for_prompt($operationId);
            $result[$operationId]['temperature'] = $this->get_temperature_for_prompt($obj['temperature']);

        }

        // get a vertical slice array for all prompts for a given language
        $promptsByLang = $this->prompt_manager->build_prompts_by_language($result);

        // save all prompts for all languages in a single option
        update_option('aikit_prompts', $result);

        foreach ($promptsByLang as $lang => $obj) {
            // save prompts for each language as options
            update_option('aikit_prompts_' . $lang, $obj);
        }
    }

    private function get_temperature_for_prompt($temperature)
    {
        if (floatval($temperature) < 0 || floatval($temperature) > 1) {
            return 0.7;
        }

        return $temperature;
    }

    private function get_icon_for_prompt ($operationId)
    {
        if (isset(AIKIT_INITIAL_PROMPTS[$operationId])) {
            return AIKIT_INITIAL_PROMPTS[$operationId]['icon'];
        }

        return 'custom';
    }

    private function get_generated_text_placement_for_prompt ($operationId)
    {
        if (isset(AIKIT_INITIAL_PROMPTS[$operationId])) {
            return AIKIT_INITIAL_PROMPTS[$operationId]['generatedTextPlacement'];
        }

        return 'below';
    }

    /**
     * Prompts page callback.
     */
    public function prompts_page() {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['reset']) && $_POST['reset'] === '1') {
                $this->reset_prompts();
            } else {
                $this->transform_post_request_and_save_prompts();
            }
        }

        ?>
        <div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            <form action="" method="post" id="aikit-prompts-form">
                <?php
//                // output security fields for the registered setting "aikit_options"
                settings_fields( 'aikit_prompts' );

                $defaultLanguage = aikit_get_language_used();

                $allPrompts = $this->prompt_manager->get_all_prompts();

                ?>

                <div class="row mt-2">
                    <p>
                        <?php echo esc_html__( 'Here you can edit or add new prompts that would appear in the "AI" dropdown menu. You can also reorder the prompts by dragging and dropping them in the order you wish. ', 'aikit' ); ?>
                        <?php echo esc_html__( 'There are lots of online resources that could help and give you tips & trick on how to best edit prompts. Simply search YouTube/Google for "Prompt engineering" to gain more knowledge on the topic.', 'aikit' ); ?>
                    </p>
                </div>
                <div class="aikit-prompts-top-bar">
                    <button type="button" class="btn btn-outline-primary float-start" id="aikit-add-prompt">
                        <?php echo esc_html__( 'Add Prompt', 'aikit' ); ?>
                    </button>
                    <button class="btn btn-outline-danger" id="aikit-reset-prompts" type="submit" data-confirm-message="<?php echo esc_html__( 'Resetting prompts will remove all changes you made in this screen, and will bring back the builtin prompts that AIKit provides out of the box. Are you sure you want to proceed?', 'aikit' ); ?>">
                        <?php echo esc_html__( 'Reset Prompts', 'aikit' ); ?>
                    </button>
                </div>

                <div id="aikit-prompts-accordion">
                    <?php

                    foreach ($allPrompts as $promptKey => $promptObject) {
                        $languages = $promptObject['languages'];
                        // push the default language to the top of the list
                        $languages = array($defaultLanguage => $languages[$defaultLanguage]) + $languages;
                    ?>
                        <div class="group">
                            <h3>
                                <span class="aikit-prompt-icon">
                                    <?php
                                        $icon = $promptObject['icon'];
                                        $iconPath = plugins_url('icons/' . $icon . '.svg', __FILE__);
                                    ?>
                                    <img src="<?php echo esc_url($iconPath); ?>" alt="<?php echo esc_attr($icon); ?>">
                                </span>
                                <span class="aikit-prompt-accordion-header"><?php echo esc_html__($languages[$defaultLanguage]['menuTitle'], 'aikit'); ?></span>
                                <img class="aikit-remove-prompt" alt="" data-confirm-message="<?php echo esc_html__( 'Are you sure you want to remove this prompt?', 'aikit' ); ?>" src="data:image/svg+xml;base64,PCEtLSBVcGxvYWRlZCB0bzogU1ZHIFJlcG8sIHd3dy5zdmdyZXBvLmNvbSwgVHJhbnNmb3JtZWQgYnk6IFNWRyBSZXBvIE1peGVyIFRvb2xzIC0tPgo8c3ZnIHdpZHRoPSI4MDBweCIgaGVpZ2h0PSI4MDBweCIgdmlld0JveD0iMCAwIDE2IDE2IiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGZpbGw9Im5vbmUiPgoNPGcgZmlsbD0iIzAwMDAwMCI+Cg08cGF0aCBkPSJNMTEuMjggNC43MmEuNzUuNzUgMCAwMTAgMS4wNkw5LjA2IDhsMi4yMiAyLjIyYS43NS43NSAwIDExLTEuMDYgMS4wNkw4IDkuMDZsLTIuMjIgMi4yMmEuNzUuNzUgMCAwMS0xLjA2LTEuMDZMNi45NCA4IDQuNzIgNS43OGEuNzUuNzUgMCAwMTEuMDYtMS4wNkw4IDYuOTRsMi4yMi0yLjIyYS43NS43NSAwIDAxMS4wNiAweiIvPgoNPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBkPSJNLjI1IDhhNy43NSA3Ljc1IDAgMTExNS41IDBBNy43NSA3Ljc1IDAgMDEuMjUgOHpNOCAxLjc1YTYuMjUgNi4yNSAwIDEwMCAxMi41IDYuMjUgNi4yNSAwIDAwMC0xMi41eiIgY2xpcC1ydWxlPSJldmVub2RkIi8+Cg08L2c+Cg08L3N2Zz4=" />

                            </h3>
                            <div>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-check">
                                            <input name="prompts[<?php echo esc_html__($promptKey, 'aikit') ?>][requires_text_selection]" class="form-check-input mt-0 requires-text-selection-input" type="checkbox" id="prompts[<?php echo esc_html__($promptKey, 'aikit') ?>][requires_text_selection]" <?php checked(1, $promptObject['requiresTextSelection']); ?>>
                                            <label class="form-check-label" for="prompts[<?php echo esc_html__($promptKey, 'aikit') ?>][requires_text_selection]">
                                                <?php echo esc_html__( 'Requires text selection', 'aikit' ); ?>
                                            </label>
                                            <div class="form-text">
                                                <?php echo esc_html__( 'Choose this option if you want to enforce text selection in the text editor. Most of the time you will want to leave this option selected. Deselect it only if you are adding a prompt that doesn\'t require input from author, like if you want OpenAI to generate text about random topic for example.', 'aikit' ); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="row mb-3">
                                            <label class="col-sm-4 col-form-label" for="prompts[<?php echo esc_html__($promptKey, 'aikit') ?>][temperature]">
                                                <?php echo esc_html__( 'Temperature', 'aikit' ); ?>
                                            </label>
                                            <div class="col-sm-3">
                                                <input type="number" min="0" max="1" step="0.1" class="form-control form-control-sm mt-0" id="prompts[<?php echo esc_html__($promptKey, 'aikit') ?>][temperature]" value="<?php echo isset($promptObject['temperature']) ? esc_html__($promptObject['temperature'], 'aikit') : 0.7 ?>" name="prompts[<?php echo esc_html__($promptKey, 'aikit') ?>][temperature]">
                                            </div>
                                            <div class="form-text">
                                                <?php echo esc_html__( 'Controls randomness: Lowering results in less random completions. As the temperature approaches zero, the model will become deterministic and repetitive.', 'aikit' ); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <label class="mt-4">
                                    <?php echo esc_html__( 'Number of words to generate', 'aikit' ); ?>
                                </label>

                                <div class="row">
                                    <div class="card col-sm-4 m-2 h-25 text-length-card fixed-card">
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input mt-0" type="radio" name="prompts[<?php echo esc_html__($promptKey, 'aikit') ?>][word_length_type]" value="fixed" <?php checked(AIKIT_WORD_LENGTH_TYPE_FIXED, $promptObject['wordLength']['type']) ?>  id="prompts[<?php echo esc_html__($promptKey, 'aikit') ?>][word_length_type_fixed]" >
                                                <label class="form-check-label" for="prompts[<?php echo esc_html__($promptKey, 'aikit') ?>][word_length_type_fixed]">
                                                    <?php echo esc_html__( 'Fixed number of words', 'aikit' ); ?>
                                                </label>
                                            </div>
                                            <div class="row mb-3">
                                                <label class="col-sm-4 col-form-label col-form-label-sm" for="prompts[<?php echo esc_html__($promptKey, 'aikit') ?>][word_length_fixed]">
                                                    <?php echo esc_html__( 'Number of words', 'aikit' ); ?>
                                                </label>
                                                <div class="col-sm-3">
                                                    <input type="number" class="form-control form-control-sm mt-0" id="prompts[<?php echo esc_html__($promptKey, 'aikit') ?>][word_length_fixed]" placeholder="400" value="<?php echo ($promptObject['wordLength']['type'] == AIKIT_WORD_LENGTH_TYPE_FIXED) ? esc_html__($promptObject['wordLength']['value'], 'aikit') : '' ?>" name="prompts[<?php echo esc_html__($promptKey, 'aikit') ?>][word_length_fixed]">
                                                </div>
                                                <div class="form-text">
                                                    <?php echo esc_html__( 'Choose this option if you want to generate a fixed number of words, regardless of how long the selected text is. This is helpful for certain types of prompts, like generating a paragraph on a certain topic for example.', 'aikit' ); ?>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="card col-sm-4 m-2 text-length-card relative-card">
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input mt-0" type="radio" name="prompts[<?php echo esc_html__($promptKey, 'aikit') ?>][word_length_type]" value="relative" <?php checked(AIKIT_WORD_LENGTH_TYPE_WORD_COUNT_MULTIPLIER, $promptObject['wordLength']['type']) ?>  id="prompts[<?php echo esc_html__($promptKey, 'aikit') ?>][word_length_type_relative]">
                                                <label class="form-check-label" for="prompts[<?php echo esc_html__($promptKey, 'aikit') ?>][word_length_type_relative]">
                                                    <?php echo esc_html__( 'Relative to length of text selected', 'aikit' ); ?>
                                                </label>
                                            </div>
                                            <div class="row mb-3">
                                                <label class="col-sm-4 col-form-label col-form-label-sm" for="prompts[<?php echo esc_html__($promptKey, 'aikit') ?>][word_length_relative]">
                                                    <?php echo esc_html__( 'Multiplier', 'aikit' ); ?>
                                                </label>
                                                <div class="col-sm-4">
                                                    <input type="range" step="1" min="1" max="6" class="form-range aikit-slider mt-0" id="prompts[<?php echo esc_html__($promptKey, 'aikit') ?>][word_length_relative]" value="<?php echo ($promptObject['wordLength']['type'] == AIKIT_WORD_LENGTH_TYPE_WORD_COUNT_MULTIPLIER) ? esc_html__($promptObject['wordLength']['value'], 'aikit') : '1' ?>" name="prompts[<?php echo esc_html__($promptKey, 'aikit') ?>][word_length_relative]">
                                                </div>
                                                <div class="col-sm-2">
                                                    <span class="slider-value"></span>
                                                </div>
                                                <div class="form-text">
                                                    <?php echo esc_html__( 'Choose this option if you want to calculate the length of the generated words relative to the length of words selected. 1x = same length as select text, 2x means two times, etc. Summarization is a good candidate to use this option for.', 'aikit' ); ?>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>

                                <h6 class="mt-4">
                                    <?php echo esc_html__( 'Prompts', 'aikit' ); ?>
                                </h6>
                                <div class="tabs">
                                    <ul>
                                        <?php
                                        foreach ($languages as $language => $languageData) {
                                            ?>
                                            <li><a href="#<?php echo esc_html__($promptKey, 'aikit') ?>_tabs_<?php echo esc_html__($language, 'aikit') ?>"><?php echo esc_html__( $this->languages[$language]['name'], 'aikit') ?></a></li>
                                            <?php
                                        }
                                        ?>
                                    </ul>
                                    <?php
                                    foreach ($languages as $language => $languageData) {
                                        ?>
                                        <div id="<?php echo esc_html__($promptKey, 'aikit') ?>_tabs_<?php echo esc_html__($language, 'aikit') ?>">
                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control menu-title-input" id="prompts[<?php echo esc_html__($promptKey, 'aikit') ?>][languages][<?php echo esc_html__($language, 'aikit') ?>][menu_title]" name="prompts[<?php echo esc_html__($promptKey, 'aikit') ?>][languages][<?php echo esc_html__($language, 'aikit') ?>][menu_title]" value="<?php echo esc_html__($languageData['menuTitle'], 'aikit'); ?>" placeholder="<?php echo esc_html__( 'Menu title', 'aikit' ); ?>" />
                                                <label for="prompts[<?php echo esc_html__($promptKey, 'aikit') ?>][languages][<?php echo esc_html__($language, 'aikit') ?>][menu_title]">
                                                    <?php echo esc_html__( 'Menu title', 'aikit' ); ?>
                                                </label>
                                                <div class="form-text">
                                                    <?php echo esc_html__( 'This is title that will appear in the AI menu for this prompt.', 'aikit' ); ?>
                                                </div>
                                            </div>

                                            <div class="form-floating">
                                                <textarea class="form-control prompt-textarea" placeholder="Leave a comment here" name="prompts[<?php echo esc_html__($promptKey, 'aikit') ?>][languages][<?php echo esc_html__($language, 'aikit') ?>][prompt]" id="prompts[<?php echo esc_html__($promptKey, 'aikit') ?>][languages][<?php echo esc_html__($language, 'aikit') ?>][prompt]" cols="30" rows="10"><?php echo esc_html__($languageData['prompt'], 'aikit'); ?></textarea>
                                                <label for="prompts[<?php echo esc_html__($promptKey, 'aikit') ?>][languages][<?php echo esc_html__($language, 'aikit') ?>][prompt]">
                                                    <?php echo esc_html__( 'Prompt', 'aikit' ); ?>
                                                </label>
                                                <div class="form-text">
                                                    <?php echo esc_html__( 'If this prompt requires text selection, the phrase', 'aikit' ); ?>
                                                    <code>[[text]]</code>
                                                    <?php echo esc_html__( 'will be replaced by the selected text before doing the request. Make sure to include it in your prompt.', 'aikit' ); ?>
                                                </div>
                                            </div>

                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                    <?php
                    }
                    ?>

                </div>
                <input type="button" id="aiKitPromptsSubmit" class="button button-primary" value="<?php echo esc_html__( 'Save Settings', 'aikit' ) ?>">
            </form>

            <div class="group-template">
                <h3>
                    <span class="aikit-prompt-icon">
                        <?php
                            $iconPath = plugins_url('icons/custom.svg', __FILE__);
                        ?>
                        <img src="<?php echo esc_url($iconPath); ?>" >
                    </span>
                    <span class="aikit-prompt-accordion-header"></span>
                    <img class="aikit-remove-prompt" alt="" data-confirm-message="<?php echo esc_html__( 'Are you sure you want to remove this prompt?', 'aikit' ); ?>" src="data:image/svg+xml;base64,PCEtLSBVcGxvYWRlZCB0bzogU1ZHIFJlcG8sIHd3dy5zdmdyZXBvLmNvbSwgVHJhbnNmb3JtZWQgYnk6IFNWRyBSZXBvIE1peGVyIFRvb2xzIC0tPgo8c3ZnIHdpZHRoPSI4MDBweCIgaGVpZ2h0PSI4MDBweCIgdmlld0JveD0iMCAwIDE2IDE2IiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGZpbGw9Im5vbmUiPgoNPGcgZmlsbD0iIzAwMDAwMCI+Cg08cGF0aCBkPSJNMTEuMjggNC43MmEuNzUuNzUgMCAwMTAgMS4wNkw5LjA2IDhsMi4yMiAyLjIyYS43NS43NSAwIDExLTEuMDYgMS4wNkw4IDkuMDZsLTIuMjIgMi4yMmEuNzUuNzUgMCAwMS0xLjA2LTEuMDZMNi45NCA4IDQuNzIgNS43OGEuNzUuNzUgMCAwMTEuMDYtMS4wNkw4IDYuOTRsMi4yMi0yLjIyYS43NS43NSAwIDAxMS4wNiAweiIvPgoNPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBkPSJNLjI1IDhhNy43NSA3Ljc1IDAgMTExNS41IDBBNy43NSA3Ljc1IDAgMDEuMjUgOHpNOCAxLjc1YTYuMjUgNi4yNSAwIDEwMCAxMi41IDYuMjUgNi4yNSAwIDAwMC0xMi41eiIgY2xpcC1ydWxlPSJldmVub2RkIi8+Cg08L2c+Cg08L3N2Zz4=" />
                </h3>

                <div>

                    <div class="row">
                        <div class="col">
                            <div class="form-check">
                                <input name="prompts[__PROMPT_KEY__][requires_text_selection]" class="form-check-input mt-0 requires-text-selection-input" type="checkbox" id="prompts[__PROMPT_KEY__][requires_text_selection]" checked>
                                <label class="form-check-label" for="prompts[__PROMPT_KEY__][requires_text_selection]">
                                    <?php echo esc_html__( 'Requires text selection', 'aikit' ); ?>
                                </label>
                                <div class="form-text">
                                    <?php echo esc_html__( 'Choose this option if you want to enforce text selection in the text editor. Most of the time you will want to leave this option selected. Deselect it only if you are adding a prompt that doesn\'t require input from author, like if you want OpenAI to generate text about random topic for example.', 'aikit' ); ?>
                                </div>
                            </div>
                        </div>

                        <div class="col">
                            <div class="row mb-3">
                                <label class="col-sm-4 col-form-label" for="prompts[__PROMPT_KEY__][temperature]">
                                    <?php echo esc_html__( 'Temperature', 'aikit' ); ?>
                                </label>
                                <div class="col-sm-3">
                                    <input type="number" min="0" max="1" step="0.1" class="form-control form-control-sm mt-0" id="prompts[__PROMPT_KEY__][temperature]" value="0.7" name="prompts[__PROMPT_KEY__][temperature]">
                                </div>
                                <div class="form-text">
                                    <?php echo esc_html__( 'Controls randomness: Lowering results in less random completions. As the temperature approaches zero, the model will become deterministic and repetitive.', 'aikit' ); ?>
                                </div>
                            </div>
                        </div>

                    </div>



                    <label class="mt-4">
                        <?php echo esc_html__( 'Number of words to generate', 'aikit' ); ?>
                    </label>

                    <div class="row">
                        <div class="card col-sm-4 m-2 h-25 text-length-card fixed-card">
                            <div class="card-body">
                                <div class="form-check">
                                    <input class="form-check-input mt-0" type="radio" name="prompts[__PROMPT_KEY__][word_length_type]" value="fixed" checked  id="prompts[__PROMPT_KEY__][word_length_type_fixed]" >
                                    <label class="form-check-label" for="prompts[__PROMPT_KEY__][word_length_type_fixed]">
                                        <?php echo esc_html__( 'Fixed number of words', 'aikit' ); ?>
                                    </label>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-4 col-form-label col-form-label-sm" for="prompts[__PROMPT_KEY__][word_length_fixed]">
                                        <?php echo esc_html__( 'Number of words', 'aikit' ); ?>
                                    </label>
                                    <div class="col-sm-3">
                                        <input type="number" class="form-control form-control-sm mt-0" id="prompts[__PROMPT_KEY__][word_length_fixed]" placeholder="400" value="400" name="prompts[__PROMPT_KEY__][word_length_fixed]">
                                    </div>
                                    <div class="form-text">
                                        <?php echo esc_html__( 'Choose this option if you want to generate a fixed number of words, regardless of how long the selected text is. This is helpful for certain types of prompts, like generating a paragraph on a certain topic for example.', 'aikit' ); ?>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="card col-sm-4 m-2 text-length-card relative-card">
                            <div class="card-body">
                                <div class="form-check">
                                    <input class="form-check-input mt-0" type="radio" name="prompts[__PROMPT_KEY__][word_length_type]" value="relative" id="prompts[__PROMPT_KEY__][word_length_type_relative]">
                                    <label class="form-check-label" for="prompts[__PROMPT_KEY__][word_length_type_relative]">
                                        <?php echo esc_html__( 'Relative to length of text selected', 'aikit' ); ?>
                                    </label>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-4 col-form-label col-form-label-sm" for="prompts[__PROMPT_KEY__][word_length_relative]">
                                        <?php echo esc_html__( 'Multiplier', 'aikit' ); ?>
                                    </label>
                                    <div class="col-sm-4">
                                        <input type="range" step="1" min="1" max="6" class="form-range aikit-slider mt-0" id="prompts[__PROMPT_KEY__][word_length_relative]" value="1" name="prompts[__PROMPT_KEY__][word_length_relative]">
                                    </div>
                                    <div class="col-sm-2">
                                        <span class="slider-value"></span>
                                    </div>
                                    <div class="form-text">
                                        <?php echo esc_html__( 'Choose this option if you want to calculate the length of the generated words relative to the length of words selected. 1x = same length as select text, 2x means two times, etc. Summarization is a good candidate to use this option for.', 'aikit' ); ?>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>


                    <h6 class="mt-4">
                        <?php echo esc_html__( 'Prompts', 'aikit' ); ?>
                    </h6>
                    <div class="tabs">
                        <ul>
                            <?php
                            foreach ($languages as $language => $languageData) {
                                ?>
                                <li><a href="#__PROMPT_KEY___tabs_<?php echo esc_html__($language, 'aikit') ?>"><?php echo esc_html__( $this->languages[$language]['name'], 'aikit') ?></a></li>
                                <?php
                            }
                            ?>
                        </ul>
                        <?php
                        foreach ($languages as $language => $languageData) {
                            ?>
                            <div id="__PROMPT_KEY___tabs_<?php echo esc_html__($language, 'aikit') ?>">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control menu-title-input" id="prompts[__PROMPT_KEY__][languages][<?php echo esc_html__($language, 'aikit') ?>][menu_title]" name="prompts[__PROMPT_KEY__][languages][<?php echo esc_html__($language, 'aikit') ?>][menu_title]" value="" placeholder="<?php echo esc_html__( 'Menu title', 'aikit' ); ?>" />
                                    <label for="prompts[__PROMPT_KEY__][languages][<?php echo esc_html__($language, 'aikit') ?>][menu_title]">
                                        <?php echo esc_html__( 'Menu title', 'aikit' ); ?>
                                    </label>
                                    <div class="form-text">
                                        <?php echo esc_html__( 'This is title that will appear in the AI menu for this prompt.', 'aikit' ); ?>
                                    </div>
                                </div>


                                <div class="form-floating">
                                    <textarea class="form-control prompt-textarea" placeholder="Leave a comment here" name="prompts[__PROMPT_KEY__][languages][<?php echo esc_html__($language, 'aikit') ?>][prompt]" id="prompts[__PROMPT_KEY__][languages][<?php echo esc_html__($language, 'aikit') ?>][prompt]" cols="30" rows="10"></textarea>
                                    <label for="prompts[__PROMPT_KEY__][languages][<?php echo esc_html__($language, 'aikit') ?>][prompt]">
                                        <?php echo esc_html__( 'Prompt', 'aikit' ); ?>
                                    </label>
                                    <div class="form-text">
                                        <?php echo esc_html__( 'If this prompt requires text selection, the phrase', 'aikit' ); ?>
                                        <code>[[text]]</code>
                                        <?php echo esc_html__( 'will be replaced by the selected text before doing the request. Make sure to include it in your prompt.', 'aikit' ); ?>
                                    </div>
                                </div>

                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>



            </div>
        </div>
        <?php
    }

    /**
     * Register settings.
     */
    public function register_settings() {

        add_settings_section(
            'aikit_settings_section_openai',
            esc_html__( 'OpenAI Settings', 'aikit' ),
            array ($this, 'aikit_settings_section_openai_callback'),
            'aikit'
        );

        // OpenAI Key
        register_setting('aikit_options', 'aikit_setting_openai_key');

        add_settings_field(
            'aikit_settings_openai_key',
            esc_html__( 'OpenAI Key', 'aikit' ),
            array ($this, 'aikit_settings_openai_key_callback'),
            'aikit',
            'aikit_settings_section_openai'
        );

        // OpenAI Language used for content generation
        register_setting('aikit_options', 'aikit_setting_openai_language');

        add_settings_field(
            'aikit_settings_openai_language',
            esc_html__( 'Language for text generation', 'aikit' ),
            array ($this, 'aikit_settings_openai_language_callback'),
            'aikit',
            'aikit_settings_section_openai'
        );

        // OpenAI Model
        register_setting('aikit_options', 'aikit_setting_openai_model');

        add_settings_field(
            'aikit_settings_openai_model',
            esc_html__( 'OpenAI Preferred Model', 'aikit' ),
            array ($this, 'aikit_settings_openai_model_callback'),
            'aikit',
            'aikit_settings_section_openai'
        );

        // OpenAI Max Tokens Multiplier
        register_setting('aikit_options', 'aikit_setting_openai_max_tokens_multiplier');

        add_settings_field(
            'aikit_settings_openai_max_tokens_multiplier',
            esc_html__( 'Max Tokens Multiplier (text length)', 'aikit' ),
            array ($this, 'aikit_settings_openai_max_tokens_multiplier_callback'),
            'aikit',
            'aikit_settings_section_openai'
        );

        // Autocompleted text background color
        register_setting('aikit_options', 'aikit_setting_autocompleted_text_background_color');

        add_settings_field(
            'aikit_settings_autocompleted_text_background_color',
            esc_html__( 'Autocompleted Text Background Color', 'aikit' ),
            array ($this, 'aikit_settings_autocompleted_text_background_color_callback'),
            'aikit',
            'aikit_settings_section_openai'
        );

        // Elementor support
        register_setting('aikit_options', 'aikit_setting_elementor_supported');

        add_settings_field(
            'aikit_settings_elementor_supported',
            esc_html__( 'Elementor support', 'aikit' ),
            array ($this, 'aikit_settings_elementor_supported_callback'),
            'aikit',
            'aikit_settings_section_openai'
        );

	    // Image sizes
	    register_setting('aikit_options', 'aikit_setting_images_size_small');
	    add_settings_field(
		    'aikit_setting_images_size_small',
		    esc_html__( 'Image sizes available', 'aikit' ),
		    array ($this, 'aikit_setting_images_size_small_callback'),
		    'aikit',
		    'aikit_settings_section_openai'
	    );

	    register_setting('aikit_options', 'aikit_setting_images_size_medium');
	    add_settings_field(
		    'aikit_setting_images_size_medium',
		    '',
		    array ($this, 'aikit_setting_images_size_medium_callback'),
		    'aikit',
		    'aikit_settings_section_openai'
	    );

	    register_setting('aikit_options', 'aikit_setting_images_size_large');
	    add_settings_field(
		    'aikit_setting_images_size_large',
		    '',
		    array ($this, 'aikit_setting_images_size_large_callback'),
		    'aikit',
		    'aikit_settings_section_openai'
	    );

	    // Image counts for each size
	    register_setting('aikit_options', 'aikit_setting_images_counts');
	    add_settings_field(
		    'aikit_setting_images_counts',
		    esc_html__( 'Image counts for each size', 'aikit' ),
		    array ($this, 'aikit_setting_images_counts_callback'),
		    'aikit',
		    'aikit_settings_section_openai'
	    );

	    // Image generation styles
	    register_setting('aikit_options', 'aikit_setting_images_styles');
	    add_settings_field(
		    'aikit_setting_images_styles',
		    esc_html__( 'Image generation styles', 'aikit' ),
		    array ($this, 'aikit_setting_images_styles_callback'),
		    'aikit',
		    'aikit_settings_section_openai'
	    );
    }

    function aikit_settings_section_openai_callback() {
        echo '<p>' . esc_html__('Adjust the plugin to your needs by editing the settings here.', 'aikit') .'</p>';
    }

    function aikit_settings_elementor_supported_callback () {
        $setting = get_option('aikit_setting_elementor_supported');

        ?>
        <input type="checkbox" id="aikit_setting_elementor_supported" name="aikit_setting_elementor_supported" value="1" <?php checked(1, $setting, true); ?>>
        <label for="aikit_setting_elementor_supported"><?php echo esc_html__('Elementor supported?', 'aikit'); ?></label>
        <p>
            <small>
                <?php
                echo esc_html__('When this is enabled, you will be able to use AIKit right inside Elementor editor using a widget called "AIKit Editor".', 'aikit');
                ?>
            </small>

        </p>
        <?php
    }

    function aikit_setting_images_styles_callback () {
        // styles will be a text area, one style per line

        $setting = get_option('aikit_setting_images_styles');

        if (isset($setting) && !empty($setting)) {
            $setting = explode("\n", $setting);
            $setting = array_map('trim', $setting);

            // remove empty lines
            $setting = array_filter($setting, function($value) {
                return !empty($value);
            });

	        $setting = implode("\n", $setting);

            update_option('aikit_setting_images_styles', $setting);
        }

        ?>
        <textarea name="aikit_setting_images_styles" id="aikit_setting_images_styles" cols="60" rows="10"><?php echo esc_attr($setting); ?></textarea>

        <p>
            <small>
	            <?php
	            echo esc_html__('Image styles are phrases that will be added at the end an image\'s prompt to change the look of the image. You can use it to maintain a certain style of images for your posts. If you like colorful images, you can add "colourful" as one of the styles. If you want to appear as if they were drawn by leonardo davinci, add "by leonardo davinci" as a style. Each line would be considered a different style and AIKit will choose a random style out of this list each time it generates an image and append it at the end of the prompt. ', 'aikit');
	            ?>
            </small>
        </p>
        <?php


    }

    function aikit_setting_images_counts_callback() {
	    $setting = get_option('aikit_setting_images_counts');

        // make sure that the value is a comma separated list of integers
        if (isset($setting) && !empty($setting)) {
            $setting = explode(',', $setting);
            $setting = array_map('intval', $setting);

            // minimum of 1 and maximum of 10
            $setting = array_map(function($value) {
                return max(1, min(10, $value));
            }, $setting);

            $setting = implode(',', $setting);

            // update the option
            update_option('aikit_setting_images_counts', $setting);
        } else {
            $setting = AIKIT_DEFAULT_SETTING_IMAGES_COUNTS;
	        update_option('aikit_setting_images_counts', $setting);
        }

        ?>
        <input size="50" type="text" name="aikit_setting_images_counts" value="<?php echo isset( $setting ) ? esc_attr( $setting ) : ''; ?>">
        <p>
            <small>
			    <?php
			    echo esc_html__('This controls the number of image you want to generate for each image size, these will appear as options in the AI image generation menu. Enter comma-separated numbers. Example: "1,2,4". Maximum allowed number is 10.', 'aikit');
			    ?>
            </small>
        </p>
        <?php
    }

    function aikit_setting_images_size_small_callback() {
        $setting = get_option('aikit_setting_images_size_small');

        ?>
            <input type="checkbox" id="aikit_setting_images_size_small" name="aikit_setting_images_size_small" value="1" <?php checked(1, $setting, true); ?>>
            <label for="aikit_setting_images_size_small"><?php echo esc_html__('Small', 'aikit'); ?> (256x256)</label>
        <p>
            <small>
                <?php
                echo esc_html__('This controls the size of the images generated. If you want to have the option to generate small images in the AI image generation menu, check this box.', 'aikit');
                ?>
            </small>

        </p>
        <?php
    }

    function aikit_setting_images_size_medium_callback () {
        $setting = get_option('aikit_setting_images_size_medium');

        ?>

            <input type="checkbox" id="aikit_setting_images_size_medium" name="aikit_setting_images_size_medium" value="1" <?php checked(1, $setting, true); ?>>
            <label for="aikit_setting_images_size_medium"><?php echo esc_html__('Medium', 'aikit'); ?> (512x512)</label>

        <p>
            <small>
                <?php
                echo esc_html__('If you want to have the option to generate medium images in the AI image generation menu, check this box.', 'aikit');
                ?>
            </small>
        </p>
        <?php
    }

    function aikit_setting_images_size_large_callback () {
        $setting = get_option('aikit_setting_images_size_large');

        ?>

            <input type="checkbox" id="aikit_setting_images_size_large" name="aikit_setting_images_size_large" value="1" <?php checked(1, $setting, true); ?>>
            <label for="aikit_setting_images_size_large"><?php echo esc_html__('Large', 'aikit'); ?> (1024x1204)</label>

        <p>
            <small>
                <?php
                echo esc_html__('If you want to have the option to generate large images in the AI image generation menu, check this box.', 'aikit');
                ?>
            </small>

        </p>
        <?php
    }

    function aikit_settings_openai_key_callback() {
        // get the value of the setting we've registered with register_setting()
        $setting = get_option('aikit_setting_openai_key');
        // output the field
        if (isset($setting) && !empty($setting)) {
            $fetchedModels = aikit_rest_openai_get_available_models($setting);
            if ($fetchedModels === false) {
                update_option('aikit_setting_openai_key_valid', false);
                // show a notice to the user that the key is invalid
                echo '<p class="aikit-invalid-key">' . esc_html__('The OpenAI key is invalid. Make sure you have entered the correct key.', 'aikit') . '</p>';
            } else {
                // add an option marking that the key is valid
                update_option('aikit_setting_openai_key_valid', true);
                // store the fetched models in the database
                update_option('aikit_setting_openai_available_models', $fetchedModels);
                // show a notice to the user that the key is valid
                echo '<p class="aikit-valid-key">' . esc_html__('The OpenAI key is valid.', 'aikit') . '</p>';
            }
        } else {
            // remove the option marking that the key is valid
            update_option('aikit_setting_openai_key_valid', false);
        }
        ?>
        <input size="100" type="text" name="aikit_setting_openai_key" value="<?php echo isset( $setting ) ? esc_attr( $setting ) : ''; ?>">
        <p>
            <small>
                <?php
                echo esc_html__('For instructions on how to get an OpenAI key, please visit ', 'aikit') . '<a href="https://getaikit.com/docs/getting-started" target="_blank">https://getaikit.com/docs/getting-started</a>';
                ?>
            </small>
        </p>
        <?php
    }

    function aikit_settings_openai_language_callback() {
        // get the value of the setting we've registered with register_setting()
        $setting = get_option('aikit_setting_openai_language');
        // output the field

        // default to english
        if (empty($setting)) {
            $setting = 'en';
        }

        // output the field
        ?>
        <select name="aikit_setting_openai_language">
            <?php
            foreach ($this->languages as $language => $languageObj) {
                ?>
                <option value="<?php echo esc_html__($language); ?>" <?php echo $setting === $language ? 'selected' : ''; ?>><?php echo esc_html__($languageObj['translatedName']); ?></option>
                <?php
            }
            ?>
        </select>
        <p>
            <small>
                <?php
                echo esc_html__('The language of the text you want to generate.', 'aikit');
                ?>
                <br>
                <?php
                echo esc_html__(' For consistent autocomplete results, make sure that the text you write in your post is written in the same language you picked here.', 'aikit');
                ?>
            </small>
        </p>
        <?php
    }

    function aikit_settings_openai_model_callback() {
        // get the value of the setting we've registered with register_setting()
        $setting = get_option('aikit_setting_openai_model');

        $defaultModels = [
            'gpt-3.5-turbo-0301',
            'text-davinci-003',
            'text-curie-001',
            'text-babbage-001',
            'text-ada-001',
            'text-davinci-001',
            'davinci',
            'davinci-instruct-beta',
            'curie-instruct-beta',
            'curie',
            'babbage',
            'ada',
        ];

        $fetchedModels = get_option('aikit_setting_openai_available_models');
        if ($fetchedModels === false) {
            $fetchedModels = [];
        }

        $allModels = array_merge($defaultModels, $fetchedModels);

        ?>
        <select name="aikit_setting_openai_model">
            <?php foreach ($allModels as $model) { ?>
                <option value="<?php echo esc_html__($model, 'aikit'); ?>" <?php echo $setting == $model ? 'selected' : ''; ?>><?php echo esc_html__($model, 'aikit'); ?></option>
            <?php }
            ?>
        </select>
        <p>
            <small>
                <?php
                echo esc_html__('Some models are more capable than others. For example, the davinci model is more capable than the ada model, which is more capable than the babbage model, and so on. The davinci model is the most capable model, but it is also the most expensive model. The ada model is the least capable model, but it is also the least expensive model.', 'aikit');

                echo esc_html__(' For more information, see ', 'aikit') . '<a href="https://beta.openai.com/docs/models/gpt-3" target="_blank">https://beta.openai.com/docs/models/gpt-3</a>.';
                ?>
            </small>
        </p>
        <?php
    }

    function aikit_settings_openai_max_tokens_multiplier_callback() {
        // get the value of the setting we've registered with register_setting()
        $setting = get_option('aikit_setting_openai_max_tokens_multiplier');
        ?>
        <input type="range" min="0" max="30" value="<?php echo isset( $setting ) && !empty($setting)? esc_attr( $setting ) : '0'; ?>" class="aikit-slider" id="aikit_setting_openai_max_tokens_multiplier" name="aikit_setting_openai_max_tokens_multiplier">
         <span id="aikit_setting_openai_max_tokens_multiplier_value"></span>
        <p>
            <small>
                <?php
                echo esc_html__('AIKit\'s builtin prompts are already preconfigured to generate a sensible number of words depending on the prompt.
                However, if you want to change the number of words generated, you can do so here. The slider is a multiplier of the number of tokens that AIKit
                would normally generate. For example, if a request would normally generate 100 words, you can set the multiplier to 2x and AIKit will generate 200 words instead.', 'aikit');
                ?>
             </small>
         </p>
        <p>
            <small>
                <?php
                echo esc_html__('Think of this as a global way to increase/decrease the length of generated text for all existing autocomplete options/prompts at once.', 'aikit');
                ?>
            </small>
        </p>
        <?php
    }

    function aikit_settings_autocompleted_text_background_color_callback() {
        // get the value of the setting we've registered with register_setting()
        $setting = get_option('aikit_setting_autocompleted_text_background_color');
        ?>
        <select name="aikit_setting_autocompleted_text_background_color" id="aikit_setting_autocompleted_text_background_color">
            <option value="">None</option>
            <option value="#D1E4DD" <?php echo $setting == '#D1E4DD' ? 'selected' : ''; ?>><?php echo esc_html__('Green', 'aikit'); ?></option>
            <option value="#D1DFE4" <?php echo $setting == '#D1DFE4' ? 'selected' : ''; ?>><?php echo esc_html__('Blue', 'aikit'); ?></option>
            <option value="#E4D1D1" <?php echo $setting == '#E4D1D1' ? 'selected' : ''; ?>><?php echo esc_html__('Red', 'aikit'); ?></option>
            <option value="#E4DAD1" <?php echo $setting == '#E4DAD1' ? 'selected' : ''; ?>><?php echo esc_html__('Orange', 'aikit'); ?></option>
            <option value="#D1D1E4" <?php echo $setting == '#D1D1E4' ? 'selected' : ''; ?>><?php echo esc_html__('Purple', 'aikit'); ?></option>
        </select>
        <p>
            <small>
                <?php
                echo esc_html__('If you prefer to have the autocompleted text stand out more, you can choose a background color for the autocompleted text.', 'aikit');
                ?>
            </small>
        </p>
        <?php
    }
}

$AI_kit_admin = AIKit_Admin::instance(
        AIKit_Prompt_Manager::get_instance(),
);

add_action('admin_init', array ($AI_kit_admin, 'register_settings'));


add_filter( 'nonce_life', function () {
    return 60 * 60 * 24 * 7; // 1 week
} );

function aikit_enqueue_admin_scripts( $hook ) {
    if ( 'toplevel_page_aikit' != $hook && 'plugins.php' != $hook && 'aikit_page_aikit_prompts' != $hook ) {
        return;
    }

	$version = aikit_get_plugin_version();
	if ($version === false) {
		$version = rand( 1, 10000000 );
	}

	if ('aikit_page_aikit_prompts' == $hook) {

        wp_enqueue_script( 'aikit_jquery_js', rtrim(plugin_dir_url( __FILE__ ), '/') . '/js/jquery-3.6.0.min.js', array(), $version );
        wp_enqueue_script( 'aikit_jquery_ui_js', rtrim(plugin_dir_url( __FILE__ ), '/') . '/js/jquery-ui.min.js', array(), $version );
        wp_enqueue_script( 'aikit_prompts', plugins_url( 'js/prompts.js', __FILE__ ), array( 'jquery' ), '1.0.0', true );
        wp_enqueue_script( 'aikit_icons', rtrim(plugin_dir_url( __FILE__ ), '/') . '/../fe/src/icons.js',  array(), $version );
        wp_enqueue_style( 'aikit_jquery_ui_css', rtrim(plugin_dir_url( __FILE__ ), '/') . '/css/jquery-ui.min.css', array(), $version );
        wp_enqueue_style( 'aikit_prompts_css', rtrim(plugin_dir_url( __FILE__ ), '/') . '/css/prompts.css', array(), $version );
        wp_enqueue_style( 'aikit_bootstrap_css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css', array(), $version );
        wp_enqueue_style( 'aikit_bootstrap_js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js', array(), $version );

        return;
    }

    wp_enqueue_script( 'aikit_admin_js', rtrim(plugin_dir_url( __FILE__ ), '/') . '/js/admin.js', array(), $version );
    wp_enqueue_style( 'aikit_admin_css', rtrim(plugin_dir_url( __FILE__ ), '/') . '/css/admin.css', array(), $version );
}

add_action( 'admin_enqueue_scripts', 'aikit_enqueue_admin_scripts' );
