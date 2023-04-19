<?php

class AIKIT_Auto_Writer
{
    // singleton
    private static $instance = null;

    public static function get_instance() {
        if (self::$instance == null) {
            self::$instance = new AIKIT_Auto_Writer();
        }
        return self::$instance;
    }

    private function __construct()
    {
        add_action( 'rest_api_init', function () {
            register_rest_route( 'aikit/auto-writer/v1', '/write', array(
                'methods' => 'POST',
                'callback' => array($this, 'generate_post'),
                'permission_callback' => function () {
                    return is_user_logged_in() && current_user_can( 'edit_posts' );
                }
            ));

            register_rest_route( 'aikit/auto-writer/v1', '/list', array(
                'methods' => 'GET',
                'callback' => array($this, 'list_posts'),
                'permission_callback' => function () {
                    return is_user_logged_in() && current_user_can( 'edit_posts' );
                }
            ));
        });
    }

    public function render()
    {
        $post_types = get_post_types( array( 'public' => true ), 'objects');
        $selected_language = aikit_get_language_used();
        $languages = AIKit_Admin::instance()->get_languages();
        $selected_language_name = $languages[$selected_language]['name'] ?? 'English';

        ?>
        <h2><?php echo esc_html__( 'AIKit Auto Writer', 'aikit' ); ?></h2>
        <p><?php echo esc_html__( 'AIKit Auto Writer is a tool helps you write drafts quickly, but please review and edit before publishing for best results. This is not a substitute for human editing, but a drafting aid. Happy writing!', 'aikit' ); ?></p>
        <form id="aikit-auto-writer-form" action="<?php echo get_site_url(); ?>/?rest_route=/aikit/auto-writer/v1/write" method="post">
            <div class="row">
                <div class="col">
                    <p>
                        <?php echo esc_html__( 'Selected language:', 'aikit' ); ?>
                        <span class="badge badge-pill badge-dark aikit-badge"><?php echo $selected_language_name?></span>
                        <a href="<?php echo admin_url( 'admin.php?page=aikit' ); ?>" ><?php echo esc_html__( 'Change language', 'aikit' ); ?></a>
                    </p>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col">
                    <div class="form-floating">
                        <textarea data-validation-message="<?php echo esc_html__( 'Please enter a brief description of the topic you want to write about.', 'aikit' ); ?>" class="form-control" placeholder="<?php echo esc_html__( 'Please enter a brief description of the topic you want to write about.', 'aikit' ); ?>" id="aikit-auto-writer-topic" name="aikit-auto-writer-topic" minlength="1"></textarea>
                        <label for="aikit-auto-writer-topic"><?php echo esc_html__( 'Write a brief description of the topic you want to write about...', 'aikit' ); ?></label>
                    </div>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="aikit-auto-writer-seo-keywords" placeholder="<?php echo esc_html__( 'SEO keywords to focus on (comma-separated)', 'aikit' ); ?>">
                        <label for="aikit-auto-writer-seo-keywords"><?php echo esc_html__( 'SEO keywords to focus on (comma-separated)', 'aikit' ); ?></label>
                    </div>
                </div>
            </div>

            <div class="row mb-2 justify-content-md-center">
                <div class="col">
                    <div class="form-floating">
                        <select class="form-select" id="aikit-auto-writer-post-type" name="aikit-auto-writer-post-type">
                            <?php foreach ($post_types as $post_type) { ?>
                                <option value="<?php echo esc_attr( $post_type->name ); ?>"><?php echo esc_html( $post_type->labels->singular_name ); ?></option>
                            <?php } ?>
                        </select>
                        <label for="aikit-auto-writer-post-type"><?php echo esc_html__( 'Post type', 'aikit' ); ?></label>
                    </div>
                </div>
                <div class="col">
                    <div class="form-floating">
                        <select class="form-select" id="aikit-auto-writer-post-category" name="aikit-auto-writer-post-category">
                            <?php foreach (get_categories() as $category) { ?>
                                <option value="<?php echo esc_attr( $category->term_id ); ?>"><?php echo esc_html( $category->name ); ?></option>
                            <?php } ?>
                        </select>
                        <label for="aikit-auto-writer-post-category"><?php echo esc_html__( 'Post category', 'aikit' ); ?></label>
                    </div>
                </div>
                <div class="col">
                    <div class="form-floating">
                        <select class="form-select" id="aikit-auto-writer-post-status" name="aikit-auto-writer-post-status">
                            <option value="draft"><?php echo esc_html__( 'Draft', 'aikit' ); ?></option>
                            <option value="publish"><?php echo esc_html__( 'Published', 'aikit' ); ?></option>
                        </select>
                        <label for="aikit-auto-writer-post-status"><?php echo esc_html__( 'Post status', 'aikit' ); ?></label>
                    </div>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col d-flex justify-content-center">
                    <div class="d-inline m-2 ">
                        <label for="aikit-auto-writer-articles" class="aikit-auto-writer"><?php echo esc_html__( 'Articles: ', 'aikit' ); ?></label>
                        <input type="number" id="aikit-auto-writer-articles" name="aikit-auto-writer-articles" min="1" max="10" value="1" step="1">
                    </div>
                    <div class="d-inline m-2 ">
                        <label for="aikit-auto-writer-sections" class="aikit-auto-writer"><?php echo esc_html__( 'Sections per article: ', 'aikit' ); ?></label>
                        <input type="number" id="aikit-auto-writer-sections" name="aikit-auto-writer-sections" min="1" max="20" value="3" step="1">
                    </div>
                    <div class="d-inline m-2 ">
                        <label for="aikit-auto-writer-words-per-section" class="aikit-auto-writer"><?php echo esc_html__( 'Maximum words per section: ', 'aikit' ); ?></label>
                        <input type="number" id="aikit-auto-writer-words-per-section" name="aikit-auto-writer-words-per-section" min="100" max="3000" value="800" step="1">
                    </div>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col d-flex justify-content-center">
                    <div class="d-inline m-2 ">
                        <input type="checkbox" class="form-check-input" id="aikit-auto-writer-include-outline" name="aikit-auto-writer-include-outline">
                        <label class="form-check-label aikit-auto-writer" for="aikit-auto-writer-include-outline"><?php echo esc_html__( 'Include outline', 'aikit' ); ?></label>
                    </div>

                    <div class="d-inline m-2">
                        <input type="checkbox" class="form-check-input" id="aikit-auto-writer-include-featured-image" name="aikit-auto-writer-include-featured-image">
                        <label class="form-check-label aikit-auto-writer" for="aikit-auto-writer-include-featured-image"><?php echo esc_html__( 'Include featured article image', 'aikit' ); ?></label>
                    </div>

                    <div class="d-inline m-2">
                        <input type="checkbox" class="form-check-input" id="aikit-auto-writer-include-section-images" name="aikit-auto-writer-include-section-images">
                        <label class="form-check-label aikit-auto-writer" for="aikit-auto-writer-include-section-images"><?php echo esc_html__( 'Include section images', 'aikit' ); ?></label>
                    </div>

                    <div class="d-inline m-2">
                        <input type="checkbox" class="form-check-input" id="aikit-auto-writer-include-conclusion" name="aikit-auto-writer-include-conclusion">
                        <label class="form-check-label aikit-auto-writer" for="aikit-auto-writer-include-conclusion"><?php echo esc_html__( 'Include conclusion', 'aikit' ); ?></label>
                    </div>

                    <div class="d-inline m-2">
                        <input type="checkbox" class="form-check-input" id="aikit-auto-writer-include-tldr" name="aikit-auto-writer-include-tldr">
                        <label class="form-check-label aikit-auto-writer" for="aikit-auto-writer-include-tldr"><?php echo esc_html__( 'Include TL;DR', 'aikit' ); ?></label>
                    </div>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col d-flex justify-content-end">
                    <button id="aikit-auto-writer-generate" class="btn btn-primary" type="submit"><img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . '../../icons/aikit.svg' ); ?>"/><?php echo esc_html__( 'Generate', 'aikit' ); ?></button>
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
                                $prompts = $this->get_prompts();

                                foreach ($prompts as $id => $promptObject) {
                                    $prompt = $promptObject['prompt'];

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
                                    echo  '<span class="aikit-auto-writer-prompt-description">' . esc_html__(' uses', 'aikit')  . $placeholderString . '</span>';
                                    echo '<textarea class="form-control aikit-auto-writer-prompt" data-prompt-id="' . $id . '" id="aikit-auto-writer-prompt-'.$id.'" name="aikit-auto-writer-prompt-'.$id.'" rows="3">'. $promptObject['prompt'] .'</textarea>';
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

        </form>

        <?php

        echo $this->get_posts();
    }

    public function list_posts($data)
    {
        return new WP_REST_Response([
            'body' => $this->get_posts($data['paged'] ?? 1),
        ], 200);
    }

    private function get_posts($page = 1)
    {
        $html = '<table class="table" id="aikit-auto-writer-posts">
            <thead>
            <tr>
                <th scope="col">'.esc_html__( 'Type', 'aikit' ).'</th>
                <th scope="col">'.esc_html__( 'Title', 'aikit' ).'</th>
                <th scope="col">'.esc_html__( 'Date created', 'aikit' ).'</th>
            </tr>
            </thead>
            <tbody>';

        $posts = new WP_Query(array(
            'post_type' => 'any',
            'post_status' => 'any',
            'posts_per_page' => 50,
            'paged' => $page,
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => 'aikit_auto_written',
                    'compare' => '=',
                    'value' => '1'
                ),
                array(
                    'key' => 'aikit_auto_written',
                    'compare' => 'EXISTS'
                )
            )
        ));

        while ( $posts->have_posts() ) {
            $posts->the_post();
            $html .= '<tr>
                <td>'.esc_html( get_post_type_object( get_post_type() )->labels->singular_name).'</td>
                <td><a target="_blank" href="'.esc_url( get_the_permalink() ).'">'.esc_html( get_the_title() ).'</a></td>
                <td>'.esc_html( get_the_date() ).'</td>
            </tr>';
        }

        if ( $posts->found_posts === 0 ) {
            $html .= '<tr>
                <td colspan="3">'.esc_html__( 'No auto-written posts found.', 'aikit' ).'</td>
            </tr>';
        }

        wp_reset_postdata();

        $big = 999999999;
        $html .= '<tr class="aikit-auto-writer-nav">
            <td colspan="3">'.
            paginate_links(array(
                'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                'total' => $posts->max_num_pages,
                'current' => $page,
                'prev_text' => __('« Previous', 'aikit'),
                'next_text' => __('Next »', 'aikit'),
            ))
            .'</td>
        </tr>
    </tbody>
</table>';

        return $html;
    }

    public function generate_post($data)
    {
        set_time_limit(0);

        $topic = $data['topic'];
        $include_outline = boolval($data['include_outline']);
        $include_featured_image = boolval($data['include_featured_image']);
        $include_section_images = boolval($data['include_section_images']);
        $include_tldr = boolval($data['include_tldr']);
        $include_conclusion = boolval($data['include_conclusion']);
        $post_type = $data['post_type'];
        $post_category = $data['post_category'];
        $post_status = $data['post_status'];
        $number_of_sections = intval($data['number_of_sections']) ?? 3;
        $section_max_length_in_words = ($data['section_max_length'] ?? 1000);
        $section_max_tokens = intval($section_max_length_in_words * 1.33);
        $temperature = $data['temperature'] ?? 0.7;
        $number_of_articles = intval($data['number_of_articles']) ?? 1;
        $seo_keywords = $data['seo_keywords'] ?? '';
        $prompts = $data['prompts'];


        if (empty(trim($topic))) {
            return new WP_Error('aikit_auto_writer_missing_topic', __('Please enter a topic.', 'aikit'));
        }

        $resultPosts = [];

        $prompt_name_suffix = '';
        if (!empty(trim($seo_keywords))) {
            $prompt_name_suffix = '-with-seo-keywords';
        }

        try {
            for ($i=0; $i < $number_of_articles; $i++) {

                $content = '';
                $generated_segments = [];

                $section_headlines = aikit_openai_text_generation_request(
                    $this->build_prompt($prompts['section-headlines' . $prompt_name_suffix], array(
                            'description' => $topic,
                            'number-of-headlines' => $number_of_sections,
                            'keywords' => $seo_keywords,
                        )
                    ), 1000, $temperature);

                $article_intro = aikit_openai_text_generation_request(
                    $this->build_prompt($prompts['article-intro' . $prompt_name_suffix], array(
                            'description' => $topic,
                            'section-headlines' => $section_headlines,
                            'keywords' => $seo_keywords,
                        )
                    ), 1000, $temperature);

                $content = $this->add_text($content, $article_intro);
                $generated_segments['article-intro'] = $article_intro;

                $section_headlines = explode("\n", $section_headlines);
                $section_headlines = array_filter($section_headlines, function ($headline) {
                    return strlen($headline) > 0;
                });
                $section_headlines = array_slice($section_headlines, 0, $number_of_sections);

                if ($include_outline) {
                    $content = $this->add_outline($content, $section_headlines);
                    $generated_segments['section-headlines'] = $section_headlines;
                }

                $title = aikit_openai_text_generation_request(
                    $this->build_prompt($prompts['article-title' . $prompt_name_suffix], array(
                            'description' => $topic,
                            'section-headlines' => implode("\n", $section_headlines),
                            'keywords' => $seo_keywords,
                        )
                    ), 1000, $temperature
                );

                $title = $this->clean_title($title);

                $section_summaries = [];



                foreach ($section_headlines as $headline) {
                    $section_content = aikit_openai_text_generation_request(
                        $this->build_prompt($prompts['section' . $prompt_name_suffix], array(
                                'description' => $topic,
                                'section-headline' => $headline,
                                'keywords' => $seo_keywords,
                            )
                        ), $section_max_tokens, $temperature);

                    if ($include_tldr) {
                        $section_summaries[] = aikit_openai_text_generation_request(
                            $this->build_prompt($prompts['section-summary' . $prompt_name_suffix], array(
                                    'section' => $section_content,
                                    'keywords' => $seo_keywords,
                                )
                            ), 1000, $temperature);
                    }

                    $content = $this->add_section_anchor($content, $headline);
                    $content = $this->add_subtitle($content, $headline);

                    if ($include_section_images) {
                        $section_image = aikit_openai_text_generation_request(
                            $this->build_prompt($prompts['image'], array(
                                    'text' => $section_content,
                                )
                            ), 1000, $temperature);

                        $images = aikit_openai_image_generation_request($section_image);
                        $content = $this->add_images($content, $images);
                    }

                    $content = $this->add_text($content, $section_content);
                    $generated_segments['section-' . $headline] = $section_content;
                }

                if ($include_conclusion) {
                    $article_conclusion = aikit_openai_text_generation_request(
                        $this->build_prompt($prompts['article-conclusion' . $prompt_name_suffix], array(
                                'description' => $topic,
                                'section-headlines' => implode("\n", $section_headlines),
                                'keywords' => $seo_keywords,
                            )
                        ), 1000, $temperature);

                    $content = $this->add_text($content, $article_conclusion);
                    $generated_segments['article-conclusion'] = $article_conclusion;
                }

                if ($include_tldr) {
                    $text = implode("\n", $section_summaries);

                    $tldr_for_all_sections = aikit_openai_text_generation_request(
                        $this->build_prompt($prompts['tldr' . $prompt_name_suffix], array(
                                'text' => $text,
                                'keywords' => $seo_keywords,
                            )
                        ), 1000, $temperature);

                    $content = $this->prepend_text($content, $tldr_for_all_sections);
                    $generated_segments['tldr'] = $tldr_for_all_sections;
                }

                $post = array(
                    'post_title' => $title,
                    'post_content' => $content,
                    'post_status' => $post_status,
                    'post_author' => get_current_user_id(),
                    'post_type' => $post_type,
                    'post_category' => array($post_category)
                );

                $post_id = wp_insert_post($post);

                if (!$post_id) {
                    return new WP_Error( 'auto_writer_error', json_encode([
                        'message' => 'Failed to create post',
                    ]), array( 'status' => 500 ) );
                }

                if ($include_featured_image) {
                    $featured_image = aikit_openai_text_generation_request(
                        $this->build_prompt($prompts['image'], array(
                                'text' => $article_intro,
                            )
                        ), 1000, $temperature);

                    $images = aikit_openai_image_generation_request($featured_image);
                    if (count($images) > 0) {
                        set_post_thumbnail($post_id, $images[0]['id']);
                    }
                }

                add_post_meta($post_id, 'aikit_auto_written', true);

                $post_type_obj = get_post_type_object( get_post_type() );

                $resultPosts[] = [
                    'post_id' => $post_id,
                    'post_title' => $title,
                    'post_link' => get_permalink($post_id),
                    'post_date' => get_the_date('Y-m-d H:i:s', $post_id),
                    'post_type' => $post_type_obj->labels->singular_name ?? $post_type,
                    'generated_segments' => $generated_segments,
                ];
            }

        } catch (Exception $e) {
            return new WP_Error( 'openai_error', json_encode([
                'message' => 'error while calling openai',
                'responseBody' => $e->getMessage(),
            ]), array( 'status' => 500 ) );
        }

        return new WP_REST_Response([
            'posts' => $resultPosts,
        ], 200);
    }

    private function build_prompt($prompt, $keyValueArray)
    {
        foreach ($keyValueArray as $key => $value) {
            $prompt = str_replace('[[' . $key . ']]', $value, $prompt);
        }

        return $prompt;
    }

    private function get_prompts()
    {
        $lang = get_option('aikit_setting_openai_language', 'en');

        return AIKIT_AUTO_GENERATOR_PROMPTS[$lang]['prompts'];
    }

    private function add_images($content, $images)
    {
        foreach ($images as $image) {
            $content .= '<img src="' . $image['url'] . '" class="wp-image-' . $image['id'] . '" />';
        }

        return $content;
    }


    public function enqueue_scripts($hook)
    {
        // check if the page is not aikit_auto_writer
        if ( 'aikit_page_aikit_auto_writer' !== $hook ) {
            return;
        }

        $version = aikit_get_plugin_version();
        if ($version === false) {
            $version = rand( 1, 10000000 );
        }

        wp_enqueue_style( 'aikit_bootstrap_css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css', array(), $version );
        wp_enqueue_style( 'aikit_auto_writer_css', plugins_url( '../../css/auto-writer.css', __FILE__ ), array(), $version );

        wp_enqueue_script( 'aikit_bootstrap_js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js', array(), $version );
        wp_enqueue_script( 'aikit_jquery_ui_js', 'https://code.jquery.com/ui/1.13.2/jquery-ui.min.js', array('jquery'), $version );
        wp_enqueue_script( 'aikit_auto_writer_js', plugins_url( '../../js/auto-writer.js', __FILE__ ), array( 'jquery' ), array(), $version );
    }

    private function add_outline($content, $section_headlines)
    {
        // add section_headlines in an ul list to the content
        $ul = '<ul>';
        foreach ($section_headlines as $headline) {
            $id = $this->generate_link_anchor_id($headline);
            $ul .= '<li>' . '<a href="#' . $id . '">' . htmlentities($headline) . '</a>' . '</li>';
        }

        $ul .= '</ul>';

        return $content . $ul;
    }

    private function add_section_anchor($content, $headline)
    {
        $id = $this->generate_link_anchor_id($headline);
        return $content . '<section id="' . $id . '"></section>';
    }

    private function generate_link_anchor_id($headline)
    {
        return strtolower(str_replace(' ', '-', $headline));
    }

    private function prepend_text($content, $text)
    {
        // divide text into paragraphs
        $paragraphs = explode("\n", $text);

        $text_to_prepend = '';
        foreach ($paragraphs as $paragraph) {
            if (empty(trim($paragraph))) {
                continue;
            }

            $text_to_prepend .= '<p>' . htmlentities($paragraph) . '</p>';
        }

        return $text_to_prepend . $content;
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

    private function clean_title($title)
    {
        // remove " and ' from the beginning and end of the title
        $title = trim($title, '"');
        return trim($title, "'");

    }

    private function add_subtitle($content, $subtitle)
    {
        return $content . '<h2>' . htmlentities($subtitle) . '</h2>';
    }
}
