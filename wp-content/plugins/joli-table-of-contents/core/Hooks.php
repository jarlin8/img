<?php

/**
 * @package jolitoc
 */
namespace WPJoli\JoliTOC;

use  WPJoli\JoliTOC\Application ;
use  WPJoli\JoliTOC\Controllers\AdminController ;
use  WPJoli\JoliTOC\Controllers\MenuController ;
use  WPJoli\JoliTOC\Controllers\PublicAppController ;
use  WPJoli\JoliTOC\Controllers\SettingsController ;
use  WPJoli\JoliTOC\Controllers\ShortcodesController ;
use  WPJoli\JoliTOC\Controllers\NoticesFreeController ;
class Hooks
{
    protected  $app ;
    protected  $admin ;
    protected  $menu ;
    protected  $public_app ;
    protected  $settings ;
    protected  $shortcodes ;
    protected  $notices ;
    public function __construct( Application &$app )
    {
        $this->app = $app;
        $this->admin = $app->requestService( AdminController::class );
        $this->menu = $app->requestService( MenuController::class );
        $this->public_app = $app->requestService( PublicAppController::class );
        $this->settings = $app->requestService( SettingsController::class );
        $this->shortcodes = $app->requestService( ShortcodesController::class );
        if ( jtoc_xy()->is_free_plan() ) {
            $this->notices_free = $app->requestService( NoticesFreeController::class );
        }
    }
    
    /**
     * Registers all the plugin hooks and filters
     */
    public function run()
    {
        $this->registerAdminHooks();
        $this->registerPublicHooks();
        $this->registerIntegrations();
    }
    
    private function registerAdminHooks()
    {
        //actions
        
        if ( jtoc_xy()->is_free_plan() ) {
            add_action( 'init', [ $this->notices_free, 'initNotices' ] );
            add_action( 'wp_ajax_joli_toc_handle_notice', [ $this->notices_free, 'jtocHandleNotice' ] );
        }
        
        // add_action( 'plugins_loaded',                       [ $this->app,           'registerLanguages' ] );
        add_action( 'admin_enqueue_scripts', [ $this->admin, 'enqueueAssets' ] );
        add_action( 'admin_menu', [ $this->menu, 'addAdminMenu' ] );
        add_action( 'admin_init', [ $this->settings, 'registerSettings' ] );
        //filters
        add_filter( 'plugin_action_links_' . plugin_basename( JTOC()->path( 'joli-table-of-contents.php' ) ), [ $this->admin, 'addSettingsLink' ] );
    }
    
    private function registerPublicHooks()
    {
        //only for front end, avoid interferences with the editor
        
        if ( jtoc_is_front() ) {
            //actions
            add_action( 'init', [ $this->shortcodes, 'registerShortcodes' ] );
            //filters
            add_filter( 'the_content', [ $this->public_app, 'joliTocFilterTheContent' ], 1000 );
        }
    
    }
    
    //Integrations - since 1.3.8
    private function registerIntegrations()
    {
        add_action( 'plugins_loaded', function () {
            
            if ( class_exists( '\\RankMath' ) ) {
                $rm = \WPJoli\JoliTOC\Integrations\RankMath::class;
                new $rm();
            }
        
        } );
    }

}