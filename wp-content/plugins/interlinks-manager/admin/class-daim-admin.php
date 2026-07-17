<?php

/*
 * this class should be used to work with the administrative side of wordpress
 */
class Daim_Admin{

    protected static $instance = null;
    private $shared = null;
    
    private $screen_id_dashboard = null;
    private $screen_id_juice = null;
    private $screen_id_anchors = null;
	private $screen_id_http_status = null;
    private $screen_id_hits = null;
    private $screen_id_wizard = null;
    private $screen_id_autolinks = null;
	private $screen_id_categories = null;
    private $screen_id_term_groups = null;
    private $screen_id_import = null;
    private $screen_id_export = null;
	private $screen_id_maintenance = null;
    private $screen_id_help = null;
    private $screen_id_options = null;
    
    private function __construct() {

        //assign an instance of the plugin info
        $this->shared = Daim_Shared::get_instance();

        //Load admin stylesheets and JavaScript
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
        
        //Write in back end head
        add_action('admin_head', array( $this, 'wr_admin_head' ));
        
        //Add the admin menu
        add_action( 'admin_menu', array( $this, 'me_add_admin_menu' ) );

        //Load the options API registrations and callbacks
        add_action('admin_init', array( $this, 'op_register_options' ) );
        
        //Add the meta box
        add_action( 'add_meta_boxes', array( $this, 'create_meta_box' ) );
        
        //Save the meta box
        add_action( 'save_post', array($this, 'daim_save_meta_interlinks_options') );

	    //Require and instantiate the class used to handle the CSV and XML exports
	    require_once( $this->shared->get( 'dir' ) . 'admin/inc/class-daim-export-controller.php' );
	    new Daim_Export_Controller( $this->shared );

        //this hook is triggered during the creation of a new blog
        add_action('wpmu_new_blog', array($this, 'new_blog_create_options_and_tables'), 10, 6);
        
        //this hook is triggered during the deletion of a blog
        add_action( 'delete_blog', array($this, 'delete_blog_delete_options_and_tables'), 10, 1 );

        //Require and instantiate the class used to register the menu options
        require_once( $this->shared->get( 'dir' ) . 'admin/inc/class-daim-menu-options.php' );
        $this->menu_options = new Daim_Menu_Options( $this->shared );

    }

    /*
     * return an istance of this class
     */
    public static function get_instance() {

        if ( null == self::$instance ) {
            self::$instance = new self;
        }

        return self::$instance;

    }
    
    /*
     * write in the admin head
     */
    public function wr_admin_head(){
        
        echo '<script type="text/javascript">';
            echo 'var daim_ajax_url = "' . admin_url('admin-ajax.php') . '";';
            echo 'var daim_nonce = "' . wp_create_nonce( "daim" ) . '";';
            echo 'var daim_admin_url ="' . get_admin_url() . '";';
        echo '</script>';
        
    }
    
    public function enqueue_admin_styles() {

        $wp_localize_script_data = array(
            'deleteText'         => esc_html__('Delete', 'daextamp'),
            'cancelText'         => esc_html__('Cancel', 'daextamp'),
        );

        $screen = get_current_screen();
        
        //menu dashboard
        if ( $screen->id == $this->screen_id_dashboard ) {
            wp_enqueue_style( $this->shared->get('slug') .'-framework-menu', $this->shared->get('url') . 'admin/assets/css/framework/menu.css', array(), $this->shared->get('ver') );
            wp_enqueue_style( $this->shared->get('slug') .'-menu-dashboard', $this->shared->get('url') . 'admin/assets/css/menu-dashboard.css', array(), $this->shared->get('ver') );
            wp_enqueue_style( $this->shared->get('slug') .'-jquery-ui-tooltip', $this->shared->get('url') . 'admin/assets/css/jquery-ui-tooltip.css', array(), $this->shared->get('ver') );

	        //Chosen
	        wp_enqueue_style($this->shared->get('slug') . '-chosen',
		        $this->shared->get('url') . 'admin/assets/inc/chosen/chosen-min.css', array(),
		        $this->shared->get('ver'));
	        wp_enqueue_style($this->shared->get('slug') . '-chosen-custom',
		        $this->shared->get('url') . 'admin/assets/css/chosen-custom.css', array(), $this->shared->get('ver'));
        }
        
        //menu juice
        if ( $screen->id == $this->screen_id_juice ) {

            wp_enqueue_style( $this->shared->get('slug') .'-framework-menu', $this->shared->get('url') . 'admin/assets/css/framework/menu.css', array(), $this->shared->get('ver') );
            wp_enqueue_style( $this->shared->get('slug') .'-menu-juice', $this->shared->get('url') . 'admin/assets/css/menu-juice.css', array(), $this->shared->get('ver') );
            wp_enqueue_style( $this->shared->get('slug') .'-jquery-ui-tooltip', $this->shared->get('url') . 'admin/assets/css/jquery-ui-tooltip.css', array(), $this->shared->get('ver') );

	        //jQuery UI Dialog
	        wp_enqueue_style($this->shared->get('slug') . '-jquery-ui-dialog',
		        $this->shared->get('url') . 'admin/assets/css/jquery-ui-dialog.css', array(),
		        $this->shared->get('ver'));
	        wp_enqueue_style($this->shared->get('slug') . '-jquery-ui-dialog-custom',
		        $this->shared->get('url') . 'admin/assets/css/jquery-ui-dialog-custom.css', array(),
		        $this->shared->get('ver'));

        }

	    //menu http status
	    if ( $screen->id == $this->screen_id_http_status ) {
		    wp_enqueue_style( $this->shared->get('slug') .'-framework-menu', $this->shared->get('url') . 'admin/assets/css/framework/menu.css', array(), $this->shared->get('ver') );
		    wp_enqueue_style( $this->shared->get('slug') .'-menu-http-status', $this->shared->get('url') . 'admin/assets/css/menu-http-status.css', array(), $this->shared->get('ver') );
		    wp_enqueue_style( $this->shared->get('slug') .'-jquery-ui-tooltip', $this->shared->get('url') . 'admin/assets/css/jquery-ui-tooltip.css', array(), $this->shared->get('ver') );

		    //Chosen
		    wp_enqueue_style($this->shared->get('slug') . '-chosen',
			    $this->shared->get('url') . 'admin/assets/inc/chosen/chosen-min.css', array(),
			    $this->shared->get('ver'));
		    wp_enqueue_style($this->shared->get('slug') . '-chosen-custom',
			    $this->shared->get('url') . 'admin/assets/css/chosen-custom.css', array(), $this->shared->get('ver'));
	    }


	    //menu hits
        if ( $screen->id == $this->screen_id_hits ) {
            wp_enqueue_style( $this->shared->get('slug') .'-framework-menu', $this->shared->get('url') . 'admin/assets/css/framework/menu.css', array(), $this->shared->get('ver') );
            wp_enqueue_style( $this->shared->get('slug') .'-menu-hits', $this->shared->get('url') . 'admin/assets/css/menu-hits.css', array(), $this->shared->get('ver') );
            wp_enqueue_style( $this->shared->get('slug') .'-jquery-ui-tooltip', $this->shared->get('url') . 'admin/assets/css/jquery-ui-tooltip.css', array(), $this->shared->get('ver') );
        }
	    //menu wizard
	    if ( $screen->id == $this->screen_id_wizard ) {
		    wp_enqueue_style( $this->shared->get('slug') .'-framework-menu', $this->shared->get('url') . 'admin/assets/css/framework/menu.css', array(), $this->shared->get('ver') );
		    wp_enqueue_style( $this->shared->get('slug') .'-menu-wizard', $this->shared->get('url') . 'admin/assets/css/menu-wizard.css', array(), $this->shared->get('ver') );
		    wp_enqueue_style( $this->shared->get('slug') .'-jquery-ui-tooltip', $this->shared->get('url') . 'admin/assets/css/jquery-ui-tooltip.css', array(), $this->shared->get('ver') );

		    //Chosen
		    wp_enqueue_style($this->shared->get('slug') . '-chosen',
			    $this->shared->get('url') . 'admin/assets/inc/chosen/chosen-min.css', array(),
			    $this->shared->get('ver'));
		    wp_enqueue_style($this->shared->get('slug') . '-chosen-custom',
			    $this->shared->get('url') . 'admin/assets/css/chosen-custom.css', array(), $this->shared->get('ver'));

		    //Handsontable
		    wp_enqueue_style($this->shared->get('slug') . '-handsontable-full',
			    $this->shared->get('url') . 'admin/assets/inc/handsontable/handsontable.full.min.css', array(),
			    $this->shared->get('ver'));

	    }

        //menu autolinks
        if ( $screen->id == $this->screen_id_autolinks ) {

            wp_enqueue_style( $this->shared->get('slug') .'-framework-menu', $this->shared->get('url') . 'admin/assets/css/framework/menu.css', array(), $this->shared->get('ver') );
            wp_enqueue_style( $this->shared->get('slug') .'-menu-autolinks', $this->shared->get('url') . 'admin/assets/css/menu-autolinks.css', array(), $this->shared->get('ver') );
            wp_enqueue_style( $this->shared->get('slug') .'-jquery-ui-tooltip', $this->shared->get('url') . 'admin/assets/css/jquery-ui-tooltip.css', array(), $this->shared->get('ver') );

	        //Chosen
	        wp_enqueue_style($this->shared->get('slug') . '-chosen',
		        $this->shared->get('url') . 'admin/assets/inc/chosen/chosen-min.css', array(),
		        $this->shared->get('ver'));
	        wp_enqueue_style($this->shared->get('slug') . '-chosen-custom',
		        $this->shared->get('url') . 'admin/assets/css/chosen-custom.css', array(), $this->shared->get('ver'));

            //jQuery UI Dialog
            wp_enqueue_style($this->shared->get('slug') . '-jquery-ui-dialog',
                $this->shared->get('url') . 'admin/assets/css/jquery-ui-dialog.css', array(),
                $this->shared->get('ver'));
            wp_enqueue_style($this->shared->get('slug') . '-jquery-ui-dialog-custom',
                $this->shared->get('url') . 'admin/assets/css/jquery-ui-dialog-custom.css', array(),
                $this->shared->get('ver'));

        }

	    //menu categories
	    if ( $screen->id == $this->screen_id_categories ) {

		    wp_enqueue_style( $this->shared->get('slug') .'-framework-menu', $this->shared->get('url') . 'admin/assets/css/framework/menu.css', array(), $this->shared->get('ver') );
		    wp_enqueue_style( $this->shared->get('slug') .'-menu-categories', $this->shared->get('url') . 'admin/assets/css/menu-categories.css', array(), $this->shared->get('ver') );
		    wp_enqueue_style( $this->shared->get('slug') .'-jquery-ui-tooltip', $this->shared->get('url') . 'admin/assets/css/jquery-ui-tooltip.css', array(), $this->shared->get('ver') );

            //jQuery UI Dialog
            wp_enqueue_style($this->shared->get('slug') . '-jquery-ui-dialog',
                $this->shared->get('url') . 'admin/assets/css/jquery-ui-dialog.css', array(),
                $this->shared->get('ver'));
            wp_enqueue_style($this->shared->get('slug') . '-jquery-ui-dialog-custom',
                $this->shared->get('url') . 'admin/assets/css/jquery-ui-dialog-custom.css', array(),
                $this->shared->get('ver'));

	    }

        //menu term groups
        if ( $screen->id == $this->screen_id_term_groups ) {

            wp_enqueue_style( $this->shared->get('slug') .'-framework-menu', $this->shared->get('url') . 'admin/assets/css/framework/menu.css', array(), $this->shared->get('ver') );
            wp_enqueue_style( $this->shared->get('slug') .'-menu-term-groups', $this->shared->get('url') . 'admin/assets/css/menu-term-groups.css', array(), $this->shared->get('ver') );
            wp_enqueue_style( $this->shared->get('slug') .'-jquery-ui-tooltip', $this->shared->get('url') . 'admin/assets/css/jquery-ui-tooltip.css', array(), $this->shared->get('ver') );

            //Chosen
            wp_enqueue_style($this->shared->get('slug') . '-chosen',
                $this->shared->get('url') . 'admin/assets/inc/chosen/chosen-min.css', array(),
                $this->shared->get('ver'));
            wp_enqueue_style($this->shared->get('slug') . '-chosen-custom',
                $this->shared->get('url') . 'admin/assets/css/chosen-custom.css', array(), $this->shared->get('ver'));

            //jQuery UI Dialog
            wp_enqueue_style($this->shared->get('slug') . '-jquery-ui-dialog',
                $this->shared->get('url') . 'admin/assets/css/jquery-ui-dialog.css', array(),
                $this->shared->get('ver'));
            wp_enqueue_style($this->shared->get('slug') . '-jquery-ui-dialog-custom',
                $this->shared->get('url') . 'admin/assets/css/jquery-ui-dialog-custom.css', array(),
                $this->shared->get('ver'));

        }

	    //Menu Maintenance
	    if ($screen->id == $this->screen_id_maintenance) {

		    //Framework Menu
		    wp_enqueue_style($this->shared->get('slug') . '-framework-menu',
			    $this->shared->get('url') . 'admin/assets/css/framework/menu.css', array(), $this->shared->get('ver'));

		    //jQuery UI Dialog
		    wp_enqueue_style($this->shared->get('slug') . '-jquery-ui-dialog',
			    $this->shared->get('url') . 'admin/assets/css/jquery-ui-dialog.css', array(),
			    $this->shared->get('ver'));
		    wp_enqueue_style($this->shared->get('slug') . '-jquery-ui-dialog-custom',
			    $this->shared->get('url') . 'admin/assets/css/jquery-ui-dialog-custom.css', array(),
			    $this->shared->get('ver'));

		    //jQuery UI Tooltip
		    wp_enqueue_style($this->shared->get('slug') . '-jquery-ui-tooltip',
			    $this->shared->get('url') . 'admin/assets/css/jquery-ui-tooltip.css', array(),
			    $this->shared->get('ver'));

		    //Chosen
		    wp_enqueue_style($this->shared->get('slug') . '-chosen',
			    $this->shared->get('url') . 'admin/assets/inc/chosen/chosen-min.css', array(),
			    $this->shared->get('ver'));
		    wp_enqueue_style($this->shared->get('slug') . '-chosen-custom',
			    $this->shared->get('url') . 'admin/assets/css/chosen-custom.css', array(), $this->shared->get('ver'));

	    }

        //Menu Help
        if ($screen->id == $this->screen_id_help) {

            wp_enqueue_style($this->shared->get('slug') . '-menu-help',
                $this->shared->get('url') . 'admin/assets/css/menu-help.css', array(), $this->shared->get('ver'));

        }

        //menu options
        if ( $screen->id == $this->screen_id_options ) {
            wp_enqueue_style( $this->shared->get('slug') .'-framework-options', $this->shared->get('url') . 'admin/assets/css/framework/options.css', array(), $this->shared->get('ver') );
            wp_enqueue_style( $this->shared->get('slug') .'-jquery-ui-tooltip', $this->shared->get('url') . 'admin/assets/css/jquery-ui-tooltip.css', array(), $this->shared->get('ver') );

	        //Chosen
	        wp_enqueue_style($this->shared->get('slug') . '-chosen',
		        $this->shared->get('url') . 'admin/assets/inc/chosen/chosen-min.css', array(),
		        $this->shared->get('ver'));
	        wp_enqueue_style($this->shared->get('slug') . '-chosen-custom',
		        $this->shared->get('url') . 'admin/assets/css/chosen-custom.css', array(), $this->shared->get('ver'));

        }
        
        /*
         * Load the post editor CSS if at least one of the three meta box is
         * enabled with the current $screen->id 
         */
        $load_post_editor_css = false;

        $interlinks_options_post_types_a = maybe_unserialize(get_option($this->shared->get('slug') . '_interlinks_options_post_types' ));
        if(is_array($interlinks_options_post_types_a) and in_array($screen->id, $interlinks_options_post_types_a)){
            $load_post_editor_css = true;
        }

        $interlinks_optimization_post_types_a = maybe_unserialize(get_option($this->shared->get('slug') . '_interlinks_optimization_post_types' ));
        if(is_array($interlinks_optimization_post_types_a) and in_array($screen->id, $interlinks_optimization_post_types_a)){
            $load_post_editor_css = true;
        }

        $interlinks_suggestions_post_types_a = maybe_unserialize(get_option($this->shared->get('slug') . '_interlinks_suggestions_post_types' ));
        if(is_array($interlinks_suggestions_post_types_a) and in_array($screen->id, $interlinks_suggestions_post_types_a)){
            $load_post_editor_css = true;
        }
        
        if($load_post_editor_css){

            //JQuery UI Tooltips
            wp_enqueue_style( $this->shared->get('slug') .'-jquery-ui-tooltip', $this->shared->get('url') . 'admin/assets/css/jquery-ui-tooltip.css', array(), $this->shared->get('ver') );

            //Post Editor CSS
            wp_enqueue_style( $this->shared->get('slug') .'-post-editor', $this->shared->get('url') . 'admin/assets/css/post-editor.css', array(), $this->shared->get('ver') );

	        //Chosen
	        wp_enqueue_style($this->shared->get('slug') . '-chosen',
		        $this->shared->get('url') . 'admin/assets/inc/chosen/chosen-min.css', array(),
		        $this->shared->get('ver'));
	        wp_enqueue_style($this->shared->get('slug') . '-chosen-custom',
		        $this->shared->get('url') . 'admin/assets/css/chosen-custom.css', array(), $this->shared->get('ver'));

        }

    }
    
    /*
     * enqueue admin-specific javascript
     */
    public function enqueue_admin_scripts() {

	    $wp_localize_script_data = array(
		    'deleteText'         => strip_tags(__('Delete', 'daim')),
		    'cancelText'         => strip_tags(__('Cancel', 'daim')),
		    'chooseAnOptionText' => strip_tags(__('Choose an Option ...', 'daim')),
		    'wizardRows' => intval(get_option($this->shared->get('slug') . '_wizard_rows'), 10),
		    'closeText'         => strip_tags(__('Close', 'daim')),
		    'postText'         => strip_tags(__('Post', 'daim')),
		    'anchorTextText'         => strip_tags(__('Anchor Text', 'daim')),
		    'juiceText'         => strip_tags(__('Juice (Value)', 'daim')),
		    'juiceVisualText'         => strip_tags(__('Juice (Visual)', 'daim')),
		    'postTooltipText'         => strip_tags(__('The post that includes the link.', 'daim')),
		    'anchorTextTooltipText'         => strip_tags(__('The anchor text of the link.', 'daim')),
		    'juiceTooltipText'         => strip_tags(__('The link juice generated by the link.', 'daim')),
		    'juiceVisualTooltipText'         => strip_tags(__('The visual representation of the link juice generated by the link.', 'daim')),
            'juiceModalTitleText'         => strip_tags(__('Internal Inbound Links for', 'daim')),
		    'itemsText'         => strip_tags(__('items', 'daim'))
	    );

        $screen = get_current_screen();
        
        //menu dashboard
        if ( $screen->id == $this->screen_id_dashboard ) {
            wp_enqueue_script( $this->shared->get('slug') . '-menu-dashboard', $this->shared->get('url') . 'admin/assets/js/menu-dashboard.js', array('jquery'), $this->shared->get('ver') );
            wp_enqueue_script('jquery-ui-tooltip');
            wp_enqueue_script( $this->shared->get('slug') . '-jquery-ui-tooltip-init', $this->shared->get('url') . 'admin/assets/js/jquery-ui-tooltip-init.js', array('jquery'), $this->shared->get('ver') );

	        //Chosen
	        wp_enqueue_script($this->shared->get('slug') . '-chosen',
		        $this->shared->get('url') . 'admin/assets/inc/chosen/chosen-min.js', array('jquery'),
		        $this->shared->get('ver'));
	        wp_enqueue_script($this->shared->get('slug') . '-chosen-init',
		        $this->shared->get('url') . 'admin/assets/js/chosen-init.js', array('jquery'),
		        $this->shared->get('ver'));
	        wp_localize_script($this->shared->get('slug') . '-chosen-init', 'objectL10n', $wp_localize_script_data);
        }
        
        //menu juice
        if ( $screen->id == $this->screen_id_juice ) {

            wp_enqueue_script( $this->shared->get('slug') . '-menu-juice', $this->shared->get('url') . 'admin/assets/js/menu-juice.js', array('jquery', 'jquery-ui-dialog'), $this->shared->get('ver') );
	        wp_localize_script($this->shared->get('slug') . '-menu-juice', 'objectL10n', $wp_localize_script_data);

            wp_enqueue_script('jquery-ui-tooltip');
            wp_enqueue_script( $this->shared->get('slug') . '-jquery-ui-tooltip-init', $this->shared->get('url') . 'admin/assets/js/jquery-ui-tooltip-init.js', array('jquery'), $this->shared->get('ver') );

        }
        
        //menu anchors
        if ( $screen->id == $this->screen_id_anchors ) {
            wp_enqueue_script('jquery-ui-tooltip');
            wp_enqueue_script( $this->shared->get('slug') . '-jquery-ui-tooltip-init', $this->shared->get('url') . 'admin/assets/js/jquery-ui-tooltip-init.js', array('jquery'), $this->shared->get('ver') );
        }

	    //menu http status
	    if ( $screen->id == $this->screen_id_http_status ) {
		    wp_enqueue_script( $this->shared->get('slug') . '-menu-http-status', $this->shared->get('url') . 'admin/assets/js/menu-http-status.js', array('jquery'), $this->shared->get('ver') );
		    wp_enqueue_script('jquery-ui-tooltip');
		    wp_enqueue_script( $this->shared->get('slug') . '-jquery-ui-tooltip-init', $this->shared->get('url') . 'admin/assets/js/jquery-ui-tooltip-init.js', array('jquery'), $this->shared->get('ver') );

		    //Chosen
		    wp_enqueue_script($this->shared->get('slug') . '-chosen',
			    $this->shared->get('url') . 'admin/assets/inc/chosen/chosen-min.js', array('jquery'),
			    $this->shared->get('ver'));
		    wp_enqueue_script($this->shared->get('slug') . '-chosen-init',
			    $this->shared->get('url') . 'admin/assets/js/chosen-init.js', array('jquery'),
			    $this->shared->get('ver'));
		    wp_localize_script($this->shared->get('slug') . '-chosen-init', 'objectL10n', $wp_localize_script_data);
	    }

        //menu hits
        if ( $screen->id == $this->screen_id_hits ) {
            wp_enqueue_script('jquery-ui-tooltip');
            wp_enqueue_script( $this->shared->get('slug') . '-jquery-ui-tooltip-init', $this->shared->get('url') . 'admin/assets/js/jquery-ui-tooltip-init.js', array('jquery'), $this->shared->get('ver') );
        }

	    //menu wizard
	    if ( $screen->id == $this->screen_id_wizard ) {

		    wp_enqueue_script( $this->shared->get('slug') . '-menu-wizard', $this->shared->get('url') . 'admin/assets/js/menu-wizard.js', array('jquery'), $this->shared->get('ver') );
		    wp_localize_script($this->shared->get('slug') . '-menu-wizard', 'objectL10n', $wp_localize_script_data);
		    wp_enqueue_script('jquery-ui-tooltip');
		    wp_enqueue_script( $this->shared->get('slug') . '-jquery-ui-tooltip-init', $this->shared->get('url') . 'admin/assets/js/jquery-ui-tooltip-init.js', array('jquery'), $this->shared->get('ver') );

		    //Chosen
		    wp_enqueue_script($this->shared->get('slug') . '-chosen',
			    $this->shared->get('url') . 'admin/assets/inc/chosen/chosen-min.js', array('jquery'),
			    $this->shared->get('ver'));
		    wp_enqueue_script($this->shared->get('slug') . '-chosen-init',
			    $this->shared->get('url') . 'admin/assets/js/chosen-init.js', array('jquery'),
			    $this->shared->get('ver'));
		    wp_localize_script($this->shared->get('slug') . '-chosen-init', 'objectL10n', $wp_localize_script_data);

		    //Handsontable
		    wp_enqueue_script($this->shared->get('slug') . '-handsontable-full',
			    $this->shared->get('url') . 'admin/assets/inc/handsontable/handsontable.full.min.js', array('jquery'),
			    $this->shared->get('ver'));

	    }

        //menu autolinks
        if ( $screen->id == $this->screen_id_autolinks ) {

	        wp_enqueue_script( $this->shared->get('slug') . '-menu-autolinks', $this->shared->get('url') . 'admin/assets/js/menu-autolinks.js', array('jquery', 'jquery-ui-dialog'), $this->shared->get('ver') );
            wp_enqueue_script('jquery-ui-tooltip');
            wp_enqueue_script( $this->shared->get('slug') . '-jquery-ui-tooltip-init', $this->shared->get('url') . 'admin/assets/js/jquery-ui-tooltip-init.js', array('jquery'), $this->shared->get('ver') );

	        //Chosen
	        wp_enqueue_script($this->shared->get('slug') . '-chosen',
		        $this->shared->get('url') . 'admin/assets/inc/chosen/chosen-min.js', array('jquery'),
		        $this->shared->get('ver'));
	        wp_enqueue_script($this->shared->get('slug') . '-chosen-init',
		        $this->shared->get('url') . 'admin/assets/js/chosen-init.js', array('jquery'),
		        $this->shared->get('ver'));
	        wp_localize_script($this->shared->get('slug') . '-chosen-init', 'objectL10n', $wp_localize_script_data);

        }

	    //menu categories
	    if ( $screen->id == $this->screen_id_categories ) {

	        //Categories Menu
            wp_enqueue_script($this->shared->get('slug') . '-menu-categories',
                $this->shared->get('url') . 'admin/assets/js/menu-categories.js', array('jquery', 'jquery-ui-dialog'),
                $this->shared->get('ver'));
            wp_localize_script($this->shared->get('slug') . '-menu-categories', 'objectL10n', $wp_localize_script_data);

		    wp_enqueue_script('jquery-ui-tooltip');
		    wp_enqueue_script( $this->shared->get('slug') . '-jquery-ui-tooltip-init', $this->shared->get('url') . 'admin/assets/js/jquery-ui-tooltip-init.js', array('jquery'), $this->shared->get('ver') );

	    }

        //menu term groups
        if ( $screen->id == $this->screen_id_term_groups ) {

            wp_enqueue_script( $this->shared->get('slug') . '-menu-term-groups', $this->shared->get('url') . 'admin/assets/js/menu-term-groups.js', array('jquery', 'jquery-ui-dialog'), $this->shared->get('ver') );
            wp_localize_script($this->shared->get('slug') . '-menu-term-groups', 'objectL10n', $wp_localize_script_data);

            //jQuery UI Tooltip
            wp_enqueue_script('jquery-ui-tooltip');
            wp_enqueue_script($this->shared->get('slug') . '-jquery-ui-tooltip-init',
                $this->shared->get('url') . 'admin/assets/js/jquery-ui-tooltip-init.js', array('jquery'),
                $this->shared->get('ver'));

            //Chosen
            wp_enqueue_script($this->shared->get('slug') . '-chosen',
                $this->shared->get('url') . 'admin/assets/inc/chosen/chosen-min.js', array('jquery'),
                $this->shared->get('ver'));
            wp_enqueue_script($this->shared->get('slug') . '-jquery-ui-chosen-init',
                $this->shared->get('url') . 'admin/assets/js/chosen-init.js', array('jquery'),
                $this->shared->get('ver'));
            wp_localize_script($this->shared->get('slug') . '-chosen-init', 'objectL10n', $wp_localize_script_data);

        }

	    //Menu Maintenance
	    if ($screen->id == $this->screen_id_maintenance) {

		    //Maintenance Menu
		    wp_enqueue_script($this->shared->get('slug') . '-menu-maintenance',
			    $this->shared->get('url') . 'admin/assets/js/menu-maintenance.js', array('jquery', 'jquery-ui-dialog'),
			    $this->shared->get('ver'));
		    wp_localize_script($this->shared->get('slug') . '-menu-maintenance', 'objectL10n',
			    $wp_localize_script_data);

		    //jQuery UI Tooltip
		    wp_enqueue_script('jquery-ui-tooltip');
		    wp_enqueue_script($this->shared->get('slug') . '-jquery-ui-tooltip-init',
			    $this->shared->get('url') . 'admin/assets/js/jquery-ui-tooltip-init.js', array('jquery'),
			    $this->shared->get('ver'));

		    //Chosen
		    wp_enqueue_script($this->shared->get('slug') . '-chosen',
			    $this->shared->get('url') . 'admin/assets/inc/chosen/chosen-min.js', array('jquery'),
			    $this->shared->get('ver'));
		    wp_enqueue_script($this->shared->get('slug') . '-jquery-ui-chosen-init',
			    $this->shared->get('url') . 'admin/assets/js/chosen-init.js', array('jquery'),
			    $this->shared->get('ver'));
		    wp_localize_script($this->shared->get('slug') . '-chosen-init', 'objectL10n', $wp_localize_script_data);

	    }
        
        //menu options
        if( $screen->id == $this->screen_id_options ){
            wp_enqueue_script('jquery-ui-tooltip');
            wp_enqueue_script( $this->shared->get('slug') . '-jquery-ui-tooltip-init', $this->shared->get('url') . 'admin/assets/js/jquery-ui-tooltip-init.js', array('jquery'), $this->shared->get('ver') );

	        //Chosen
	        wp_enqueue_script($this->shared->get('slug') . '-chosen',
		        $this->shared->get('url') . 'admin/assets/inc/chosen/chosen-min.js', array('jquery'),
		        $this->shared->get('ver'));
	        wp_enqueue_script($this->shared->get('slug') . '-chosen-init',
		        $this->shared->get('url') . 'admin/assets/js/chosen-init.js', array('jquery'),
		        $this->shared->get('ver'));
	        wp_localize_script($this->shared->get('slug') . '-chosen-init', 'objectL10n', $wp_localize_script_data);

        }
        
        /*
         * Load the post editor JS if at least one of the three meta box is
         * enabled with the current $screen->id 
         */
        $load_post_editor_js = false;

        $interlinks_options_post_types_a = maybe_unserialize(get_option($this->shared->get('slug') . '_interlinks_options_post_types' ));
        if(is_array($interlinks_options_post_types_a) and in_array($screen->id, $interlinks_options_post_types_a)){
            $load_post_editor_js = true;
        }

        $interlinks_optimization_post_types_a = maybe_unserialize(get_option($this->shared->get('slug') . '_interlinks_optimization_post_types' ));
        if(is_array($interlinks_optimization_post_types_a) and in_array($screen->id, $interlinks_optimization_post_types_a)){
            $load_post_editor_js = true;
        }

        $interlinks_suggestions_post_types_a = maybe_unserialize(get_option($this->shared->get('slug') . '_interlinks_suggestions_post_types' ));
        if(is_array($interlinks_suggestions_post_types_a) and in_array($screen->id, $interlinks_suggestions_post_types_a)){
            $load_post_editor_js = true;
        }
        
        if($load_post_editor_js){

            //JQuery UI Tooltips
	        wp_enqueue_script('jquery-ui-tooltip');
            wp_enqueue_script( $this->shared->get('slug') . '-jquery-ui-tooltip-init', $this->shared->get('url') . 'admin/assets/js/jquery-ui-tooltip-init.js', array('jquery'), $this->shared->get('ver') );

            //Post Editor Js
            wp_enqueue_script( $this->shared->get('slug') . '-post-editor', $this->shared->get('url') . 'admin/assets/js/post-editor.js', array('jquery'), $this->shared->get('ver') );

	        //Chosen
	        wp_enqueue_script($this->shared->get('slug') . '-chosen',
		        $this->shared->get('url') . 'admin/assets/inc/chosen/chosen-min.js', array('jquery'),
		        $this->shared->get('ver'));
	        wp_enqueue_script($this->shared->get('slug') . '-chosen-init',
		        $this->shared->get('url') . 'admin/assets/js/chosen-init.js', array('jquery'),
		        $this->shared->get('ver'));
	        wp_localize_script($this->shared->get('slug') . '-chosen-init', 'objectL10n', $wp_localize_script_data);

        }

    }
    
    /*
     * plugin activation
     */
    public function ac_activate($networkwide){
        
        /*
         * delete options and tables for all the sites in the network
         */
        if(function_exists('is_multisite') and is_multisite()) {

            /*
             * if this is a "Network Activation" create the options and tables
             * for each blog
             */
            if ($networkwide) {
            
                //get the current blog id
                global $wpdb;
                $current_blog = $wpdb->blogid;

                //create an array with all the blog ids
                $blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");

                //iterate through all the blogs
                foreach ($blogids as $blog_id){

                    //swith to the iterated blog
                    switch_to_blog($blog_id);

                    //create options and tables for the iterated blog
                    $this->ac_initialize_options();
                    $this->ac_create_database_tables();

                }

                //switch to the current blog
                switch_to_blog($current_blog);
                
            }else{
                
                /*
                 * if this is not a "Network Activation" create options and
                 * tables only for the current blog
                 */
                $this->ac_initialize_options();
                $this->ac_create_database_tables();

            }

        }else{

            /*
             * if this is not a multisite installation create options and
             * tables only for the current blog
             */
            $this->ac_initialize_options();
            $this->ac_create_database_tables();

        }
        
    }

    //create the options and tables for the newly created blog
    public function new_blog_create_options_and_tables($blog_id, $user_id, $domain, $path, $site_id, $meta ) {

        global $wpdb;

        /*
         * if the plugin is "Network Active" create the options and tables for
         * this new blog
         */
        if(is_plugin_active_for_network('interlinks-manager/init.php')){

            //get the id of the current blog
            $current_blog = $wpdb->blogid;

            //switch to the blog that is being activated
            switch_to_blog($blog_id);

            //create options and database tables for the new blog
            $this->ac_initialize_options();
            $this->ac_create_database_tables();

            //switch to the current blog
            switch_to_blog($current_blog);

        }

    }

    //delete options and tables for the deleted blog
    public function delete_blog_delete_options_and_tables($blog_id){

        global $wpdb;
        
        //get the id of the current blog
        $current_blog = $wpdb->blogid;

        //switch to the blog that is being activated
        switch_to_blog($blog_id);

        //create options and database tables for the new blog
        $this->un_delete_options();
        $this->un_delete_database_tables();

        //switch to the current blog
        switch_to_blog($current_blog);

    }

    /*
     * initialize plugin options
     */
    private function ac_initialize_options(){

        foreach($this->shared->get('options') as $key => $value){
            add_option($key, $value);
        }

    }

    /*
     * create the plugin database tables
     */
    private function ac_create_database_tables(){

        global $wpdb;

        //Get the database character collate that will be appended at the end of each query
        $charset_collate = $wpdb->get_charset_collate();

        //check database version and create the database
        if( intval(get_option( $this->shared->get('slug') . '_database_version'), 10) < 5 ){

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

            //create *prefix*_archive
            $table_name = $wpdb->prefix . $this->shared->get('slug') . "_archive";
            $sql = "CREATE TABLE $table_name (
                id bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                post_id bigint(20) NOT NULL DEFAULT '0',
                post_title text NOT NULL DEFAULT '',
                post_type varchar(20) NOT NULL DEFAULT '',
                post_date datetime DEFAULT NULL,
                manual_interlinks bigint(20) NOT NULL DEFAULT '0',
                auto_interlinks bigint(20) NOT NULL DEFAULT '0',
                iil bigint(20) NOT NULL DEFAULT '0',
                content_length bigint(20) NOT NULL DEFAULT '0',
                recommended_interlinks bigint(20) NOT NULL DEFAULT '0',
                optimization tinyint(1) NOT NULL DEFAULT '0'
            ) $charset_collate";

            dbDelta($sql);
            
            //create *prefix*_juice
            global $wpdb;
            $table_name = $wpdb->prefix . $this->shared->get('slug') . "_juice";
            $sql = "CREATE TABLE $table_name (
                id bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                url varchar(2083) NOT NULL DEFAULT '',
                iil bigint(20) NOT NULL DEFAULT '0',
                juice bigint(20) NOT NULL DEFAULT '0',
                juice_relative bigint(20) NOT NULL DEFAULT '0'
            ) $charset_collate";

            dbDelta($sql);
            
            //create *prefix*_anchors
            global $wpdb;
            $table_name = $wpdb->prefix . $this->shared->get('slug') . "_anchors";
            $sql = "CREATE TABLE $table_name (
                id bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                url varchar(2083) NOT NULL DEFAULT '',
                anchor longtext NOT NULL DEFAULT '',
                post_id bigint(20) NOT NULL DEFAULT '0',
                post_title text NOT NULL DEFAULT '',
                juice bigint(20) NOT NULL DEFAULT '0'
            ) $charset_collate";

            dbDelta($sql);
            
            //create *prefix*_hits
            global $wpdb;
            $table_name = $wpdb->prefix . $this->shared->get('slug') . "_hits";
            $sql = "CREATE TABLE $table_name (
                id bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                source_post_id bigint(20) NOT NULL DEFAULT '0',
                post_title text NOT NULL DEFAULT '',
                target_url varchar(2083) NOT NULL DEFAULT '',
                date datetime DEFAULT NULL,
                date_gmt datetime DEFAULT NULL,
                link_type tinyint(1) NOT NULL DEFAULT '0'
            ) $charset_collate";

            dbDelta($sql);
            
            //create *prefix*_autolinks
            global $wpdb;
            $table_name = $wpdb->prefix . $this->shared->get('slug') . "_autolinks";
            $sql = "CREATE TABLE $table_name (
                id bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                name TEXT NOT NULL DEFAULT '',
                category_id BIGINT NOT NULL DEFAULT 0,
                keyword varchar(255) NOT NULL DEFAULT '',
                url varchar(2083) NOT NULL DEFAULT '',
                title varchar(1024) NOT NULL DEFAULT '',
                string_before int(11) NOT NULL DEFAULT '1',
                string_after int(11) NOT NULL DEFAULT '1',
                keyword_before VARCHAR(255) NOT NULL DEFAULT '',
                keyword_after VARCHAR(255) NOT NULL DEFAULT '',
                activate_post_types varchar(1000) NOT NULL DEFAULT '',
                categories TEXT NOT NULL DEFAULT '',
                tags TEXT NOT NULL DEFAULT '',
                term_group_id BIGINT NOT NULL DEFAULT 0,
                max_number_autolinks int(11) NOT NULL DEFAULT '0',
                case_insensitive_search tinyint(1) NOT NULL DEFAULT '0',
                open_new_tab tinyint(1) NOT NULL DEFAULT '0',
                use_nofollow tinyint(1) NOT NULL DEFAULT '0',
                priority int(11) NOT NULL DEFAULT '0'
            ) $charset_collate";

            dbDelta($sql);

	        //create *prefix*_category
	        $table_name = $wpdb->prefix . $this->shared->get('slug') . "_category";
	        $sql        = "CREATE TABLE $table_name (
                category_id bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                name TEXT,
                description TEXT
            ) $charset_collate";

	        dbDelta($sql);

            //create *prefix*_term_group
            $table_name = $wpdb->prefix . $this->shared->get('slug') . "_term_group";
            $query_part = '';
            for ($i = 1; $i <= 50; $i++) {
                $query_part .= 'post_type_' . $i . ' TEXT,
                ';
                $query_part .= 'taxonomy_' . $i . ' TEXT,
                ';
                $query_part .= 'term_' . $i . ' BIGINT';
                if ($i !== 50) {
                    $query_part .= ',
                    ';
                }
            }
            $sql = "CREATE TABLE $table_name (
                term_group_id bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100),
                $query_part
            ) $charset_collate";

	        dbDelta($sql);

            //create *prefix*_http_status
	        $table_name = $wpdb->prefix . $this->shared->get('slug') . "_http_status";
	        $sql = "CREATE TABLE $table_name (
                id bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                post_id bigint(20) NOT NULL DEFAULT '0',
                post_title text NOT NULL DEFAULT '',
                url text NOT NULL DEFAULT '',
                anchor text NOT NULL DEFAULT '',
                checked tinyint(1) NOT NULL DEFAULT 0,
                last_check_date datetime DEFAULT NULL,
                last_check_date_gmt datetime DEFAULT NULL,
                code text NOT NULL DEFAULT ''
            ) $charset_collate";

            dbDelta($sql);

            //Update database version
            update_option( $this->shared->get('slug') . '_database_version',"5");

            //Make the database data compatible with the new plugin versions
            $this->shared->convert_database_data();

            //Make the options compatible with the new plugin versions
            $this->shared->convert_options_data();

        }

    }

    /*
     * plugin delete
     */
    static public function un_delete(){
        
        /*
         * delete options and tables for all the sites in the network
         */
        if(function_exists('is_multisite') and is_multisite()) {

            //get the current blog id
            global $wpdb;
            $current_blog = $wpdb->blogid;

            //create an array with all the blog ids
            $blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");

            //iterate through all the blogs
            foreach ($blogids as $blog_id){

                //swith to the iterated blog
                switch_to_blog($blog_id);

                //create options and tables for the iterated blog
                Daim_Admin::un_delete_options();
                Daim_Admin::un_delete_database_tables();

            }

            //switch to the current blog
            switch_to_blog($current_blog);

        }else{

            /*
             * if this is not a multisite installation delete options and
             * tables only for the current blog
             */
            Daim_Admin::un_delete_options();
            Daim_Admin::un_delete_database_tables();

        }
        
    }

    /*
     * delete plugin options
     */
    static public function un_delete_options(){

        //assign an instance of Daextamp_Shared
        $shared = Daim_Shared::get_instance();

        foreach($shared->get('options') as $key => $value){
            delete_option($key);
        }

    }

    /*
     * delete plugin database tables
     */
    static public function un_delete_database_tables(){

        //assign an instance of Daim_Shared
        $shared = Daim_Shared::get_instance();

        global $wpdb;
        
        $table_name = $wpdb->prefix . $shared->get('slug') . "_archive";
        $sql = "DROP TABLE $table_name";  
        $wpdb->query($sql);

        $table_name = $wpdb->prefix . $shared->get('slug') . "_juice";
        $sql = "DROP TABLE $table_name";  
        $wpdb->query($sql);
        
        $table_name = $wpdb->prefix . $shared->get('slug') . "_anchors";
        $sql = "DROP TABLE $table_name";  
        $wpdb->query($sql);
        
        $table_name = $wpdb->prefix . $shared->get('slug') . "_hits";
        $sql = "DROP TABLE $table_name";  
        $wpdb->query($sql);
        
        $table_name = $wpdb->prefix . $shared->get('slug') . "_autolinks";
        $sql = "DROP TABLE $table_name";  
        $wpdb->query($sql);

	    $table_name = $wpdb->prefix . $shared->get('slug') . "_category";
	    $sql = "DROP TABLE $table_name";
	    $wpdb->query($sql);

	    $table_name = $wpdb->prefix . $shared->get('slug') . "_term_group";
	    $sql = "DROP TABLE $table_name";
	    $wpdb->query($sql);

	    $table_name = $wpdb->prefix . $shared->get('slug') . "_http_status";
	    $sql = "DROP TABLE $table_name";
	    $wpdb->query($sql);

    }

    /*
     * register the admin menu
     */
    public function me_add_admin_menu() {
        
        add_menu_page(
	        esc_html__('IM', 'daim'),
            esc_html__('Interlinks', 'daim'),
            get_option( $this->shared->get('slug') . "_dashboard_menu_required_capability"),
            $this->shared->get('slug') . '-dashboard',
            array( $this, 'me_display_menu_dashboard'),
            'dashicons-admin-links'
        );

        $this->screen_id_dashboard = add_submenu_page(
            $this->shared->get('slug') . '-dashboard',
	        esc_html__('IM - Dashboard', 'daim'),
            esc_html__('Dashboard', 'daim'),
            get_option( $this->shared->get('slug') . '_dashboard_menu_required_capability'),
            $this->shared->get('slug') . '-dashboard',
            array( $this, 'me_display_menu_dashboard')
        );
        
        $this->screen_id_juice = add_submenu_page(
            $this->shared->get('slug') . '-dashboard',
	        esc_html__('IM - Juice', 'daim'),
            esc_html__('Juice', 'daim'),
            get_option( $this->shared->get('slug') . "_juice_menu_required_capability"),
            $this->shared->get('slug') . '-juice',
            array( $this, 'me_display_menu_juice')
        );

	    $this->screen_id_http_status = add_submenu_page(
		    $this->shared->get('slug') . '-dashboard',
		    esc_html__('IM - HTTP Status', 'daim'),
		    esc_html__('HTTP Status', 'daim'),
		    get_option( $this->shared->get('slug') . "_http_status_menu_required_capability"),
		    $this->shared->get('slug') . '-http-status',
		    array( $this, 'me_display_menu_http_status')
	    );

        $this->screen_id_hits = add_submenu_page(
            $this->shared->get('slug') . '-dashboard',
	        esc_html__('IM - Hits', 'daim'),
            esc_html__('Hits', 'daim'),
            get_option( $this->shared->get('slug') . "_hits_menu_required_capability"),
            $this->shared->get('slug') . '-hits',
            array( $this, 'me_display_menu_hits')
        );

	    $this->screen_id_wizard = add_submenu_page(
		    $this->shared->get('slug') . '-dashboard',
		    esc_html__('IM - Wizard', 'daim'),
		    esc_html__('Wizard', 'daim'),
		    get_option( $this->shared->get('slug') . "_wizard_menu_required_capability"),
		    $this->shared->get('slug') . '-wizard',
		    array( $this, 'me_display_menu_wizard')
	    );

        $this->screen_id_autolinks = add_submenu_page(
            $this->shared->get('slug') . '-dashboard',
	        esc_html__('IM - AIL', 'daim'),
            esc_html__('AIL', 'daim'),
            get_option( $this->shared->get('slug') . "_ail_menu_required_capability"),
            $this->shared->get('slug') . '-autolinks',
            array( $this, 'me_display_menu_autolinks')
        );

	    $this->screen_id_categories = add_submenu_page(
		    $this->shared->get('slug') . '-dashboard',
		    esc_html__('IM - Categories', 'daim'),
		    esc_html__('Categories', 'daim'),
		    get_option( $this->shared->get('slug') . "_categories_menu_required_capability"),
		    $this->shared->get('slug') . '-categories',
		    array( $this, 'me_display_menu_categories')
	    );

        $this->screen_id_term_groups = add_submenu_page(
            $this->shared->get('slug') . '-dashboard',
            esc_html__('IM - Term Groups', 'daim'),
            esc_html__('Term Groups', 'daim'),
            get_option( $this->shared->get('slug') . "_term_groups_menu_required_capability"),
            $this->shared->get('slug') . '-term-groups',
            array( $this, 'me_display_menu_term_groups')
        );

        $this->screen_id_import = add_submenu_page(
            $this->shared->get('slug') . '-dashboard',
            esc_html__('IM - Import', 'daim'),
            esc_html__('Import', 'daim'),
            get_option( $this->shared->get('slug') . "_import_menu_required_capability"),
            $this->shared->get('slug') . '-import',
            array( $this, 'me_display_menu_import')
        );
        
        $this->screen_id_export = add_submenu_page(
            $this->shared->get('slug') . '-dashboard',
            esc_html__('IM - Export', 'daim'),
            esc_html__('Export', 'daim'),
            get_option( $this->shared->get('slug') . "_export_menu_required_capability"),
            $this->shared->get('slug') . '-export',
            array( $this, 'me_display_menu_export')
        );

	    $this->screen_id_maintenance = add_submenu_page(
		    $this->shared->get('slug') . '-dashboard',
		    esc_html__('IM - Maintenance', 'daim'),
		    esc_html__('Maintenance', 'daim'),
		    get_option( $this->shared->get('slug') . "_maintenance_menu_required_capability"),
		    $this->shared->get('slug') . '-maintenance',
		    array( $this, 'me_display_menu_maintenance')
	    );

        $this->screen_id_help = add_submenu_page(
            $this->shared->get('slug') . '-dashboard',
            esc_html__('IM - Help', 'daim'),
            esc_html__('Help', 'daim'),
            'manage_options',
            $this->shared->get('slug') . '-help',
            array( $this, 'me_display_menu_help')
        );
        
        $this->screen_id_options = add_submenu_page(
            $this->shared->get('slug') . '-dashboard',
	        esc_html__('IM - Options', 'daim'),
            esc_html__('Options', 'daim'),
            'manage_options',
            $this->shared->get('slug') . '-options',
            array( $this, 'me_display_menu_options')
        );
        
    }
    
    /*
     * includes the dashboard view
     */
    public function me_display_menu_dashboard() {
        include_once( 'view/dashboard.php' );
    }
    
    /*
     * includes the juice view
     */
    public function me_display_menu_juice() {
        include_once( 'view/juice.php' );
    }
    
    /*
     * includes the anchors view
     */
    public function me_display_menu_anchors() {
        include_once( 'view/anchors.php' );
    }

	/*
     * includes the http status view
     */
	public function me_display_menu_http_status() {
		include_once( 'view/http_status.php' );
	}

    /*
     * includes the hits view
     */
    public function me_display_menu_hits() {
        include_once( 'view/hits.php' );
    }

	/*
     * includes the wizard view
     */
	public function me_display_menu_wizard() {
		include_once( 'view/wizard.php' );
	}

    /*
     * includes the autolinks view
     */
    public function me_display_menu_autolinks() {
        include_once( 'view/autolinks.php' );
    }

	/*
     * includes the categories view
     */
	public function me_display_menu_categories() {
		include_once( 'view/categories.php' );
	}

    /*
     * includes the term groups
     */
    public function me_display_menu_term_groups() {
        include_once( 'view/term_groups.php' );
    }

    /*
     * includes the import view
     */
    public function me_display_menu_import() {
        include_once( 'view/import.php' );
    }

    /*
     * includes the export view
     */
    public function me_display_menu_export() {
        include_once( 'view/export.php' );
    }

	/*
     * includes the maintenance view
     */
	public function me_display_menu_maintenance() {
		include_once( 'view/maintenance.php' );
	}

    /*
     * includes the help view
     */
    public function me_display_menu_help() {
        include_once( 'view/help.php' );
    }
    
    /*
     * includes the options view
     */
    public function me_display_menu_options() {
        include_once( 'view/options.php' );
    }

    /*
     * register options
     */
    public function op_register_options() {

        $this->menu_options->register_options();

    }

	//meta box -----------------------------------------------------------------
    public function create_meta_box(){
        
        if(current_user_can(get_option( $this->shared->get('slug') . "_interlinks_options_mb_required_capability"))){

            /*
             * Load the "Interlinks Options" meta box only in the post types defined
             * with the "Interlinks Options Post Types" option
             */
            $interlinks_options_post_types_a = maybe_unserialize(get_option($this->shared->get('slug') . '_interlinks_options_post_types' ));
            if(is_array($interlinks_options_post_types_a)){
	            foreach ($interlinks_options_post_types_a as $key => $post_type) {
		            add_meta_box( 'daim-meta-options', esc_html__('Interlinks Options', 'daim'), array($this, 'create_options_meta_box_callback'), $post_type, 'normal', 'high' );
	            }
            }

        }

        if(current_user_can(get_option( $this->shared->get('slug') . "_interlinks_optimization_mb_required_capability"))){
        
            /*
             * Load the "Interlinks Optimization" meta box only in the post types
             * defined with the "Interlinks Optimization Post Types" option
             */
            $interlinks_optimization_post_types_a = maybe_unserialize(get_option($this->shared->get('slug') . '_interlinks_optimization_post_types' ));
            if(is_array($interlinks_optimization_post_types_a)){
	            foreach ($interlinks_optimization_post_types_a as $key => $post_type) {
		            add_meta_box( 'daim-meta-optimization', esc_html__('Interlinks Optimization', 'daim'), array($this, 'create_optimization_meta_box_callback'), $post_type, 'side', 'default' );
	            }
            }

        }
        
        if(current_user_can(get_option( $this->shared->get('slug') . "_interlinks_suggestions_mb_required_capability"))){
        
            /*
             * Load the "Interlinks Suggestions" meta box only in the post types
             * defined with the "Interlinks Suggestions Post Types" option
             */
            $interlinks_suggestions_post_types_a = maybe_unserialize(get_option($this->shared->get('slug') . '_interlinks_suggestions_post_types' ));
            if(is_array($interlinks_suggestions_post_types_a)){
	            foreach ($interlinks_suggestions_post_types_a as $key => $post_type) {
		            add_meta_box( 'daim-meta-suggestions', esc_html__('Interlinks Suggestions', 'daim'), array($this, 'create_suggestions_meta_box_callback'), $post_type, 'side', 'default' );
	            }
            }

        }
        
    }
    
    //display the Interlinks Options meta box content
    public function create_options_meta_box_callback( $post ) {

	//retrieve the Interlinks Manager data values
	$seo_power = get_post_meta( $post->ID, '_daim_seo_power', true );
        if(strlen(trim($seo_power)) == 0){$seo_power = (int) get_option( $this->shared->get('slug') . '_default_seo_power');}
        $enable_ail = get_post_meta( $post->ID, '_daim_enable_ail', true );
	
        //if the $enable_ail is empty use the Enable AIL option as a default
        if(strlen(trim($enable_ail)) == 0){
            $enable_ail = get_option( $this->shared->get('slug') . '_default_enable_ail_on_post');
        }
        
	?>

        <table class="form-table table-interlinks-options">
            <tbody>
                
                <tr>
                    <th scope="row"><label><?php esc_html_e('SEO Power', 'daim'); ?></label></th>
                    <td>
                        <input type="text" name="daim_seo_power" value="<?php echo intval(( $seo_power ), 10); ?>" class="regular-text" maxlength="7">
                        <div class="help-icon" title="<?php esc_attr_e('The SEO Power of this post.', 'daim'); ?>"></div>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><label><?php esc_html_e('Enable AIL', 'daim'); ?></label></th>
                    <td>
                        <select id="daim-enable-ail" class="daext-display-none" name="daim_enable_ail">
                            <option <?php selected(intval($enable_ail, 10), 0); ?> value="0"><?php esc_html_e('No', 'daim'); ?></option>
                            <option <?php selected(intval($enable_ail, 10), 1); ?>value="1"><?php esc_html_e('Yes', 'daim'); ?></option>
                        </select>
                        <div class="help-icon" title="<?php esc_attr_e('Select "Yes" to enable the AIL in this post.', 'daim'); ?>"></div>

                    </td>
                </tr>
                
            </tbody>
        </table>     
        
	<?php
	
	// Use nonce for verification
	wp_nonce_field( plugin_basename( __FILE__ ), 'daim_nonce' );	
	
    }
    
    //display the Interlinks Optimization meta box content
    public function create_optimization_meta_box_callback( $post ) {
	
	?>

        <div class="meta-box-body">
            <table class="form-table">
                <tbody>

                    <tr>
                        <td>
                            <?php echo $this->shared->generate_interlinks_optimization_metabox_html($post); ?>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
        
	<?php	
	
    }
    
    //display the Interlinks Suggestions meta box content
    public function create_suggestions_meta_box_callback( $post ) {
	
	?>

        <div class="meta-box-body">
            <table class="form-table">
                <tbody>

                    <tr>
                        <td>
                            <input id="daim-interlinks-suggestions-hidden-input" type="text"/>
                            <p id="daim-interlinks-suggestions-introduction"><?php esc_html_e('Click the "Generate" button multiple times until you can find posts suitable to be used as interlinks of this post.', 'daim'); ?></p>
                            <div id="daim-interlinks-suggestions-list"></div>
                        </td>
                    </tr>

                </tbody>
            </table>  
        </div>

        <div id="major-publishing-actions">

            <div id="publishing-action">
                <input id="ajax-request-status" type="hidden" value="inactive">
                <span class="spinner"></span>
                <input data-post-id="<?php echo $post->ID; ?>" type="button" class="button button-primary button-large" id="generate-ideas" value="<?php esc_attr_e('Generate', 'daim'); ?>">
            </div>
            <div class="clear"></div>

        </div>
        
	<?php	
	
    }
    
    //Save the Interlinks Options meta data
    public function daim_save_meta_interlinks_options( $post_id ) {

        //security verifications -----------------------------------------------

        // verify if this is an auto save routine. 
        // If it is our form has not been submitted, so we dont want to do anything        
        if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {  
          return;
        }	

        /*
         * verify this came from the our screen and with proper authorization,
         * because save_post can be triggered at other times
         */
        if ( !isset( $_POST['daim_nonce'] ) || !wp_verify_nonce( sanitize_text_field($_POST['daim_nonce']), plugin_basename( __FILE__ ) ) ){
            return;
        }

        //verify the capability
        if(!current_user_can(get_option( $this->shared->get('slug') . "_interlinks_options_mb_required_capability"))){return;}

        //end security verifications -------------------------------------------

        //save the "SEO Power" only if it's included in the allowed values
        if(intval($_POST['daim_seo_power'], 10) !== 0 and intval($_POST['daim_seo_power'], 10) <= 1000000){
            update_post_meta( $post_id, '_daim_seo_power', intval($_POST['daim_seo_power'], 10) );
        }
        
        //save the "Enable AIL"
        update_post_meta( $post_id, '_daim_enable_ail', intval($_POST['daim_enable_ail'], 10) );

    }

	/*
     * plugin deactivation
     */
	public function dc_deactivate( $networkwide ) {
		wp_clear_scheduled_hook( 'daextdaim_cron_hook' );
	}
    
}