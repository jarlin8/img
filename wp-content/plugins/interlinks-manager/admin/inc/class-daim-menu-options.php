<?php

/**
 * This class adds the options with the related callbacks and validations.
 */
class Daim_Menu_Options {

	public function __construct( $shared ) {

		//assign an instance of the plugin info
		$this->shared = $shared;

	}

	public function register_options() {

        //section ail ----------------------------------------------------------
        add_settings_section(
            'daim_ail_settings_section',
            NULL,
            NULL,
            'daim_ail_options'
        );

        add_settings_field(
            'default_category_id',
            esc_html__('Category', 'daim'),
            array($this,'default_category_id_callback'),
            'daim_ail_options',
            'daim_ail_settings_section'
        );

        register_setting(
            'daim_ail_options',
            'daim_default_category_id',
            array($this,'default_category_id_validation')
        );

        add_settings_field(
            'default_title',
            esc_html__('Title', 'daim'),
            array($this,'default_title_callback'),
            'daim_ail_options',
            'daim_ail_settings_section'
        );

        register_setting(
            'daim_ail_options',
            'daim_default_title',
            array($this,'default_title_validation')
        );

        add_settings_field(
            'default_open_new_tab',
            esc_html__('Open New Tab', 'daim'),
            array($this,'default_open_new_tab_callback'),
            'daim_ail_options',
            'daim_ail_settings_section'
        );

        register_setting(
            'daim_ail_options',
            'daim_default_open_new_tab',
            array($this,'default_open_new_tab_validation')
        );

        add_settings_field(
            'default_use_nofollow',
            esc_html__('Use Nofollow', 'daim'),
            array($this,'default_use_nofollow_callback'),
            'daim_ail_options',
            'daim_ail_settings_section'
        );

        register_setting(
            'daim_ail_options',
            'daim_default_use_nofollow',
            array($this,'default_use_nofollow_validation')
        );

        add_settings_field(
            'default_activate_post_types',
            esc_html__('Post Types', 'daim'),
            array($this,'default_activate_post_types_callback'),
            'daim_ail_options',
            'daim_ail_settings_section'
        );

        register_setting(
            'daim_ail_options',
            'daim_default_activate_post_types',
            array($this,'default_activate_post_types_validation')
        );

        add_settings_field(
            'default_categories',
            esc_html__('Categories', 'daim'),
            array($this,'default_categories_callback'),
            'daim_ail_options',
            'daim_ail_settings_section'
        );

        register_setting(
            'daim_ail_options',
            'daim_default_categories',
            array($this,'default_categories_validation')
        );

        add_settings_field(
            'default_tags',
            esc_html__('Tags', 'daim'),
            array($this,'default_tags_callback'),
            'daim_ail_options',
            'daim_ail_settings_section'
        );

        register_setting(
            'daim_ail_options',
            'daim_default_tags',
            array($this,'default_tags_validation')
        );

        add_settings_field(
            'default_term_group_id',
            esc_html__('Term Group', 'daim'),
            array($this,'default_term_group_id_callback'),
            'daim_ail_options',
            'daim_ail_settings_section'
        );

        register_setting(
            'daim_ail_options',
            'daim_default_term_group_id',
            array($this,'default_term_group_id_validation')
        );

        add_settings_field(
            'default_case_insensitive_search',
            esc_html__('Case Insensitive Search', 'daim'),
            array($this,'default_case_insensitive_search_callback'),
            'daim_ail_options',
            'daim_ail_settings_section'
        );

        register_setting(
            'daim_ail_options',
            'daim_default_case_insensitive_search',
            array($this,'default_case_insensitive_search_validation')
        );

        add_settings_field(
            'default_string_before',
            esc_html__('Left Boundary', 'daim'),
            array($this,'default_string_before_callback'),
            'daim_ail_options',
            'daim_ail_settings_section'
        );

        register_setting(
            'daim_ail_options',
            'daim_default_string_before',
            array($this,'default_string_before_validation')
        );

        add_settings_field(
            'default_string_after',
            esc_html__('Right Boundary', 'daim'),
            array($this,'default_string_after_callback'),
            'daim_ail_options',
            'daim_ail_settings_section'
        );

        register_setting(
            'daim_ail_options',
            'daim_default_string_after',
            array($this,'default_string_after_validation')
        );

        add_settings_field(
            'default_keyword_before',
            esc_html__('Keyword Before', 'daim'),
            array($this,'default_keyword_before_callback'),
            'daim_ail_options',
            'daim_ail_settings_section'
        );

        register_setting(
            'daim_ail_options',
            'daim_default_keyword_before',
            array($this,'default_keyword_before_validation')
        );

        add_settings_field(
            'default_keyword_after',
            esc_html__('Keyword After', 'daim'),
            array($this,'default_keyword_after_callback'),
            'daim_ail_options',
            'daim_ail_settings_section'
        );

        register_setting(
            'daim_ail_options',
            'daim_default_keyword_after',
            array($this,'default_keyword_after_validation')
        );

        add_settings_field(
            'default_max_number_autolinks_per_keyword',
            esc_html__('Limit', 'daim'),
            array($this,'default_max_number_autolinks_per_keyword_callback'),
            'daim_ail_options',
            'daim_ail_settings_section'
        );

        register_setting(
            'daim_ail_options',
            'daim_default_max_number_autolinks_per_keyword',
            array($this,'default_max_number_autolinks_per_keyword_validation')
        );

        add_settings_field(
            'default_priority',
            esc_html__('Priority', 'daim'),
            array($this,'default_priority_callback'),
            'daim_ail_options',
            'daim_ail_settings_section'
        );

        register_setting(
            'daim_ail_options',
            'daim_default_priority',
            array($this,'default_priority_validation')
        );

        //section suggestions --------------------------------------------------
        add_settings_section(
            'daim_suggestions_settings_section',
            NULL,
            NULL,
            'daim_suggestions_options'
        );

        add_settings_field(
            'suggestions_pool_post_types',
            esc_html__('Source Post Types', 'daim'),
            array($this,'suggestions_pool_post_types_callback'),
            'daim_suggestions_options',
            'daim_suggestions_settings_section'
        );

        register_setting(
            'daim_suggestions_options',
            'daim_suggestions_pool_post_types',
            array($this,'suggestions_pool_post_types_validation')
        );

        add_settings_field(
            'suggestions_pool_size',
            esc_html__('Results Pool Size', 'daim'),
            array($this,'suggestions_pool_size_callback'),
            'daim_suggestions_options',
            'daim_suggestions_settings_section'
        );

        register_setting(
            'daim_suggestions_options',
            'daim_suggestions_pool_size',
            array($this,'suggestions_pool_size_validation')
        );

        add_settings_field(
            'suggestions_titles',
            esc_html__('Titles', 'daim'),
            array($this,'suggestions_titles_callback'),
            'daim_suggestions_options',
            'daim_suggestions_settings_section'
        );

        register_setting(
            'daim_suggestions_options',
            'daim_suggestions_titles',
            array($this,'suggestions_titles_validation')
        );

        add_settings_field(
            'suggestions_categories',
            esc_html__('Categories', 'daim'),
            array($this,'suggestions_categories_callback'),
            'daim_suggestions_options',
            'daim_suggestions_settings_section'
        );

        register_setting(
            'daim_suggestions_options',
            'daim_suggestions_categories',
            array($this,'suggestions_categories_validation')
        );

        add_settings_field(
            'suggestions_tags',
            esc_html__('Tags', 'daim'),
            array($this,'suggestions_tags_callback'),
            'daim_suggestions_options',
            'daim_suggestions_settings_section'
        );

        register_setting(
            'daim_suggestions_options',
            'daim_suggestions_tags',
            array($this,'suggestions_categories_validation')
        );

        add_settings_field(
            'suggestions_post_type',
            esc_html__('Post Type', 'daim'),
            array($this,'suggestions_post_type_callback'),
            'daim_suggestions_options',
            'daim_suggestions_settings_section'
        );

        register_setting(
            'daim_suggestions_options',
            'daim_suggestions_post_type',
            array($this,'suggestions_categories_validation')
        );

        //section optimization -------------------------------------------------
        add_settings_section(
            'daim_optimization_settings_section',
            NULL,
            NULL,
            'daim_optimization_options'
        );

        add_settings_field(
            'optimization_num_of_characters',
            esc_html__('Characters per Interlink', 'daim'),
            array($this,'optimization_num_of_characters_callback'),
            'daim_optimization_options',
            'daim_optimization_settings_section'
        );

        register_setting(
            'daim_optimization_options',
            'daim_optimization_num_of_characters',
            array($this,'optimization_num_of_characters_validation')
        );

        add_settings_field(
            'optimization_delta',
            esc_html__('Optimization Delta', 'daim'),
            array($this,'optimization_delta_callback'),
            'daim_optimization_options',
            'daim_optimization_settings_section'
        );

        register_setting(
            'daim_optimization_options',
            'daim_optimization_delta',
            array($this,'optimization_delta_validation')
        );

        //section juice --------------------------------------------------------
        add_settings_section(
            'daim_juice_settings_section',
            NULL,
            NULL,
            'daim_juice_options'
        );

        add_settings_field(
            'default_seo_power',
            esc_html__('SEO Power (Default)', 'daim'),
            array($this,'default_seo_power_callback'),
            'daim_juice_options',
            'daim_juice_settings_section'
        );

        register_setting(
            'daim_juice_options',
            'daim_default_seo_power',
            array($this,'default_seo_power_validation')
        );

        add_settings_field(
            'penality_per_position_percentage',
            esc_html__('Penality per Position (%)', 'daim'),
            array($this,'penality_per_position_percentage_callback'),
            'daim_juice_options',
            'daim_juice_settings_section'
        );

        register_setting(
            'daim_juice_options',
            'daim_penality_per_position_percentage',
            array($this,'penality_per_position_percentage_validation')
        );

        add_settings_field(
            'remove_link_to_anchor',
            esc_html__('Remove Link to Anchor', 'daim'),
            array($this,'remove_link_to_anchor_callback'),
            'daim_juice_options',
            'daim_juice_settings_section'
        );

        register_setting(
            'daim_juice_options',
            'daim_remove_link_to_anchor',
            array($this,'remove_link_to_anchor_validation')
        );

        add_settings_field(
            'remove_url_parameters',
            esc_html__('Remove URL Parameters', 'daim'),
            array($this,'remove_url_parameters_callback'),
            'daim_juice_options',
            'daim_juice_settings_section'
        );

        register_setting(
            'daim_juice_options',
            'daim_remove_url_parameters',
            array($this,'remove_url_parameters_validation')
        );

        //section tracking -----------------------------------------------------
        add_settings_section(
            'daim_tracking_settings_section',
            NULL,
            NULL,
            'daim_tracking_options'
        );

        add_settings_field(
            'track_internal_links',
            esc_html__('Track Internal Links', 'daim'),
            array($this,'track_internal_links_callback'),
            'daim_tracking_options',
            'daim_tracking_settings_section'
        );

        register_setting(
            'daim_tracking_options',
            'daim_track_internal_links',
            array($this,'track_internal_links_validation')
        );

        //section analysis --------------------------------------------------
        add_settings_section(
            'daim_analysis_settings_section',
            NULL,
            NULL,
            'daim_analysis_options'
        );

        add_settings_field(
            'set_max_execution_time',
            esc_html__('Set Max Execution Time', 'daim'),
            array($this,'set_max_execution_time_callback'),
            'daim_analysis_options',
            'daim_analysis_settings_section'
        );

        register_setting(
            'daim_analysis_options',
            'daim_set_max_execution_time',
            array($this,'set_max_execution_time_validation')
        );

        add_settings_field(
            'max_execution_time_value',
            esc_html__('Max Execution Time Value', 'daim'),
            array($this,'max_execution_time_value_callback'),
            'daim_analysis_options',
            'daim_analysis_settings_section'
        );

        register_setting(
            'daim_analysis_options',
            'daim_max_execution_time_value',
            array($this,'max_execution_time_value_validation')
        );

        add_settings_field(
            'set_memory_limit',
            esc_html__('Set Memory Limit', 'daim'),
            array($this,'set_memory_limit_callback'),
            'daim_analysis_options',
            'daim_analysis_settings_section'
        );

        register_setting(
            'daim_analysis_options',
            'daim_set_memory_limit',
            array($this,'set_memory_limit_validation')
        );

        add_settings_field(
            'memory_limit_value',
            esc_html__('Memory Limit Value', 'daim'),
            array($this,'memory_limit_value_callback'),
            'daim_analysis_options',
            'daim_analysis_settings_section'
        );

        register_setting(
            'daim_analysis_options',
            'daim_memory_limit_value',
            array($this,'memory_limit_value_validation')
        );

        add_settings_field(
            'limit_posts_analysis',
            esc_html__('Limit Posts Analysis', 'daim'),
            array($this,'limit_posts_analysis_callback'),
            'daim_analysis_options',
            'daim_analysis_settings_section'
        );

        register_setting(
            'daim_analysis_options',
            'daim_limit_posts_analysis',
            array($this,'limit_posts_analysis_validation')
        );

        add_settings_field(
            'dashboard_post_types',
            esc_html__('Dashboard Post Types', 'daim'),
            array($this,'dashboard_post_types_callback'),
            'daim_analysis_options',
            'daim_analysis_settings_section'
        );

        register_setting(
            'daim_analysis_options',
            'daim_dashboard_post_types',
            array($this,'dashboard_post_types_validation')
        );

        add_settings_field(
            'juice_post_types',
            esc_html__('Juice Post Types', 'daim'),
            array($this,'juice_post_types_callback'),
            'daim_analysis_options',
            'daim_analysis_settings_section'
        );

        register_setting(
            'daim_analysis_options',
            'daim_juice_post_types',
            array($this,'juice_post_types_validation')
        );

		add_settings_field(
			'http_status_post_types',
			esc_html__('HTTP Status Post Types', 'daim'),
			array($this,'http_status_post_types_callback'),
			'daim_analysis_options',
			'daim_analysis_settings_section'
		);

		register_setting(
			'daim_analysis_options',
			'daim_http_status_post_types',
			array($this,'http_status_post_types_validation')
		);

        //meta boxes -----------------------------------------------------------
        add_settings_section(
            'daim_metaboxes_settings_section',
            NULL,
            NULL,
            'daim_metaboxes_options'
        );

        add_settings_field(
            'interlinks_options_post_types',
            esc_html__('Interlinks Options Post Types', 'daim'),
            array($this,'interlinks_options_post_types_callback'),
            'daim_metaboxes_options',
            'daim_metaboxes_settings_section'
        );

        register_setting(
            'daim_metaboxes_options',
            'daim_interlinks_options_post_types',
            array($this,'interlinks_options_post_types_validation')
        );

        add_settings_field(
            'interlinks_optimization_post_types',
            esc_html__('Interlinks Optimization Post Types', 'daim'),
            array($this,'interlinks_optimization_post_types_callback'),
            'daim_metaboxes_options',
            'daim_metaboxes_settings_section'
        );

        register_setting(
            'daim_metaboxes_options',
            'daim_interlinks_optimization_post_types',
            array($this,'interlinks_optimization_post_types_validation')
        );

        add_settings_field(
            'interlinks_suggestions_post_types',
            esc_html__('Interlinks Suggestions Post Types', 'daim'),
            array($this,'interlinks_suggestions_post_types_callback'),
            'daim_metaboxes_options',
            'daim_metaboxes_settings_section'
        );

        register_setting(
            'daim_metaboxes_options',
            'daim_interlinks_suggestions_post_types',
            array($this,'interlinks_suggestions_post_types_validation')
        );

        //capabilities ----------------------------------------------------------
        add_settings_section(
            'daim_capabilities_settings_section',
            NULL,
            NULL,
            'daim_capabilities_options'
        );

        add_settings_field(
            'dashboard_menu_required_capability',
            esc_html__('Dashboard Menu', 'daim'),
            array($this,'dashboard_menu_required_capability_callback'),
            'daim_capabilities_options',
            'daim_capabilities_settings_section'
        );

        register_setting(
            'daim_capabilities_options',
            'daim_dashboard_menu_required_capability',
            array($this,'dashboard_menu_required_capability_validation')
        );

        add_settings_field(
            'juice_menu_required_capability',
            esc_html__('Juice Menu', 'daim'),
            array($this,'juice_menu_required_capability_callback'),
            'daim_capabilities_options',
            'daim_capabilities_settings_section'
        );

        register_setting(
            'daim_capabilities_options',
            'daim_juice_menu_required_capability',
            array($this,'juice_menu_required_capability_validation')
        );

        add_settings_field(
            'hits_menu_required_capability',
            esc_html__('Hits Menu', 'daim'),
            array($this,'hits_menu_required_capability_callback'),
            'daim_capabilities_options',
            'daim_capabilities_settings_section'
        );

        register_setting(
            'daim_capabilities_options',
            'daim_hits_menu_required_capability',
            array($this,'hits_menu_required_capability_validation')
        );

		add_settings_field(
			'http_status_menu_required_capability',
			esc_html__('HTTP Status Menu', 'daim'),
			array($this,'http_status_menu_required_capability_callback'),
			'daim_capabilities_options',
			'daim_capabilities_settings_section'
		);

		register_setting(
			'daim_capabilities_options',
			'daim_http_status_menu_required_capability',
			array($this,'http_status_menu_required_capability_validation')
		);

        add_settings_field(
            'wizard_menu_required_capability',
            esc_html__('Wizard Menu', 'daim'),
            array($this,'wizard_menu_required_capability_callback'),
            'daim_capabilities_options',
            'daim_capabilities_settings_section'
        );

        register_setting(
            'daim_capabilities_options',
            'daim_wizard_menu_required_capability',
            array($this,'wizard_menu_required_capability_validation')
        );

        add_settings_field(
            'ail_menu_required_capability',
            esc_html__('AIL Menu', 'daim'),
            array($this,'ail_menu_required_capability_callback'),
            'daim_capabilities_options',
            'daim_capabilities_settings_section'
        );

        register_setting(
            'daim_capabilities_options',
            'daim_ail_menu_required_capability',
            array($this,'ail_menu_required_capability_validation')
        );

        add_settings_field(
            'categories_menu_required_capability',
            esc_html__('Categories Menu', 'daim'),
            array($this,'categories_menu_required_capability_callback'),
            'daim_capabilities_options',
            'daim_capabilities_settings_section'
        );

        register_setting(
            'daim_capabilities_options',
            'daim_categories_menu_required_capability',
            array($this,'categories_menu_required_capability_validation')
        );

        add_settings_field(
            'term_groups_menu_required_capability',
            esc_html__('Term Groups Menu', 'daim'),
            array($this,'term_groups_menu_required_capability_callback'),
            'daim_capabilities_options',
            'daim_capabilities_settings_section'
        );

        register_setting(
            'daim_capabilities_options',
            'daim_term_groups_menu_required_capability',
            array($this,'term_groups_menu_required_capability_validation')
        );

        add_settings_field(
            'import_menu_required_capability',
            esc_html__('Import Menu', 'daim'),
            array($this,'import_menu_required_capability_callback'),
            'daim_capabilities_options',
            'daim_capabilities_settings_section'
        );

        register_setting(
            'daim_capabilities_options',
            'daim_import_menu_required_capability',
            array($this,'import_menu_required_capability_validation')
        );
        
        add_settings_field(
            'export_menu_required_capability',
            esc_html__('Export Menu', 'daim'),
            array($this,'export_menu_required_capability_callback'),
            'daim_capabilities_options',
            'daim_capabilities_settings_section'
        );

        register_setting(
            'daim_capabilities_options',
            'daim_export_menu_required_capability',
            array($this,'export_menu_required_capability_validation')
        );
        
        add_settings_field(
            'maintenance_menu_required_capability',
            esc_html__('Maintenance Menu', 'daim'),
            array($this,'maintenance_menu_required_capability_callback'),
            'daim_capabilities_options',
            'daim_capabilities_settings_section'
        );

        register_setting(
            'daim_capabilities_options',
            'daim_maintenance_menu_required_capability',
            array($this,'maintenance_menu_required_capability_validation')
        );

        add_settings_field(
            'interlinks_options_mb_required_capability',
            esc_html__('Interlinks Options Meta Box', 'daim'),
            array($this,'interlinks_options_mb_required_capability_callback'),
            'daim_capabilities_options',
            'daim_capabilities_settings_section'
        );

        register_setting(
            'daim_capabilities_options',
            'daim_interlinks_options_mb_required_capability',
            array($this,'interlinks_options_mb_required_capability_validation')
        );

        add_settings_field(
            'interlinks_optimization_mb_required_capability',
            esc_html__('Interlinks Optimization Meta Box', 'daim'),
            array($this,'interlinks_optimization_mb_required_capability_callback'),
            'daim_capabilities_options',
            'daim_capabilities_settings_section'
        );

        register_setting(
            'daim_capabilities_options',
            'daim_interlinks_optimization_mb_required_capability',
            array($this,'interlinks_optimization_mb_required_capability_validation')
        );

        add_settings_field(
            'interlinks_suggestions_mb_required_capability',
            esc_html__('Interlinks Suggestions Meta Box', 'daim'),
            array($this,'interlinks_suggestions_mb_required_capability_callback'),
            'daim_capabilities_options',
            'daim_capabilities_settings_section'
        );

        register_setting(
            'daim_capabilities_options',
            'daim_interlinks_suggestions_mb_required_capability',
            array($this,'interlinks_suggestions_mb_required_capability_validation')
        );

        //advanced -----------------------------------------------------------------------------------------------------
        add_settings_section(
            'daim_advanced_settings_section',
            NULL,
            NULL,
            'daim_advanced_options'
        );

        add_settings_field(
            'default_enable_ail_on_post',
            esc_html__('Enable AIL', 'daim'),
            array($this,'default_enable_ail_on_post_callback'),
            'daim_advanced_options',
            'daim_advanced_settings_section'
        );

        register_setting(
            'daim_advanced_options',
            'daim_default_enable_ail_on_post',
            array($this,'default_enable_ail_on_post_validation')
        );

        add_settings_field(
            'filter_priority',
            esc_html__('Filter Priority', 'daim'),
            array($this,'filter_priority_callback'),
            'daim_advanced_options',
            'daim_advanced_settings_section'
        );

        register_setting(
            'daim_advanced_options',
            'daim_filter_priority',
            array($this,'filter_priority_validation')
        );

        add_settings_field(
            'ail_test_mode',
            esc_html__('Test Mode', 'daim'),
            array($this,'ail_test_mode_callback'),
            'daim_advanced_options',
            'daim_advanced_settings_section'
        );

        register_setting(
            'daim_advanced_options',
            'daim_ail_test_mode',
            array($this,'ail_test_mode_validation')
        );

        add_settings_field(
            'random_prioritization',
            esc_html__('Random Prioritization', 'daim'),
            array($this, 'random_prioritization_callback'),
            'daim_advanced_options',
            'daim_advanced_settings_section'
        );

        register_setting(
            'daim_advanced_options',
            'daim_random_prioritization',
            array($this, 'random_prioritization_validation')
        );

        add_settings_field(
            'ignore_self_ail',
            esc_html__('Ignore Self AIL', 'daim'),
            array($this,'ignore_self_ail_callback'),
            'daim_advanced_options',
            'daim_advanced_settings_section'
        );

        register_setting(
            'daim_advanced_options',
            'daim_ignore_self_ail',
            array($this,'ignore_self_ail_validation')
        );

        add_settings_field(
            'categories_and_tags_verification',
            esc_html__('Categories & Tags Verification', 'daim'),
            array($this,'categories_and_tags_verification_callback'),
            'daim_advanced_options',
            'daim_advanced_settings_section'
        );

        register_setting(
            'daim_advanced_options',
            'daim_categories_and_tags_verification',
            array($this,'categories_and_tags_verification_validation')
        );

        add_settings_field(
            'general_limit_mode',
            esc_html__('General Limit Mode', 'daim'),
            array($this,'general_limit_mode_callback'),
            'daim_advanced_options',
            'daim_advanced_settings_section'
        );

        register_setting(
            'daim_advanced_options',
            'daim_general_limit_mode',
            array($this,'general_limit_mode_validation')
        );

        add_settings_field(
            'characters_per_autolink',
            esc_html__('General Limit (Characters per AIL)', 'daim'),
            array($this,'characters_per_autolink_callback'),
            'daim_advanced_options',
            'daim_advanced_settings_section'
        );

        register_setting(
            'daim_advanced_options',
            'daim_characters_per_autolink',
            array($this,'characters_per_autolink_validation')
        );

        add_settings_field(
            'max_number_autolinks_per_post',
            esc_html__('General Limit (Amount)', 'daim'),
            array($this,'max_number_autolinks_per_post_callback'),
            'daim_advanced_options',
            'daim_advanced_settings_section'
        );

        register_setting(
            'daim_advanced_options',
            'daim_max_number_autolinks_per_post',
            array($this,'max_number_autolinks_per_post_validation')
        );

        add_settings_field(
            'general_limit_subtract_mil',
            esc_html__('General Limit (Subtract MIL)', 'daim'),
            array($this,'general_limit_subtract_mil_callback'),
            'daim_advanced_options',
            'daim_advanced_settings_section'
        );

        register_setting(
            'daim_advanced_options',
            'daim_general_limit_subtract_mil',
            array($this,'general_limit_subtract_mil_validation')
        );

        add_settings_field(
            'same_url_limit',
            esc_html__('Same URL Limit', 'daim'),
            array($this,'same_url_limit_callback'),
            'daim_advanced_options',
            'daim_advanced_settings_section'
        );

        register_setting(
            'daim_advanced_options',
            'daim_same_url_limit',
            array($this,'same_url_limit_validation')
        );

        add_settings_field(
            'wizard_rows',
            esc_html__('Wizard Rows', 'daim'),
            array($this,'wizard_rows_callback'),
            'daim_advanced_options',
            'daim_advanced_settings_section'
        );

        register_setting(
            'daim_advanced_options',
            'daim_wizard_rows',
            array($this,'wizard_rows_validation')
        );

        add_settings_field(
            'supported_terms',
            esc_html__('Supported Terms', 'daim'),
            array($this,'supported_terms_callback'),
            'daim_advanced_options',
            'daim_advanced_settings_section'
        );

        register_setting(
            'daim_advanced_options',
            'daim_supported_terms',
            array($this,'supported_terms_validation')
        );

		add_settings_field(
			'protect_attributes',
			esc_html__('Protect Attributes', 'daim'),
			array($this,'protect_attributes_callback'),
			'daim_advanced_options',
			'daim_advanced_settings_section'
		);

		register_setting(
			'daim_advanced_options',
			'daim_protect_attributes',
			array($this,'protect_attributes_validation')
		);

        add_settings_field(
            'protected_tags',
            esc_html__('Protected Tags', 'daim'),
            array($this,'protected_tags_callback'),
            'daim_advanced_options',
            'daim_advanced_settings_section'
        );

        register_setting(
            'daim_advanced_options',
            'daim_protected_tags',
            array($this,'protected_tags_validation')
        );

        add_settings_field(
            'protected_gutenberg_blocks',
            esc_html__('Protected Gutenberg Blocks', 'daim'),
            array($this,'protected_gutenberg_blocks_callback'),
            'daim_advanced_options',
            'daim_advanced_settings_section'
        );

        register_setting(
            'daim_advanced_options',
            'daim_protected_gutenberg_blocks',
            array($this,'protected_gutenberg_blocks_validation')
        );

        add_settings_field(
            'protected_gutenberg_custom_blocks',
            esc_html__('Protected Gutenberg Custom Blocks', 'daim'),
            array($this,'protected_gutenberg_custom_blocks_callback'),
            'daim_advanced_options',
            'daim_advanced_settings_section'
        );

        register_setting(
            'daim_advanced_options',
            'daim_protected_gutenberg_custom_blocks',
            array($this,'protected_gutenberg_custom_blocks_validation')
        );

        add_settings_field(
            'protected_gutenberg_custom_void_blocks',
            esc_html__('Protected Gutenberg Custom Void Blocks', 'daim'),
            array($this,'protected_gutenberg_custom_void_blocks_callback'),
            'daim_advanced_options',
            'daim_advanced_settings_section'
        );

        register_setting(
            'daim_advanced_options',
            'daim_protected_gutenberg_custom_void_blocks',
            array($this,'protected_gutenberg_custom_void_blocks_validation')
        );

        add_settings_field(
            'pagination_dashboard_menu',
            esc_html__('Pagination Dashboard Menu', 'daim'),
            array($this,'pagination_dashboard_menu_callback'),
            'daim_advanced_options',
            'daim_advanced_settings_section'
        );

        register_setting(
            'daim_advanced_options',
            'daim_pagination_dashboard_menu',
            array($this,'pagination_dashboard_menu_validation')
        );

        add_settings_field(
            'pagination_juice_menu',
            esc_html__('Pagination Juice Menu', 'daim'),
            array($this,'pagination_juice_menu_callback'),
            'daim_advanced_options',
            'daim_advanced_settings_section'
        );

        register_setting(
            'daim_advanced_options',
            'daim_pagination_juice_menu',
            array($this,'pagination_juice_menu_validation')
        );

		add_settings_field(
			'pagination_http_status_menu',
			esc_html__('Pagination HTTP Status Menu', 'daim'),
			array($this,'pagination_http_status_menu_callback'),
			'daim_advanced_options',
			'daim_advanced_settings_section'
		);

		register_setting(
			'daim_advanced_options',
			'daim_pagination_http_status_menu',
			array($this,'pagination_http_status_menu_validation')
		);

        add_settings_field(
            'pagination_hits_menu',
            esc_html__('Pagination Hits Menu', 'daim'),
            array($this,'pagination_hits_menu_callback'),
            'daim_advanced_options',
            'daim_advanced_settings_section'
        );

        register_setting(
            'daim_advanced_options',
            'daim_pagination_hits_menu',
            array($this,'pagination_hits_menu_validation')
        );

        add_settings_field(
            'pagination_ail_menu',
            esc_html__('Pagination AIL Menu', 'daim'),
            array($this,'pagination_ail_menu_callback'),
            'daim_advanced_options',
            'daim_advanced_settings_section'
        );

        register_setting(
            'daim_advanced_options',
            'daim_pagination_ail_menu',
            array($this,'pagination_ail_menu_validation')
        );

        add_settings_field(
            'pagination_categories_menu',
            esc_html__('Pagination Categories Menu', 'daim'),
            array($this,'pagination_categories_menu_callback'),
            'daim_advanced_options',
            'daim_advanced_settings_section'
        );

        register_setting(
            'daim_advanced_options',
            'daim_pagination_categories_menu',
            array($this,'pagination_categories_menu_validation')
        );

        add_settings_field(
            'pagination_term_groups_menu',
            esc_html__('Pagination Term Groups Menu', 'daim'),
            array($this,'pagination_term_groups_menu_callback'),
            'daim_advanced_options',
            'daim_advanced_settings_section'
        );

        register_setting(
            'daim_advanced_options',
            'daim_pagination_term_groups_menu',
            array($this,'pagination_term_groups_menu_validation')
        );

		add_settings_field(
			'http_status_checks_per_iteration',
			esc_html__('HTTP Status WP-Cron Checks Per Run', 'daim'),
			array($this,'http_status_checks_per_iteration_callback'),
			'daim_advanced_options',
			'daim_advanced_settings_section'
		);

		register_setting(
			'daim_advanced_options',
			'daim_http_status_checks_per_iteration',
			array($this,'http_status_checks_per_iteration_validation')
		);

		//repeat the function above for this option "http_status_cron_schedule_interval"
		add_settings_field(
			'http_status_cron_schedule_interval',
			esc_html__('HTTP Status WP-Cron Event Interval', 'daim'),
			array($this,'http_status_cron_schedule_interval_callback'),
			'daim_advanced_options',
			'daim_advanced_settings_section'
		);

		register_setting(
			'daim_advanced_options',
			'daim_http_status_cron_schedule_interval',
			array($this,'http_status_cron_schedule_interval_validation')
		);

		//repeat the function above for this option "http_status_request_timeout"
		add_settings_field(
			'http_status_request_timeout',
			esc_html__('HTTP Status Request Timeout', 'daim'),
			array($this,'http_status_request_timeout_callback'),
			'daim_advanced_options',
			'daim_advanced_settings_section'
		);

		register_setting(
			'daim_advanced_options',
			'daim_http_status_request_timeout',
			array($this,'http_status_request_timeout_validation')
		);

	}

    //ail options callbacks and validations ------------------------------------
    public function default_category_id_callback($args){

        $html = '<select id="daim_default_category_id" name="daim_default_category_id" class="daext-display-none">';

        $html .= '<option value="0" ' . selected(intval(get_option("daim_defaults_category_id")), 0,
                false) . '>' . esc_html__('None', 'daim') . '</option>';

        global $wpdb;
        $table_name = $wpdb->prefix . $this->shared->get('slug') . "_category";
        $sql        = "SELECT category_id, name FROM $table_name ORDER BY category_id DESC";
        $category_a = $wpdb->get_results($sql, ARRAY_A);

        foreach ($category_a as $key => $category) {
            $html .= '<option value="' . $category['category_id'] . '" ' . selected(intval(get_option("daim_default_category_id")),
                    $category['category_id'], false) . '>' . esc_html(stripslashes($category['name'])) . '</option>';
        }

        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr__('The category of the AIL. This option determines the default value of the "Category" field available in the "AIL" menu and in the "Wizard" menu.', 'daim') . '"></div>';

        echo $html;

    }

    public function default_category_id_validation($input){

        $input = sanitize_text_field( $input );

        return intval($input, 10);

    }

    public function default_title_callback($args){

        $html = '<input type="text" id="daim_default_title" name="daim_default_title" class="regular-text" value="' . esc_attr(get_option("daim_default_title")) . '" />';
        $html .= '<div class="help-icon" title="' . esc_attr__('The title attribute of the link automatically generated on the keyword. This option determines the default value of the "Title" field available in the "AIL" menu and is also used for the AIL generated with the "Wizard" menu.', 'daim') . '"></div>';

        echo $html;

    }

    public function default_title_validation($input){

        $input = sanitize_text_field( $input );

        if( mb_strlen($input) > 1024 ){
            add_settings_error( 'daim_default_title', 'daim_default_title', esc_html__('Please enter a valid capability in the "Wizard Menu" option.', 'daim') );
            $output = get_option('daim_default_title');
        }else{
            $output = $input;
        }

        return trim($output);

    }

    public function default_open_new_tab_callback($args){

        $html = '<select id="daim_default_open_new_tab" name="daim_default_open_new_tab" class="daext-display-none">';
        $html .= '<option ' . selected(intval(get_option("daim_default_open_new_tab")), 0, false) . ' value="0">' . esc_html__('No', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_default_open_new_tab")), 1, false) . ' value="1">' . esc_html__('Yes', 'daim') . '</option>';
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr__('If you select "Yes" the link generated on the defined keyword opens the linked document in a new tab. This option determines the default value of the "Open New Tab" field available in the "AIL" menu and is also used for the AIL generated with the "Wizard" menu.', 'daim') . '"></div>';

        echo $html;

    }

    public function default_open_new_tab_validation($input){

        $input = sanitize_text_field( $input );

        return intval($input, 10) == 1 ? '1' : '0';

    }

    public function default_use_nofollow_callback($args){

        $html = '<select id="daim_default_use_nofollow" name="daim_default_use_nofollow" class="daext-display-none">';
        $html .= '<option ' . selected(intval(get_option("daim_default_use_nofollow")), 0, false) . ' value="0">' . esc_html__('No', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_default_use_nofollow")), 1, false) . ' value="1">' . esc_html__('Yes', 'daim') . '</option>';
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr__('If you select "Yes" the link generated on the defined keyword will include the rel="nofollow" attribute. This option determines the default value of the "Use Nofollow" field available in the "AIL" menu and is also used for the AIL generated with the "Wizard" menu.', 'daim') . '"></div>';

        echo $html;

    }

    public function default_use_nofollow_validation($input){

        $input = sanitize_text_field( $input );

        return intval($input, 10) == 1 ? '1' : '0';

    }

    public function default_activate_post_types_callback($args)
    {

        $default_activate_post_types_a = get_option("daim_default_activate_post_types");

        $available_post_types_a = get_post_types(array(
            'public'  => true,
            'show_ui' => true
        ));

        //Remove the "attachment" post type
        $available_post_types_a = array_diff($available_post_types_a, array('attachment'));

        $html = '<select id="daim-default-activate-post-types" name="daim_default_activate_post_types[]" class="daext-display-none" multiple>';

        foreach ($available_post_types_a as $single_post_type) {
            if (is_array($default_activate_post_types_a) and in_array($single_post_type, $default_activate_post_types_a)) {
                $selected = 'selected';
            } else {
                $selected = '';
            }
            $post_type_obj = get_post_type_object($single_post_type);
            $html          .= '<option value="' . $single_post_type . '" ' . $selected . '>' . esc_html($post_type_obj->label) . '</option>';
        }

        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr__('With this option you are able to determine in which post types the defined keywords will be automatically converted to a link. This option determines the default value of the "Post Types" field available in the "AIL" menu and is also used for the AIL generated with the "Wizard" menu.',
                'daim') . '"></div>';

        echo $html;

    }

    public function default_activate_post_types_validation($input)
    {

	    if (is_array($input) and count($input) > 0) {
		    $output = $input;
	    } else {
		    add_settings_error( 'daim_default_activate_post_types', 'daim_default_activate_post_types', esc_html__('Please enter at least one post type in the "Post Types" option.', 'daim') );
		    $output = get_option('daim_default_activate_post_types');
	    }

	    return $output;

    }

    public function default_categories_callback($args)
    {

        $default_categories_a = get_option("daim_default_categories");

        $html = '<select id="daim-default-categories" name="daim_default_categories[]" class="daext-display-none" multiple>';

        $categories = get_categories(array(
            'hide_empty' => 0,
            'orderby'    => 'term_id',
            'order'      => 'DESC'
        ));

        foreach ($categories as $category) {
            if (is_array($default_categories_a) and in_array($category->term_id, $default_categories_a)) {
                $selected = 'selected';
            } else {
                $selected = '';
            }
            $html .= '<option value="' . $category->term_id . '" ' . $selected . '>' . esc_html($category->name) . '</option>';
        }

        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr__('With this option you are able to determine in which categories the defined keywords will be automatically converted to a link. Leave this field empty to convert the keyword in any category. This option determines the default value of the "Categories" field available in the "AIL" menu and is also used for the AIL generated with the "Wizard" menu.',
                'daim') . '"></div>';

        echo $html;

    }

    public function default_categories_validation($input)
    {

        if (wp_is_numeric_array($input)) {
            return $input;
        } else {
            return '';
        }

    }

    public function default_tags_callback($args)
    {

        $default_tags_a = get_option("daim_default_tags");

        $html = '<select id="daim-default-categories" name="daim_default_tags[]" class="daext-display-none" multiple>';

        $categories = get_categories(array(
            'hide_empty' => 0,
            'orderby'    => 'term_id',
            'order'      => 'DESC',
            'taxonomy'   => 'post_tag'
        ));

        foreach ($categories as $category) {
            if (is_array($default_tags_a) and in_array($category->term_id, $default_tags_a)) {
                $selected = 'selected';
            } else {
                $selected = '';
            }
            $html .= '<option value="' . $category->term_id . '" ' . $selected . '>' . esc_html($category->name) . '</option>';
        }

        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr__('With this option you are able to determine in which tags the defined keywords will be automatically converted to a link. Leave this field empty to convert the keyword in any tag. This option determines the default value of the "Tags" field available in the "AIL" menu and is also used for the AIL generated with the "Wizard" menu.',
                'daim') . '"></div>';

        echo $html;

    }

    public function default_tags_validation($input)
    {

        if (wp_is_numeric_array($input)) {
            return $input;
        } else {
            return '';
        }

    }

    public function default_term_group_id_callback($args)
    {

        $html = '<select id="daim-default-term-group-id" name="daim_default_term_group_id" class="daext-display-none">';

        $html .= '<option value="0" ' . selected(intval(get_option("daim_default_term_group_id")), 0,
                false) . '>' . esc_html__('None', 'daim') . '</option>';

        global $wpdb;
        $table_name   = $wpdb->prefix . $this->shared->get('slug') . "_term_group";
        $sql          = "SELECT term_group_id, name FROM $table_name ORDER BY term_group_id DESC";
        $term_group_a = $wpdb->get_results($sql, ARRAY_A);

        foreach ($term_group_a as $key => $term_group) {
            $html .= '<option value="' . $term_group['term_group_id'] . '" ' . selected(intval(get_option("daim_default_term_group_id")),
                    $term_group['term_group_id'],
                    false) . '>' . esc_html(stripslashes($term_group['name'])) . '</option>';
        }

        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr__('The terms that will be compared with the ones available on the posts where the AIL are applied. Please note that when a term group is selected the "Categories" and "Tags" options will be ignored. This option determines the default value of the "Term Group" field available in the "AIL" menu and is also used for the AIL generated with the "Wizard" menu.',
                'daim') . '"></div>';

        echo $html;

    }

    public function default_term_group_id_validation($input)
    {

        $input = sanitize_text_field( $input );

        return intval($input, 10);

    }

    public function default_case_insensitive_search_callback($args){

        $html = '<select id="daim_default_case_insensitive_search" name="daim_default_case_insensitive_search" class="daext-display-none">';
        $html .= '<option ' . selected(intval(get_option("daim_default_case_insensitive_search")), 0, false) . ' value="0">' . esc_html__('No', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_default_case_insensitive_search")), 1, false) . ' value="1">' . esc_html__('Yes', 'daim') . '</option>';
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr__('If you select "Yes" your keyword will match both lowercase and uppercase variations. This option determines the default value of the "Case Insensitive Search" field available in the "AIL" menu and is also used for the AIL generated with the "Wizard" menu.', 'daim') . '"></div>';

        echo $html;

    }

    public function default_case_insensitive_search_validation($input){

        $input = sanitize_text_field( $input );

        return intval($input, 10) == 1 ? '1' : '0';

    }

    public function default_string_before_callback($args)
    {

        $html = '<select id="daim_default_string_before" name="daim_default_string_before" class="daext-display-none">';
        $html .= '<option ' . selected(intval(get_option("daim_default_string_before")), 1,
                false) . ' value="1">' . esc_html__('Generic', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_default_string_before")), 2,
                false) . ' value="2">' . esc_html__('White Space', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_default_string_before")), 3,
                false) . ' value="3">' . esc_html__('Comma', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_default_string_before")), 4,
                false) . ' value="4">' . esc_html__('Point', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_default_string_before")), 5,
                false) . ' value="5">' . esc_html__('None', 'daim') . '</option>';
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr__('Use this option to match keywords preceded by a generic boundary or by a specific character. This option determines the default value of the "Left Boundary" field available in the "AIL" menu and is also used for the AIL generated with the "Wizard" menu.',
                'daim') . '"></div>';

        echo $html;

    }

    public function default_string_before_validation($input)
    {

        $input = sanitize_text_field( $input );

        if (intval($input, 10) >= 1 and intval($input, 10) <= 5) {
            return intval($input, 10);
        } else {
            return intval(get_option('daim_default_string_before'), 10);
        }

    }

    public function default_string_after_callback($args)
    {

        $html = '<select id="daim_default_string_after" name="daim_default_string_after" class="daext-display-none">';
        $html .= '<option ' . selected(intval(get_option("daim_default_string_after")), 1,
                false) . ' value="1">' . esc_html__('Generic', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_default_string_after")), 2,
                false) . ' value="2">' . esc_html__('White Space', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_default_string_after")), 3,
                false) . ' value="3">' . esc_html__('Comma', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_default_string_after")), 4,
                false) . ' value="4">' . esc_html__('Point', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_default_string_after")), 5,
                false) . ' value="5">' . esc_html__('None', 'daim') . '</option>';
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr__('Use this option to match keywords followed by a generic boundary or by a specific character. This option determines the default value of the "Right Boundary" field available in the "AIL" menu and is also used for the AIL generated with the "Wizard" menu.',
                'daim') . '"></div>';

        echo $html;

    }

    public function default_string_after_validation($input)
    {

        $input = sanitize_text_field( $input );

        if (intval($input, 10) >= 1 and intval($input, 10) <= 5) {
            return intval($input, 10);
        } else {
            return intval(get_option('daim_default_string_after'), 10);
        }

    }

    public function default_keyword_before_callback($args){

        $html = '<input type="text" id="daim_default_keyword_before" name="daim_default_keyword_before" class="regular-text" value="' . esc_attr(get_option("daim_default_keyword_before")) . '" />';
        $html .= '<div class="help-icon" title="' . esc_attr__('Use this option to match occurences preceded by a specific string. This option determines the default value of the "Keyword Before" field available in the "AIL" menu and is also used for the AIL generated with the "Wizard" menu.', 'daim') . '"></div>';

        echo $html;

    }

    public function default_keyword_before_validation($input){

        $input = sanitize_text_field( $input );

        if(mb_strlen($input) > 255){
            add_settings_error( 'daim_default_keyword_before', 'daim_default_keyword_before', esc_html__('Please enter a valid value in the "String Before" option.', 'daim') );
            $output = get_option('daim_default_keyword_before');
        }else{
            $output = $input;
        }

        return $output;

    }

    public function default_keyword_after_callback($args){

        $html = '<input type="text" id="daim_default_keyword_after" name="daim_default_keyword_after" class="regular-text" value="' . esc_attr(get_option("daim_default_keyword_after")) . '" />';
        $html .= '<div class="help-icon" title="' . esc_attr__('Use this option to match occurences followed by a specific string. This option determines the default value of the "Keyword After" field available in the "AIL" menu and is also used for the AIL generated with the "Wizard" menu.', 'daim') . '"></div>';

        echo $html;

    }

    public function default_keyword_after_validation($input){

        $input = sanitize_text_field( $input );

        if(mb_strlen($input) > 255){
            add_settings_error( 'daim_default_keyword_after', 'daim_default_keyword_after', esc_html__('Please enter a valid value in the "String Before" option.', 'daim') );
            $output = get_option('daim_default_keyword_after');
        }else{
            $output = $input;
        }

        return $output;

    }

    public function default_max_number_autolinks_per_keyword_callback($args){

        $html = '<input type="text" id="daim_default_max_number_autolinks_per_keyword" name="daim_default_max_number_autolinks_per_keyword" class="regular-text" value="' . intval(get_option("daim_default_max_number_autolinks_per_keyword"), 10) . '" />';
        $html .= '<div class="help-icon" title="' . esc_attr__('With this option you can determine the maximum number of matches of the defined keyword automatically converted to a link. This option determines the default value of the "Limit" field available in the "AIL" menu and is also used for the AIL generated with the "Wizard" menu.', 'daim') . '"></div>';
        echo $html;

    }

    public function default_max_number_autolinks_per_keyword_validation($input){

        $input = sanitize_text_field( $input );

        if(!preg_match($this->shared->regex_number_ten_digits, $input) or intval($input, 10) < 1 or intval($input, 10) > 1000000 ){
            add_settings_error( 'daim_default_max_number_autolinks_per_keyword', 'daim_default_max_number_autolinks_per_keyword', esc_html__('Please enter a number from 1 to 1000000 in the "Limit" option.', 'daim') );
            $output = get_option('daim_default_max_number_autolinks_per_keyword');
        }else{
            $output = $input;
        }

        return intval($output,  10);

    }

    public function default_priority_callback($args){

        $html = '<input type="text" id="daim_default_priority" name="daim_default_priority" class="regular-text" value="' . intval(get_option("daim_default_priority"), 10) . '" />';
        $html .= '<div class="help-icon" title="' . esc_attr__('The priority value determines the order used to apply the AIL on the post. This option determines the default value of the "Priority" field available in the "AIL" menu and is also used for the AIL generated with the "Wizard" menu.', 'daim') . '"></div>';

        echo $html;

    }

    public function default_priority_validation($input){

        $input = sanitize_text_field( $input );

        if(!preg_match($this->shared->regex_number_ten_digits, $input) or intval($input, 10) > 1000000){
            add_settings_error( 'daim_default_priority', 'daim_default_priority', esc_html__('Please enter a number from 0 to 1000000 in the "Priority" option.', 'daim') );
            $output = get_option('daim_default_priority');
        }else{
            $output = $input;
        }

        return intval($output, 10);

    }

    //suggestions options callbacks and validations ----------------------------

    public function suggestions_pool_post_types_callback($args)
    {

        $suggestions_pool_post_types_a = get_option("daim_suggestions_pool_post_types");

        $available_post_types_a = get_post_types(array(
            'public'  => true,
            'show_ui' => true
        ));

        //Remove the "attachment" post type
        $available_post_types_a = array_diff($available_post_types_a, array('attachment'));

        $html = '<select id="daim-suggestions-pool-post-types" name="daim_suggestions_pool_post_types[]" class="daext-display-none" multiple>';

        foreach ($available_post_types_a as $single_post_type) {
            if (is_array($suggestions_pool_post_types_a) and in_array($single_post_type, $suggestions_pool_post_types_a)) {
                $selected = 'selected';
            } else {
                $selected = '';
            }
            $post_type_obj = get_post_type_object($single_post_type);
            $html          .= '<option value="' . $single_post_type . '" ' . $selected . '>' . esc_html($post_type_obj->label) . '</option>';
        }

        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr__('With this option you are able to determine in which post types the algorithm available in the "Interlinks Suggestions" meta box should look for suggestions.',
                'daim') . '"></div>';

        echo $html;

    }

    public function suggestions_pool_post_types_validation($input)
    {

        if (is_array($input) and count($input) > 0) {
            $output = $input;
        } else {
	        add_settings_error( 'daim_suggestions_pool_post_types', 'daim_suggestions_pool_post_types', esc_html__('Please enter at least one post type in the "Source Post Types" option.', 'daim') );
	        $output = get_option('daim_suggestions_pool_post_types');
        }

	    return $output;

    }

    public function suggestions_pool_size_callback($args){

        $html = '<input type="text" id="daim_suggestions_pool_size" name="daim_suggestions_pool_size" class="regular-text" value="' . esc_attr(get_option("daim_suggestions_pool_size")) . '" />';
        $html .= '<div class="help-icon" title="' . esc_attr__('This option determines the maximum number of results returned by the algorithm available in the "Interlinks Suggestions" meta box. (The five results shown for each iteration are retrieved from a pool of results which has, as a maximum size, the value defined with this option.)', 'daim') . '"></div>';

        echo $html;

    }

    public function suggestions_pool_size_validation($input){

        $input = sanitize_text_field( $input );

        if(!preg_match($this->shared->regex_number_ten_digits, $input) or intval($input, 10) < 5 or intval($input) > 1000000){
            add_settings_error( 'daim_suggestions_pool_size', 'daim_suggestions_pool_size', esc_html__('Please enter a number from 5 to 1000000 in the "Pool Size" option.', 'daim') );
            $output = get_option('daim_suggestions_pool_size');
        }else{
            $output = $input;
        }

        return intval($output, 10);

    }

    public function suggestions_titles_callback($args){

        $html = '<select id="daim_suggestions_titles" name="daim_suggestions_titles" class="daext-display-none">';
        $html .= '<option ' . selected(get_option("daim_suggestions_titles"), 'consider', false) . ' value="consider">' . esc_html__('Consider', 'daim') . '</option>';
        $html .= '<option ' . selected(get_option("daim_suggestions_titles"), 'ignore', false) . ' value="ignore">' . esc_html__('Ignore', 'daim') . '</option>';
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr__('Select if the algorithm available in the "Interlinks Suggestions" meta box should consider the posts, pages and custom post types titles.', 'daim') . '"></div>';

        echo $html;

    }

    public function suggestions_titles_validation($input){

        $input = sanitize_text_field( $input );

        return $input;

    }

    public function suggestions_categories_callback($args){

        $html = '<select id="daim_suggestions_categories" name="daim_suggestions_categories" class="daext-display-none">';
        $html .= '<option ' . selected(get_option("daim_suggestions_categories"), 'require', false) . ' value="require">' . esc_html__('Require', 'daim') . '</option>';
        $html .= '<option ' . selected(get_option("daim_suggestions_categories"), 'consider', false) . ' value="consider">' . esc_html__('Consider', 'daim') . '</option>';
        $html .= '<option ' . selected(get_option("daim_suggestions_categories"), 'ignore', false) . ' value="ignore">' . esc_html__('Ignore', 'daim') . '</option>';
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr__('Select if the algorithm available in the "Interlinks Suggestions" meta box should consider the post categories. If "Required" is selected the algorithm will return only posts that have at least one category in common with the edited post.', 'daim') . '"></div>';

        echo $html;

    }

    public function suggestions_categories_validation($input){

        $input = sanitize_text_field( $input );

        return $input;

    }

    public function suggestions_tags_callback($args){

        $html = '<select id="daim_suggestions_tags" name="daim_suggestions_tags" class="daext-display-none">';
        $html .= '<option ' . selected(get_option("daim_suggestions_tags"), 'require', false) . ' value="require">' . esc_html__('Require', 'daim') . '</option>';
        $html .= '<option ' . selected(get_option("daim_suggestions_tags"), 'consider', false) . ' value="consider">' . esc_html__('Consider', 'daim') . '</option>';
        $html .= '<option ' . selected(get_option("daim_suggestions_tags"), 'ignore', false) . ' value="ignore">' . esc_html__('Ignore', 'daim') . '</option>';
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr__('Select if the algorithm available in the "Interlinks Suggestions" meta box should consider the post tags. If "Required" is selected the algorithm will return only posts that have at least one tag in common with the edited post.', 'daim') . '"></div>';

        echo $html;

    }

    public function suggestions_tags_validation($input){

        $input = sanitize_text_field( $input );

        return $input;

    }

    public function suggestions_post_type_callback($args){

        $html = '<select id="daim_suggestions_post_type" name="daim_suggestions_post_type" class="daext-display-none">';
        $html .= '<option ' . selected(get_option("daim_suggestions_post_type"), 'require', false) . ' value="require">' . esc_html__('Require', 'daim') . '</option>';
        $html .= '<option ' . selected(get_option("daim_suggestions_post_type"), 'consider', false) . ' value="consider">' . esc_html__('Consider', 'daim') . '</option>';
        $html .= '<option ' . selected(get_option("daim_suggestions_post_type"), 'ignore', false) . ' value="ignore">' . esc_html__('Ignore', 'daim') . '</option>';
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr__('Select if the algorithm available in the "Interlinks Suggestions" meta box should consider the post type. If "Required" is selected the algorithm will return only posts that belong to the same post type of the edited post.', 'daim') . '"></div>';

        echo $html;

    }

    public function suggestions_post_type_validation($input){

        $input = sanitize_text_field( $input );

        return $input;

    }

    //optimization options callbacks and validations ---------------------------

    public function optimization_num_of_characters_callback($args){

        $html = '<input type="text" id="daim_optimization_num_of_characters" name="daim_optimization_num_of_characters" class="regular-text" value="' . intval(get_option("daim_optimization_num_of_characters"), 10) . '" />';
        $html .= '<div class="help-icon" title="' . esc_attr__('The "Recommended Interlinks" value available in the "Dashboard" menu and in the "Interlinks Optimization" meta box is based on the defined "Characters per Interlink" and on the content length of the post. For example if you define 500 "Characters per Interlink", in the "Dashboard" menu, with a post that has a content length of 2000 characters you will get 4 as the value for the "Recommended Interlinks".', 'daim') . '"></div>';

        echo $html;

    }

    public function optimization_num_of_characters_validation($input){

        $input = sanitize_text_field( $input );

        if(!preg_match($this->shared->regex_number_ten_digits, $input) or ( intval($input, 10) < 1 ) or ( intval($input, 10) > 1000000 ) ){
            add_settings_error( 'daim_optimization_num_of_characters', 'daim_optimization_num_of_characters', esc_html__('Please enter a number from 1 to 1000000 in the "Characters per Interlink" option.', 'daim') );
            $output = get_option('daim_optimization_num_of_characters');
        }else{
            $output = $input;
        }

        return intval($output, 10);

    }

    public function optimization_delta_callback($args){

        $html = '<input type="text" id="daim_optimization_delta" name="daim_optimization_delta" class="regular-text" value="' . intval(get_option("daim_optimization_delta"), 10) . '" />';
        $html .= '<div class="help-icon" title="' . esc_attr__('The "Optimization Delta" is used to generate the "Optimization Flag" available in the "Dashboard" menu and the text message diplayed in the "Interlinks Optimization" meta box. This option determines how different can be the actual number of interlinks in a post from the calculated "Recommended Interlinks". This option defines a range, so for example in a post with 10 "Recommended Interlinks" and this option value equal to 4, the post will be considered optimized when it includes from 8 to 12 interlinks.', 'daim') . '"></div>';


        echo $html;

    }

    public function optimization_delta_validation($input){

        $input = sanitize_text_field( $input );

        if(!preg_match($this->shared->regex_number_ten_digits, $input) or ( intval($input, 10) > 1000000 )){
            add_settings_error( 'daim_optimization_delta', 'daim_optimization_delta', esc_html__('Please enter a number from 0 to 1000000 in the "Optimization Delta" option.', 'daim') );
            $output = get_option('daim_optimization_delta');
        }else{
            $output = $input;
        }

        return intval($output, 10);

    }

    //juice options callbacks and validations ----------------------------------
    public function default_seo_power_callback($args){

        $html = '<input type="text" id="daim_default_seo_power" name="daim_default_seo_power" class="regular-text" value="' . intval(get_option("daim_default_seo_power"), 10) . '" />';
        $html .= '<div class="help-icon" title="' . esc_attr__('The "SEO Power" is the base value used to calculate the flow of "Link Juice" and this option determines the default "SEO Power" value of a post. You can override this value for specific posts in the "Interlinks Options" meta box.', 'daim') . '"></div>';

        echo $html;

    }

    public function default_seo_power_validation($input){

        $input = sanitize_text_field( $input );

        if(!preg_match($this->shared->regex_number_ten_digits, $input) or ( intval($input, 10) < 100 ) or ( intval($input, 10) > 1000000 ) ){
            add_settings_error( 'daim_default_seo_power', 'daim_default_seo_power', esc_html__('Please enter a number from 100 to 1000000 in the "SEO Power (Default)" option.', 'daim') );
            $output = get_option('daim_default_seo_power');
        }else{
            $output = $input;
        }

        return intval($output, 10);

    }

    public function penality_per_position_percentage_callback($args){

        $html = '<input type="text" id="daim_penality_per_position_percentage" name="daim_penality_per_position_percentage" class="regular-text" value="' . intval(get_option("daim_penality_per_position_percentage"), 10) . '" />';
        $html .= '<div class="help-icon" title="' . esc_attr__('With multiple links in an article, the algorithm that calculates the "Link Juice" passed by each link removes a percentage of the passed "Link Juice" based on the position of a link compared to the other links.', 'daim') . '"></div>';

        echo $html;

    }

    public function penality_per_position_percentage_validation($input){

        $input = sanitize_text_field( $input );

        if( !preg_match($this->shared->regex_number_ten_digits, $input) or ( intval($input, 10) > 100 ) ){
            add_settings_error( 'daim_penality_per_position_percentage', 'daim_penality_per_position_percentage', esc_html__('Please enter a number from 0 to 100 in the "Penality per position" option.', 'daim') );
            $output = get_option('daim_penality_per_position_percentage');
        }else{
            $output = $input;
        }

        return intval($output, 10);

    }

    public function remove_link_to_anchor_callback($args) {

        $html = '<select id="daim_remove_link_to_anchor" name="daim_remove_link_to_anchor" class="daext-display-none">';
        $html .= '<option ' . selected(intval(get_option("daim_remove_link_to_anchor")), 0, false) . ' value="0">' . esc_html__('No', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_remove_link_to_anchor")), 1, false) . ' value="1">' . esc_html__('Yes', 'daim') . '</option>';
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr__('Select "Yes" to automatically remove links to anchors from every URL used to calculate the link juice. With this option enabled "http://example.com" and "http://example.com#myanchor" will both contribute to generate link juice only for a single URL, that is "http://example.com".', 'daim') . '"></div>';

        echo $html;

    }

    public function remove_link_to_anchor_validation($input){

        $input = sanitize_text_field( $input );

        return intval($input, 10) == 1 ? '1' : '0';

    }

    public function remove_url_parameters_callback($args) {

        $html = '<select id="daim_remove_url_parameters" name="daim_remove_url_parameters" class="daext-display-none">';
        $html .= '<option ' . selected(intval(get_option("daim_remove_url_parameters")), 0, false) . ' value="0">' . esc_html__('No', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_remove_url_parameters")), 1, false) . ' value="1">' . esc_html__('Yes', 'daim') . '</option>';
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr__('Select "Yes" to automatically remove the URL parameters from every URL used to calculate the link juice. With this option enabled "http://example.com" and "http://example.com?param=1" will both contribute to generate link juice only for a single URL, that is "http://example.com". Please note that this option should not be enabled if your website is using URL parameters to actually identify specific pages. (for example with pretty permalinks not enabled)', 'daim') . '"></div>';

        echo $html;

    }

    public function remove_url_parameters_validation($input){

        $input = sanitize_text_field( $input );

        return intval($input, 10) == 1 ? '1' : '0';

    }

    //tracking options callbacks and validations -------------------------------
    public function track_internal_links_callback($args) {

        $html = '<select id="daim_track_internal_links" name="daim_track_internal_links" class="daext-display-none">';
        $html .= '<option ' . selected(intval(get_option("daim_track_internal_links")), 0, false) . ' value="0">' . esc_html__('No', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_track_internal_links")), 1, false) . ' value="1">' . esc_html__('Yes', 'daim') . '</option>';
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr__('With this option enabled every click on the manual and auto internal links will be tracked. The collected data will be available in the "Hits" menu.', 'daim') . '"></div>';

        echo $html;

    }

    public function track_internal_links_validation($input){

        $input = sanitize_text_field( $input );

        return intval($input, 10) == 1 ? '1' : '0';

    }

    //analysis options callbacks and validations ----------------------------
    public function set_max_execution_time_callback($args){

        $html = '<select id="daim_set_max_execution_time" name="daim_set_max_execution_time" class="daext-display-none">';
        $html .= '<option ' . selected(intval(get_option("daim_set_max_execution_time")), 0, false) . ' value="0">' . esc_html__('No', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_set_max_execution_time")), 1, false) . ' value="1">' . esc_html__('Yes', 'daim') . '</option>';
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr__('Select "Yes" to enable your custom "Max Execution Time Value" on long running scripts.', 'daim') . '"></div>';

        echo $html;

    }

    public function set_max_execution_time_validation($input){

        $input = sanitize_text_field( $input );

        return intval($input, 10) == 1 ? '1' : '0';

    }

    public function max_execution_time_value_callback($args){

        $html = '<input type="text" id="daim_max_execution_time_value" name="daim_max_execution_time_value" class="regular-text" value="' . intval(get_option("daim_max_execution_time_value"), 10) . '" />';
        $html .= '<div class="help-icon" title="' . esc_attr__('This value determines the maximum number of seconds allowed to execute long running scripts.', 'daim') . '"></div>';

        echo $html;

    }

    public function max_execution_time_value_validation($input){

        $input = sanitize_text_field( $input );

        if(!preg_match($this->shared->regex_number_ten_digits, $input) or intval($input, 10) < 1 or intval($input, 10) > 1000000 ){
            add_settings_error( 'daim_max_execution_time_value', 'daim_max_execution_time_value', esc_html__('Please enter a number from 1 to 1000000 in the "Max Execution Time Value" option.', 'daim') );
            $output = get_option('daim_max_execution_time_value');
        }else{
            $output = $input;
        }

        return intval($output, 10);

    }

    public function set_memory_limit_callback($args){

        $html = '<select id="daim_set_memory_limit" name="daim_set_memory_limit" class="daext-display-none">';
        $html .= '<option ' . selected(intval(get_option("daim_set_memory_limit")), 0, false) . ' value="0">' . esc_html__('No', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_set_memory_limit")), 1, false) . ' value="1">' . esc_html__('Yes', 'daim') . '</option>';
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr__('Select "Yes" to enable your custom "Memory Limit Value" on long running scripts.', 'daim') . '"></div>';

        echo $html;

    }

    public function set_memory_limit_validation($input){

        $input = sanitize_text_field( $input );

        return intval($input, 10) == 1 ? '1' : '0';

    }

    public function memory_limit_value_callback($args){

        $html = '<input type="text" id="daim_memory_limit_value" name="daim_memory_limit_value" class="regular-text" value="' . intval(get_option("daim_memory_limit_value"), 10) . '" />';
        $html .= '<div class="help-icon" title="' . esc_attr__('This value determines the PHP memory limit in megabytes allowed to execute long running scripts.', 'daim') . '"></div>';

        echo $html;

    }

    public function memory_limit_value_validation($input){

        $input = sanitize_text_field( $input );

        if(!preg_match($this->shared->regex_number_ten_digits, $input) or intval($input, 10) < 1 or intval($input, 10) > 1000000 ){
            add_settings_error( 'daim_memory_limit_value', 'daim_memory_limit_value', esc_html__('Please enter a number from 1 to 1000000 in the "Memory Limit Value" option.', 'daim') );
            $output = get_option('daim_memory_limit_value');
        }else{
            $output = $input;
        }

        return intval($output, 10);

    }

    public function limit_posts_analysis_callback($args){

        $html = '<input type="text" id="daim_limit_posts_analysis" name="daim_limit_posts_analysis" class="regular-text" value="' . intval(get_option("daim_limit_posts_analysis"), 10) . '" />';
        $html .= '<div class="help-icon" title="' . esc_attr__('With this options you can determine the maximum number of posts analyzed to get information about your internal links, to get information about the internal links juice and to get suggestions in the "Interlinks Suggestions" meta box. If you select for example "1000", the analysis performed by the plugin will use your latest "1000" posts.', 'daim') . '"></div>';

        echo $html;

    }

    public function limit_posts_analysis_validation($input){

        $input = sanitize_text_field( $input );

        if(!preg_match($this->shared->regex_number_ten_digits, $input) or intval($input, 10) < 1 or intval($input, 10) > 100000){
            add_settings_error( 'daim_limit_posts_analysis', 'daim_limit_posts_analysis', esc_html__('Please enter a number from 1 to 100000 in the "Limit Posts Analysis" option.', 'daim') );
            $output = get_option('daim_limit_posts_analysis');
        }else{
            $output = $input;
        }

        return intval($output, 10);

    }

    public function dashboard_post_types_callback($args)
    {

        $dashboard_post_types_a = get_option("daim_dashboard_post_types");

        $available_post_types_a = get_post_types(array(
            'public'  => true,
            'show_ui' => true
        ));

        //Remove the "attachment" post type
        $available_post_types_a = array_diff($available_post_types_a, array('attachment'));

        $html = '<select id="daim-dashboard-post-types" name="daim_dashboard_post_types[]" class="daext-display-none" multiple>';

        foreach ($available_post_types_a as $single_post_type) {
            if (is_array($dashboard_post_types_a) and in_array($single_post_type, $dashboard_post_types_a)) {
                $selected = 'selected';
            } else {
                $selected = '';
            }
            $post_type_obj = get_post_type_object($single_post_type);
            $html          .= '<option value="' . $single_post_type . '" ' . $selected . '>' . esc_html($post_type_obj->label) . '</option>';
        }

        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr__('With this option you are able to determine the post types analyzed in the Dashboard menu.',
                'daim') . '"></div>';

        echo $html;

    }

    public function dashboard_post_types_validation($input)
    {

	    if (is_array($input) and count($input) > 0) {
		    $output = $input;
	    } else {
		    add_settings_error( 'daim_dashboard_post_types_validation', 'daim_dashboard_post_types_validation', esc_html__('Please enter at least one post type in the "Dashboard Post Types" option.', 'daim') );
		    $output = get_option('daim_dashboard_post_types');
	    }

	    return $output;

    }
	
    public function juice_post_types_callback($args)
    {

        $juice_post_types_a = get_option("daim_juice_post_types");

        $available_post_types_a = get_post_types(array(
            'public'  => true,
            'show_ui' => true
        ));

        //Remove the "attachment" post type
        $available_post_types_a = array_diff($available_post_types_a, array('attachment'));

        $html = '<select id="daim-juice-post-types" name="daim_juice_post_types[]" class="daext-display-none" multiple>';

        foreach ($available_post_types_a as $single_post_type) {
            if (is_array($juice_post_types_a) and in_array($single_post_type, $juice_post_types_a)) {
                $selected = 'selected';
            } else {
                $selected = '';
            }
            $post_type_obj = get_post_type_object($single_post_type);
            $html          .= '<option value="' . $single_post_type . '" ' . $selected . '>' . esc_html($post_type_obj->label) . '</option>';
        }

        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr__('With this option you are able to determine the post types analyzed in the Juice menu.',
                'daim') . '"></div>';

        echo $html;

    }

    public function juice_post_types_validation($input)
    {

	    if (is_array($input) and count($input) > 0) {
		    $output = $input;
	    } else {
		    add_settings_error( 'daim_juice_post_types_validation', 'daim_juice_post_types_validation', esc_html__('Please enter at least one post type in the "Juice Post Types" option.', 'daim') );
		    $output = get_option('daim_juice_post_types');
	    }

	    return $output;

    }

	public function http_status_post_types_callback($args)
	{

		$http_status_post_types_a = get_option("daim_http_status_post_types");

		$available_post_types_a = get_post_types(array(
			'public'  => true,
			'show_ui' => true
		));

		//Remove the "attachment" post type
		$available_post_types_a = array_diff($available_post_types_a, array('attachment'));

		$html = '<select id="daim-http-status-post-types" name="daim_http_status_post_types[]" class="daext-display-none" multiple>';

		foreach ($available_post_types_a as $single_post_type) {
			if (is_array($http_status_post_types_a) and in_array($single_post_type, $http_status_post_types_a)) {
				$selected = 'selected';
			} else {
				$selected = '';
			}
			$post_type_obj = get_post_type_object($single_post_type);
			$html          .= '<option value="' . $single_post_type . '" ' . $selected . '>' . esc_html($post_type_obj->label) . '</option>';
		}

		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__('With this option you are able to determine the post types analyzed in the HTTP Status menu.',
				'daim') . '"></div>';

		echo $html;

	}

	public function http_status_post_types_validation($input)
	{

		if (is_array($input) and count($input) > 0) {
			$output = $input;
		} else {
			add_settings_error( 'daim_http_status_post_types_validation', 'daim_http_status_post_types_validation', esc_html__('Please enter at least one post type in the "http_status Post Types" option.', 'daim') );
			$output = get_option('daim_http_status_post_types');
		}

		return $output;

	}

    //metaboxes options callbacks and validation -------------------------------
    public function interlinks_options_post_types_callback($args)
    {

        $interlinks_options_post_types_a = get_option("daim_interlinks_options_post_types");

        $available_post_types_a = get_post_types(array(
            'public'  => true,
            'show_ui' => true
        ));

        //Remove the "attachment" post type
        $available_post_types_a = array_diff($available_post_types_a, array('attachment'));

        $html = '<select id="daim-interlinks-options-post-types" name="daim_interlinks_options_post_types[]" class="daext-display-none" multiple>';

        foreach ($available_post_types_a as $single_post_type) {
            if (is_array($interlinks_options_post_types_a) and in_array($single_post_type, $interlinks_options_post_types_a)) {
                $selected = 'selected';
            } else {
                $selected = '';
            }
            $post_type_obj = get_post_type_object($single_post_type);
            $html          .= '<option value="' . $single_post_type . '" ' . $selected . '>' . esc_html($post_type_obj->label) . '</option>';
        }

        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr__('With this option you are able to determine in which post types the "Interlinks Options" meta box should be loaded.',
                'daim') . '"></div>';

        echo $html;

    }

    public function interlinks_options_post_types_validation($input)
    {

        if (is_array($input)) {
            return $input;
        } else {
            return '';
        }

    }

    public function interlinks_optimization_post_types_callback($args)
    {

        $interlinks_optimization_post_types_a = get_option("daim_interlinks_optimization_post_types");

        $available_post_types_a = get_post_types(array(
            'public'  => true,
            'show_ui' => true
        ));

        //Remove the "attachment" post type
        $available_post_types_a = array_diff($available_post_types_a, array('attachment'));

        $html = '<select id="daim-interlinks-optimization-post-types" name="daim_interlinks_optimization_post_types[]" class="daext-display-none" multiple>';

        foreach ($available_post_types_a as $single_post_type) {
            if (is_array($interlinks_optimization_post_types_a) and in_array($single_post_type, $interlinks_optimization_post_types_a)) {
                $selected = 'selected';
            } else {
                $selected = '';
            }
            $post_type_obj = get_post_type_object($single_post_type);
            $html          .= '<option value="' . $single_post_type . '" ' . $selected . '>' . esc_html($post_type_obj->label) . '</option>';
        }

        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr__('With this option you are able to determine in which post types the "Interlinks Optimization" meta box should be loaded.',
                'daim') . '"></div>';

        echo $html;

    }

    public function interlinks_optimization_post_types_validation($input)
    {

        if (is_array($input)) {
            return $input;
        } else {
            return '';
        }

    }

    public function interlinks_suggestions_post_types_callback($args)
    {

        $interlinks_suggestions_post_types_a = get_option("daim_interlinks_suggestions_post_types");

        $available_post_types_a = get_post_types(array(
            'public'  => true,
            'show_ui' => true
        ));

        //Remove the "attachment" post type
        $available_post_types_a = array_diff($available_post_types_a, array('attachment'));

        $html = '<select id="daim-interlinks-suggestions-post-types" name="daim_interlinks_suggestions_post_types[]" class="daext-display-none" multiple>';

        foreach ($available_post_types_a as $single_post_type) {
            if (is_array($interlinks_suggestions_post_types_a) and in_array($single_post_type, $interlinks_suggestions_post_types_a)) {
                $selected = 'selected';
            } else {
                $selected = '';
            }
            $post_type_obj = get_post_type_object($single_post_type);
            $html          .= '<option value="' . $single_post_type . '" ' . $selected . '>' . esc_html($post_type_obj->label) . '</option>';
        }

        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr__('With this option you are able to determine in which post types the "Interlinks Suggestions" meta box should be loaded.',
                'daim') . '"></div>';

        echo $html;

    }

    public function interlinks_suggestions_post_types_validation($input)
    {

        if (is_array($input)) {
            return $input;
        } else {
            return '';
        }

    }

    public function dashboard_menu_required_capability_callback($args){

        $html = '<input type="text" id="daim_dashboard_menu_required_capability" name="daim_dashboard_menu_required_capability" class="regular-text" value="' . esc_attr(get_option("daim_dashboard_menu_required_capability")) . '" />';
        $html .= '<div class="help-icon" title="' . esc_attr__('The capability required to get access on the "Dashboard" Menu.', 'daim') . '"></div>';

        echo $html;

    }

    public function dashboard_menu_required_capability_validation($input){

        $input = sanitize_text_field( $input );

        if(!preg_match($this->shared->regex_capability, $input)){
            add_settings_error( 'daim_dashboard_menu_required_capability', 'daim_dashboard_menu_required_capability', esc_html__('Please enter a valid capability in the "Dashboard Menu" option.', 'daim') );
            $output = get_option('daim_dashboard_menu_required_capability');
        }else{
            $output = $input;
        }

        return trim($output);

    }

    public function juice_menu_required_capability_callback($args){

        $html = '<input type="text" id="daim_juice_menu_required_capability" name="daim_juice_menu_required_capability" class="regular-text" value="' . esc_attr(get_option("daim_juice_menu_required_capability")) . '" />';
        $html .= '<div class="help-icon" title="' . esc_attr__('The capability required to get access on the "Juice" Menu.', 'daim') . '"></div>';

        echo $html;

    }

    public function juice_menu_required_capability_validation($input){

        $input = sanitize_text_field( $input );

        if(!preg_match($this->shared->regex_capability, $input)){
            add_settings_error( 'daim_juice_menu_required_capability', 'daim_juice_menu_required_capability', esc_html__('Please enter a valid capability in the "Juice Menu" option.', 'daim') );
            $output = get_option('daim_juice_menu_required_capability');
        }else{
            $output = $input;
        }

        return trim($output);

    }

    public function hits_menu_required_capability_callback($args){

        $html = '<input type="text" id="daim_hits_menu_required_capability" name="daim_hits_menu_required_capability" class="regular-text" value="' . esc_attr(get_option("daim_hits_menu_required_capability")) . '" />';
        $html .= '<div class="help-icon" title="' . esc_attr__('The capability required to get access on the "Hits" Menu.', 'daim') . '"></div>';

        echo $html;

    }

    public function hits_menu_required_capability_validation($input){

        $input = sanitize_text_field( $input );

        if(!preg_match($this->shared->regex_capability, $input)){
            add_settings_error( 'daim_hits_menu_required_capability', 'daim_hits_menu_required_capability', esc_html__('Please enter a valid capability in the "Hits Menu" option.', 'daim') );
            $output = get_option('daim_hits_menu_required_capability');
        }else{
            $output = $input;
        }

        return trim($output);

    }

	public function http_status_menu_required_capability_callback($args){

		$html = '<input type="text" id="daim_http_status_menu_required_capability" name="daim_http_status_menu_required_capability" class="regular-text" value="' . esc_attr(get_option("daim_http_status_menu_required_capability")) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__('The capability required to get access on the "http_status" Menu.', 'daim') . '"></div>';

		echo $html;

	}

	public function http_status_menu_required_capability_validation($input){

		$input = sanitize_text_field( $input );

		if(!preg_match($this->shared->regex_capability, $input)){
			add_settings_error( 'daim_http_status_menu_required_capability', 'daim_http_status_menu_required_capability', esc_html__('Please enter a valid capability in the "HTTP Status Menu" option.', 'daim') );
			$output = get_option('daim_http_status_menu_required_capability');
		}else{
			$output = $input;
		}

		return trim($output);

	}

    public function wizard_menu_required_capability_callback($args){

        $html = '<input type="text" id="daim_wizard_menu_required_capability" name="daim_wizard_menu_required_capability" class="regular-text" value="' . esc_attr(get_option("daim_wizard_menu_required_capability")) . '" />';
        $html .= '<div class="help-icon" title="' . esc_attr__('The capability required to get access on the "Wizard" Menu.', 'daim') . '"></div>';

        echo $html;

    }

    public function wizard_menu_required_capability_validation($input){

        $input = sanitize_text_field( $input );

        if(!preg_match($this->shared->regex_capability, $input)){
            add_settings_error( 'daim_wizard_menu_required_capability', 'daim_wizard_menu_required_capability', esc_html__('Please enter a valid capability in the "Wizard Menu" option.', 'daim') );
            $output = get_option('daim_wizard_menu_required_capability');
        }else{
            $output = $input;
        }

        return trim($output);

    }

    public function ail_menu_required_capability_callback($args){

        $html = '<input type="text" id="daim_ail_menu_required_capability" name="daim_ail_menu_required_capability" class="regular-text" value="' . esc_attr(get_option("daim_ail_menu_required_capability")) . '" />';
        $html .= '<div class="help-icon" title="' . esc_attr__('The capability required to get access on the "AIL" Menu.', 'daim') . '"></div>';

        echo $html;

    }

    public function ail_menu_required_capability_validation($input){

        $input = sanitize_text_field( $input );

        if(!preg_match($this->shared->regex_capability, $input)){
            add_settings_error( 'daim_ail_menu_required_capability', 'daim_ail_menu_required_capability', esc_html__('Please enter a valid capability in the "AIL Menu" option.', 'daim') );
            $output = get_option('daim_ail_menu_required_capability');
        }else{
            $output = $input;
        }

        return trim($output);

    }

    public function categories_menu_required_capability_callback($args){

        $html = '<input type="text" id="daim_categories_menu_required_capability" name="daim_categories_menu_required_capability" class="regular-text" value="' . esc_attr(get_option("daim_categories_menu_required_capability")) . '" />';
        $html .= '<div class="help-icon" title="' . esc_attr__('The capability required to get access on the "Categories" Menu.', 'daim') . '"></div>';

        echo $html;

    }

    public function categories_menu_required_capability_validation($input){

        $input = sanitize_text_field( $input );

        if(!preg_match($this->shared->regex_capability, $input)){
            add_settings_error( 'daim_categories_menu_required_capability', 'daim_categories_menu_required_capability', esc_html__('Please enter a valid capability in the "Categories Menu" option.', 'daim') );
            $output = get_option('daim_categories_menu_required_capability');
        }else{
            $output = $input;
        }

        return trim($output);

    }

    public function term_groups_menu_required_capability_callback($args){

        $html = '<input type="text" id="daim_term_groups_menu_required_capability" name="daim_term_groups_menu_required_capability" class="regular-text" value="' . esc_attr(get_option("daim_term_groups_menu_required_capability")) . '" />';
        $html .= '<div class="help-icon" title="' . esc_attr__('The capability required to get access on the "Term Groups" Menu.', 'daim') . '"></div>';

        echo $html;

    }

    public function term_groups_menu_required_capability_validation($input){

        $input = sanitize_text_field( $input );

        if(!preg_match($this->shared->regex_capability, $input)){
            add_settings_error( 'daim_term_groups_menu_required_capability', 'daim_term_groups_menu_required_capability', esc_html__('Please enter a valid capability in the "Term Groups Menu" option.', 'daim') );
            $output = get_option('daim_term_groups_menu_required_capability');
        }else{
            $output = $input;
        }

        return trim($output);

    }

    public function import_menu_required_capability_callback($args){

        $html = '<input type="text" id="daim_import_menu_required_capability" name="daim_import_menu_required_capability" class="regular-text" value="' . esc_attr(get_option("daim_import_menu_required_capability")) . '" />';
        $html .= '<div class="help-icon" title="' . esc_attr__('The capability required to get access on the "Import" Menu.', 'daim') . '"></div>';

        echo $html;

    }

    public function import_menu_required_capability_validation($input){

        $input = sanitize_text_field( $input );

        if(!preg_match($this->shared->regex_capability, $input)){
            add_settings_error( 'daim_import_menu_required_capability', 'daim_import_menu_required_capability', esc_html__('Please enter a valid capability in the "Import Menu" option.', 'daim') );
            $output = get_option('daim_import_menu_required_capability');
        }else{
            $output = $input;
        }

        return trim($output);

    }
    
    public function export_menu_required_capability_callback($args){

        $html = '<input type="text" id="daim_export_menu_required_capability" name="daim_export_menu_required_capability" class="regular-text" value="' . esc_attr(get_option("daim_export_menu_required_capability")) . '" />';
        $html .= '<div class="help-icon" title="' . esc_attr__('The capability required to get access on the "Export" Menu.', 'daim') . '"></div>';

        echo $html;

    }

    public function export_menu_required_capability_validation($input){

        $input = sanitize_text_field( $input );

        if(!preg_match($this->shared->regex_capability, $input)){
            add_settings_error( 'daim_export_menu_required_capability', 'daim_export_menu_required_capability', esc_html__('Please enter a valid capability in the "Export Menu" option.', 'daim') );
            $output = get_option('daim_export_menu_required_capability');
        }else{
            $output = $input;
        }

        return trim($output);

    }
    
    public function maintenance_menu_required_capability_callback($args){

        $html = '<input type="text" id="daim_maintenance_menu_required_capability" name="daim_maintenance_menu_required_capability" class="regular-text" value="' . esc_attr(get_option("daim_maintenance_menu_required_capability")) . '" />';
        $html .= '<div class="help-icon" title="' . esc_attr__('The capability required to get access on the "Maintenance" Menu.', 'daim') . '"></div>';

        echo $html;

    }

    public function maintenance_menu_required_capability_validation($input){

        $input = sanitize_text_field( $input );

        if(!preg_match($this->shared->regex_capability, $input)){
            add_settings_error( 'daim_maintenance_menu_required_capability', 'daim_maintenance_menu_required_capability', esc_html__('Please enter a valid capability in the "Maintenance Menu" option.', 'daim') );
            $output = get_option('daim_maintenance_menu_required_capability');
        }else{
            $output = $input;
        }

        return trim($output);

    }

    public function interlinks_options_mb_required_capability_callback($args){

        $html = '<input type="text" id="daim_interlinks_options_mb_required_capability" name="daim_interlinks_options_mb_required_capability" class="regular-text" value="' . esc_attr(get_option("daim_interlinks_options_mb_required_capability")) . '" />';
        $html .= '<div class="help-icon" title="' . esc_attr__('The capability required to get access on the "Interlinks Options" Meta Box.', 'daim') . '"></div>';

        echo $html;

    }

    public function interlinks_options_mb_required_capability_validation($input){

        $input = sanitize_text_field( $input );

        if(!preg_match($this->shared->regex_capability, $input)){
            add_settings_error( 'daim_interlinks_options_mb_required_capability', 'daim_interlinks_options_mb_required_capability', esc_html__('Please enter a valid capability in the "Interlinks Options Meta Box" option.', 'daim') );
            $output = get_option('daim_interlinks_options_mb_required_capability');
        }else{
            $output = $input;
        }

        return trim($output);

    }

    public function interlinks_optimization_mb_required_capability_callback($args){

        $html = '<input type="text" id="daim_interlinks_optimization_mb_required_capability" name="daim_interlinks_optimization_mb_required_capability" class="regular-text" value="' . esc_attr(get_option("daim_interlinks_optimization_mb_required_capability")) . '" />';
        $html .= '<div class="help-icon" title="' . esc_attr__('The capability required to get access on the "Interlinks Optimization" Meta Box.', 'daim') . '"></div>';

        echo $html;

    }

    public function interlinks_optimization_mb_required_capability_validation($input){

        $input = sanitize_text_field( $input );

        if(!preg_match($this->shared->regex_capability, $input)){
            add_settings_error( 'daim_interlinks_optimization_mb_required_capability', 'daim_interlinks_optimization_mb_required_capability', esc_html__('Please enter a valid capability in the "Interlinks Optimization Meta Box" option.', 'daim') );
            $output = get_option('daim_interlinks_optimization_mb_required_capability');
        }else{
            $output = $input;
        }

        return trim($output);

    }

    public function interlinks_suggestions_mb_required_capability_callback($args){

        $html = '<input type="text" id="daim_interlinks_suggestions_mb_required_capability" name="daim_interlinks_suggestions_mb_required_capability" class="regular-text" value="' . esc_attr(get_option("daim_interlinks_suggestions_mb_required_capability")) . '" />';
        $html .= '<div class="help-icon" title="' . esc_attr__('The capability required to get access on the "Interlinks Suggestions" Meta Box.', 'daim') . '"></div>';

        echo $html;

    }

    public function interlinks_suggestions_mb_required_capability_validation($input){

        $input = sanitize_text_field( $input );

        if(!preg_match($this->shared->regex_capability, $input)){
            add_settings_error( 'daim_interlinks_suggestions_mb_required_capability', 'daim_interlinks_suggestions_mb_required_capability', esc_html__('Please enter a valid capability in the "Interlinks Suggestions Meta Box" option.', 'daim') );
            $output = get_option('daim_interlinks_suggestions_mb_required_capability');
        }else{
            $output = $input;
        }

        return trim($output);

    }

    //advanced ---------------------------------------------------------------------------------------------------------
    public function default_enable_ail_on_post_callback($args){

        $html = '<select id="daim_default_enable_ail_on_post" name="daim_default_enable_ail_on_post" class="daext-display-none">';
        $html .= '<option ' . selected(intval(get_option("daim_default_enable_ail_on_post")), 0, false) . ' value="0">' . esc_html__('No', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_default_enable_ail_on_post")), 1, false) . ' value="1">' . esc_html__('Yes', 'daim') . '</option>';
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr__('This option determines the default status of the "Enable AIL" option available in the "Interlinks Options" meta box.', 'daim') . '"></div>';

        echo $html;

    }

    public function default_enable_ail_on_post_validation($input){

        $input = sanitize_text_field( $input );

        return intval($input, 10) == 1 ? '1' : '0';

    }

    public function filter_priority_callback($args)
    {

        $html = '<input maxlength="11" type="text" id="daim_filter_priority" name="daim_filter_priority" class="regular-text" value="' . intval(get_option("daim_filter_priority"),
                10) . '" />';
        $html .= '<div class="help-icon" title="' . esc_attr__('This option determines the priority of the filter used to apply the AIL. A lower number corresponds with an earlier execution.',
                'daim') . '"></div>';
        echo $html;

    }

    public function filter_priority_validation($input)
    {

        $input = sanitize_text_field( $input );

        if (intval($input, 10) < -2147483648 or intval($input, 10) > 2147483646) {
            add_settings_error('daim_filter_priority', 'daim_filter_priority',
                esc_html__('Please enter a number from -2147483648 to 2147483646 in the "Filter Priority" option.',
                    'daim'));
            $output = get_option('daim_filter_priority');
        } else {
            $output = $input;
        }

        return intval($output, 10);

    }

    public function ail_test_mode_callback($args){

        $html = '<select id="daim_ail_test_mode" name="daim_ail_test_mode" class="daext-display-none">';
        $html .= '<option ' . selected(intval(get_option("daim_ail_test_mode")), 0, false) . ' value="0">' . esc_html__('No', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_ail_test_mode")), 1, false) . ' value="1">' . esc_html__('Yes', 'daim') . '</option>';
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr__('With the test mode enabled the AIL will be applied to your posts, pages or custom post types only if the user that is requesting the posts, pages or custom post types has the capability defined with the "AIL Menu" option.', 'daim') . '"></div>';

        echo $html;

    }

    public function ail_test_mode_validation($input){

        $input = sanitize_text_field( $input );

        return intval($input, 10) == 1 ? '1' : '0';

    }

    public function random_prioritization_callback($args)
    {

        $html = '<select id="daim_random_prioritization" name="daim_random_prioritization" class="daext-display-none">';
        $html .= '<option ' . selected(intval(get_option("daim_random_prioritization")), 0,
                false) . ' value="0">' . esc_html__('No', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_random_prioritization")), 1,
                false) . ' value="1">' . esc_html__('Yes', 'daim') . '</option>';
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr__("With this option enabled the order used to apply the AIL with the same priority is randomized on a per-post basis. With this option disabled the order used to apply the AIL with the same priority is the order used to add them in the back-end. It's recommended to enable this option for a better distribution of the AIL.", 'daim') . '"></div>';

        echo $html;

    }

    public function random_prioritization_validation($input)
    {

        $input = sanitize_text_field( $input );

        return intval($input, 10) == 1 ? '1' : '0';

    }

    public function ignore_self_ail_callback($args){

        $html = '<select id="daim_ignore_self_ail" name="daim_ignore_self_ail" class="daext-display-none">';
        $html .= '<option ' . selected(intval(get_option("daim_ignore_self_ail")), 0, false) . ' value="0">' . esc_html__('No', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_ignore_self_ail")), 1, false) . ' value="1">' . esc_html__('Yes', 'daim') . '</option>';
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr__('With this option enabled, the AIL, which have as a target the post where they should be applied, will be ignored.', 'daim') . '"></div>';

        echo $html;

    }

    public function ignore_self_ail_validation($input){

        $input = sanitize_text_field( $input );

        return intval($input, 10) == 1 ? '1' : '0';

    }

    public function categories_and_tags_verification_callback($args)
    {

        $html = '<select id="daim_categories_and_tags_verification" name="daim_categories_and_tags_verification" class="daext-display-none">';
        $html .= '<option ' . selected(get_option("daim_categories_and_tags_verification"), 'post',
                false) . ' value="post">' . esc_html__('Post', 'daim') . '</option>';
        $html .= '<option ' . selected(get_option("daim_categories_and_tags_verification"), 'any',
                false) . ' value="any">' . esc_html__('Any', 'daim') . '</option>';
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr__('If "Post" is selected categories and tags will be verified only in the "post" post type, if "Any" is selected categories and tags will be verified in any post type.',
                'daim') . '"></div>';

        echo $html;

    }

    public function categories_and_tags_verification_validation($input)
    {

        $input = sanitize_text_field( $input );

        switch ($input) {
            case 'post':
                return 'post';
            default:
                return 'any';
        }

    }

    public function general_limit_mode_callback($args)
    {

        $html = '<select id="daim_general_limit_mode" name="daim_general_limit_mode" class="daext-display-none">';
        $html .= '<option ' . selected(intval(get_option("daim_general_limit_mode")), 0,
                false) . ' value="0">' . esc_html__('Auto', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_general_limit_mode")), 1,
                false) . ' value="1">' . esc_html__('Manual', 'daim') . '</option>';
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr__('If "Auto" is selected the maximum number of AIL per post is automatically generated based on the length of the post, in this case the "General Limit (Characters per AIL)" option is used. If "Manual" is selected the maximum number of AIL per post is equal to the value of the "General Limit (Amount)" option.',
                'daim') . '"></div>';

        echo $html;

    }

    public function general_limit_mode_validation($input)
    {

        $input = sanitize_text_field( $input );

        return intval($input, 10) == 1 ? '1' : '0';

    }

    public function characters_per_autolink_callback($args)
    {

        $html = '<input maxlength="7" type="text" id="daim_characters_per_autolink" name="daim_characters_per_autolink" class="regular-text" value="' . intval(get_option("daim_characters_per_autolink"),
                10) . '" />';
        $html .= '<div class="help-icon" title="' . esc_attr__('This value is used to automatically determine the maximum number of AIL per post when the "General Limit Mode" option is set to "Auto".',
                'daim') . '"></div>';
        echo $html;

    }

    public function characters_per_autolink_validation($input)
    {

        $input = sanitize_text_field( $input );

        if ( ! preg_match($this->shared->regex_number_ten_digits, $input) or intval($input, 10) < 1 or intval($input,
                10) > 1000000) {
            add_settings_error('daim_characters_per_autolink',
                'daim_characters_per_autolink',
                esc_html__('Please enter a number from 1 to 1000000 in the "General Limit (Characters per AIL)" option.',
                    'daim'));
            $output = get_option('daim_characters_per_autolink');
        } else {
            $output = $input;
        }

        return intval($output, 10);

    }

    public function max_number_autolinks_per_post_callback($args){

        $html = '<input maxlength="7" type="text" id="daim_max_number_autolinks_per_post" name="daim_max_number_autolinks_per_post" class="regular-text" value="' . intval(get_option("daim_max_number_autolinks_per_post"), 10) . '" />';
        $html .= '<div class="help-icon" title="' . esc_attr__('This value determines the maximum number of AIL per post when the "General Limit Mode" option is set to "Manual".', 'daim') . '"></div>';

        echo $html;

    }

    public function max_number_autolinks_per_post_validation($input){

        $input = sanitize_text_field( $input );

        if(!preg_match($this->shared->regex_number_ten_digits, $input) or intval($input, 10) < 1 or intval($input, 10) > 1000000 ){
            add_settings_error( 'daim_max_number_autolinks_per_post', 'daim_max_number_autolinks_per_post', esc_html__('Please enter a number from 1 to 1000000 in the "General Limit (Amount)" option.', 'daim') );
            $output = get_option('daim_max_number_autolinks_per_post');
        }else{
            $output = $input;
        }

        return intval($output, 10);

    }

    public function general_limit_subtract_mil_callback($args)
    {

        $html = '<select id="daim_general_limit_subtract_mil" name="daim_general_limit_subtract_mil" class="daext-display-none">';
        $html .= '<option ' . selected(intval(get_option("daim_general_limit_subtract_mil"), 10), 0,
                false) . ' value="0">' . esc_html__('No', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_general_limit_subtract_mil"), 10), 1,
                false) . ' value="1">' . esc_html__('Yes', 'daim') . '</option>';
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr__('With this option enabled the number of MIL included in the post will be subtracted from the maximum number of AIL allowed in the post.',
                'daim') . '"></div>';

        echo $html;

    }

    public function general_limit_subtract_mil_validation($input)
    {

        $input = sanitize_text_field( $input );

        return intval($input, 10) == 1 ? '1' : '0';

    }

    public function same_url_limit_callback($args)
    {

        $html = '<input maxlength="7" type="text" id="daim_same_url_limit" name="daim_same_url_limit" class="regular-text" value="' . intval(get_option("daim_same_url_limit"),
                10) . '" />';
        $html .= '<div class="help-icon" title="' . esc_attr__('This option limits the number of AIL with the same URL to a specific value.',
                'daim') . '"></div>';
        echo $html;

    }

    public function same_url_limit_validation($input)
    {

        $input = sanitize_text_field( $input );

        if ( ! preg_match($this->shared->regex_number_ten_digits, $input) or intval($input, 10) < 1 or intval($input,
                10) > 1000000) {
            add_settings_error('daim_same_url_limit', 'daim_same_url_limit',
                esc_html__('Please enter a number from 1 to 1000000 in the "Same URL Limit" option.', 'daim'));
            $output = get_option('daim_same_url_limit');
        } else {
            $output = $input;
        }

        return intval($output, 10);

    }

    public function wizard_rows_callback($args)
    {

        $html = '<input maxlength="7" type="text" id="daim_wizard_rows" name="daim_wizard_rows" class="regular-text" value="' . intval(get_option("daim_wizard_rows"),
                10) . '" />';
        $html .= '<div class="help-icon" title="' . esc_attr__('This option determines the number of rows available in the table of the Wizard menu.',
                'daim') . '"></div>';
        echo $html;

    }

    public function wizard_rows_validation($input)
    {

        $input = sanitize_text_field( $input );

        if ( ! preg_match($this->shared->regex_number_ten_digits, $input) or intval($input, 10) < 100 or intval($input,
                10) > 2000) {
            add_settings_error('daim_wizard_rows', 'daim_wizard_rows',
                esc_html__('Please enter a number from 100 to 2000 in the "Wizard Rows" option.', 'daim'));
            $output = get_option('daim_wizard_rows');
        } else {
            $output = $input;
        }

        return intval($output, 10);

    }

    public function supported_terms_callback($args)
    {

        $html = '<input maxlength="7" type="text" id="daim_supported_terms" name="daim_supported_terms" class="regular-text" value="' . intval(get_option("daim_supported_terms"),
                10) . '" />';
        $html .= '<div class="help-icon" title="' . esc_attr__('This option determines the maximum number of terms supported in a single term group.',
                'daim') . '"></div>';
        echo $html;

    }

    public function supported_terms_validation($input)
    {

        $input = sanitize_text_field( $input );

        if ( ! preg_match($this->shared->regex_number_ten_digits, $input) or intval($input,
                10) > 50) {
            add_settings_error('daim_supported_terms', 'daim_supported_terms',
                esc_html__('Please enter a number from 1 to 50 in the "Supported Terms" option.', 'daim'));
            $output = get_option('daim_supported_terms');
        } else {
            $output = $input;
        }

        return intval($output, 10);

    }

	public function protect_attributes_callback($args)
	{

		$html = '<select id="daim_protect_attributes" name="daim_protect_attributes" class="daext-display-none">';
		$html .= '<option ' . selected(intval(get_option("daim_protect_attributes")), 0,
				false) . ' value="0">' . esc_html__('No', 'daim') . '</option>';
		$html .= '<option ' . selected(intval(get_option("daim_protect_attributes")), 1,
				false) . ' value="1">' . esc_html__('Yes', 'daim') . '</option>';
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__("With this option enabled, the AIL will not be applied to HTML attributes.", 'daim') . '"></div>';

		echo $html;

	}

	public function protect_attributes_validation($input)
	{

		$input = sanitize_text_field( $input );

		return intval($input, 10) == 1 ? '1' : '0';

	}
	
    public function protected_tags_callback($args)
    {

        $protected_tags_a = $this->shared->get_protected_tags_option();

        $html = '<select id="daim-protected-tags" name="daim_protected_tags[]" class="daext-display-none" multiple>';

        $list_of_html_tags = array(
            'a',
            'abbr',
            'acronym',
            'address',
            'applet',
            'area',
            'article',
            'aside',
            'audio',
            'b',
            'base',
            'basefont',
            'bdi',
            'bdo',
            'big',
            'blockquote',
            'body',
            'br',
            'button',
            'canvas',
            'caption',
            'center',
            'cite',
            'code',
            'col',
            'colgroup',
            'datalist',
            'dd',
            'del',
            'details',
            'dfn',
            'dir',
            'div',
            'dl',
            'dt',
            'em',
            'embed',
            'fieldset',
            'figcaption',
            'figure',
            'font',
            'footer',
            'form',
            'frame',
            'frameset',
            'h1',
            'h2',
            'h3',
            'h4',
            'h5',
            'h6',
            'head',
            'header',
            'hgroup',
            'hr',
            'html',
            'i',
            'iframe',
            'img',
            'input',
            'ins',
            'kbd',
            'keygen',
            'label',
            'legend',
            'li',
            'link',
            'map',
            'mark',
            'menu',
            'meta',
            'meter',
            'nav',
            'noframes',
            'noscript',
            'object',
            'ol',
            'optgroup',
            'option',
            'output',
            'p',
            'param',
            'pre',
            'progress',
            'q',
            'rp',
            'rt',
            'ruby',
            's',
            'samp',
            'script',
            'section',
            'select',
            'small',
            'source',
            'span',
            'strike',
            'strong',
            'style',
            'sub',
            'summary',
            'sup',
            'table',
            'tbody',
            'td',
            'textarea',
            'tfoot',
            'th',
            'thead',
            'time',
            'title',
            'tr',
            'tt',
            'u',
            'ul',
            'var',
            'video',
            'wbr'
        );

        foreach ($list_of_html_tags as $key => $tag) {
            $html .= '<option value="' . $tag . '" ' . $this->shared->selected_array($protected_tags_a,
                    $tag) . '>' . $tag . '</option>';
        }

        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr__('With this option you are able to determine in which HTML tags the AIL should not be applied.',
                'daim') . '"></div>';

        echo $html;

    }

    public function protected_tags_validation($input)
    {

        if (is_array($input)) {
            return $input;
        } else {
            return '';
        }

    }

    public function protected_gutenberg_blocks_callback($args)
    {

        $protected_gutenberg_blocks_a = get_option("daim_protected_gutenberg_blocks");

        $html = '<select id="daim-protected-gutenberg-blocks" name="daim_protected_gutenberg_blocks[]" class="daext-display-none" multiple>';

        $html .= '<option value="paragraph" ' . $this->shared->selected_array($protected_gutenberg_blocks_a,
                'paragraph') . '>' . esc_html__('Paragraph', 'daim') . '</option>';
        $html .= '<option value="image" ' . $this->shared->selected_array($protected_gutenberg_blocks_a,
                'image') . '>' . esc_html__('Image', 'daim') . '</option>';
        $html .= '<option value="heading" ' . $this->shared->selected_array($protected_gutenberg_blocks_a,
                'heading') . '>' . esc_html__('Heading', 'daim') . '</option>';
        $html .= '<option value="gallery" ' . $this->shared->selected_array($protected_gutenberg_blocks_a,
                'gallery') . '>' . esc_html__('Gallery', 'daim') . '</option>';
        $html .= '<option value="list" ' . $this->shared->selected_array($protected_gutenberg_blocks_a,
                'list') . '>' . esc_html__('List', 'daim') . '</option>';
        $html .= '<option value="quote" ' . $this->shared->selected_array($protected_gutenberg_blocks_a,
                'quote') . '>' . esc_html__('Quote', 'daim') . '</option>';
        $html .= '<option value="audio" ' . $this->shared->selected_array($protected_gutenberg_blocks_a,
                'audio') . '>' . esc_html__('Audio', 'daim') . '</option>';
        $html .= '<option value="cover-image" ' . $this->shared->selected_array($protected_gutenberg_blocks_a,
                'cover-image') . '>' . esc_html__('Cover Image', 'daim') . '</option>';
        $html .= '<option value="subhead" ' . $this->shared->selected_array($protected_gutenberg_blocks_a,
                'subhead') . '>' . esc_html__('Subhead', 'daim') . '</option>';
        $html .= '<option value="video" ' . $this->shared->selected_array($protected_gutenberg_blocks_a,
                'video') . '>' . esc_html__('Video', 'daim') . '</option>';
        $html .= '<option value="code" ' . $this->shared->selected_array($protected_gutenberg_blocks_a,
                'code') . '>' . esc_html__('Code', 'daim') . '</option>';
        $html .= '<option value="html" ' . $this->shared->selected_array($protected_gutenberg_blocks_a,
                'html') . '>' . esc_html__('Custom HTML', 'daim') . '</option>';
        $html .= '<option value="preformatted" ' . $this->shared->selected_array($protected_gutenberg_blocks_a,
                'preformatted') . '>' . esc_html__('Preformatted', 'daim') . '</option>';
        $html .= '<option value="pullquote" ' . $this->shared->selected_array($protected_gutenberg_blocks_a,
                'pullquote') . '>' . esc_html__('Pullquote', 'daim') . '</option>';
        $html .= '<option value="table" ' . $this->shared->selected_array($protected_gutenberg_blocks_a,
                'table') . '>' . esc_html__('Table', 'daim') . '</option>';
        $html .= '<option value="verse" ' . $this->shared->selected_array($protected_gutenberg_blocks_a,
                'verse') . '>' . esc_html__('Verse', 'daim') . '</option>';
        $html .= '<option value="button" ' . $this->shared->selected_array($protected_gutenberg_blocks_a,
                'button') . '>' . esc_html__('Button', 'daim') . '</option>';
        $html .= '<option value="columns" ' . $this->shared->selected_array($protected_gutenberg_blocks_a,
                'columns') . '>' . esc_html__('Columns', 'daim') . '</option>';
        $html .= '<option value="more" ' . $this->shared->selected_array($protected_gutenberg_blocks_a,
                'more') . '>' . esc_html__('More', 'daim') . '</option>';
        $html .= '<option value="nextpage" ' . $this->shared->selected_array($protected_gutenberg_blocks_a,
                'nextpage') . '>' . esc_html__('Page Break', 'daim') . '</option>';
        $html .= '<option value="separator" ' . $this->shared->selected_array($protected_gutenberg_blocks_a,
                'separator') . '>' . esc_html__('Separator', 'daim') . '</option>';
        $html .= '<option value="spacer" ' . $this->shared->selected_array($protected_gutenberg_blocks_a,
                'spacer') . '>' . esc_html__('Spacer', 'daim') . '</option>';
        $html .= '<option value="text-columns" ' . $this->shared->selected_array($protected_gutenberg_blocks_a,
                'text-columns') . '>' . esc_html__('Text Columnns', 'daim') . '</option>';
        $html .= '<option value="shortcode" ' . $this->shared->selected_array($protected_gutenberg_blocks_a,
                'shortcode') . '>' . esc_html__('Shortcode', 'daim') . '</option>';
        $html .= '<option value="categories" ' . $this->shared->selected_array($protected_gutenberg_blocks_a,
                'categories') . '>' . esc_html__('Categories', 'daim') . '</option>';
        $html .= '<option value="latest-posts" ' . $this->shared->selected_array($protected_gutenberg_blocks_a,
                'latest-posts') . '>' . esc_html__('Latest Posts', 'daim') . '</option>';
        $html .= '<option value="embed" ' . $this->shared->selected_array($protected_gutenberg_blocks_a,
                'embed') . '>' . esc_html__('Embed', 'daim') . '</option>';
        $html .= '<option value="core-embed/twitter" ' . $this->shared->selected_array($protected_gutenberg_blocks_a,
                'core-embed/twitter') . '>' . esc_html__('Twitter', 'daim') . '</option>';
        $html .= '<option value="core-embed/youtube" ' . $this->shared->selected_array($protected_gutenberg_blocks_a,
                'core-embed/youtube') . '>' . esc_html__('YouTube', 'daim') . '</option>';
        $html .= '<option value="core-embed/facebook" ' . $this->shared->selected_array($protected_gutenberg_blocks_a,
                'core-embed/facebook') . '>' . esc_html__('Facebook', 'daim') . '</option>';
        $html .= '<option value="core-embed/instagram" ' . $this->shared->selected_array($protected_gutenberg_blocks_a,
                'core-embed/instagram') . '>' . esc_html__('Instagram', 'daim') . '</option>';
        $html .= '<option value="core-embed/wordpress" ' . $this->shared->selected_array($protected_gutenberg_blocks_a,
                'core-embed/wordpress') . '>' . esc_html__('WordPress', 'daim') . '</option>';
        $html .= '<option value="core-embed/soundcloud" ' . $this->shared->selected_array($protected_gutenberg_blocks_a,
                'core-embed/soundcloud') . '>' . esc_html__('SoundCloud', 'daim') . '</option>';
        $html .= '<option value="core-embed/spotify" ' . $this->shared->selected_array($protected_gutenberg_blocks_a,
                'core-embed/spotify') . '>' . esc_html__('Spotify', 'daim') . '</option>';
        $html .= '<option value="core-embed/flickr" ' . $this->shared->selected_array($protected_gutenberg_blocks_a,
                'core-embed/flickr') . '>' . esc_html__('Flickr', 'daim') . '</option>';
        $html .= '<option value="core-embed/vimeo" ' . $this->shared->selected_array($protected_gutenberg_blocks_a,
                'core-embed/vimeo') . '>' . esc_html__('Vimeo', 'daim') . '</option>';
        $html .= '<option value="core-embed/animoto" ' . $this->shared->selected_array($protected_gutenberg_blocks_a,
                'core-embed/animoto') . '>' . esc_html__('Animoto', 'daim') . '</option>';
        $html .= '<option value="core-embed/cloudup" ' . $this->shared->selected_array($protected_gutenberg_blocks_a,
                'core-embed/cloudup') . '>' . esc_html__('Cloudup', 'daim') . '</option>';
        $html .= '<option value="core-embed/collegehumor" ' . $this->shared->selected_array($protected_gutenberg_blocks_a,
                'core-embed/collegehumor') . '>' . esc_html__('CollegeHumor', 'daim') . '</option>';
        $html .= '<option value="core-embed/dailymotion" ' . $this->shared->selected_array($protected_gutenberg_blocks_a,
                'core-embed/dailymotion') . '>' . esc_html__('DailyMotion', 'daim') . '</option>';
        $html .= '<option value="core-embed/funnyordie" ' . $this->shared->selected_array($protected_gutenberg_blocks_a,
                'core-embed/funnyordie') . '>' . esc_html__('Funny or Die', 'daim') . '</option>';
        $html .= '<option value="core-embed/hulu" ' . $this->shared->selected_array($protected_gutenberg_blocks_a,
                'core-embed/hulu') . '>' . esc_html__('Hulu', 'daim') . '</option>';
        $html .= '<option value="core-embed/imgur" ' . $this->shared->selected_array($protected_gutenberg_blocks_a,
                'core-embed/imgur') . '>' . esc_html__('Imgur', 'daim') . '</option>';
        $html .= '<option value="core-embed/issuu" ' . $this->shared->selected_array($protected_gutenberg_blocks_a,
                'core-embed/issuu') . '>' . esc_html__('Issuu', 'daim') . '</option>';
        $html .= '<option value="core-embed/kickstarter" ' . $this->shared->selected_array($protected_gutenberg_blocks_a,
                'core-embed/kickstarter') . '>' . esc_html__('Kickstarter', 'daim') . '</option>';
        $html .= '<option value="core-embed/meetup-com" ' . $this->shared->selected_array($protected_gutenberg_blocks_a,
                'core-embed/meetup-com') . '>' . esc_html__('Meetup.com', 'daim') . '</option>';
        $html .= '<option value="core-embed/mixcloud" ' . $this->shared->selected_array($protected_gutenberg_blocks_a,
                'core-embed/mixcloud') . '>' . esc_html__('Mixcloud', 'daim') . '</option>';
        $html .= '<option value="core-embed/photobucket" ' . $this->shared->selected_array($protected_gutenberg_blocks_a,
                'core-embed/photobucket') . '>' . esc_html__('Photobucket', 'daim') . '</option>';
        $html .= '<option value="core-embed/polldaddy" ' . $this->shared->selected_array($protected_gutenberg_blocks_a,
                'core-embed/polldaddy') . '>' . esc_html__('Polldaddy', 'daim') . '</option>';
        $html .= '<option value="core-embed/reddit" ' . $this->shared->selected_array($protected_gutenberg_blocks_a,
                'core-embed/reddit') . '>' . esc_html__('Reddit', 'daim') . '</option>';
        $html .= '<option value="core-embed/reverbnation" ' . $this->shared->selected_array($protected_gutenberg_blocks_a,
                'core-embed/reverbnation') . '>' . esc_html__('ReverbNation', 'daim') . '</option>';
        $html .= '<option value="core-embed/screencast" ' . $this->shared->selected_array($protected_gutenberg_blocks_a,
                'core-embed/screencast') . '>' . esc_html__('Screencast', 'daim') . '</option>';
        $html .= '<option value="core-embed/scribd" ' . $this->shared->selected_array($protected_gutenberg_blocks_a,
                'core-embed/scribd') . '>' . esc_html__('Scribd', 'daim') . '</option>';
        $html .= '<option value="core-embed/slideshare" ' . $this->shared->selected_array($protected_gutenberg_blocks_a,
                'core-embed/slideshare') . '>' . esc_html__('Slideshare', 'daim') . '</option>';
        $html .= '<option value="core-embed/smugmug" ' . $this->shared->selected_array($protected_gutenberg_blocks_a,
                'core-embed/smugmug') . '>' . esc_html__('SmugMug', 'daim') . '</option>';
        $html .= '<option value="core-embed/speaker" ' . $this->shared->selected_array($protected_gutenberg_blocks_a,
                'core-embed/speaker') . '>' . esc_html__('Speaker', 'daim') . '</option>';
        $html .= '<option value="core-embed/ted" ' . $this->shared->selected_array($protected_gutenberg_blocks_a,
                'core-embed/ted') . '>' . esc_html__('Ted', 'daim') . '</option>';
        $html .= '<option value="core-embed/tumblr" ' . $this->shared->selected_array($protected_gutenberg_blocks_a,
                'core-embed/tumblr') . '>' . esc_html__('Tumblr', 'daim') . '</option>';
        $html .= '<option value="core-embed/videopress" ' . $this->shared->selected_array($protected_gutenberg_blocks_a,
                'core-embed/videopress') . '>' . esc_html__('VideoPress', 'daim') . '</option>';
        $html .= '<option value="core-embed/wordpress-tv" ' . $this->shared->selected_array($protected_gutenberg_blocks_a,
                'core-embed/wordpress-tv') . '>' . esc_html__('WordPress.tv', 'daim') . '</option>';

        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr__('With this option you are able to determine in which Gutenberg blocks the AIL should not be applied.',
                'daim') . '"></div>';

        echo $html;

    }

    public function protected_gutenberg_blocks_validation($input)
    {

        if (is_array($input)) {
            return $input;
        } else {
            return '';
        }

    }

    public function protected_gutenberg_custom_blocks_callback($args)
    {

        $html = '<input type="text" id="daim_protected_gutenberg_custom_blocks" name="daim_protected_gutenberg_custom_blocks" class="regular-text" value="' . esc_attr(get_option("daim_protected_gutenberg_custom_blocks")) . '" />';
        $html .= '<div class="help-icon" title="' . esc_attr__('Enter a list of Gutenberg custom blocks, separated by a comma.',
                'daim') . '"></div>';

        echo $html;

    }

    public function protected_gutenberg_custom_blocks_validation($input)
    {

        if (strlen(trim($input)) > 0 and ! preg_match($this->shared->regex_list_of_gutenberg_blocks, $input)) {
            add_settings_error('daim_protected_gutenberg_custom_blocks',
                'daim_protected_gutenberg_custom_blocks',
                esc_html__('Please enter a valid list of Gutenberg custom blocks separated by a comma in the "Protected Gutenberg Custom Blocks" option.',
                    'daim'));
            $output = get_option('daim_protected_gutenberg_custom_blocks');
        } else {
            $output = $input;
        }

        return $output;

    }

    public function protected_gutenberg_custom_void_blocks_callback($args)
    {

        $html = '<input type="text" id="daim_protected_gutenberg_custom_void_blocks" name="daim_protected_gutenberg_custom_void_blocks" class="regular-text" value="' . esc_attr(get_option("daim_protected_gutenberg_custom_void_blocks")) . '" />';
        $html .= '<div class="help-icon" title="' . esc_attr__('Enter a list of Gutenberg custom void blocks, separated by a comma.',
                'daim') . '"></div>';

        echo $html;

    }

    public function protected_gutenberg_custom_void_blocks_validation($input)
    {

        $input = sanitize_text_field( $input );

        if (strlen(trim($input)) > 0 and ! preg_match($this->shared->regex_list_of_gutenberg_blocks, $input)) {
            add_settings_error('daim_protected_gutenberg_custom_void_blocks',
                'daim_protected_gutenberg_custom_void_blocks',
	            esc_html__('Please enter a valid list of Gutenberg custom void blocks separated by a comma in the "Protected Gutenberg Custom Void Blocks" option.',
                    'daim'));
            $output = get_option('daim_protected_gutenberg_custom_void_blocks');
        } else {
            $output = $input;
        }

        return $output;

    }

    public function pagination_dashboard_menu_callback($args){

        $html = '<select id="daim_pagination_dashboard_menu" name="daim_pagination_dashboard_menu" class="daext-display-none">';
        $html .= '<option ' . selected(intval(get_option("daim_pagination_dashboard_menu")), 10, false) . ' value="10">' . esc_html__('10', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_pagination_dashboard_menu")), 20, false) . ' value="20">' . esc_html__('20', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_pagination_dashboard_menu")), 30, false) . ' value="30">' . esc_html__('30', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_pagination_dashboard_menu")), 40, false) . ' value="40">' . esc_html__('40', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_pagination_dashboard_menu")), 50, false) . ' value="50">' . esc_html__('50', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_pagination_dashboard_menu")), 60, false) . ' value="60">' . esc_html__('60', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_pagination_dashboard_menu")), 70, false) . ' value="70">' . esc_html__('70', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_pagination_dashboard_menu")), 80, false) . ' value="80">' . esc_html__('80', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_pagination_dashboard_menu")), 90, false) . ' value="90">' . esc_html__('90', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_pagination_dashboard_menu")), 100, false) . ' value="100">' . esc_html__('100', 'daim') . '</option>';
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr__('This options determines the number of elements per page displayed in the "Dashboard" menu.', 'daim') . '"></div>';

        echo $html;

    }

    public function pagination_dashboard_menu_validation($input){

        $input = sanitize_text_field( $input );

        return intval($input, 10);

    }

    public function pagination_juice_menu_callback($args){

        $html = '<select id="daim_pagination_juice_menu" name="daim_pagination_juice_menu" class="daext-display-none">';
        $html .= '<option ' . selected(intval(get_option("daim_pagination_juice_menu")), 10, false) . ' value="10">' . esc_html__('10', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_pagination_juice_menu")), 20, false) . ' value="20">' . esc_html__('20', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_pagination_juice_menu")), 30, false) . ' value="30">' . esc_html__('30', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_pagination_juice_menu")), 40, false) . ' value="40">' . esc_html__('40', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_pagination_juice_menu")), 50, false) . ' value="50">' . esc_html__('50', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_pagination_juice_menu")), 60, false) . ' value="60">' . esc_html__('60', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_pagination_juice_menu")), 70, false) . ' value="70">' . esc_html__('70', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_pagination_juice_menu")), 80, false) . ' value="80">' . esc_html__('80', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_pagination_juice_menu")), 90, false) . ' value="90">' . esc_html__('90', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_pagination_juice_menu")), 100, false) . ' value="100">' . esc_html__('100', 'daim') . '</option>';
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr__('This options determines the number of elements per page displayed in the "Juice" menu.', 'daim') . '"></div>';

        echo $html;

    }

    public function pagination_juice_menu_validation($input){

        $input = sanitize_text_field( $input );

        return intval($input, 10);

    }

	public function pagination_http_status_menu_callback($args){

		$html = '<select id="daim_pagination_http_status_menu" name="daim_pagination_http_status_menu" class="daext-display-none">';
		$html .= '<option ' . selected(intval(get_option("daim_pagination_http_status_menu")), 10, false) . ' value="10">' . esc_html__('10', 'daim') . '</option>';
		$html .= '<option ' . selected(intval(get_option("daim_pagination_http_status_menu")), 20, false) . ' value="20">' . esc_html__('20', 'daim') . '</option>';
		$html .= '<option ' . selected(intval(get_option("daim_pagination_http_status_menu")), 30, false) . ' value="30">' . esc_html__('30', 'daim') . '</option>';
		$html .= '<option ' . selected(intval(get_option("daim_pagination_http_status_menu")), 40, false) . ' value="40">' . esc_html__('40', 'daim') . '</option>';
		$html .= '<option ' . selected(intval(get_option("daim_pagination_http_status_menu")), 50, false) . ' value="50">' . esc_html__('50', 'daim') . '</option>';
		$html .= '<option ' . selected(intval(get_option("daim_pagination_http_status_menu")), 60, false) . ' value="60">' . esc_html__('60', 'daim') . '</option>';
		$html .= '<option ' . selected(intval(get_option("daim_pagination_http_status_menu")), 70, false) . ' value="70">' . esc_html__('70', 'daim') . '</option>';
		$html .= '<option ' . selected(intval(get_option("daim_pagination_http_status_menu")), 80, false) . ' value="80">' . esc_html__('80', 'daim') . '</option>';
		$html .= '<option ' . selected(intval(get_option("daim_pagination_http_status_menu")), 90, false) . ' value="90">' . esc_html__('90', 'daim') . '</option>';
		$html .= '<option ' . selected(intval(get_option("daim_pagination_http_status_menu")), 100, false) . ' value="100">' . esc_html__('100', 'daim') . '</option>';
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__('This options determines the number of elements per page displayed in the "HTTP Status" menu.', 'daim') . '"></div>';

		echo $html;

	}

	public function pagination_http_status_menu_validation($input){

		$input = sanitize_text_field( $input );

		return intval($input, 10);

	}

    public function pagination_hits_menu_callback($args){

        $html = '<select id="daim_pagination_hits_menu" name="daim_pagination_hits_menu" class="daext-display-none">';
        $html .= '<option ' . selected(intval(get_option("daim_pagination_hits_menu")), 10, false) . ' value="10">' . esc_html__('10', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_pagination_hits_menu")), 20, false) . ' value="20">' . esc_html__('20', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_pagination_hits_menu")), 30, false) . ' value="30">' . esc_html__('30', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_pagination_hits_menu")), 40, false) . ' value="40">' . esc_html__('40', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_pagination_hits_menu")), 50, false) . ' value="50">' . esc_html__('50', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_pagination_hits_menu")), 60, false) . ' value="60">' . esc_html__('60', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_pagination_hits_menu")), 70, false) . ' value="70">' . esc_html__('70', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_pagination_hits_menu")), 80, false) . ' value="80">' . esc_html__('80', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_pagination_hits_menu")), 90, false) . ' value="90">' . esc_html__('90', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_pagination_hits_menu")), 100, false) . ' value="100">' . esc_html__('100', 'daim') . '</option>';
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr__('This options determines the number of elements per page displayed in the "Hits" menu.', 'daim') . '"></div>';

        echo $html;

    }

    public function pagination_hits_menu_validation($input){

        $input = sanitize_text_field( $input );

        return intval($input, 10);

    }

    public function pagination_ail_menu_callback($args){

        $html = '<select id="daim_pagination_ail_menu" name="daim_pagination_ail_menu" class="daext-display-none">';
        $html .= '<option ' . selected(intval(get_option("daim_pagination_ail_menu")), 10, false) . ' value="10">' . esc_html__('10', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_pagination_ail_menu")), 20, false) . ' value="20">' . esc_html__('20', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_pagination_ail_menu")), 30, false) . ' value="30">' . esc_html__('30', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_pagination_ail_menu")), 40, false) . ' value="40">' . esc_html__('40', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_pagination_ail_menu")), 50, false) . ' value="50">' . esc_html__('50', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_pagination_ail_menu")), 60, false) . ' value="60">' . esc_html__('60', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_pagination_ail_menu")), 70, false) . ' value="70">' . esc_html__('70', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_pagination_ail_menu")), 80, false) . ' value="80">' . esc_html__('80', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_pagination_ail_menu")), 90, false) . ' value="90">' . esc_html__('90', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_pagination_ail_menu")), 100, false) . ' value="100">' . esc_html__('100', 'daim') . '</option>';
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr__('This options determines the number of elements per page displayed in the "AIL" menu.', 'daim') . '"></div>';

        echo $html;

    }

    public function pagination_ail_menu_validation($input){

        $input = sanitize_text_field( $input );

        return intval($input, 10);

    }

    public function pagination_categories_menu_callback($args){

        $html = '<select id="daim_pagination_categories_menu" name="daim_pagination_categories_menu" class="daext-display-none">';
        $html .= '<option ' . selected(intval(get_option("daim_pagination_categories_menu")), 10, false) . ' value="10">' . esc_html__('10', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_pagination_categories_menu")), 20, false) . ' value="20">' . esc_html__('20', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_pagination_categories_menu")), 30, false) . ' value="30">' . esc_html__('30', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_pagination_categories_menu")), 40, false) . ' value="40">' . esc_html__('40', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_pagination_categories_menu")), 50, false) . ' value="50">' . esc_html__('50', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_pagination_categories_menu")), 60, false) . ' value="60">' . esc_html__('60', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_pagination_categories_menu")), 70, false) . ' value="70">' . esc_html__('70', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_pagination_categories_menu")), 80, false) . ' value="80">' . esc_html__('80', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_pagination_categories_menu")), 90, false) . ' value="90">' . esc_html__('90', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_pagination_categories_menu")), 100, false) . ' value="100">' . esc_html__('100', 'daim') . '</option>';
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr__('This options determines the number of elements per page displayed in the "Categories" menu.', 'daim') . '"></div>';

        echo $html;

    }

    public function pagination_categories_menu_validation($input){

        $input = sanitize_text_field( $input );

        return intval($input, 10);

    }

    public function pagination_term_groups_menu_callback($args){

        $html = '<select id="daim_pagination_term_groups_menu" name="daim_pagination_term_groups_menu" class="daext-display-none">';
        $html .= '<option ' . selected(intval(get_option("daim_pagination_term_groups_menu")), 10, false) . ' value="10">' . esc_html__('10', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_pagination_term_groups_menu")), 20, false) . ' value="20">' . esc_html__('20', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_pagination_term_groups_menu")), 30, false) . ' value="30">' . esc_html__('30', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_pagination_term_groups_menu")), 40, false) . ' value="40">' . esc_html__('40', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_pagination_term_groups_menu")), 50, false) . ' value="50">' . esc_html__('50', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_pagination_term_groups_menu")), 60, false) . ' value="60">' . esc_html__('60', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_pagination_term_groups_menu")), 70, false) . ' value="70">' . esc_html__('70', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_pagination_term_groups_menu")), 80, false) . ' value="80">' . esc_html__('80', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_pagination_term_groups_menu")), 90, false) . ' value="90">' . esc_html__('90', 'daim') . '</option>';
        $html .= '<option ' . selected(intval(get_option("daim_pagination_term_groups_menu")), 100, false) . ' value="100">' . esc_html__('100', 'daim') . '</option>';
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr__('This options determines the number of elements per page displayed in the "Term Groups" menu.', 'daim') . '"></div>';

        echo $html;

    }

    public function pagination_term_groups_menu_validation($input){

        $input = sanitize_text_field( $input );

        return intval($input, 10);

    }

	public function http_status_checks_per_iteration_callback($args){

		$html = '<input maxlength="7" type="text" id="daim_http_status_checks_per_iteration" name="daim_http_status_checks_per_iteration" class="regular-text" value="' . intval(get_option("daim_http_status_checks_per_iteration"), 10) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__('This value determines the number of HTTP requests sent at each run of the WP-Cron event. This parameter is used in the WP-Cron event used to verify the HTTP response status codes of the URLs used in the internal links.', 'daim') . '"></div>';

		echo $html;

	}

	public function http_status_checks_per_iteration_validation($input){

		$input = sanitize_text_field( $input );

		if(!preg_match($this->shared->regex_number_ten_digits, $input) or intval($input, 10) < 1 or intval($input, 10) > 20 ){
			add_settings_error( 'daim_http_status_checks_per_iteration', 'daim_http_status_checks_per_iteration', esc_html__('Please enter a number from 1 to 20 in the "HTTP Status WP-Cron Checks Per Run" option.', 'daim') );
			$output = get_option('daim_http_status_checks_per_iteration');
		}else{
			$output = $input;
		}

		return intval($output, 10);

	}
	
	public function http_status_cron_schedule_interval_callback($args){

		$html = '<input maxlength="7" type="text" id="daim_http_status_cron_schedule_interval" name="daim_http_status_cron_schedule_interval" class="regular-text" value="' . intval(get_option("daim_http_status_cron_schedule_interval"), 10) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__('This value determines the interval in seconds between the custom WP-Cron events used to verify the HTTP response status codes of the URLs used in the internal links.', 'daim') . '"></div>';

		echo $html;

	}

	public function http_status_cron_schedule_interval_validation($input){

		$input = sanitize_text_field( $input );

		if(!preg_match($this->shared->regex_number_ten_digits, $input) or intval($input, 10) < 10 or intval($input, 10) > 86400 ){
			add_settings_error( 'daim_http_status_cron_schedule_interval', 'daim_http_status_cron_schedule_interval', esc_html__('Please enter a number from 10 to 86400 in the "HTTP Status WP-Cron Event Interval" option.', 'daim') );
			$output = get_option('daim_http_status_cron_schedule_interval');
		}else{
			$output = $input;
		}

		return intval($output, 10);

	}

	public function http_status_request_timeout_callback($args){

		$html = '<input maxlength="7" type="text" id="daim_http_status_request_timeout" name="daim_http_status_request_timeout" class="regular-text" value="' . intval(get_option("daim_http_status_request_timeout"), 10) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__('This value determines how long the connection should stay open in seconds for the HTTP requests used to verify the HTTP response status codes of the URLs used in the internal links.', 'daim') . '"></div>';

		echo $html;

	}

	public function http_status_request_timeout_validation($input){

		$input = sanitize_text_field( $input );

		if(!preg_match($this->shared->regex_number_ten_digits, $input) or intval($input, 10) < 1 or intval($input, 10) > 60 ){
			add_settings_error( 'daim_http_status_request_timeout', 'daim_http_status_request_timeout', esc_html__('Please enter a number from 1 to 60 in the "HTTP Status Request Timeout" option.', 'daim') );
			$output = get_option('daim_http_status_request_timeout');
		}else{
			$output = $input;
		}

		return intval($output, 10);

	}

}