<?php
class AIKIT_Auto_Writer_Form
{
    //singleton
    private static $instance = null;

    public static function get_instance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function show(
        $show_selected_language = true,
        $show_back_button = false,
        $is_edit = false,
        $description = '',
        $keywords = '',
        $post_type = 'post',
        $post_category = 1,
        $post_status = 'draft',
        $articles = 1,
        $sections_per_article = 3,
        $max_section_length = 800,
        $include_outline = false,
        $include_featured_image = false,
        $include_section_images = false,
        $include_conclusion = false,
        $include_tldr = false,
        $prompts = [],
        $id = 0,
        $generation_interval = 'hourly',
        $generator_status = false,
        $max_runs = 0
    ) {
        $post_types = get_post_types( array( 'public' => true ), 'objects');
        $selected_language = aikit_get_language_used();
        $languages = AIKit_Admin::instance()->get_languages();
        $selected_language_name = $languages[$selected_language]['name'] ?? 'English';

        $available_statuses = [
            'draft' => esc_html__( 'Draft', 'aikit' ),
            'publish' => esc_html__( 'Publish', 'aikit' ),
        ];

        $generator_statuses = [
            true => esc_html__( 'Active', 'aikit' ),
            false => esc_html__( 'Inactive', 'aikit' ),
        ];

        $translations = [
            'Scheduled' => esc_html__( 'Scheduled', 'aikit' ),
            'scheduled Successfully.' => esc_html__( 'scheduled Successfully.', 'aikit' ),
            'AI Auto Writer' => esc_html__( 'AI Auto Writer', 'aikit' ),
        ];

        ?>
        <form id="aikit-auto-writer-form" action="<?php echo get_site_url(); ?>/?rest_route=/aikit/auto-writer/v1/write" method="post">
            <?php if ($is_edit) { ?>
                <input type="hidden" name="aikit-auto-writer-generator-id" id="aikit-auto-writer-generator-id" value="<?php echo $id; ?>">
            <?php } ?>
            <?php if ($show_selected_language) { ?>
            <div class="row">
                <div class="col">
                    <p>
                        <?php echo esc_html__( 'Selected language:', 'aikit' ); ?>
                        <span class="badge badge-pill badge-dark aikit-badge"><?php echo $selected_language_name?></span>
                        <a href="<?php echo admin_url( 'admin.php?page=aikit' ); ?>" ><?php echo esc_html__( 'Change language', 'aikit' ); ?></a>
                    </p>
                </div>
            </div>
            <?php } ?>
            <?php if ($show_back_button) { ?>
            <div class="row">
                <div class="col mb-2">
                    <a href="<?php echo admin_url( 'admin.php?page=aikit_scheduler' ); ?>" class="aikit-scheduler-back"><?php echo esc_html__( '« Back to Scheduler', 'aikit' ); ?></a>
                </div>
            </div>
            <?php } ?>
            <div class="row mb-2">
                <div class="col">
                    <div class="form-floating">
                        <textarea data-validation-message="<?php echo esc_html__( 'Please enter a brief description of the topic you want to write about.', 'aikit' ); ?>" class="form-control" placeholder="<?php echo esc_html__( 'Please enter a brief description of the topic you want to write about.', 'aikit' ); ?>" id="aikit-auto-writer-topic" name="aikit-auto-writer-topic" minlength="1"><?php echo esc_html( $description ); ?></textarea>
                        <label for="aikit-auto-writer-topic"><?php echo esc_html__( 'Write a brief description of the topic you want to write about...', 'aikit' ); ?></label>
                    </div>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="aikit-auto-writer-seo-keywords" placeholder="<?php echo esc_html__( 'SEO keywords to focus on (comma-separated)', 'aikit' ); ?>" value="<?php echo esc_attr( $keywords ); ?>"/>
                        <label for="aikit-auto-writer-seo-keywords"><?php echo esc_html__( 'SEO keywords to focus on (comma-separated)', 'aikit' ); ?></label>
                    </div>
                </div>
                <?php if ($is_edit) { ?>
                    <div class="col-4">
                        <div class="form-floating">
                            <input type="number" class="form-control" id="aikit-auto-writer-max-runs" placeholder="<?php echo esc_html__( 'How many times to run (0=infinity)', 'aikit' ); ?>" value="<?php echo esc_attr( $max_runs ); ?>"/>
                            <label for="aikit-auto-writer-max-runs"><?php echo esc_html__( 'How many times to run (0=infinity)', 'aikit' ); ?></label>
                        </div>
                    </div>
                <?php } ?>
            </div>

            <div class="row mb-2 justify-content-md-center">
                <div class="col">
                    <div class="form-floating">
                        <select class="form-select" id="aikit-auto-writer-post-type" name="aikit-auto-writer-post-type">
                            <?php foreach ($post_types as $type) { ?>
                                <option value="<?php echo esc_attr( $type->name ); ?>" <?php echo $type->name === $post_type ? 'selected' : ''; ?>><?php echo esc_html( $type->labels->singular_name ); ?></option>
                            <?php } ?>
                        </select>
                        <label for="aikit-auto-writer-post-type"><?php echo esc_html__( 'Post type', 'aikit' ); ?></label>
                    </div>
                </div>
                <div class="col">
                    <div class="form-floating">
                        <select class="form-select" id="aikit-auto-writer-post-category" name="aikit-auto-writer-post-category">
                            <?php foreach (get_categories(['hide_empty' => false]) as $category) { ?>
                                <option value="<?php echo esc_attr( $category->term_id ); ?>" <?php echo $category->term_id === $post_category ? 'selected' : ''; ?>><?php echo esc_html( $category->name ); ?></option>
                            <?php } ?>
                        </select>
                        <label for="aikit-auto-writer-post-category"><?php echo esc_html__( 'Post category', 'aikit' ); ?></label>
                    </div>
                </div>
                <div class="col">
                    <div class="form-floating">
                        <select class="form-select" id="aikit-auto-writer-post-status" name="aikit-auto-writer-post-status">
                            <?php foreach ($available_statuses as $status => $status_name) { ?>
                                <option value="<?php echo esc_attr( $status ); ?>" <?php echo $status === $post_status ? 'selected' : ''; ?>><?php echo esc_html( $status_name ); ?></option>
                            <?php } ?>
                        </select>
                        <label for="aikit-auto-writer-post-status"><?php echo esc_html__( 'Post status', 'aikit' ); ?></label>
                    </div>
                </div>
                <?php if ($is_edit) { ?>
                <div class="col">
                    <?php
                        $this->render_intervals($generation_interval);
                    ?>
                </div>
                <div class="col">
                    <div class="form-floating">
                        <select class="form-select" id="aikit-auto-writer-generator-status" name="aikit-auto-writer-generator-status">
                            <?php foreach ($generator_statuses as $status => $status_name) { ?>

                                <option value="<?php echo esc_attr( $status ); ?>" <?php echo boolval($status) === boolval($generator_status) ? 'selected' : ''; ?>><?php echo esc_html( $status_name ); ?></option>
                            <?php } ?>
                        </select>
                        <label for="aikit-auto-writer-post-status"><?php echo esc_html__( 'Status', 'aikit' ); ?></label>
                    </div>
                </div>
                <?php } ?>
            </div>

            <div class="row mb-2">
                <div class="col d-flex justify-content-center">
                    <div class="d-inline m-2 ">
                        <label for="aikit-auto-writer-articles" class="aikit-auto-writer"><?php echo esc_html__( 'Articles: ', 'aikit' ); ?></label>
                        <input type="number" id="aikit-auto-writer-articles" name="aikit-auto-writer-articles" min="1" max="10" step="1" value="<?php echo esc_attr( $articles ); ?>">
                    </div>
                    <div class="d-inline m-2 ">
                        <label for="aikit-auto-writer-sections" class="aikit-auto-writer"><?php echo esc_html__( 'Sections per article: ', 'aikit' ); ?></label>
                        <input type="number" id="aikit-auto-writer-sections" name="aikit-auto-writer-sections" min="1" max="20" step="1" value="<?php echo esc_attr( $sections_per_article ); ?>">
                    </div>
                    <div class="d-inline m-2 ">
                        <label for="aikit-auto-writer-words-per-section" class="aikit-auto-writer"><?php echo esc_html__( 'Maximum words per section: ', 'aikit' ); ?></label>
                        <input type="number" id="aikit-auto-writer-words-per-section" name="aikit-auto-writer-words-per-section" min="100" max="3000" step="1" value="<?php echo esc_attr( $max_section_length ); ?>">
                    </div>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col d-flex justify-content-center">
                    <div class="d-inline m-2 ">
                        <input type="checkbox" class="form-check-input" id="aikit-auto-writer-include-outline" name="aikit-auto-writer-include-outline" <?php echo $include_outline ? 'checked' : ''; ?>>
                        <label class="form-check-label aikit-auto-writer" for="aikit-auto-writer-include-outline"><?php echo esc_html__( 'Include outline', 'aikit' ); ?></label>
                    </div>

                    <div class="d-inline m-2">
                        <input type="checkbox" class="form-check-input" id="aikit-auto-writer-include-featured-image" name="aikit-auto-writer-include-featured-image" <?php echo $include_featured_image ? 'checked' : ''; ?>>
                        <label class="form-check-label aikit-auto-writer" for="aikit-auto-writer-include-featured-image"><?php echo esc_html__( 'Include featured article image', 'aikit' ); ?></label>
                    </div>

                    <div class="d-inline m-2">
                        <input type="checkbox" class="form-check-input" id="aikit-auto-writer-include-section-images" name="aikit-auto-writer-include-section-images" <?php echo $include_section_images ? 'checked' : ''; ?>>
                        <label class="form-check-label aikit-auto-writer" for="aikit-auto-writer-include-section-images"><?php echo esc_html__( 'Include section images', 'aikit' ); ?></label>
                    </div>

                    <div class="d-inline m-2">
                        <input type="checkbox" class="form-check-input" id="aikit-auto-writer-include-conclusion" name="aikit-auto-writer-include-conclusion" <?php echo $include_conclusion ? 'checked' : ''; ?>>
                        <label class="form-check-label aikit-auto-writer" for="aikit-auto-writer-include-conclusion"><?php echo esc_html__( 'Include conclusion', 'aikit' ); ?></label>
                    </div>

                    <div class="d-inline m-2">
                        <input type="checkbox" class="form-check-input" id="aikit-auto-writer-include-tldr" name="aikit-auto-writer-include-tldr" <?php echo $include_tldr ? 'checked' : ''; ?>>
                        <label class="form-check-label aikit-auto-writer" for="aikit-auto-writer-include-tldr"><?php echo esc_html__( 'Include TL;DR', 'aikit' ); ?></label>
                    </div>
                </div>
            </div>

            <div class="row mb-2 justify-content-end text-end">
                <div class="col-6">
                    <?php if ($is_edit) { ?>
                        <button id="aikit-auto-writer-save" data-edit="1" class="btn btn-primary" type="button"><i class="bi bi-save"></i> <?php echo esc_html__( 'Update', 'aikit' ); ?></button>
                    <?php } else { ?>
                        <div class="row aikit-schedule-options mb-2 " style="display: none">
                            <div class="col">
                                <div class="row">
                                    <div class="col">
                                        <?php
                                        $this->render_intervals();
                                        ?>
                                    </div>
                                    <div class="col">
                                        <div class="form-floating">
                                            <input type="number" class="form-control" value="0" id="aikit-auto-writer-max-runs" placeholder="<?php echo esc_html__( 'How many times to run (0=infinity)', 'aikit' ); ?>">
                                            <label for="aikit-auto-writer-max-runs"><?php echo esc_html__( 'How many times to run (0=infinity)', 'aikit' ); ?></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row justify-content-end">
                                    <div class="col">
                                        <div class="mt-2">
                                            <a id="aikit-auto-writer-cancel-schedule" class="me-2" href="#"><?php echo esc_html__( 'Cancel', 'aikit' ); ?></a>
                                            <button type="button" id="aikit-auto-writer-confirm-schedule" class="btn btn-dark"><i class="bi bi-arrow-repeat me-2"></i><?php echo esc_html__( 'Confirm Schedule', 'aikit' ); ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button id="aikit-auto-writer-schedule" class="btn btn-outline-dark" type="button"><i class="bi bi-arrow-repeat me-2"></i><?php echo esc_html__( 'Schedule', 'aikit' ); ?></button>
                        <button id="aikit-auto-writer-generate" class="btn btn-primary ms-2" type="submit"><img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . '../../icons/aikit.svg' ); ?>"/><?php echo esc_html__( 'Generate', 'aikit' ); ?></button>
                    <?php } ?>
                </div>
            </div>

            <div class="row">
                <div class="col d-flex justify-content-end">
                    <div class="aikit-dont-close-page"><?php echo esc_html__( 'Please don\'t close this page until generation is done.', 'aikit' ); ?></div>
                </div>
            </div>

            <div class="accordion accordion-flush" id="aikit-auto-writer-prompts">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#aikit-auto-writer-prompts-pane" aria-expanded="false" aria-controls="flush-collapseOne">
                            <?php echo esc_html__( 'Prompts', 'aikit' ); ?>
                        </button>
                    </h2>
                    <div id="aikit-auto-writer-prompts-pane" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            <?php
                            $prompts = !empty($prompts) ? $prompts : $this->get_prompts();

                            foreach ($prompts as $id => $prompt) {
                                // get all the placeholders in the prompt (e.g. [[noun]]) and list them
                                preg_match_all('/\[\[([^\]]+)\]\]/', $prompt, $matches);
                                $placeholders = $matches[1];
                                // surround each placeholder with <code> tags
                                $placeholders = array_map(function($placeholder) {
                                    return '<code>' . $placeholder . '</code>';
                                }, $placeholders);
                                $placeholderString = implode(', ', $placeholders);

                                echo '<div class="mb-2">';
                                echo '<label for="aikit-auto-writer-prompt-'.$id.'" class="aikit-auto-writer">'. $id  .':</label>';
                                echo '<span class="aikit-auto-writer-prompt-description">' . esc_html__(' uses', 'aikit')  . $placeholderString . '</span>';
                                echo '<textarea class="form-control aikit-auto-writer-prompt" data-prompt-id="' . $id . '" id="aikit-auto-writer-prompt-'.$id.'" name="aikit-auto-writer-prompt-'.$id.'" rows="3">'. $prompt .'</textarea>';
                                echo '</div>';
                            }
                            ?>
                            <p class="aikit-auto-writer-placeholder-descriptions">
                                <?php echo esc_html__( 'You can use the following placeholders in your prompts:', 'aikit' ); ?>
                            </p>

                            <ul class="aikit-auto-writer-placeholder-descriptions">
                                <li><code>[[description]]</code> - <?php echo esc_html__( 'this will be replaced with you article description/topic.', 'aikit' ); ?></li>
                                <li><code>[[section]]</code> - <?php echo esc_html__( 'this will be replaced with the text of generated section.', 'aikit' ); ?></li>
                                <li><code>[[text]]</code> - <?php echo esc_html__( 'this will be replaced with text needed for that prompt.', 'aikit' ); ?></li>
                                <li><code>[[section-headlines]]</code> - <?php echo esc_html__( 'this will be replaced with the suggested headlines for the sections about to be generated.', 'aikit' ); ?></li>
                                <li><code>[[keywords]]</code> - <?php echo esc_html__( 'this will be replaced with the SEO keywords you entered.', 'aikit' ); ?></li>
                                <li><code>[[number-of-headlines]]</code> - <?php echo esc_html__( 'this will be replaced with the number of headlines for the article section.', 'aikit' ); ?></li>
                            </ul>

                        </div>
                    </div>
                </div>
            </div>

            <div>
                <input type="hidden" id="aikit-auto-writer-translations" value="<?php echo esc_attr(json_encode($translations)); ?>">
            </div>

        </form>

        <?php
    }

    private function render_intervals($selected = null)
    {
        $available_intervals = [
            'hourly' => esc_html__( 'Hourly', 'aikit' ),
            'twicedaily' => esc_html__( 'Twice daily', 'aikit' ),
            'daily' => esc_html__( 'Daily', 'aikit' ),
        ];

        ?>
            <div class="form-floating">
                <select class="form-select" id="aikit-auto-writer-schedule-interval" name="aikit-auto-writer-schedule-interval">
                    <?php foreach ($available_intervals as $value => $label) { ?>
                        <option value="<?php echo esc_attr($value); ?>" <?php echo $selected === $value ? 'selected' : ''; ?>><?php echo esc_html($label); ?></option>
                    <?php } ?>
                </select>
                <label for="aikit-auto-writer-schedule-interval"><?php echo esc_html__( 'Interval', 'aikit' ); ?></label>
            </div>
        <?php
    }

    private function get_prompts()
    {
        $lang = get_option('aikit_setting_openai_language', 'en');

        return AIKIT_AUTO_GENERATOR_PROMPTS[$lang]['prompts'];
    }
}