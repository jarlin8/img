<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WASP_RevisionsMetaBox extends WINP_MetaBox {

	/**
	 * A visible title of the metabox.
	 *
	 * Inherited from the class FactoryMetabox.
	 * @link http://codex.wordpress.org/Function_Reference/add_meta_box
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $title;

	/**
	 * The part of the page where the edit screen
	 * section should be shown ('normal', 'advanced', or 'side').
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $context = 'normal';

	/**
	 * The priority within the context where the boxes should show ('high', 'core', 'default' or 'low').
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_meta_box
	 * Inherited from the class FactoryMetabox.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $priority = 'core';

	public $css_class = '';

	public function __construct( $plugin ) {
		parent::__construct( $plugin );

		$this->title     = __( 'Code Revisions', 'insert-php' );
		$this->css_class = WINP_Helper::get_factory_class();
	}

	/**
	 * Configures a metabox.
	 *
	 * @since 1.0.0
	 *
	 * @param Wbcr_Factory000_ScriptList $scripts A set of scripts to include.
	 * @param Wbcr_Factory000_StyleList $styles A set of style to include.
	 *
	 * @return void
	 */
	public function configure( $scripts, $styles ) {
		$styles->add( WASP_PLUGIN_URL . '/admin/assets/css/revisions.css' );
		$scripts->add( WASP_PLUGIN_URL . '/admin/assets/js/revisions.js' );
	}

	public function html() {
		global $post;

		$snippet_id = (int) $post->ID;
		if ( 0 === $snippet_id ) {
			wp_die( __( 'Access denied', 'insert-php' ) );
		}
		$snippet   = get_post( $snippet_id );
		$revisions = wp_get_post_revisions(
			$snippet_id,
			array(
			    'numberposts' => 10,
				'order'   => 'DESC',
				'orderby' => 'date ID',
			)
		);

		$this->renderMetabox( $snippet, $revisions );
	}

	/**
	 * @param $snippet WP_Post
	 * @param $revisions WP_Post[]
	 * @param array $current WP_Post[] - current revisions will be marked. 2 items needed
	 */
	public function renderMetabox( $snippet, $revisions, $current = array() ) {
		$user_can_edit         = current_user_can( 'edit_' . WINP_SNIPPETS_POST_TYPE . 's' );
		$user_can_edit_other   = current_user_can( 'edit_others_' . WINP_SNIPPETS_POST_TYPE . 's' );
		$user_can_delete       = current_user_can( 'delete_' . WINP_SNIPPETS_POST_TYPE );
		$user_can_delete_other = current_user_can( 'delete_others_' . WINP_SNIPPETS_POST_TYPE . 's' );
		$user_id               = get_current_user_id();
		?>
        <form class="winp_rev_form">
            <table class="wp-list-table widefat fixed striped snippet-revisions">
                <thead>
                <tr>
                    <th><?php _e( "Compare", "insert-php" ); ?></th>
                    <th><?php _e( "Revision", "insert-php" ); ?></th>
                    <th><?php _e( "Author", "insert-php" ); ?></th>
                    <th><?php _e( "Delete", "insert-php" ); ?></th>
                    <th><?php _e( "Restore", "insert-php" ); ?></th>
                </tr>
                </thead>
                <tbody>
				<?php
				if ( count( $revisions ) ):
					foreach ( $revisions as $revision ):
						if ( wp_is_post_autosave( $revision ) ) {
							continue;
						}
						setup_postdata( $revision );
						$can_delete   = ( $revision->post_author == $user_id and $user_can_delete ) or ( $revision->post_author != $user_id and $user_can_delete_other );
						$can_restore  = ( $snippet->post_author == $user_id and $user_can_edit ) or ( $snippet->post_author != $user_id and $user_can_edit_other );
						$activeFirst  = false;
						$activeSecond = false;
						if ( count( $current ) >= 2 ) {
							if ( $revision->ID == $current[0]->ID ) {
								$activeFirst = true;
							}
							if ( $revision->ID == $current[1]->ID ) {
								$activeSecond = true;
							}
						}
						?>
                        <tr>
                            <td>
                                <input class="winp_rev_radio_from" type="radio" name="winp_rev_from"
                                        value="<?php echo $revision->ID; ?>"
                                        data-panel="from" <?php echo ( $activeFirst ) ? 'checked' : '' ?>>
                                <input class="winp_rev_radio_to" type="radio" name="winp_rev_to"
                                        value="<?php echo $revision->ID; ?>"
                                        data-panel="to" <?php echo ( $activeSecond ) ? 'checked' : '' ?>>
                            </td>
                            <td><?php echo date_i18n( __( 'M j, Y @ H:i' ), strtotime( $revision->post_modified ) ); ?></td>
                            <td><?php the_author(); ?></td>
                            <th><input type="checkbox" name="winp_rev_delete_mark"
                                       value="<?php echo $revision->ID; ?>" <?= ( $can_delete ) ? '' : 'disabled' ?>></th>
                            <th>
								<?php if ( $can_restore ) { ?>
                                    <a href="<?php echo wp_nonce_url( admin_url( 'edit.php?post_type=' . WINP_SNIPPETS_POST_TYPE . '&page=revisions-wbcr_insert_php&action=restore&revision=' . $revision->ID ), 'winp_rev_' . $revision->ID . '_restore' ); ?>">
										<?php _e( 'Restore', 'insert-php' ); ?>
                                    </a>
								<?php } else { ?>
                                    <span><?php _e( 'Restore', 'insert-php' ); ?></span>
								<?php } ?>
                            </th>
                        </tr>
						<?php
						wp_reset_postdata();
					endforeach;
				else:
					?>
                    <tr>
                        <td colspan="5" class="winp-rbox-no-items"><i><?php _e( "No revisions", "insert-php" ); ?></i>
                        </td>
                    </tr>
					<?php
				endif;
				?>
                </tbody>
            </table>
            <input type="hidden" name="winp_rbox_snippet" value="<?php echo $snippet->ID ?>">
            <input type="hidden" id="winp_rev_del_nonce" value="<?php echo wp_create_nonce( 'winp_rev_delete' ); ?>">
        </form>
		<?php if ( count( $revisions ) ): ?>
            <div class="revisions-actionbar">
                <button type="button"
                        class="button action winp_rev_compare"><?php _e( 'Compare', 'insert-php' ); ?></button>
                <button type="button"
                        class="button action winp_rev_delete"><?php _e( 'Delete', 'insert-php' ); ?></button>
            </div>
		<?php endif;
	}
}