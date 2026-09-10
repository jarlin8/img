/** 
 * UnGrabber - Most effective way to protect your online content from being copied.
 * Exclusively on Envato Market: https://1.envato.market/ungrabber
 * 
 * @encoding     UTF-8
 * @version      1.0.0
 * @copyright    Copyright (C) 2019 Merkulove ( https://merkulov.design/ ). All rights reserved.
 * @license      Envato Standard License https://1.envato.market/KYbje
 * @author       Alexandr Khmelnytsky (info@alexander.khmelnitskiy.ua)
 * @support      dmitry@merkulov.design
 **/

/**
 * Plugin assignments,
 **/
jQuery(function($) {
    
    "use strict";

    /** INPUT which will save the settings */
    var assignInput = jQuery("#mdp-assignInput");
    
    /**
     * Read assignments settings.
     **/
    function mdp_readSettings () {
        
        // Get assignments from field
        var aConf = '';
        try {
            
            var aConfJson = jQuery(assignInput).val();
            aConfJson = aConfJson.replace(/\|/g, '"');
            aConf = JSON.parse(aConfJson);
            
            // Matching Method
            var matchingMethod = aConf.matchingMethod;
            jQuery("#mdp-assign-box .mdp-matching-method .mdp-button-group .mdp-button").removeClass("mdp-active");
            if(matchingMethod == 0){ jQuery("#mdp-assign-box .mdp-matching-method .mdp-button-group .mdp-all").addClass("mdp-active"); }
            if(matchingMethod == 1){ jQuery("#mdp-assign-box .mdp-matching-method .mdp-button-group .mdp-any").addClass("mdp-active"); }
            
            // WordPress Content
            var WPContent = aConf.WPContent;
            var WPContentVal = aConf.WPContentVal + '';
            jQuery("#mdp-assign-box .mdp-wp-content .mdp-button-group .mdp-button").removeClass("mdp-active");
            if(WPContent == 0){ jQuery("#mdp-assign-box .mdp-wp-content .mdp-button-group .mdp-ignore").addClass("mdp-active"); }
            if(WPContent == 1){ jQuery("#mdp-assign-box .mdp-wp-content .mdp-button-group .mdp-include").addClass("mdp-active"); }
            if(WPContent == 2){ jQuery("#mdp-assign-box .mdp-wp-content .mdp-button-group .mdp-exclude").addClass("mdp-active"); }
            var WPContentArray = WPContentVal.split(",");
            if(WPContentVal != '') { jQuery("#mdp-assign-box .mdp-wp-content select.wp-content").val(WPContentArray).trigger("chosen:updated"); }
            
            // Home Page
            var homePage = aConf.homePage;
            jQuery("#mdp-assign-box .mdp-home-page .mdp-button-group .mdp-button").removeClass("mdp-active");
            if(homePage == 0){ jQuery("#mdp-assign-box .mdp-home-page .mdp-button-group .mdp-ignore").addClass("mdp-active"); }
            if(homePage == 1){ jQuery("#mdp-assign-box .mdp-home-page .mdp-button-group .mdp-include").addClass("mdp-active"); }
            if(homePage == 2){ jQuery("#mdp-assign-box .mdp-home-page .mdp-button-group .mdp-exclude").addClass("mdp-active"); }
            
            // Menu Items
            var menuItems = aConf.menuItems;
            var menuItemsVal = aConf.menuItemsVal + '';
            jQuery("#mdp-assign-box .mdp-menu-items .mdp-button-group .mdp-button").removeClass("mdp-active");
            if(menuItems == 0){ jQuery("#mdp-assign-box .mdp-menu-items .mdp-button-group .mdp-ignore").addClass("mdp-active"); }
            if(menuItems == 1){ jQuery("#mdp-assign-box .mdp-menu-items .mdp-button-group .mdp-include").addClass("mdp-active"); }
            if(menuItems == 2){ jQuery("#mdp-assign-box .mdp-menu-items .mdp-button-group .mdp-exclude").addClass("mdp-active"); }
            var menuItemsArray = menuItemsVal.split(",");
            if(menuItemsVal != '') { jQuery("#mdp-assign-box .mdp-menu-items select.menuitems").val(menuItemsArray).trigger("chosen:updated"); }
            
            // User Roles
            var userRoles = aConf.userRoles;
            var userRolesVal = aConf.userRolesVal + '';
            jQuery("#mdp-assign-box .mdp-user-roles .mdp-button-group .mdp-button").removeClass("mdp-active");
            if(userRoles == 0){ jQuery("#mdp-assign-box .mdp-user-roles .mdp-button-group .mdp-ignore").addClass("mdp-active"); }
            if(userRoles == 1){ jQuery("#mdp-assign-box .mdp-user-roles .mdp-button-group .mdp-include").addClass("mdp-active"); }
            if(userRoles == 2){ jQuery("#mdp-assign-box .mdp-user-roles .mdp-button-group .mdp-exclude").addClass("mdp-active"); }
            var userRolesArray = userRolesVal.split(",");
            if(userRolesVal != '') { jQuery("#mdp-assign-box .mdp-user-roles select.user-roles").val(userRolesArray).trigger("chosen:updated"); }
                        
            // URL
            var URL = aConf.URL;
            var URLVal = aConf.URLVal;
            jQuery("#mdp-assign-box .mdp-url .mdp-button-group .mdp-button").removeClass("mdp-active");
            if(URL == 0){ jQuery("#mdp-assign-box .mdp-url .mdp-button-group .mdp-ignore").addClass("mdp-active"); }
            if(URL == 1){ jQuery("#mdp-assign-box .mdp-url .mdp-button-group .mdp-include").addClass("mdp-active"); }
            if(URL == 2){ jQuery("#mdp-assign-box .mdp-url .mdp-button-group .mdp-exclude").addClass("mdp-active"); }
            jQuery("#mdp-assign-box .mdp-url textarea.mdp-url-field").val(URLVal);
            
        } catch (e) {
            
            // Reset all controls to default state
            jQuery("#mdp-assign-box .mdp-button-group .mdp-button").removeClass("mdp-active");
            
            // Matching Method
            jQuery("#mdp-assign-box .mdp-matching-method .mdp-button-group .mdp-all").addClass("mdp-active");
            
            // WordPress Content
            jQuery("#mdp-assign-box .mdp-wp-content .mdp-button-group .mdp-ignore").addClass("mdp-active");
            jQuery("#mdp-assign-box .mdp-wp-content select.wp-content").val("").trigger("chosen:updated");
            
            // Home Page
            jQuery("#mdp-assign-box .mdp-home-page .mdp-button-group .mdp-ignore").addClass("mdp-active");
            
            // Menu Items
            jQuery("#mdp-assign-box .mdp-menu-items .mdp-button-group .mdp-ignore").addClass("mdp-active");
            jQuery("#mdp-assign-box .mdp-menu-items select.menuitems").val("").trigger("chosen:updated");            
            
            // User Roles
            jQuery("#mdp-assign-box .mdp-user-roles .mdp-button-group .mdp-ignore").addClass("mdp-active");
            jQuery("#mdp-assign-box .mdp-user-roles select.user-roles").val("").trigger("chosen:updated");            
                        
            // URL
            jQuery("#mdp-assign-box .mdp-url .mdp-button-group .mdp-ignore").addClass("mdp-active");
            jQuery("#mdp-assign-box .mdp-url textarea.mdp-url-field").val("");
            
            console.log(e);
            
        }
        
    }
        
    /**
     * Save settings.
     **/
    function mdp_saveSettings() {
        
        /** Get new values */
        
        // Matching Method
        var matchingMethod = 0;
        if(jQuery("#mdp-assign-box .mdp-matching-method .mdp-button-group .mdp-all").hasClass("mdp-active")) { matchingMethod = 0; }
        if(jQuery("#mdp-assign-box .mdp-matching-method .mdp-button-group .mdp-any").hasClass("mdp-active")) { matchingMethod = 1; }
        
        // WordPress Content
        var WPContent = 0;
        if(jQuery("#mdp-assign-box .mdp-wp-content .mdp-button-group .mdp-ignore").hasClass("mdp-active")) { WPContent = 0; }
        if(jQuery("#mdp-assign-box .mdp-wp-content .mdp-button-group .mdp-include").hasClass("mdp-active")) { WPContent = 1; }
        if(jQuery("#mdp-assign-box .mdp-wp-content .mdp-button-group .mdp-exclude").hasClass("mdp-active")) { WPContent = 2; }
        
        var WPContentVal = '';
        if(WPContent){
            WPContentVal = jQuery("#mdp-assign-box .mdp-wp-content select.wp-content").val();
        }
        
        // Home Page
        var homePage = 0;
        if(jQuery("#mdp-assign-box .mdp-home-page .mdp-button-group .mdp-ignore").hasClass("mdp-active")) { homePage = 0; }
        if(jQuery("#mdp-assign-box .mdp-home-page .mdp-button-group .mdp-include").hasClass("mdp-active")) { homePage = 1; }
        if(jQuery("#mdp-assign-box .mdp-home-page .mdp-button-group .mdp-exclude").hasClass("mdp-active")) { homePage = 2; }
        
        // Menu Items
        var menuItems = 0;
        if(jQuery("#mdp-assign-box .mdp-menu-items .mdp-button-group .mdp-ignore").hasClass("mdp-active")) { menuItems = 0; }
        if(jQuery("#mdp-assign-box .mdp-menu-items .mdp-button-group .mdp-include").hasClass("mdp-active")) { menuItems = 1; }
        if(jQuery("#mdp-assign-box .mdp-menu-items .mdp-button-group .mdp-exclude").hasClass("mdp-active")) { menuItems = 2; }
        
        var menuItemsVal = '';
        if(menuItems){
            menuItemsVal = jQuery("#mdp-assign-box .mdp-menu-items select.menuitems").val();
        }
        
        // User Roles
        var userRoles = 0;
        if(jQuery("#mdp-assign-box .mdp-user-roles .mdp-button-group .mdp-ignore").hasClass("mdp-active")) { userRoles = 0; }
        if(jQuery("#mdp-assign-box .mdp-user-roles .mdp-button-group .mdp-include").hasClass("mdp-active")) { userRoles = 1; }
        if(jQuery("#mdp-assign-box .mdp-user-roles .mdp-button-group .mdp-exclude").hasClass("mdp-active")) { userRoles = 2; }
        
        var userRolesVal = '';
        if(userRoles){
            userRolesVal = jQuery("#mdp-assign-box .mdp-user-roles select.user-roles").val();
        }
        
        // URL
        var URL = 0;
        if(jQuery("#mdp-assign-box .mdp-url .mdp-button-group .mdp-ignore").hasClass("mdp-active")) { URL = 0; }
        if(jQuery("#mdp-assign-box .mdp-url .mdp-button-group .mdp-include").hasClass("mdp-active")) { URL = 1; }
        if(jQuery("#mdp-assign-box .mdp-url .mdp-button-group .mdp-exclude").hasClass("mdp-active")) { URL = 2; }
        
        var URLVal = '';
        if(URL){
            URLVal = jQuery("#mdp-assign-box .mdp-url textarea.mdp-url-field").val();
        }        
        
        var aConf = {

            matchingMethod: matchingMethod,
            WPContent: WPContent,
            WPContentVal: WPContentVal,
            homePage: homePage,
            menuItems: menuItems,
            menuItemsVal: menuItemsVal,
            userRoles: userRoles,
            userRolesVal: userRolesVal,
            URL: URL,
            URLVal: URLVal
        };
        
        var aConfJson = JSON.stringify(aConf);
        aConfJson = aConfJson.replace(/\"/g, '|');// Input truncate quotes, so made some replacments
        
        assignInput.val( aConfJson );// Set setting to input
        assignInput.change();

    }
    
    /**
     * Initialization.
     **/
    function mdp_ungrabber_assignment_ini() {
        
        /** Show/Hide unused controls. */
        jQuery("#mdp-assign-box .mdp-button.mdp-active").click();
        
        /** Make select boxes more user-friendly - Chosen. */
        jQuery("#mdp-assign-box select.chosen-select").chosen({
            width:'100%',
            search_contains: true,
            disable_search_threshold: 7,
            inherit_select_classes: true,
            no_results_text: "Oops, nothing found"
        });
        
    }
    
    /**
     * Matching Method click.
     **/
    jQuery("#mdp-assign-box .mdp-matchingMethod .mdp-button").on( 'click', function (e){
        e.preventDefault();
        jQuery("#mdp-assign-box .mdp-matchingMethod button").removeClass("mdp-active");
        jQuery(this).addClass("mdp-active");
        mdp_saveSettings();
    });
    
    /**
     * Menu Items Ignore click.
     **/
    jQuery("#mdp-assign-box .mdp-menu-items .mdp-button-group .mdp-ignore").on( 'click', function (e){
        e.preventDefault();
        jQuery("#mdp-assign-box .mdp-menu-items button").removeClass("mdp-active");
        jQuery(this).addClass("mdp-active");
        jQuery("#mdp-assign-box .mdp-menu-items .mdp-menuitems-selection").hide(200);
        jQuery(this).closest(".mdp-menu-items").removeClass("mdp-red mdp-green");
        mdp_saveSettings();
    });
    
    /**
     * Menu Items Include click.
     **/
    jQuery("#mdp-assign-box .mdp-menu-items .mdp-button-group .mdp-include").on( 'click', function (e){
        e.preventDefault();
        jQuery("#mdp-assign-box .mdp-menu-items button").removeClass("mdp-active");
        jQuery(this).addClass("mdp-active");
        jQuery("#mdp-assign-box .mdp-menu-items .mdp-menuitems-selection").show(200);
        jQuery(this).closest(".mdp-menu-items").removeClass("mdp-red").addClass("mdp-green");
        mdp_saveSettings();
    });
    
    /**
     * Menu Items Exclude click.
     **/
    jQuery("#mdp-assign-box .mdp-menu-items .mdp-button-group .mdp-exclude").on( 'click', function (e){
        e.preventDefault();
        jQuery("#mdp-assign-box .mdp-menu-items button").removeClass("mdp-active");
        jQuery(this).addClass("mdp-active");
        jQuery("#mdp-assign-box .mdp-menu-items .mdp-menuitems-selection").show(200);
        jQuery(this).closest(".mdp-menu-items").removeClass("mdp-green").addClass("mdp-red");
        mdp_saveSettings();
    });
    
    /**
     * User Roles Ignore click.
     **/
    jQuery("#mdp-assign-box .mdp-user-roles .mdp-button-group .mdp-ignore").on( 'click', function (e){
        e.preventDefault();
        jQuery("#mdp-assign-box .mdp-user-roles button").removeClass("mdp-active");
        jQuery(this).addClass("mdp-active");
        jQuery("#mdp-assign-box .mdp-user-roles .user-roles-box").hide(200);
        jQuery(this).closest(".mdp-user-roles").removeClass("mdp-red mdp-green");
        mdp_saveSettings();
    });
    
    /**
     * User Roles Include click.
     **/
    jQuery("#mdp-assign-box .mdp-user-roles .mdp-button-group .mdp-include").on( 'click', function (e){
        e.preventDefault();
        jQuery("#mdp-assign-box .mdp-user-roles button").removeClass("mdp-active");
        jQuery(this).addClass("mdp-active");
        jQuery("#mdp-assign-box .mdp-user-roles .user-roles-box").show(200);
        jQuery(this).closest(".mdp-user-roles").removeClass("mdp-red").addClass("mdp-green");
        mdp_saveSettings();
    });
    
    /**
     * User Roles Exclude click.
     **/
    jQuery("#mdp-assign-box .mdp-user-roles .mdp-button-group .mdp-exclude").on( 'click', function (e){
        e.preventDefault();
        jQuery("#mdp-assign-box .mdp-user-roles button").removeClass("mdp-active");
        jQuery(this).addClass("mdp-active");
        jQuery("#mdp-assign-box .mdp-user-roles .user-roles-box").show(200);
        jQuery(this).closest(".mdp-user-roles").removeClass("mdp-green").addClass("mdp-red");
        mdp_saveSettings();
    });
    
    /**
     * Home Page IGNORE click.
     **/
    jQuery("#mdp-assign-box .mdp-home-page .mdp-button-group .mdp-ignore").on( 'click', function (e){
        e.preventDefault();
        jQuery("#mdp-assign-box .mdp-home-page .mdp-button-group button").removeClass("mdp-active");
        jQuery(this).addClass("mdp-active");
        jQuery(this).closest(".mdp-home-page").removeClass("mdp-green mdp-red");
        mdp_saveSettings();
    });
    
    /**
     * Home Page INCLUDE click.
     **/
    jQuery("#mdp-assign-box .mdp-home-page .mdp-button-group .mdp-include").on( 'click', function (e){
        e.preventDefault();
        jQuery("#mdp-assign-box .mdp-home-page .mdp-button-group button").removeClass("mdp-active");
        jQuery(this).addClass("mdp-active");
        jQuery(this).closest(".mdp-home-page").removeClass("mdp-red").addClass("mdp-green");
        mdp_saveSettings();
    });
    
    /**
     * Home Page EXCLUDE click.
     **/
    jQuery("#mdp-assign-box .mdp-home-page .mdp-button-group .mdp-exclude").on( 'click', function (e){
        e.preventDefault();
        jQuery("#mdp-assign-box .mdp-home-page .mdp-button-group button").removeClass("mdp-active");
        jQuery(this).addClass("mdp-active");
        jQuery(this).closest(".mdp-home-page").removeClass("mdp-green").addClass("mdp-red");
        mdp_saveSettings();
    });
       
    /**
     * User URL IGNORE click.
     **/
    jQuery("#mdp-assign-box .mdp-url .mdp-button-group .mdp-ignore").on( 'click', function (e){
        e.preventDefault();
        jQuery("#mdp-assign-box .mdp-url button").removeClass("mdp-active");
        jQuery(this).addClass("mdp-active");
        jQuery(this).closest(".mdp-url").removeClass("mdp-green mdp-red");
        jQuery("#mdp-assign-box .mdp-url .mdp-url-box").hide(200);
        mdp_saveSettings();
    });
    
    /**
     * User URL INCLUDE click.
     **/
    jQuery("#mdp-assign-box .mdp-url .mdp-button-group .mdp-include").on( 'click', function (e){
        e.preventDefault();
        jQuery("#mdp-assign-box .mdp-url button").removeClass("mdp-active");
        jQuery(this).addClass("mdp-active");
        jQuery(this).closest(".mdp-url").removeClass("mdp-red").addClass("mdp-green");
        jQuery("#mdp-assign-box .mdp-url .mdp-url-box").show(200);
        mdp_saveSettings();
    });
    
    /**
     * User URL EXCLUDE click.
     **/
    jQuery("#mdp-assign-box .mdp-url .mdp-button-group .mdp-exclude").on( 'click', function (e){
        e.preventDefault();
        jQuery("#mdp-assign-box .mdp-url button").removeClass("mdp-active");
        jQuery(this).addClass("mdp-active");
        jQuery(this).closest(".mdp-url").removeClass("mdp-green").addClass("mdp-red");
        jQuery("#mdp-assign-box .mdp-url .mdp-url-box").show(200);
        mdp_saveSettings();
    });
    
    /**
     * User WordPress Content IGNORE click.
     **/
    jQuery("#mdp-assign-box .mdp-wp-content .mdp-button-group .mdp-ignore").on( 'click', function (e){
        e.preventDefault();
        jQuery("#mdp-assign-box .mdp-wp-content button").removeClass("mdp-active");
        jQuery(this).addClass("mdp-active");
        jQuery(this).closest(".mdp-wp-content").removeClass("mdp-green mdp-red");
        jQuery("#mdp-assign-box .mdp-wp-content .mdp-wp-content-box").hide(200);
        mdp_saveSettings();
    });
    
    /**
     * User WordPress Content INCLUDE click.
     **/
    jQuery("#mdp-assign-box .mdp-wp-content .mdp-button-group .mdp-include").on( 'click', function (e){
        e.preventDefault();
        jQuery("#mdp-assign-box .mdp-wp-content button").removeClass("mdp-active");
        jQuery(this).addClass("mdp-active");
        jQuery(this).closest(".mdp-wp-content").removeClass("mdp-red").addClass("mdp-green");
        jQuery("#mdp-assign-box .mdp-wp-content .mdp-wp-content-box").show(200);
        mdp_saveSettings();
    });
    
    /**
     * User WordPress Content EXCLUDE click.
     **/
    jQuery("#mdp-assign-box .mdp-wp-content .mdp-button-group .mdp-exclude").on( 'click', function (e){
        e.preventDefault();
        jQuery("#mdp-assign-box .mdp-wp-content button").removeClass("mdp-active");
        jQuery(this).addClass("mdp-active");
        jQuery(this).closest(".mdp-wp-content").removeClass("mdp-green").addClass("mdp-red");
        jQuery("#mdp-assign-box .mdp-wp-content .mdp-wp-content-box").show(200);
        mdp_saveSettings();
    });
    
    /**
     * Seve valeue in field on change.
     **/
    jQuery("select.wp-content, select.menuitems, select.user-roles, textarea.mdp-url-field").on('input propertychange', function () {
        mdp_saveSettings();
    });
    
    mdp_readSettings();
    
    mdp_ungrabber_assignment_ini();
    
    /** Show warning for unsaved data in form. */
    jQuery( document ).ready( function () {
        
        var unsaved = false;
        
        /** Triggers change in all input fields including text type. */
        jQuery( ':input' ).on( 'change', function() { 
            unsaved = true;
        } );
        
        /** Show warning if form not saved. */
        window.addEventListener( 'beforeunload', ( event ) => {
            if ( unsaved ) {
                event.returnValue = 'Are you sure you want to leave?';
            }
        });
        
        /** Diasble unsved form warning and save settings. */
        jQuery( '#submit' ).on( 'click', function( e ) {
            unsaved = false;
        } );
        
    } );
    
} );

