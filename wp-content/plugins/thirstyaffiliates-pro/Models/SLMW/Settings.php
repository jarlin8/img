<?php
namespace ThirstyAffiliates_Pro\Models\SLMW;

use ThirstyAffiliates_Pro\Abstracts\Abstract_Main_Plugin_Class;

use ThirstyAffiliates_Pro\Interfaces\Model_Interface;

use ThirstyAffiliates_Pro\Helpers\Plugin_Constants;
use ThirstyAffiliates_Pro\Helpers\Helper_Functions;

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Model that houses the logic of Software License Manager plugin integration settings.
 * 
 * @since 1.0.0
 */
class Settings implements Model_Interface {

    /*
    |--------------------------------------------------------------------------
    | Class Properties
    |--------------------------------------------------------------------------
    */

    /**
     * Property that holds the single main instance of Settings.
     *
     * @since 1.0.0
     * @access private
     * @var Settings
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
     * @return Settings
     */
    public static function get_instance( Abstract_Main_Plugin_Class $main_plugin , Plugin_Constants $constants , Helper_Functions $helper_functions ) {

        if ( !self::$_instance instanceof self )
            self::$_instance = new self( $main_plugin , $constants , $helper_functions );

        return self::$_instance;

    }

    /**
     * Register slmw settings menu.
     * 
     * @since 1.6
     * @access public
     */
    public function register_slmw_settings_menu() {

        add_menu_page( 
            __( "TAP License" , "thirstyaffiliates-pro" ), 
            __( "TAP License" , "thirstyaffiliates-pro" ), 
            "manage_sites", 
            "tap-ms-license-settings", 
            array( $this , "generate_slmw_settings_page" )
        );

    }

    /**
     * Register slmw settings page.
     *
     * @since 1.6
     * @access public
     */
    public function generate_slmw_settings_page() {

        $tap_slmw_activation_email = is_multisite() ? get_site_option( Plugin_Constants::OPTION_ACTIVATION_EMAIL ) : get_option( Plugin_Constants::OPTION_ACTIVATION_EMAIL );
        $tap_slmw_license_key      = is_multisite() ? get_site_option( Plugin_Constants::OPTION_LICENSE_KEY ) : get_option( Plugin_Constants::OPTION_LICENSE_KEY );

        ?>
        
        <div id="tap_slmw_settings">

            <h2><?php _e( "ThirstyAffiliates Pro License" , "thirstyaffiliates-pro" ); ?></h2>

            <table class="form-table">
                <tbody>
                <tr valign="top">
                    <th scope="row" class="titledesc">
                        <label for="tap_slmw_activation_email"><?php _e( 'License Email' , 'thirstyaffiliates-pro' ); ?></label>
                    </th>
                    <td class="forminp forminp-text">
                        <input type="text" id="tap_slmw_activation_email" class="regular-text ltr" value="<?php echo $tap_slmw_activation_email; ?>"/>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row" class="titledesc">
                        <label for="tap_slmw_license_key"><?php _e( 'License Key' , 'thirstyaffiliates-pro' ); ?></label>
                    </th>
                    <td class="forminp forminp-text">
                        <input type="text" id="tap_slmw_license_key" class="regular-text ltr" value="<?php echo $tap_slmw_license_key; ?>"/>
                    </td>
                </tr>
                </tbody>
            </table>

            <p class="submit">
                <input type="button" id="submit" class="button button-primary" value="<?php _e( 'Save Changes' , 'thirstyaffiliates-pro' ); ?>"/>
                <span class="spinner" style="float: none; position: relative; top: -2px; margin-top: 0; display: none; visibility: visible;"></span>
            </p>

        </div><!--#tap_slmw_settings-->
        
        <?php

    }

    /**
     * Register slmw settings section.
     *
     * @since 1.0.0
     * @access public
     *
     * @param array $settings_sections Array of settings sections.
     * @return array Filtered array of settings sections.
     */
    public function register_slmw_settings_section( $settings_sections ) {

        if ( array_key_exists( 'tap_slmw_settings_section' , $settings_sections ) )
            return $settings_sections;

        $settings_sections[ 'tap_slmw_settings_section' ] = array(
            'title' => __( 'License' , 'thirstyaffiliates-pro' ),
            'desc'  => sprintf( __( 'Enter the activation email and the license key given to you after purchasing ThirstyAffiliates Pro. You can find this information by logging into your <a href="%1$s" target="_blank">My Account</a> on our website or in the purchase confirmation email sent to your email address.' , 'thirstyaffiliates-pro' ) , "https://thirstyaffiliates.com/my-account" )
        );

        return $settings_sections;

    }

    /**
     * Register amazon settings section options.
     *
     * @since 1.0.0
     * @access public
     *
     * @param array $settings_section_options Array of options per settings sections.
     * @return array Filtered array of options per settings sections.
     */
    public function register_slmw_settings_section_options( $settings_section_options ) {

        if ( array_key_exists( 'tap_slmw_settings_section' , $settings_section_options ) )
            return $settings_section_options;

        $settings_section_options[ 'tap_slmw_settings_section' ] = apply_filters(
            'tap_slmw_settings_section_options' ,
            array(
                array(
                    'id'    => Plugin_Constants::OPTION_ACTIVATION_EMAIL,
                    'title' => __( 'Activation Email' , 'thirstyaffiliates-pro' ),
                    'desc'  => '',
                    'type'  => 'text',
                ),
                array(
                    'id'    => Plugin_Constants::OPTION_LICENSE_KEY,
                    'title' => __( 'License Key' , 'thirstyaffiliates-pro' ),
                    'desc'  => '',
                    'type'  => 'text',
                )
            ) 
        );

        return $settings_section_options;

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
     * @implements ThirstyAffiliates\Interfaces\Model_Interface
     */
    public function run () {
        
        if ( is_multisite() ) {

            // Add SLMW Settings In Multi-Site Environment
            add_action( "network_admin_menu" , array( $this , 'register_slmw_settings_menu' ) );

        } else {

            add_filter( 'ta_settings_option_sections' , array( $this , 'register_slmw_settings_section' ) );
            add_filter( 'ta_settings_section_options' , array( $this , 'register_slmw_settings_section_options' ) );

        }
        
    }

}