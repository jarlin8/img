=== Joli Table Of Contents ===
Contributors: wpjoli
Donate link: 
Tags: table of contents,toc,indexes,navigation,seo,summary
Requires at least: 4.0
Tested up to: 5.7
Stable tag: 1.3.8
Requires PHP: 5.6
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Adds SEO-friendly Table of Contents to your posts/pages. Auto & manual insert available. Highly customizable.

== Description ==

Adds a **SEO-friendly Table of Contents** to your posts/pages. Makes your website look more valuable for both your visitors and Google.

**Performance friendly**: Styles/Scripts DO NOT load if the Table Of Contents is not supposed to display.

#### ⭐ CHECK OUR OTHER PLUGINS
* [Joli FAQ SEO](https://wordpress.org/plugins/joli-faq-seo/)
* [Joli CLEAR Lightbox](https://wordpress.org/plugins/joli-clear-lightbox/)


#### 📃 Features

* Auto-insert table of contents (select post types and position in the content).
* Manual insert table of contents by **shortcode**.
* **Customizable** Table of Contents **Title**.
* Show headings by custom depth (From ***H2*** to ***H4***).
* Customizable CSS Icons for expand/collapse buttons (4 free icons, 20 in pro version)
* Show TOC only if a minimum of headings has been found in the content (Optional).
* Disable headings per text/class
* **Hierarchical** (amount of empty space customizable) or **Flat View**.
* Optional Numbering Prefix (ex: 1. Title 1; 1.2. Subtitle 2, etc...).
* Pretty hash in the actual URL (ex: mysite.com/my-article/#subtitle-2
* Latin & non-latin characters support for hash  [since 1.3.2]
* Multilanguage hash transliteration available (e.g. #История => #istoriya) [since 1.3.3]
* Customizable Numbering Prefix **Separator** (Ex: "." => "1.1.2"; "-" => "1-1-2").
* Customizable Numbering Prefix **Suffix** (Ex: )." => "1.1.2)"; "/" => "1.1.2/").
* Customizable Padding.
* Customizable Headings Height (makes the TOC look more or less dense).
* 6 Auto-insert positions available (See below for details).
* Auto-insert to posts and/or pages.
* Fully responsive.
* 3 Visibility Options on page load (Unfolded, Folded (unfoldable), Responsive [since 1.3.0]).
* Hide or Show Overflowing Headings when viewport cannot fit the whole title length.
* Smooth scrolling available.
* Customizable TOC Title alignment (left, center, right).
* Customizable TOC Toggle button position (left, right) (when folded).
* Customizable TOC Toggle button: Text/HTML/Custom icons (open & close states).
* 2 Themes included (Default & Classic).
* Themes settings can be overriden (Colors, size, etc).
* Customizable Width: Min/Max Width or 100% Width.
* Customizable Font Size, Font Weight.
* Optional Shadow.
* Colors fully customizable (Table of Contents, Title, Headings, Shadow, Prefix).
* Custom CSS available.
* Developer-friendly. Many hooks provided. (See below for details).
* IE10+ Compatible

#### 🚀 Pro Features

[Get Joli Table Of Contents Pro](https://wpjoli.com/joli-table-of-contents/ "Joli Table Of Contents Pro")

* All of the free features.
* **Custom Post Type** Support.
* Exclusive **Floating Widget** that shows the current section ( see details below ).
* ***7 Visibility Options*** on page load (Unfolded, Folded (unfoldable),  Responsive [since 1.3.0]).
* **Additional Themes** (Default, Dark, Classic, Classic Dark, Wikipedia-like).
* Force **Enable/Disable** TOC per post settings.
* Customizable **Floating Position** ( Top or Bottom ).
* Customizable **Floating Offset** (Horizontally & Vertically).
* Expands on **Hover or Click**.
* Collapses on **Leave hover or Click away**.
* Optional **Expanding Animation**.
* **Multi-columns mode** (since 1.1.0).
* Show headings by custom depth (From ***H2*** to ***H6***).
* Customizable CSS Icons for expand/collapse buttons (choose from 20 icons)

#### Auto-insert
The Auto-insert feature is optional and allows to automatically insert the Table of Contents to your content with the following options.

*Supported post types:*
* Post
* Pages
* Custom Post Type ***(Pro only)***

*Auto-insert positions:*
* Before the content
* After the content
* Before H1
* After H1
* Before first H2
* After first paragraph

#### Visibility Options

1. [**Invisible, floating on scroll**](https://wpjoli.com/joli-table-of-contents/demos/visibility-invisible-floating/ "Invisible, floating on scroll") ***(Pro only)***

Table Of Contents is invisible after page is loaded. It only shows on scroll through a floating widget

2. [**Unfolded, in-content**](https://wpjoli.com/joli-table-of-contents/demos/visibility-unfolded-incontent/ "Unfolded, in-content")

Table Of Contents is unfolded after page is loaded. It remains in its position and no widget shows on scroll.

3. [**Unfolded, folded & floating on scroll**](https://wpjoli.com/joli-table-of-contents/demos/visibility-unfolded-floating/ "Unfolded, folded & floating on scroll") ***(Pro only)***

Table Of Contents is unfolded after page is loaded. It also shows on scroll through a floating widget.

4. [**Folded, in-content**](https://wpjoli.com/joli-table-of-contents/demos/visibility-folded-incontent/ "Folded, in-content")

Table Of Contents is folded after page is loaded. It can be unfolded at will. It remains in its position and no widget shows on scroll.

5. [**Folded, folded & floating on scroll**](https://wpjoli.com/joli-table-of-contents/demos/visibility-folded-floating/ "Folded, folded & floating on scroll") ***(Pro only)***

Table Of Contents is folded after page is loaded. It can be unfolded at will. It also shows on scroll through a floating widget.

6. [**Responsive, in-content**]

Table Of Contents is folded on mobile, unfolded on desktop after page is loaded. It can be unfolded at will. It remains in its position and no widget shows on scroll.

7. [**Responsive, folded & floating on scroll**] ***(Pro only)***

Table Of Contents is folded on mobile, unfolded on desktop after page is loaded. It can be unfolded at will. It also shows on scroll through a floating widget.

### Shortcode
Use the following shortcode within your content to have the table of contents display where you wish to:

    [joli-toc]

### 🎣 Hooks

You can use any of the hooks provided to add custom content to the actual Table Of Contents.

**How to use ?**

Copy & paste the code examples below into your theme's functions.php file:

#### Filters
* `joli_toc_disable_autoinsert`

Globally disables Joli Table Of Contents site-wide.

    add_filter('joli_toc_disable_autoinsert', function(){ return true; });

* `joli_toc_disable_js`

For some reason if you do not want the js/css/inline styles to load (could break functionnalities).

    //disables js
    add_filter('joli_toc_disable_js', function(){ return true; });

    //disables css
    add_filter('joli_toc_disable_styles', function(){ return true; });

    //disables inline css & custom styles
    add_filter('joli_toc_disable_inline_styles', function(){ return true; });

* `jolitoc_shortcode_tag`

Customizes the shortcode tag ( *Useful if you were using a different plugin before* ).

    add_filter('jolitoc_shortcode_tag', function(){ return 'custom-tag';});

* `joli_toc_expand_str`

Customizes the toggle button (expand).

    //Using Text
    add_filter('joli_toc_expand_str', function(){ return '>';});
    
    //Using Font Awesome
    add_filter('joli_toc_expand_str', function(){ return '<i class="fa fa-caret-down"></i>';});

* `joli_toc_collapse_str`

Customizes the toggle button (collapse).

    add_filter('joli_toc_collapse_str', function(){ return '˅';});

* `joli_toc_collapse_str`

Customizes the title (collapse).

    add_filter('joli_toc_toc_title', 'my_custom_title', 10, 1);

    function my_custom_title( $title ){ 
        if ($a == $b){
            $title = "My Custom TOC Title";
        }else{
            $title = "My Alternate TOC Title";
        }

        return $title;
    }


#### Actions
`joli_toc_before_table_of_contents`
`joli_toc_before_title`
`joli_toc_after_title`
`joli_toc_after_headings`
`joli_toc_after_table_of_contents`

**Example: adding a horizontal bar after the title**

Copy & paste the following code into your theme's functions.php file:

    add_action( 'joli_toc_after_title', 'echo_hr' );
    
    function echo_hr(){
        echo <hr class="joli-div">;
    }



== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress
1. Use the Settings->Plugin Name screen to configure the plugin
1. (Make your instructions match the desired user flow for activating and installing your plugin. Include any steps that might be needed for explanatory purposes)




### INSTALLING FROM THE WORDPRESS ADMIN
1. Go to the “Plugins > Add New” page.
1. Type “Joli Table Of Contents” in the search field
1. Look for the “Joli Table Of Contents” plugin in the search result and click on the “Install Now” button, the installation process of plugin will begin.
1. Click “Activate” when the installation is complete.

### INSTALLING WITH THE ARCHIVE
Go to the page “Plugins > Add New” on the WordPress control panel
Click on the “Upload Plugin” button, the form to upload the archive will be opened.
Select the archive with the plugin and click “Install Now”.
Click on the “Activate Plugin” button when the installation is complete.

### MANUAL INSTALLATION
Upload the folder joli-table-of-contents to your site's plugin folder, usually it is /wp-content/plugins/.
Go to the page “Plugins > Add New” on the WordPress control panel
Look for “Joli Table Of Contents” in the plugins list and click “Activate”.

### HOW TO DISPLAY THE TABLE OF CONTENTS ON MY WEBSITE ?
Once you've installed Joli Table Of Contents, go to the Settings page under the Menu "Joli TOC".

Then You have 2 choices: 
1. Under Auto-insert, select the post types you would like to have to TOC to display
2. Use the [joli-toc] shortcode directly inside your content.

### WHAT TO DO AFTER ACTIVATION ?

Go to the Settings page under the Menu "Joli TOC", then configure the following basic options to get started:

*GENERAL > GENERAL*
1. Choose a Title for the Table Of Contents
2. Choose to display a prefix or not

*AUTO-INSERT > AUTO-INSERT TABLE OF CONTENTS*
1. Select which post types you would like to have the TOC display.
2. Choose the **TOC Position**

*BEHAVIOUR > BEHAVIOUR*
1. Select the Visibility.
2. Choose how to deal with the Headings overflow.
3. Select your prefered toggle button position.

*APPEARANCE > THEMES*
1. Choose a theme
2. Select your prefered Title alignment.
2. Customize any of the Appearance settings to override theme defaults.

== Frequently Asked Questions ==

= How can I make it work after installation ? =

Once you've installed Joli Table Of Contents, go to the Settings page under the Menu "Joli TOC".

Then You have 2 choices: 
1. Under Auto-insert, select the post types you would like to have to TOC to display
2. Use the [joli-toc] shortcode directly inside your content.

= Can I use custom icons such as FontAwesome for the toggle button ? =

Yes! You can easily customize the toggle button using either text or icons.
The toggle button has 2 states: collapsed and expanded.

Use the following hooks into your theme's functions.php to customize the button:

add_filter('joli_toc_expand_str', function(){ return '<i class="fa fa-bars"></i>';});
add_filter('joli_toc_collapse_str', function(){ return '[close]';});

= Is Joli Table Of Contents responsive ? =

Yes, it is fully responsive and hover events work as touch actions on mobile.

= The title, headings or toggle button won't show, what to do ? =

Depending on your theme, text color may have the same color as the TOC background color as they are inherited by default.
In order to change the color, go to the Settings, then under the Appearance tab, change any color.

== Screenshots ==

1. General settings
2. Auto-insert settings
3. Behviour settings
4. Appearance settings
5. Documentation
6. Default theme demo with shadow option
7. Classic theme demo
8. Font awesome toggle icon example 1
9. Font awesome toggle icon example 2

== Changelog ==

= 1.3.8 =
* Added filter joli_toc_disable_styles
* Added filter joli_toc_disable_inline_styles
* Added filter joli_toc_toc_title
* Added Rank Math integration

= 1.3.7 =
* Fixed admin display bug when using a bootstrap theme
* Fixed ID matching bug when IDs where already existing
* Improved front-end display for invisible and folded mode
* Moved custom CSS from footer to before TOC for faster     processing
* Added scrollbar when TOC size is over viewport size

= 1.3.6 =
* Improved admin scripts/styles
* Refreshed settings panel
* Fixed admin notices

= 1.3.4 =
* Pro version bug fixes

= 1.3.3 =
* Improved hash processing
* Added latin & non-latin advanced hash options (incl. transliteration)

= 1.3.2 =
* Fixed smooth scroll not working with non-latin anchor links

= 1.3.0 =
* Added expand/collapse CSS icons from the settings
* Added responsive mode (collapsed on mobile, unfolded on desktop)
* Added reset settings button
* Fixed awkward jump when switching to floating mode (pro only)
* Fixed Non-latin characters bug
* Fixed headings text wrap bug
* Fixed headings active properties were not showing

= 1.2.0 =
* Added Disable heading per text
* Added Disable heading per class
* Added Jump-to offset
* Added filter hook: joli_toc_disable_js
* Added filter hook: joli_toc_header_tag to allow customizing this html tag
* Clicking the TOC Title now toggles between folded/unfolded display
* CSS optimizition
* Optimized HTML output
* Minor admin css fixes

= 1.1.2 =
* Fixed minor bug

= 1.1.1 =
* Fixed overwriting heading classes
* Fixed HTML output bug on some installs
* Minified assets for smaller frontend footprint

= 1.1.0 =
* Added Multi-columns mode [PRO].
* Added filter hook: joli_toc_headings: Allows operations on the headings before rendering.
* Fixed IE display bugs
* Fixed TOC generation bug if first heading was not H2

= 1.0.1 =
* Fixed text domain issue
* Added Settings link for convenience.
* Added quick start guide & notice in the settings.

= 1.0.0 =
* Initial release.

== Upgrade Notice ==

