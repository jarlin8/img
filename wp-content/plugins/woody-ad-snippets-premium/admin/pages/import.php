<?php
/**
 * This class is implemented page: import in the admin panel.
 *
 * @author        Alex Kovalev <alex.kovalevv@gmail.com>
 * @since         1.0.0
 * @package       core
 * @copyright (c) 2018, OnePress Ltd
 *
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Common Settings
 */
class WASP_Import_Page extends WINP_Page {

	/**
	 * WASP_Import_Page constructor.
	 *
	 * @param WINP_Plugin $plugin
	 */
	public function __construct( WINP_Plugin $plugin ) {
		$this->menu_post_type = WINP_SNIPPETS_POST_TYPE;

		$this->id         = 'import';
		$this->menu_title = __( 'Import/Export', 'insert-php' );

		parent::__construct( $plugin );

		$this->plugin = $plugin;
	}

	public function assets( $scripts, $styles ) {
		$this->scripts->request( 'jquery' );

		$this->scripts->request( [
			'control.checkbox',
			'control.dropdown',
		], 'bootstrap' );

		$this->styles->request( [
			'bootstrap.core',
			'bootstrap.form-group',
			'bootstrap.separator',
			'control.dropdown',
			'control.checkbox',
		], 'bootstrap' );

		$this->styles->add( WASP_PLUGIN_URL . '/admin/assets/css/import.css' );
	}

	/**
	 * Get snippet data
	 *
	 * @return array
	 */
	private function getSnippetData() {
		$results = [];
		$types   = [];
		$tags    = [];

		$snippets = get_posts( [
			'post_type'   => WINP_SNIPPETS_POST_TYPE,
			'post_status' => 'publish',
			'numberposts' => - 1,
		] );

		if ( ! empty( $snippets ) ) {
			$results['tags'] = [ [ '__all', __( 'Select all', 'insert-php' ) ] ];

			foreach ( (array) $snippets as $snippet ) {
				$snippet_type = WINP_Helper::get_snippet_type( $snippet->ID );
				if ( ! isset( $types[ $snippet_type ] ) ) {
					$types[ $snippet_type ] = 1;
				} else {
					$types[ $snippet_type ] ++;
				}

				$terms = wp_get_post_terms( $snippet->ID, WINP_SNIPPETS_TAXONOMY );

				if ( ! empty( $terms ) ) {
					foreach ( (array) $terms as $snippet_tag ) {
						if ( ! isset( $tags[ $snippet_tag->slug ] ) ) {
							$tags[ $snippet_tag->slug ] = 1;
						} else {
							$tags[ $snippet_tag->slug ] ++;
						}
					}
				}
			}

			foreach ( $types as $snippet_type => $count ) {
				$results['types'][]   = [ $snippet_type, $snippet_type . ' (' . $count . ')' ];
				$results['all_types'] = implode( ',', array_keys( $types ) );
			}

			foreach ( $tags as $tag => $count ) {
				$results['tags'][]   = [ $tag, $tag . ' (' . $count . ')' ];
				$results['all_tags'] = implode( ',', array_keys( $tags ) );
			}
		}

		return $results;
	}

	/**
	 * Returns options for the Basic Settings screen.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function getOptions() {
		$options = [];

		$options[] = [
			'type' => 'html',
			'html' => '<h3 style="margin-left:0">' . __( 'Status', 'insert-php' ) . '</h3>',
		];

		$options[] = [
			'type'    => 'dropdown',
			'name'    => 'status',
			'way'     => 'buttons',
			'data'    => [
				[
					'active',
					__( 'Active', 'insert-php' ),
				],
				[
					'notactive',
					__( 'Not active', 'insert-php' ),
				],
				[
					'all',
					__( 'All', 'insert-php' ),
				],
			],
			'default' => 'all',
			'hint'    => __( 'Status for snippets', 'insert-php' ),
		];

		$options[] = [
			'type' => 'html',
			'html' => '<h3 style="margin-left:0">' . __( 'Snippet types', 'insert-php' ) . '</h3>',
		];

		$data = $this->getSnippetData();

		$options[] = [
			'type'  => 'list',
			'name'  => 'types',
			'way'   => 'checklist',
			'data'  => $data['types'],
			'value' => $data['all_types'],
			'hint'  => __( 'Types of snippets', 'insert-php' ),
		];

		$options[] = [
			'type' => 'html',
			'html' => '<h3 style="margin-left:0">' . __( 'Tags', 'insert-php' ) . '</h3>',
		];

		$options[] = [
			'type'  => 'list',
			'name'  => 'tags',
			'way'   => 'checklist',
			'data'  => $data['tags'],
			'value' => ( $data['all_tags'] ? '__all,' . $data['all_tags'] : '' ),
			'hint'  => __( 'Snippet tags', 'insert-php' ),
		];

		return $options;
	}

	private function getMessage() {
		$wbcr_inp_error    = WINP_Plugin::app()->request->request( 'wbcr_inp_error', '' );
		$wbcr_inp_imported = WINP_Plugin::app()->request->request( 'wbcr_inp_imported', - 1 );
		$wbcr_exp_none     = WINP_Plugin::app()->request->request( 'wbcr_exp_none', '' );
		$wbcr_saved        = WINP_Plugin::app()->request->post( $this->plugin->getPrefix() . 'saved', '' );
		if ( ! empty( $wbcr_inp_error ) ) { ?>
            <div id="message" class="alert alert-danger">
                <p><?php _e( 'An error occurred when processing the import files.', 'insert-php' ); ?></p>
            </div>
			<?php
		} else if ( intval( $wbcr_inp_imported ) >= 0 ) {
			$imported = intval( $wbcr_inp_imported );
			if ( 0 === $imported ) {
				$message = __( 'No snippets were imported.', 'insert-php' );
			} else {
				/* translators: %1$d: number imported snippets */
				$message = sprintf( _n( 'Successfully imported <strong>%1$d</strong> snippet.', 'Successfully imported <strong>%1$d</strong> snippets.', $imported, 'insert-php' ), $imported );
			}
			?>
            <div id="message" class="alert alert-success">
                <p><?php echo $message; ?></p>
            </div>
			<?php
		} else if ( ! empty( $wbcr_exp_none ) ) {
			?>
            <div id="message" class="alert alert-warning">
                <p><?php echo __( 'No snippets were exported.', 'insert-php' ); ?></p>
            </div>
			<?php
		} else if ( ! empty( $wbcr_saved ) && WINP_Plugin::app()->request->get( 'tab', 'import' ) != 'export' ) {
			?>
            <div id="message" class="alert alert-warning">
                <p><?php _e( 'No files selected!', 'insert-php' ); ?></p>
            </div>
			<?php
		}
	}

	public function indexAction() {
		$import_url = remove_query_arg( [ 'tab', 'wbcr_inp_imported', 'wbcr_exp_none' ] );
		$export_url = add_query_arg( 'tab', 'export', $import_url );

		$import_tab = true;
		$export_tab = false;

		if ( WINP_Plugin::app()->request->get( 'tab', 'import' ) == 'export' ) {
			$import_tab = false;
			$export_tab = true;
		}

		if ( $export_tab ) {
			// creating a form
			$form = WINP_Helper::get_factory_form( [
				'scope' => substr( $this->plugin->getPrefix(), 0, - 1 ),
				'name'  => 'import',
			], $this->plugin );
			$form->setProvider( WINP_Helper::get_options_value_provider( $this->plugin ) );
			$form->add( $this->getOptions() );

			if ( isset( $_POST['wbcr_inp_export_form_action'] ) ) {
				if ( ! WINP_Plugin::app()->currentUserCan() ) {
					wp_die( __( 'Sorry, you are not allowed to import snippets as this user.' ), __( 'You need a higher level of permission.' ), 403 );
				}

				check_admin_referer( 'wbcr_inp_import_form', 'wbcr_inp_import_form_nonce_field' );

				$status = WINP_Plugin::app()->request->post( $this->plugin->getPrefix() . 'status', 'all' );
				$types  = WINP_Plugin::app()->request->post( $this->plugin->getPrefix() . 'types', [] );
				$tags   = WINP_Plugin::app()->request->post( $this->plugin->getPrefix() . 'tags', [] );

				$tags_index = array_search( '__all', $tags );
				if ( false !== $tags_index ) {
					unset( $tags[ $tags_index ] );
				}

				$meta_query_conditions = [];
				$tax_query_conditions  = [];

				if ( 'all' != $status ) {
					$conditions = [
						'key'   => WINP_Plugin::app()->getPrefix() . 'snippet_activate',
						'value' => 1,
					];

					if ( 'active' != $status ) {
						$conditions['compare'] = '!=';
					}

					$meta_query_conditions[] = $conditions;
				}

				if ( ! empty( $types ) ) {
					if ( count( $types ) > 1 ) {
						$type_condition = [ 'relation' => 'OR' ];
						foreach ( $types as $type ) {
							$type_condition[] = [
								'key'   => WINP_Plugin::app()->getPrefix() . 'snippet_type',
								'value' => $type,
							];
						}
					} else {
						$type_condition = [
							'key'   => WINP_Plugin::app()->getPrefix() . 'snippet_type',
							'value' => $types[0],
						];
					}

					$meta_query_conditions[] = $type_condition;
				}

				if ( ! empty( $tags ) ) {
					$tax_query_conditions = [
						'taxonomy' => WINP_SNIPPETS_TAXONOMY,
						'field'    => 'slug',
						'terms'    => $tags,
					];
				}

				if ( count( $meta_query_conditions ) > 1 ) {
					$meta_query_conditions['relation'] = 'AND';
				}

				$conditions = [
					'post_type'   => WINP_SNIPPETS_POST_TYPE,
					'post_status' => 'publish',
					'numberposts' => - 1,
				];

				if ( ! empty( $meta_query_conditions ) ) {
					$conditions['meta_query'] = $meta_query_conditions;
				}

				if ( ! empty( $tax_query_conditions ) ) {
					$conditions['tax_query'] = [ $tax_query_conditions ];
				}

				$snippets = get_posts( $conditions );
				if ( $snippets ) {
					$ids = [];
					foreach ( $snippets as $snippet ) {
						$ids[] = $snippet->ID;
					}
					WASP_Core::app()->get_actions_object()->export_snippets( $ids, true );
				} else {
					$url = add_query_arg( [ 'wbcr_exp_none' => true ], $export_url );
					wp_redirect( esc_url_raw( $url ) );
					exit;
				}
			} else {
				?>
                <script type="text/javascript">
					jQuery(document).ready(function($) {
						$('#factory-checklist-wbcr_inp_tags-__all').click(function() {
							var checked = $(this).prop('checked');
							$('input[name="wbcr_inp_tags[]"]').each(function() {
								if( $(this).val() != '__all' ) {
									$('input[name="wbcr_inp_tags[]"]').prop('checked', checked);
								}
							});
						});
					});
                </script>
				<?php
			}
		}

		$max_size_bytes = apply_filters( 'import_upload_size_limit', wp_max_upload_size() );
		?>
        <div class="wrap">
            <div class="<?php echo WINP_Helper::get_factory_class(); ?>">
                <form method="post" class="form-horizontal" enctype="multipart/form-data">
					<?php $this->getMessage(); ?>
                    <h3><?php _e( 'Woody ad snippets Import', 'insert-php' ); ?></h3>
                    <div class="row">
                        <div class="col-md-9">
                            <div class="nav-tab-wrapper">
                                <a href="<?php echo $import_url; ?>" class="nav-tab<?php echo( $import_tab ? ' nav-tab-active' : '' ); ?>">
									<?php _e( 'Import', 'insert-php' ); ?>
                                </a>
                                <a href="<?php echo $export_url; ?>" class="nav-tab<?php echo( $export_tab ? ' nav-tab-active' : '' ); ?>">
									<?php _e( 'Export', 'insert-php' ); ?>
                                </a>
                            </div>
							<?php if ( $import_tab ) { ?>
                                <div id="tab1">
                                    <h4><?php _e( 'Duplicate Snippets', 'insert-php' ); ?></h4>
                                    <p class="description">
										<?php esc_html_e( 'What should happen if an existing snippet is found with an identical name to an imported snippet?', 'insert-php' ); ?>
                                    </p>
                                    <div style="padding-top: 10px;" class="winp-import-radio-container">
                                        <fieldset>
                                            <p>
                                                <label style="font-weight: normal;">
                                                    <input type="radio" name="duplicate_action" value="ignore" checked="checked">
													<?php _e( 'Ignore any duplicate snippets: import all snippets from the file regardless and leave all existing snippets unchanged.', 'insert-php' ); ?>
                                                </label>
                                            </p>
                                            <p>
                                                <label style="font-weight: normal;">
                                                    <input type="radio" name="duplicate_action" value="replace">
													<?php _e( 'Replace any existing snippets with a newly imported snippet of the same name.', 'insert-php' ); ?>
                                                </label>
                                            </p>
                                            <p>
                                                <label style="font-weight: normal;">
                                                    <input type="radio" name="duplicate_action" value="skip">
													<?php _e( 'Do not import any duplicate snippets; leave all existing snippets unchanged.', 'insert-php' ); ?>
                                                </label>
                                            </p>
                                        </fieldset>
                                    </div>
                                    <h3><?php _e( 'Upload Files', 'insert-php' ); ?></h3>
                                    <p class="description">
										<?php _e( 'Choose one or more Php Snippets (.json/.zip) files to upload, then click "Upload files and import".', 'insert-php' ); ?>
                                    </p>
                                    <fieldset>
                                        <p>
                                            <label for="upload" style="font-weight: normal;">
												<?php _e( 'Choose files from your computer:', 'insert-php' ); ?>
                                            </label>
											<?php
											/* translators: %s: size in bytes */
											printf( esc_html__( '(Maximum size: %s)', 'insert-php' ), size_format( $max_size_bytes ) );
											?>
                                            <input type="file" id="upload" name="wbcr_inp_import_files[]" size="25" accept="application/json,.json,application/zip,.zip" multiple="multiple">
                                            <input type="hidden" name="action" value="save">
                                            <input type="hidden" name="max_file_size" value="<?php echo esc_attr( $max_size_bytes ); ?>">
                                        </p>
                                    </fieldset>
                                    <div class="form-group form-horizontal">
                                        <div class="control-group controls col-sm-12">
											<?php wp_nonce_field( 'wbcr_inp_import_form', 'wbcr_inp_import_form_nonce_field' ); ?>
                                            <input name="<?php echo 'wbcr_inp_import_form_action' ?>" class="btn btn-primary" type="submit" value="<?php _e( 'Upload files and import', 'insert-php' ) ?>"/>
                                        </div>
                                    </div>
                                </div>
							<?php } else { ?>
                                <div id="tab2">
                                    <div style="padding-top: 10px;">
										<?php $form->html(); ?>
                                    </div>
                                    <div class="form-group form-horizontal">
                                        <div class="control-group controls col-sm-12">
											<?php wp_nonce_field( 'wbcr_inp_import_form', 'wbcr_inp_import_form_nonce_field' ); ?>
                                            <input name="<?php echo 'wbcr_inp_export_form_action' ?>" class="btn btn-primary" type="submit" value="<?php _e( 'Export all snippets', 'insert-php' ); ?>"/>
                                        </div>
                                    </div>
                                </div>
							<?php } ?>
                        </div>
                        <div class="col-md-3">
                            <div id="winp-dashboard-widget" class="winp-right-widget">
								<?php
								apply_filters( 'wbcr/inp/dashboard/widget/print', '' );
								?>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
		<?php
	}
}
