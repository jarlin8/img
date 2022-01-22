<?php

/**
 * @package jolitoc
 */
namespace WPJoli\JoliTOC\Controllers;

use  WPJoli\JoliTOC\Application ;
use  WPJoli\JoliTOC\Controllers\Callbacks\SettingsCallbacks ;
use  WPJoli\JoliTOC\Config\Settings ;
class SettingsController
{
    protected  $prefix ;
    protected  $page ;
    protected  $page_name ;
    protected  $settings = array() ;
    public  $groups = array() ;
    public  $sections = array() ;
    public  $fields = array() ;
    protected  $settings_cb ;
    //will contain the options array stored in db
    protected  $cached_settings ;
    public function __construct()
    {
        $this->settings_cb = new SettingsCallbacks();
        //loads the default settings array
        $this->settings = $this->defaultSettings();
        $this->prefix = Application::SLUG . '_';
        $this->page_name = Application::SETTINGS_SLUG;
        $this->initSettings();
        //load the options array stored in db to prevent further SQL queries
        $this->cached_settings = get_option( Application::SETTINGS_SLUG );
        // pre($this->cached_settings);
        //Reset settings button clicked
        if ( isset( $_POST['jtoc_reset_settings'] ) ) {
            $this->resetSettings();
        }
    }
    
    private function defaultSettings()
    {
        $settings = (include JTOC()->path( 'config/defaults.php' ));
        return $settings;
    }
    
    public function initSettings()
    {
        $settings = $this->settings;
        $this->groups = [];
        $this->sections = [];
        $this->fields = [];
        $cpt = 0;
        //Init Groups-----------------
        foreach ( $settings as $group ) {
            $_group = [
                'id'    => $group['group'],
                'name'  => $group['group'],
                'label' => $group['label'],
            ];
            $this->groups[] = $_group;
            //Init Sections-------------------
            foreach ( $group['sections'] as $section ) {
                $_section = [
                    'name'  => $section['name'],
                    'group' => $group['group'],
                    'title' => $section['title'],
                    'desc'  => isset_or_null( $section['desc'] ),
                ];
                $this->sections[] = $_section;
                //Init Fields-------------------
                foreach ( $section['fields'] as $field ) {
                    $_args = $field['args'];
                    //adds some args automatically
                    $option_id = $section['name'] . '.' . $field['id'];
                    $_args['name'] = $this->page_name . '[' . $section['name'] . '.' . $field['id'] . ']';
                    $_args['id'] = $option_id;
                    $pro_class = ( isset_or_null( $_args['pro'] ) === true ? ' joli-pro' : '' );
                    $_args['class'] = 'tab-' . $group['group'] . $pro_class;
                    $_args['type'] = $field['type'];
                    $_field = [
                        'id'            => $field['id'],
                        'option_id'     => $option_id,
                        'section'       => $section['name'],
                        'group'         => $group['group'],
                        'label'         => $field['title'],
                        'type'          => $field['type'],
                        'default'       => isset_or_null( $field['default'] ),
                        'initial_value' => isset_or_null( $field['initial_value'] ),
                        'args'          => $_args,
                        'name'          => $this->page_name . '[' . $option_id . ']',
                        'sanitize'      => isset_or_null( $field['sanitize'] ),
                    ];
                    $this->fields[] = $_field;
                    $cpt++;
                    // 'fields' => [
                    //     [
                    //         'id' => 'min-width',
                    //         'title' => esc_html__('Minimum width', 'joli-table-of-contents'),
                    //         'type' => 'text',
                    //         'args' => [
                    //             'class' => 'ui-toggle'
                    //         ],
                    //         'default' => '300px',
                    //     ],
                    // ],
                }
            }
        }
    }
    
    public function registerSettings()
    {
        $setting_name = $this->page_name;
        //--Register Sections-----
        // $_section = [
        //     'name' => $this->prefix . $section['name'],
        //     'group' => $this->prefix . $group['id'],
        //     'title' => $section['title'],
        //     'callback' => [ $this->settings_cb, 'sectionCallback'],
        //     // 'desc' => $section['desc'],
        // ];
        foreach ( $this->sections as $section ) {
            add_settings_section(
                $section['name'],
                $section['title'],
                [ $this, 'sectionCallback' ],
                $setting_name
            );
        }
        //--Register Fields-----
        // $_field = [
        //     'id' => $field['id'],
        //     'group' => $this->prefix . $group['id'],
        //     'section' => $this->prefix . $section['name'],
        //     'label' => $field['title'],
        //     // 'desc' => ArrayHelper::getValue($config, 'desc'),
        //     'type' => $field['type'],
        //     'default' => $field['default'],
        //     'args' => $field['args'],
        //     'callback' => $field['callback'],
        //     'name' => $this->prefix . $group['id'] . '[' . $field['id'] . ']',
        // ];
        register_setting( $setting_name, $setting_name, [
            'sanitize_callback' => [ $this, 'sanitizeCallback' ],
        ] );
        foreach ( $this->fields as $field ) {
            add_settings_field(
                $field['section'] . '.' . $field['id'],
                $field['label'],
                [ $this->settings_cb, 'inputField' ],
                // $this->assignFieldCallback( $field[ 'type' ] ),
                $setting_name,
                // $field[ 'group' ],
                $field['section'],
                $field['args']
            );
        }
        //--Register groups-----
        // $_group = [
        //     'id' => $this->prefix . $group['id'],
        //     'name' => $group['id'],
        //     'label' => $group['label'],
        //     'callback' => $group['label'],
        // ];
        // foreach ($this->groups as $group) {
        //     register_setting(
        //         $group[ 'id' ],
        //         $group[ 'id' ],
        //         [
        //             'sanitize_callback' => [ $this->settings_cb, 'sanitizeCallback']
        //         ]
        //     );
        // }
    }
    
    public function sanitizeCallback( $input )
    {
        // [
        //     "general.show-title" => "Title",
        //     ...
        // ]
        foreach ( $input as $option => $value ) {
            $field_item = arrayFind( $option, 'option_id', $this->fields );
            $key = 'sanitize';
            $sanitization = isset_or_null( $field_item[$key] );
            //If no specific sanitation is passed, use the type as default sanitation
            if ( !$sanitization ) {
                $sanitization = isset_or_null( $field_item['type'] );
            }
            //Calls the corresponding sanitization function
            
            if ( $sanitization ) {
                //builds the corresponding method name: ex: sanitizeText found in the SettingsCallbacks class
                $method_name = 'sanitize' . ucfirst( $sanitization );
                if ( method_exists( $this->settings_cb, $method_name ) ) {
                    $input[$option] = call_user_func( [ $this->settings_cb, $method_name ], $value );
                }
            }
        
        }
        return $input;
    }
    
    /**
     * Displays the section description if any
     */
    public function sectionCallback( $args )
    {
        foreach ( $this->sections as $section ) {
            
            if ( $section['name'] === $args['id'] ) {
                if ( isset( $section['desc'] ) && $section['desc'] ) {
                    echo  $section['desc'] ;
                }
                break;
            }
        
        }
    }
    
    /**
     * Setup settings on plugin activation
     *
     * @return void
     */
    public function setupSettings()
    {
        if ( !$this->fields ) {
            $this->initSettings();
        }
        $options = [];
        //runs through each "prepared" option
        foreach ( $this->fields as $field ) {
            $option_item[$field['option_id']] = ( isset( $field['initial_value'] ) ? $field['initial_value'] : $field['default'] );
            $options += $option_item;
            // $option_item[ $field[ 'option_id' ] ] = $field[ 'default' ];
            // $options[] = $option_item;
        }
        //add the option to the database if none
        if ( get_option( $this->page_name ) === false ) {
            add_option( $this->page_name, $options );
        }
        $this->cached_settings = $options;
    }
    
    public function resetSettings()
    {
        delete_option( $this->page_name );
        $this->setupSettings();
    }
    
    /**
     * Gets the global option from the database or from the local cache
     * Ex: getOption( 'general', 'prefix' );
     *
     * @param [type] identifier
     * @param [type] section (parent of identifier)
     * @return mixed option value, default value, or null
     */
    public function getOption( $name, $section )
    {
        $option_selector = $section . '.' . $name;
        $field_item = arrayFind( $name, 'id', $this->fields );
        $default = $field_item['default'];
        
        if ( $this->cached_settings ) {
            $options = $this->cached_settings;
            //get option from cache
        } else {
            $options = get_option( $this->page_name );
            //get option from database
        }
        
        $value = null;
        if ( $options && is_array( $options ) ) {
            $value = $this->fetchOption( $option_selector, $options, $default );
        }
        if ( $value !== null ) {
            return $value;
        }
        return;
    }
    
    /**
     * Fetches the option from the unserialized array
     */
    public function fetchOption( $option_selector, $options, $default = null )
    {
        $value = null;
        if ( is_array( $options ) && array_key_exists( $option_selector, $options ) ) {
            $value = $options[$option_selector];
        }
        if ( $value === null ) {
            return $default;
        }
        return $value;
    }
    
    public function getGroups()
    {
        // (
        //     [id] => general
        //     [name] => general
        //     [label] => General
        // )
        return $this->groups;
    }

}