<?php

/*
 * this class should be used to stores properties and methods shared by the
 * admin and public side of wordpress
 */
class Daim_Shared
{

	//properties used in add_autolinks()
	private $ail_id;
	private $ail_a;
	private $parsed_autolink;
    private $parsed_post_type = null;
	private $max_number_autolinks_per_post;
	private $same_url_limit = null;
	private $autolinks_ca = null;
	private $pb_id = null;
	private $pb_a = null;

    //regex
	public $regex_list_of_gutenberg_blocks = '/^(\s*([A-Za-z0-9-\/]+\s*,\s*)+[A-Za-z0-9-\/]+\s*|\s*[A-Za-z0-9-\/]+\s*)$/';
    public $regex_list_of_post_types = '/^(\s*([A-Za-z0-9_-]+\s*,\s*)+[A-Za-z0-9_-]+\s*|\s*[A-Za-z0-9_-]+\s*)$/';
    public $regex_number_ten_digits = '/^\s*\d{1,10}\s*$/';
    public $regex_capability = '/^\s*[A-Za-z0-9_]+\s*$/';
    
    protected static $instance = null;

    private $data = array();

    private function __construct()
    {

	    add_action('init', array($this, 'cron_schedules_init') );

	    add_action( 'daextdaim_cron_hook', array( $this, 'daextdaim_cron_exec' ) );

	    //Set plugin textdomain
        load_plugin_textdomain('daim', false, 'interlinks-manager/lang/');
        
        $this->data['slug'] = 'daim';
        $this->data['ver'] = '1.32';
        $this->data['dir'] = substr(plugin_dir_path(__FILE__), 0, -7);
        $this->data['url'] = substr(plugin_dir_url(__FILE__), 0, -7);

        add_action('delete_term', array($this, 'delete_term_action'), 10, 3);

        //Here are stored the plugin option with the related default values
        $this->data['options'] = [

            //database version -----------------------------------------------------
            $this->get('slug') . "_database_version" => "0",

            //AIL ------------------------------------------------------------------
            $this->get('slug') . '_default_category_id' => "0",
            $this->get('slug') . '_default_title' => "",
            $this->get('slug') . '_default_open_new_tab' => "0",
            $this->get('slug') . '_default_use_nofollow' => "0",
            $this->get('slug') . '_default_activate_post_types' => ['post', 'page'],
            $this->get('slug') . '_default_categories' => "",
            $this->get('slug') . '_default_tags' => "",
            $this->get('slug') . '_default_term_group_id' => "",
            $this->get('slug') . '_default_case_insensitive_search' => "0",
            $this->get('slug') . '_default_string_before' => "1",
            $this->get('slug') . '_default_string_after' => "1",
            $this->get('slug') . '_default_keyword_before' => "",
            $this->get('slug') . '_default_keyword_after' => "",
            $this->get('slug') . '_default_max_number_autolinks_per_keyword' => "100",
            $this->get('slug') . '_default_priority' => "0",

            //suggestions
            $this->get('slug') . '_suggestions_pool_post_types' => ['post', 'page'],
            $this->get('slug') . '_suggestions_pool_size' => 50,
            $this->get('slug') . '_suggestions_titles' => "consider",
            $this->get('slug') . '_suggestions_categories' => "consider",
            $this->get('slug') . '_suggestions_tags' => "consider",
            $this->get('slug') . '_suggestions_post_type' => "consider",

            //optimization ---------------------------------------------------------
            $this->get('slug') . '_optimization_num_of_characters' => 1000,
            $this->get('slug') . '_optimization_delta' => 2,

            //juice ----------------------------------------------------------------
            $this->get('slug') . '_default_seo_power' => 1000,
            $this->get('slug') . '_penality_per_position_percentage' => "1",
            $this->get('slug') . '_remove_link_to_anchor' => "1",
            $this->get('slug') . '_remove_url_parameters' => "0",

            //tracking -------------------------------------------------------------
            $this->get('slug') . '_track_internal_links' => "1",

            //analysis ----------------------------------------------------------
            $this->get('slug') . '_set_max_execution_time' => "1",
            $this->get('slug') . '_max_execution_time_value' => "300",
            $this->get('slug') . '_set_memory_limit' => "0",
            $this->get('slug') . '_memory_limit_value' => "512",
            $this->get('slug') . '_limit_posts_analysis' => "1000",
            $this->get('slug') . '_dashboard_post_types' => ['post', 'page'],
            $this->get('slug') . '_juice_post_types' => ['post', 'page'],
            $this->get('slug') . '_http_status_post_types' => ['post', 'page'],

            //meta boxes -----------------------------------------------------------
            $this->get('slug') . '_interlinks_options_post_types' => ['post', 'page'],
            $this->get('slug') . '_interlinks_optimization_post_types' => ['post', 'page'],
            $this->get('slug') . '_interlinks_suggestions_post_types' => ['post', 'page'],

            //capabilities ----------------------------------------------------------
            $this->get('slug') . '_dashboard_menu_required_capability' => "edit_others_posts",
            $this->get('slug') . '_juice_menu_required_capability' => "edit_others_posts",
            $this->get('slug') . '_hits_menu_required_capability' => "edit_others_posts",
            $this->get('slug') . '_http_status_menu_required_capability' => "edit_others_posts",
            $this->get('slug') . '_wizard_menu_required_capability' => "edit_others_posts",
            $this->get('slug') . '_ail_menu_required_capability' => "edit_others_posts",
            $this->get('slug') . '_categories_menu_required_capability' => "edit_others_posts",
            $this->get('slug') . '_term_groups_menu_required_capability' => "edit_others_posts",
            $this->get('slug') . '_import_menu_required_capability' => "edit_others_posts",
            $this->get('slug') . '_export_menu_required_capability' => "edit_others_posts",
            $this->get('slug') . '_maintenance_menu_required_capability' => "edit_others_posts",
            $this->get('slug') . '_interlinks_options_mb_required_capability' => "edit_others_posts",
            $this->get('slug') . '_interlinks_optimization_mb_required_capability' => "edit_posts",
            $this->get('slug') . '_interlinks_suggestions_mb_required_capability' => "edit_posts",

            //Advanced
            $this->get('slug') . '_default_enable_ail_on_post' => "1",
            $this->get('slug') . '_filter_priority' => "2147483646",
            $this->get('slug') . '_ail_test_mode' => "0",
            $this->get('slug') . '_random_prioritization' => "0",
            $this->get('slug') . '_ignore_self_ail' => "1",
            $this->get('slug') . '_categories_and_tags_verification' => "post",
            $this->get('slug') . '_general_limit_mode' => "1",
            $this->get('slug') . '_characters_per_autolink' => "200",
            $this->get('slug') . '_max_number_autolinks_per_post' => "100",
            $this->get('slug') . '_general_limit_subtract_mil' => "0",
            $this->get('slug') . '_same_url_limit' => "100",
            $this->get('slug') . '_wizard_rows' => "500",
            $this->get('slug') . '_supported_terms' => "10",
            $this->get('slug') . '_protect_attributes' => "1",
            $this->get('slug') . '_http_status_checks_per_iteration' => "2",
            $this->get('slug') . '_http_status_cron_schedule_interval' => "60",
            $this->get('slug') . '_http_status_request_timeout' => "10",

            //By default the following HTML tags are protected:
            $this->get('slug') . '_protected_tags' => array(
                'h1',
                'h2',
                'h3',
                'h4',
                'h5',
                'h6',
                'a',
                'img',
                'ul',
                'ol',
                'span',
                'pre',
                'code',
                'table',
                'iframe',
                'script'
            ),

            /*
             * By default all the Gutenberg Blocks except the following are protected:
             *
             * - Paragraph
             * - List
             * - Text Columns
             */
            $this->get('slug') . '_protected_gutenberg_blocks' => array(
                //'paragraph',
                'image',
                'heading',
                'gallery',
                //'list',
                'quote',
                'audio',
                'cover-image',
                'subhead',
                'video',
                'code',
                'html',
                'preformatted',
                'pullquote',
                'table',
                'verse',
                'button',
                'columns',
                'more',
                'nextpage',
                'separator',
                'spacer',
                //'text-columns',
                'shortcode',
                'categories',
                'latest-posts',
                'embed',
                'core-embed/twitter',
                'core-embed/youtube',
                'core-embed/facebook',
                'core-embed/instagram',
                'core-embed/wordpress',
                'core-embed/soundcloud',
                'core-embed/spotify',
                'core-embed/flickr',
                'core-embed/vimeo',
                'core-embed/animoto',
                'core-embed/cloudup',
                'core-embed/collegehumor',
                'core-embed/dailymotion',
                'core-embed/funnyordie',
                'core-embed/hulu',
                'core-embed/imgur',
                'core-embed/issuu',
                'core-embed/kickstarter',
                'core-embed/meetup-com',
                'core-embed/mixcloud',
                'core-embed/photobucket',
                'core-embed/polldaddy',
                'core-embed/reddit',
                'core-embed/reverbnation',
                'core-embed/screencast',
                'core-embed/scribd',
                'core-embed/slideshare',
                'core-embed/smugmug',
                'core-embed/speaker',
                'core-embed/ted',
                'core-embed/tumblr',
                'core-embed/videopress',
                'core-embed/wordpress-tv'
            ),

            $this->get('slug') . '_protected_gutenberg_custom_blocks' => "",
            $this->get('slug') . '_protected_gutenberg_custom_void_blocks' => "",
            $this->get('slug') . '_pagination_dashboard_menu' => "10",
            $this->get('slug') . '_pagination_juice_menu' => "10",
            $this->get('slug') . '_pagination_http_status_menu' => "10",
            $this->get('slug') . '_pagination_hits_menu' => "10",
            $this->get('slug') . '_pagination_ail_menu' => "10",
            $this->get('slug') . '_pagination_categories_menu' => "10",
            $this->get('slug') . '_pagination_term_groups_menu' => "10",

	        //Used internally to verify the status of the last update of the broken link check
            $this->get('slug') . '_broken_list_check_last_update' => "",

        ];

    }

    public static function get_instance()
    {

        if (null == self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;

    }

	public function cron_schedules_init() {
		add_filter( 'cron_schedules', array($this, 'custom_cron_schedule') );
	}

	/**
	 * Adds a custom cron schedule for every 5 minutes.
	 *
	 * @param array $schedules An array of non-default cron schedules.
	 * @return array Filtered array of non-default cron schedules.
	 */
	function custom_cron_schedule( $schedules ) {

		// add a custom schedule named 'daim_custom_schedule' to the existing set
		$schedules[ 'daim_custom_schedule' ] = array(
			'interval' => intval(get_option($this->get('slug') . '_http_status_cron_schedule_interval'), 10),
			'display' => __( 'Custom schedule based on the Interlinks Manager options.', 'daim' )
		);
		return $schedules;
		
	}

    //retrieve data
    public function get($index)
    {
        return $this->data[$index];
    }
    
    /*
     * Get the number of manual interlinks in a given string
     * 
     * @param string $string The string in which the search should be performed
     * @return int The number of internal links in the string
     */
    public function get_manual_interlinks($string){
        
        //remove the HTML comments
        $string = $this->remove_html_comments($string);
        
        //remove script tags
        $string = $this->remove_script_tags($string);
        
        /*
         * Get the website url and escape the regex character. # and 
         * whitespace ( used with the 'x' modifier ) are not escaped, thus
         * should not be included in the $site_url string
         */
        $site_url = preg_quote(get_home_url());
        
        //working regex
        $num_matches = preg_match_all(
            '{
            <a                      #1 Match the element a start-tag
            [^>]+                   #2 Match everything except > for at least one time
            href\s*=\s*             #3 Equal may have whitespaces on both sides
            ([\'"]?)                #4 Match double quotes, single quote or no quote ( captured for the backreference \1 )
            ' . $site_url . '       #5 The site URL ( Scheme and Domain )
            [^\'">\s]+              #6 The rest of the URL ( Path and/or File )
            (\1)                    #7 Backreference that matches the href value delimiter matched at line 4
            [^>]*                   #8 Any character except > zero or more times
            >                       #9 End of the start-tag
            .*?                     #10 Link text or nested tags. After the dot ( enclose in parenthesis ) negative lookbehinds can be applied to avoid specific stuff inside the link text or nested tags. Example with single negative lookbehind (.(?<!word1))*? Example with multiple negative lookbehind (.(?<!word1)(?<!word2)(?<!word3))*?
            <\/a\s*>                #11 Element a end-tag with optional white-spaces characters before the >
            }ix',                   
            $string, $matches);
        
        return $num_matches;
        
    }
    
    /*
     * Count the number of auto interlinks in the string
     * 
     * @param string $string The string in which the search should be performed
     * @return int The number of autolinks
     */
    public function get_autolinks_number($string){
        
        //remove the HTML comments
        $string = $this->remove_html_comments($string);
        
        //remove script tags
        $string = $this->remove_script_tags($string);
        
        /*
         * Get the website url and quote and escape the regex character. # and 
         * whitespace ( used with the 'x' modifier ) are not escaped, thus
         * should not be included in the $site_url string
         */
        $site_url = preg_quote(get_home_url());
        
        $num_matches = preg_match_all(
            '{
            <a\s+                   #1 The element a start-tag followed by one or more whitespace character
            data-ail="[\d]+"\s+     #2 The data-ail attribute followed by one or more whitespace character
            target="_[\w]+"\s+      #3 The target attribute followed by one or more whitespace character
            (?:rel="nofollow"\s+)?  #4 The rel="nofollow" attribute followed by one or more whitespace character, all is made optional by the trailing ? that works on the non-captured group ?:
            href\s*=\s*             #5 Equal may have whitespaces on both sides
            ([\'"]?)                #6 Match double quotes, single quote or no quote ( captured for the backreference \1 )
            ' . $site_url . '       #7 The site URL ( Scheme and Domain )
            [^\'">\s]*              #8 The rest of the URL ( Path and/or File )
            (\1)                    #9 Backreference that matches the href value delimiter matched at line 5
            [^>]*                   #10 Any character except > zero or more times
            >                       #11 End of the start-tag
            .+?                     #12 Any character one or more time with the quantifier lazy
            <\/a\s*>                #13 Element a end-tag with optional white-spaces characters before the >
            }ix',
            $string, $matches);
        
        return $num_matches;
        
    }
    
    /*
     * Get the raw post_content of the specified post
     * 
     * @param $post_id The ID of the post
     * @return string The raw post content
     */
    public function get_raw_post_content($post_id){
        
        global $wpdb;
        $table_name = $wpdb->prefix . "posts";
        $safe_sql = $wpdb->prepare("SELECT post_content FROM $table_name WHERE ID = %d", $post_id);
        $post_obj = $wpdb->get_row($safe_sql);
        
        return $post_obj->post_content;
        
    }
    
    /*
     * The optimization is calculated based on:
     * - the "Optimization Delta" option
     * - the number of interlinks
     * - the content length
     * True is returned if the content is optimized, False if it's not optimized
     * 
     * @param int $number_of_interlinks The overall number of interlinks ( manual interlinks + auto interlinks )
     * @param int $content_length The content length
     * @return bool True if is optimized, False if is not optimized
     */
    public function calculate_optimization($number_of_interlinks, $content_length){

        //get the values of the options
        $optimization_num_of_characters = (int) get_option($this->get('slug') . '_optimization_num_of_characters');
        $optimization_delta = (int) get_option($this->get('slug') . '_optimization_delta');
        
        //determines if this post is optimized
        $optimal_number_of_interlinks = (int) $content_length / $optimization_num_of_characters;
        if(
            ( $number_of_interlinks >= ( $optimal_number_of_interlinks - $optimization_delta ) ) and
            ( $number_of_interlinks <= ( $optimal_number_of_interlinks + $optimization_delta ) )
        ){
            $is_optimized = true;
        }else{
            $is_optimized = false;
        }
        
        return $is_optimized;
        
    }
    
    /*
     * The optimal number of interlinks is calculated by dividing the content
     * length for the value in the "Characters per Interlink" option and
     * converting the result to an integer
     * 
     * @param int $number_of_interlinks The overall number of interlinks ( manual interlinks + auto interlinks )
     * @param int $content_length The content length
     * @return int The number of recommended interlinks
     */
    public function calculate_recommended_interlinks($number_of_interlinks, $content_length){

        //get the values of the options
        $optimization_num_of_characters = get_option($this->get('slug') . '_optimization_num_of_characters');
        $optimization_delta = get_option($this->get('slug') . '_optimization_delta');
        
        //determines the optimal number of interlinks
        $optimal_number_of_interlinks = $content_length / $optimization_num_of_characters;
        
        return intval($optimal_number_of_interlinks, 10);
        
    }
    
    /*
     * The minimum number of interlinks suggestion is calculated by subtracting
     * half of the optimization delta from the optimal number of interlinks
     * 
     * @param int The post id
     * @return int The minimum number of interlinks suggestion
     */
    public function get_suggested_min_number_of_interlinks($post_id){

        //get the content length of the raw post
        $content_length = mb_strlen($this->get_raw_post_content($post_id));
        
        //get the values of the options
        $optimization_num_of_characters = intval( get_option($this->get('slug') . '_optimization_num_of_characters'), 10);
        $optimization_delta = intval( get_option($this->get('slug') . '_optimization_delta'), 10);
        
        //determines the optimal number of interlinks
        $optimal_number_of_interlinks = $content_length / $optimization_num_of_characters;
        
        //get the minimum number of interlinks
        $min_number_of_interlinks = intval( ( $optimal_number_of_interlinks - ( $optimization_delta / 2 ) ), 10);
        
        //set to zero negative values
        if( $min_number_of_interlinks < 0 ){ $min_number_of_interlinks = 0; }
        
        return $min_number_of_interlinks;
        
    }
    
    /*
     * The maximum number of interlinks suggestion is calculated by adding
     * half of the optimization delta to the optimal number of interlinks
     * 
     * @param int The post id
     * @return int The maximum number of interlinks suggestion
     */
    public function get_suggested_max_number_of_interlinks($post_id){

        //get the content length of the raw post
        $content_length = mb_strlen($this->get_raw_post_content($post_id));
        
        ///get the values of the options
        $optimization_num_of_characters = get_option($this->get('slug') . '_optimization_num_of_characters');
        $optimization_delta = get_option($this->get('slug') . '_optimization_delta');
        
        //determines the optimal number of interlinks
        $optimal_number_of_interlinks = $content_length / $optimization_num_of_characters;
        
        return intval( ( $optimal_number_of_interlinks + ( $optimization_delta / 2 ) ), 10);
        
    }
    
    /*
     * Get the number of hits related to a specific post
     * 
     * @param $post_id The post_id for which the hits should be counted
     * @return int The number of hits
     */
    public function get_number_of_hits($post_id){
        
        global $wpdb;
        $table_name = $wpdb->prefix . $this->get('slug') . "_hits";
        $safe_sql = $wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE source_post_id = %d", $post_id);
        $number_of_hits = $wpdb->get_var($safe_sql);
        
        return $number_of_hits;
        
    }
    
    /*
     * Add autolinks to the content based on the keyword created with the AIL
     * menu:
     * 
     * 1 - The protected blocks are applied with apply_protected_blocks()
     * 2 - The words to be converted as a link are temporarely replaced with [ail]ID[/ail]
     * 3 - The [ail]ID[/ail] identifiers are replaced with the actual links
     * 4 - The protected block are removed with the remove_protected_blocks()
     * 5 - The content with applied the autolinks is returned
     * 
     * @param $content The content on which the autolinks should be applied
     * @param $check_query This parameter is set to True when the method is
     * called inside the loop and is used to verify if we are in a single post 
     * @param $post_type If the autolinks are added from the back-end this
     * parameter is used to determine the post type of the content
     * $post_id This parameter is used if the method has been called outside
     * the loop
     * @return string The content with applied the autolinks
     * 
     */
    public function add_autolinks($content, $check_query = true, $post_type = '', $post_id = false ){
        
        //verify that we are inside a post, page or cpt
        if($check_query){
            if(!is_singular() or is_attachment() or is_feed()){return $content;}
        }
        
        /*
         * If the $post_id is not set means that we are in the loop and can be
         * retrieved with get_the_ID()
         */
        if($post_id === false){ $post_id = get_the_ID(); }

        //get the permalink
        $post_permalink = get_permalink($post_id);
            
        /*
         * Verify with the "Enable AIL" post meta data or ( if the meta data is
         * not present ) verify through the "Default Enable AIL" option if the
         * autolinks should be applied to this post
         */
        $enable_ail = get_post_meta( $post_id, '_daim_enable_ail', true );
        if(strlen(trim($enable_ail)) == 0){
            $enable_ail = get_option( $this->get('slug') . '_default_enable_ail_on_post');
        }
        if( intval($enable_ail, 10) == 0 ){return $content;}

        //initialize properties
        $this->ail_id = 0;
        $this->ail_a = array();
        $this->post_id = $post_id;
        
        //get the max number of autolinks allowed per post
        $this->max_number_autolinks_per_post = $this->get_max_number_autolinks_per_post($this->post_id, $content);

	    //Save the "Same URL Limit" as a class property
	    $this->same_url_limit = intval(get_option($this->get('slug') . '_same_url_limit'), 10);

	    //protect the tags and the commented HTML with protected blocks
	    $content = $this->apply_protected_blocks($content);

        //initialize the counter of the autolinks applied
        $total_autolink_applied = 0;
        
        //get an array with the autolinks from the db table
        global $wpdb;
        $table_name = $wpdb->prefix . $this->get('slug') . "_autolinks";
        $sql = "SELECT * FROM $table_name ORDER BY priority DESC";
        $autolinks = $wpdb->get_results($sql, ARRAY_A);

	    /*
		 * To avoid additional database requests for each autolink in preg_replace_callback_2() save the data of the
		 * autolink in an array that uses the "autolink_id" as its index.
		 */
	    $this->autolinks_ca = $this->save_autolinks_in_custom_array($autolinks);

	    //Apply the Random Prioritization if enabled
	    if (intval(get_option($this->get('slug') . '_random_prioritization'), 10) === 1) {
		    $autolinks = $this->apply_random_prioritization($autolinks, $post_id);
	    }

        //cycle through all the defined autolinks
        foreach ($autolinks as $key => $autolink) {

            //Save this autolink as a class property
            $this->parsed_autolink = $autolink;

            /*
             * Self AIL
             *
             * If the "Ignore Self AIL" option is set to true, do not apply the autolinks that have, as a target, the
             * post where they should be applied
             *
             * Compare $autolink['url'] with the the permalink ( with the get_home_url() removed ),
             * if the comparison returns true ( which means that the autolink url and the current url are the same ) do
             * not apply the autolink
             */
            if( intval(get_option( $this->get('slug') . '_ignore_self_ail'), 10) == 1 ){
                $home_url_length = abs( strlen( get_home_url() ) );
                if( $autolink['url'] == substr( $post_permalink, $home_url_length ) ){continue;}
            }

            /*
             * If $post_type is not empty means that we are adding the autolinks through the back-end, in this case set
             * the $this->parsed_post_type property with the $post_type variable.
             *
             * If $post_type is empty means that we are in the loop and the post type can be retrieved with the
             * get_post_type() function.
             */
            if ($post_type !== '') {
                $this->parsed_post_type = $post_type;
            } else {
                $this->parsed_post_type = get_post_type();
            }

            //Get the list of post types where the autolinks should be applied.
            $post_types_a = maybe_unserialize($autolink['activate_post_types']);

            //Verify the post type
            if (!is_array($post_types_a) or in_array($this->parsed_post_type, $post_types_a) === false) {
                continue;
            }

            /*
             * If the term group is not set:
             *
             * - Check if the post is compliant by verifying categories and tags
             *
             * If the term group is set:
             *
             * - Check if the post is compliant by verifying the term group
             */
            if (intval($autolink['term_group_id'], 10) === 0) {

                /*
                 * Verify categories and tags only in the "post" post type or in all the posts. This verification is based
                 * on the value of the $categories_and_tags_verification option.
                 *
                 * - If $categories_and_tags_verification is equal to "any" verify the presence of the selected categories
                 * and tags in any post type.
                 * - If $categories_and_tags_verification is equal to "post" verify the presence of the selected categories
                 * and tags only in the "post" post type.
                 */
                $categories_and_tags_verification =  get_option($this->get('slug') . "_categories_and_tags_verification");
                if (($categories_and_tags_verification === 'any' or $this->parsed_post_type === 'post') and
                    ( ! $this->is_compliant_with_categories($this->post_id, $autolink) or
                        ! $this->is_compliant_with_tags($this->post_id, $autolink))) {
                    continue;
                }

            }else{

                //Do not proceed with the application of the autolink if this post is not compliant with the term group
                if ( ! $this->is_compliant_with_term_group($this->post_id, $autolink)) {
                    continue;
                }

            }

            //get the max number of autolinks per keyword
            $max_number_autolinks_per_keyword = $autolink['max_number_autolinks'];
            
            //apply a case insensitive search if the case_insensitive_flag is selected
            if($autolink['case_insensitive_search']){
                $modifier = 'iu';//enable case sensitive and unicode modifier
            }else{
                $modifier = 'u';//enable unicode modifier
            }
            
            $ail_temp = array();
            
            //find the left boundary
            switch($autolink['string_before']){
                case 1:
                    $string_before = '\b';
                    break;
                
                case 2:
                    $string_before = ' ';
                    break;
                
                case 3:
                    $string_before = ',';
                    break;
                
                case 4:
                    $string_before = '\.';
                    break;
                
                case 5:
                    $string_before = '';
                    break;
            }
            
            //find the right boundary
            switch($autolink['string_after']){
                case 1:
                    $string_after = '\b';
                    break;
                
                case 2:
                    $string_after = ' ';
                    break;
                
                case 3:
                    $string_after = ',';
                    break;
                
                case 4:
                    $string_after = '\.';
                    break;

                case 5:
                    $string_after = '';
                    break;
            }
            
            //escape regex characters and the '/' regex delimiter
	        $autolink_keyword = preg_quote(stripslashes($autolink['keyword']), '/');
            $autolink_keyword_before = preg_quote($autolink['keyword_before'], '/');
            $autolink_keyword_after  = preg_quote($autolink['keyword_after'], '/');

            /*
             * Step 1: "The creation of temporary identifiers of the sostitutions"
             * Replace all the matches with the [ail]ID[/ail] string, where the
             * ID is the identifier of the sostitution.
             * The ID is also used as the index of the $this->ail_a temporary array
             * used to store information about all the sostutions.
             * This array will be later used in "Step 2" to replace the
             * [ail]ID[/ail] string with the actual links
             */
            $content = preg_replace_callback(
            '/(' . $autolink_keyword_before . ')(' . ($string_before) . ')(' . $autolink_keyword . ')(' . ($string_after) . ')(' . $autolink_keyword_after . ')/' . $modifier,
            array($this, 'preg_replace_callback_1'),
            $content,
            $max_number_autolinks_per_keyword);
            
        }
        
        /*
         * Step 2: "The replacement of the temporary string [ail]ID[/ail]"
         * Replaces the [ail]ID[/ail] matches found in the $content with the
         * actual links by using the $this->ail_a array to find the identifier of the
         * sostitutions and by retrieving in the db table "autolinks" ( with the
         *  "autolink_id" ) additional information about the sostitution.
         */
        $content = preg_replace_callback(
        '/\[ail\](\d+)\[\/ail\]/',
        array($this, 'preg_replace_callback_2'),
        $content);
        
        //remove the protected blocks
        $content = $this->remove_protected_blocks($content);
        
        return $content;
        
    }
    
    /*
     * Replace the following elements with with [pr]ID[/pr]:
     *
     * - HTML attributes
     * - Protected Gutenberg blocks
     * - Commented HTML
     * - Protected HTML tags
     * 
     * The replaced content is saved in the property $pr_a, an array with the ID used in the [pr]ID[/pr] placeholder
     * as the index.
     * 
     * @param $content string The unprotected $content
     * @return string The $content with applied the protected block
     */
    private function apply_protected_blocks($content){
        
        $this->pb_id = 0;
        $this->pb_a = array();

		//Protect all the HTML attributes if the "Protect Attributes" option is enabled
	    if(intval(get_option($this->get('slug') . '_protect_attributes'), 10) === 1){

		    //Match all the HTML attributes that use double quotes as the attribute value delimiter
		    $content = preg_replace_callback(
			    '{
					<[a-z0-9]+    #1 The beginning of any HTML element
					\s+           #2 Optional whitespaces 
					(             #3 Begin a group
					(?:           #4 Begin a non-capturing group	
					\s*           #5 Optional whitespaces
					[a-z0-9-_]+   #6 Match the name of the attribute
					\s*=\s*       #7 Equal may have whitespaces on both sides
					"             #8 Match double quotes
					[^"]*         #9 Any character except double quotes zero or more times
					"             #10 Match double quotes
					\s*           #11 Optional whitespaces 
					|			  #12 Provide an alternative to match attributes without values like for example "itemscope"
					\s*           #13 Optional whitespaces
					[a-z0-9-_]    #14 Match the name of the attribute
					\s*           #15 Optional whitespaces 
					)*            #16 Close the group that matches the complete attribute (attribute name + equal sign + attribute value) and use the * to match multiple groups
					)             #17 Close the main capturing group
					\/?           #18 Match an optional / (used in void elements)
					>             #19 Match the end of any HTML element
                    }ixs',
			    array($this, 'apply_single_protected_block_attributes'),
			    $content
		    );

		    //Match all the HTML attributes that use single quotes as the attribute value delimiter
		    $content = preg_replace_callback(
			    '{
					<[a-z0-9]+    #1 The beginning of any HTML element
					\s+           #2 Optional whitespaces 
					(             #3 Begin a group
					(?:           #4 Begin a non-capturing group
					\s*           #5 Optional whitespaces
					[a-z0-9-_]+   #6 Match the name of the attribute
					\s*=\s*       #7 Equal may have whitespaces on both sides
					\'            #8 Match single quote
					[^\']*        #9 Any character except single quote zero or more times
					\'            #10 Match single quote
					\s*           #11 Optional whitespaces 
					|			  #12 Provide an alternative to match attributes without values like for example "itemscope"
					\s*           #13 Optional whitespaces
					[a-z0-9-_]    #14 Match the name of the attribute
					\s*           #15 Optional whitespaces 
					)*            #16 Close the group that matches the complete attribute (attribute name + equal sign + attribute value) and use the * to match multiple groups
					)             #17 Close the main capturing group
					\/?           #18 Match an optional / (used in void elements)
					>             #19 Match the end of any HTML element
                    }ixs',
			    array($this, 'apply_single_protected_block_attributes'),
			    $content
		    );

	    }

	    //Get the Gutenberg Protected Blocks
	    $protected_gutenberg_blocks   = get_option($this->get('slug') . '_protected_gutenberg_blocks');
	    $protected_gutenberg_blocks_a = maybe_unserialize($protected_gutenberg_blocks);
	    if ( ! is_array($protected_gutenberg_blocks_a)) {
		    $protected_gutenberg_blocks_a = array();
	    }

	    //Get the Protected Gutenberg Custom Blocks
	    $protected_gutenberg_custom_blocks   = get_option($this->get('slug') . '_protected_gutenberg_custom_blocks');
	    $protected_gutenberg_custom_blocks_a = array_filter(explode(',',
		    str_replace(' ', '', trim($protected_gutenberg_custom_blocks))));

	    //Get the Protected Gutenberg Custom Void Blocks
	    $protected_gutenberg_custom_void_blocks   = get_option($this->get('slug') . '_protected_gutenberg_custom_void_blocks');
	    $protected_gutenberg_custom_void_blocks_a = array_filter(explode(',',
		    str_replace(' ', '', trim($protected_gutenberg_custom_void_blocks))));

	    $protected_gutenberg_blocks_comprehensive_list_a = array_merge($protected_gutenberg_blocks_a,
		    $protected_gutenberg_custom_blocks_a, $protected_gutenberg_custom_void_blocks_a);

	    if (is_array($protected_gutenberg_blocks_comprehensive_list_a)) {

		    foreach ($protected_gutenberg_blocks_comprehensive_list_a as $key => $block) {

			    //Non-Void Blocks
			    if ($block === 'paragraph' or
			        $block === 'image' or
			        $block === 'heading' or
			        $block === 'gallery' or
			        $block === 'list' or
			        $block === 'quote' or
			        $block === 'audio' or
			        $block === 'cover-image' or
			        $block === 'subhead' or
			        $block === 'video' or
			        $block === 'code' or
			        $block === 'preformatted' or
			        $block === 'pullquote' or
			        $block === 'table' or
			        $block === 'verse' or
			        $block === 'button' or
			        $block === 'columns' or
			        $block === 'more' or
			        $block === 'nextpage' or
			        $block === 'separator' or
			        $block === 'spacer' or
			        $block === 'text-columns' or
			        $block === 'shortcode' or
			        $block === 'embed' or
			        $block === 'html' or
			        $block === 'core-embed/twitter' or
			        $block === 'core-embed/youtube' or
			        $block === 'core-embed/facebook' or
			        $block === 'core-embed/instagram' or
			        $block === 'core-embed/wordpress' or
			        $block === 'core-embed/soundcloud' or
			        $block === 'core-embed/spotify' or
			        $block === 'core-embed/flickr' or
			        $block === 'core-embed/vimeo' or
			        $block === 'core-embed/animoto' or
			        $block === 'core-embed/cloudup' or
			        $block === 'core-embed/collegehumor' or
			        $block === 'core-embed/dailymotion' or
			        $block === 'core-embed/funnyordie' or
			        $block === 'core-embed/hulu' or
			        $block === 'core-embed/imgur' or
			        $block === 'core-embed/issuu' or
			        $block === 'core-embed/kickstarter' or
			        $block === 'core-embed/meetup-com' or
			        $block === 'core-embed/mixcloud' or
			        $block === 'core-embed/photobucket' or
			        $block === 'core-embed/polldaddy' or
			        $block === 'core-embed/reddit' or
			        $block === 'core-embed/reverbnation' or
			        $block === 'core-embed/screencast' or
			        $block === 'core-embed/scribd' or
			        $block === 'core-embed/slideshare' or
			        $block === 'core-embed/smugmug' or
			        $block === 'core-embed/speaker' or
			        $block === 'core-embed/ted' or
			        $block === 'core-embed/tumblr' or
			        $block === 'core-embed/videopress' or
			        $block === 'core-embed/wordpress-tv' or
			        in_array($block, $protected_gutenberg_custom_blocks_a)
			    ) {

				    //escape regex characters and the '/' regex delimiter
				    $block = preg_quote($block, '/');

				    //Non-Void Blocks Regex
				    $content = preg_replace_callback(
					    '/
                    <!--\s+(wp:' . $block . ').*?-->        #1 Gutenberg Block Start
                    .*?                                     #2 Gutenberg Content
                    <!--\s+\/\1\s+-->                       #3 Gutenberg Block End
                    /ixs',
					    array($this, 'apply_single_protected_block'),
					    $content
				    );

				    //Void Blocks
			    } elseif ($block === 'categories' or
			              $block === 'latest-posts' or
			              in_array($block, $protected_gutenberg_custom_void_blocks_a)
			    ) {

				    //escape regex characters and the '/' regex delimiter
				    $block = preg_quote($block, '/');

				    //Void Blocks Regex
				    $content = preg_replace_callback(
					    '/
                    <!--\s+wp:' . $block . '.*?\/-->        #1 Void Block
                    /ix',
					    array($this, 'apply_single_protected_block'),
					    $content
				    );

			    }

		    }

	    }

        /*
         * Protect the commented sections, enclosed between <!-- and -->
         */
        $content = preg_replace_callback(
            '/
            <!--                                #1 Comment Start
            .*?                                 #2 Any character zero or more time with a lazy quantifier
            -->                                 #3 Comment End
            /ix',                               
            array($this,'apply_single_protected_block'),
            $content
        );
        
        //Get the list of the protected tags from the "Protected Tags" option
        $protected_tags_a = $this->get_protected_tags_option();
        foreach($protected_tags_a as $key => $single_protected_tag){

            /*
             * Validate the tag. HTML elements all have names that only use
             * characters in the range 0–9, a–z, and A–Z.
             */
            if( preg_match( '/^[0-9a-zA-Z]+$/' , $single_protected_tag) === 1 ){
                
                //make the tag lowercase
                $single_protected_tag = strtolower($single_protected_tag);
                
                /*
                 * Apply different treatment if the tag is a void tag or a
                 * non-void tag
                 */
                if( $single_protected_tag == 'area' or
                    $single_protected_tag == 'base' or
                    $single_protected_tag == 'br' or
                    $single_protected_tag == 'col' or
                    $single_protected_tag == 'embed' or
                    $single_protected_tag == 'hr' or
                    $single_protected_tag == 'img' or
                    $single_protected_tag == 'input' or
                    $single_protected_tag == 'keygen' or
                    $single_protected_tag == 'link' or
                    $single_protected_tag == 'meta' or
                    $single_protected_tag == 'param' or
                    $single_protected_tag == 'source' or
                    $single_protected_tag == 'track' or
                    $single_protected_tag == 'wbr'
                ){

                    //apply the protected block on void tags
                    $content = preg_replace_callback(
                        '/                                  
                        <                                   #1 Begin the start-tag
                        (' . $single_protected_tag . ')     #2 The tag name ( captured for the backreference )
                        (\s+[^>]*)?                         #3 Match the rest of the start-tag
                        >                                   #4 End the start-tag
                        /ix',                               
                        array($this,'apply_single_protected_block'),
                        $content
                    );

                }else{

                    //apply the protected block on non-void tags
                    $content = preg_replace_callback(
                        '/
                        <                                   #1 Begin the start-tag
                        (' . $single_protected_tag . ')     #2 The tag name ( captured for the backreference )
                        (\s+[^>]*)?                         #3 Match the rest of the start-tag
                        >                                   #4 End the start-tag
                        .*?                                 #5 The element content ( with the "s" modifier the dot matches also the new lines )
                        <\/\1\s*>                           #6 The end-tag with a backreference to the tag name ( \1 ) and optional white-spaces before the closing >
                        /ixs',                              
                        array($this,'apply_single_protected_block'),
                        $content
                    );

                }
                
            }
            
        }
        
        return $content;
        
    }
    
    /*
     * This method is in multiple preg_replace_callback located in the
     * apply_protected_blocks() method.
     * 
     * What it does is:
     * 1 - save the match in the $pb_a array
     * 2 - return the protected block with the related identifier ( [pb]ID[/pb] )
     * 
     * @param $m An array with at index 0 the complete match and at index 1 the
     * capture group
     * @return string
     */
    private function apply_single_protected_block($m){
        
        //save the match in the $pb_a array
        $this->pb_id++;
        $this->pb_a[$this->pb_id] = $m[0];

        /*
         * replace the tag/URL with the protected block and the
         * index of the $pb_a array as the identifier
         */
        return '[pb]' . $this->pb_id . '[/pb]';
            
    }

	/*
	 * This method is used by a preg_replace_callback located in the apply_protected_blocks() method.
	 *
	 * Specifically, this method is used to apply a protected block on the matched HTML attributes.
	 *
	 * What it does:
	 *
	 * 1 - Saves the match in the $pb_a array
	 * 2 - Replaces the matched HTML attributes with a protected blocks
	 * 2 - Returns the modified HTML
	 *
	 * @param $m An array with at index 0 the complete match and at index 1 the first capturing group (one or more HTML
	 * attributes)
	 * @return string
	 */
	private function apply_single_protected_block_attributes($m){

		//save the match in the $pb_a array
		$this->pb_id++;
		$this->pb_a[$this->pb_id] = $m[1];

		//Replace the matched attribute with the protected block and return it
		return str_replace($m[1], '[pb]' . $this->pb_id . '[/pb]', $m[0]);

	}
    
    /*
     * Replace the block [pr]ID[/pr] with the related tags found in the
     * $pb_a property
     * 
     * @param $content string The $content with applied the protected block
     * return string The unprotected content
     */
    private function remove_protected_blocks($content){
        
        $content = preg_replace_callback(
            '/\[pb\](\d+)\[\/pb\]/',
            array($this, 'preg_replace_callback_3'),
            $content
        );
        
        return $content;
        
    }
    
    /*
     * Calculate the link juice of a links based on the given parameters.
     * 
     * @param $post_content_with_autolinks The post content ( with autolinks applied )
     * @param $post_id The post id
     * @param $link_postition The position of the link in the string ( the line where the link string starts )
     * @return int The link juice of the link
     */
    public function calculate_link_juice($post_content_with_autolinks, $post_id, $link_position){
        
        //Get the SEO power of the post
        $seo_power = get_post_meta( $post_id, '_daim_seo_power', true );
        if(strlen(trim($seo_power)) == 0){$seo_power = (int) get_option( $this->get('slug') . '_default_seo_power');}
        
        /*
         * Divide the SEO power for the total number of links ( all the links,
         * external and internal are considered )
         */
        $juice_per_link = $seo_power / $this->get_number_of_links($post_content_with_autolinks);
        
        /*
         * Calculate the index of the link on the post ( example 1 for the first
         * link or 3 for the third link )
         * A regular expression that counts the links on a string that starts
         * from the beginning of the post and ends at the $link_position is used
         */
        $post_content_before_the_link = substr($post_content_with_autolinks, 0, $link_position);
        $number_of_links_before = $this->get_number_of_links($post_content_before_the_link);
        
        /*
         * Remove a percentage of the $juice_value based on the number of links
         * before this one
         */
        $penality_per_position_percentage = (int) get_option( $this->get('slug') . '_penality_per_position_percentage');
        $link_juice = $juice_per_link - ( ( $juice_per_link / 100 * $penality_per_position_percentage ) * $number_of_links_before );
        
        //return the link juice or 0 if the calculated link juice is negative
        if($link_juice < 0){$link_juice = 0;}
        return $link_juice;
        
    }
    
    /*
     * Get the total number of links ( any kind of link: internal, external,
     * nofollow, dofollow ) available in the passed string
     * 
     * @param $s The string on which the number of links should be counted
     * @return int The number of links found on the string
     */
    public function get_number_of_links($s){
                
        //remove the HTML comments
        $s = $this->remove_html_comments($s);
        
        //remove script tags
        $s = $this->remove_script_tags($s);
        
        $num_matches = preg_match_all(
            '{<a                                #1 Begin the element a start-tag
            [^>]+                               #2 Any character except > at least one time
            href\s*=\s*                         #3 Equal may have whitespaces on both sides
            ([\'"]?)                            #4 Match double quotes, single quote or no quote ( captured for the backreference \1 )
            [^\'">\s]+                          #5 The site URL
            \1                                  #6 Backreference that matches the href value delimiter matched at line 4     
            [^>]*                               #7 Any character except > zero or more times
            >                                   #8 End of the start-tag
            .*?                                 #9 Link text or nested tags. After the dot ( enclose in parenthesis ) negative lookbehinds can be applied to avoid specific stuff inside the link text or nested tags. Example with single negative lookbehind (.(?<!word1))*? Example with multiple negative lookbehind (.(?<!word1)(?<!word2)(?<!word3))*?
            <\/a\s*>                            #10 Element a end-tag with optional white-spaces characters before the >
            }ix',
        $s, $matches);
        
        return $num_matches;
        
    }
    
    /*
     * Given a link returns it with the anchor link removed.
     * 
     * @param $s The link that should be analyzed
     * @return string The link with the link anchor removed
     */
    public function remove_link_to_anchor($s){
        
        $s = preg_replace_callback(
            '/([^#]+)               #Everything except # one or more times ( captured )
            \#.*                    #The # with anything the follows zero or more times
            /ux',
            array($this, 'preg_replace_callback_4'),
            $s
        );
            
        return $s;
        
    }
    
    /*
     * Given an URL the parameter part is removed
     * 
     * @param $s The URL
     * @return string The URL with the URL parameters removed
     */
    public function remove_url_parameters($s){
        
        $s = preg_replace_callback(
            '/([^?]+)               #Everything except ? one or more time ( captured )
            \?.*                    #The ? with anything the follows zero or more times
            /ux',
            array($this, 'preg_replace_callback_5'),
            $s
        );
            
        return $s;
        
    }
    
    /*
     * Callback of the preg_replace_callback() function
     * 
     * This callback is used to avoid an anonimus function as a parameter of the
     * preg_replace_callback() function for PHP backward compatibility
     * 
     * Look for uses of preg_replace_callback_1 to find which
     * preg_replace_callback() function is actually using this callback
     */
    public function preg_replace_callback_1($m){
                
        /*
         * do not apply the replacement ( and return the matches string )
         * if the max number of autolinks per post has been reached
         */
        if($this->max_number_autolinks_per_post == $this->ail_id or
           $this->same_url_limit_reached()){
            /*
             * return the captered text with related left and right boundaries
             * to not alter the content
             */
            return $m[1] . $m[2] . $m[3] . $m[4] . $m[5];
        }else{
            $this->ail_id++;
            $this->ail_a[$this->ail_id]['autolink_id'] = $this->parsed_autolink['id'];
	        $this->ail_a[$this->ail_id]['url'] = $this->parsed_autolink['url'];
            $this->ail_a[$this->ail_id]['text'] = $m[3];
            $this->ail_a[$this->ail_id]['left_boundary'] = $m[2];
            $this->ail_a[$this->ail_id]['right_boundary'] = $m[4];
            $this->ail_a[$this->ail_id]['keyword_before'] = $m[1];
            $this->ail_a[$this->ail_id]['keyword_after']  = $m[5];

            return '[ail]' . $this->ail_id . '[/ail]';
        }
        
    }
    
    /*
     * Callback of the preg_replace_callback() function
     * 
     * This callback is used to avoid an anonimus function as a parameter of the
     * preg_replace_callback() function for PHP backward compatibility
     * 
     * Look for uses of preg_replace_callback_2 to find which
     * preg_replace_callback() function is actually using this callback
     */
    public function preg_replace_callback_2($m){
        
        /*
         * Find the related text of the link from the $this->ail_a multidimensional
         * array by using the match as the index
         */
        $link_text = $this->ail_a[$m[1]]['text'];

        /*
         * Get the left and right boundaries
         */
        $left_boundary = $this->ail_a[$m[1]]['left_boundary'];
        $right_boundary = $this->ail_a[$m[1]]['right_boundary'];

        //Get the keyword_before and keyword_after
        $keyword_before = $this->ail_a[$m[1]]['keyword_before'];
        $keyword_after  = $this->ail_a[$m[1]]['keyword_after'];

	    //Get the autolink_id
        $autolink_id = $this->ail_a[$m[1]]['autolink_id'];

        //get the "url" value
        $link_url = $this->autolinks_ca[$autolink_id]['url'];

        //generate the title attribute HTML if the "title" field is not empty
        if(strlen(trim($this->autolinks_ca[$autolink_id]['title'])) > 0){
            $title_attribute = 'title="' . esc_attr(stripslashes($this->autolinks_ca[$autolink_id]['title'])) . '"';
        }else{
            $title_attribute = '';
        }

        //get the "open_new_tab" value
        if( intval($this->autolinks_ca[$autolink_id]['open_new_tab'], 10) == 1 ){$open_new_tab = 'target="_blank"';}else{$open_new_tab = 'target="_self"';}

        //get the "use_nofollow" value
        if( intval($this->autolinks_ca[$autolink_id]['use_nofollow'], 10) == 1 ){$use_nofollow = 'rel="nofollow"';}else{$use_nofollow = '';}

        //return the actual link
        return $keyword_before . $left_boundary . '<a data-ail="' . $this->post_id . '" ' . $open_new_tab . ' ' . $use_nofollow . ' href="' . esc_url(get_home_url() . $link_url) . '" ' . $title_attribute . '>' . $link_text . '</a>' . $right_boundary . $keyword_after;
            
    } 
    
    /*
     * Callback of the preg_replace_callback() function
     * 
     * This callback is used to avoid an anonimus function as a parameter of the
     * preg_replace_callback() function for PHP backward compatibility
     * 
     * Look for uses of preg_replace_callback_3 to find which
     * preg_replace_callback() function is actually using this callback
     */
    public function preg_replace_callback_3($m){
        
        /*
         * The presence of nested protected blocks is verified. If a protected
         * block is inside the content of a protected block the
         * remove_protected_block() method is applied recursively until there
         * are no protected blocks
         */
        $html = $this->pb_a[$m[1]];
        $recursion_ends = false;
        
        do{
            
            /*
             * if there are no protected blocks in content of the protected
             * block end the recursion, otherwise apply remove_protected_block()
             * again
             */
            if( preg_match('/\[pb\](\d+)\[\/pb\]/', $html) == 0 ){
                $recursion_ends = true;
            }else{
                $html = $this->remove_protected_blocks($html);
            }
            
        }while($recursion_ends === false);

        return $html;
            
    }
    
    /*
     * Callback of the preg_replace_callback() function
     * 
     * This callback is used to avoid an anonimus function as a parameter of the
     * preg_replace_callback() function for PHP backward compatibility
     * 
     * Look for uses of preg_replace_callback_4 to find which
     * preg_replace_callback() function is actually using this callback
     */
    public function preg_replace_callback_4($m){
        
        return $m[1];
            
    }
    
    /*
     * Callback of the preg_replace_callback() function
     * 
     * This callback is used to avoid an anonimus function as a parameter of the
     * preg_replace_callback() function for PHP backward compatibility
     * 
     * Look for uses of preg_replace_callback_5 to find which
     * preg_replace_callback() function is actually using this callback
     */
    public function preg_replace_callback_5($m){
        
        return $m[1];
            
    }
    
    /*
     * Callback of the preg_replace_callback() function
     * 
     * This callback is used to avoid an anonimus function as a parameter of the
     * preg_replace_callback() function for PHP backward compatibility
     * 
     * Look for uses of preg_replace_callback_6 to find which
     * preg_replace_callback() function is actually using this callback
     */
    public function preg_replace_callback_6($m){
                
        //replace '<a "' with '<a data-mil="[post-id]"' and return
        return '<a data-mil="' . get_the_ID() . '" ' . mb_substr($m[0], 3);
            
    }
    
    /*
     * Callback of the usort() function
     * 
     * This callback is used to avoid an anonimus function as a parameter of the
     * usort() function for PHP backward compatibility
     * 
     * Look for uses of usort_callback_1 to find which usort() function is
     * actually using this callback
     */
    public function usort_callback_1($a, $b){
        
        return $b['score'] - $a['score'];
        
    }
    
    /*
     * Remove the HTML comment ( comment enclosed between <!-- and --> )
     * 
     * @param $content The HTML with the comments
     * @return string The HTML without the comments
     */
    public function remove_html_comments($content){
        
        $content = preg_replace(
            '/
            <!--                                #1 Comment Start
            .*?                                 #2 Any character zero or more time with a lazy quantifier
            -->                                 #3 Comment End
            /ix',                               
            '',
            $content
        );
        
        return $content;
        
    }
    
    /*
     * Remove the script tags
     * 
     * @param $content The HTML with the script tags
     * @return string The HTML without the script tags
     */
    public function remove_script_tags($content){
        
        $content = preg_replace(
            '/
            <                                   #1 Begin the start-tag
            script                              #2 The script tag name
            (\s+[^>]*)?                         #3 Match the rest of the start-tag
            >                                   #4 End the start-tag
            .*?                                 #5 The element content ( with the "s" modifier the dot matches also the new lines )
            <\/script\s*>                       #6 The script end-tag with optional white-spaces before the closing >
            /ixs',                              
            '',
            $content
        );
        
        return $content;
        
    }

    /*
     * Get the number of records available in the "_archive" db table
     *
     * @return int The number of records in the "_archive" db table
     */
    public function number_of_records_in_archive(){

        global $wpdb;
        $table_name = $wpdb->prefix . $this->get('slug') . "_archive";
        $total_items = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");

        return $total_items;

    }

    /*
     * Get the number of records available in the "_juice" db table
     *
     * @return int The number of records in the "_juice" db table
     */
    public function number_of_records_in_juice(){

        global $wpdb;
        $table_name = $wpdb->prefix . $this->get('slug') . "_juice";
        $total_items = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");

        return $total_items;

    }

	/*
     * Check if the all the URLs in the "_http_status" db table have been checked and that there is at least one record.
     *
     * @return bool
     */
    public function complete_http_status_data_exists(){

	    global $wpdb;
	    $table_name    = $wpdb->prefix . $this->get( 'slug' ) . "_http_status";
	    $total_items   = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name" );
	    $checked_items = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name WHERE checked = 1" );

	    if ( $total_items > 0 and $total_items === $checked_items ) {
		    return true;
	    } else {
		    return false;
	    }

    }

    /*
     * Get the number of records available in the "_hits" db table
     *
     * @return int The number of records in the "_hits" db table
     */
    public function number_of_records_in_hits(){

        global $wpdb;
        $table_name = $wpdb->prefix . $this->get('slug') . "_hits";
        $total_items = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");

        return $total_items;

    }

	/*
	 * If $needle is present in the $haystack array echos 'selected="selected"'.
	 *
	 * @param $haystack Array
	 * @param $needle String
	 */
	public function selected_array($array, $needle)
	{

		if (is_array($array) and in_array($needle, $array)) {
			return 'selected="selected"';
		}

	}

	/*
	 * If the number of times that the parsed autolink URL ($this->parsed_autolink['url']) is present in the array that
	 * includes the data of the autolinks already applied as temporary identifiers ($this->ail_a) is equal or
	 * higher than the limit estabilished with the "Same URL Limit" option ($this->same_url_limit) True is returned,
	 * otherwise False is returned.
	 *
	 * @return Bool
	 */
	public function same_url_limit_reached()
	{

		$counter = 0;

		foreach ($this->ail_a as $key => $value) {
			if ($value['url'] === $this->parsed_autolink['url']) {
				$counter++;
			}
		}

		if ($counter >= $this->same_url_limit) {
			return true;
		} else {
			return false;
		}

	}

	/*
	 * With versions lower than 1.19 the list of protected block is stored in the "daim_protected_tags" option as a
	 * comma separated list of tags and not as a serialized array.
	 *
	 * This method:
	 *
	 * 1 - Retrieves the "daim_protected_tags" option value
	 * 2 - If the value is a string (pre 1.19) the protected tags are converted to an array
	 * 3 - Returns the array of protected tags
	 *
	 * @return Array
	 */
	public function get_protected_tags_option(){

		$protected_tags_a = [];
		$protected_tags = get_option("daim_protected_tags");
		if(is_string($protected_tags)){
			$protected_tags = str_replace(' ', '', $protected_tags);
			if(strlen($protected_tags) > 0){
				$protected_tags_a = explode(',', str_replace(' ', '', $protected_tags));
			}
		}else{
			$protected_tags_a = $protected_tags;
		}

		return $protected_tags_a;

	}

	/*
	 * Returns the maximum number of AIL allowed per post by using the method explained below.
	 *
	 * If the "General Limit Mode" option is set to "Auto":
	 *
	 * The maximum number of autolinks per post is calculated based on the content length of this post divided for the
	 * value of the "General Limit (Characters per AIL)" option.
	 *
	 * If the "General Limit Mode" option is set to "Manual":
	 *
	 * The maximum number of AIL per post is equal to the value of "General Limit (Max AIL per Post)".
	 *
	 * @param $post_id int The post ID for which the maximum number AIL per post should be calculated.
	 * @return int The maximum number of AIL allowed per post.
	 */
	private function get_max_number_autolinks_per_post($post_id, $post_content)
	{

		/**
		 * Calculate the maximumn umber of AIL that should be applied in the post based on the following options:
		 *
		 * - General Limit Mode
		 * - General Limit (Characters per AIL)
		 * - General Limit (Amount)
		 */
		if (intval(get_option($this->get('slug') . '_general_limit_mode'), 10) === 0) {

			//Auto -----------------------------------------------------------------------------------------------------
			$post_obj                = get_post($post_id);
			$post_length             = mb_strlen($post_obj->post_content);
			$characters_per_autolink = intval(get_option($this->get('slug') . '_characters_per_autolink'), 10);
			$number_of_ail = intval($post_length / $characters_per_autolink, 10);

		} else {

			//Manual ---------------------------------------------------------------------------------------------------
			$number_of_ail = intval(get_option($this->get('slug') . '_max_number_autolinks_per_post'), 10);

		}

		/**
		 * If the "General Limit (Subtract MIL) option is enabled subtract the number of existing MIL of the post
		 * ($number_of_mil) from the maximum number of AIL that should be applied in the post ($number_of_ail).
		 * Otherwise return the maximum number of AIL that should be applied in the post without further calculations.
		 */
		if(intval(get_option("daim_general_limit_subtract_mil"), 10) === 1){

			$number_of_mil = $this->get_manual_interlinks($post_content);
			$result = max($number_of_ail - $number_of_mil, 0);
			return intval($result, 10);

		}else{

			return $number_of_ail;

		}

	}

	/*
	 * Given the post object, the HTML content of the Interlinks Optimization meta-box is returned.
	 *
	 * @param $post The post object.
	 * @return String The HTML of the Interlinks Optimization meta-box.
	 */
	public function generate_interlinks_optimization_metabox_html($post){

		$html = '';

		$suggested_min_number_of_interlinks = $this->get_suggested_min_number_of_interlinks($post->ID);
		$suggested_max_number_of_interlinks = $this->get_suggested_max_number_of_interlinks($post->ID);
		$post_content_with_autolinks = $this->add_autolinks($post->post_content, false, $post->post_type, $post->ID);
		$number_of_manual_interlinks = $this->get_manual_interlinks($post->post_content);
		$number_of_autolinks = $this->get_autolinks_number($post_content_with_autolinks);
		$total_number_of_interlinks = $number_of_manual_interlinks + $number_of_autolinks;
		if($total_number_of_interlinks >= $suggested_min_number_of_interlinks and $total_number_of_interlinks <= $suggested_max_number_of_interlinks){
			$html .= '<p>' . esc_html__('The number of interlinks included in this post is optimized.', 'daim') . '</p>';
		}else{
			$html .= '<p>' . esc_html__('Please optimize the number of interlinks, this post currently has', 'daim') . '&nbsp' . $total_number_of_interlinks . '&nbsp' . _n('interlink', 'interlinks', $total_number_of_interlinks, 'daim') . '. (' . $number_of_manual_interlinks . '&nbsp' . _n('manual interlink', 'manual interlinks', $number_of_manual_interlinks, 'daim') . '&nbsp' . esc_html__('and', 'daim') . '&nbsp' . $number_of_autolinks . '&nbsp' . _n('auto interlink', 'auto interlinks', $number_of_autolinks, 'daim') . ')</p>';
			if($suggested_min_number_of_interlinks === $suggested_max_number_of_interlinks){
				$html .= '<p>' . esc_html__('Based on the content length and on your options their number should be', 'daim') . '&nbsp' . $suggested_min_number_of_interlinks . '.</p>';
			}else{
				$html .= '<p>' . esc_html__('Based on the content length and on your options their number should be included between', 'daim') . '&nbsp' . $suggested_min_number_of_interlinks . '&nbsp' . esc_html__('and', 'daim') . '&nbsp' . $suggested_max_number_of_interlinks . '.</p>';
			}
		}

		return $html;

	}

	/*
	 * To avoid additional database requests for each autolink in preg_replace_callback_2() save the data of the
	 * autolink in an array that uses the "autolink_id" as its index.
	 *
	 * @param $autolinks Array
	 * @return Array
	 */
	public function save_autolinks_in_custom_array($autolinks)
	{

		$autolinks_ca = array();

		foreach ($autolinks as $key => $autolink) {

			$autolinks_ca[$autolink['id']] = $autolink;

		}

		return $autolinks_ca;

	}

	/*
	 * Applies a random order (based on the hash of the post_id and autolink_id) to the autolinks that have the same
	 * priority. This ensures a better distribution of the autolinks.
	 *
	 * @param $autolink Array
	 * @param $post_id Int
	 * @return Array
	 */
	public function apply_random_prioritization($autolinks, $post_id)
	{

		//Initialize variables
		$autolinks_rp1 = array();
		$autolinks_rp2 = array();

		//Move the autolinks array in the new $autolinks_rp1 array, which uses the priority value as its index
		foreach ($autolinks as $key => $autolink) {

			$autolinks_rp1[$autolink['priority']][] = $autolink;

		}

		/*
		 * Apply a random order (based on the hash of the post_id and autolink_id) to the autolinks that have the same
		 * priority.
		 */
		foreach ($autolinks_rp1 as $key => $autolinks_a) {

			/*
			 * In each autolink create the new "hash" field which include an hash value based on the post_id and on the
			 * autolink id.
			 */
			foreach ($autolinks_a as $key2 => $autolink) {

				/*
				 * Create the hased value. Note that the "-" character is used to avoid situations where the same input
				 * is provided to the md5() function.
				 *
				 * Without the "-" character for example with:
				 *
				 * $post_id = 12 and $autolink['id'] = 34
				 *
				 * We provide the same input of:
				 *
				 * $post_id = 123 and $autolink['id'] = 4
				 *
				 * etc.
				 */
				$hash = hexdec(md5($post_id . '-' . $autolink['id']));

				/*
				 * Convert all the non-digits to the character "1", this makes the comparison performed in the usort
				 * callback possible.
				 */
				$autolink['hash']   = preg_replace('/\D/', '1', $hash, -1, $replacement_done);
				$autolinks_a[$key2] = $autolink;

			}

			//Sort $autolinks_a based on the new value of the "hash" field
			usort($autolinks_a, function ($a, $b) {

				return $b['hash'] - $a['hash'];

			});

			$autolinks_rp1[$key] = $autolinks_a;

		}

		/*
		 * Move the autolinks in the new $autolinks_rp2 array, which is structured like the original array, where the
		 * value of the priority field is stored in the autolink and it's not used as the index of the array that
		 * includes all the autolinks with the same priority.
		 */
		foreach ($autolinks_rp1 as $key => $autolinks_a) {

			for ($t = 0; $t < (count($autolinks_a)); $t++) {

				$autolink        = $autolinks_a[$t];
				$autolinks_rp2[] = $autolink;

			}

		}

		return $autolinks_rp2;

	}

	/*
	 * Returns true if one or more AIL are using the specified category.
	 *
	 * @param $category_id Int
	 * @return bool
	 */
	public function category_is_used($category_id)
	{

		global $wpdb;

		$table_name  = $wpdb->prefix . $this->get('slug') . "_autolinks";
		$safe_sql    = $wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE category_id = %d", $category_id);
		$total_items = $wpdb->get_var($safe_sql);

		if ($total_items > 0) {
			return true;
		} else {
			return false;
		}

	}

	/*
	 * Given the category ID the category name is returned.
	 *
	 * @param $category_id Int
	 * @return String
	 */
	public function get_category_name($category_id)
	{

		if (intval($category_id, 10) === 0) {
			return __('None', 'daim');
		}

		global $wpdb;
		$table_name   = $wpdb->prefix . $this->get('slug') . "_category";
		$safe_sql     = $wpdb->prepare("SELECT * FROM $table_name WHERE category_id = %d ", $category_id);
		$category_obj = $wpdb->get_row($safe_sql);

		return $category_obj->name;

	}

	/*
	 * Returns true if the category with the specified $category_id exists.
	 *
	 * @param $category_id Int
	 * @return bool
	 */
	public function category_exists($category_id)
	{

		global $wpdb;

		$table_name  = $wpdb->prefix . $this->get('slug') . "_category";
		$safe_sql    = $wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE category_id = %d", $category_id);
		$total_items = $wpdb->get_var($safe_sql);

		if ($total_items > 0) {
			return true;
		} else {
			return false;
		}

	}

	/**
	 * Returns the number of items in the "anchors" database table with the specified "url".
	 *
	 * @param $url
	 * @return int
	 */
	public function get_anchors_with_url($url){

		global $wpdb;
		$table_name = $wpdb->prefix . $this->get('slug') . "_anchors";
		$safe_sql = $wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE url = %s ORDER BY id DESC", $url);
		$total_items = $wpdb->get_var($safe_sql);

		return intval($total_items);

	}

    /*
     * Get an array with the post types with UI except the attachment post type.
     *
     * @return Array
     */
    public function get_post_types_with_ui()
    {

        //Get all the post types with UI
        $args               = array(
            'public'  => true,
            'show_ui' => true
        );
        $post_types_with_ui = get_post_types($args);

        //Remove the attachment post type
        unset($post_types_with_ui['attachment']);

        //Replace the associative index with a numeric index
        $temp_array = array();
        foreach ($post_types_with_ui as $key => $value) {
            $temp_array[] = $value;
        }
        $post_types_with_ui = $temp_array;

        return $post_types_with_ui;

    }

    /*
     * Returns True if the post has the categories required by the autolink or if the autolink doesn't require any
     * specific category.
     *
     * @return Bool
     */
    private function is_compliant_with_categories($post_id, $autolink)
    {

        $autolink_categories_a = maybe_unserialize($autolink['categories']);
        $post_categories       = get_the_terms($post_id, 'category');
        $category_found        = false;

        //If no categories are specified return true
        if ( ! is_array($autolink_categories_a)) {
            return true;
        }

	    //If the post has no categories return false
	    if(!is_array($post_categories)){
		    return false;
	    }

        /*
         * Do not proceed with the application of the autolink if in this post no categories included in
         * $autolink_categories_a are available.
         */
        foreach ($post_categories as $key => $post_single_category) {
            if (in_array($post_single_category->term_id, $autolink_categories_a)) {
                $category_found = true;
            }
        }

        if ($category_found) {
            return true;
        } else {
            return false;
        }

    }

    /*
     * Returns True if the post has the tags required by the autolink or if the autolink doesn't require any specific
     * tag.
     *
     * @return Bool
     */
    private function is_compliant_with_tags($post_id, $autolink)
    {

        $autolink_tags_a = maybe_unserialize($autolink['tags']);
        $post_tags       = get_the_terms($post_id, 'post_tag');
        $tag_found       = false;

        //If no tags are specified return true
        if ( ! is_array($autolink_tags_a)) {
            return true;
        }

        if ($post_tags !== false) {

            /*
             * Do not proceed with the application of the autolink if this post has at least one tag but no tags
             * included in $autolink_tags_a are available.
             */
            foreach ($post_tags as $key => $post_single_tag) {
                if (in_array($post_single_tag->term_id, $autolink_tags_a)) {
                    $tag_found = true;
                }
            }
            if ( ! $tag_found) {
                return false;
            }

        } else {

            //Do not proceed with the application of the autolink if this post has no tags associated
            return false;

        }

        return true;

    }

    /*
     * Verifies if the post includes at least one term included in the term group associated with the autolink.
     *
     * In the following conditions True is returned:
     *
     * - When a term group is not set
     * - When the post has at least one term present in the term group
     */
    private function is_compliant_with_term_group($post_id, $autolink)
    {

        $supported_terms = intval(get_option($this->get('slug') . '_supported_terms'), 10);

        global $wpdb;
        $table_name     = $wpdb->prefix . $this->get('slug') . "_term_group";
        $safe_sql       = $wpdb->prepare("SELECT * FROM $table_name WHERE term_group_id = %d ",
            $autolink['term_group_id']);
        $term_group_obj = $wpdb->get_row($safe_sql);

        if ($term_group_obj !== null) {

            for ($i = 1; $i <= $supported_terms; $i++) {

                $post_type = $term_group_obj->{'post_type_' . $i};
                $taxonomy  = $term_group_obj->{'taxonomy_' . $i};
                $term      = $term_group_obj->{'term_' . $i};

                //Verify post type, taxonomy and term as specified in the term group
                if ($post_type === $this->parsed_post_type and has_term($term, $taxonomy, $post_id)) {
                    return true;
                }

            }

            return false;

        }

        return true;

    }

    /*
     * Fires after a term is deleted from the database and the cache is cleaned.
     *
     * The following tasks are performed:
     *
     * Part 1 - Deletes the $term_id found in the categories field of the autolinks
     * Part 2 - Deletes the $term_id found in the tags field of the autolinks
     * Part 3 - Deletes the $term_id found in the 50 term_[n] fields of the term groups
     */
    public function delete_term_action($term_id, $term_taxonomy_id, $taxonomy_slug)
    {

        //Part 1-2 -------------------------------------------------------------------------------------------------------

        global $wpdb;
        $table_name = $wpdb->prefix . $this->get('slug') . "_autolinks";
        $autolink_a = $wpdb->get_results("SELECT * FROM $table_name ORDER BY id ASC", ARRAY_A);

        if ($autolink_a !== null and count($autolink_a) > 0) {

            foreach ($autolink_a as $key1 => $autolink) {

                //Delete the term in the categories field of the autolinks
                $category_term_a = maybe_unserialize($autolink['categories']);
                if (is_array($category_term_a) and count($category_term_a) > 0) {
                    foreach ($category_term_a as $key2 => $category_term) {
                        if (intval($category_term, 10) === $term_id) {
                            unset($category_term_a[$key2]);
                        }
                    }
                }
                $category_term_a_serialized = maybe_serialize($category_term_a);

                //Delete the term in the tags field of the autolinks
                $tag_term_a = maybe_unserialize($autolink['tags']);
                if (is_array($tag_term_a) and count($tag_term_a) > 0) {
                    foreach ($tag_term_a as $key2 => $tag_term) {
                        if (intval($tag_term, 10) === $term_id) {
                            unset($tag_term_a[$key2]);
                        }
                    }
                }
                $tag_term_a_serialized = maybe_serialize($tag_term_a);

                //Update the record of the database if $categories or $tags are changed
                if ($autolink['categories'] !== $category_term_a_serialized or
                    $autolink['tags'] !== $tag_term_a_serialized) {

                    $safe_sql = $wpdb->prepare("UPDATE $table_name SET 
                        categories = %s,
                        tags = %s
                        WHERE id = %d",
                        $category_term_a_serialized,
                        $tag_term_a_serialized,
                        $autolink['id']);

                    $wpdb->query($safe_sql);

                }

            }


        }

        //Part 3 -------------------------------------------------------------------------------------------------------

        //Delete the term in all the 50 term_[n] field of the term groups
        $table_name   = $wpdb->prefix . $this->get('slug') . "_term_group";
        $term_group_a = $wpdb->get_results("SELECT * FROM $table_name ORDER BY term_group_id ASC", ARRAY_A);

        if ($term_group_a !== null and count($term_group_a) > 0) {

            foreach ($term_group_a as $key => $term_group) {

                $no_terms = true;
                for ($i = 1; $i <= 50; $i++) {

                    if (intval($term_group['term_' . $i], 10) === $term_id) {
                        $term_group['post_type_' . $i] = '';
                        $term_group['taxonomy_' . $i]  = '';
                        $term_group['term_' . $i]      = 0;
                    }

                    if (intval($term_group['term_' . $i], 10) !== 0) {
                        $no_terms = false;
                    }

                }

                /*
                 * If all the terms of the term group are empty delete the term group and reset the association between
                 * autolinks and this term group. If there are terms in the term group update the term group.
                 */
                if ($no_terms) {

                    //Delete the term group
                    $safe_sql     = $wpdb->prepare("DELETE FROM $table_name WHERE term_group_id = %d ",
                        $term_group['term_group_id']);
                    $query_result = $wpdb->query($safe_sql);

                    //If the term group is used reset the association between the autolinks and this term group
                    if ($this->term_group_is_used($term_group['term_group_id'])) {

                        //reset the association between the autolinks and this term group
                        $safe_sql = $wpdb->prepare("UPDATE $table_name SET 
                                    term_group_id = 0,
                                    WHERE term_group_id = %d",
                            $term_group['term_group_id']);

                    }

                } else {

                    //Update the term group

                    $query_part = '';
                    for ($i = 1; $i <= 50; $i++) {
                        $query_part .= 'post_type_' . $i . ' = %s,';
                        $query_part .= 'taxonomy_' . $i . ' = %s,';
                        $query_part .= 'term_' . $i . ' = %d';
                        if ($i !== 50) {
                            $query_part .= ',';
                        }
                    }

                    //update the database
                    global $wpdb;
                    $table_name = $wpdb->prefix . $this->get('slug') . "_term_group";
                    $safe_sql   = $wpdb->prepare("UPDATE $table_name SET
                        $query_part
                        WHERE term_group_id = %d",
                        $term_group["post_type_1"], $term_group["taxonomy_1"], $term_group["term_1"],
                        $term_group["post_type_2"], $term_group["taxonomy_2"], $term_group["term_2"],
                        $term_group["post_type_3"], $term_group["taxonomy_3"], $term_group["term_3"],
                        $term_group["post_type_4"], $term_group["taxonomy_4"], $term_group["term_4"],
                        $term_group["post_type_5"], $term_group["taxonomy_5"], $term_group["term_5"],
                        $term_group["post_type_6"], $term_group["taxonomy_6"], $term_group["term_6"],
                        $term_group["post_type_7"], $term_group["taxonomy_7"], $term_group["term_7"],
                        $term_group["post_type_8"], $term_group["taxonomy_8"], $term_group["term_8"],
                        $term_group["post_type_9"], $term_group["taxonomy_9"], $term_group["term_9"],
                        $term_group["post_type_10"], $term_group["taxonomy_10"], $term_group["term_10"],
                        $term_group["post_type_11"], $term_group["taxonomy_11"], $term_group["term_11"],
                        $term_group["post_type_12"], $term_group["taxonomy_12"], $term_group["term_12"],
                        $term_group["post_type_13"], $term_group["taxonomy_13"], $term_group["term_13"],
                        $term_group["post_type_14"], $term_group["taxonomy_14"], $term_group["term_14"],
                        $term_group["post_type_15"], $term_group["taxonomy_15"], $term_group["term_15"],
                        $term_group["post_type_16"], $term_group["taxonomy_16"], $term_group["term_16"],
                        $term_group["post_type_17"], $term_group["taxonomy_17"], $term_group["term_17"],
                        $term_group["post_type_18"], $term_group["taxonomy_18"], $term_group["term_18"],
                        $term_group["post_type_19"], $term_group["taxonomy_19"], $term_group["term_19"],
                        $term_group["post_type_20"], $term_group["taxonomy_20"], $term_group["term_20"],
                        $term_group["post_type_21"], $term_group["taxonomy_21"], $term_group["term_21"],
                        $term_group["post_type_22"], $term_group["taxonomy_22"], $term_group["term_22"],
                        $term_group["post_type_23"], $term_group["taxonomy_23"], $term_group["term_23"],
                        $term_group["post_type_24"], $term_group["taxonomy_24"], $term_group["term_24"],
                        $term_group["post_type_25"], $term_group["taxonomy_25"], $term_group["term_25"],
                        $term_group["post_type_26"], $term_group["taxonomy_26"], $term_group["term_26"],
                        $term_group["post_type_27"], $term_group["taxonomy_27"], $term_group["term_27"],
                        $term_group["post_type_28"], $term_group["taxonomy_28"], $term_group["term_28"],
                        $term_group["post_type_29"], $term_group["taxonomy_29"], $term_group["term_29"],
                        $term_group["post_type_30"], $term_group["taxonomy_30"], $term_group["term_30"],
                        $term_group["post_type_31"], $term_group["taxonomy_31"], $term_group["term_31"],
                        $term_group["post_type_32"], $term_group["taxonomy_32"], $term_group["term_32"],
                        $term_group["post_type_33"], $term_group["taxonomy_33"], $term_group["term_33"],
                        $term_group["post_type_34"], $term_group["taxonomy_34"], $term_group["term_34"],
                        $term_group["post_type_35"], $term_group["taxonomy_35"], $term_group["term_35"],
                        $term_group["post_type_36"], $term_group["taxonomy_36"], $term_group["term_36"],
                        $term_group["post_type_37"], $term_group["taxonomy_37"], $term_group["term_37"],
                        $term_group["post_type_38"], $term_group["taxonomy_38"], $term_group["term_38"],
                        $term_group["post_type_39"], $term_group["taxonomy_39"], $term_group["term_39"],
                        $term_group["post_type_40"], $term_group["taxonomy_40"], $term_group["term_40"],
                        $term_group["post_type_41"], $term_group["taxonomy_41"], $term_group["term_41"],
                        $term_group["post_type_42"], $term_group["taxonomy_42"], $term_group["term_42"],
                        $term_group["post_type_43"], $term_group["taxonomy_43"], $term_group["term_43"],
                        $term_group["post_type_44"], $term_group["taxonomy_44"], $term_group["term_44"],
                        $term_group["post_type_45"], $term_group["taxonomy_45"], $term_group["term_45"],
                        $term_group["post_type_46"], $term_group["taxonomy_46"], $term_group["term_46"],
                        $term_group["post_type_47"], $term_group["taxonomy_47"], $term_group["term_47"],
                        $term_group["post_type_48"], $term_group["taxonomy_48"], $term_group["term_48"],
                        $term_group["post_type_49"], $term_group["taxonomy_49"], $term_group["term_49"],
                        $term_group["post_type_50"], $term_group["taxonomy_50"], $term_group["term_50"],
                        $term_group['term_group_id']);

                    $query_result = $wpdb->query($safe_sql);

                }

            }

        }

    }

    /**
     * Make the database data compatible with the new plugin versions.
     *
     * Only Task 1 is available at the moment. Use Task 2, Task 3, etc. for additional operation on the database.
     */
    public function convert_database_data(){

        /**
         * Task 1:
         *
         * Convert all the values of the category field of the autolinks saved as a comma separated list of values
         * to an array.
         *
         * Note that the category field of the autolinks is saved serialized starting from version 1.26
         */
        global $wpdb;
        $table_name = $wpdb->prefix . $this->get('slug') . "_autolinks";
        $autolink_a = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);

        //if there are data generate the csv header and content
        if( count( $autolink_a ) > 0 ){

            foreach ( $autolink_a as $autolink ) {

                if(strlen($autolink['activate_post_types']) === 0 or is_serialized($autolink['activate_post_types'])){
                    continue;
                }

                $activate_post_types = preg_replace('/\s+/', '', $autolink['activate_post_types']);
                $post_type_a = explode(",", $activate_post_types);

                if(is_array($post_type_a)){

                    $post_type_serialized = maybe_serialize($post_type_a);
                    $autolink_id = intval($autolink['id'], 10);

                    $table_name = $wpdb->prefix . $this->get('slug') . "_autolinks";
                    $safe_sql = $wpdb->prepare("UPDATE $table_name SET 
                        activate_post_types = %s
                        WHERE id = %d",
                        $post_type_serialized,
                        $autolink_id);

                    $wpdb->query( $safe_sql );

                }

            }

        }

    }

    /**
     * Make the database data compatible with the new plugin versions.
     *
     * Only Task 1 is available at the moment. Use Task 2, Task 3, etc. for additional operation on the database.
     */
    public function convert_options_data(){

        /**
         * Task 1:
         *
         * Convert all the options that include list of post types saved as a comma separated list of values to an
         * array.
         *
         * Note that these options are saved serialized starting from version 1.26
         */
        $option_name_a = [
            '_default_activate_post_types',
            '_suggestions_pool_post_types',
            '_dashboard_post_types',
            '_juice_post_types',
            '_interlinks_options_post_types',
            '_interlinks_optimization_post_types',
            '_interlinks_suggestions_post_types'
        ];

        foreach ( $option_name_a as $option_name ) {

            $option_value = get_option($this->get('slug') . $option_name);

            if(is_array($option_value) or (is_string($option_value) and strlen($option_value) === 0)){
                continue;
            }

            $activate_post_types = preg_replace('/\s+/', '', $option_value);
            $post_type_a = explode(",", $activate_post_types);

            if(is_array($post_type_a)){
                update_option($this->get('slug') . $option_name, $post_type_a);
            }

        }

    }

    /*
     * Returns true if the term group with the specified $term_group_id exists.
     *
     * @param $term_group_id Int
     * @return bool
     */
    public function term_group_exists($term_group_id)
    {

        global $wpdb;

        $table_name  = $wpdb->prefix . $this->get('slug') . "_term_group";
        $safe_sql    = $wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE term_group_id = %d", $term_group_id);
        $total_items = $wpdb->get_var($safe_sql);

        if ($total_items > 0) {
            return true;
        } else {
            return false;
        }

    }

    /*
     * Returns true if one or more autolinks are using the specified term group.
     *
     * @param $term_group_id Int
     * @return bool
     */
    public function term_group_is_used($term_group_id)
    {

        global $wpdb;

        $table_name  = $wpdb->prefix . $this->get('slug') . "_autolinks";
        $safe_sql    = $wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE term_group_id = %d", $term_group_id);
        $total_items = $wpdb->get_var($safe_sql);

        if ($total_items > 0) {
            return true;
        } else {
            return false;
        }

    }

    /*
 * Returns true if there are exportable data or false if here are no exportable data.
 */
    public function exportable_data_exists()
    {

        $exportable_data = false;
        global $wpdb;

        $table_name  = $wpdb->prefix . $this->get('slug') . "_autolinks";
        $total_items = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
        if ($total_items > 0) {
            $exportable_data = true;
        }

        $table_name  = $wpdb->prefix . $this->get('slug') . "_category";
        $total_items = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
        if ($total_items > 0) {
            $exportable_data = true;
        }

        $table_name  = $wpdb->prefix . $this->get('slug') . "_term_group";
        $total_items = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
        if ($total_items > 0) {
            $exportable_data = true;
        }

        return $exportable_data;

    }

    /*
     * Generates the XML version of the data of the table.
     *
     * @param db_table_name The name of the db table without the prefix.
     * @param db_table_primary_key The name of the primary key of the table
     * @return String The XML version of the data of the db table
     */
    public function convert_db_table_to_xml($db_table_name, $db_table_primary_key)
    {

        $out = '';

        //get the data from the db table
        global $wpdb;
        $table_name = $wpdb->prefix . $this->get('slug') . "_$db_table_name";
        $data_a     = $wpdb->get_results("SELECT * FROM $table_name ORDER BY $db_table_primary_key ASC", ARRAY_A);

        //generate the data of the db table
        foreach ($data_a as $record) {

            $out .= "<$db_table_name>";

            //get all the indexes of the $data array
            $record_keys = array_keys($record);

            //cycle through all the indexes of the single record and create all the XML tags
            foreach ($record_keys as $key) {
                $out .= "<" . $key . ">" . esc_attr($record[$key]) . "</" . $key . ">";
            }

            $out .= "</$db_table_name>";

        }

        return $out;

    }

    /*
     * Objects as a value are set to empty strings. This prevent to generate notices with the methods of the wpdb class.
     *
     * @param $data An array which includes objects that should be converted to a empty strings.
     * @return string An array where the objects have been replaced with empty strings.
     */
    public function replace_objects_with_empty_strings($data)
    {

        foreach ($data as $key => $value) {
            if (gettype($value) === 'object') {
                $data[$key] = '';
            }
        }

        return $data;

    }

    /*
     * Set the PHP "Max Execution Time" and "Memory Limit" based on the values defined in the options.
     */
    public function set_met_and_ml(){

        /*
         * Set the custom "Max Execution Time Value" defined in the options if
         * the 'Set Max Execution Time' option is set to "Yes"
         */
        if( intval( get_option( $this->get('slug') . '_set_max_execution_time') , 10) === 1 ){
            ini_set('max_execution_time', intval(get_option("daim_max_execution_time_value"), 10));
        }

        /*
         * Set the custom "Memory Limit Value" ( in megabytes ) defined in the
         * options if the 'Set Memory Limit' option is set to "Yes"
         */
        if( intval( get_option( $this->get('slug') . '_set_memory_limit') , 10) === 1 ){
            ini_set('memory_limit', intval(get_option("daim_memory_limit_value"), 10) . 'M');
        }

    }

	/**
	 * Execute the cron jobs.
	 */
	public function daextdaim_cron_exec() {

		//Get the HTTP response status code of a limited number of URLs saved in the "http_status" db table.
		$this->check_http_status();

		/**
		 * Check in the "_http_status" db table if all the links have been checked. (if there are zero links to check)
		 * If all the links have been checked, clear the schedule of the cron hook.
		 */
		global $wpdb;
		$table_name = $wpdb->prefix  . $this->get('slug') . "_http_status";
		$count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE checked = 0");
		if(intval($count, 10) === 0){
			wp_clear_scheduled_hook( 'daextdaim_cron_hook' );
		}

	}

	/**
	 * Create the list of URLs for which the HTTP status code should be checked. The records are saved in the
	 * "_http_status" db table.
	 */
	public function create_http_status_list(){

		//Set the PHP "Max Execution Time" and "Memory Limit" based on the values defined in the options
		$this->set_met_and_ml();

		//delete all the items in the "_http_status" db table
		global $wpdb;
		$table_name = $wpdb->prefix . $this->get('slug') . "_http_status";
		$wpdb->query("TRUNCATE TABLE $table_name");

		/*
		 * Create a query used to consider in the analysis only the post types selected with the
		 * HTTP Status Post Types option.
		 */
		$http_status_post_types_a = maybe_unserialize(get_option($this->get('slug') . '_http_status_post_types' ));
		$post_types_query = '';
		if(is_array($http_status_post_types_a)){
			foreach($http_status_post_types_a as $key => $value){

				if (!preg_match("/[a-z0-9_-]+/", $value)) {continue;}

				$post_types_query .= "post_type = '" . $value . "'";
				if($key != ( count($http_status_post_types_a) - 1 )){$post_types_query .= ' OR ';}

			}
		}
		
		//get all the considered posts
		global $wpdb;
		$table_name = $wpdb->prefix . "posts";
		$limit_posts_analysis = intval(get_option($this->get('slug') . '_limit_posts_analysis'), 10);
		$safe_sql = "SELECT ID, post_title, post_type, post_date, post_content FROM $table_name WHERE ($post_types_query) AND post_status = 'publish' ORDER BY post_date DESC LIMIT " . $limit_posts_analysis;
		$posts_a = $wpdb->get_results($safe_sql, ARRAY_A);

		foreach ($posts_a as $key => $single_post) {

			//set the post content
			$post_content = $single_post['post_content'];

			//remove the HTML comments
			$post_content = $this->remove_html_comments( $post_content );

			//remove script tags
			$post_content = $this->remove_script_tags( $post_content );

			//Apply the auto interlinks to the post content
			$post_content_with_autolinks = $this->add_autolinks( $post_content, false, $single_post['post_type'], $single_post['ID'] );

			/*
			 * Get the website url and quote and escape the regex character. # and
			 * whitespace ( used with the 'x' modifier ) are not escaped, thus
			 * should not be included in the $site_url string
			 */
			$site_url = preg_quote( get_home_url() );

			//Find all the manual and auto interlinks matches with a regex and add them in an array
			preg_match_all(
				'{<a                                #1 Begin the element a start-tag
                [^>]+                               #2 Any character except > at least one time
                href\s*=\s*                         #3 Equal may have whitespaces on both sides
                ([\'"]?)                            #4 Match double quotes, single quote or no quote ( captured for the backreference \1 )
                (' . $site_url . '[^\'">\s]* )      #5 The site URL ( Scheme and Domain ) and the rest of the URL ( Path and/or File ) ( captured )
                \1                                  #6 Backreference that matches the href value delimiter matched at line 4     
                [^>]*                               #7 Any character except > zero or more times
                >                                   #8 End of the start-tag
                (.*?)                               #9 Link text or nested tags. After the dot ( enclose in parenthesis ) negative lookbehinds can be applied to avoid specific stuff inside the link text or nested tags. Example with single negative lookbehind (.(?<!word1))*? Example with multiple negative lookbehind (.(?<!word1)(?<!word2)(?<!word3))*?
                <\/a\s*>                            #10 Element a end-tag with optional white-spaces characters before the >
                }ix',
				$post_content_with_autolinks, $matches, PREG_OFFSET_CAPTURE );

			//save the URL and other data in the "_http_status" db table
			$captures = $matches[2];
			foreach ( $captures as $key => $single_capture ) {

				//Get the current time
				$date = current_time('mysql');
				$date_gmt = current_time('mysql', 1);

				//save the link in the "http_status" db table
				global $wpdb;
				$table_name = $wpdb->prefix . $this->get('slug') . "_http_status";
				$safe_sql = $wpdb->prepare("INSERT INTO $table_name SET 
                    post_id = %d,
                    post_title = %s,
                    url = %s,
                    anchor = %s,
                    checked = %d,
                    last_check_date = %s,
                    last_check_date_gmt = %s",
					$single_post['ID'],
					get_the_title($single_post['ID']),
					$single_capture[0],
					$matches[3][$key][0],
					0,
					$date,
					$date_gmt
				);

				$wpdb->query( $safe_sql );

			}

		}

	}

	/**
	 * Get the HTTP response status code of a limited number of URLs saved in the "http_status" db table.
	 *
	 * Note that:
	 *
	 * - The number of URLs checked per run of this function is set in the "http_status_checks_per_iteration" option.
	 * - The timeout of the HTTP request is set in the "http_status_request_timeout" option.
	 * - This function runs in a WP-Cron job.
	 */
	public function check_http_status(){

		$http_status_checks_per_iteration = intval(get_option($this->get('slug') . '_http_status_checks_per_iteration'), 10);
		$http_status_request_timeout = intval(get_option($this->get('slug') . '_http_status_request_timeout'), 10);

		//iterate through the url available in the http_status db table
		global $wpdb;
		$table_name = $wpdb->prefix . $this->get('slug') . "_http_status";
		$safe_sql = $wpdb->prepare("SELECT * FROM $table_name WHERE checked = 0 ORDER BY id ASC LIMIT %d",
			$http_status_checks_per_iteration);
		$http_status_a = $wpdb->get_results($safe_sql, ARRAY_A);

		//iterate through $http_status_a
		foreach($http_status_a as $key => $http_status){

			//check the http response of the url
			$response = wp_remote_get( $http_status['url'], array( 'timeout' => $http_status_request_timeout ) );
			$status_code = wp_remote_retrieve_response_code($response);

			//Get the current time
			$date = current_time('mysql');
			$date_gmt = current_time('mysql', 1);

			//update the checked field to 1 in the "_http_status" db table for the iterated id
			global $wpdb;
			$table_name = $wpdb->prefix . $this->get('slug') . "_http_status";
			$safe_sql = $wpdb->prepare("UPDATE $table_name SET
				checked = %d,
                last_check_date = %s,
                last_check_date_gmt = %s,
                code = %d
				WHERE id = %d",
				1,
				$date,
				$date_gmt,
				$status_code,
				$http_status['id']
			);

			$wpdb->query( $safe_sql );

		}

	}

	public function schedule_cron_event() {
		if ( ! wp_next_scheduled( 'daextdaim_cron_hook' ) ) {
			wp_schedule_event( time(), 'daim_custom_schedule', 'daextdaim_cron_hook' );
		}
	}

	/**
	 * Given the value of the http response status code, return the status code description.
	 */
	public function get_status_code_description( $http_status_code ) {

		//add the status code description
		switch ( $http_status_code ) {

			//1xx - Informational responses
			case 100:
				$http_status_code_description = 'Continue';
				break;
			case 101:
				$http_status_code_description = 'Switching Protocols';
				break;
			case 102:
				$http_status_code_description = 'Processing';
				break;
			case 103:
				$http_status_code_description = 'Early Hints';
				break;

			//2xx - Successful responses
			case 200:
				$http_status_code_description = 'OK';
				break;
			case 201:
				$http_status_code_description = 'Created';
				break;
			case 202:
				$http_status_code_description = 'Accepted';
				break;
			case 203:
				$http_status_code_description = 'Non-Authoritative Information';
				break;
			case 204:
				$http_status_code_description = 'No Content';
				break;
			case 205:
				$http_status_code_description = 'Reset Content';
				break;
			case 206:
				$http_status_code_description = 'Partial Content';
				break;
			case 207:
				$http_status_code_description = 'Multi-Status';
				break;
			case 208:
				$http_status_code_description = 'Already Reported';
				break;
			case 226:
				$http_status_code_description = 'IM Used';
				break;

			//3xx - Redirection messages
			case 300:
				$http_status_code_description = 'Multiple Choices';
				break;
			case 301:
				$http_status_code_description = 'Moved Permanently';
				break;
			case 302:
				$http_status_code_description = 'Found';
				break;
			case 303:
				$http_status_code_description = 'See Other';
				break;
			case 304:
				$http_status_code_description = 'Not Modified';
				break;
			case 305:
				$http_status_code_description = 'Use Proxy';
				break;
			case 306:
				$http_status_code_description = 'unused';
				break;
			case 307:
				$http_status_code_description = 'Temporary Redirect';
				break;
			case 308:
				$http_status_code_description = 'Permanent Redirect';
				break;

			//4xx - Client error responses
			case 400:
				$http_status_code_description = 'Bad Request';
				break;
			case 401:
				$http_status_code_description = 'Unauthorized';
				break;
			case 402:
				$http_status_code_description = 'Payment Required';
				break;
			case 403:
				$http_status_code_description = 'Forbidden';
				break;
			case 404:
				$http_status_code_description = 'Not Found';
				break;
			case 405:
				$http_status_code_description = 'Method Not Allowed';
				break;
			case 406:
				$http_status_code_description = 'Not Acceptable';
				break;
			case 407:
				$http_status_code_description = 'Proxy Authentication Required';
				break;
			case 408:
				$http_status_code_description = 'Request Timeout';
				break;
			case 409:
				$http_status_code_description = 'Conflict';
				break;
			case 410:
				$http_status_code_description = 'Gone';
				break;
			case 411:
				$http_status_code_description = 'Length Required';
				break;
			case 412:
				$http_status_code_description = 'Precondition Failed';
				break;
			case 413:
				$http_status_code_description = 'Payload Too Large';
				break;
			case 414:
				$http_status_code_description = 'URI Too Long';
				break;
			case 415:
				$http_status_code_description = 'Unsupported Media Type';
				break;
			case 416:
				$http_status_code_description = 'Range Not Satisfiable';
				break;
			case 417:
				$http_status_code_description = 'Expectation Failed';
				break;
			case 418:
				$http_status_code_description = 'I\'m a teapot';
				break;
			case 421:
				$http_status_code_description = 'Misdirected Request';
				break;
			case 422:
				$http_status_code_description = 'Unprocessable Content';
				break;
			case 423:
				$http_status_code_description = 'Locked';
				break;
			case 424:
				$http_status_code_description = 'Failed Dependency';
				break;
			case 426:
				$http_status_code_description = 'Upgrade Required';
				break;
			case 428:
				$http_status_code_description = 'Precondition Required';
				break;
			case 429:
				$http_status_code_description = 'Too Many Requests';
				break;
			case 431:
				$http_status_code_description = 'Request Header Fields Too Large';
				break;
			case 451:
				$http_status_code_description = 'Unavailable For Legal Reasons';
				break;

			//5xx - Server error responses
			case 500:
				$http_status_code_description = 'Internal Server Error';
				break;
			case 501:
				$http_status_code_description = 'Not Implemented';
				break;
			case 502:
				$http_status_code_description = 'Bad Gateway';
				break;
			case 503:
				$http_status_code_description = 'Service Unavailable';
				break;
			case 504:
				$http_status_code_description = 'Gateway Timeout';
				break;
			case 505:
				$http_status_code_description = 'HTTP Version Not Supported';
				break;
			case 506:
				$http_status_code_description = 'Variant Also Negotiates';
				break;
			case 507:
				$http_status_code_description = 'Insufficient Storage';
				break;
			case 508:
				$http_status_code_description = 'Loop Detected';
				break;
			case 510:
				$http_status_code_description = 'Not Extended';
				break;
			case 511:
				$http_status_code_description = 'Network Authentication Required';
				break;

			default:
				$http_status_code_description = 'Unknown';
				break;

		}

		return $http_status_code_description;

	}

	/**
	 * Given the value of the HTTP response status code, return the corresponding status code group.
	 *
	 * @param $http_status_code
	 *
	 * @return string
	 */
	public function get_http_response_status_code_group($http_status_code){

		if ( $http_status_code >= 100 && $http_status_code <= 199 ) {
			$group_name = '1xx-informational-responses';
		} elseif ( $http_status_code >= 200 && $http_status_code <= 299 ) {
			$group_name = '2xx-successful-responses';
		} elseif ( $http_status_code >= 300 && $http_status_code <= 399 ) {
			$group_name = '3xx-redirection-messages';
		} elseif ( $http_status_code >= 400 && $http_status_code <= 499 ) {
			$group_name = '4xx-client-error-responses';
		} elseif ( $http_status_code >= 500 && $http_status_code <= 599 ) {
			$group_name = '5xx-server-error-responses';
		} else {
			$group_name = 'unknown';
		}

		return $group_name;

	}

}