<?php

use AIKit\Dependencies\duzun\hQuery;

class AIKIT_Repurposer
{
    const TABLE_NAME = 'aikit_repurpose_jobs';
    const JOB_TYPE_URL = 'url';
    const JOB_TYPE_YOUTUBE = 'youtube';

    const POST_META_AIKIT_REPURPOSER_JOB_ID = 'aikit_repurposer_job_id';
    const POST_META_AIKIT_REPURPOSED = 'aikit_repurposed';

    /** @var AIKIT_Youtube_Subtitle_Reader|null  */
    private $youtube_subtitle_reader;

    // singleton
    private static $instance = null;

    public static function get_instance()
    {
        if (self::$instance == null) {
            self::$instance = new AIKIT_Repurposer();
        }
        return self::$instance;
    }

    public function __construct()
    {
        add_action( 'rest_api_init', function () {
            register_rest_route( 'aikit/repurposer/v1', '/repurpose', array(
                'methods' => 'POST',
                'callback' => array($this, 'handle_request'),
                'permission_callback' => function () {
                    return is_user_logged_in() && current_user_can( 'edit_posts' );
                }
            ));

            register_rest_route( 'aikit/repurposer/v1', '/extract-text', array(
                'methods' => 'POST',
                'callback' => array($this, 'extract_text'),
                'permission_callback' => function () {
                    return is_user_logged_in() && current_user_can( 'edit_posts' );
                }
            ));

            register_rest_route( 'aikit/repurposer/v1', '/delete', array(
                'methods' => 'POST',
                'callback' => array($this, 'delete_job'),
                'permission_callback' => function () {
                    return is_user_logged_in() && current_user_can( 'edit_posts' );
                }
            ));
        });

        add_action('aikit_repurposer', array($this, 'execute'));
        $this->youtube_subtitle_reader = AIKIT_Youtube_Subtitle_Reader::get_instance();
    }

    public function activate_scheduler()
    {
        if (! wp_next_scheduled ( 'aikit_repurposer')) {
            wp_schedule_event( time(), 'every_5_minutes', 'aikit_repurposer');
        }
    }

    public function deactivate_scheduler()
    {
        wp_clear_scheduled_hook('aikit_repurposer');
    }

    public function delete_job($data)
    {
        global $wpdb;

        $table_name = $wpdb->prefix . self::TABLE_NAME;

        $job_id = $data['id'];

        $wpdb->delete(
            $table_name,
            array(
                'id' => $job_id,
            )
        );

        return new WP_REST_Response(array(
            'success' => true,
        ));
    }

    public function execute()
    {
        global $wpdb;

        $table_name = $wpdb->prefix . self::TABLE_NAME;

        $jobs = $wpdb->get_results(
            "SELECT * FROM $table_name WHERE is_active = 1 and finished_at IS NULL AND is_running = 0 AND error_count < 5"
        );

        foreach ($jobs as $job) {
            $wpdb->update(
                $table_name,
                array(
                    'is_running' => 1,
                ),
                array('id' => $job->id)
            );

            $extracted_text = '';
            try {
                if ($job->extracted_text == null || $job->extracted_text == '') {
                    $extracted_text = $this->get_url_content($job->job_url);
                } else {
                    $extracted_text = $job->extracted_text;
                }

                $result = $this->process_job($job, $extracted_text);

            } catch (\Throwable $th) {
                $result = [
                    'success' => false,
                    'message' => $th->getMessage(),
                ];
            }

            $error_count = $job->error_count;
            if (!$result['success']) {
                $error_count++;
            }

            // store the content in the job

            $wpdb->update(
                $table_name,
                array(
                    'is_running' => 0,
                    'extracted_text' => $extracted_text,
                    'logs' => json_encode([$result]),
                    'error_count' => $error_count,
                    'finished_at' => current_time('mysql'),
                ),
                array('id' => $job->id)
            );

        }
    }

    private function process_job($job, $extracted_text)
    {
        $post_type = $job->output_post_type;
        $post_category = $job->output_post_category;
        $post_status = $job->output_post_status;
        $post_author = $job->output_post_author;
        $keywords = $job->keywords ?? '';
        $number_of_articles = $job->number_of_articles;
        $include_featured_image = $job->include_featured_image == 1;
        $prompts = json_decode($job->prompts, true);
        $temperature = 0.7;

        if (empty(trim($extracted_text))) {
            return [
                'success' => false,
                'message' => 'Extracted content from URL is empty.',
            ];
        }

        $post_ids = [];

        $prompt_name_suffix = '';
        if (!empty(trim($keywords))) {
            $prompt_name_suffix = '-with-seo-keywords';
        }

        for ($i=0; $i < $number_of_articles; $i++) {

            $result_content = '';
            $summaries_of_all_chunks_array = [];

            $chunks = $this->get_paragraphs($extracted_text);
            foreach ($chunks as $chunk) {
                if (empty(trim($chunk))) {
                    continue;
                }

                $estimated_chunk_tokens = $this->estimate_number_of_tokens($chunk);
                $generated_chunk = aikit_openai_text_generation_request(
                    $this->build_prompt($prompts['text-generation' . $prompt_name_suffix], array(
                            'text' => $chunk,
                            'keywords' => $keywords,
                        )
                    ), $estimated_chunk_tokens * 1.5, $temperature);

                $result_content = $this->add_text($result_content, $generated_chunk);

                $summaries_of_all_chunks_array[] = aikit_openai_text_generation_request(
                    $this->build_prompt($prompts['summary'], array(
                            'text' => $generated_chunk,
                        )
                    ), 250, $temperature);
            }

            $summaries_of_all_chunks = implode("\n", $summaries_of_all_chunks_array);

            $title = aikit_openai_text_generation_request(
                $this->build_prompt($prompts['title' . $prompt_name_suffix], array(
                        'summaries' => $summaries_of_all_chunks,
                        'keywords' => $keywords,
                    )
                ), 250, $temperature);

            $title = $this->clean_title($title);

            $post = array(
                'post_title' => $title,
                'post_content' => $result_content,
                'post_status' => $post_status,
                'post_author' => $post_author,
                'post_type' => $post_type,
                'post_category' => array($post_category)
            );

            $post_id = wp_insert_post($post);

            if ($include_featured_image) {
                $featured_image = aikit_openai_text_generation_request(
                    $this->build_prompt($prompts['image'], array(
                            'summaries' => $summaries_of_all_chunks,
                        )
                    ), 250, $temperature);

                $images = aikit_openai_image_generation_request($featured_image);
                if (count($images) > 0) {
                    set_post_thumbnail($post_id, $images[0]['id']);
                }
            }

            if (!$post_id) {
                throw new Exception('Could not create post');
            }

            add_post_meta($post_id, self::POST_META_AIKIT_REPURPOSED, true);
            add_post_meta($post_id, self::POST_META_AIKIT_REPURPOSER_JOB_ID, $job->id);


            if (!empty($keywords)) {
                wp_set_post_tags($post_id, explode(',', $keywords), true);
            }

            $post_ids[] = $post_id;
        }

        return [
            'success' => true,
            'post_ids' => $post_ids,
        ];
    }

    private function clean_title($title)
    {
        // remove " and ' from the beginning and end of the title
        $title = trim($title, '"');
        return trim($title, "'");

    }

    private function add_text($content, $text)
    {
        // divide text into paragraphs
        $paragraphs = explode("\n", $text);

        foreach ($paragraphs as $paragraph) {
            if (empty(trim($paragraph))) {
                continue;
            }

            $content .= '<p>' . htmlentities($paragraph) . '</p>';
        }

        return $content;
    }

    private function estimate_number_of_tokens ($text) {
        return intval(aikit_calculate_word_count_utf8($text) * 2);
    }

    private function build_prompt($prompt, $keyValueArray)
    {
        foreach ($keyValueArray as $key => $value) {
            $prompt = str_replace('[[' . $key . ']]', $value, $prompt);
        }

        return $prompt;
    }

    private function get_url_content($url)
    {
        $doc = hQuery::fromUrl($url, [
            'Accept' => 'text/html,application/xhtml+xml;q=0.9,*/*;q=0.8',
            'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/112.0.0.0 Safari/537.36 Edg/112.0.1722.64',
        ]);

        if (!$doc) {
            throw new Exception('Could not load URL, probably does not exist.');
        }

        $elements = $doc->find('h1, h2, h3, h4, h5, h6, p, blockquote, pre, code');

        $text = '';
        foreach ($elements as $element) {
            // if paragraph, add 2 new lines
            if ($element->nodeName == 'p') {
                $text .= str_replace("\n", ' ', $element->text());
                $text .= "\n\n";
            } else {
                $text .= $element->text();
                $text .= "\n";
            }
        }

        return $text;
    }

    public function extract_text($data)
    {
        $url = $data['url'];

        if (empty($url)) {
            return new WP_REST_Response([
                'error' => __('URL is empty.', 'aikit'),
            ], 400);
        }

        try {
            $job_type = $data['job_type'];

            if ($job_type == self::JOB_TYPE_URL) {
                $content = $this->get_url_content($url);
            } else {
                $content = $this->youtube_subtitle_reader->get_subtitles($url);
            }
        } catch (\Throwable $e) {
            return new WP_REST_Response([
                'error' => __('Could not extract text from URL.', 'aikit'),
                'message' => $e->getMessage(),
            ], 400);
        }

        return new WP_REST_Response([
            'content' => $content,
        ], 200);
    }

    public function handle_request($data)
    {
        $id = $this->add_job($data);

        if ($id === false) {
            return new WP_REST_Response([
                'error' => __('Could not create job.', 'aikit'),
            ], 400);
        }

        return new WP_REST_Response([
            'message' => __('Job created.', 'aikit'),
            'url' => admin_url('admin.php?page=aikit_repurpose&action=jobs&job_id=' . $id),
        ], 200);
    }

    public function add_job($data)
    {
        global $wpdb;

        $table_name = $wpdb->prefix . self::TABLE_NAME;

        $entity = array(
            'job_url' => $data['url'],
            'job_type' => $data['job_type'] == self::JOB_TYPE_YOUTUBE ? self::JOB_TYPE_YOUTUBE : self::JOB_TYPE_URL,
            'keywords' => $data['keywords'],
            'include_featured_image' => boolval($data['include_featured_image']),
            'is_active' => 1,
            'number_of_articles' => $data['number_of_articles'],
            'output_post_type' => $data['post_type'],
            'output_post_status' => $data['post_status'],
            'output_post_author' => get_current_user_id(),
            'output_post_category' => $data['post_category'],
            'prompts' => json_encode($data['prompts']),
            'date_created' => current_time( 'mysql' ),
            'date_modified' => current_time( 'mysql' ),
            'logs' => '[]',
        );

        if (isset($data['extracted_text']) && !empty(trim($data['extracted_text']))) {
            $entity['extracted_text'] = $data['extracted_text'];
        }

        $result = $wpdb->insert(
            $table_name,
            $entity
        );

        return $result === false ? false : $wpdb->insert_id;
    }

    public function render()
    {
        $active_tab = isset( $_GET['action'] ) ? $_GET['action'] : 'create';
        $cron_url = get_site_url() . '/wp-cron.php';

        ?>
        <div class="wrap">
            <h1><?php echo esc_html__( 'AIKit Repurpose', 'aikit' ); ?></h1>
            <p>
                <?php echo esc_html__( 'AIKit repurposing jobs allow you to automatically create new posts out of existing content.', 'aikit' ); ?>
                <?php echo esc_html__( 'Please review and edit before publishing for best results. This is not a substitute for human editing, but a drafting aid. Happy writing!', 'aikit' ); ?>
            </p>

            <p>
                <strong><?php echo esc_html__( 'Note:', 'aikit' ); ?></strong>
                <?php echo esc_html__('AIKit repurposing jobs run in the background as scheduled jobs.', 'aikit'); ?>
                <?php echo esc_html__( 'By default, WordPress scheduled jobs only run when someone visits your site. To ensure that your repurposing jobs run even if nobody visits your site, you can set up a cron job on your server to call the WordPress cron system at regular intervals. Please ask your host provider to do that for you. Here is the cron job definition:', 'aikit' ); ?>
                <code>
                    */5 * * * * curl -I <?php echo $cron_url ?> >/dev/null 2>&1
                </code>
            </p>

            <ul class="nav nav-tabs aikit-repurposer-tabs">
                <li class="nav-item">
                    <a class="nav-link <?php echo $active_tab == 'create' ? 'active' : ''; ?>" aria-current="page" href="<?php echo admin_url( 'admin.php?page=aikit_repurpose&action=create' ); ?>"><?php echo esc_html__( 'Create Repurpose Job', 'aikit' ); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $active_tab == 'jobs' ? 'active' : ''; ?>" href="<?php echo admin_url( 'admin.php?page=aikit_repurpose&action=jobs' ); ?>"><?php echo esc_html__( 'Jobs', 'aikit' ); ?></a>
                </li>
            </ul>

            <?php
                if ($active_tab == 'create') {
                    $this->render_create_tab();
                } else if ($active_tab == 'jobs') {
                    if (isset($_GET['job_id']))
                        $this->render_view_tab();
                    else {
                        $this->render_listing_tab();
                    }
                }
            ?>

        </div>
        <?php

    }

    private function render_create_tab()
    {
        $this->show_job_form();
    }

    private function show_job_form($is_view = false, $job = null)
    {
        $selected_language = aikit_get_language_used();
        $languages = AIKit_Admin::instance()->get_languages();
        $selected_language_name = $languages[$selected_language]['name'] ?? 'English';

        $post_types = get_post_types( array( 'public' => true ), 'objects');

        $available_statuses = [
            'draft' => esc_html__( 'Draft', 'aikit' ),
            'publish' => esc_html__( 'Publish', 'aikit' ),
        ];

        $translations = [
            'Created' => esc_html__( 'Created', 'aikit' ),
            'Repurpose job' => esc_html__( 'Repurpose job', 'aikit' ),
            'created Successfully.' => esc_html__( 'created Successfully.', 'aikit' ),
        ];

        ?>
        <form id="aikit-repurposer-form" action="<?php echo get_site_url(); ?>/?rest_route=/aikit/repurposer/v1/repurpose" method="post">
            <?php if (!$is_view) { ?>
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

            <?php if ($is_view) { ?>
                <div class="row">
                    <div class="col mb-2">
                        <a href="<?php echo admin_url( 'admin.php?page=aikit_repurpose&action=jobs' ); ?>" class="aikit-repurposer-back"><?php echo esc_html__( '« Back to Jobs', 'aikit' ); ?></a>
                    </div>
                </div>
            <?php } ?>

            <div class="row mb-2">
                <div class="col">
                    <span class="me-3"><?php echo esc_html__( 'Choose content type:', 'aikit' ); ?></span>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input aikit-repurposer-form-check-input" type="radio" name="aikit-repurposer-job-type" id="aikit-repurposer-job-type-url" value="url" <?php echo (($is_view && $job->job_type == self::JOB_TYPE_URL) || (!$is_view)) ? 'checked' : ''; ?> <?php echo $is_view ? 'disabled' : ''; ?>>
                        <label class="form-check-label" for="aikit-repurposer-job-type-url"><?php echo esc_html__( 'Post or article', 'aikit' ); ?></label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input aikit-repurposer-form-check-input" type="radio" name="aikit-repurposer-job-type" id="aikit-repurposer-job-type-youtube" value="youtube" <?php echo ($is_view && $job->job_type == self::JOB_TYPE_YOUTUBE) ? 'checked' : ''; ?> <?php echo $is_view ? 'disabled' : ''; ?>>
                        <label class="form-check-label" for="aikit-repurposer-job-type-youtube"><?php echo esc_html__( 'YouTube video', 'aikit' ); ?> <i class="bi bi-youtube"></i></label>
                        <a href="#" class="aikit-repurposer-how-to"><?php echo esc_html__( 'How to setup', 'aikit' ); ?></a>
                    </div>
                </div>
            </div>

            <div class="row mb-2 aikit-repurposer-how-to-content">
                <div class="col">
                    <p>
                        <?php echo esc_html__( 'To repurpose a YouTube video, AIKit needs to read the subtitles of the video. To do so, you need to connect to ', 'aikit' ); ?>
                        <a target="_blank" href="https://rapidapi.com/yashagarwal/api/subtitles-for-youtube"><?php echo esc_html__('Subtitles for YouTube.', 'aikit'); ?></a>
                        <?php echo esc_html__('This API is used to read YouTube video subtitles to allow you to fetch the content of videos and repurpose/spin them and create posts based on them in your website.', 'aikit'); ?>
                        <?php echo esc_html__('"Subtitles for YouTube" API offers a generous 100 free requests per day which will be enough for most users.', 'aikit'); ?>
                        <?php echo esc_html__('If you would like to repurpose videos, please', 'aikit'); ?>
                        <a href="https://rapidapi.com/yashagarwal/api/subtitles-for-youtube/pricing" target="_blank"><?php echo esc_html__('subscribe', 'aikit'); ?></a>
                        <?php echo esc_html__('to a plan, then enter your API key in the', 'aikit'); ?>
                        <a href="<?php echo admin_url( 'admin.php?page=aikit' ); ?>" ><?php echo esc_html__( 'settings page.', 'aikit' ); ?></a>.
                    </p>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="aikit-repurposer-url" placeholder="<?php echo esc_html__( 'URL of article, post or video', 'aikit' ); ?>" data-validation-message="<?php echo esc_html__( 'Please enter a valid URL', 'aikit' ); ?>" <?php echo $is_view ? 'disabled' : ''; ?> value="<?php echo $is_view ? $job->job_url : ''; ?>"/>
                        <label for="aikit-repurposer-url"><?php echo esc_html__( 'URL of article, post or video', 'aikit' ); ?></label>
                    </div>
                </div>
            </div>

            <?php if (!$is_view) { ?>
                <div class="row mb-2 aikit-repurposer-extracted-text-container">
                    <div class="col">
                        <small class="text-muted">
                            <?php echo esc_html__( 'Have a quick look at the extracted text to make sure it looks good and remove any unnecessary parts.', 'aikit' ); ?>
                            <?php echo esc_html__( 'Try to combine the text related to the same topic into one paragraph for better results.', 'aikit' ); ?>
                        </small>
                        <div class="form-floating">
                            <textarea class="form-control" id="aikit-repurposer-extracted-text" placeholder="<?php echo esc_html__( 'Extracted text', 'aikit' ); ?>" data-validation-message="<?php echo esc_html__( 'Extracted text', 'aikit' ); ?>"></textarea>
                            <label for="aikit-repurposer-extracted-text"><?php echo esc_html__( 'Extracted text', 'aikit' ); ?></label>
                        </div>
                    </div>
                </div>

            <?php } ?>

            <div class="row mb-2">
                <div class="col-9">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="aikit-repurposer-seo-keywords" placeholder="<?php echo esc_html__( 'SEO keywords to focus on (comma-separated)', 'aikit' ); ?>" value="<?php echo $is_view ? $job->keywords : ''; ?>" <?php echo $is_view ? 'disabled' : ''; ?>/>
                        <label for="aikit-repurposer-seo-keywords"><?php echo esc_html__( 'SEO keywords to focus on (comma-separated)', 'aikit' ); ?></label>
                    </div>
                </div>
                <div class="col pt-3">
                    <input type="checkbox" class="form-check-input" id="aikit-repurposer-include-featured-image" name="aikit-repurposer-include-featured-image" <?php echo $is_view ? 'disabled' : ''; ?> <?php echo $is_view && $job->include_featured_image ? 'checked' : ''; ?>/>
                    <label class="form-check-label aikit-repurposer" for="aikit-repurposer-include-featured-image"><?php echo esc_html__( 'Include featured image', 'aikit' ); ?></label>
                </div>
            </div>

            <div class="row mb-2 justify-content-md-center">
                <div class="col">
                    <div class="form-floating">
                        <input type="number" class="form-control" id="aikit-repurposer-articles" placeholder="<?php echo esc_html__( 'Posts to generate: ', 'aikit' ); ?>" min="1" max="10" step="1" value="<?php echo $is_view ? $job->number_of_articles : '1'; ?>" <?php echo $is_view ? 'disabled' : ''; ?>/>
                        <label for="aikit-repurposer-articles"><?php echo esc_html__( 'Posts to generate: ', 'aikit' ); ?></label>
                    </div>
                </div>
                <div class="col">
                    <div class="form-floating">
                        <select class="form-select" id="aikit-repurposer-post-type" name="aikit-repurposer-post-type" <?php echo $is_view ? 'disabled' : ''; ?>>
                            <?php foreach ($post_types as $type) { ?>
                                <option value="<?php echo esc_attr( $type->name ); ?>" <?php echo $is_view && $job->post_type == $type->name ? 'selected' : ''; ?>><?php echo esc_html( $type->labels->singular_name ); ?></option>
                            <?php } ?>
                        </select>
                        <label for="aikit-repurposer-post-type"><?php echo esc_html__( 'Post type', 'aikit' ); ?></label>
                    </div>
                </div>
                <div class="col">
                    <div class="form-floating">
                        <select class="form-select" id="aikit-repurposer-post-category" name="aikit-repurposer-post-category" <?php echo $is_view ? 'disabled' : ''; ?>>
                            <?php foreach (get_categories(['hide_empty' => false]) as $category) { ?>
                                <option value="<?php echo esc_attr( $category->term_id ); ?>" <?php echo $is_view && $job->post_category == $category->term_id ? 'selected' : ''; ?>><?php echo esc_html( $category->name ); ?></option>
                            <?php } ?>
                        </select>
                        <label for="aikit-repurposer-post-category"><?php echo esc_html__( 'Post category', 'aikit' ); ?></label>
                    </div>
                </div>
                <div class="col">
                    <div class="form-floating">
                        <select class="form-select" id="aikit-repurposer-post-status" name="aikit-repurposer-post-status" <?php echo $is_view ? 'disabled' : ''; ?>>
                            <?php foreach ($available_statuses as $status => $status_name) { ?>
                                <option value="<?php echo esc_attr( $status ); ?>" <?php echo $is_view && $job->post_status == $status ? 'selected' : ''; ?>><?php echo esc_html( $status_name ); ?></option>
                            <?php } ?>
                        </select>
                        <label for="aikit-repurposer-post-status"><?php echo esc_html__( 'Post status', 'aikit' ); ?></label>
                    </div>
                </div>
            </div>

            <?php if (!$is_view) { ?>
            <div class="row mb-2 justify-content-end text-end">
                <div class="col">
                    <button id="aikit-repurposer-generate" class="btn btn btn-primary ms-2" type="submit"><i class="bi bi-arrow-clockwise"></i> <?php echo esc_html__( 'Repurpose', 'aikit' ); ?></button>
                </div>
            </div>
            <?php } ?>

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
                            $prompts = $is_view ? json_decode($job->prompts) : $this->get_prompts();

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
                                echo '<label for="aikit-repurposer-prompt-'.$id.'" class="aikit-repurposer"><strong>'. $id  .':</strong></label>';
                                echo '<span class="aikit-repurposer-prompt-description">' . esc_html__(' uses', 'aikit')  . $placeholderString . '</span>';
                                echo '<textarea ' .  ($is_view ? 'disabled' : '')  .  ' class="form-control aikit-repurposer-prompt" data-prompt-id="' . $id . '" id="aikit-repurposer-prompt-'.$id.'" name="aikit-repurposer-prompt-'.$id.'" rows="3">'. $prompt .'</textarea>';
                                echo '</div>';
                            }
                            ?>
                            <p class="aikit-repurposer-placeholder-descriptions">
                                <?php echo esc_html__( 'You can use the following placeholders in your prompts:', 'aikit' ); ?>
                            </p>

                            <ul class="aikit-repurposer-placeholder-descriptions">
                                <li><code>[[text]]</code> - <?php echo esc_html__( 'this will be replaced with text needed for that prompt.', 'aikit' ); ?></li>
                                <li><code>[[summaries]]</code> - <?php echo esc_html__( 'this will be replaced with the combination of all the summaries of all parts of the post.', 'aikit' ); ?></li>
                                <li><code>[[keywords]]</code> - <?php echo esc_html__( 'this will be replaced with the SEO keywords you entered.', 'aikit' ); ?></li>
                            </ul>

                        </div>
                    </div>
                </div>
            </div>

            <input type="hidden" id="aikit-repurposer-translations" value="<?php echo esc_attr(json_encode($translations)); ?>">


        </form>
        <?php
    }

    private function render_listing_tab()
    {
        $paged = isset($_GET['paged']) ? intval($_GET['paged']) : 1;
        $per_page = 25;
        $html = '<table class="table" id="aikit-repurposer-jobs">
            <thead>
            <tr>
                <th scope="col">' . esc_html__('URL', 'aikit') . '</th>
                <th scope="col">' . esc_html__('Job Type', 'aikit') . '</th>
                <th scope="col">' . esc_html__('Keywords', 'aikit') . '</th>
                <th scope="col">' . esc_html__('Done', 'aikit') . '</th>
                <th scope="col">' . esc_html__('Had errors', 'aikit') . '</th>
                <th scope="col">' . esc_html__('Date created', 'aikit') . '</th>               
                <th scope="col">' . esc_html__('Actions', 'aikit') . '</th>
            </tr>
            </thead>
            <tbody>';

        // get all jobs from DB
        global $wpdb;
        $table_name = $wpdb->prefix . self::TABLE_NAME;

        // prepared statement to prevent SQL injection with pagination
        $jobs = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name ORDER BY id DESC LIMIT %d, %d", ($paged - 1) * $per_page, $per_page));

        if (empty($jobs)) {
            $html .= '<tr>
                <td colspan="7">' . esc_html__('No entries found', 'aikit') . '</td>
            </tr>';
        }

        $page_url = get_admin_url() . 'admin.php?page=aikit_repurpose&action=jobs';
        $delete_url = get_site_url() . '/?rest_route=/aikit/repurposer/v1/delete';

        $date_format = get_option('time_format') . ' ' . get_option('date_format');

        foreach ($jobs as $job) {
            $current_page_url = $page_url . '&job_id=' . $job->id;
            $html .= '<tr>
                <td>' . '<a href="' . $current_page_url . '">' . esc_html($job->job_url) . '</a></td>
                <td>' . strtoupper($job->job_type) . '</td>
                <td>' . (empty($job->keywords) ? '-' : $job->keywords) . '</td>
                <td>' . ($job->finished_at !== null ? ('<span class="badge badge-pill badge-dark aikit-badge-active">' . __('Yes', 'aikit')) : ('<span class="badge badge-pill badge-dark aikit-badge-inactive">' . __('No', 'aikit'))) . '</span></td>
                <td>' . ($job->error_count > 0 ? ('<span class="badge text-bg-danger aikit-badge-danger">' . __('Yes', 'aikit')) : ('<span class="badge badge-pill badge-dark aikit-badge-success">' . __('No', 'aikit'))) . '</span></td>
                <td>' . (empty($job->date_created) ? '-' : date($date_format, strtotime($job->date_created))) . '</td>               
                <td>
                    <a href="' . $page_url . '&job_id=' . $job->id . '" title="' . __('View', 'aikit') . '" class="aikit-repurposer-action" data-id="' . $job->id . '"><i class="bi bi-eye-fill"></i></a>
                    <a href="' . $delete_url . '" title="' . __('Delete', 'aikit') . '" class="aikit-repurposer-jobs-delete aikit-repurposer-action" data-confirm-message="' . __('Are you sure you want to delete this repurposing job?', 'aikit') . '" data-id="' . $job->id . '"><i class="bi bi-trash-fill"></i></a>
                </td>
            </tr>';
        }

        // pagination
        $total_jobs = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
        $total_pages = ceil($total_jobs / $per_page);
        $html .= '<tr>
            <td colspan="7">';

        // previous page

        $html .= '<div class="aikit-repurposer-jobs-pagination">';
        if ($paged > 1) {
            $html .= '<a href="' . $page_url . '&paged=' . ($paged - 1) . '">' . __('« Previous', 'aikit') . '</a>';
        }

        for ($i = 1; $i <= $total_pages; $i++) {
            // add class to current page
            $current_page_class = '';
            if ($paged == $i) {
                $current_page_class = 'aikit-repurposer-jobs-pagination-current';
            }

            $html .= '<a class="' . $current_page_class . '" href="' . $page_url . '&paged=' . $i . '" data-page="' . $i . '">' . $i . '</a>';
        }

        // next page
        if ($paged < $total_pages) {
            $html .= '<a href="' . $page_url . '&paged=' . ($paged + 1) . '">' . __('Next »', 'aikit') . '</a>';
        }

        $html .= '</div>';

        $html .= '</td>
            </tr>';
        $html .= '</tbody>
        
        </table>';

        echo $html;
    }

    private function render_view_tab()
    {
        $id = intval($_GET['job_id']);

        if (empty($id)) {
            echo '<div class="alert alert-danger">' . esc_html__('Invalid ID', 'aikit') . '</div>';
            return;
        }

        $job = $this->get_job($id);

        if (empty($job)) {
            echo '<div class="alert alert-danger">' . esc_html__('Job not found', 'aikit') . '</div>';
            return;
        }

        $logs = json_decode($job->logs, true);
        $generated_posts = $this->get_generated_posts_by_job_id($id);

        ?>
        <div class="aikit-repurposer-view">
            <?php
                $this->show_job_form(true, $job);
            ?>
            <ul class="nav nav-tabs mt-3" id="aikit-repurposer-tabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#aikit-repurposer-tabs-posts" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true"><?php echo esc_html__( 'Generated Posts', 'aikit' ); ?></button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="home-tab" data-bs-toggle="tab" data-bs-target="#aikit-repurposer-tabs-extracted-texts" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true"><?php echo esc_html__( 'Extracted text', 'aikit' ); ?></button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#aikit-repurposer-tabs-logs" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false"><?php echo esc_html__( 'Logs', 'aikit' ); ?></button>
                </li>
            </ul>
            <div class="tab-content mt-2" id="aikit-repurposer-tab-panes">
                <div class="tab-pane fade show active" id="aikit-repurposer-tabs-posts" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                    <?php
                    $html = '<table class="table" id="aikit-auto-writer-posts">
                                    <thead>
                                    <tr>
                                        <th scope="col">'.esc_html__( 'Type', 'aikit' ).'</th>
                                        <th scope="col">'.esc_html__( 'Title', 'aikit' ).'</th>
                                        <th scope="col">'.esc_html__( 'Date created', 'aikit' ).'</th>
                                    </tr>
                                    </thead>
                                    <tbody>';

                    if (count($generated_posts) === 0) {
                        $html .= '<tr><td colspan="3">'.esc_html__( 'No posts generated yet.', 'aikit' ).'</td></tr>';
                    }

                    foreach ($generated_posts as $post) {
                        $html .= '<tr>
                                        <td>'.esc_html($post->post_type).'</td>
                                        <td><a href="'.esc_url(get_permalink($post->ID)).'">'.esc_html($post->post_title).'</a></td>
                                        <td>'.esc_html($post->post_date).'</td>
                                    </tr>';
                    }

                    $html .= '</tbody></table>';

                    echo $html;

                    ?>
                </div>
                <div class="tab-pane fade" id="aikit-repurposer-tabs-extracted-texts" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                    <?php

                    $extracted_text = $job->extracted_text;
                    ?>

                    <textarea class="form-control" rows="10" disabled><?php echo esc_html($extracted_text); ?></textarea>

                </div>
                <div class="tab-pane fade" id="aikit-repurposer-tabs-logs" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                    <?php

                    if (count($logs) === 0) {
                        echo esc_html__( 'No logs found.', 'aikit' );
                    }

                    foreach ($logs as $log) {
                        ?>
                        <pre><code class="json"><?php echo esc_html(json_encode($log, JSON_PRETTY_PRINT)); ?></code></pre>
                        <?php
                    }
                    ?>

                </div>
            </div>
        </div>
        <?php
    }

    private function get_job($id)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . self::TABLE_NAME;

        $generator = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id));

        if (empty($generator)) {
            return false;
        }

        return $generator;
    }

    private function get_generated_posts_by_job_id($id)
    {
        $args = [
            'post_type' => 'any',
            'post_status' => 'any',
            'meta_query' => [
                [
                    'key' => self::POST_META_AIKIT_REPURPOSER_JOB_ID,
                    'value' => $id,
                    'compare' => '=',
                ],
                [
                    'key' => self::POST_META_AIKIT_REPURPOSER_JOB_ID,
                    'compare' => 'EXISTS',
                ],
            ],
        ];

        $query = new WP_Query($args);

        return $query->posts;
    }

    public function enqueue_scripts($hook)
    {
        if ( 'aikit_page_aikit_repurpose' !== $hook ) {
            return;
        }

        $version = aikit_get_plugin_version();
        if ($version === false) {
            $version = rand( 1, 10000000 );
        }

        wp_enqueue_style( 'aikit_bootstrap_css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css', array(), $version );
        wp_enqueue_style( 'aikit_bootstrap_icons_css', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.4/font/bootstrap-icons.css', array(), $version );
        wp_enqueue_style( 'aikit_repurposer_css', plugins_url( '../../css/repurposer.css', __FILE__ ), array(), $version );

        wp_enqueue_script( 'aikit_bootstrap_js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js', array(), $version );
        wp_enqueue_script( 'aikit_jquery_ui_js', 'https://code.jquery.com/ui/1.13.2/jquery-ui.min.js', array('jquery'), $version );
        wp_enqueue_script( 'aikit_repurposer_js', plugins_url( '../../js/repurposer.js', __FILE__ ), array( 'jquery' ), array(), $version );
    }

    public function do_db_migration()
    {
        global $wpdb;

        $table_name = $wpdb->prefix . self::TABLE_NAME;
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            job_type varchar(255) NOT NULL,
            job_url mediumtext NOT NULL,
            extracted_text TEXT NULL,
            output_post_type varchar(255) NOT NULL,
            output_post_status varchar(255) NOT NULL,
            output_post_author mediumint(9) NOT NULL,
            output_post_category mediumint(9) NOT NULL,
            keywords TEXT DEFAULT NULL,
            number_of_articles mediumint(9) NOT NULL,
            include_featured_image BOOLEAN NOT NULL,
            is_active BOOLEAN NOT NULL,
            prompts TEXT NOT NULL,
            finished_at datetime DEFAULT NULL,
            date_created datetime DEFAULT NULL,
            date_modified datetime DEFAULT NULL,
            logs TEXT NOT NULL,
            is_running BOOLEAN DEFAULT FALSE,
            error_count mediumint(9) DEFAULT 0,
            PRIMARY KEY  (id)
        );";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }

    private function get_prompts()
    {
        $lang = get_option('aikit_setting_openai_language', 'en');

        return AIKIT_REPURPOSER_PROMPTS[$lang]['prompts'];
    }

    private function get_paragraphs($string, $statements_per_paragraph = 5)
    {
        $paragraphs = explode("\n\n", $string);

        $result = [];
        foreach ($paragraphs as $paragraph) {
            $word_count = aikit_calculate_word_count_utf8($paragraph);
            if ($word_count > 500) {
                $split_string = $this->cut_long_string_into_smaller_chunks($paragraph, $statements_per_paragraph);
                $split_string = str_replace('.', '. ', $split_string);
                $result = array_merge($result, $split_string);
            } else {
                $result[] = $paragraph;
            }
        }

        return $result;
    }

    private function cut_long_string_into_smaller_chunks($string, $statements_per_paragraph = 5) {
        // Split the string into an array of lines
        $lines = preg_split('/(\n|\.)\s*/', $string, -1, PREG_SPLIT_DELIM_CAPTURE);

        $chunks = array();
        $chunk = '';
        $statement_count = 0;

        // Loop through each line and add it to the current chunk
        foreach ($lines as $line) {
            // If the line is empty, skip it
            if (trim($line) === '') {
                continue;
            }

            // Add the line to the current chunk
            $chunk .= '' . $line;

            // Count the number of statements in the line
            $statement_count += substr_count($line, '.') + substr_count($line, "\n");

            // If the chunk now has at least 5 statements, add it to the list of chunks
            if ($statement_count >= $statements_per_paragraph) {
                $chunks[] = $chunk;
                $chunk = '';
                $statement_count = 0;
            }
        }

        // If there is a partial chunk remaining, add it to the list of chunks
        if ($chunk !== '') {
            $chunks[] = $chunk;
        }

        return $chunks;
    }
}