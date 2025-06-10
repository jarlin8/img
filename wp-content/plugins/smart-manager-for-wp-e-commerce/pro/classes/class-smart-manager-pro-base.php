<?php

if ( !defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Smart_Manager_Pro_Base' ) ) {
	class Smart_Manager_Pro_Base extends Smart_Manager_Base {

		public $dashboard_key = '';
		public static $post_table_cols = array();
		protected static $sm_beta_background_updater_action;
		public static $dashboard = '';
		function __construct($dashboard_key) {
			$this->dashboard_key = $dashboard_key;
			parent::__construct($dashboard_key);
			self::$dashboard = $dashboard_key;
			$this->advanced_search_operators = array_merge( $this->advanced_search_operators, array(
				'startsWith' => 'like',
				'endsWith' => 'like',
				'anyOf' => 'like',
				'notStartsWith' => 'not like',
				'notEndsWith' => 'not like',
				'notAnyOf' => 'not like'
			 ) );

			add_filter( 'sm_dashboard_model', array( &$this, 'pro_dashboard_model' ), 11, 2 );
			add_filter( 'sm_data_model', array( &$this, 'pro_data_model' ), 11, 2);
			add_filter( 'sm_inline_update_pre', array( &$this, 'pro_inline_update_pre' ), 11, 1);
			add_filter( 'sm_default_dashboard_model_postmeta_cols', array( &$this, 'pro_custom_postmeta_cols' ), 11, 1 );
			remove_action( 'transition_post_status', '_update_term_count_on_transition_post_status', 10, 3 ); //removed because taking time in bulk edit, when assign terms to post.
			//map inline terms update data
			add_filter( 'sm_process_inline_terms_update', array( &$this, 'map_inline_terms_update_data' ), 10, 1);
			add_action( 'sm_inline_update_post_data', __CLASS__. '::update_posts' );
			// Code for handling of `starts with/ends with` advanced search operators
			$advanced_search_filter_tables = array( 'posts', 'postmeta', 'terms' );
			switch(  $this->advanced_search_table_types ) {
				case ( ! empty( $this->advanced_search_table_types['flat'] ) && ! empty( $this->advanced_search_table_types['meta'] ) ):
					$advanced_search_filter_tables = array_merge( array_merge( array_keys( $this->advanced_search_table_types['flat'] ), array_keys( $this->advanced_search_table_types['meta'] ) ), array( 'terms' ) );
					break;
				case ( ! empty( $this->advanced_search_table_types['flat'] ) && empty( $this->advanced_search_table_types['meta'] ) ):
					$advanced_search_filter_tables = array_merge( array_keys( $this->advanced_search_table_types['flat'] ), array( 'terms' ) );
					break;
				case ( empty( $this->advanced_search_table_types['flat'] ) && ! empty( $this->advanced_search_table_types['meta'] ) ):
					$advanced_search_filter_tables = array_merge( array_keys( $this->advanced_search_table_types['meta'] ), array( 'terms' ) );
					break;
			}
			if( ! empty( $advanced_search_filter_tables ) && is_array( $advanced_search_filter_tables ) ){
				foreach( $advanced_search_filter_tables as $table ){
					add_filter( 'sm_search_format_query_' . $table . '_col_value', array( &$this, 'format_search_value' ), 11, 2 );
					add_filter( 'sm_search_'. $table .'_cond', array( &$this, 'modify_search_cond' ), 11, 2 );
				}
			}
			add_filter(
				'sm_get_process_names_for_adding_tasks',
				function( $process_name = '' ) {
					if( empty( $process_name ) ) {
						return;
					}
					return array(
						'bulk_edit',
					);
				}
			);
			if ( 'yes' === Smart_Manager_Settings::get( 'delete_media_when_permanently_deleting_post_type_records' ) ) {
				add_action( 'before_delete_post', array( &$this, 'delete_attached_media' ), 11, 2 );
			}
		}

		public function get_yoast_meta_robots_values() {
			return array( '-'            => __( 'Site-wide default', 'smart-manager-for-wp-e-commerce' ),
						'none'         => __( 'None', 'smart-manager-for-wp-e-commerce' ),
						'noimageindex' => __( 'No Image Index', 'smart-manager-for-wp-e-commerce' ),
						'noarchive'    => __( 'No Archive', 'smart-manager-for-wp-e-commerce' ),
						'nosnippet'    => __( 'No Snippet', 'smart-manager-for-wp-e-commerce' ) );
		}

		public function get_rankmath_robots_values() {
			return array( 'index'      => __( 'Index', 'smart-manager-for-wp-e-commerce' ),
						'noindex'      => __( 'No Index', 'smart-manager-for-wp-e-commerce' ),
						'nofollow'     => __( 'No Follow', 'smart-manager-for-wp-e-commerce' ),
						'noarchive'    => __( 'No Archive', 'smart-manager-for-wp-e-commerce' ),
						'noimageindex' => __( 'No Image Index', 'smart-manager-for-wp-e-commerce' ),
						'nosnippet'    => __( 'No Snippet', 'smart-manager-for-wp-e-commerce' ) );
		}

		public function get_rankmath_seo_score_class( $score ) {
			if ( $score > 80 ) {
				return 'great';
			}

			if ( $score > 51 && $score < 81 ) {
				return 'good';
			}

			return 'bad';
		}

		//Filter to add custom columns
		public function pro_custom_postmeta_cols( $postmeta_cols ) {

			$yoast_pm_cols = $rank_math_pm_cols = array();

			$active_plugins = (array) get_option( 'active_plugins', array() );

			if ( is_multisite() ) {
				$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
			}

			if ( ( in_array( 'wordpress-seo/wp-seo.php', $active_plugins, true ) || array_key_exists( 'wordpress-seo/wp-seo.php', $active_plugins ) ) ) {
				$yoast_pm_cols = array('_yoast_wpseo_metakeywords','_yoast_wpseo_title','_yoast_wpseo_metadesc','_yoast_wpseo_meta-robots-noindex','_yoast_wpseo_primary_product_cat','_yoast_wpseo_focuskw_text_input','_yoast_wpseo_linkdex','_yoast_wpseo_focuskw','_yoast_wpseo_redirect','_yoast_wpseo_primary_category','_yoast_wpseo_content_score','_yoast_wpseo_meta-robots-nofollow','_yoast_wpseo_primary_kbe_taxonomy','_yoast_wpseo_opengraph-title','_yoast_wpseo_opengraph-description','_yoast_wpseo_primary_wpm-testimonial-category','_yoast_wpseo_twitter-title','_yoast_wpseo_twitter-description', '_yoast_wpseo_opengraph-image', '_yoast_wpseo_opengraph-image-id', '_yoast_wpseo_twitter-image', '_yoast_wpseo_twitter-image-id', '_yoast_wpseo_focuskeywords');
			}

			if( !empty( $yoast_pm_cols ) ) {
				foreach( $yoast_pm_cols as $meta_key ) {
					if( !isset( $postmeta_cols[ $meta_key ] ) ) {
						$postmeta_cols[ $meta_key ] = array( 'meta_key' => $meta_key, 'meta_value' => '' );
					}
				}	
			}

			if ( ( in_array( 'seo-by-rank-math/rank-math.php', $active_plugins, true ) || array_key_exists( 'seo-by-rank-math/rank-math.php', $active_plugins ) ) ) {
				$rank_math_pm_cols = array('rank_math_title','rank_math_description','rank_math_focus_keyword','rank_math_canonical_url','rank_math_facebook_title','rank_math_facebook_description','rank_math_twitter_title','rank_math_twitter_description','rank_math_breadcrumb_title', 'rank_math_robots', 'rank_math_seo_score', 'rank_math_facebook_image', 'rank_math_twitter_image_id', 'rank_math_twitter_image', 'rank_math_twitter_image_id', 'rank_math_primary_product_cat');
			}

			if( !empty( $rank_math_pm_cols ) ) {
				foreach( $rank_math_pm_cols as $meta_key ) {
					if( !isset( $postmeta_cols[ $meta_key ] ) ) {
						$postmeta_cols[ $meta_key ] = array( 'meta_key' => $meta_key, 'meta_value' => '' );
					}
				}	
			}

			return $postmeta_cols;
		}

		//Function to handle custom fields common in more than 1 post type
		public function pro_dashboard_model( $dashboard_model, $dashboard_model_saved ) {

			$colum_name_titles = array( 	'_yoast_wpseo_title' => __( 'Yoast SEO Title', 'smart-manager-for-wp-e-commerce' ), 
					 						'_yoast_wpseo_metadesc' => __( 'Yoast Meta Description', 'smart-manager-for-wp-e-commerce' ), 
					 						'_yoast_wpseo_metakeywords' => __( 'Yoast Meta Keywords', 'smart-manager-for-wp-e-commerce' ), 
					 						'_yoast_wpseo_focuskw' => __( 'Yoast Focus Keyphrase', 'smart-manager-for-wp-e-commerce' ), 
			 						);

			$html_columns = array( '_yoast_wpseo_content_score' => __( 'Yoast Readability Score', 'smart-manager-for-wp-e-commerce' ),
									'_yoast_wpseo_linkdex' => __( 'Yoast SEO Score', 'smart-manager-for-wp-e-commerce' ),
									'rank_math_seo_score' => __( 'Rank Math SEO Score', 'smart-manager-for-wp-e-commerce' ) );

			$product_cat_index = sm_multidimesional_array_search('terms_product_cat', 'data', $dashboard_model['columns']);

			$column_model = &$dashboard_model['columns'];

			foreach( $column_model as $key => &$column ) {
				if ( empty( $column['src'] ) ) continue;

				$src_exploded = explode("/",$column['src']);

				if (empty($src_exploded)) {
					$col_nm = $column['src'];
				}

				if ( sizeof($src_exploded) > 2 ) {
					$col_table = $src_exploded[0];
					$cond = explode("=",$src_exploded[1]);

					if (sizeof($cond) == 2) {
						$col_nm = $cond[1];
					}
				} else {
					$col_nm = $src_exploded[1];
					$col_table = $src_exploded[0];
				}

				switch( $col_nm ) {
					case '_yoast_wpseo_meta-robots-noindex':
						$column['key'] = $column['name'] = sprintf( 
							/* translators: %1$s: dashboard title */
							__( 'Allow search engines to show this %1$s in search results?', 'smart-manager-for-wp-e-commerce' ), rtrim( $this->dashboard_title, 's' ) );
						$yoast_noindex = array( '0' => __( 'Default', 'smart-manager-for-wp-e-commerce'),
														'2' => __( 'Yes', 'smart-manager-for-wp-e-commerce' ),
														'1' => __( 'No', 'smart-manager-for-wp-e-commerce' ) );

						$column = $this->generate_dropdown_col_model( $column, $yoast_noindex );
						break;

					case '_yoast_wpseo_meta-robots-nofollow':
						$column['key'] = $column['name'] = sprintf(
							/* translators: %1$s: dashboard title */
							__( 'Should search engines follow links on this %1$s?', 'smart-manager-for-wp-e-commerce' ), rtrim( $this->dashboard_title, 's' ) );
						$yoast_nofollow = array('0' => __( 'Yes', 'smart-manager-for-wp-e-commerce' ),
												'1' => __( 'No', 'smart-manager-for-wp-e-commerce' ) );

						$column = $this->generate_dropdown_col_model( $column, $yoast_nofollow );
						break;
					case '_yoast_wpseo_meta-robots-adv':
						$column['key'] = $column['name'] = __( 'Meta robots advanced', 'smart-manager-for-wp-e-commerce' );
						$values = $this->get_yoast_meta_robots_values();
						$column = $this->generate_multilist_col_model( $column, $values );
						break;
					case 'rank_math_robots':
						$column['key'] = $column['name'] = __( 'Robots Meta', 'smart-manager-for-wp-e-commerce' );
						$values = $this->get_rankmath_robots_values();
						$column = $this->generate_multilist_col_model( $column, $values );
						break;
					case ($col_nm == '_yoast_wpseo_primary_product_cat' || $col_nm == 'rank_math_primary_product_cat'):

						$product_cat_values = array();

						$taxonomy_terms = get_terms('product_cat', array('hide_empty'=> 0,'orderby'=> 'id'));
						

						if( !empty( $taxonomy_terms ) ) {
							foreach ($taxonomy_terms as $term_obj) {
								$product_cat_values[$term_obj->term_id] = array();
								$product_cat_values[$term_obj->term_id]['term'] = $term_obj->name;
								$product_cat_values[$term_obj->term_id]['parent'] = $term_obj->parent;
							}
						}

						$values = $parent_cat_term_ids = array();
						foreach( $product_cat_values as $term_id => $obj ) {

							$values[ $term_id ] = $obj['term'];

							if( !empty( $obj['parent'] ) ) {
								$values[ $term_id ] = ( ! empty( $product_cat_values[ $obj['parent'] ] ) ) ? $product_cat_values[ $obj['parent'] ]['term']. ' > ' .$values[ $term_id ] : $values[ $term_id ];
								if( in_array( $obj['parent'], $parent_cat_term_ids ) === false ) {
									$parent_cat_term_ids[] = $obj['parent'];
								}
							}
						}

						//Code for unsetting the parent category ids
						if( !empty( $parent_cat_term_ids ) ) {
							foreach( $parent_cat_term_ids as $parent_id ) {
								if( isset( $values[ $parent_id ] ) ) {
									unset( $values[ $parent_id ] );
								}
							}
						}

						$column = $this->generate_dropdown_col_model( $column, $values );
						break;
					case ( !empty( $colum_name_titles[ $col_nm ] ) ):
						$column['key'] = $column['name'] = $colum_name_titles[ $col_nm ];
						break;
					case ( !empty( $html_columns[ $col_nm ] ) ):
						$column['key'] = $column['name'] = $html_columns[ $col_nm ];
						$column['type'] = 'text';
						$column['renderer']= 'html';
						$column['frozen'] = false;
						$column['sortable'] = false;
						$column['exportable'] = true;
						$column['searchable'] = false;
						$column['editable'] = false;
						$column['editor'] = false;
						$column['batch_editable'] = false;
						$column['hidden'] = true;
						$column['allow_showhide'] = true;
						$column['width'] = 200;
						break;
				}
			}

			if (!empty($dashboard_model_saved)) {
				$col_model_diff = sm_array_recursive_diff($dashboard_model_saved,$dashboard_model);	
			}

			//clearing the transients before return
			if (!empty($col_model_diff)) {
				delete_transient( 'sa_sm_'.$this->dashboard_key );	
			}

			return $dashboard_model;
		}

		public function pro_data_model ($data_model, $data_col_params) {

			if( !class_exists('WPSEO_Rank') && file_exists( WP_PLUGIN_DIR. '/wordpress-seo/inc/class-wpseo-rank.php' ) ) {
				include_once WP_PLUGIN_DIR. '/wordpress-seo/inc/class-wpseo-rank.php';
			}

			if( empty( $data_model['items'] ) ) {
				return $data_model;
			}

			foreach ($data_model['items'] as $key => $data) {
				if (empty($data['posts_id'])) continue;

				//Code for handling data for Yoast Readability Score
				if( !empty( $data['postmeta_meta_key__yoast_wpseo_content_score_meta_value__yoast_wpseo_content_score'] ) && is_callable( array( 'WPSEO_Rank', 'from_numeric_score' ) ) ) {

					$rank  = WPSEO_Rank::from_numeric_score( (int)$data['postmeta_meta_key__yoast_wpseo_content_score_meta_value__yoast_wpseo_content_score'] );
					$title = $rank->get_label();
					$data_model['items'][$key]['postmeta_meta_key__yoast_wpseo_content_score_meta_value__yoast_wpseo_content_score'] = '<div aria-hidden="true" title="' . esc_attr( $title ) . '" class="wpseo-score-icon ' . esc_attr( $rank->get_css_class() ) . '"></div><span class="screen-reader-text wpseo-score-text">' . $title . '</span>';
				}

				//Code for handling data for Yoast SEO Score
				if( !empty( $data['postmeta_meta_key__yoast_wpseo_linkdex_meta_value__yoast_wpseo_linkdex'] ) && is_callable( array( 'WPSEO_Rank', 'from_numeric_score' ) ) ) {

					$rank  = WPSEO_Rank::from_numeric_score( (int)$data['postmeta_meta_key__yoast_wpseo_linkdex_meta_value__yoast_wpseo_linkdex'] );
					$title = $rank->get_label();
					$data_model['items'][$key]['postmeta_meta_key__yoast_wpseo_linkdex_meta_value__yoast_wpseo_linkdex'] = '<div aria-hidden="true" title="' . esc_attr( $title ) . '" class="wpseo-score-icon ' . esc_attr( $rank->get_css_class() ) . '"></div><span class="screen-reader-text wpseo-score-text">' . $title . '</span>';
				}

				//Code for handling Yoast Meta Robots
				if( isset( $data['postmeta_meta_key__yoast_wpseo_meta-robots-adv_meta_value__yoast_wpseo_meta-robots-adv'] ) ) {
					$actual_values = $this->get_yoast_meta_robots_values();
					if( !empty( $data['postmeta_meta_key__yoast_wpseo_meta-robots-adv_meta_value__yoast_wpseo_meta-robots-adv'] ) ) {

						$current_values = explode( ',', $data['postmeta_meta_key__yoast_wpseo_meta-robots-adv_meta_value__yoast_wpseo_meta-robots-adv'] );

						$formatted_value = array();

						foreach( $current_values as $value ) {

							if( !empty( $actual_values[ $value ] ) ) {
								$formatted_value[] = $actual_values[ $value ];
							}
						}

						$data_model['items'][$key]['postmeta_meta_key__yoast_wpseo_meta-robots-adv_meta_value__yoast_wpseo_meta-robots-adv'] = implode(', <br>', $formatted_value);
					} else {
						$data_model['items'][$key]['postmeta_meta_key__yoast_wpseo_meta-robots-adv_meta_value__yoast_wpseo_meta-robots-adv'] = $actual_values['-'];
					}	
				}

				//Code for handling Yoast Meta Robots
				if( isset( $data['postmeta_meta_key_rank_math_robots_meta_value_rank_math_robots'] ) ) {
					$actual_values = $this->get_rankmath_robots_values();
					if( !empty( $data['postmeta_meta_key_rank_math_robots_meta_value_rank_math_robots'] ) ) {

						$current_values = maybe_unserialize( $data['postmeta_meta_key_rank_math_robots_meta_value_rank_math_robots'] );

						$formatted_value = array();

						foreach( $current_values as $value ) {

							if( !empty( $actual_values[ $value ] ) ) {
								$formatted_value[] = $actual_values[ $value ];
							}
						}

						$data_model['items'][$key]['postmeta_meta_key_rank_math_robots_meta_value_rank_math_robots'] = implode(', <br>', $formatted_value);
					} else {
						$data_model['items'][$key]['postmeta_meta_key_rank_math_robots_meta_value_rank_math_robots'] = $actual_values['index'];
					}
				}

				//Code for handling data for Rank Math SEO Score
				if( isset( $data['postmeta_meta_key_rank_math_seo_score_meta_value_rank_math_seo_score'] ) ) {

					$score = ( !empty( $data['postmeta_meta_key_rank_math_seo_score_meta_value_rank_math_seo_score'] ) ) ? $data['postmeta_meta_key_rank_math_seo_score_meta_value_rank_math_seo_score'] : 0;
					$class     = $this->get_rankmath_seo_score_class( $score );
					$score = $score . ' / 100';

					$data_model['items'][$key]['postmeta_meta_key_rank_math_seo_score_meta_value_rank_math_seo_score'] = '<span class="rank-math-seo-score '.$class.'">
						<strong>'.$score.'</strong></span>';
				}
			}

			return $data_model;
		}

		public function pro_inline_update_pre( $edited_data ) {
			if (empty($edited_data)) return $edited_data;

			foreach ($edited_data as $id => $edited_row) {

				if( empty( $id ) ) {
					continue;
				}

				//Code for handling Yoast SEO meta robots editing
				if( !empty( $edited_row['postmeta/meta_key=_yoast_wpseo_meta-robots-adv/meta_value=_yoast_wpseo_meta-robots-adv'] ) ) {
					$actual_values = $this->get_yoast_meta_robots_values();
					$current_values = explode( ', <br>', $edited_row['postmeta/meta_key=_yoast_wpseo_meta-robots-adv/meta_value=_yoast_wpseo_meta-robots-adv'] );

					$formatted_value = array();

					foreach( $current_values as $value ) {

						$key = array_search( $value, $actual_values );

						if( $key !== false ) {
							$formatted_value[] = $key;
						}
					}

					$edited_data[$id]['postmeta/meta_key=_yoast_wpseo_meta-robots-adv/meta_value=_yoast_wpseo_meta-robots-adv'] = implode(',', $formatted_value);
				}

				// Code for handling Rank Math robots editing
				if( !empty( $edited_row['postmeta/meta_key=rank_math_robots/meta_value=rank_math_robots'] ) ) {
					$actual_values = $this->get_yoast_meta_robots_values();
					$current_values = explode( ', <br>', $edited_row['postmeta/meta_key=rank_math_robots/meta_value=rank_math_robots'] );
					$formatted_value = array();

					foreach( $current_values as $value ) {

						$key = array_search( $value, $actual_values );

						if( $key !== false ) {
							$formatted_value[] = $key;
						}
					}

					$edited_data[$id]['postmeta/meta_key=rank_math_robots/meta_value=rank_math_robots'] = $formatted_value;
				}

			}

			return $edited_data;
		}

		public function generate_multilist_col_model( $colObj, $values = array() ) {
			
			$colObj ['values'] = array();

			foreach( $values as $key => $value ) {
				$colObj ['values'][$key] = array( 'term' => $value, 'parent' => 0 );
			}

			//code for handling values for advanced search
			$colObj['search_values'] = array();
			foreach( $values as $key => $value ) {
				$colObj['search_values'][] = array( 'key' => $key, 'value' => $value );
			}

			$colObj ['type'] = $colObj ['editor'] = 'sm.multilist';
			$colObj ['strict'] 			= true;
			$colObj ['allowInvalid'] 	= false;
			$colObj ['editable']		= false;

			return $colObj;
		}

		public function generate_dropdown_col_model( $colObj, $dropdownValues = array() ) {

			$dropdownKeys = ( !empty( $dropdownValues ) ) ? array_keys( $dropdownValues ) : array();
			$colObj['defaultValue'] = ( !empty( $dropdownKeys[0] ) ) ? $dropdownKeys[0] : '';
			$colObj['save_state'] = true;
			
			$colObj['values'] = $dropdownValues;
			$colObj['selectOptions'] = $dropdownValues; //for inline editing

			$colObj['search_values'] = array();
			foreach( $dropdownValues as $key => $value) {
				$colObj['search_values'][] = array('key' => $key, 'value' => $value);
			}

			$colObj['type'] = 'dropdown';
			$colObj['strict'] = true;
			$colObj['allowInvalid'] = false;
			$colObj['editor'] = 'select';
			$colObj['renderer'] = 'selectValueRenderer';

			return $colObj;
		}

		public function get_entire_store_ids() {

			global $wpdb;

			$selected_ids = array();

			if( !empty( $this->req_params['filteredResults'] ) ) {
				$post_ids = get_transient('sa_sm_search_post_ids');
				$selected_ids = ( !empty( $post_ids ) ) ? explode( ",", $post_ids ) : array();
			} else {

				$post_type = (!empty($this->req_params['table_model']['posts']['where'])) ? $this->req_params['table_model']['posts']['where'] : array('post_type' => $this->dashboard_key);

				if( !empty( $this->req_params['table_model']['posts']['where']['post_type'] ) ) {
            		$post_type = ( is_array( $this->req_params['table_model']['posts']['where']['post_type'] ) ) ? $this->req_params['table_model']['posts']['where']['post_type'] : array( $this->req_params['table_model']['posts']['where']['post_type'] );
            	}

				$from = " FROM {$wpdb->prefix}posts ";
				$where = " WHERE post_type IN ('". implode( "','", $post_type ) ."') ";

				$update_trash_records = apply_filters( 'sm_update_trash_records', ( 'yes' === get_option( 'sm_update_trash_records', 'no' ) ) );
				if( empty( $update_trash_records ) && ( is_callable( array( $this, 'is_show_trash_records' ) ) && empty( $this->is_show_trash_records() ) ) ){
					$where .= " AND post_status != 'trash'";
				}
				
				$from	= apply_filters('sm_beta_background_entire_store_ids_from', $from, $this->req_params);
				$where	= apply_filters('sm_beta_background_entire_store_ids_where', $where, $this->req_params);
				
				$query = apply_filters( 'sm_beta_background_entire_store_ids_query', $wpdb->prepare( "SELECT ID ". $from ." ". $where ." AND 1=%d", 1 ) );
				$selected_ids = $wpdb->get_col( $query );
			}

			return $selected_ids;
		}

		//function to handle batch update request
		public function batch_update() {
			global $wpdb, $current_user;
			$col_data_type = self::get_column_data_type( $this->dashboard_key ); // For fetching column data type		
			$batch_update_actions = (!empty($this->req_params['batch_update_actions'])) ? json_decode(stripslashes($this->req_params['batch_update_actions']), true) : array();
			$dashboard_key = $this->dashboard_key; //fix for PHP 5.3 or earlier	
			$batch_update_actions = array_map( function( $batch_update_action ) use ( $dashboard_key, $col_data_type ) {
				$batch_update_action['dashboard_key'] = $dashboard_key;
				$batch_update_action['date_type'] = ( ! empty( $col_data_type[$batch_update_action['type']] ) ) ? $col_data_type[$batch_update_action['type']] : 'text';
				//data type for handling copy_from_field operator
				if ( 'copy_from_field' === $batch_update_action['operator'] ) { 
					$batch_update_action['copy_field_data_type'] = ( ! empty( $col_data_type[$batch_update_action['value']] ) ) ? $col_data_type[$batch_update_action['value']] : 'text';
				}
				return $batch_update_action;
			}, $batch_update_actions);
			$get_selected_ids_and_entire_store_flag = $this->get_selected_ids_and_entire_store_flag();
			$selected_ids = ( ! empty( $get_selected_ids_and_entire_store_flag['selected_ids'] ) ) ? $get_selected_ids_and_entire_store_flag['selected_ids'] : array();
			$is_entire_store = ( ! empty( $get_selected_ids_and_entire_store_flag['entire_store'] ) ) ? $get_selected_ids_and_entire_store_flag['entire_store'] : false;

			self::send_to_background_process( array( 'process_name' => _x( 'Bulk Edit', 'process name', 'smart-manager-for-wp-e-commerce' ),
														'process_key' => 'bulk_edit',
													 	'callback' => array( 'class_path' => $this->req_params['class_path'],
																			'func' => array( $this->req_params['class_nm'], 'process_batch_update' ) ),
													 	'actions' => $batch_update_actions,
														'is_scheduled' => $this->req_params['isScheduled'],
														'scheduled_for' => $this->req_params['scheduledFor'],
														'title' => $this->req_params['title'],
														'selected_ids' => $selected_ids,
														'entire_task' => $this->entire_task,
														'storewide_option' => $this->req_params['storewide_option'],
														'active_module' => $this->req_params['active_module'],
														'entire_store' => $is_entire_store,
														'dashboard_key' => $this->dashboard_key,
														'dashboard_title' => $this->dashboard_title,
														'class_path' => $this->req_params['class_path'],
														'class_nm' => $this->req_params['class_nm'],
														'backgroundProcessRunningMessage' => $this->req_params['backgroundProcessRunningMessage'],
														'SM_IS_WOO30' => $this->req_params['SM_IS_WOO30'],
														'scheduled_action_admin_url' => $this->req_params['scheduledActionAdminUrl']
														 ) );
		}

		//function to handle batch update request
		public static function send_to_background_process( $params = array() ) {
			if ( empty( $params ) || ! is_array( $params ) ) {
				return;
			}
		 	if ( ( isset( $params['is_scheduled'] ) && ! empty( $params['is_scheduled'] ) ) && ( isset( $params['scheduled_for'] ) && ! empty( $params['scheduled_for'] && '0000-00-00 00:00:00' !==  $params['scheduled_for'] ) ) && ( isset( $params['scheduled_action_admin_url'] ) && ! empty( $params['scheduled_action_admin_url'] ) ) ) {
				$timestamp = strtotime( date( $params['scheduled_for'] ) );
				as_schedule_single_action( $timestamp, 'storeapps_smart_manager_scheduled_actions', array( $params ) );
				echo sprintf(
					/* translators: %1$d: number of updated record %2$s: record update message */ 
					_x( "Bulk Edit action scheduled successfully. Check all your scheduled actions <a target='_blank' href='%s'>here</a>.", 'success notification', 'smart-manager-for-wp-e-commerce' ), $params['scheduled_action_admin_url'] );
				exit;
			}
			$identifier = '';
			$process_names = apply_filters( 'sm_get_process_names_for_adding_tasks', $params['process_key'] );
			if ( ! empty( $process_names ) && ( is_array( $process_names ) ) && in_array( $params['process_key'], $process_names ) ) {
				$task_id = 0;
				if ( is_callable( array( 'Smart_Manager_Task', 'task_update' ) ) && ( isset( $params['title'] ) && ( ! empty( $params['title'] ) ) ) && ( ! empty( $params['dashboard_key'] ) ) && ( ! empty( $params['actions'] ) ) && ( ! empty( $params['selected_ids'] ) && is_array( $params['selected_ids'] ) ) ) {
					$task_id = Smart_Manager_Task::task_update(
						array(
							'title' => $params['title'],
							    'created_date' => date('Y-m-d H:i:s'),
							    'completed_date' => '0000-00-00 00:00:00',
							    'post_type' => $params['dashboard_key'],
							    'type' => 'bulk_edit',
							    'status' => 'in-progress',
							    'actions' => ( ! empty( $params['is_scheduled'] ) && is_array( $params['actions'] ) ) ? array_merge( $params['actions'], array( 'is_scheduled' => $params['is_scheduled'] ) ) : $params['actions'],
							    'record_count' => count( $params['selected_ids'] ),
							)
					);
				}
				$params['actions'] = array_map( function( $params_action ) use( $task_id ) {
					$params_action['task_id'] = $task_id;
					return $params_action;
				}, $params['actions'] );
			}
			if ( is_callable( array( 'Smart_Manager_Pro_Background_Updater', 'get_identifier' ) ) ) {
				$identifier = Smart_Manager_Pro_Background_Updater::get_identifier();
			}
			if ( !empty( $identifier ) && ! empty( $params['selected_ids'] ) ) {
				$default_params = array( 'process_name' => _x( 'Bulk edit / Batch update', 'process name', 'smart-manager-for-wp-e-commerce' ),
										'process_key' => 'bulk_edit',
										'callback' => array( 'class_path' => $params['class_path'],
															'func' => array( $params['class_nm'], 'process_batch_update' ) ),
										'id_count' => count( $params['selected_ids'] ),
										'active_dashboard' => $params['dashboard_title']
									);
				$params = ( !empty( $params ) ) ? array_merge( $default_params, $params ) : $default_params;
				update_option( $identifier.'_params', $params, 'no' );
				update_option( $identifier.'_ids', $params['selected_ids'], 'no' );
				update_option( $identifier.'_initial_process', 1, 'no' );

				//Calling the initiate_batch_process function to initiaite the batch process
				if ( is_callable( array( Smart_Manager_Pro_Background_Updater::instance(), 'initiate_batch_process' ) ) ) {
					Smart_Manager_Pro_Background_Updater::instance()->initiate_batch_process();
				}
			}
		}

		/**
		 * Processes batch update conditions and prepares database updates.
		 *
		 * @param array $batch_args Arguments for the batch update, including:
		 *     - 'selected_ids': (array) IDs to be updated in the batch.
		 *     - 'batch_params': (array) Parameters for batch processing
		 *     - 'task_details_data': (array) Optional, data for undoing tasks.
		 * @return void
		*/
		public static function process_batch_update( $batch_args = array() ) {
			if ( empty( $batch_args ) || ( ! is_array( $batch_args ) ) || empty( $batch_args['selected_ids'] ) || empty( $batch_args['batch_params'] ) || empty( $batch_args['batch_params']['process_name'] )) {
				return;
			}
			do_action( 'sm_pro_pre_process_batch_update_args' );
			$db_updates_args = array(); // For storing all of the selected/entire ids args with its actions.
			if ( ( "Undo Tasks" === $batch_args['batch_params']['process_name'] ) || ( ! empty( $batch_args['task_details_data'] ) ) ) {
				foreach ( $batch_args['task_details_data'] as $args ) {
					$args  = self::process_batch_update_args( $args );
					if ( empty( $args ) ) {
						continue;
					}
					$db_updates_args[] = $args;
				}
			} else {
				foreach ( $batch_args['selected_ids'] as $selected_id ) {
					$prev_vals = array();
					foreach ( $batch_args['batch_params']['actions'] as $key => $args ) {
						$args['id'] = $selected_id;
						$args  = self::process_batch_update_args( $args, $prev_vals );
						if ( empty( $args ) ) {
							continue;
						}
						$special_batch_update_operators = ( ( ! empty( $args['operator'] ) ) && ( 'copy_from_field' === $args['operator'] ) && ( ! empty( $args['selected_column_name'] ) ) ) ? array( $args['selected_column_name'] => 'copy_from_field' ) : array();
						$special_batch_update_operators = apply_filters( 'sm_special_batch_update_operators', $special_batch_update_operators, $args );
						if ( ( ! empty( $prev_vals ) ) && is_array( $prev_vals ) && ( ! empty( $special_batch_update_operators ) ) && is_array( $special_batch_update_operators ) ) { // To handle operators like 'set_to_regular_price, set_to_sale_price'.
							$operator_key = array_search( $args['operator'], $special_batch_update_operators );
							$args['value'] = ( ! empty( $operator_key ) && in_array( $operator_key, array_keys( $prev_vals ) ) ) ? $prev_vals[ $operator_key ] : $args['value'];
						}
						$db_updates_args[] = $args;
						if ( $key === ( count( $batch_args['batch_params']['actions'] ) - 1 ) ) {
							continue;
						}
						$prev_vals[ $args['col_nm'] ] = $args['value'];						
					}
				}
			}
			//update the data in database.
			do_action( 'sm_pro_pre_process_batch_db_updates' );
			if ( empty( $db_updates_args ) ) {
				return;
			}
			self::process_batch_update_db_updates( $db_updates_args );
		}

		/**
		 * Processes and validates arguments for batch updates.
		 *
		 * @param array $args Arguments for the batch update, including:
		 *     - 'type': (string) The data type and table/column identifiers.
		 *     - 'operator': (string) Operation to perform (e.g., set, append, increase).
		 *     - 'id': (int) ID of the record to update.
		 *     - 'date_type': (string) Specifies if data is a date, time, or numeric.
		 *     - 'value': (mixed) New value or modifier for the batch update.
		 *     - 'meta': (array) Additional meta options for the update.
		 *
		 * @param array $prev_vals Array of previous values in case of multiple actions for same column.
		 * @return array|false Processed and validated batch update arguments, or false if invalid.
		*/
		public static function process_batch_update_args( $args = array(), $prev_vals = array() ) {
			if ( empty( $args ) ) {
				return false;
			}
			do_action( 'sm_beta_pre_process_batch', $args );
			// code for processing logic for batch update.
			if ( empty( $args['type'] ) || empty( $args['operator'] ) || empty( $args['id'] ) || empty( $args['date_type'] ) ) {
				return false;
			}
			$type_exploded = explode("/",$args['type']);
			if ( empty( $type_exploded ) ) {
				return false;
			}
			if ( sizeof($type_exploded) > 2 ) {
				$args['table_nm'] = $type_exploded[0];
				$cond = explode("=",$type_exploded[1]);
				if (sizeof($cond) == 2) {
					$args['col_nm'] = $cond[1];
				}
			} else {
				$args['col_nm'] = $type_exploded[1];
				$args['table_nm'] = $type_exploded[0];
			}
			$prev_val = $new_val = '';
			if ( ( ! empty( $prev_vals ) ) && is_array( $prev_vals ) && ( ! empty( $prev_vals[ $args['col_nm'] ] ) ) ) {
				$prev_val = $prev_vals[ $args['col_nm'] ];
			} else {
				$prev_val = apply_filters( 'sm_beta_batch_update_prev_value', $prev_val, $args );
				if ( empty( $prev_val ) ) {
					if ( is_callable( array( 'Smart_Manager_Task', 'get_previous_data' ) ) ) {
						$prev_val = Smart_Manager_Task::get_previous_data( $args['id'], $args['table_nm'], $args['col_nm'] );
					}
				}
				if ( 'numeric' === $args['date_type'] ) {
					$prev_val = ( ! empty( $prev_val ) ) ? floatval( $prev_val ) : 0;
				}
			}
			$args['prev_val'] = $prev_val;
			$value1 = $args['value'];
			$args_meta = ( ! empty( $args['meta'] ) ) ? $args['meta'] : array();
			if( $args['date_type'] == 'numeric' ) {
				$value1 = ( ! empty( $value1 ) ) ? floatval( $value1 ) : 0;
			}
			//Code for handling different conditions for updating datetime fields
			if( $args['date_type'] == 'sm.datetime' && ( $args['operator'] == 'set_date_to' || $args['operator'] == 'set_time_to' ) ) {
				//if prev_val is null
				if( empty($prev_val) ) {
					$date = ( $args['operator'] == 'set_date_to' ) ? $value1 : current_time( 'Y-m-d' );
					$time = ( $args['operator'] == 'set_time_to' ) ? $value1 : current_time( 'H:i:s' );
				} else {
					$date = ( $args['operator'] == 'set_date_to' ) ? $value1 : date('Y-m-d', strtotime($prev_val));
					$time = ( $args['operator'] == 'set_time_to' ) ? $value1 : date('H:i:s', strtotime($prev_val));
				}
				$value1 = $date.' '.$time;
			}
			if( ( $args['date_type'] == 'sm.datetime' || $args['date_type'] == 'sm.date' || $args['date_type'] == 'sm.time' ) && !empty( $args['date_type'] ) && $args['date_type'] == 'timestamp' ) { //code for handling timestamp values
				if( $args['date_type'] == 'sm.time' ) {
					$value1 = '1970-01-01 '.$value1;
				}
				$value1 = strtotime( $value1 );
			}
			// Code for handling increase/decrease date by operator
			$date_type_fields = array( 'sm.date', 'sm.datetime', 'sm.time', 'timestamp' );
			$date_format = 'Y-m-d';
			if ( in_array( $args['date_type'], $date_type_fields ) ) {
				switch ( $args['date_type'] ) {
					case 'timestamp': 
					case 'sm.date':
						$date_format = 'Y-m-d';												
						break;
					case 'sm.datetime':
						$date_format = 'Y-m-d H:i:s';							
						break;
					case 'sm.time':
						$date_format = 'h:i';
						break;
				}
				$args['prev_val'] = ( ! empty( $prev_val ) ? ( strtotime( $prev_val ) ? $prev_val : date( $date_format, $prev_val ) ) : current_time( $date_format ) );
				$value1 = ( ! empty( $value1 ) ? ( strtotime( $value1 ) ? $value1 : date( $date_format, $value1 ) ) : $value1 );
			}
			$additional_date_operators = array( 'increase_date_by', 'decrease_date_by' );
			if( in_array( $args['date_type'], $date_type_fields ) && in_array( $args['operator'], $additional_date_operators ) )
			{
				$args['meta']['dateDuration'] = !empty ( $args['meta']['dateDuration'] ) ? $args['meta']['dateDuration'] : ( ( 'sm.time' === $args['date_type'] ) ? 'hours' : 'days' );
				$args['value'] = !empty ( $args['value'] ) ? $args['value'] : 0;
				$prev_val = ( ! empty( $args['prev_val'] ) ) ? $args['prev_val'] : current_time( $date_format );
				$value1  =  date( $date_format, strtotime( $prev_val. ( ( 'increase_date_by' === $args['operator'] ) ? '+' : '-' ) .$args['value']. $args['meta']['dateDuration'] ) );
			}
			if( $args['date_type'] == 'dropdown' || $args['date_type'] == 'multilist' ) {
				if( $args['operator'] == 'add_to' || $args['operator'] == 'remove_from' ) {
					if( $args['table_nm'] == 'terms' ) {
						$prev_val = wp_get_object_terms( $args['id'], $args['col_nm'], 'orderby=none&fields=ids' );
					} else {
						if( !empty( $args['multiSelectSeparator'] ) && !empty( $prev_val ) ) {
							$prev_val = explode( $args['multiSelectSeparator'], $prev_val );
						} else {
							$prev_val = ( !empty( $prev_val ) ) ? $prev_val : array();	
						}
					}
					$value1 = ( !is_array( $value1 ) ) ? array( $value1 ) : $value1;
					if( !empty( $prev_val ) ) {
						$value1 = ( $args['operator'] == 'add_to' ) ? array_merge($prev_val, $value1) : array_diff($prev_val, $value1);
					}
					$value1 = array_unique( $value1 );
				} 
				
				$separator = ( !empty( $args['multiSelectSeparator'] ) ) ? $args['multiSelectSeparator'] : ",";
				$value1 = ( !empty( $separator ) && is_array( $value1 ) ) ? implode( $separator, $value1 ) : $value1;
			}
			if( $args['date_type'] == 'sm.multilist' && $args['operator'] != 'set_to' && $args['table_nm'] == 'postmeta' ) { //code for handling multilist values
				
			}
			// Code for handling serialized data updates
			if( $args['date_type'] == 'sm.serialized' ) {
				$value1 = maybe_unserialize( $value1 );
			}
			// default value for prev_val
			$numeric_operators = array( 'increase_by_per', 'decrease_by_per', 'increase_by_num', 'decrease_by_num' );
			if ( in_array( $args['operator'], $numeric_operators ) && empty( $prev_val ) ) {
				$prev_val = 0;
			}
			//cases to update the value based on the batch update actions
			switch( $args['operator'] ) {
				case 'set_to':
					$new_val = $value1;
					break;
				case 'prepend':
					$new_val = $value1.''.$prev_val;
					break;
				case 'append':
					$new_val = $prev_val.''.$value1;
					break;
				case 'search_and_replace':
					if( isset( $args_meta['replace_value'] ) ){
						$replace_val = ( ! empty( $args_meta['replace_value'] ) ) ? $args_meta['replace_value'] : '';
						$new_val = str_replace( $value1, $replace_val, $prev_val );
					} else {
						$new_val = $prev_val;
					}
					break;
				case 'increase_by_per':
					$new_val = ( ! empty( $prev_val ) ) ? round( ($prev_val + ($prev_val * ($value1 / 100))), apply_filters('sm_beta_pro_num_decimals',get_option( 'woocommerce_price_num_decimals' )) ) : '';
					break;
				case 'decrease_by_per':
					$new_val = self::decrease_value_by_per( $prev_val, $value1 );
					break;
				case 'increase_by_num':
					$new_val = round( ($prev_val + $value1), apply_filters('sm_beta_pro_num_decimals',get_option( 'woocommerce_price_num_decimals' )) );
					break;
				case 'decrease_by_num':
					$new_val = self::decrease_value_by_num( $prev_val, $value1 );
					break;
				default:
					$new_val = $value1;
					break;
			}
			//Code for handling 'copy_from' and 'copy_from_field' action
			$args['copy_from_operators'] = array('copy_from', 'copy_from_field');
			$value1 = ( 'copy_from_field' === $args['operator'] && empty( $args['value'] ) ) ? $args['type'] : $args['value'];
			if( in_array( $args['operator'], $args['copy_from_operators'] ) && ( ! empty( $value1 ) ) ) {
				$args['selected_table_name'] = $args['table_nm'];
				$args['selected_column_name'] = $args['col_nm'];
				$args['selected_value'] = $value1;
				if( 'copy_from_field' === $args['operator'] ) {
					$explode_selected_value = ( false !== strpos( $args['selected_value'], '/' ) ) ? explode('/', $args['selected_value']) : $args['selected_value'];
					if ( is_array( $explode_selected_value ) && sizeof( $explode_selected_value ) >= 2 ) {
						$args['selected_table_name'] = $explode_selected_value[0];
						$args['selected_column_name'] = $explode_selected_value[1];
						$cond = ( false !== strpos( $args['selected_column_name'], '=' ) ) ? explode( "=", $args['selected_column_name'] ) : $args['selected_column_name'];
						$args['selected_column_name'] = ( ( is_array( $cond ) ) && ( 2 === sizeof( $cond ) ) ) ? $cond[1] : $cond;				
					}  
					$args['selected_value'] = $args['id'];	
				}
				switch ( $args['selected_table_name'] ) {
					case 'posts':
						$new_val = get_post_field( $args['selected_column_name'], $args['selected_value'] );
						break;
					case 'postmeta':
						$new_val = get_post_meta( $args['selected_value'], $args['selected_column_name'], true );
						break;
					case 'terms':
						$term_ids = wp_get_object_terms( $args['selected_value'], $args['selected_column_name'], array( 'orderby' => 'term_id', 'order' => 'ASC', 'fields' => 'ids' ) );
						$new_val = ( ! is_wp_error( $term_ids ) && ! empty( $term_ids ) ) ? $term_ids : array();
						break;
					case 'custom':
						$new_val = apply_filters( 'sm_get_value_for_copy_from_operator', $new_val, $args );
						break;
					default:
						$new_val = $value1;
						break;
				}
				$new_val = ( 'numeric' === $args['date_type'] && empty( $new_val ) ) ? 0 : $new_val;
				$args['new_value'] = $new_val;
				$new_val = ( ( 'copy_from_field' === $args['operator'] && ( ! empty ( $args['copy_field_data_type'] ) ) ) && is_callable( array( 'Smart_Manager_Pro_Base', 'handle_serialized_data' ) ) ) ? self::handle_serialized_data( $args ) : $new_val;	
			}
			$args['value'] = $new_val;
			$args = apply_filters( 'sm_beta_post_batch_process_args', $args );
			return $args;
		}

		//function to handle serialized values for copy from field operator
		public static function handle_serialized_data( $args = array() ) {

			if( empty( $args['date_type'] ) || empty( $args['new_value'] ) ) {
				return '';
			}

			switch( true ) {
				case( 'sm.serialized' === $args['date_type'] ):
					return maybe_unserialize( $args['new_value'] );
				case( 'sm.serialized' !== $args['date_type'] && 'sm.serialized' === $args['copy_field_data_type'] ):
					return maybe_serialize( $args['new_value'] );
				default:
					return $args['new_value'];
			}
		}

		//function to handle the batch update db updates
		public static function process_batch_update_db_updates( $arguments = array() ) {
			if ( empty( $arguments ) || ( ! is_array( $arguments ) ) ) {
				return;
			}
			$update = false;
			$default_batch_update = true;
			$post_data = array();
			$update_result = array();
			$postmeta_failed_to_update = array();
			$taxonomies = array();
			//code to map data for updation.
			foreach ( $arguments as $key => $args ) {
				if ( empty( $args['id'] ) ) {
					continue;
				}
				if(empty($post_data[ $args['id'] ]['meta_input'])){
					$post_data[ $args['id'] ]['meta_input'] = array();
				}
				if(empty($post_data[ $args['id'] ]['tax_input'])){
					$post_data[ $args['id'] ]['tax_input'] = array();
				}
				do_action( 'sm_pre_batch_update_db_updates', $args );		
				$default_batch_update = apply_filters( 'sm_default_batch_update_db_updates', $default_batch_update, $args );
				if ( empty( $default_batch_update ) ) {
					continue;
				}
				switch ( $args['table_nm'] ) {
					case 'posts':
						$post_data[ $args['id'] ][ $args['col_nm'] ] = $args['value'];
						if ( 'post_date' === $args['col_nm'] ) {
							$post_data[ $args['id'] ]['edit_date'] = true;
							$post_data[ $args['id'] ]['post_date_gmt'] = get_gmt_from_date( $args['value'] );
						}
					break;
					case 'postmeta':
						$post_data[ $args['id'] ]['meta_input'][ $args['col_nm'] ] = $args['value'];
						break;
					case 'terms':
						$post_data[ $args['id'] ]['tax_input'][$args['col_nm']][] = self::batch_update_terms_table_data( $args, true );
						if ( ! in_array( $args['col_nm'], $taxonomies ) ) {
							$taxonomies[] = $args['col_nm'];
						}
						break;
					case 'custom':
						if ( 'copy_from' === $args['operator'] ) {
							$arguments[ $key ]['update'] = apply_filters( 'sm_update_value_for_copy_from_operator', $args );
						}
						break;
					default:
						$post_data[ $args['id'] ][ $args['col_nm'] ] = $args['value'];
						break;
				}
			}
			set_transient( 'sm_beta_skip_delete_dashboard_transients', 1, DAY_IN_SECONDS ); // for preventing delete dashboard transients
			Smart_Manager_Base::$update_task_details_params = array();
			if ( ! empty( $post_data ) ) {
				$update_result = self::update_posts( array( 'posts_data' => $post_data, 'taxonomies' => $taxonomies ) );
				//for handling failed post metas status
				if( ( ! empty( $update_result ) ) && ( ! empty( $update_result['postmeta_update_result'] ) ) ){
					if ( ( 'success' !== $update_result['postmeta_update_result'] ) && is_array( $update_result['postmeta_update_result'] ) && ( ! empty( $update_result['postmeta_update_result'] ) ) ) {
						$postmeta_failed_to_update = $update_result['postmeta_update_result'];
					}
				}
			}
			foreach ( $arguments as $args ) {
				if ( empty ( $args['id'] ) ) {
					continue;
				}
				$custom_update_status = array_key_exists( 'update', $args ) ? $args['update'] : true;
				switch ( $args['table_nm'] ) {
					case 'posts':
						$update = ( ( empty( $update_result ) ) || ( ! is_array( $update_result ) ) || ( empty( $update_result['posts_update_result'] ) ) ) ? false : true; 
						break;
					case 'postmeta':
						if ( empty ( $args['col_nm'] ) ) break;
						$update = true;
						if( ( empty( $update_result ) ) || ( ! is_array( $update_result ) ) ){ //if all meta data is not updated
							$update = false; 
						} elseif ( ! empty( $postmeta_failed_to_update ) ){ //else check if any meta data failed to update
							$update = ( in_array( $args['id'] . "/" . $args['col_nm'], $postmeta_failed_to_update ) ) ? false : true;
						}
						break;
					case 'terms':
						$update = ( empty( $update_result ) || ( is_array( $update_result ) && ( ( empty( $update_result[ 'taxonomies_update_result' ] ) ) || ( empty( $update_result[ 'taxonomies_update_result' ][ 'status' ] ) ) ) ) ) ? false : true;
						break;
					case 'custom':
						$update = $custom_update_status;
						break;
					default:
						$update = ( ( empty( $update_result ) ) || ( !is_array( $update_result ) ) || ( empty( $update_result['posts_update_result'] ) ) ) ? false : true; 
						break;
				}
				$update = apply_filters( 'sm_post_batch_update_db_updates', $update, $args );
				if ( empty( $update ) ) {
					if ( is_callable( array( 'Smart_Manager', 'log' ) ) ) {
						/* translators: %s process name */
						Smart_Manager::log( 'error', sprintf( _x( '%s failed', 'update status', 'smart-manager-for-wp-e-commerce' ), ( ! empty( $args['process_name'] ) ? $args['process_name'] : '' ) ) );
					}
					continue;
				} elseif ( ! empty( $args['task_id'] ) && ( ! empty( property_exists( 'Smart_Manager_Base', 'update_task_details_params' ) ) ) ) {
					$action = 'set_to';
					if ( in_array( $args['operator'], array( 'add_to', 'remove_from' ) ) ) {
						$action = apply_filters( 'sm_task_update_action', $args['operator'], $args );
					}
					//Special handling for add_to and remove_from operations for terms table.
					if ( ( 'terms' === $args['table_nm'] ) ) {
						$existing_relationships = ( ( ! empty( $update_result[ 'taxonomies_update_result' ] ) ) && ( ! empty( $update_result[ 'taxonomies_update_result' ][ 'existing_relationships' ] ) ) ) ? $update_result[ 'taxonomies_update_result' ][ 'existing_relationships' ] : array();

						if ( ( empty( $args[ 'col_nm' ] ) ) || ( empty( $existing_relationships ) ) || ( empty( $existing_relationships[ $args[ 'id' ] ] ) ) || empty( $existing_relationships[ $args[ 'id' ] ][ $args[ 'col_nm' ] ] ) ) {
							continue;
						}
						$terms_undo_details = self::get_terms_undo_details( $existing_relationships, $args );
						if ( ( empty( $terms_undo_details ) ) || ( ! is_array( $terms_undo_details ) ) || ( empty( $terms_undo_details['action'] ) ) || ( empty( $terms_undo_details['prev_val'] ) ) || ( empty( $terms_undo_details['updated_val'] ) ) ) {
							continue;
						}
						$action = $terms_undo_details['action'];
						$args['prev_val'] =  $terms_undo_details['prev_val'];
						$args['value'] =  $terms_undo_details['updated_val'];
						if ( in_array( $args['operator'], array( 'add_to', 'remove_from' ) ) ) {
							list( $args['prev_val'], $args['value'] ) = [$args['value'], $args['prev_val']];
						}
					}
					Smart_Manager_Base::$update_task_details_params[] = array(
						'task_id' => $args['task_id'],
								'action' => $action,
								'status' => 'completed',
								'record_id' => $args['id'],
								'field' => $args['type'],  
								'prev_val' => ( ( ! empty( $args['col_nm'] ) ) && ( ! empty( $args['date_type'] ) ) ) ? sa_sm_format_prev_val( array(
												'prev_val' => $args['prev_val'],
												'update_column' => $args['col_nm'],
												'col_data_type' => $args['date_type'],
												'updated_val' => $args['value']
												)
											) : $args['prev_val'],
								'updated_val' => $args['value'],
								'operator' => $args['operator'],
					);
				}
			}
			if ( ! empty( Smart_Manager_Base::$update_task_details_params ) ) {
				apply_filters( 'sm_task_details_update_by_prev_val', Smart_Manager_Base::$update_task_details_params );
				// For updating task details table
				if ( ( ! empty( Smart_Manager_Base::$update_task_details_params ) ) && is_callable( array( 'Smart_Manager_Task', 'task_details_update' ) ) ) {
					return Smart_Manager_Task::task_details_update();
				}
			}
			return true;		
		}

		//function to handle batch process complete
		public static function batch_process_complete() {
			$identifier = '';

			if ( is_callable( array( 'Smart_Manager_Pro_Background_Updater', 'get_identifier' ) ) ) {
				$identifier = Smart_Manager_Pro_Background_Updater::get_identifier();
			}

			if( empty( $identifier ) ) {
				return;
			}

			$background_process_params = get_option( $identifier.'_params', false );

			if( empty( $background_process_params ) ) {
				if ( is_callable( array( 'Smart_Manager', 'log' ) ) ) {
					Smart_Manager::log( 'error', _x( 'No batch process params found', 'batch process', 'smart-manager-for-wp-e-commerce' ) );
				}
				return;
			}
			delete_option( $identifier.'_params' );

			// Preparing email content
			$email = get_option('admin_email');
			$site_title = get_option( 'blogname' );

			$email_heading_color = get_option('woocommerce_email_base_color');
			$email_heading_color = (empty($email_heading_color)) ? '#96588a' : $email_heading_color; 
			$email_text_color = get_option('woocommerce_email_text_color');
			$email_text_color = (empty($email_text_color)) ? '#3c3c3c' : $email_text_color; 

			$actions = ( !empty($background_process_params['actions']) ) ? $background_process_params['actions'] : array();

			$records_str = $background_process_params['id_count'] .' '. (( $background_process_params['id_count'] > 1 ) ? __( 'records', SM_TEXT_DOMAIN ) : __( 'record', SM_TEXT_DOMAIN ));
			$records_str .= ( $background_process_params['entire_store'] ) ? ' ('. __( 'entire store', SM_TEXT_DOMAIN ) .')' : '';

			$background_process_param_name = $background_process_params['process_name'];

			$title = sprintf( __( '[%1s] %2s process completed!', SM_TEXT_DOMAIN ), $site_title, $background_process_param_name );

			ob_start();

			include( apply_filters( 'sm_beta_pro_batch_email_template', SM_PRO_EMAIL_TEMPLATE_PATH.'/bulk-edit.php' ) );

			$message = ob_get_clean();

			$subject = $title;
			self::send_email( array( 'subject' => $subject, 'message' => $message, 'email' => $email ) );
		}

		//Function to generate the data for print_invoice
		public function get_print_invoice() {

			global $smart_manager_beta;

			ini_set('memory_limit','512M');
			set_time_limit(0);

			$purchase_id_arr = ( ! empty( $this->req_params['selected_ids'] ) ) ? json_decode( stripslashes( $this->req_params['selected_ids'] ), true ) : array();
			if ( ( ! empty( $this->req_params['storewide_option'] ) ) && ( 'entire_store' === $this->req_params['storewide_option'] ) && ( ! empty( $this->req_params['active_module'] ) ) ) { //code for fetching all the ids
				$purchase_id_arr = $this->get_entire_store_ids();
			}

			$sm_text_domain = 'smart-manager-for-wp-e-commerce';
			$sm_is_woo30 = ( ! empty( Smart_Manager::$sm_is_woo30 ) && 'true' === Smart_Manager::$sm_is_woo30 ) ? true : false;
			$sm_is_woo44 = ( ! empty( Smart_Manager::$sm_is_woo44 ) && 'true' === Smart_Manager::$sm_is_woo44 ) ? true : false;

			ob_start();
			if ( function_exists( 'wc_get_template' ) ) {
				$template = 'order-invoice.php';
				wc_get_template(
					$template,
					array( 'purchase_id_arr' => $purchase_id_arr,
							'sm_text_domain' => $sm_text_domain,
							'sm_is_woo30' => $sm_is_woo30,
							'sm_is_woo44' => $sm_is_woo44,
							'smart_manager_beta' => $smart_manager_beta
						),
					$this->get_template_base_dir( $template ),
					SM_PLUGIN_DIR_PATH .'/pro/templates/'
				);
			} else {
				include( apply_filters( 'sm_beta_pro_batch_order_invoice_template', SM_PRO_URL.'templates/order-invoice.php' ) );
			}
			echo ob_get_clean();
			exit;
		}

		//function to handle duplicate records functionality
		public function duplicate_records() {
			$get_selected_ids_and_entire_store_flag = $this->get_selected_ids_and_entire_store_flag();
			$selected_ids = ( ! empty( $get_selected_ids_and_entire_store_flag['selected_ids'] ) ) ? $get_selected_ids_and_entire_store_flag['selected_ids'] : array();
			$is_entire_store = ( ! empty( $get_selected_ids_and_entire_store_flag['entire_store'] ) ) ? $get_selected_ids_and_entire_store_flag['entire_store'] : false;
			self::send_to_background_process( array( 'process_name' => _x( 'Duplicate Records', 'process name', 'smart-manager-for-wp-e-commerce' ),
														'process_key' => 'duplicate_records',
														'callback' => array( 'class_path' => $this->req_params['class_path'],
																			'func' => array( $this->req_params['class_nm'], 'process_duplicate_record' ) ),
														'selected_ids' => $selected_ids,
														'entire_task' => $this->entire_task,
														'storewide_option' => $this->req_params['storewide_option'],'active_module' => $this->req_params['active_module'],
														'entire_store' => $is_entire_store,
														'dashboard_key' => $this->dashboard_key,
														'dashboard_title' => $this->dashboard_title,
														'class_path' => $this->req_params['class_path'],
														'class_nm' => $this->req_params['class_nm'],
														'backgroundProcessRunningMessage' => $this->req_params['backgroundProcessRunningMessage'],
														'SM_IS_WOO30' => $this->req_params['SM_IS_WOO30']
													)
											);
		}

		public static function get_duplicate_record_settings() {
	
			$defaults = array(
				'status' => 'same',
				'type' => 'same',
				'timestamp' => 'current',
				'title' => '('.__('Copy', SM_TEXT_DOMAIN).')',
				'slug' => 'copy',
				'time_offset' => false,
				'time_offset_days' => 0,
				'time_offset_hours' => 0,
				'time_offset_minutes' => 0,
				'time_offset_seconds' => 0,
				'time_offset_direction' => 'newer'
			);
			
			$settings = apply_filters( 'sm_beta_duplicate_records_settings', $defaults );
			
			return $settings;
		}


		//function to process duplicate records logic
		public static function process_duplicate_record( $params ) {
			$original_id = ( !empty( $params['id'] ) ) ? $params['id'] : '';

			do_action('sm_beta_pre_process_duplicate_records', $original_id );

			//code for processing logic for duplicate records
			if( empty( $original_id ) ) {
				return false;
			}

			global $wpdb;

			// Get the post as an array
			$duplicate = get_post( $original_id, 'ARRAY_A' );
				
			$settings = self::get_duplicate_record_settings();
			
			// Modify title
			$appended = ( $settings['title'] != '' ) ? ' '.$settings['title'] : '';
			$duplicate['post_title'] = $duplicate['post_title'].' '.$appended;
			$duplicate['post_name'] = sanitize_title($duplicate['post_name'].'-'.$settings['slug']);
			
			// Set the post status
			if( $settings['status'] != 'same' ) {
				$duplicate['post_status'] = $settings['status'];
			}
			
			// Set the post type
			if( $settings['type'] != 'same' ) {
				$duplicate['post_type'] = $settings['type'];
			}
			
			// Set the post date
			$timestamp = ( $settings['timestamp'] == 'duplicate' ) ? strtotime($duplicate['post_date']) : current_time('timestamp',0);
			$timestamp_gmt = ( $settings['timestamp'] == 'duplicate' ) ? strtotime($duplicate['post_date_gmt']) : current_time('timestamp',1);
			
			if( $settings['time_offset'] ) {
				$offset = intval($settings['time_offset_seconds']+$settings['time_offset_minutes']*60+$settings['time_offset_hours']*3600+$settings['time_offset_days']*86400);
				if( $settings['time_offset_direction'] == 'newer' ) {
					$timestamp = intval($timestamp+$offset);
					$timestamp_gmt = intval($timestamp_gmt+$offset);
				} else {
					$timestamp = intval($timestamp-$offset);
					$timestamp_gmt = intval($timestamp_gmt-$offset);
				}
			}
			$duplicate['post_date'] = date('Y-m-d H:i:s', $timestamp);
			$duplicate['post_date_gmt'] = date('Y-m-d H:i:s', $timestamp_gmt);
			$duplicate['post_modified'] = date('Y-m-d H:i:s', current_time('timestamp',0));
			$duplicate['post_modified_gmt'] = date('Y-m-d H:i:s', current_time('timestamp',1));

			// Remove some of the keys
			unset( $duplicate['ID'] );
			unset( $duplicate['guid'] );
			unset( $duplicate['comment_count'] );

			// Insert the post into the database
			$duplicate_id = wp_insert_post( $duplicate );
			
			// Duplicate all the taxonomies/terms
			$taxonomies = get_object_taxonomies( $duplicate['post_type'] );
			foreach( $taxonomies as $taxonomy ) {
				$terms = wp_get_post_terms( $original_id, $taxonomy, array('fields' => 'names') );
				wp_set_object_terms( $duplicate_id, $terms, $taxonomy );
			}
		  
			// Duplicate all the custom fields
			$custom_fields = get_post_custom( $original_id );

			$postmeta_data = array();

			foreach ( $custom_fields as $key => $value ) {
			  if( is_array($value) && count($value) > 0 ) { //TODO: optimize
					foreach( $value as $i=>$v ) {
						$postmeta_data[] = '('.$duplicate_id.',\''.$key.'\',\''.$v.'\')'; 
					}
				}
			}

			if( !empty($postmeta_data) ) {

				$q = "INSERT INTO {$wpdb->prefix}postmeta(post_id, meta_key, meta_value) VALUES ". implode(",", $postmeta_data);
				$query = $wpdb->query("INSERT INTO {$wpdb->prefix}postmeta(post_id, meta_key, meta_value) VALUES ". implode(",", $postmeta_data));
			}

			do_action( 'sm_beta_post_process_duplicate_records', array( 'original_id' => $original_id, 'duplicate_id' => $duplicate_id, 'settings' => $settings, 'duplicate' => $duplicate ) );
			if( is_wp_error($duplicate_id) ) {
				if ( is_callable( array( 'Smart_Manager', 'log' ) ) ) {
					Smart_Manager::log( 'error', _x( 'Duplicate process failed', 'duplicate process', 'smart-manager-for-wp-e-commerce' ) );
				}
				return false;
			} else {
				return true;
			}

		}

		/**
		 * Function to handle deletion via background process
		 */
		public function delete_all() {
			$get_selected_ids_and_entire_store_flag = $this->get_selected_ids_and_entire_store_flag();
			$selected_ids = ( ! empty( $get_selected_ids_and_entire_store_flag['selected_ids'] ) ) ? $get_selected_ids_and_entire_store_flag['selected_ids'] : array();
			$is_entire_store = ( ! empty( $get_selected_ids_and_entire_store_flag['entire_store'] ) ) ? $get_selected_ids_and_entire_store_flag['entire_store'] : false;
			$process_name = _x( 'Move to trash', 'process name', 'smart-manager-for-wp-e-commerce' );
			$process_key = 'move_to_trash';
			$callback_func = 'sm_process_move_to_trash_records';
			if ( ! empty( $this->req_params['deletePermanently'] ) ) {
				$process_name = _x( 'Delete All Records', 'process name', 'smart-manager-for-wp-e-commerce' );
				$process_key = 'delete_all_records';
				$callback_func = 'sm_delete_records_permanently';
			}
			$default_delete_process = apply_filters( 'sm_pro_default_process_delete_records', true );
			if ( empty( $default_delete_process ) ) {
				$process_name = _x( 'Delete '. $this->dashboard_title . ' records', 'process name', 'smart-manager-for-wp-e-commerce' );
				$process_key = 'delete_non_post_type_records';
			}
			$callback_func = ( ! empty( $default_delete_process ) ) ? $callback_func : 'sm_process_delete_non_posts_records';
			self::send_to_background_process( array( 'process_name' => $process_name,
													'process_key' => $process_key,
														'callback' => array( 'class_path' => $this->req_params['class_path'],
																			'func' => array( $this->req_params['class_nm'], $callback_func ) ),
														'callback_params' => array ( 'delete_permanently' => $this->req_params['deletePermanently'] ),
														'selected_ids' => $selected_ids,
														'entire_task' => $this->entire_task,
														'storewide_option' => $this->req_params['storewide_option'],'active_module' => $this->req_params['active_module'],
														'entire_store' => $is_entire_store,
														'dashboard_key' => $this->dashboard_key,
														'dashboard_title' => $this->dashboard_title,
														'class_path' => $this->req_params['class_path'],
														'class_nm' => $this->req_params['class_nm'],
														'backgroundProcessRunningMessage' => $this->req_params['backgroundProcessRunningMessage'],
														'SM_IS_WOO30' => $this->req_params['SM_IS_WOO30'],
														'default_delete_process' => $default_delete_process
													)
											);
		}

		/**
		 * Function to handle move to trash functionality
		 *
		 * @param  array $params Required params array.
		 * @return WP_Post|false|null Post data on success, false or null on failure.
		 */
		public static function sm_process_move_to_trash_records( $args = array() ) {
			if ( empty( $args['selected_ids'] ) || ( ! is_array( $args['selected_ids'] ) ) ) {
				return;
			}
			global $wpdb;
			$force_delete = false; // Setting this to false since `sm_process_move_to_trash_records()` trash the records.
			// Sanitize and prepare the selected post IDs
			$selected_post_ids = array_map( 'intval', $args['selected_ids'] );
			// Prepare a placeholder string for the post IDs
			$post_id_placeholders = implode( ',', array_fill( 0, count( $selected_post_ids ), '%d' ) );
			// Delete posts if trash is disabled.
			if ( ! EMPTY_TRASH_DAYS ) {
				return self::sm_delete_records_permanently( $args );
			}
			// Fetch posts and check status.
			$post_results = $wpdb->get_results(
			   	$wpdb->prepare(
					"SELECT ID, post_status FROM {$wpdb->prefix}posts WHERE ID IN ( $post_id_placeholders )", $selected_post_ids
				),
				'ARRAY_A'
			);
			if ( ( empty( $post_results ) || ( is_wp_error( $post_results ) ) ) && is_callable( array( 'Smart_Manager', 'log' ) ) || ( ! is_array( $post_results ) ) ) {
				Smart_Manager::log( 'error', _x( 'Move to trash failed', 'move to trash process', 'smart-manager-for-wp-e-commerce' ) );
				return false;
			}
			if ( class_exists( 'WooCommerce' ) && class_exists( 'WC_Post_Data' ) ) {
				remove_action( 'wp_trash_post', array( 'WC_Post_Data', 'trash_post' ) );
			}
			// Loop through results to build lists of IDs
			$ids_to_trash = array();
			$previous_statuses = array();
			foreach ( $post_results as $post_result ) {
				if ( 'trash' === $post_result['post_status'] ) {
					continue;
				}
				$ids_to_trash[] = $post_result['ID'];
				$previous_statuses[$post_result['ID']] = $post_result['post_status'];
				// Filters whether a post trashing should take place.
				$check = apply_filters( 'pre_trash_post', null, $post_result, $post_result['post_status'] );
				if ( null !== $check ) {
					return $check;
				}
				do_action( 'wp_trash_post', $post_result['ID'], $post_result['post_status'] );
			}
			if ( ( ! isset( $args['move_to_trash_pre_action'] ) ) && empty( $args['move_to_trash_pre_action'] ) ) { // Shouldn't trigger below in case of call to this function from products_pre_process_move_to_trash_records() for varitions.
				do_action( 'sm_pro_pre_process_move_to_trash_records', array( 
					'selected_post_ids' => $selected_post_ids,
					'post_id_placeholders' => $post_id_placeholders
				) );
			}
			if ( empty( $ids_to_trash ) || ( ! is_array( $ids_to_trash ) ) || ( empty( $previous_statuses ) || ( ! is_array( $previous_statuses ) ) ) ) {
				return false;
			}
			$ids_to_trash_placeholder = implode( ', ', array_fill( 0, count( $ids_to_trash ), '%d' ) );
			// Insert metadata for previous status and trash time.
			$values = array();
			foreach ( $ids_to_trash as $id ) {
				if ( empty( $previous_statuses[ $id ] ) ) {
					continue;
				}
				$previous_status = $previous_statuses[ $id ];
				$values[] = $wpdb->prepare("(%d, '_wp_trash_meta_status', %s), (%d, '_wp_trash_meta_time', %d)", $id, $previous_status, $id, time());
			}
			if ( ! empty( $values ) && ( is_array( $values ) ) ) {
				$wpdb->query(
					"INSERT INTO {$wpdb->prefix}postmeta (post_id, meta_key, meta_value) VALUES " . implode( ', ', $values )
				);
			}
			// Update status to "trash".
			$wpdb->query(
			   	$wpdb->prepare( 
					"UPDATE {$wpdb->prefix}posts SET post_status = 'trash' WHERE ID IN ( $ids_to_trash_placeholder )", $ids_to_trash
				)
			);
			// Delete comments related to these posts.
			$wpdb->query(
			   	$wpdb->prepare(
					"DELETE FROM {$wpdb->prefix}comments WHERE comment_post_ID IN ( $post_id_placeholders )", $selected_post_ids
				)
			);
			foreach ( $post_results as $post_result ) {
				if ( 'trash' === $post_result['post_status'] ) {
					continue;
				}
				do_action( 'trashed_post', $post_result['ID'], $post_result['post_status'] );
			}
			return true;
		}

		/**
		 * Function to get template base directory for Smart Manager templates
		 *
		 * @param  string $template_name Template name.
		 * @return string $template_base_dir Base directory for Smart Manager templates.
		 */
		public function get_template_base_dir( $template_name = '' ) {

			$template_base_dir = '';
			$sm_dir_name = SM_PLUGIN_DIR . '/';
			$sm_base_dir    = 'woocommerce/' . $sm_dir_name;

			// First locate the template in woocommerce/smart-manager-for-wp-e-commerce folder of active theme.
			$template = locate_template(
				array(
					$sm_base_dir . $template_name,
				)
			);

			if ( ! empty( $template ) ) {
				$template_base_dir = $sm_base_dir;
			} else {
				// If not found then locate the template in smart-manager-for-wp-e-commerce folder of active theme.
				$template = locate_template(
					array(
						$sm_dir_name . $template_name,
					)
				);
				if ( ! empty( $template ) ) {
					$template_base_dir = $sm_dir_name;
				}
			}

			$template_base_dir = apply_filters( 'sm_template_base_dir', $template_base_dir, $template_name );

			return $template_base_dir;
		}

		/**
		 * Function to get modify the search cond for `any of/not any of` search operators
		 *
		 * @param  string $cond Search condition.
		 * @param  array $search_params Advanced search params.
		 * 
		 * @return string $cond Updated search condition.
		 */
		public function modify_search_cond( $cond = '', $search_params = array() ) {
		
			$operator = ( ! empty( $search_params['selected_search_operator'] ) ) ? $search_params['selected_search_operator'] : '';
			
			if( empty( $operator ) ){
				return $cond;
			}

			$val = ( ! empty( $search_params['search_value'] ) ) ? $search_params['search_value'] : '';
			$col = ( ! empty( $search_params['search_col'] ) ) ? $search_params['search_col'] : '';

			if( ! in_array( $operator, array( 'anyOf', 'notAnyOf' ) ) || empty( $val ) || empty( $col ) ){
				return $cond;
			}

			$val = explode( "|", $val );

			if( ! is_array( $val ) ){
				return $cond;
			}

			$addln_cond = '';
			if( ! empty( $search_params['is_meta_table'] ) ){
				$col = ( ! empty( $search_params['skip_placeholders'] ) ) ? "'". trim( $col ) . "'": ("'%". trim( $col ) . "%'");
				$addln_cond = $search_params['table_nm'] . ".meta_key LIKE " . $col . " AND ";
				$col = 'meta_value';
			}
			$col = $search_params['table_nm'] . "." . $col;
			$cond = array_reduce( $val, function( $carry, $item ) use( $col, $operator, $addln_cond, $search_params ) {
				$condition = " ( " . $addln_cond . " " . $col . " " . 
							( ( 'notAnyOf' === $operator ) ? 'NOT ' : '' ) . 
							"LIKE" . 
							( ! empty( $search_params['skip_placeholders'] ) ? 
								( " '%" . trim( $item ) . "%'" ) : 
								" %s" ) . 
							" ) ";
				$condition .= ( 'notAnyOf' === $operator ) ? 'AND' : 'OR';
				return $carry . $condition;
			
			}, '' );
			return ( 'notAnyOf' === $operator ) ? ( ( " AND" === substr( $cond, -4 ) ) ? "( " . substr( $cond, 0, -4 ) . " )" : $cond ) : ( ( " OR" === substr( $cond, -3 ) ) ? "( " . substr( $cond, 0, -3 ) . " )" : $cond );
		}

		/**
		 * Function to get format the search value for `starts with/ends with` search operators
		 *
		 * @param  string $search_value Searched value.
		 * @param  array $search_params Advanced search params.
		 * 
		 * @return string $search_value Formatted searched value.
		 */
		public function format_search_value( $search_value = '', $search_params = array() ) {

			$operator = ( ! empty( $search_params['selected_search_operator'] ) ) ? $search_params['selected_search_operator'] : '';

			if( empty( $operator ) ){
				return $search_value;
			}

			switch( true ) {
				case( in_array( $operator, array( 'startsWith', 'notStartsWith' ) ) ):
					return $search_value. '%';
				case( in_array( $operator, array( 'endsWith', 'notEndsWith' ) ) ):
					return '%'. $search_value;
				default:
					return $search_value;
			}
		}

		/**
		 * Function to fetch column data type
		 *
		 * @param  string $dashboard_key current dashboard name.
		 * @return string $col_data_type column data type
		 */
		public static function get_column_data_type( $dashboard_key = '' ) {
			if ( empty( $dashboard_key ) ) {
				return;
			}
			$current_store_model = get_transient( 'sa_sm_' . $dashboard_key );
			if ( empty( $current_store_model ) && is_array( $current_store_model ) ) {
				return;
			}
			$current_store_model = json_decode( $current_store_model, true );
			$col_model = ( ! empty( $current_store_model['columns'] ) ) ? $current_store_model['columns'] : array();
			if ( empty( $col_model ) ) {
				return;
			}
			$col_data_type = array();
			$date_type_cols = array( 'sm.date', 'sm.datetime', 'sm.time', 'timestamp' );
			//Code for storing the timestamp cols
			foreach ( $col_model as $col ) {
				if ( empty( $col['type'] ) ) {
					continue;
				}
				$col_data_type[ $col['src'] ] = ( ( in_array( $col['type'], $date_type_cols, true ) ) && ( ! empty( $col['date_type'] ) && ( 'timestamp' === $col['date_type'] ) ) ) ? 'timestamp' : $col['type'];
			} 
			return $col_data_type;	
		}

		/**
		 * Function update the edited column titles for the specific dashboard
		 *
		 * @param  array $args request params array.
		 * @return void
		 */
		public static function update_column_titles( $args = array() ){
			( ! empty( $args['edited_column_titles'] ) && ! empty( $args['state_option_name'] ) ) ? update_option( $args['state_option_name'] .'_columns', array_merge( get_option( $args['state_option_name'] .'_columns', array() ), $args['edited_column_titles'] ), 'no' ) : '';
		}

		/**
		 * Function to batch update terms table related data
		 *
		 * @param  array $args request params array.
		 * @return boolean $update result of the function call.
		 */
		public static function batch_update_terms_table_data( $args = array(), $is_post_terms = false ) {
			if ( empty( $args ) || ( ! is_array( $args ) ) || empty( $args['operator'] ) || empty( $args['id'] ) || empty( $args['col_nm'] ) ) {
				return false;
			}
			$value = ( is_array( $args['value'] ) && ! empty( $args['value'][0] ) ) ? intval( $args['value'][0] ) : intval( $args['value'] );
			if ( ( ! empty( $args['copy_from_operators'] )  && is_array( $args['copy_from_operators'] ) ) && in_array( $args['operator'], $args['copy_from_operators'] ) ) {
				$value = $args['value'];
			}
			if ( $is_post_terms ) {
				return array(
					'value'  => $value,
					'operator' => $args[ 'operator' ]
				);
			}
			if ( 'remove_from' === $args['operator'] ) {
				return wp_remove_object_terms( $args['id'], $value, $args['col_nm'] );
			} else {
				$append = ( 'add_to' === $args['operator'] ) ? true : false;
				return wp_set_object_terms( $args['id'], $value, $args['col_nm'], $append );
			}
		}

		/**
		 * Before deleting a post, do some cleanup like removing attached media.
		 *
		 * @param int $order_id Order ID.
		 * @param WP_Post $post Post data.
		 */
		public function delete_attached_media( $post_id = 0, $post = null ) {
			if ( empty( intval( $post_id ) ) ) {
				return;
			}
			global $wpdb;
			$attachments = get_children( array(
				'post_parent' => $post_id,
				'post_type'   => 'attachment', 
				'numberposts' => -1,
				'post_status' => 'any' 
		  	) );
			if ( empty( $attachments ) || ! is_array( $attachments ) ) {
				return;
			}
			$attached_media_post_ids = array();
			$post_ids = array();
			foreach ( $attachments as $attachment ) {
				$attachment_id = $attachment->ID;
				if ( empty( intval( $attachment_id ) ) ) {
					continue;
				}
				$attached_media_post_ids = $wpdb->get_col(
											$wpdb->prepare( "SELECT DISTINCT post_id 
											FROM {$wpdb->prefix}postmeta WHERE post_id <> %d AND meta_key = %s AND meta_value = %s", $post_id, '_thumbnail_id', $attachment_id )
										); 
				$attached_media_post_ids = apply_filters( 'sm_delete_attachment_get_matching_gallery_images_post_ids', $attached_media_post_ids, array(
					'post_id' => $post_id,
					'attachment_id' => $attachment_id
				) );
				$post_ids = $wpdb->get_col(
									$wpdb->prepare( "SELECT DISTINCT ID 
									FROM {$wpdb->prefix}posts WHERE ID <> %d AND post_content LIKE '%wp-image-" . $attachment_id . "%' OR post_excerpt LIKE '%wp-image-" . $attachment_id . "%' OR post_content LIKE '%wp:image {\"id\":$attachment_id%' OR post_excerpt LIKE '%wp:image {\"id\":$attachment_id%'", $post_id )
									);
			}
			if ( empty( ( is_array( $attached_media_post_ids ) && is_array( $post_ids ) ) && array_merge( $attached_media_post_ids, $post_ids ) ) ) {
				wp_delete_attachment( $attachment_id, true );
				wp_delete_post( $attachment_id, true );
			}
		}

		/**
		 * Get selected ids.
		 *
		 * @param WP_Post $post Post data.
		 */
		public function get_selected_ids_and_entire_store_flag() {
			$selected_ids =  ( ! empty( $this->req_params['selected_ids'] ) ) ? json_decode( stripslashes( $this->req_params['selected_ids'] ), true ) : array();
			$entire_store = false;
			if ( ( false === $this->entire_task ) && ( ! empty( $this->req_params['storewide_option'] ) ) && ( 'entire_store' === $this->req_params['storewide_option'] ) && ( ! empty( $this->req_params['active_module'] ) ) ) {
				$selected_ids = $this->get_entire_store_ids();
				$entire_store = true;
			}
			return array(
				'selected_ids' => $selected_ids,
				'entire_store' => $entire_store
			);
		}

		/**
		 * Deletes specified posts from the database.
		 *
		 * @param array $args An array of required params.
		 * @return array $deleted_posts deleted post ids on successful deletion, false on failure.
		 */
		public static function sm_delete_records_permanently( $args = array() ) {
			if ( empty( $args['selected_ids'] ) ) {
				return;
			}
			global $wpdb;
			$deleted_posts = array();
			$force_delete = true; // Setting this to true since `sm_delete_records()` deletes the records permanently.
			// Sanitize and prepare the selected post IDs
			$selected_post_ids = array_map( 'intval', $args['selected_ids'] );
			$num_ids = count( $selected_post_ids );
			if ( 0 === $num_ids ) {
				return; // No valid post IDs
			}
			// Prepare a placeholder string for the post IDs
			$post_id_placeholders = implode( ',', array_fill( 0, $num_ids, '%d' ) );
			$args = array( 
				'selected_post_ids' => $selected_post_ids,
				'post_id_placeholders' => $post_id_placeholders
			);
			if ( class_exists( 'WooCommerce' ) ) {
				remove_action( 'delete_post', array( 'WC_Post_Data', 'delete_post' ) );
			}
			remove_action( 'delete_post', '_wp_delete_post_menu_item' );
			$posts = self::get_post_obj_from_ids( $selected_post_ids );
			if ( empty( $posts ) || ( ! is_array( $posts ) ) ) {
				return;
			}
			// Pre-deletion actions
			foreach ( $posts as $post ) {
				// Filters whether a post deletion should take place.
				$check = apply_filters( 'pre_delete_post', null, $post, $force_delete );
				if ( null !== $check ) {
					return $check;
				}
				// Actions before deletion
				do_action( 'before_delete_post', $post->ID, $post );
				do_action( "delete_post_{$post->post_type}", $post->ID, $post );
				do_action( 'delete_post', $post->ID, $post );
			}
			// Handling a menu item when its original object is deleted.
			self::sm_delete_post_menu_item( $args );
 			do_action( 'sm_pro_pre_process_delete_records', $args );
			// Delete misc postmeta
			$wpdb->query(
			   $wpdb->prepare(
				   "DELETE FROM {$wpdb->prefix}postmeta WHERE post_id IN ( $post_id_placeholders ) AND meta_key IN (%s, %s)",
				   array_merge( $selected_post_ids, array('_wp_trash_meta_status', '_wp_trash_meta_time') )
			   )
			);
			// Delete term relationships for the specified post IDs.
			$wpdb->query(
				$wpdb->prepare(
					"DELETE FROM {$wpdb->prefix}term_relationships
					WHERE object_id IN ( $post_id_placeholders )",
					$selected_post_ids
				)
			);
			// Delete childrens.
			$wpdb->query(
				$wpdb->prepare(
					"DELETE FROM {$wpdb->prefix}posts WHERE post_parent IN ( $post_id_placeholders ) AND post_type <> %s",
					array_merge( $selected_post_ids, array('attachment') )
				)
			);
			wp_defer_comment_counting( true );
			// Get all comment IDs for the given post IDs
			$comment_ids = $wpdb->get_col(
				$wpdb->prepare(
					"SELECT comment_ID FROM {$wpdb->prefix}comments WHERE comment_post_ID IN ( $post_id_placeholders )",
					$selected_post_ids
				)
			);
			// Check if there are comment IDs to delete
			if ( ! empty( $comment_ids ) ) {
				// Prepare a string of comma-separated comment IDs for the delete query.
				$comment_ids_placeholder = implode( ',', array_fill( 0, count( $comment_ids ), '%d' ) );	
				// Delete comments from the comments table
				$wpdb->query(
					$wpdb->prepare(
						"DELETE FROM {$wpdb->prefix}comments WHERE comment_ID IN ( $comment_ids_placeholder )",
						$comment_ids
					)
				);
				// Optionally, delete comment meta if needed.
				$wpdb->query(
					$wpdb->prepare(
						"DELETE FROM {$wpdb->prefix}commentmeta WHERE comment_id IN ( $comment_ids_placeholder )",
						$comment_ids
					)
				);
			}
			wp_defer_comment_counting( false );
			// Delete postmeta.
			$wpdb->query(
				$wpdb->prepare( "DELETE FROM {$wpdb->prefix}postmeta WHERE post_id IN ( $post_id_placeholders )", $selected_post_ids )
			);
			// Delete the posts
			$wpdb->query(
				$wpdb->prepare( "DELETE FROM {$wpdb->prefix}posts WHERE ID IN ( $post_id_placeholders )", $selected_post_ids )
			);
			// Final deletion actions
			foreach ( $posts as $post ) {
				do_action( "deleted_post_{$post->post_type}", $post->ID, $post );
				do_action( 'deleted_post', $post->ID, $post );
				// Clean post cache
				clean_post_cache( $post );
				// Handle children cache if the post is hierarchical
				if ( is_post_type_hierarchical( $post->post_type ) ) {
					$children = get_children( array( 'post_parent' => $post->ID ) );
					foreach ( $children as $child ) {
						clean_post_cache( $child );
					}
				}
				wp_clear_scheduled_hook( 'publish_future_post', array( $post->ID ) );
				do_action( 'after_delete_post', $post->ID, $post );
				// Collect deleted post ID
				$deleted_posts[] = $post->ID;
			}
			if ( ( empty( $deleted_posts ) || ( is_wp_error( $deleted_posts ) ) ) && is_callable( array( 'Smart_Manager', 'log' ) ) ) {
				Smart_Manager::log( 'error', _x( 'Delete records permanently failed', 'delete permanently', 'smart-manager-for-wp-e-commerce' ) );
				return false;
			}
			return true;
		}

		/**
		 * Function for handling a menu item when its original object is deleted.
		 *
		 * @param array $args Array of selected post IDs and placeholders.
		 */
		public static function sm_delete_post_menu_item( $args = array() ) {
			if ( empty( $args ) || ( ! is_array( $args ) ) || empty( $args['selected_post_ids'] ) || empty( $args['post_id_placeholders'] ) ) {
				return;
			}
			$menu_item_ids = self::sm_get_associated_nav_menu_items( $args, 'post_type' );
			if ( empty( $menu_item_ids ) || ( ! is_array( $menu_item_ids ) ) ) {
				return;
			}
			self::sm_delete_records_permanently( array(
				'selected_ids' => $menu_item_ids
				)
			);
		}

		/**
		 * Returns the menu items associated with a particular object.
		 *
		 *
		 * @param array    $args   Array of required params.
		 * @param string $object_type Optional. The type of object, such as 'post_type' or 'taxonomy'.
		 *                            Default 'post_type'.
		 * @param string $taxonomy    Optional. If $object_type is 'taxonomy', $taxonomy is the name
		 *                            of the tax that $object_id belongs to. Default empty.
		 * @return int[] The array of menu item IDs; empty array if none.
		 */
		public static function sm_get_associated_nav_menu_items( $args = array(), $object_type = 'post_type', $taxonomy = '' ) {
			if ( empty( $args ) || ( ! is_array( $args ) ) || empty( $args['selected_post_ids'] ) || empty( $args['post_id_placeholders'] ) ) {
				return;
			}
			global $wpdb;
			$metu_items_query = $wpdb->prepare("
				SELECT DISTINCT p.ID,
					pm.meta_key as meta_key,
					(CASE 
						WHEN pm.meta_key = '_menu_item_object' THEN pm.meta_value 
						WHEN pm.meta_key = '_menu_item_object_id' THEN pm.meta_value
					END) as meta_value
				FROM {$wpdb->prefix}postmeta as pm
				JOIN {$wpdb->prefix}posts p 
					ON (p.ID = pm.post_id
						AND p.post_type = 'nav_menu_item'
						AND pm.meta_key IN ('_menu_item_object', '_menu_item_object_id'))
				WHERE pm.post_id IN (
					SELECT post_id
					FROM {$wpdb->prefix}postmeta
					WHERE meta_key = '_menu_item_object_id'
						AND meta_value IN (" . $args['post_id_placeholders'] . ")
				)
			", $args['selected_post_ids'] );
			
			// Additional conditions based on object type
			if ( 'post_type' === $object_type ) {
				$metu_items_query .= " AND EXISTS (
					SELECT 1
					FROM {$wpdb->prefix}postmeta pm2
					WHERE pm2.post_id = p.ID
					AND pm2.meta_key = '_menu_item_type'
					AND pm2.meta_value = 'post_type'
				)";
			} elseif ( 'taxonomy' === $object_type ) {
				$metu_items_query .= $wpdb->prepare("
					AND EXISTS (
						SELECT 1
						FROM {$wpdb->prefix}postmeta pm2
						WHERE pm2.post_id = p.ID
						AND pm2.meta_key = '_menu_item_type'
						AND pm2.meta_value = 'taxonomy'
						AND (
							SELECT pm3.meta_value
							FROM {$wpdb->prefix}postmeta pm3
							WHERE pm3.post_id = p.ID
							AND pm3.meta_key = '_menu_item_object'
						) = %s
					)
				", $taxonomy );
			}
			$results = $wpdb->get_col( $metu_items_query );
			if ( is_wp_error( $results ) || ( empty( $results ) ) || ( ! is_array( $results ) ) ) {
				return;
			}
			// Remove duplicate IDs and return unique values
			return array_unique( $results );
		}

		/**
		 * Function to handle delete of a single record
		 *
		 * @param  integer $deleting_id The ID of the record to be deleted.
		 * @return boolean
		 */
		public static function sm_process_delete_non_posts_records( $params = array() ) {
			$deleting_id = ( ! empty( $params['id'] ) ) ? $params['id'] : 0;
			do_action( 'sm_pro_pre_process_delete_non_posts_records', array( 'deleting_id' => $deleting_id, 'source' => __CLASS__ ) );
			if ( empty( $deleting_id ) ) {
				return false;
			}
			$force_delete = ( ! empty( $params['delete_permanently'] ) ) ? true : false;
			$result = false;
			$params[ $force_delete ] = $force_delete;
			$result = apply_filters( 'sm_pro_default_process_delete_records_result', $result, $deleting_id, $params );
			do_action( 'sm_pro_post_process_delete_non_posts_records', array( 'deleting_id' => $deleting_id, 'source' => __CLASS__ ) );
			if ( empty( $result ) ) {
				if ( is_callable( array( 'Smart_Manager', 'log' ) ) ) {
					Smart_Manager::log( 'error', _x( 'Delete process failed', 'delete process', 'smart-manager-for-wp-e-commerce' ) );
				}
				return false;
			}
			return true;
		}

		/**
		 * Retrieves an array of WP_Post objects based on post IDs.
		 *
		 * @param array $post_ids Array of post IDs to retrieve.
		 * @return array|null Array of WP_Post objects or void if input is invalid.
		*/
		public static function get_post_obj_from_ids( $post_ids = array() )
		{
			if ( empty( $post_ids ) || ( ! is_array( $post_ids ) ) ) {
				return;
			}
			$num_ids = count( $post_ids );
			if ( empty( $num_ids ) ) {
				return;
			}
			$post_ids              = array_map( 'intval', $post_ids ); // Sanitize ids.
			$post_ids_placeholders = implode( ',', array_fill( 0, $num_ids, '%d' ) );
			global $wpdb;
			$results = $wpdb->get_results(// phpcs:ignore
				$wpdb->prepare(
					"SELECT * FROM {$wpdb->prefix}posts WHERE ID IN ( $post_ids_placeholders )", $post_ids
				)
			); // phpcs:ignore
			if ( is_wp_error( $results ) || empty( $results ) || ( ! is_array( $results ) ) ) {
				return;
			}
			return array_map( function ( $result ) {
				return new WP_Post( $result );
			}, $results);
		}

		/**
		 * Merges new data into existing post data, with new fields overwriting old ones.
		 *
		 * @param WP_Post|null $post The post object to update.
		 * @param array        $args New post data fields to merge.
		 * @return void
		*/
		public static function process_post_update_data( $post = null, $args = array() )
		{
			if ( empty( $post ) || empty( $args ) || ( ! is_array( $args ) ) ) {
				return;
			}
			// First, get all of the original fields.
			$current_post_data = get_post( $post->ID, ARRAY_A );
			// Escape data pulled from DB.
			$current_post_data = wp_slash( $current_post_data );
			// Passed post category list overwrites existing category list if not empty.
			$post_cats = ( ! empty( $args['post_category'] ) && is_array( $args['post_category'] ) && count( $args['post_category'] ) > 0 ) ? $args['post_category'] : $current_post_data['post_category'];
			// Drafts shouldn't be assigned a date unless explicitly done so by the user.
			$clear_date = ( ! empty( $current_post_data['post_status'] ) && in_array( $current_post_data['post_status'], array( 'draft', 'pending', 'auto-draft' ), true ) && empty( $args['edit_date'] ) && ( '0000-00-00 00:00:00' === $current_post_data['post_date_gmt'] ) ) ? true : false;
			// Merge old and new fields with new fields overwriting old ones.
			$args                  = ( ( ! empty( $current_post_data ) ) && ( is_array( $current_post_data ) ) ) ? array_merge( $current_post_data, $args ) : $args;
			$args['post_category'] = $post_cats;
			if ( $clear_date ) {
				$args['post_date']     = current_time( 'mysql' );
				$args['post_date_gmt'] = '';
			}
			return $args;
		}

		/**
		 * Check whether to skip post data update based on specific parameters.
		 *
		 * @param array $update_params Parameters provided for the update, expected to include 'tax_input'.
		 * @param array $post_data Data of the post being updated, including post type.
		 * 
		 * @return bool|void Returns true to skip update, false to proceed, or void if input is invalid.
		*/
		public static function is_skip_post_data_update( $update_params = array(), $post_data = array() ) {
			if ( ( empty( $update_params ) ) || ( empty( $post_data ) ) || ( ! is_array( $post_data ) ) || ( empty( $post_data['post_type'] ) ) || ( ! is_array( $update_params ) ) ) {
				return;
			}
			$skip_update = false;
			if ( ( count( $update_params ) !== 1 ) || ( ! array_key_exists('tax_input', $update_params ) ) ) {
				return $skip_update;
			}
			if ( ( ! is_array( $update_params['tax_input'] ) ) || ( empty( $update_params['tax_input'] ) ) ) {
				return $skip_update;
			}
			foreach ( array_keys( $update_params['tax_input'] ) as $taxonomy ) {
				if ( ( taxonomy_exists( $taxonomy ) ) && ( is_object_in_taxonomy( $post_data['post_type'], $taxonomy ) ) ) {
					return false;
				}
				$skip_update = true;
			}
			return $skip_update;
		}

		/**
		 * Updates multiple posts with provided data.
		 *
		 * @param array $request_params Array of post data and fields to update where each key in array is a post ID and the value is an associative array of post fields to update.
		 * @param bool  $wp_error Optional. Whether to return WP_Error on failure. Default false.
		 * @param bool  $fire_after_hooks Optional. Whether to fire the after insert hooks.
		 * @return array|false Array of updated post IDs or false if an error occurs in update.
		 */
		public static function update_posts( $request_params = array(), $wp_error = false, $fire_after_hooks = true ) {
			if ( ( ! is_array( $request_params ) ) || empty( $request_params ) || empty( $request_params['posts_data'] ) || ( ! is_array( $request_params['posts_data'] ) ) ) {
				return;
			}
			$posts_parms_arr = $request_params['posts_data'];
			global $wpdb;
			$post_ids                  = array_map( 'intval', array_keys( $request_params['posts_data'] ) ); // Sanitize ids.
			if ( empty( $post_ids ) ) {
				return;
			}
			$posts                     = self::get_post_obj_from_ids( $post_ids );
			if ( empty( $posts ) || ( ! is_array( $posts ) ) ) {
				return;
			}
			$posts_data_to_update      = array();
			$posts_meta_data_to_update = array();
			$posts_meta_keys           = array();
			$posts_before              = array();
			$posts_data_for_after_hook = array(); // data maybe modified by filter wp_insert_post_data.
			$posts_args_for_after_hook = array(); // unmodified data.
			$update                    = true;
			$posts_update_result       = true;
			$postmeta_update_result    = array();
			$terms_update_result       = array();
			$updated_posts_obj         = array();
			$taxonomies		   		   = ( ! empty( $request_params['taxonomies'] ) ) ? $request_params['taxonomies'] : array();
			$taxonomy_data_to_update   = array();
			$taxonomies_update_result  = true;
			$all_term_ids              = array();
			// compat for 'Germanized for WooCommerce Pro' plugin.
			$taxonomy_terms = apply_filters( 
				'sm_pro_get_taxonomy_terms',
				$taxonomies
			);
			// code for mapping posts update data.
			foreach ( $posts as $post ) {
				if ( empty( $post->ID ) ) {
					continue;
				}
				$post_parms = $posts_parms_arr[ $post->ID ]; //original post parms array, may have structure different from WP post parms array
				if ( is_object( $post_parms ) ) {
					// Non-escaped post was passed.
					$post_parms = get_object_vars( $post_parms ); // convert an object into an associative array.
					$post_parms = wp_slash( $post_parms );
				}
				$postarr = self::process_post_update_data( $post, $post_parms );
				if ( ( true === self::is_skip_post_data_update( $post_parms, $postarr ) ) ) {
					continue;
				}
				//map $postarr['tax_input'] just like wp does.
				if ( ! empty( $postarr['tax_input'] ) && is_array( $postarr['tax_input'] ) ) {
					$postarr['tax_input'] = array_map(
						function( $terms ) {
							return array_column(
								array_filter(
									$terms,
									function( $term ) {
										return ( ( ! empty( $term['operator'] ) ) && ( 'remove_from' !== $term['operator'] ) );
									}
								),
								'value'
							);
						},
						$postarr['tax_input']
					);
				}
				if ( empty( $postarr ) ) {
					continue;
				}
				// Capture original pre-sanitized array for passing into filters.
				$unsanitized_postarr = $postarr;
				$user_id = get_current_user_id();
				$defaults = array(
					'post_author'           => $user_id,
					'post_content'          => '',
					'post_content_filtered' => '',
					'post_title'            => '',
					'post_excerpt'          => '',
					'post_status'           => 'draft',
					'post_type'             => 'post',
					'comment_status'        => '',
					'ping_status'           => '',
					'post_password'         => '',
					'to_ping'               => '',
					'pinged'                => '',
					'post_parent'           => 0,
					'menu_order'            => 0,
					'guid'                  => '',
					'import_id'             => 0,
					'context'               => '',
					'post_date'             => '',
					'post_date_gmt'         => '',
				);
				$postarr = wp_parse_args( $postarr, $defaults );
				unset( $postarr['filter'] );
				$postarr = sanitize_post( $postarr, 'db' );
				$guid    = $postarr['guid'];
				// Get the post ID and GUID.
				$post_id                  = $post->ID;
				$post_before              = $post;
				$posts_before[ $post_id ] = $post_before; // posts before array with key as id and value as post obj.
				if ( is_null( $post_before ) ) {
					continue;
				}
				$guid            = $post->guid;
				$previous_status = $post->post_status;
				$post_type    = empty( $postarr['post_type'] ) ? 'post' : $postarr['post_type'];
				$post_title   = $postarr['post_title'];
				$post_content = $postarr['post_content'];
				$post_excerpt = $postarr['post_excerpt'];
				$post_name = ( ! empty( $postarr['post_name'] ) ) ? $postarr['post_name'] : $post_before->post_name;
				$maybe_empty = 'attachment' !== $post_type
				&& ! $post_content && ! $post_title && ! $post_excerpt
				&& post_type_supports( $post_type, 'editor' )
				&& post_type_supports( $post_type, 'title' )
				&& post_type_supports( $post_type, 'excerpt' );
				//Filters whether the post should be considered "empty".
				if ( apply_filters( 'wp_insert_post_empty_content', $maybe_empty, $postarr ) ) {
					continue;
				}
				$post_status = empty( $postarr['post_status'] ) ? 'draft' : $postarr['post_status'];
				if ( 'attachment' === $post_type && ( ! in_array( $post_status, array( 'inherit', 'private', 'trash', 'auto-draft' ), true ) ) ) {
					$post_status = 'inherit';
				}
				if ( ! empty( $postarr['post_category'] ) ) {
					// Filter out empty terms.
					$post_category = array_filter( $postarr['post_category'] );
				} elseif ( ! isset( $postarr['post_category'] ) ) {
					$post_category = $post_before->post_category;
				}
				// Make sure we set a valid category.
				if ( empty( $post_category ) || ( 0 === count( $post_category ) ) || ( ! is_array( $post_category ) ) ) {
					// 'post' requires at least one category.
					$post_category = ( 'post' === $post_type && 'auto-draft' !== $post_status ) ? array( get_option( 'default_category' ) ) : array();
				}
				/*
				* Don't allow contributors to set the post slug for pending review posts.
				*
				* For new posts check the primitive capability, for updates check the meta capability.
				*/
				if ( 'pending' === $post_status ) {
					$post_type_object = get_post_type_object( $post_type );
					if ( ! current_user_can( 'publish_post', $post_id ) ) {
						$post_name = '';
					}
				}
				/*
				* Create a valid post name. Drafts and pending posts are allowed to have
				* an empty post name.
				*/
				if ( empty( $post_name ) ) {
					$post_name = ( ! in_array( $post_status, array( 'draft', 'pending', 'auto-draft' ), true ) ) ? sanitize_title( $post_title ) : '';
				} else {
					// On updates, we need to check to see if it's using the old, fixed sanitization context.
					$check_name = sanitize_title( $post_name, '', 'old-save' );
					$post_name = ( ( strtolower( urlencode( $post_name ) ) === $check_name ) && ( $post->post_name === $check_name ) ) ?  $check_name : sanitize_title( $post_name );
				}
				/*
				* Resolve the post date from any provided post date or post date GMT strings.
				* if none are provided, the date will be set to now.
				*/
				$post_date = wp_resolve_post_date( $postarr['post_date'], $postarr['post_date_gmt'] );
				if ( ! $post_date ) {
					continue;
				}
				$post_date_gmt = ( empty( $postarr['post_date_gmt'] ) || ( '0000-00-00 00:00:00' === $postarr['post_date_gmt'] ) ) ? ( ( ! in_array( $post_status, get_post_stati( array( 'date_floating' => true ) ), true ) ) ? get_gmt_from_date( $post_date ) : '0000-00-00 00:00:00' ) : $postarr['post_date_gmt'];
				$post_modified     = current_time( 'mysql' );
				$post_modified_gmt = current_time( 'mysql', 1 );
				//set modified date parms to posts_parms_arr to update it in DB.
				if( ( empty( $posts_parms_arr[ $post->ID ]['post_modified'] ) ) ){
					$posts_parms_arr[ $post->ID ]['post_modified'] = $post_modified;
				}
				if( ( empty( $posts_parms_arr[ $post->ID ]['post_modified_gmt'] ) ) ){
					$posts_parms_arr[ $post->ID ]['post_modified_gmt'] = $post_modified_gmt;
				}
				// Comment status.
				$comment_status = ( empty( $postarr['comment_status'] ) ) ? 'closed' : $postarr['comment_status'];
				// These variables are needed by compact() later.
				$post_content_filtered = $postarr['post_content_filtered'];
				$post_author           = ( ! empty( $postarr['post_author'] ) ) ? $postarr['post_author'] : $user_id;
				$ping_status           = empty( $postarr['ping_status'] ) ? get_default_comment_status( $post_type, 'pingback' ) : $postarr['ping_status'];
				$to_ping               = ( ! empty( $postarr['to_ping'] ) ) ? sanitize_trackback_urls( $postarr['to_ping'] ) : '';
				$pinged                = ( ! empty( $postarr['pinged'] ) ) ? $postarr['pinged'] : '';
				$import_id             = ( ! empty( $postarr['import_id'] ) ) ? $postarr['import_id'] : 0;
				/*
				* The 'wp_insert_post_parent' filter expects all variables to be present.
				* Previously, these variables would have already been extracted
				*/
				$menu_order = ( ( ! empty( $postarr['menu_order'] ) ) ) ? (int) $postarr['menu_order'] : 0;
				$post_password = ( ! empty( $postarr['post_password'] ) ) ? $postarr['post_password'] : '';
				$post_password = ( 'private' === $post_status ) ? '' : $post_password;
				$post_parent = ( ( ! empty( $postarr['post_parent'] ) ) ) ? (int) $postarr['post_parent'] : 0;
				$new_postarr = array_merge(
					array(
						'ID' => $post_id,
					),
					compact( array_diff( array_keys( $defaults ), array( 'context', 'filter' ) ) )
				);
				// Filters the post parent -- used to check for and prevent hierarchy loops.
				$post_parent = apply_filters( 'wp_insert_post_parent', $post_parent, $post_id, $new_postarr, $postarr );
				/*
				* If the post is being untrashed and it has a desired slug stored in post meta,
				* reassign it.
				*/
				if ( ( 'trash' === $previous_status ) && ( 'trash' !== $post_status ) ) {
					$desired_post_slug = get_post_meta( $post_id, '_wp_desired_post_slug', true );
					if ( $desired_post_slug ) {
						delete_post_meta( $post_id, '_wp_desired_post_slug' );
						$post_name = $desired_post_slug;
					}
				}
				// If a trashed post has the desired slug, change it and let this post have it.
				if (  ( 'trash' !== $post_status ) && $post_name ) {
					//Filters whether or not to add a `__trashed` suffix to trashed posts that match the name of the updated post.
					$add_trashed_suffix = apply_filters( 'add_trashed_suffix_to_trashed_posts', true, $post_name, $post_id );
					if ( $add_trashed_suffix ) {
						wp_add_trashed_suffix_to_post_name_for_trashed_posts( $post_name, $post_id );
					}
				}
				// When trashing an existing post, change its slug to allow non-trashed posts to use it.
				if ( ( 'trash' === $post_status ) && ( 'trash' !== $previous_status ) && ( 'new' !== $previous_status ) ) {
					$post_name = wp_add_trashed_suffix_to_post_name_for_post( $post_id );
				}
				$post_name = wp_unique_post_slug( $post_name, $post_id, $post_status, $post_type, $post_parent );
				// Don't unslash.
				$post_mime_type = ( ! empty( $postarr['post_mime_type'] ) ) ? $postarr['post_mime_type'] : '';
				$data = compact(
					'post_author',
					'post_date',
					'post_date_gmt',
					'post_content',
					'post_content_filtered',
					'post_title',
					'post_excerpt',
					'post_status',
					'post_type',
					'comment_status',
					'ping_status',
					'post_password',
					'post_name',
					'to_ping',
					'pinged',
					'post_modified',
					'post_modified_gmt',
					'post_parent',
					'menu_order',
					'post_mime_type',
					'guid'
				);
				$emoji_fields = array( 'post_title', 'post_content', 'post_excerpt' );
				if ( ! empty( $emoji_fields ) && ( is_array( $emoji_fields ) ) ) {
					foreach ( $emoji_fields as $emoji_field ) {
						if ( ! empty( $data[ $emoji_field ] ) ) {
							$charset = $wpdb->get_col_charset( $wpdb->posts, $emoji_field );
							if ( 'utf8' === $charset ) {
								$data[ $emoji_field ] = wp_encode_emoji( $data[ $emoji_field ] );
							}
						}
					}
				}
				//Filters slashed post data just before it is inserted into the database.
				$data = apply_filters( 'wp_insert_post_data', $data, $postarr, $unsanitized_postarr, $update );
				$data  = wp_unslash( $data );
				// Fires immediately before an existing post is updated in the database.
				do_action( 'pre_post_update', $post_id, $data );
				// $posts_data_to_update[ $post_id ] = $data;
				$posts_data_to_update[ $post_id ] = $posts_parms_arr[ $post->ID ];
				if ( empty( $data['post_name'] ) && ( ! in_array( $data['post_status'], array( 'draft', 'pending', 'auto-draft' ), true ) ) ) {
					$data['post_name'] = wp_unique_post_slug( sanitize_title( $data['post_title'], $post_id ), $post_id, $data['post_status'], $post_type, $post_parent );
					$posts_data_to_update[ $post_id ]['post_name'] = $data['post_name'];
				}
				if ( is_object_in_taxonomy( $post_type, 'category' ) ) {
					wp_set_post_categories( $post_id, $post_category );
				}
				if ( ( ! empty( $postarr['tags_input'] ) ) && is_object_in_taxonomy( $post_type, 'post_tag' ) ) {
					wp_set_post_tags( $post_id, $postarr['tags_input'] );
				}
				
				if ( ! empty( $post_parms['tax_input'] ) && is_array( $post_parms['tax_input'] ) ) {
					foreach ( $post_parms['tax_input'] as $taxonomy => $terms_data ) {
						if ( ( ! taxonomy_exists( $taxonomy ) ) || ( empty( $terms_data ) ) || ( ! is_object_in_taxonomy( $post_type, $taxonomy ) ) ) {
							continue;
						}
						// Prepare an array to hold term values.
						$term_ids_set    = array();
						$term_ids_remove = array();
						$append = false; // Default append to false.
						$remove_all_terms = false;
						foreach ( $terms_data as $term_data ) {
							if ( ( empty( $term_data ) ) || ( empty( $term_data['operator'] ) ) ) {
								continue;
							}
							$remove_all_terms = ( ! empty( $term_data['remove_all_terms'] ) && $term_data['remove_all_terms'] === true ) ? true : false;
							if ( ( $term_data['operator'] !== 'remove_from' ) ){
								if ( ( is_array( $term_data['value'] ) ) ) {
									$term_ids_set = array_map( 'absint', $term_data['value'] );
								} else {
									$term_ids_set[] = ( ! empty( $term_data['value'] ) ) ? absint( $term_data['value'] ) : 0;
								}
								if ( $term_data['operator'] === 'add_to' ){ 
									$append = true; // Set append if any term needs it.
								}
							} else {
								if ( ( is_array( $term_data['value'] ) ) ) {
									$term_ids_remove = array_map( 'absint', $term_data['value'] );
								} else {
									$term_ids_remove[] = ( ! empty( $term_data['value'] ) ) ? absint( $term_data['value'] ) : 0;
								}
							}
						}
						// compat for 'Germanized for WooCommerce Pro' plugin.
						$postarr = apply_filters( 'sm_pro_update_meta_args',
							$postarr,
							array( 'taxonomy' => $taxonomy,
								'term_ids' => $term_ids_set,
								'term_ids_remove' => $term_ids_remove,
								'taxonomy_terms' => $taxonomy_terms
							) 
						);
						$all_term_ids = array_merge( $all_term_ids, array_unique( array_merge( $term_ids_set, $term_ids_remove ) ) );
						$taxonomy_data_to_update[ $post_id ][$taxonomy] = array( 'term_ids_set'=>$term_ids_set,'taxonomy' => $taxonomy, 'append' => $append, 'term_ids_remove' => $term_ids_remove, 'remove_all_terms' => $remove_all_terms );
					}
				}
				if ( ! empty( $postarr['meta_input'] ) ) {
					$posts_meta_data_to_update[ $post_id ] = $postarr['meta_input'];
					$posts_meta_keys = array_unique( array_merge(
						$posts_meta_keys,
						array_keys( $postarr['meta_input'] )
					));
				}
				$posts_data_for_after_hook[ $post_id ] = $data; // modified data by filter wp_insert_post_data.
				$posts_args_for_after_hook[ $post_id ] = $postarr; // same as data but unmodified, since data is filtered by hook.
				$updated_posts_obj[ $post_id ] = self::update_post_object( $post, (object) $data );
			}

			if ( empty( $posts_data_to_update ) ) return;
			// Update posts data.
			$posts_update_result = self::run_bulk_update_posts_query( $posts_data_for_after_hook, $post_ids );
			if ( empty( $posts_update_result ) ) {
				return array(
					'taxonomies_update_result' => false,
					'postmeta_update_result' => false,
					'posts_update_result'     => false
				);
			}
			// update post_meta data.
			if ( ( ! empty( $posts_meta_data_to_update ) ) && ( ! empty( $posts_meta_keys ) ) ) {
				$postmeta_update_result = self::update_meta_tables(
					array(
						'meta_data_edited'=>array(
							'postmeta' => $posts_meta_data_to_update,
						),
						'meta_keys_edited'=>( ! empty( $posts_meta_keys ) && is_array( $posts_meta_keys ) ) ? array_unique( $posts_meta_keys ) : array(),
						'task_id'         => ( ! empty( $request_params['task_id'] ) ) ? absint( $request_params['task_id'] ) : 0,
						'prev_postmeta_values' => ( ! empty( $request_params['prev_postmeta_values'] ) ) ? $request_params['prev_postmeta_values'] : array()
					)
				);
			}
			// update terms data.
			if( ( ! empty( $taxonomy_data_to_update ) ) && ( ! empty( $taxonomies ) ) ){
				$taxonomies_update_result = self::set_or_remove_object_terms( 
					array(
						'taxonomy_data_to_update' => $taxonomy_data_to_update,
						'taxonomies'              => $taxonomies,
						'task_id'                 => ( ! empty( $request_params['task_id'] ) ) ? absint( $request_params['task_id'] ) : 0,
						'term_ids'                => ( ! empty( $all_term_ids ) && is_array( $all_term_ids ) ) ? $all_term_ids : array()
					)
				);
			}
			// execute actions after updating a post.
			self::update_posts_after_update_actions(
				array(
					'post_ids'                  => $post_ids,
					'posts_data_for_after_hook' => $posts_data_for_after_hook,
					'posts_args_for_after_hook' => $posts_args_for_after_hook,
					'fire_after_hooks'          => $fire_after_hooks,
					'posts_before'              => $posts_before,
					'task_id'                   => ( ! empty( $request_params['task_id'] ) ) ? absint( $request_params['task_id'] ) : 0,
					'posts_fields_edited'       => $posts_parms_arr,
					'updated_posts'             => $updated_posts_obj,
				)
			);
			//return update result.
			return array(
				'taxonomies_update_result' => ( ! empty( $taxonomies_update_result ) ) ? $taxonomies_update_result : false,
				'postmeta_update_result' => $postmeta_update_result,
				'posts_update_result'     => $posts_update_result
			);
		}

		/**
		 * Updates or inserts metadata for meta tables.
		 *
		 * Metadata is either added if missing, or updated if it already exists.
		 *
		 * @param array $meta_data_edited   The main data array structured as:
		 *                                  [table_name => [id => [meta_key => meta_value]]].
		 *                                  table_name` (string): The name of the table to update.
		 *                                  id (int): The identifier for each record (e.g., post ID).
		 *                                  meta_key (string): The meta key to be updated.
		 *                                  meta_value (mixed): The new value to store.
		 *
		 * @param array $meta_keys_edited   Array of specific meta keys to update.
		 *
		 * @return array|true update_failed_data array, true if all ids are updated successfully.
		*/
		public static function update_meta_tables( $args = array() ){
			if ( empty( $args['meta_data_edited'] ) ) {
				return;
			}
			global $wpdb;
			$update_params_meta = array(); // for all tables with meta_key = meta_value like structure for updating the values.
			$insert_params_meta = array(); // for all tables with meta_key = meta_value like structure for inserting the values.
			$update_failed_data = array();
			$field_names = array();
			foreach ( $args['meta_data_edited'] as $update_table => $update_params ) {
				if ( empty( $update_params ) ) {
					continue;
				}
				$post_ids = array_keys( $update_params );
				$meta_keys_edited = ( ! empty( $args['meta_keys_edited'] ) ) ? $args['meta_keys_edited'] : array();
				$update_table_key = ''; //pkey for the update table.
				if ( 'postmeta' === $update_table ) {
					$update_table_key = 'post_id';
				}
				//Code for getting the old values and meta_ids
				$old_meta_data = self::get_meta_data( $post_ids, $meta_keys_edited, $update_table, $update_table_key );
				$meta_data = array();
				if ( ! empty( $old_meta_data ) ) {
					foreach ( $old_meta_data as $key => $old_values ) {
						foreach ( $old_values as $data ) {
							if ( empty( $meta_data[ $key ] ) ) {
								$meta_data[ $key ] = array();
							}
							$meta_data[ $key ][ $data['meta_key'] ] = array();
							$meta_data[ $key ][ $data['meta_key'] ]['meta_id'] = $data['meta_id'];
							$meta_data[ $key ][ $data['meta_key'] ]['meta_value'] = $data['meta_value'];
						}
					}
				}
				$meta_index = 0;
				$insert_meta_index = 0;
				$index = 0;
				$insert_index = 0;
				$old_post_id = '';
				$update_params_index = 0;
				//Code for generating the query.
				foreach ( $update_params as $id => $updated_data ) {
					$updated_data_index = 0;
					$update_params_index++;
					foreach ( $updated_data as $key => $value ) {
						$updated_data_index++;
						$field_names[ $id ][ $key ] = "{$update_table}/meta_key={$key}/meta_value={$key}";
						$key = wp_unslash($key);
						$value = esc_sql( wp_unslash( $value ) );
						$meta_type = 'post';
						if ( 'postmeta' === $update_table ) {
							$value = sanitize_meta( $key, $value, 'post' );
						}
						// Filter whether to update metadata of a specific type.
						$check = apply_filters( "update_{$meta_type}_metadata", null, $id, $key, $value, '' );
						if ( null !== $check ) {
							continue;
						}
						if ( is_numeric( $value ) ) {
							$value = strval( $value );
						}
						// Code for handling if the meta key does not exist.
						if ( empty( $meta_data[ $id ][ $key ] ) ) {
							// Filter whether to add metadata of a specific type.
							$check = apply_filters( "add_{$meta_type}_metadata", null, $id, $key, $value, false );
							if ( null !== $check ) {
								continue;
							}
							if ( empty( $insert_params_meta[ $update_table ] ) ) {
								$insert_params_meta[ $update_table ] = array();
								$insert_params_meta[ $update_table ][ $insert_meta_index ] = array();
								$insert_params_meta[ $update_table ][ $insert_meta_index ]['values'] = array();
							}
							if ( $insert_index >= 5 ) { // Code to have not more than 5 value sets in single insert query.
								$insert_index = 0;
								$insert_meta_index++;
							}
							$insert_params_meta[ $update_table ][ $insert_meta_index ]['values'][] = array(
								'id' => $id,
								'meta_key' => $key,
								'meta_value' => $value
							);
							$value = maybe_serialize( $value );
							if ( empty( $insert_params_meta[ $update_table ][ $insert_meta_index ]['query'] ) ) {
								$insert_params_meta[ $update_table ][ $insert_meta_index ]['query'] = "(" . $id . ", '" . $key . "', '" . $value . "')";
							} else {
								$insert_params_meta[ $update_table ][ $insert_meta_index ]['query'] .= ", (" . $id . ", '" . $key . "', '" . $value . "')";
							}
							$insert_index++;
							continue;
						}
						$value = maybe_serialize($value);
						if (empty($update_params_meta[$update_table])) {
							$update_params_meta[$update_table] = array();
							$update_params_meta[$update_table][$meta_index] = array();
							$update_params_meta[$update_table][$meta_index]['ids'] = array();
							$update_params_meta[$update_table][$meta_index]['query'] = '';
						}
		
						//if meta old value & new value does not match then create a query for updating.
						if (! empty($meta_data[$id][$key]) && $meta_data[$id][$key]['meta_value'] !== $value) {
							$meta_data[$id][$key]['meta_value'] = $value;
							if ($index >= 5 && $old_post_id != $id) {
								$update_params_meta[$update_table][$meta_index]['query'] .= ' ELSE meta_value END END ';
								$index = 0;
								$meta_index++;
							}
		
							if (empty($update_params_meta[$update_table][$meta_index]['query'])) {
								$update_params_meta[$update_table][$meta_index]['query'] = ' CASE post_id ';
							}
		
							if ($old_post_id != $id) {
								if (!empty($index)) {
									$update_params_meta[$update_table][$meta_index]['query'] .= ' ELSE meta_value END ';
								}
								$update_params_meta[$update_table][$meta_index]['query'] .= " WHEN '" . $id . "' THEN 
																									CASE meta_key ";
		
								$old_post_id = $id;
								$update_params_meta[$update_table][$meta_index]['ids'][] = $id;
								$index++;
							}
							$update_params_meta[$update_table][$meta_index]['query'] .= " WHEN '" . $key . "' THEN '" . $value . "' ";
						}	
					}
					//Code for the last condition.
					if ($update_params_index === sizeof($update_params) &&  $updated_data_index === sizeof($updated_data) && !empty($update_params_meta[$update_table][$meta_index]['query'])) {
						$update_params_meta[$update_table][$meta_index]['query'] .= ' ELSE meta_value END END ';
					}
				}
		
				// Start here... update the actions and query in for loop.
				if (!empty($insert_params_meta)) {
					foreach ($insert_params_meta as $insert_table => $data) {
						if (empty($data)) {
							continue;
						}
		
						$insert_table_key = 'post_id';
						foreach ($data as $insert_params) {
							if (empty($insert_params['values']) || empty($insert_params['query'])) {
								continue;
							}
		
							$insert_meta_query = "INSERT INTO {$wpdb->prefix}" . $insert_table . " (" . $insert_table_key . ",meta_key,meta_value)
																	VALUES " . $insert_params['query'];
		
							if ($insert_table == 'postmeta') {
								// function to replicate wordpress add_metadata().
								self::add_post_meta(
									array(
										'meta_type' => 'post',
										'insert_values' => $insert_params['values'],
										'insert_meta_query' => $insert_meta_query,
										'insert_table_key'=> $insert_table_key,
										'field_names'=> $field_names,
										'task_id'=> !empty( $args['task_id'] ) ? $args['task_id'] : 0,
										'prev_postmeta_values'=> !empty( $args['prev_postmeta_values'] ) ? $args['prev_postmeta_values'] : array()
									)
								);
							} else {
								$result_insert_meta = $wpdb->query($insert_meta_query);
							}
						}
					}
				}
		
				// data updation for meta tables.
				if (!empty($update_params_meta)) {
					foreach ($update_params_meta as $update_table => $data) {
						if (empty($data)) {
							continue;
						}
		
						$update_table_key = (empty($update_table_key)) ? 'post_id' : $update_table_key;
						foreach ($data as $update_params) {
							if (empty($update_params['ids']) || empty($update_params['query'])) {
								continue;
							}
							$update_meta_query = "UPDATE {$wpdb->prefix}$update_table
																SET meta_value = " . $update_params['query'] . "
																WHERE $update_table_key IN (" . implode(',', $update_params['ids']) . ")";
							if ('postmeta' === $update_table) {
								// function to replicate wordpress update_postmeta().
								$update_result = self::update_post_meta(
									array(
										'meta_type' => 'post',
										'update_ids' => (! empty($update_params['ids'])) ? $update_params['ids'] : array(),
										'meta_data' => (! empty($meta_data)) ? $meta_data : array(),
										'update_meta_query' => (! empty($update_meta_query)) ? $update_meta_query : '',
										'update_table_key' => (! empty($update_table_key)) ? $update_table_key : 'post_id',
										'field_names'=> $field_names,
										'task_id'=> !empty( $args['task_id'] ) ? $args['task_id'] : 0,
										'prev_postmeta_values'=> !empty( $args['prev_postmeta_values'] ) ? $args['prev_postmeta_values'] : array(),
									)
								);
								if ( $update_result['update_status']!==true && !empty( $update_result['update_failed_data'] && is_array( $update_result['update_failed_data'] ) )  ) {
									$update_failed_data = array_merge( $update_failed_data, $update_result['update_failed_data'] );
								}
							}
						}
					}
				}
			}
			return ! empty( $update_failed_data ) ? $update_failed_data : 'success';
		}
		// Function to get the meta data for the given ids
		public static function get_meta_data($ids, $meta_keys, $update_table, $update_table_key = 'post_id') {
			global $wpdb;

			$ids_format = implode(', ', array_fill(0, count($ids), '%s'));
			$meta_keys_format = implode(', ', array_fill(0, count($meta_keys), '%s'));
			$group_by = '';

			if ( $update_table == 'postmeta' ) {
				$group_by = 'GROUP BY '.$update_table_key.' , meta_id';
			}

			$old_meta_data_query = "SELECT *
								FROM {$wpdb->prefix}$update_table
								WHERE post_id IN (".implode(',',$ids).")
									AND meta_key IN ('".implode("','",$meta_keys)."')
									AND 1=%d
								$group_by";

			$old_meta_data_results = $wpdb->get_results( $wpdb->prepare( $old_meta_data_query, 1 ), 'ARRAY_A');  // passed 1 to avoid the debug warning

			$old_meta_data = array();

			if ( count($old_meta_data_results) > 0) {
				foreach ($old_meta_data_results as $meta_data) {

					$post_id = $meta_data[$update_table_key];
					unset($meta_data[$update_table_key]);

					if ( empty($old_meta_data[$post_id]) ) {
						$old_meta_data[$post_id] = array();
					}
					
					$old_meta_data[$post_id][] = $meta_data;
				}
			}

			return $old_meta_data;
		}
		/**
		 * Updates meta data in batch for various WordPress meta types, with action hooks for pre- and post-update events.
		 *
		 * This function replicates the functionality of `update_post_meta()` but allows batch updating for multiple meta IDs
		 *
		 * @param array $args {
		 *     Arguments for updating meta data.
		 *
		 *     @type array  $update_ids        Array of IDs for posts, users, or other objects to update.
		 *     @type array  $meta_data         Nested array where each ID has meta keys with corresponding meta values.
		 *     @type string $meta_type         Type of meta (e.g., 'post', 'user') used for triggering specific hooks.
		 *     @type string $update_table_key  Database column used as the key for the update (e.g., 'post_id' for post meta).
		 *     @type string $update_meta_query Optional. SQL query string for executing the batch update.
		 * }
		 *
		 * @global wpdb $wpdb WordPress database abstraction object.
		 * @return void
		*/
		public static function update_post_meta( $args = array() ) {
			if ( empty( $args['update_ids'] ) || empty( $args['meta_data'] ) ) {
				return;
			}
			$result = array( 'update_status'=> false, 'update_failed_data' => [] );
			global $wpdb;
			$update_query_values = $update_query_ids = array();
			// Code for executing actions pre update.
			foreach ( $args['update_ids'] as $id ) {
				if ( empty( $args['meta_data'][ $id ] ) ) {
					continue;
				}
				$meta_key_update_values = '';
				foreach ( $args['meta_data'][ $id ] as $meta_key => $value ) {
					do_action( "update_{$args['meta_type']}_meta", $value['meta_id'], $id, $meta_key, $value['meta_value'] );
					$meta_value = maybe_serialize( $value['meta_value'] );
					if ( 'post' === $args['meta_type'] ) {
						do_action( 'update_postmeta', $value['meta_id'], $id, $meta_key, $value['meta_value'] );
					}
					if ( empty( $args['update_meta_query'] ) ) {
						$meta_key_update_values .= " WHEN '". $meta_key ."' THEN '". $value['meta_value'] ."' ";
					}
				}
				if ( empty( $args['update_meta_query'] ) && ! empty( $meta_key_update_values ) ) {
					$update_query_ids[] = $id;
					$update_query_values[] = " WHEN '". $id ."' THEN CASE meta_key ". $meta_key_update_values ." ELSE meta_value END ";
				}
			}
			if ( empty( $args['update_meta_query'] ) && ! empty( $update_query_values ) ) {
				$args['update_meta_query'] = "UPDATE {$wpdb->prefix}". $args['meta_type'] ."meta SET meta_value = CASE ". $args['update_table_key'] ." ". implode( ' ', $update_query_values ) ." END 
									WHERE ". $args['update_table_key'] ." IN (". implode( ',', $update_query_ids ) ." ) ";
			}
			if ( empty( $args['update_meta_query'] ) ) {
				return;
			}
			$result_update_meta = $wpdb -> query( $args['update_meta_query'] );
			if( !empty( $result_update_meta ) && !is_wp_error( $result_update_meta ) ){
				$result['update_status'] = true;
			}
			// Code for executing actions post update
			foreach ( $args['update_ids'] as $id ) {
				if ( empty( $args['meta_data'][ $id] ) ) {
					continue;
				}
				wp_cache_delete($id, $args['meta_type'] . '_meta');
				foreach ( $args['meta_data'][ $id ] as $meta_key => $meta_data ) {
					do_action( "updated_{$args['meta_type']}_meta", $meta_data['meta_id'], $id, $meta_key, $meta_data['meta_value'] );
					$meta_value = maybe_serialize( $meta_data['meta_value'] );
					if ( 'post' === $args['meta_type'] ) {
						do_action( 'updated_postmeta', $meta_data['meta_id'], $id, $meta_key, $meta_value );
					}
					if( empty( $result_update_meta ) || ( is_wp_error( $result_update_meta ) ) ){
						$result['update_status'] = false;
						$result['update_failed_data'][] = $id. "/" . $meta_key;
					}
					if( empty($args['field_names'][ $id ][ $meta_key ] ) ) continue;
					
					$disable_task_details_update = apply_filters(
						'sm_disable_task_details_update',
						array(
							'prev_vals'=>('postmeta/meta_key=_product_attributes/meta_value=_product_attributes' === $args['field_names'][ $id ][ $meta_key ]) ? Smart_Manager_Base::$previous_vals : $args['prev_postmeta_values'],
							'field_name'=>!empty( $args['field_names'][ $id ][ $meta_key ] ) ? $args['field_names'][ $id ][ $meta_key ] : '',
							'data'=>!empty( $args ) ? $args : array(),
							'record_id'=>!empty( $id ) ? intval( $id ) : 0,
						)
					);
					
					if ( empty($args['task_id'] ) || empty( $id ) || empty( $meta_key ) || ! isset($args['field_names'][ $id ][ $meta_key ] ) || ( ! empty( $disable_task_details_update ) ) ) {
							continue;
					}
					Smart_Manager_Base::$update_task_details_params[] = array(
						'task_id' =>$args['task_id'],
						'action' => 'set_to',
						'status' => 'completed',
						'record_id' => $id,
						'field' =>$args['field_names'][ $id ][ $meta_key ],                                                               
						'prev_val' =>$args['prev_postmeta_values'][ $id ][ $meta_key ],
						'updated_val' => $meta_value,
					);	
				}
			}
			return $result;
		}
		
		// Function to replicate wordpress add_metadata()
		public static function add_post_meta( $args = array() ) {
			if( empty( $args ) ) return;
			global $wpdb;
			if ( empty($args['insert_values']) ) {
				return;
			}
		
			$insert_query_values = array();
		
			// Code for executing actions pre insert
			foreach ( $args['insert_values'] as $insert_value ) {
				do_action( "add_{$args['meta_type']}_meta", $insert_value['id'], $insert_value['meta_key'], $insert_value['meta_value'] );
				
				if( empty($args['insert_meta_query']) ) {
					$insert_query_values[] = " ( ". $insert_value['id'] .", '". $insert_value['meta_key'] ."', '". $insert_value['meta_value'] ."' ) ";
				}
			}
		
			if( empty($args['insert_meta_query']) && !empty($insert_query_values) ) {
				$args['insert_meta_query'] = "INSERT INTO {$wpdb->prefix}". $args['meta_type'] ."meta(". $args['insert_table_key'] .", meta_key, meta_value) VALUES ". implode(",", $insert_query_values);
			}
			
		
			//Code for inserting the values
			$result_insert_meta = $wpdb->query($args['insert_meta_query']);
			$mid = '';
		
			// Code for executing actions pre insert
			foreach ( $args['insert_values'] as $insert_value ) {
				
				if ( empty($first_insert_id) ) {
					$mid = $wpdb->insert_id;
				}
				wp_cache_delete($insert_value['id'], $args['meta_type'] . '_meta');
				do_action( "added_{$args['meta_type']}_meta", $mid, $insert_value['id'], $insert_value['meta_key'], $insert_value['meta_value'] );
		
				$mid++;
				if ( ( defined('SMPRO') && empty( SMPRO ) ) || empty( $args['task_id'] ) || empty( $insert_value['id'] ) || empty( $insert_value['meta_key'] ) || ( is_wp_error( $result_insert_meta ) ) || ! isset( $args['field_names'][ $insert_value['id'] ][ $insert_value['meta_key'] ] ) ) {
					continue;
				}
				Smart_Manager_Base::$update_task_details_params[] = array(
						'task_id' => $args['task_id'],
						'action' => 'set_to',
						'status' => 'completed',
						'record_id' => $insert_value['id'],
						'field' => $args['field_names'][ $insert_value['id'] ][ $insert_value['meta_key'] ],                                                               
						'prev_val' => $args['prev_postmeta_values'][ $insert_value['id'] ][ $insert_value['meta_key'] ],
						'updated_val' => $insert_value['meta_value'],
				);
			}
			return;
		}
		
		/**
		 * Updates multiple posts in the WordPress posts table using a single database call.
		 *
		 * @param array $posts_data_to_update Associative array of post data with post IDs as keys.
		 * @param array $selected_post_ids Array of post IDs to update.
		 * @return void|false|int Number of rows affected if the update is successful, or false if the update fails or returns an error.
		 */
		public static function run_bulk_update_posts_query( $posts_data_to_update = array(), $selected_post_ids = array() ) {
			if ( empty( $posts_data_to_update ) || ( ! is_array( $posts_data_to_update ) ) || empty( $selected_post_ids ) || ( ! is_array( $selected_post_ids ) ) ) return;
			global $wpdb;
			// Sanitize and count post IDs.
			$selected_post_ids = array_map( 'intval', $selected_post_ids );
			$num_ids           = count( $selected_post_ids );
			// Return early if there are no valid IDs.
			if ( empty( $num_ids ) ) return;
			// Prepare placeholders for the post IDs.
			$post_id_placeholders = implode( ',', array_fill( 0, $num_ids, '%d' ) );
			if ( empty( $post_id_placeholders ) ) return;
			$columns = array(
				'post_author',
				'post_date',
				'post_date_gmt',
				'post_content',
				'post_content_filtered',
				'post_title',
				'post_excerpt',
				'post_status',
				'post_type',
				'comment_status',
				'ping_status',
				'post_password',
				'post_name',
				'to_ping',
				'pinged',
				'post_modified',
				'post_modified_gmt',
				'post_parent',
				'menu_order',
				'post_mime_type',
				'guid'
			);
			// Initialize arrays to hold SQL parts.
			$case_statements = array_fill_keys( $columns, array() );
			// Build CASE statements for each field.
			foreach ( $posts_data_to_update as $post_id => $post_data ) {
				if ( ! in_array( $post_id, $selected_post_ids, true ) ) continue;
				if ( empty( $case_statements ) || ( ! is_array( $case_statements ) ) ) {
					continue;
				}
				foreach ( $case_statements as $field => &$case_clause ) {
					if ( ! isset( $post_data[ $field ] ) ) continue;
					$case_clause[] = $wpdb->prepare( 'WHEN %d THEN %s', $post_id, $post_data[ $field ] );
				}
			}

			// Construct SET clauses with CASE expressions.
			$set_clauses = array();
			if ( ! empty( $case_statements ) && ( is_array( $case_statements ) ) ) {
				foreach ( $case_statements as $field => $clauses ) {
					if ( empty( $clauses ) ) continue;
					$set_clauses[] = "{$field} = CASE ID " . implode( ' ', $clauses ) . " ELSE {$field} END";
				}
			}
			// If there are no valid fields to update, exit.
			if ( empty( $set_clauses ) ) return;
			// Execute the query with the selected post IDs.
			if ( ( is_wp_error( $wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}posts SET " . implode( ', ', $set_clauses ) . " WHERE ID IN ( $post_id_placeholders )", $selected_post_ids ) ) ) ) && is_callable( array( 'Smart_Manager', 'log' ) ) ) {
				Smart_Manager::log( 'error', _x( 'Bulk update posts batch failed', 'bulk update process', 'smart-manager-for-wp-e-commerce' ) );
				return false;
			}
			return true;
		}

		/**
		 * Function for actions to execute after updating a post.
		 *
		 * @param array $parms Parameters for actions to fire after post update.
		 * @return void
		*/
		public static function update_posts_after_update_actions( $parms = array() ) {
			$post_ids                  = ( ! empty( $parms['post_ids'] ) ) ? $parms['post_ids'] : array();
			$posts_data_for_after_hook = ( ! empty( $parms['posts_data_for_after_hook'] ) ) ? $parms['posts_data_for_after_hook'] : array();
			$posts_args_for_after_hook = ( ! empty( $parms['posts_args_for_after_hook'] ) ) ? $parms['posts_args_for_after_hook'] : array();
			$fire_after_hooks          = ( ! empty( $parms['fire_after_hooks'] ) ) ? $parms['fire_after_hooks'] : false;
			$posts_before              = ( ! empty( $parms['posts_before'] ) ) ? $parms['posts_before'] : array();
			$posts_fields_edited       = ( ! empty( $parms['posts_fields_edited'] ) ) ? $parms['posts_fields_edited'] : array();
			$task_id          		   = ( ! empty( $parms['task_id'] ) ) ? $parms['task_id'] : 0;
			$update                    = true;
			$updated_posts_obj         = ( ! empty( $parms['updated_posts'] ) ) ? $parms['updated_posts'] : array();
			if ( ( empty( $posts_fields_edited ) ) || ( empty( $post_ids ) ) || ( empty( $posts_data_for_after_hook ) ) || ( empty( $posts_args_for_after_hook ) ) || ( empty( $posts_before ) ) ) {
				return;
			}
			if ( ( empty( $updated_posts_obj ) ) || ( ! is_array( $updated_posts_obj ) ) ) return;
			foreach ( $updated_posts_obj as $post ) {
				if ( ( empty( $post->ID ) ) ) continue;
				$post_id = $post->ID;
				$post_before     = $posts_before[ $post_id ];
				$data            = $posts_data_for_after_hook[ $post_id ];//post data that maybe modified by filter
				$args            = $posts_args_for_after_hook[ $post_id ];//unmodified post data
				if ( ! empty( $args['page_template'] ) ) {
					$post->page_template = $args['page_template'];
					$page_templates      = wp_get_theme()->get_page_templates( $post );
					( ( 'default' !== $args['page_template'] ) && ( ! isset( $page_templates[ $args['page_template'] ] ) ) ) ? update_post_meta( $post_id, '_wp_page_template', 'default' ) : update_post_meta( $post_id, '_wp_page_template', $args['page_template'] );
				}
				self::fire_post_update_hooks( $post, $post_before, $update, $post_before->post_status, $data['post_status'] );
				if ( $fire_after_hooks ) {
					wp_after_insert_post( $post, $update, $post_before );
				}
				if ( ( empty( $task_id ) ) || ( ! is_array( $posts_fields_edited ) ) ) continue;
				foreach ( $posts_fields_edited[ $post_id ] as $key => $value ) {
					if ( ( ! empty( $key ) ) && ( 'ID' === $key ) ) continue;
					Smart_Manager_Base::$update_task_details_params[] = array(
						'task_id' => $task_id,
						'action' => 'set_to',
						'status' => 'completed',
						'record_id' => $post_id,
						'field' => 'posts/'.$key,                                                               
						'prev_val' => $post_before->$key,
						'updated_val' => $value
					);
				}
			}
		}

		//Fires when an existing post has been updated. 
		public static function fire_post_update_hooks( $post = null, $post_before = null, $update = true, $previous_status = '', $new_status = '' ){
			if( ( empty( $post ) ) || ( empty( $post_before ) ) || ( empty( $previous_status ) ) || ( empty( $new_status ) ) ) return;
			do_action( 'sm_pro_before_run_after_update_hooks', $post, $post_before );
			do_action( 'transition_post_status', $new_status, $previous_status, $post );
			do_action( "{$previous_status}_to_{$new_status}", $post );
			do_action( "{$new_status}_{$post->post_type}", $post->ID, $post, $previous_status );
			do_action( "edit_post_{$post->post_type}", $post->ID, $post );
			do_action( 'edit_post', $post->ID, $post );
			do_action( 'post_updated', $post->ID, $post, $post_before );
			do_action( "save_post_{$post->post_type}", $post->ID, $post, $update );
			do_action( 'save_post', $post->ID, $post, $update );
			do_action( 'wp_insert_post', $post->ID, $post, $update );
		}

		/**
		 * Function Clones a post object and updates its properties without modifying the original.
		 *
		 * @param object|null $post_object The original post object to be cloned and updated.
		 * @param object|null $update_properties Key-value pairs of properties to update.
		 * 
		 * @return object|null The updated post object or null
		*/
		public static function update_post_object( $post_object = null, $update_properties = null ) {
			if ( ( empty( $post_object ) ) || ( empty( $update_properties ) ) ) return;
			// Clone the post object to avoid modifying the original
			$post_object = clone $post_object;
			foreach ( $update_properties as $property => $value ) {
				if ( ! property_exists( $post_object, $property ) ) continue;
				$post_object->$property = $value; // Override existing property with new value.
			}
			return $post_object;
		}
		
		/**
		 * Bulk updates terms for multiple posts, replicating wp_set_object_terms & wp_remove_object_terms functionality.
		 *
		 * handles term relationships, updates taxonomy counts, and triggers relevant WordPress actions.
		 *
		 * @param array $args
		 *
		 * @return bool True on success, array of taxonomy data failed to update on failure, void when data is invalid
		*/
		public static function set_or_remove_object_terms( $args = array() ) {
			if ( ( empty( $args ) ) || ( empty( $args['taxonomy_data_to_update'] ) ) || ( empty( $args['taxonomies'] ) ) || ( ! is_array( $args['taxonomies'] ) ) || ( ! is_array( $args['taxonomy_data_to_update'] ) ) || ( empty( $args['term_ids'] ) ) || ( ! is_array( $args['term_ids'] ) ) ) {
				return;
			}
			global $wpdb;
			$posts_params_arr       = $args['taxonomy_data_to_update'];
			$taxonomies             = array_map( 'sanitize_text_field', array_unique( $args['taxonomies'] ) );
			$objects_new_tt_ids     = array(); //tt ids to create relationship for.
			$tt_ids                 = array(); //tt ids (passed + newly created).
			$objects_tt_ids         = array(); 
			$taxonomy_count_data    = array();
			$insert_tt_placeholders = array();
			$delete_tt_placeholders = array();
			$delete_tt_ids          = array(); //all tt ids to delete relationship.
			$objects_delete_tt_ids  = array(); //tt ids to delete relationship keyed by object id.
			$existing_relationships = array(); 
			$insert_result          = false;
			$delete_result          = false;
			$task_id 				= ( ! empty( $args['task_id'] ) ) ? $args['task_id'] : 0;
			$terms_taxonomy_ids     = self::get_term_taxonomy_ids_by_term_ids( array_unique( $args['term_ids'] ) );
			// Fetch existing term relationships for objects/posts and taxonomies.
			$existing_terms = wp_get_object_terms( 
				array_map( 'absint', array_keys( $posts_params_arr ) ),//passing object ids.
				$taxonomies,
				[
					'fields' => 'all_with_object_id',
					'orderby' => 'none',
					'update_term_meta_cache' => false,
				] 
			);
			//extract old terms ids and map existing term relationships.
			foreach ( $existing_terms as $term ) {
				if ( ( empty( $term ) ) || ( empty( $term->taxonomy ) ) || ( empty($term->term_taxonomy_id) ) || ( empty( $term->object_id ) ) ) {
					continue;
				}
				$existing_relationships[ $term->object_id ][ $term->taxonomy ][] = $term->term_taxonomy_id;
			}
			// Process the term relationships for each object.
			foreach ( $posts_params_arr as $object_id => $params ) {
				if( ( empty( $object_id ) ) || ( empty( $params ) ) || ( ! is_array( $params ) ) ) {
					continue;
				}
				$object_id = absint( $object_id ); //sanitize object_id or post id.
				
				foreach ( $params as $taxonomy => $taxonomy_data ) {
					if ( ( empty( $taxonomy_data ) ) || ( empty( $taxonomy ) )  || ( empty( $taxonomy_data[ 'term_ids_set' ] ) && empty( $taxonomy_data[ 'term_ids_remove' ] ) ) ) {
						continue;
					}
					$taxonomy   = sanitize_text_field( $taxonomy ); //sanitize taxonomy.
					$append     = ( ! empty( $taxonomy_data['append'] ) ) ? true : false;

					$object_rel = ( ( ! empty( $existing_relationships ) ) && ( ! empty( $existing_relationships[ $object_id ] ) ) ) ? $existing_relationships[ $object_id ] : array();

					$object_existing_tt_ids = ( ( ! empty( $object_rel ) ) && ( ! empty( $object_rel[ $taxonomy ] ) ) ) ? $object_rel[ $taxonomy ] : array();

					$objects_delete_tt_ids[ $object_id ][ $taxonomy ] = array();
					$objects_new_tt_ids[ $object_id ][ $taxonomy ]    = array();
					$objects_tt_ids[ $object_id ][ $taxonomy ]        = array();
					if ( ( ! empty( $taxonomy_data['remove_all_terms'] ) ) ) {
						$taxonomy_data['term_ids_remove'] = $object_existing_tt_ids;
					}
					foreach ( $taxonomy_data['term_ids_set'] as $t_id ) {
						if ( empty( $t_id ) || ( is_string( $t_id ) && '' === trim( $t_id ) ) ) {
							continue;
						}
						$tt_id = ( ( is_array( $terms_taxonomy_ids ) ) && ( ! empty( $terms_taxonomy_ids[$t_id] ) ) ) ? absint( $terms_taxonomy_ids[$t_id] ) : 0;
						//code for creating the terms.
						if ( ! is_int( $t_id ) ) { //if not int then value assumed to be new term name (default wordpress behaviour).
							$term_info = wp_insert_term( $t_id, $taxonomy );
							if ( is_wp_error( $term_info ) || ( empty( $term_info ) ) || ( ! is_array( $term_info ) ) || ( empty( $term_info['term_taxonomy_id'] ) ) ) {
								continue;
							}
							$tt_id = absint( $term_info['term_taxonomy_id'] ); //sanitize term taxonomy id.
						}
						if ( empty( $tt_id ) ) {
							continue;
						}
						if ( ( empty( $tt_ids[ $taxonomy ] ) ) || ( ! in_array( $tt_id, $tt_ids[ $taxonomy ], true ) ) ) { 
							$tt_ids[ $taxonomy ][] = $tt_id;
						}
						if ( ( empty( $objects_tt_ids[ $object_id ][ $taxonomy ] ) ) || ( ! in_array( $tt_id, $objects_tt_ids[ $object_id ][ $taxonomy ], true ) ) ) { 
							$objects_tt_ids[ $object_id ][ $taxonomy ][] = $tt_id;
						}
						// skip if the term relationship already exists.
						if( ( in_array( $tt_id, $object_existing_tt_ids, true ) ) ) {
							continue;
						}
						if ( ( empty( $objects_new_tt_ids[ $object_id ][ $taxonomy ] ) ) || ( ! in_array( $tt_id, $objects_new_tt_ids[ $object_id ][ $taxonomy ], true ) ) ) { 
							//Fires immediately before an object-term relationship is added.
							$objects_new_tt_ids[ $object_id ][ $taxonomy ][] = $tt_id;
							do_action( 'add_term_relationship', $object_id, $tt_id, $taxonomy );
							$insert_tt_placeholders[] = $wpdb->prepare( '(%d, %d)', $object_id, $tt_id );
						}
					}
					// Collect old term relationships for deletion if not appending.
					if ( ( ! $append ) && ( ! empty( $object_existing_tt_ids ) ) ) { 
						foreach ( $object_existing_tt_ids as $old_tt_id ) {
							if ( ( empty( $old_tt_id ) ) || ( empty( $tt_ids[ $taxonomy ] ) ) || ( in_array( $old_tt_id, $tt_ids[ $taxonomy ], true ) ) ) {
								continue;
							}
							if ( ( empty( $objects_delete_tt_ids[ $object_id ][ $taxonomy ] ) ) || ( ! in_array( $old_tt_id, $objects_delete_tt_ids[ $object_id ][ $taxonomy ], true ) ) ) { 
								$delete_tt_placeholders[] = $wpdb->prepare( '(%d, %d)', $object_id, $old_tt_id );
								$objects_delete_tt_ids[ $object_id ][ $taxonomy ][] =  $old_tt_id;
							}
							if ( ( empty( $delete_tt_ids[ $taxonomy ] ) ) || ( ! in_array( $old_tt_id, $delete_tt_ids[ $taxonomy ], true ) ) ) { 
								$delete_tt_ids[ $taxonomy ][] = $old_tt_id;
							}
						}
					}
					if ( ( empty( $taxonomy_data['term_ids_remove'] ) ) || ( ! is_array( $taxonomy_data['term_ids_remove'] ) ) ) {
						if( ( ! empty( $objects_delete_tt_ids[ $object_id ][ $taxonomy ] ) ) ) {
							do_action( 'delete_term_relationships', $object_id, $objects_delete_tt_ids[ $object_id ][ $taxonomy ], $taxonomy );
						}
						continue;
					}
					foreach ( $taxonomy_data['term_ids_remove'] as $t_id ) {
						if ( empty( $t_id ) ) {
							continue;
						}
						$tt_id = empty( $taxonomy_data['remove_all_terms'] ) ? ( ( is_array( $terms_taxonomy_ids ) && ! empty( $terms_taxonomy_ids[ $t_id ] ) ) ? absint( $terms_taxonomy_ids[ $t_id ] ) : 0 ) : absint( $t_id );
						if ( ( empty( $tt_id ) ) || ( ! in_array( $tt_id, $object_existing_tt_ids ) ) ) {
							continue;
						}
						if ( ( ! in_array( $tt_id, $objects_delete_tt_ids[ $object_id ][ $taxonomy ], true ) ) ) { 
							$delete_tt_placeholders[] = $wpdb->prepare( '(%d, %d)', $object_id, $tt_id );
							$objects_delete_tt_ids[ $object_id ][ $taxonomy ][] = $tt_id;
						}
						if ( ( empty( $delete_tt_ids[ $taxonomy ] ) ) || ( ! in_array( $tt_id, $delete_tt_ids[ $taxonomy ], true ) ) ) { 
							$delete_tt_ids[ $taxonomy ][] = $tt_id;
						}
					}
					if( ( ! empty( $objects_delete_tt_ids[ $object_id ][ $taxonomy ] ) ) ) {
						do_action( 'delete_term_relationships', $object_id, $objects_delete_tt_ids[ $object_id ][ $taxonomy ], $taxonomy );
					}
				}
			}

			$all_tts = array(); //for updating counts of the taxonomies.
			foreach ( $taxonomies as $taxonomy ) {
				if ( empty( $tt_ids[ $taxonomy ] ) && empty( $delete_tt_ids[ $taxonomy ] ) ) {
					continue;
				}
				$taxonomy_count_data[$taxonomy]  = array();
				if ( ( ! empty( $tt_ids[ $taxonomy ] ) ) ) {
					$all_tts = array_merge( $all_tts, $tt_ids[ $taxonomy ] );
					$taxonomy_count_data[$taxonomy] = array_merge( $taxonomy_count_data[$taxonomy], $tt_ids[ $taxonomy ] );
				}
				if ( ( ! empty( $delete_tt_ids[ $taxonomy ] ) ) ) {
					$all_tts = array_merge( $all_tts, $delete_tt_ids[ $taxonomy ] );
					$taxonomy_count_data[$taxonomy] = array_merge( $taxonomy_count_data[$taxonomy], $delete_tt_ids[ $taxonomy ] );
				}
			}
			if ( empty( $all_tts ) ) {
				return;
			}
			$all_tts = array_unique( $all_tts );
			// Perform bulk insert.
			if ( ( ! empty( $insert_tt_placeholders ) ) ) {
				$insert_result = $wpdb->query( "INSERT INTO $wpdb->term_relationships (object_id, term_taxonomy_id) VALUES " . implode( ',', $insert_tt_placeholders ) . " ON DUPLICATE KEY UPDATE term_taxonomy_id = term_taxonomy_id" );
			}
			// Perform bulk delete.
			if ( ( ! empty( $delete_tt_placeholders ) ) ) {
				$delete_result = $wpdb->query( "DELETE FROM $wpdb->term_relationships WHERE (object_id, term_taxonomy_id) IN (" . implode( ',', $delete_tt_placeholders ) . ")" );
			}
			if ( ( empty( $delete_result ) ) && ( empty( $insert_result ) ) ) {
				return;
			}
			//Remove the WC action.
			if ( class_exists( 'WooCommerce' ) ) {
				remove_action( 'set_object_terms', 'wc_clear_term_product_ids', 10 );
			}
			//fire add and delete terms post actions.
			foreach ( $posts_params_arr as $object_id => $params ) {
				if( ( empty( $object_id ) ) || ( empty( $params ) ) || ( ! is_array( $params ) ) ) {
					continue;
				}
				foreach ( $params as $taxonomy => $taxonomy_data ) {
					if ( ( empty( $taxonomy_data ) ) || ( empty( $taxonomy ) )  || ( empty( $taxonomy_data[ 'term_ids_set' ] ) && empty( $taxonomy_data[ 'term_ids_remove' ] ) ) ) {
						continue;
					}
					// $existing_relationships
					$taxonomy_old_tt_ids = ( ( ! empty( $existing_relationships[ $object_id ] ) ) && ( ! empty( $existing_relationships[ $object_id ][ $taxonomy ] ) ) ) ? $existing_relationships[ $object_id ][ $taxonomy ] : array();
					//fire add terms post action for each term.
					if ( ( ! empty( $insert_result ) ) && ( ! is_wp_error( $insert_result ) ) && ( ! empty( $objects_new_tt_ids[ $object_id ] ) ) && ( ! empty( $objects_new_tt_ids[ $object_id ][ $taxonomy ] ) ) ) {
						foreach ( $objects_new_tt_ids[ $object_id ][ $taxonomy ] as $tt_id ) { 
							do_action( 'added_term_relationship', $object_id, $tt_id, $taxonomy );
							if ( empty( $task_id ) ) {
								continue;
							}
							Smart_Manager_Base::$update_task_details_params[] = array(
								'task_id' => $task_id,
								'action' => 'remove_from',
								'status' => 'completed',
								'record_id' => $object_id ,
								'field' => 'terms/'.$taxonomy,  
								'prev_val' => $tt_id,
								'updated_val' => $taxonomy_old_tt_ids,
							);
						}
					}
					if ( ( ! empty( $objects_tt_ids ) ) && ( ! empty( $objects_tt_ids[ $object_id ] ) ) && ( ! empty( $objects_tt_ids[ $object_id ][ $taxonomy ] ) ) ) {
						do_action( 'set_object_terms', $object_id, $taxonomy_data[ 'term_ids_set' ], $objects_tt_ids[ $object_id ][ $taxonomy ], $taxonomy, $taxonomy_data[ 'append' ], $taxonomy_old_tt_ids );//check
					}
					//fire delete terms post action.
					if( ( ! empty( $delete_result ) ) && ( ! is_wp_error( $delete_result ) ) && ( ! empty( $delete_tt_ids ) ) ) {
						if ( empty( $objects_delete_tt_ids ) || ( empty( $objects_delete_tt_ids[ $object_id ] ) ) || ( empty( $objects_delete_tt_ids[ $object_id ][ $taxonomy ] ) ) ) {
							continue;
						}
						
						do_action( 'deleted_term_relationships', $object_id, $objects_delete_tt_ids[ $object_id ][ $taxonomy ], $taxonomy );
						wp_cache_delete( $object_id, $taxonomy . '_relationships' );
						if ( empty( $task_id ) ) {
							continue;
						}
						foreach ( $objects_delete_tt_ids[ $object_id ][ $taxonomy ] as $delete_tt_id ) {
							Smart_Manager_Base::$update_task_details_params[] = array(
								'task_id' => $task_id,
								'action' => 'add_to',
								'status' => 'completed',
								'record_id' => $object_id ,
								'field' => 'terms/'.$taxonomy,  
								'prev_val' => $delete_tt_id,
								'updated_val' =>  $taxonomy_old_tt_ids,
							);
						}
					}
				}
			}
			// update terms count.
			self::update_term_count( $taxonomy_count_data );
			do_action( 'sm_pro_post_process_terms_update', $all_tts );
			//clear cache.
			wp_cache_set_terms_last_changed();
			return array(
				'status'=>true,
				'existing_relationships'=>$existing_relationships,
			);
		}

		/**
		 * Deletes multiple metadata entries in bulk.
		 *
		 * @param string $meta_type  The type of object metadata is for (e.g., 'post', 'user').
		 * @param array  $meta_data  An array of metadata to delete. Each item should be an associative array with keys:
		 *                           'object_id', 'meta_key', 'meta_value' (optional).
		 * @return bool|null True on success else null 
		*/
		public static function delete_metadata( $args = array() ) {
			if ( ( empty( $args ) ) || ( ! is_array( $args ) ) || (  empty( $args[ 'meta_type' ] ) ) || (  empty( $args[ 'meta_data' ] ) ) || (  ! is_array( $args[ 'meta_data' ] ) ) ) {
				return;
			}
			$meta_type = $args[ 'meta_type' ];
			$meta_data = $args[ 'meta_data' ];
			global $wpdb;
			if ( empty( $meta_type ) || empty( $meta_data ) ) {
				return;
			}
			$table = _get_meta_table( $meta_type );
			if ( ! $table ) {
				return;
			}
			$type_column = sanitize_key( $meta_type . '_id' );
			$id_column   = ( 'user' === $meta_type ) ? 'umeta_id' : 'meta_id';
			// Prepare query for bulk selection.
			$select_placeholders = array();
			$select_params     = array();
			$object_ids = array();
			foreach ( $meta_data as $data ) {
				$object_id  = ( ! empty( $data['object_id'] ) ) ? absint( $data['object_id'] ) : 0;
				$meta_key   = ( ! empty( $data['meta_key'] ) ) ? wp_unslash( $data['meta_key'] ) : '';
				if ( empty( $object_id ) || empty( $meta_key ) ) {
					continue;
				}
				$meta_value = ( ! empty( $data['meta_value'] ) ) ? maybe_serialize( wp_unslash( $data['meta_value'] ) ) : '';
				// Short-circuit filter
				$check = apply_filters( "delete_{$meta_type}_metadata", null, $object_id, $meta_key, $meta_value, false );
				if ( null !== $check ) {
					continue;
				}
				// Add conditions for query building.
				$select_placeholders[] = "(meta_key = %s AND $type_column = %d" . ( ( ! empty( $meta_value ) ) ? " AND meta_value = %s" : "" ) . ")";
				$select_params[]       = $meta_key;
				$select_params[]       = $object_id;
				if ( ( ! empty( $meta_value ) ) ) {
					$select_params[] = $meta_value;
				}
			}
			if ( empty( $select_placeholders ) ) {
				return;
			}
			//Run query to fetch meta data
			$object_id_col = $meta_type . "_id";
			$query = "SELECT " . str_replace($object_id_col, "$object_id_col AS object_id", implode(', ', array ( 'meta_id', 'meta_key', 'meta_value', $object_id_col ) ) ) . " FROM $table WHERE " . implode( ' OR ', $select_placeholders );
			$result = $wpdb->get_results( $wpdb->prepare( $query, $select_params ) );
			if ( ( empty( $result ) ) || ( is_wp_error( $result ) ) ) {
				return;
			}
			//map data for post and pre actions.
			$grouped_meta_data = self::group_meta_data_to_delete( $result );
			if ( empty( $grouped_meta_data ) || ( ! is_array( $grouped_meta_data ) ) || empty( $grouped_meta_data['meta_ids_to_delete'] ) || empty( $grouped_meta_data['meta_data_for_actions'] ) ) {
				return;
			}
			$meta_ids_to_delete = ( ! empty( $grouped_meta_data['meta_ids_to_delete'] ) ) ? $grouped_meta_data['meta_ids_to_delete'] : array();
			$meta_data_for_actions = ( ! empty( $grouped_meta_data['meta_data_for_actions'] ) ) ? $grouped_meta_data['meta_data_for_actions'] : array();
			//Fire pre actions.
			foreach ( $meta_data_for_actions as $data ) {
				if ( ( empty( $data ) ) || ( empty( $data['meta_ids'] ) ) || ( empty($data['object_id']) ) || ( empty( $data['meta_key'] ) ) || ( ! isset( $data['meta_value'] ) ) ) {
					continue;
				}
				$object_ids[] = $object_id; 
				do_action( "delete_{$meta_type}_meta", $data['meta_ids'], $data['object_id'], $data['meta_key'], $data['meta_value'] );
				if ( 'post' === $meta_type ) {
					do_action( 'delete_postmeta', $data['meta_ids'] );
				}
			}
			// Run delete query.
			$query = "DELETE FROM $table WHERE $id_column IN ( " . implode( ',', array_map( 'absint', $meta_ids_to_delete ) ) . " )";
			$result = $wpdb->query( $query );
			if ( empty( $result ) || ( is_wp_error( $result ) ) ) {
				return;
			}
			//clear cache.
			if ( ( ! empty( $object_ids ) ) ) {
				wp_cache_delete_multiple( $object_ids, $meta_type . '_meta' );
			}
			//Fire post actions.
			foreach ( $meta_data_for_actions as $data ) {
				if ( ( empty( $data ) ) || ( empty( $data['meta_ids'] ) ) || ( empty( $data['object_id'] ) ) || ( empty( $data['meta_key'] ) ) || ( ! isset( $data['meta_value'] ) ) ) {
					continue;
				}
				do_action( "deleted_{$meta_type}_meta", $data['meta_ids'], $data['object_id'], $data['meta_key'], $data['meta_value'] );
				if ( 'post' === $meta_type ) {
					do_action( 'deleted_postmeta', $data['meta_ids'] );
				}
			}
			return true;
		}

		/**
		 * Deletes multiple metadata entries in bulk.
		 *
		 * @param object $meta_objects  The post metadata.
		 * 
		 * @return array|void array of grouped meta data else void 
		*/
		public static function group_meta_data_to_delete( $meta_objects = array() ) {
			if ( ( empty( $meta_objects ) ) || ( ! is_array( $meta_objects ) ) ) {
				return;
			}
			$meta_ids_to_delete    = array();
			$meta_data_for_actions = array();
			foreach ( $meta_objects as $meta ) {
				if ( ( empty( $meta ) ) || ( empty( $meta->meta_id ) ) ) {
					continue;
				}
				$key = $meta->meta_key . '|' . $meta->meta_value . '|' . $meta->object_id;
				$meta_data_for_actions[ $key ]['meta_key']   = ( ! empty( $meta->meta_key ) ) ? sanitize_key( $meta->meta_key ) : '' ;
				$meta_data_for_actions[ $key ]['meta_value'] = ( isset( $meta->meta_value ) ) ? maybe_serialize( $meta->meta_value ) : '' ;
				$meta_data_for_actions[ $key ]['object_id']  = ( ! empty( $meta->object_id ) ) ? absint( $meta->object_id ) : 0 ;
				$meta_data_for_actions[ $key ]['meta_ids'][] = ( ! empty( $meta->meta_id ) ) ? absint( $meta->meta_id ) : 0 ;
				$meta_ids_to_delete[]                        =  $meta->meta_id;
			}
			return array(
				'meta_data_for_actions' => ( ! empty( $meta_data_for_actions ) ) ? array_values( $meta_data_for_actions ) : array(),
				'meta_ids_to_delete' => array_unique( $meta_ids_to_delete )
			);
		}

		/**
		 * Function to update posts count for terms of the taxonomy.
		 *
		 * @param array $taxonomy_count_data  array taxonomy data containing terms.
		 *
		 * @return void
		*/
		public static function update_term_count( $taxonomy_count_data = array() ) {
			if( ( empty( $taxonomy_count_data ) ) || ( ! is_array( $taxonomy_count_data ) ) ){
				return;
			}
			//update terms count for each taxonomy.
			foreach ($taxonomy_count_data as $taxonomy => $terms) {
				if( ( empty( $terms ) ) || ( empty( $taxonomy ) ) || ( ! is_array( $terms ) ) ){
					continue;
				}
				$terms = array_map( 'intval', $terms );
			
				$taxonomy = get_taxonomy( $taxonomy );
				if ( ( empty( $taxonomy ) ) ) {
					return;
				}
				if ( ( ! empty( $taxonomy->update_count_callback ) ) ) {
					//handle product taxonomies terms count.
					if ( ( '_wc_term_recount' === $taxonomy->update_count_callback ) && ( class_exists( 'Smart_Manager_Pro_Product' ) ) && ( is_callable( array( 'Smart_Manager_Pro_Product', 'products_taxonomy_term_recount' ) ) ) ) {
						Smart_Manager_Pro_Product::products_taxonomy_term_recount( $terms, $taxonomy );
					}else{
						call_user_func( $taxonomy->update_count_callback, $terms, $taxonomy );
					}
				} else {
					$object_types = (array) $taxonomy->object_type;
					foreach ( $object_types as &$object_type ) {
						if ( str_starts_with( $object_type, 'attachment:' ) ) {
							list( $object_type ) = explode( ':', $object_type );
						}
					}
			
					if ( array_filter( $object_types, 'post_type_exists' ) == $object_types ) {
						// Only post types are attached to this taxonomy.
						self::update_post_term_count( $terms, $taxonomy );
					} else {
						// Default count updater.
						self::update_generic_term_count( $terms, $taxonomy );
					}
				}
				clean_term_cache( $terms, '', false );
			}
		}

		/**
		 * Function to update posts count for terms.
		 *
		 * @param array $terms  array of terms.
		 * @param object  $taxonomy  Taxonomy object.
		 *
		 * @return void
		*/
		public static function update_post_term_count( $terms = array(), $taxonomy = null ) {
			if( ( empty( $terms ) ) || ( empty( $taxonomy ) ) || ( ! is_array( $terms ) ) ){
				return;
			}
			global $wpdb;
		
			$object_types = (array) $taxonomy->object_type;
			
			foreach ( $object_types as &$object_type ) {
				list( $object_type ) = explode( ':', $object_type );
			}
		
			$object_types = array_unique( $object_types );
		
			$check_attachments = array_search( 'attachment', $object_types, true );
			if ( false !== $check_attachments ) {
				unset( $object_types[ $check_attachments ] );
				$check_attachments = true;
			}
			$object_types = esc_sql( array_filter( $object_types, 'post_type_exists' ) );
			$post_statuses = esc_sql(
				apply_filters( 'update_post_term_count_statuses', array( 'publish' ), $taxonomy )
			);
			// Prepare the placeholders for the terms in a single query.
			$placeholders = implode( ',', array_fill( 0, count( $terms ), '%d' ) );
			$counts = array();
			// Query for attachment counts, if applicable.
			if ( $check_attachments ) {
				$attachment_counts = $wpdb->get_results(
					$wpdb->prepare(
						"SELECT term_taxonomy_id, COUNT(*) AS count 
						 FROM $wpdb->term_relationships
						 INNER JOIN $wpdb->posts AS p1 ON p1.ID = $wpdb->term_relationships.object_id 
						 WHERE term_taxonomy_id IN ($placeholders) 
						 AND ( post_status IN ('" . implode( "', '", $post_statuses ) . "') 
							 OR ( post_status = 'inherit' 
							 AND post_parent > 0 
							 AND (SELECT post_status FROM $wpdb->posts WHERE ID = p1.post_parent) IN ('" . implode( "', '", $post_statuses ) . "') ) ) 
						 AND post_type = 'attachment' 
						 GROUP BY term_taxonomy_id",
						$terms
					),
					OBJECT_K
				);
				$counts = array_merge( $counts, $attachment_counts );
			}
			// Query for other object types.
			if ( $object_types ) {
				$post_counts = $wpdb->get_results(
					$wpdb->prepare(
						"SELECT term_taxonomy_id, COUNT(*) AS count 
						 FROM $wpdb->term_relationships
						 INNER JOIN $wpdb->posts ON $wpdb->posts.ID = $wpdb->term_relationships.object_id 
						 WHERE term_taxonomy_id IN ($placeholders) 
						 AND post_status IN ('" . implode( "', '", $post_statuses ) . "') 
						 AND post_type IN ('" . implode( "', '", $object_types ) . "') 
						 GROUP BY term_taxonomy_id",
						$terms
					),
					OBJECT_K
				);
		
				$counts = array_merge_recursive( $counts, $post_counts );
			}
			$updates = [];
			foreach ( (array) $terms as $term ) {
				if ( ( empty( $term ) ) ) {
					continue;
				}
				// Pre-action for the term.
				do_action( 'edit_term_taxonomy', $term, $taxonomy->name );
				$count = 0;
				// Iterate over the array of objects in $counts to sum matching counts.
				foreach ( $counts as $object ) {
					if ( ( ! empty( $object->term_taxonomy_id ) ) && (int) $object->term_taxonomy_id === $term ) {
						$count += ( ! empty( $object->count ) ) ? (int) $object->count : 0;
					}
				}
				// Collect update data.
				$updates[] = $wpdb->prepare( "(%d, %d)", $term, $count );
			}
		
			// Perform bulk update query.
			if ( empty( $updates ) ) { 
				return;
			}
			$query = "
				INSERT INTO $wpdb->term_taxonomy (term_taxonomy_id, count)
				VALUES " . implode( ', ', $updates ) . "
				ON DUPLICATE KEY UPDATE count = VALUES(count)";
			$result = $wpdb->query( $query );
			if ( ( empty( $result ) ) || ( is_wp_error( $result ) ) ) {
				return;
			}
			// Post-action for the term count update.
			foreach ( (array) $terms as $term ) { 
				do_action( 'edited_term_taxonomy', $term, $taxonomy->name );
			}
		}

		/**
		 * Function to update other post types terms count apart from posts .
		 *
		 * @param array $terms  array of terms.
		 * @param object  $taxonomy  Taxonomy object.
		 *
		 * @return void
		*/
		public static function update_generic_term_count( $terms = array(), $taxonomy = null ) {
			if( ( empty( $terms ) ) || ( empty( $taxonomy ) ) || ( ! is_array( $terms ) ) ){
				return;
			}
			global $wpdb;
		
			// Prepare the placeholders for the terms in a single query.
			$placeholders = implode( ',', array_fill( 0, count( $terms ), '%d' ) );
		
			// Fetch counts for all terms in one query.
			$counts = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT term_taxonomy_id, COUNT(*) AS count 
					 FROM $wpdb->term_relationships 
					 WHERE term_taxonomy_id IN ($placeholders) 
					 GROUP BY term_taxonomy_id",
					$terms
				),
				OBJECT_K
			);
		
			$updates = array();
			foreach ( (array) $terms as $term ) {
				if ( ( empty( $term ) ) ) {
					continue;
				}
				$count = 0;
				// Iterate over the array of objects in $counts to sum matching counts.
				foreach ( $counts as $object ) {
					if ( ( empty( $object ) ) ) {
						continue;
					}
					if ( isset( $object->term_taxonomy_id ) && (int) $object->term_taxonomy_id === $term ) {
						$count += isset( $object->count ) ? (int) $object->count : 0;
					}
				}
				// Pre-action for the term.
				do_action( 'edit_term_taxonomy', $term, $taxonomy->name );
		
				// Collect update data.
				$updates[] = $wpdb->prepare( "(%d, %d)", $term, $count );
			}
		
			// Perform bulk update query.
			if ( ! empty( $updates ) ) {
				$query = "INSERT INTO $wpdb->term_taxonomy (term_taxonomy_id, count) VALUES " . implode( ', ', $updates ) . " ON DUPLICATE KEY UPDATE count = VALUES(count)";
				$result = $wpdb->query( $query );
				if ( ( empty( $result ) ) || ( is_wp_error( $result ) ) ) {
					return;
				}
				foreach ( (array) $terms as $term ) {
					// Post-action for the term.
					do_action( 'edited_term_taxonomy', $term, $taxonomy->name );
				}
			}
		}

		/* Function to get terms undo details
		 *
		 * @param array $existing_relationships
		 * @param array $args
		 * 
		 * @return void|array{action: string, prev_val: mixed, updated_val: mixed}
		*/
		public static function get_terms_undo_details( $existing_relationships = array(), $args = array() ) {
			if ( ( empty( $args[ 'col_nm' ] ) ) || ( empty( $existing_relationships ) ) || ( empty( $existing_relationships[ $args[ 'id' ] ] ) ) || empty( $existing_relationships[ $args[ 'id' ] ][ $args[ 'col_nm' ] ] ) ) {
				return;
			}
			$action = 'set_to';
			$prev_vals = $existing_relationships[ $args[ 'id' ] ][ $args[ 'col_nm' ] ];
			if ( ( 'remove_from' === $args['operator'] ) ) {
				if ((is_array($prev_vals)) && ( ! in_array($args['value'],$prev_vals) )) {
					return;
				}
				$action = 'add_to';
			}
			if ( ( 'add_to' === $args['operator'] ) ) {
				if ( ( is_array( $prev_vals ) ) && ( in_array( $args['value'], $prev_vals ) ) ) {
					return;
				}
				$action = 'remove_from';
			}
			return(
				array(
					'action'=>$action,
					'prev_val'=>$prev_vals,
					'updated_val'=>$args['value'],
				)
			);
		}
		
		/**
		 * Function to get Term By ID form the array of term objects.
		 *
		 * @param array $terms  array of term objects.
		 * @param int  $term_id  ID of the terms to get.
		 *
		 * @return object|void term object on success else void.
		*/
		public static function get_term_by_id( $terms = array(), $term_id = 0 ) {
			if ( empty( $terms ) || empty( $term_id ) || ( ! is_array( $terms ) ) ) {
				return;
			}
			foreach ( $terms as $term ) {
				if ( empty( $term ) ) {
					continue;
				}
				if ( ( ! empty( $term->term_id ) ) && ( absint( $term->term_id ) === absint( $term_id ) ) ) {
					return $term;
				}
			}
		}

		/**
	     * Function to map inline terms update data.
		 * 
		 * @param  array $args array data.
		 * 
		 * @return array $args array data.
		 */
		public static function map_inline_terms_update_data( $args = array() ) {
			if ( ( empty( $args ) ) || ( ! is_array( $args ) ) || ( empty( $args[ 'id' ] ) ) || ( ! isset( $args[ 'taxonomies' ] ) ) || ( ! is_array( $args[ 'taxonomies' ] ) ) || ( ! isset( $args[ 'value' ] ) ) || ( empty ( $args[ 'term_ids' ] ) ) || ( empty ( $args[ 'update_column' ] ) ) ) {
				return $args;
			}
			if( ( ! in_array( $args[ 'update_column' ], $args[ 'taxonomies' ] ) ) ){
				$args[ 'taxonomies' ][] = $args[ 'update_column' ];
			}
			if ( ( empty( absint( $args[ 'value' ] ) ) ) ) {
				$default_term = absint( get_option( 'default_'.$args[ "update_column" ], 0 ) );
				if ( ( ! empty( $default_term ) ) ) {
					$args[ 'term_ids' ] = array( $default_term );
				}
			}
			$args[ 'taxonomy_data_to_update' ][ $args['id'] ][ $args[ 'update_column' ] ] = array( 
				'term_ids_set' => $args[ 'term_ids' ],
				'taxonomy' => $args[ 'update_column' ], 
				'append' => false,
				'remove_all_terms' => ( empty( absint( $args[ 'value' ] ) ) ) ? true : false,
			);
			return $args;
		}

		/**
		 * Decreases the given value by a specified percentage.
		 *
		 * @param float|int $prev_val The initial value before the decrease.
		 * @param float|int $per The percentage by which to decrease the initial value.
		 * @return int The value after the decrease.
		 */
		public static function decrease_value_by_per( $prev_val = 0, $per = 0 ) {
			if ( ( empty( $prev_val ) ) || ( empty( $per ) ) ) {
				return $prev_val;
			}
			return round( ( $prev_val - ( $prev_val * ( $per / 100 ) ) ), apply_filters( 'sm_beta_pro_num_decimals', get_option( 'woocommerce_price_num_decimals' ) ) );
		}

		/**
		 * Decreases the given value by a specified number.
		 *
		 * @param float|int $prev_val The initial value before decrease. Default is 0.
		 * @param float|int $num The number to decrease the initial value by. Default is 0.
		 * @return int The resulting value after decrease.
		 */
		public static function decrease_value_by_num( $prev_val = 0, $num = 0 ) {
			if ( empty( $prev_val ) || empty( $num ) ) {
				return $prev_val;
			}
			return round( ( $prev_val - $num ), apply_filters( 'sm_beta_pro_num_decimals', get_option( 'woocommerce_price_num_decimals' ) ) );
		}

		/**
		 * Generates an advanced search query for scheduled exports.
		 *
		 * When order statuses are provided in the parameters, the query will include a separate
		 * condition for each order status along with the date range.
		 * @param array  $args Array of arguments.
		 *                               
		 * @return string JSON-encoded advanced search query.
		 */
		public static function get_scheduled_exports_advanced_search_query( $args = array() ) {
			if ( ( empty( $args ) ) || ( ! is_array( $args ) ) || ( empty( $args['interval_days'] ) ) || ( empty( $args['table_nm'] ) ) || ( empty( $args['date_col'] ) ) ) {
				return '';
			}
			global $wpdb;
			// Get the export date range.
			$date_range = self::get_scheduled_export_date_range( (int) $args['interval_days'] );
			if ( ( empty( $date_range ) ) || ( ! is_array( $date_range ) ) || ( empty( $date_range['start_date'] ) ) || ( empty( $date_range['end_date'] ) ) ) {
				return '';
			}
			// Determine if order statuses are provided and not empty.
			if ( ! empty( $args['order_statuses'] ) && is_array( $args['order_statuses'] ) && ( ! empty( $args['status_col'] ) ) ) {
				$rules = array();
				// Build a separate AND block for each order status.
				foreach ( $args['order_statuses'] as $status ) {
					$rules[] = array(
						'condition' => 'AND',
						'rules'     => array(
							array(
								'type'     => $wpdb->prefix . $args['table_nm'] . '.' . $args['status_col'],
								'operator' => 'is',
								'value'    => $status,
							),
							array(
								'type'     => $wpdb->prefix . $args['table_nm'] . '.' . $args['date_col'],
								'operator' => 'gte',
								'value'    => $date_range['start_date'],
							),
							array(
								'type'     => $wpdb->prefix . $args['table_nm'] . '.' . $args['date_col'],
								'operator' => 'lte',
								'value'    => $date_range['end_date'],
							),
						),
					);
				}
				return wp_json_encode( 
					array(
						array(
							'condition' => 'OR',
							'rules'     => $rules,
						),
					)
				);
			} else {
				// If no order statuses are provided, only use the date range.
				return wp_json_encode( 
					array(
						array(
							'condition' => 'OR',
							'rules'     => array(
								array(
									'condition' => 'AND',
									'rules'     => array(
										array(
											'type'     => $wpdb->prefix . $args['table_nm'] . '.' . $args['date_col'],
											'operator' => 'gte',
											'value'    => $date_range['start_date'],
										),
										array(
											'type'     => $wpdb->prefix . $args['table_nm'] . '.' . $args['date_col'],
											'operator' => 'lte',
											'value'    => $date_range['end_date'],
										),
									),
								),
							),
						),
					) 
				);
			}
		}

		/**
		 * Calculates the start and end date range for exporting orders based on run time and interval.
		 *
		 * @param int    $interval_days  The number of past days to include in the export.
		 * @param string $end_date_time  The scheduled run time in 'Y-m-d H:i:s' format.
		 * @return array                 Associative array with 'start_date' and 'end_date'.
		*/
		public static function get_scheduled_export_date_range( $interval_days = 0, $end_date_time = '' ) {
			$interval_days = intval( $interval_days );
			if ( ( empty( $interval_days ) ) ) {
				return;
			}
			if ( ( empty( $end_date_time  ) ) ) {
				$end_date_time = current_time( 'Y-m-d H:i:s' );
			}
			// Convert GMT offset (in hours) to seconds.
			$offset = (float)get_option( 'gmt_offset', 0 ) * HOUR_IN_SECONDS;
			$timestamp = strtotime( $end_date_time  );
			if ( ! $timestamp ) {
				return;
			}
			return array(
				'start_date' => gmdate( 'Y-m-d', strtotime( "-{$interval_days} days", $timestamp - $offset ) + $offset ) . ' 00:00:00',
				'end_date'   => gmdate( 'Y-m-d', strtotime( '-1 days', $timestamp - $offset ) + $offset ) . ' 23:59:59',
			);
		}

		/**
		 * Sends an email using WooCommerce's `wc_mail` if available,
		 * otherwise falls back to WordPress's `wp_mail`.
		 *
		 * @param array $args {
		 *     @type string $subject Email subject.
		 *     @type string $email   Recipient's email address.
		 *     @type string $message Email body content.
		 * }
		 * @return void
		 */
		public static function send_email( $args = array() ) {
			if( ( empty( $args ) ) || ( ! is_array( $args ) ) || ( empty( $args['subject'] ) ) || ( empty( $args['email'] ) ) || ( empty( $args['message'] ) ) ) {
				return;
			}
			if( function_exists( 'wc_mail' ) ) {
				wc_mail( sanitize_email( $args['email'] ), $args['subject'], $args['message'] );
			} elseif( function_exists( 'wp_mail' ) ) {
				wp_mail( sanitize_email( $args['email'] ), $args['subject'], $args['message'] );
			}
		}

		/**
		 * Processes a scheduled export CSV file and sends an email with the download link.
		 *
		 * @param array $args Parameters for processing the CSV export.
		 * @return void
		*/
		public static function process_scheduled_csv_email_export( $args = array() ) {
			if ( ( empty( $args ) ) || ( ! is_array( $args ) ) || ( empty( $args['scheduled_export_params'] ) ) || ( empty( $args['csv_file_name'] ) ) || ( empty( $args['file_data'] ) ) || ( empty( $args['file_data']['upload_dir'] ) ) || ( ! is_array( $args['file_data']['upload_dir'] ) ) || ( empty( $args['file_data']['file_content'] ) ) || ( empty( $args['scheduled_export_params']['schedule_export_email'] ) ) ) {
				Smart_Manager::log( 'error', _x( 'Export CSV: Missing required CSV file data.', 'process scheduled export file data', 'smart-manager-for-wp-e-commerce' ) );
				return;
			}
			$csv_upload_dir = trailingslashit( $args['file_data']['upload_dir']['basedir'] ) . 'woocommerce_uploads/';
			if ( ( ! file_exists( $csv_upload_dir ) ) ) {
				if ( false === wp_mkdir_p( $csv_upload_dir ) ) {
					/* translators: %s: Directory path */
					Smart_Manager::log( 'error', sprintf( _x( 'Export CSV: unable to create directory %s', 'export file data', 'smart-manager-for-wp-e-commerce' ), $csv_upload_dir ) );
					return;
				};
			}
			$csv_file_name  = sanitize_file_name( $args['csv_file_name'] );
			$full_file_path = trailingslashit( $csv_upload_dir ) . $csv_file_name;
			//check file write permissions.
			if ( false === file_put_contents( $full_file_path, $args['file_data']['file_content'] ) ) {
				/* translators: %s: File path */
				Smart_Manager::log( 'error', sprintf( _x( 'Export CSV: unable to write file to %s', 'process scheduled export file data', 'smart-manager-for-wp-e-commerce' ), $full_file_path ) );
				return;
			}

			$filetype = wp_check_filetype( $csv_file_name, null );
			if ( ( empty( $filetype ) ) || ( ! is_array( $filetype ) ) || ( empty( $filetype['type'] ) ) ) {
				Smart_Manager::log( 'error', _x( 'Export CSV: error in checking file type', 'process scheduled export file data', 'smart-manager-for-wp-e-commerce' ) );
				return;
			}
			$attachment_id = wp_insert_attachment( array(
				'guid'           => trailingslashit( $args['file_data']['upload_dir']['baseurl'] ) . 'woocommerce_uploads/' . $csv_file_name,
				'post_mime_type' => $filetype['type'],
				'post_title'     => $csv_file_name,
				'post_status'    => 'inherit'
			), $full_file_path );

			if ( ( empty( $attachment_id ) ) || ( is_wp_error( $attachment_id ) ) ) {
				/* translators: %s: File path */
				Smart_Manager::log( 'error', sprintf( _x( 'Export CSV: failed to insert attachment for file %s', 'process scheduled export file data', 'smart-manager-for-wp-e-commerce' ), $full_file_path ) );
				return;
			}
			// Update attachment meta to mark as a scheduled export file.
			update_post_meta( $attachment_id, 'sa_sm_is_scheduled_export_file', true );
			//generate media URL and send email.
			$csv_url  = wp_get_attachment_url( $attachment_id );
			if( empty( $csv_url  ) ) {
				Smart_Manager::log( 'error', _x( 'Export CSV: error in getting csv url', 'process scheduled export file data', 'smart-manager-for-wp-e-commerce' ) );
				return;
			}
			// Preparing email content.
			$site_name = get_bloginfo();
			$date = date_i18n( get_option( 'date_format' ), current_time( 'timestamp' ) );
			$email_subject = sprintf(/* translators: 1: Site title, 2: Date */
				_x( 'Your Scheduled Orders Export from %1$s on %2$s Is Ready', 'Email subject for scheduled export', 'smart-manager-for-wp-e-commerce' ),
				$site_name,
				$date
			);
			ob_start();
			include( apply_filters( 'sm_beta_pro_scheduled_export_email_template', SM_PRO_EMAIL_TEMPLATE_PATH.'/scheduled-export.php' ) );
			$email_message = ob_get_clean();
			//send email.
			self::send_email( array(
				'email' => sanitize_email( $args['scheduled_export_params']['schedule_export_email'] ),
				'subject' => ( ! empty( $email_subject ) ) ? $email_subject : '',
				'message' => ( ! empty( $email_message ) ) ? $email_message : ''
			) );
		}

		/**
		 * Schedule scheduled exports file deletion after x number of days
		 *
		 * @return void
		 */
		public static function schedule_scheduled_exports_cleanup() {
			if ( ! function_exists( 'as_schedule_recurring_action' ) ||  ! function_exists( 'as_next_scheduled_action' ) ) {
				return;
			}
			if ( as_next_scheduled_action( 'storeapps_smart_manager_scheduled_export_cleanup' ) ) {
				return;
			}
			$file_deletion_days = intval( get_option( 'sa_sm_scheduled_export_file_expiration_days' ) );
			if ( empty( $file_deletion_days ) ) {
				$file_deletion_days = intval( apply_filters( 'sa_sm_scheduled_export_file_expiration_days', 30 ) );
				if ( empty( $file_deletion_days ) ) {
					return;
				}
				update_option( 'sa_sm_scheduled_export_file_expiration_days', $file_deletion_days, 'no' );
			}
			$timestamp = strtotime( date('Y-m-d H:i:s', strtotime( "+".$file_deletion_days." Days" ) ) );
			if ( empty( $timestamp ) ) {
				return;
			}
			// Schedule the recurring action to run daily.
			as_schedule_recurring_action( $timestamp, DAY_IN_SECONDS, 'storeapps_smart_manager_scheduled_export_cleanup' );
		}

		/**
		 * Retrieve parameters needed to create a scheduled export action.
		 *
		 * @param array $params Input parameters.
		 *
		 * @return array Filtered parameters for the scheduled export action.
		 */
		public static function get_scheduled_export_action_params( $params = array() ) {
			if ( empty( $params ) || ! is_array( $params ) ) {
				return;
			}
			return array_intersect_key( $params, array_flip( array(
				'action',
				'cmd',
				'active_module',
				'pro',
				'SM_IS_WOO30',
				'is_scheduled_export',
				'scheduled_export_params',
				'class_nm',
				'class_path',
				'dashboard_key',
				'table_model',
				'sort_params'
			) ) );
		}

		/**
		 * Get term_taxonomy_ids for the given term_ids using raw SQL.
		 *
		 * @param array $term_ids Array of term IDs.
		 * @return array List of term_taxonomy_id.
		 */
		public static function get_term_taxonomy_ids_by_term_ids( $term_ids = array() ) {
			if ( empty( $term_ids ) || ! is_array( $term_ids ) ) {
				return;
			}
			global $wpdb;
			$term_ids = array_unique( array_map( 'absint', $term_ids ) );
			return array_column( $wpdb->get_results( $wpdb->prepare(
				"SELECT term_id, term_taxonomy_id FROM {$wpdb->term_taxonomy} WHERE term_id IN (" . implode( ',', array_fill( 0, count( $term_ids ), '%d' ) ) . ")",
				...$term_ids
			), ARRAY_A ), 'term_taxonomy_id', 'term_id' );
		}
	}
}
