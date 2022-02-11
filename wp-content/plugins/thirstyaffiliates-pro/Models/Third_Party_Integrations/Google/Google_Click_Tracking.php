<?php
namespace ThirstyAffiliates_Pro\Models\Third_Party_Integrations\Google;

use ThirstyAffiliates_Pro\Abstracts\Abstract_Main_Plugin_Class;

use ThirstyAffiliates_Pro\Interfaces\Model_Interface;

use ThirstyAffiliates_Pro\Helpers\Plugin_Constants;
use ThirstyAffiliates_Pro\Helpers\Helper_Functions;

// Data Models
use ThirstyAffiliates\Models\Affiliate_Link;

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Model that houses the logic for the CSV Import/Export module.
 *
 * @since 1.2.0
 */
class Google_Click_Tracking implements Model_Interface {

    /*
    |--------------------------------------------------------------------------
    | Class Properties
    |--------------------------------------------------------------------------
    */

    /**
     * Property that holds the single main instance of Settings_Extension.
     *
     * @since 1.2.0
     * @access private
     * @var Settings_Extension
     */
    private static $_instance;

    /**
     * Model that houses all the plugin constants.
     *
     * @since 1.2.0
     * @access private
     * @var Plugin_Constants
     */
    private $_constants;

    /**
     * Property that houses all the helper functions of the plugin.
     *
     * @since 1.2.0
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
     * @since 1.2.0
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
     * @since 1.2.0
     * @access public
     *
     * @param Abstract_Main_Plugin_Class $main_plugin      Main plugin object.
     * @param Plugin_Constants           $constants        Plugin constants object.
     * @param Helper_Functions           $helper_functions Helper functions object.
     * @return Settings_Extension
     */
    public static function get_instance( Abstract_Main_Plugin_Class $main_plugin , Plugin_Constants $constants , Helper_Functions $helper_functions ) {

        if ( !self::$_instance instanceof self )
            self::$_instance = new self( $main_plugin , $constants , $helper_functions );

        return self::$_instance;

    }

    /**
     * Get tracking script.
     *
     * @since 1.2.0
     * @since 1.4.0 Add event action value setting implementation.
     * @access private
     *
     * @return string $tracking_script Google tracking script to use.
     */
    private function get_tracking_script() {

        // Makes sure the plugin is defined before trying to use it
        if ( ! function_exists( 'is_plugin_active' ) )
            include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

        $script_type     = get_option( 'tap_google_click_tracking_script' , 'universal_ga' );
        $tracking_script = '';
        $event_action    = get_option( 'tap_google_click_tracking_event_action_value' , 'link_uri' );

        switch( $script_type ) {

            case 'gtm' :
                $tracking_script = "dataLayer.push({
                    'event'    : action_name,
                    'link_uri' : $event_action
                });";
                break;

            case 'legacy_ga' :
                $tracking_script = "_gaq.push( [ '_trackEvent' , action_name , $event_action , page_slug , , false ] );";
                break;

            case 'universal_ga' :

                $is_yoast        = is_plugin_active( 'google-analytics-for-wordpress/googleanalytics.php' ) ? true : false;
                $custom_func     = get_option( 'tap_universal_ga_custom_func' );
                $ga_func_name    = $is_yoast ? '__gaTracker' : 'ga';
                $ga_func_name    = $custom_func ? esc_attr( $custom_func ) : $ga_func_name;
                $tracking_script = $ga_func_name . "( 'send' , 'event' , {
                    eventCategory : action_name,
                    eventAction   : $event_action,
                    eventLabel    : page_slug,
                    transport     : 'beacon'
                } );";

                break;

            case 'gtag_ga' :
            default:

                $tracking_script = "gtag( 'event' , action_name , {
                    event_category : action_name,
                    event_action   : $event_action,
                    event_label    : page_slug
                } );";

                break;
        }

        return $tracking_script;
    }

    /**
     * Get the tracking script JS function name.
     *
     * @since 1.2.0
     * @access private
     *
     * @return string JS function name.
     */
    private function get_tracking_script_function_name() {

        $script_type  = get_option( 'tap_google_click_tracking_script' , 'universal_ga' );
        $ga_func_name = '';

        switch( $script_type ) {

            case 'gtm' :
                $ga_func_name = 'dataLayer.push';
                break;

            case 'legacy_ga' :
                $ga_func_name = '_gaq.push';
                break;

            case 'universal_ga' :
                $is_yoast     = is_plugin_active( 'google-analytics-for-wordpress/googleanalytics.php' ) ? true : false;
                $custom_func  = get_option( 'tap_universal_ga_custom_func' );
                $ga_func_name = $is_yoast ? '__gaTracker' : 'ga';
                $ga_func_name = $custom_func ? esc_attr( $custom_func ) : $ga_func_name;
                break;

            case 'gtag_ga' :
            default:
                $ga_func_name = 'gtag';
                break;
        }

        return $ga_func_name;
    }

    /**
     * Display click tracking inline JS to the wp_footer action hook.
     *
     * @since 1.2.0
     * @since 1.4.0 Add JS values for link slug and link text, and improved code for checking in link is uncloaked or not.
     * @access public
     */
    public function display_click_tracking_inline_js() {

        $action_name         = esc_attr( get_option( 'tap_google_click_tracking_action_name' ) );
        $default_action_name = __( 'Affiliate Link' , 'thirstyaffiliates-pro' );
        $page_uri            = isset( $_SERVER[ 'REQUEST_URI' ] ) ? $_SERVER[ 'REQUEST_URI' ] : '';
        $page_slug           = apply_filters( 'tap_gct_clean_page_slug' , true ) ? preg_replace( '/\?.*/' , '' , $page_uri ) : $page_uri; // Clean the URI by removing all query strings
        $tracking_script     = $this->get_tracking_script();
        $ga_func_name        = $this->get_tracking_script_function_name();

        ?>
        <script type="text/javascript">
            var thirstyGoogleClickTrack = function ( e ) {

                var $this       = jQuery( this ),
                    linkID      = $this.data( 'linkid' ),
                    href        = linkID ? $this.attr( 'href' ) : thirstyFunctions.isThirstyLink( $this.attr( 'href' ) ),
                    action_name = '<?php echo $action_name ? $action_name : $default_action_name; ?>',
                    page_slug   = '<?php echo esc_attr( $page_slug ); ?>',
                    home_url    = '<?php echo home_url(); ?>';

                if ( ! href || typeof <?php echo $ga_func_name; ?> !== 'function' ) {
                    return;
                }

                var is_uncloak  = href.indexOf( home_url + '/' + thirsty_global_vars.link_prefix ) < 0,
                    href_parts  = href.split('/'),
                    href_last   = href_parts[ href_parts.length - 1 ] ? href_parts[ href_parts.length - 1 ] : href_parts[ href_parts.length - 2 ],
                    link_text   = $this.text(),
                    link_uri    = linkID && is_uncloak ? href : href.replace( home_url , '' ),
                    link_slug   = linkID && is_uncloak ? href : href_last;

                <?php echo $tracking_script; ?>

            };

            jQuery( document ).ready( function($) {
                $( 'body' ).on( 'click', 'a', thirstyGoogleClickTrack );
            });
        </script>
        <?php
    }

    /**
     * Make sure ta.js script is enqueued when GCT module is enabled.
     *
     * @since 1.2.0
     * @access public
     *
     * @param bool $enable Toggle to check if ta.js is enqueued or not.
     * @return bool Filtered toggle to check if ta.js is enqueued or not.
     */
    public function enqueue_ta_js( $enable ) {

        return ( get_option( 'tap_enable_google_click_tracking' , 'yes' ) === 'yes' ) ? true : $enable;
    }




    /*
    |--------------------------------------------------------------------------
    | Implemented Interface Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Execute model.
     *
     * @implements ThirstyAffiliates_Pro\Interfaces\Model_Interface
     *
     * @since 1.2.0
     * @access public
     */
    public function run() {

        // When module is disabled in the settings, then it shouldn't run the whole class.
        if ( get_option( 'tap_enable_google_click_tracking' , 'yes' ) !== 'yes' )
            return;

        add_action( 'wp_footer' , array( $this , 'display_click_tracking_inline_js' ) );
        add_filter( 'ta_enqueue_tajs_script' , array( $this , 'enqueue_ta_js' ) );
    }

}
