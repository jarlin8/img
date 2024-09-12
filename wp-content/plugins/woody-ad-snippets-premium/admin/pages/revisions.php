<?php
/**
 * Common Settings
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WASP_RevisionsPage extends WINP_Page {

	public $internal = true;

	/**
	 * WASP_RevisionsPage constructor.
	 *
	 * @param WINP_Plugin $plugin
	 */
	public function __construct( WINP_Plugin $plugin ) {
		$this->menu_post_type = WINP_SNIPPETS_POST_TYPE;

		$this->id         = 'revisions';
		$this->menu_title = __( 'Revisions', 'insert-php' );

		parent::__construct( $plugin );

		$this->plugin = $plugin;
	}

	public function assets( $scripts, $styles ) {
		$this->styles->request( array( 'bootstrap.core' ), 'bootstrap' );
		$this->scripts->request( 'jquery' );

		$this->styles->add( WASP_PLUGIN_URL . '/admin/assets/css/code-editor.min.css' );
		$this->styles->add( WASP_PLUGIN_URL . '/admin/assets/css/editor-addons/merge.css' );
		$this->styles->add( WASP_PLUGIN_URL . '/admin/assets/css/revisions.css' );

		$this->scripts->add( WASP_PLUGIN_URL . '/admin/assets/js/code-editor.min.js' );
		$this->scripts->add( WASP_PLUGIN_URL . '/admin/assets/js/editor-addons/diff_match_patch.js' );
		$this->scripts->add( WASP_PLUGIN_URL . '/admin/assets/js/editor-addons/merge.js' );
		$this->scripts->add( WASP_PLUGIN_URL . '/admin/assets/js/revisions.js' );
	}

	/**
	 * Query args:
	 *
	 * snippet int - (required) snippet id
	 *
	 * from && to int - (optional) revisions id
	 *
	 */
	public function indexAction() {
		global $post;

		require_once ABSPATH . 'wp-admin/includes/revision.php';
		require_once WASP_PLUGIN_DIR . '/admin/metaboxes/revisions.php';

		$snippet_id = WINP_Plugin::app()->request->get( 'snippet', 0 );
		if ( empty( $snippet_id ) || $snippet_id < 1 ) {
			wp_die( 'Bad revision id' );
		}

		$post    = get_post( $snippet_id );
		$snippet = $post;
		setup_postdata( $post );

		$revisions = wp_get_post_revisions(
			$snippet_id,
			array(
				'order'   => 'DESC',
				'orderby' => 'date ID',
			)
		);
		$revisions = array_filter(
			$revisions,
			array(
				$this,
				'excludeAutosave',
			)
		);

		$from_id = WINP_Plugin::app()->request->get( 'from', 0 );
		$to_id   = WINP_Plugin::app()->request->get( 'to', 0 );
		if ( ! empty( $from_id ) && ! empty( $to_id ) ) {
			if ( ! isset( $revisions[ $from_id ] ) or ! isset( $revisions[ $to_id ] ) ) {
				wp_die( 'Bad revision id' );
			}

			$from = $revisions[ $from_id ];
			$to   = $revisions[ $to_id ];
		} else {
			// show last revision
			if ( count( $revisions ) < 2 ) {
				wp_die( __( 'Not enough revisions to compare', 'insert-php' ) );
			}
			// take two first item
			reset( $revisions );
			$to = current( $revisions );
			next( $revisions );
			$from = current( $revisions );
		}

		$snippet_type          = WINP_Helper::get_snippet_type();
		$editorOptions         = array();
		$editorOptions['mode'] = ( WINP_SNIPPET_TYPE_PHP == $snippet_type ) ? 'text/x-php' : 'application/x-httpd-php';

		$metabox = new WASP_RevisionsMetaBox( $this->plugin );
		?>
        <div class="wrap <?php echo WINP_Helper::get_factory_class(); ?>">
            <div class="winp-rev-header">
                <h1 class="long-header"><?php _e( "Compare Revisions of ", "insert-php" ); ?>“<a
                            href="<?php echo $this->getSnippetURL( $post ) ?>"><?php the_title(); ?></a>"</h1>
                <a href="<?php echo $this->getSnippetURL( $post ) ?>">&larr; <?php _e( 'Return to editor', 'insert-php' ); ?></a>
            </div>

            <!-- selected revisions header -->
            <div class="revisions comparing-two-revisions winp-rev-diff-header">
                <div class="revisions-controls">
					<?php $now_gmt = time(); ?>
                    <div class="revisions-meta">
                        <div class="diff-meta diff-meta-from">
                            <div class="diff-title clearfix">
								<?php
								$author     = get_userdata( $from->post_author );
								$human_time = sprintf( __( '%s ago' ), human_time_diff( strtotime( $from->post_modified_gmt ), $now_gmt ) );
								?>
                                <strong><?php _e( "From:", "insert-php" ); ?></strong>

                                <div class="author-card">

                                    <div class="author-info">

                                        <span class="byline"><?php _e( "Revision by", "insert-php" ); ?> <span
                                                    class="author-name"><?php echo $author->display_name ?></span></span>

                                        <span class="time-ago"><?php echo $human_time ?></span>
                                        <span class="date">( <?php echo date_i18n( __( 'M j, Y @ H:i' ), strtotime( $from->post_modified ) ); ?>
                                            )</span>
                                    </div>

                                </div>

                            </div>
                        </div>
                        <div class="diff-meta diff-meta-to">

                            <div class="diff-title clearfix">
								<?php
								$author     = get_userdata( $to->post_author );
								$human_time = sprintf( __( '%s ago' ), human_time_diff( strtotime( $to->post_modified_gmt ), $now_gmt ) );
								?>
                                <strong><?php _e( "To:", "insert-php" ); ?></strong>
                                <div class="author-card">

                                    <div class="author-info">
                                        <span class="byline"><?php _e( "Revision by", "insert-php" ); ?> <span
                                                    class="author-name"><?php echo $author->display_name ?></span></span>

                                        <span class="time-ago"><?php echo $human_time ?></span>
                                        <span class="date">( <?php echo date_i18n( __( 'M j, Y @ H:i' ), strtotime( $to->post_modified ) ); ?>
                                            )</span>
                                    </div>

                                    <!--    <input type="button" class="restore-revision button button-primary" value="Restore This Revision">   -->
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="winp-diff-section">
                <h3 class="diff-property"><?php _e( "Title", "insert-php" ) ?></h3>
				<?php
				$haveDiffs = $this->haveDiffs( $from->post_title, $to->post_title );
				?>
                <div class="clearfix">
                    <div class="winp-rev-compare-col">
                        <div class="winp-rev-title <?php echo ( $haveDiffs ) ? 'winp-rev-changed' : '' ?>"><?php echo $from->post_title; ?></div>
                    </div>
                    <div class="winp-rev-compare-middle-col"></div>
                    <div class="winp-rev-compare-col">
                        <div class="winp-rev-title <?php echo ( $haveDiffs ) ? 'winp-rev-changed' : '' ?>"><?php echo $to->post_title; ?></div>
                    </div>
                </div>

            </div>

            <div class="winp-diff-section">
                <h3 class="diff-property"><?php _e( "Content", "insert-php" ) ?></h3>
                <!-- editor placeholder -->
                <div id="diff"></div>
            </div>

            <!-- revisions list metabox -->
            <div class="revisions-widget postbox">

                <div class="widget-head">
                    <button type="button" class="handlediv" aria-expanded="true"><span
                                class="screen-reader-text"><?php _e( "Toggle panel: Publish", "insert-php" ); ?></span><span
                                class="toggle-indicator" aria-hidden="true"></span></button>
                    <h2 class="winp-rev-caption"><?php _e( "Code Revisions", "insert-php" ); ?></h2>
                </div>
				<?php $metabox->renderMetabox( $snippet, $revisions, array( $from, $to ) ); ?>
            </div>


        </div>
		<?php
		$this->printScripts( $snippet, $from, $to, $editorOptions );

	}

	protected function getSnippetURL( $post ) {
		return admin_url( "post.php?post={$post->ID}&action=edit" );
	}

	private function printScripts( $snippet, $from, $to, $editorOptions = array() ) {
		?>

        <script>
            var data, dv, panes = 2, highlight = true, connect = 'align', collapse = false;

            function initUI() {
                if (data == null) return;
                var target = document.querySelector('#diff');
                target.innerHTML = '';
                dv = Woody_CodeMirror.MergeView(target, {
                    value: data.to,
                    origLeft: data.from,
                    lineNumbers: true,
                    mode: '<?php echo $editorOptions['mode'] ?>',
                    highlightDifferences: highlight,
                    connect: connect,
                    collapseIdentical: collapse,
                    revertButtons: false,
                    readOnly: true,
                });
            }

            function toggleDifferences() {
                dv.setShowDifferences(highlight = !highlight);
            }

            window.onload = function() {
                initUI();
                var d = document.createElement('div');
                d.style.cssText = 'width: 50px; margin: 7px; height: 14px';
                dv.editor().addLineWidget(57, d);
                setTimeout(function() {
                    resize(dv);
                });
                window.onresize = function() {
                    resize(dv);
                };

            };

            function mergeViewHeight(mergeView) {
                function editorHeight(editor) {
                    if (!editor) return 0;
                    return editor.getScrollInfo().height;
                }

                return Math.max(editorHeight(mergeView.leftOriginal()),
                    editorHeight(mergeView.editor()),
                    editorHeight(mergeView.rightOriginal()));
            }

            function resize(mergeView) {
                var height = mergeViewHeight(mergeView);
                for (; ;) {
                    if (mergeView.leftOriginal())
                        mergeView.leftOriginal().setSize(null, height);
                    mergeView.editor().setSize(null, height);
                    if (mergeView.rightOriginal())
                        mergeView.rightOriginal().setSize(null, height);

                    var newHeight = mergeViewHeight(mergeView);
                    if (newHeight >= height) break;
                    else height = newHeight;
                }
                mergeView.wrap.style.setProperty('height', height + 'px');
            }
        </script>
        <script>
            data = <?php echo json_encode( array(
				'origin' => $snippet->post_content,
				'from'   => $from->post_content,
				'to'     => $to->post_content,
			) ); ?>
        </script>
		<?php
	}

	/**
	 * Redirect to editor
	 *
	 * GET method
	 */
	public function restoreAction() {
		$revision_id = WINP_Plugin::app()->request->request( 'revision', '' );

		if ( empty( $revision_id ) || $revision_id <= 0 ) {
			wp_die( "bad revision id" );
		}
		check_admin_referer( 'winp_rev_' . $revision_id . '_restore' );

		$revision = get_post( $revision_id );
		if ( $revision === null || $revision->post_type != 'revision' ) {
			wp_die( 'bad revision' );
		}
		if ( ! $revision->post_parent || ( $snippet = get_post( $revision->post_parent ) ) === null ) {
			wp_die( 'bad revision' );
		}

		$canEdit      = current_user_can( 'edit_' . WINP_SNIPPETS_POST_TYPE . 's' );
		$canEditOther = current_user_can( 'edit_others_' . WINP_SNIPPETS_POST_TYPE . 's' );
		$user_id      = get_current_user_id();

		if ( ( $snippet->post_author == $user_id && $canEdit ) || ( $snippet->post_author != $user_id and $canEditOther ) ) {
			wp_restore_post_revision( $revision->ID );
		} else {
			wp_die( __( 'Access denied', 'insert-php' ) );
		}
		// redirect
		wp_safe_redirect( $this->getSnippetURL( $snippet ) );


	}

	public function excludeAutosave( $post ) {
		return ! wp_is_post_autosave( $post );
	}

	/**
	 * GET method
	 *
	 * query args:
	 *
	 * ids (required) - comma-separated ids of revisions
	 *
	 * ref (optional) - url for redirect
	 */
	public function deleteAction() {
		check_admin_referer( 'winp_rev_delete' );

		$ids = WINP_Plugin::app()->request->request( 'ids', '' );
		// comma-separated ids allowed
		$ids = preg_replace( "/[^0-9, ]+/i", '', $ids );
		if ( ! $ids ) {
			wp_die( 'Bad ids' );
		}

		$canDelete      = current_user_can( 'delete_' . WINP_SNIPPETS_POST_TYPE );
		$canDeleteOther = current_user_can( 'delete_others_' . WINP_SNIPPETS_POST_TYPE . 's' );

		$revisions = get_posts( array(
			'post_type'   => 'revision',
			'include'     => $ids,
			'numberposts' => - 1,
			'post_status' => 'any',
		) );
		$revisions = array_filter( $revisions, array( $this, "excludeAutosave" ) );

		$user_id = get_current_user_id();

		// check
		foreach ( $revisions as $revision ) {
			if ( $revision === null or $revision->post_type != 'revision' ) {
				wp_die( "Bad revision" );
			}
			if (
				( $revision->post_author == $user_id and $canDelete )
				|| ( $revision->post_author != $user_id and $canDeleteOther )
			) {
				wp_delete_post( $revision->ID );
			} else {
				wp_die( __( 'Access denied', 'insert-php' ) );
			}
		}

		$location = WINP_Plugin::app()->request->get( 'ref', '' );
		if ( ! empty( $location ) ) {
			wp_safe_redirect( $location );
		} else {
			echo 1;
		}

	}

	private function haveDiffs( $left_string, $right_string ) {
		if ( ! class_exists( 'WP_Text_Diff_Renderer_Table', false ) ) {
			require( ABSPATH . WPINC . '/wp-diff.php' );
		}
		$left_string  = normalize_whitespace( $left_string );
		$right_string = normalize_whitespace( $right_string );

		$left_lines  = explode( "\n", $left_string );
		$right_lines = explode( "\n", $right_string );
		$text_diff   = new Text_Diff( $left_lines, $right_lines );

		return ! $text_diff->isEmpty();
	}

}
