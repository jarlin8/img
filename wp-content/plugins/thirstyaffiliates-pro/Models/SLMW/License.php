<?php
namespace ThirstyAffiliates_Pro\Models\SLMW;

use ThirstyAffiliates_Pro\Abstracts\Abstract_Main_Plugin_Class;

use ThirstyAffiliates_Pro\Interfaces\Model_Interface;
use ThirstyAffiliates_Pro\Interfaces\Initiable_Interface;

use ThirstyAffiliates_Pro\Helpers\Plugin_Constants;
use ThirstyAffiliates_Pro\Helpers\Helper_Functions;

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Model that houses logic for the plugin license.
 *
 * @since 1.0.0
 */
class License implements Model_Interface , Initiable_Interface {

    /*
    |--------------------------------------------------------------------------
    | Class Properties
    |--------------------------------------------------------------------------
    */

    /**
     * Property that holds the single main instance of License.
     *
     * @since 1.0.0
     * @access private
     * @var License
     */
    private static $_instance;

    /**
     * Model that houses all the plugin constants.
     *
     * @since 1.0.0
     * @access private
     * @var Plugin_Constants
     */
    private $_constants;

    /**
     * Property that houses all the helper functions of the plugin.
     *
     * @since 1.0.0
     * @access private
     * @var Helper_Functions
     */
    private $_helper_functions;




    /*
    |--------------------------------------------------------------------------
    | Class Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Class constructor.
     *
     * @since 1.0.0
     * @access public
     *
     * @param Abstract_Main_Plugin_Class $main_plugin      Main plugin object.
     * @param Plugin_Constants           $constants        Plugin constants object.
     * @param Helper_Functions           $helper_functions Helper functions object.
     */
    public function __construct( Abstract_Main_Plugin_Class $main_plugin , Plugin_Constants $constants , Helper_Functions $helper_functions ) {

        $this->_constants        = $constants;
        $this->_helper_functions = $helper_functions;

        $main_plugin->add_to_all_plugin_models( $this );

    }

    /**
     * Ensure that only one instance of this class is loaded or can be loaded ( Singleton Pattern ).
     *
     * @since 1.0.0
     * @access public
     *
     * @param Abstract_Main_Plugin_Class $main_plugin      Main plugin object.
     * @param Plugin_Constants           $constants        Plugin constants object.
     * @param Helper_Functions           $helper_functions Helper functions object.
     * @return License
     */
    public static function get_instance( Abstract_Main_Plugin_Class $main_plugin , Plugin_Constants $constants , Helper_Functions $helper_functions ) {

        if ( !self::$_instance instanceof self )
            self::$_instance = new self( $main_plugin , $constants , $helper_functions );

        return self::$_instance;

    }

    /**
     * Activate license notice.
     *
     * @since 1.0.0
     * @since 1.1.2 Made notice dismissable on non-TA pages. Once notice is dismissed, it will not show up on non-TA pages anymore.
     * @since 1.6 Refactor codebase to improve support for multisite installs.
     * @access public
     */
    public function activate_license_notice() {

        $license_activated = is_multisite() ? get_site_option(  Plugin_Constants::OPTION_LICENSE_ACTIVATED ) : get_option(  Plugin_Constants::OPTION_LICENSE_ACTIVATED );

        if ( $license_activated !== 'yes' ) {

            $screen      = get_current_screen();
            $dismissable = ( strpos( $screen->id , 'thirstylink' ) === false && $screen->id !== 'edit-tap-event-notification' ) ? 'is-dismissible' : '';

            // When notice is dismissed on non-TA pages, then don't show it anymore.
            if ( $dismissable && get_option( 'tap_slmw_active_notice_dismissed' ) == 'yes' )
                return;

            global $wp;

            if ( is_multisite() ) {

                $current_url           = add_query_arg( $wp->query_string , '?' , network_home_url( $wp->request ) );
                $tap_slmw_settings_url = $current_url . "wp-admin/network/admin.php?page=tap-ms-license-settings";

            } else
                $tap_slmw_settings_url = admin_url() . 'edit.php?post_type=thirstylink&page=thirsty-settings&tab=tap_slmw_settings_section';

            ?>

            <div class="notice notice-error <?php echo $dismissable; ?> tap-activate-license-notice">
                <h4 class="tap-activate-license-notice">
                    <?php echo sprintf( __( 'Please <a href="%1$s">activate</a> your copy of ThirstyAffiliates Pro to get latest updates and have access to support.' , 'thirstyaffiliates-pro' ) , $tap_slmw_settings_url ); ?>
                </h4>
            </div>

            <?php if ( $dismissable ) { ?>

                <style>.tap-activate-license-notice .notice-dismiss { margin-top: 8px; }</style>
                <script>
                    jQuery( document ).ready( function( $ ) {

                        $( '.tap-activate-license-notice' ).on( 'click' , '.notice-dismiss' , function() {
                            $.post( window.ajaxurl, { action : 'tap_slmw_dismiss_activate_notice' } );
                        } );

                    } );
                </script>

            <?php }

        }

    }

    /**
     * AJAX activate license for this site.
     *
     * @since 1.0.0
     * @since 1.6 Refactor codebase to improve support for multisite installs.
     * @access public
     */
    public function ajax_activate_license() {

        if ( !defined( "DOING_AJAX" ) || !DOING_AJAX )
            $response = array( 'status' => 'fail' , 'error_msg' => __( 'Invalid AJAX Operation' , 'thirstyaffiliates-pro' ) );
        elseif ( !isset( $_POST[ 'activation-email' ] ) || !isset( $_POST[ 'license-key' ] ) || !isset( $_POST[ 'ajax-nonce' ] ) )
            $response = array( 'status' => 'fail' , 'error_msg' => __( 'Required parameters not supplied' , 'thirstyaffiliates-pro' ) );
        elseif ( !check_ajax_referer( 'tap_activate_license' , 'ajax-nonce' , false ) )
            $response = array( 'status' => 'fail' , 'error_msg' => __( 'Security check failed' , 'thirstyaffiliates-pro' ) );
        else {

            $activation_email = trim( $_POST[ 'activation-email' ] );
            $license_key      = trim( $_POST[ 'license-key' ] );
            $activation_url   = add_query_arg( array(
                'activation_email' => urlencode( $activation_email ),
                'license_key'      => $license_key,
                'site_url'         => home_url(),
                'multisite'        => is_multisite() ? 1 : 0
            ) , apply_filters( 'tap_license_activation_url' , Plugin_Constants::LICENSE_ACTIVATION_URL ) );

            // Store data even if not valid license
            if ( is_multisite() ) {

                update_site_option( 'tap_slmw_activation_email' , $activation_email );
                update_site_option( 'tap_slmw_license_key' , $license_key );

            } else {

                update_option( 'tap_slmw_activation_email' , $activation_email );
                update_option( 'tap_slmw_license_key' , $license_key );

            }

            $option = array(
                'timeout' => 10 , //seconds
                'headers' => array( 'Accept' => 'application/json' )
            );

            $result = wp_remote_retrieve_body( wp_remote_get( $activation_url , $option ) );

            if ( empty( $result ) )
                $response = array( 'status' => 'fail' , 'error_msg' => __( 'Failed to activate license. Failed to connect to activation access point. Please contact plugin support.' , 'thirstyaffiliates-pro' ) );
            else {

                $result = json_decode( $result );

                if ( empty( $result ) || !property_exists( $result , 'status' ) )
                    $response = array( 'status' => 'fail' , 'error_msg' => __( 'Failed to activate license. Activation access point return invalid response. Please contact plugin support.' , 'thirstyaffiliates-pro' ) );
                else {

                    if ( $result->status === 'success' ) {

                        if ( is_multisite() )
                            update_site_option( Plugin_Constants::OPTION_LICENSE_ACTIVATED , 'yes' );
                        else
                            update_option( Plugin_Constants::OPTION_LICENSE_ACTIVATED , 'yes' );

                        $response = array( 'status' => $result->status , 'success_msg' => $result->success_msg );

                    } else {

                        if ( is_multisite() )
                            update_site_option( Plugin_Constants::OPTION_LICENSE_ACTIVATED , 'no' );
                        else
                            update_option( Plugin_Constants::OPTION_LICENSE_ACTIVATED , 'no' );

                        $response = array( 'status' => $result->status , 'error_msg' => $result->error_msg );

                        // Remove any locally stored update data if there are any
                        $wp_site_transient = get_site_transient( 'update_plugins' );

                        if ( $wp_site_transient ) {

                            $tap_plugin_basename = 'thirstyaffiliates-pro/thirstyaffiliates-pro.php';

                            if ( isset( $wp_site_transient->checked ) && is_array( $wp_site_transient->checked ) && array_key_exists( $tap_plugin_basename , $wp_site_transient->checked ) )
                                unset( $wp_site_transient->checked[ $tap_plugin_basename ] );

                            if ( isset( $wp_site_transient->response ) && is_array( $wp_site_transient->response ) && array_key_exists( $tap_plugin_basename , $wp_site_transient->response ) )
                                unset( $wp_site_transient->response[ $tap_plugin_basename ] );

                            set_site_transient( 'update_plugins' , $wp_site_transient );

                            wp_update_plugins();

                        }

                    }

                }

            }

        }


        @header( 'Content-Type: application/json; charset=' . get_option( 'blog_charset' ) );
        echo wp_json_encode( $response );
        wp_die();

    }

    /**
     * AJAX dismiss activate notice.
     *
     * @since 1.1.2
     * @access public
     */
    public function ajax_dismiss_activate_notice() {

        if ( !defined( "DOING_AJAX" ) || !DOING_AJAX )
            $response = array( 'status' => 'fail' , 'error_msg' => __( 'Invalid AJAX Operation' , 'thirstyaffiliates-pro' ) );
        else {

            if ( is_multisite() )
                update_site_option( 'tap_slmw_active_notice_dismissed' , 'yes' );
            else
                update_option( 'tap_slmw_active_notice_dismissed' , 'yes' );

            $response = array( 'status' => 'success' );
        }

        @header( 'Content-Type: application/json; charset=' . get_option( 'blog_charset' ) );
        echo wp_json_encode( $response );
        wp_die();

    }




    /*
    |--------------------------------------------------------------------------
    | Implemented Interface Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Execute plugin script loader.
     *
     * @since 1.0.0
     * @access public
     * @implements ThirstyAffiliates\Interfaces\Initiable_Interface
     */
    public function initialize() {

        add_action( 'wp_ajax_tap_activate_license' , array( $this , 'ajax_activate_license' ) );
        add_action ( 'wp_ajax_tap_slmw_dismiss_activate_notice' , array( $this , 'ajax_dismiss_activate_notice' ) );

    }

    /**
     * Execute plugin script loader.
     *
     * @since 1.0.0
     * @since 1.6 Refactor codebase to improve support for multisite installs.
     * @access public
     * @implements ThirstyAffiliates\Interfaces\Model_Interface
     */
    public function run () {

        if ( is_multisite() ) {

            add_action( 'network_admin_notices' , array( $this , 'activate_license_notice' ) );

        } else {

            add_action( 'admin_notices' , array( $this , 'activate_license_notice' ) );

        }

    }

}
