<?php
// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}
?>
<div class="wrap gspb_welcome_div_container">
    <style>
        #wpcontent {
            background: #f8fafc;
            padding: 0;
        }

        .wrap {
            margin: 0 auto;
        }

        .wrap .notice,
        .wrap .error {
            display: none
        }

        .wrap h2 {
            font-size: 1.4em;
            margin-bottom: 1.5em;
            margin-top: 0;
            font-weight: bold;
        }

        .greenshift_form {
            padding: 15px 25px 25px 25px;
            background: #fff;
            margin-top: 15px;
            box-shadow: 0 0 3px 0 rgb(0 0 0 / 10%), 0 1px 2px -1px rgb(0 0 0 / 10%);
            overflow: hidden;
        }

        .greenshift_form .form-table {
            margin-top: 0
        }

        .gs-introtext {
            font-size: 14px;
            line-height: 22.4px;
            color: rgb(100 116 139);
            margin-bottom: 30px;
        }

        .gs-intro-video iframe {
            box-shadow: 10px 10px 20px rgb(0 0 0 / 15%);
        }

        .gs-intro-video {
            margin-bottom: 40px
        }

        .wrap h1 {
            text-align: left;
            padding: 15px 20px;
            margin: -1px -1px 60px -1px;
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            box-shadow: 0 3px 8px rgb(0 0 0 / 5%);
        }

        .gs-padd {
            padding: 25px;
            text-align: left;
            margin: 1.5em auto;
            max-width: 900px;
        }

        .rtl .gs-padd {
            text-align: right
        }

        .wp-core-ui .button-primary {
            background-color: #2184f9
        }

        .nav-tab {
            font-size: 16px;
            border: none;
            padding: 10px 20px;
            background: none;
            border-bottom: 2px solid transparent;
        }

        .nav-tab-active,
        .nav-tab-active:focus,
        .nav-tab-active:focus:active,
        .nav-tab:hover {
            border-bottom: 2px solid #2184f9;
            background: #fff;
            color: #2184f9;
        }

        .nav-tab-wrapper {
            padding-left: 20px;
            background: white;
            border-bottom: 1px solid #edeff5;
        }

        .nav-tab-wrapper>div {
            margin: 0 auto;
            max-width: 950px
        }

        .wrap .fs-notice {
            margin: 0 25px 35px 25px !important
        }

        .wrap .fs-plugin-title {
            display: none !important
        }

        .gs_main_text {
            font-size: 15px;
            margin-bottom: 12px;
        }

        .gs_main_text a {
            color: #2184f9
        }

        .mb30 {
            margin-bottom: 30px
        }
    </style>
    <nav class="nav-tab-wrapper">
        <div>
            <a href="?page=greenshift_dashboard" class="nav-tab">
                <?php esc_html_e("Getting Started", 'greenshift-animation-and-page-builder-blocks'); ?>
            </a>
            <a href="?page=greenshift" class="nav-tab">
                <?php esc_html_e("Settings", 'greenshift-animation-and-page-builder-blocks'); ?>
            </a>
            <a href="?page=greenshift_dashboard-addons" class="nav-tab">
                <?php esc_html_e("Addons", 'greenshift-animation-and-page-builder-blocks'); ?>
            </a>
            <a href="?page=greenshift_upgrade" class="nav-tab">
                <?php esc_html_e("Upgrade", 'greenshift-animation-and-page-builder-blocks'); ?>
            </a>
            <a href="?page=greenshift_contact" class="nav-tab  nav-tab-active">
                <?php esc_html_e("Contact Us", 'greenshift-animation-and-page-builder-blocks'); ?>
            </a>
        </div>
    </nav>

    <div class="gs-padd">
        <h2><?php esc_html_e("Contact Us", 'greenshift-animation-and-page-builder-blocks'); ?></h2>
        <div class="greenshift_form">
            <p style="float:left; margin-right:25px;"><img src="<?php echo GREENSHIFT_DIR_URL . 'libs/logo_300.png'; ?>" height="100" width="100" /></p>
            <p class="gs_main_text"><?php esc_html_e("Thank you for using Greenshift. For any bug report or questions, please, contact us:", 'greenshift-animation-and-page-builder-blocks'); ?></p>
            <div class="gs_main_text">
                <a href="https://shop.greenshiftwp.com/contact-us/" target="_blank">
                <?php esc_html_e("Private contact form", 'greenshift-animation-and-page-builder-blocks'); ?>
                </a>
            </div>
            <div class="gs_main_text">
            <a href="https://wordpress.org/support/plugin/greenshift-animation-and-page-builder-blocks/" target="_blank"><?php esc_html_e("Ticket system on Wordpress.org", 'greenshift-animation-and-page-builder-blocks'); ?></a>
            </div>
        </div>
    </div>
</div>