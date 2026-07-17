<?php
// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}
?>
<?php $licenses = greenshift_edd_check_all_licenses(); ?>
<?php $is_allinone = false; ?>
<?php if (!empty($licenses['all_in_one']) && $licenses['all_in_one'] == 'valid') {
    $licensesfull = get_option('gspb_edd_licenses');
    if (!empty($licensesfull['all_in_one']['expires'] && $licensesfull['all_in_one']['expires'] == 'lifetime') && isset($licensesfull['all_in_one']['license_limit']) && $licensesfull['all_in_one']['license_limit'] === 0) {
        $is_allinone = true;
    }
}
?>
<div class="wrap gspb_welcome_div_container">
    <style>
        .gs_grid {
            grid-template-columns: repeat(1, minmax(0, 1fr));
            display: grid;
        }

        .gs_grid_2 {
            display: flex;
            flex-direction: column;
            gap: 15px
        }

        @media (min-width: 1024px) {
            .gs_grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 2.5rem;
            }

            .gs_grid_1 {
                grid-column: span 2 / span 2;
                padding: 20px 40px !important
            }
        }

        #wpcontent {
            background: #f8fafc;
            padding: 0;
        }

        #wpcontent *{box-sizing: border-box;}

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
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: 0;
        }

        .gs-intro-video {
            position: relative;
            width: 100%;
            padding-bottom: 56.25%;
            margin-bottom: 40px
        }

        .wrap h1 {
            font-size: 1.8rem;
        }

        .gs-padd {
            text-align: left;
            margin: 2.5em auto;
            max-width: 1150px;
            padding: 25px
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
            max-width: 1150px
        }

        .wrap .fs-notice {
            margin: 0 25px 35px 25px !important
        }

        .wrap .fs-plugin-title {
            display: none !important
        }

        .mb30 {
            margin-bottom: 30px
        }

        .gs_main_text {
            font-size: 15px;
            margin-bottom: 12px;
        }

        .gs_main_text a {
            color: #2184f9
        }

        a.gs_main_btn {
            background-color: #2184f9;
            border: 1px solid #2184f9;
            color: #fff;
            padding: 10px 16px;
            border-radius: 6px;
            text-decoration: none;
            text-align: center
        }

        a.gs_sec_btn {
            background-color: #fff;
            color: #111;
            border: 1px solid #ddd;
            padding: 10px 16px;
            border-radius: 6px;
            text-decoration: none;
            text-align: center
        }
    </style>
    <style>
        #gspb_addons .gspb-cards-list {
            list-style: none;
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            justify-content: space-between;
        }

        #gspb_addons .gspb-cards-list .gspb-card {
            height: 152px;
            width: 300px;
            padding: 0;
            font-size: 14px;
            list-style: none;
            border: 1px solid #ddd;
            position: relative;
        }

        #gspb_addons .gspb-cards-list .gspb-card .gspb-inner {
            background-color: #fff;
            overflow: hidden;
            height: 100%;
            position: relative;
        }

        #gspb_addons .gspb-cards-list .gspb-card .gspb-inner>ul {
            -moz-transition: all, 0.15s;
            -o-transition: all, 0.15s;
            -ms-transition: all, 0.15s;
            -webkit-transition: all, 0.15s;
            transition: all, 0.15s;
            left: 0;
            right: 0;
            top: 0;
            position: absolute;
        }

        #gspb_addons .gspb-cards-list .gspb-card .gspb-inner>ul>li {
            list-style: none;
            line-height: 18px;
            padding: 0 15px;
            width: 100%;
            display: block;
            box-sizing: border-box;
        }

        #gspb_addons .gspb-cards-list .gspb-card .gspb-inner .gspb-card-banner {
            padding: 0;
            margin: 0;
            display: block;
            height: 100px;
            background-repeat: repeat-x;
            background-size: 100% 100%;
            transition: all, 0.15s;
        }

        .gspb-badge {
            position: absolute;
            top: 10px;
            right: 0;
            background: #71ae00;
            color: white;
            text-transform: uppercase;
            padding: 5px 10px;
            border-radius: 3px 0 0 3px;
            font-weight: bold;
            border-right: 0;
            box-shadow: 0 2px 1px -1px rgb(0 0 0 / 30%);
        }

        #gspb_addons .gspb-cards-list .gspb-card .gspb-inner .gspb-title {
            margin: 10px 0 0 0;
            height: 18px;
            overflow: hidden;
            color: #000;
            white-space: nowrap;
            text-overflow: ellipsis;
            font-weight: bold;
        }

        #gspb_addons .gspb-cards-list .gspb-card .gspb-inner .gspb-offer {
            font-size: 0.9em;
        }

        #gspb_addons .gspb-cards-list .gspb-card .gspb-inner .gspb-description {
            background-color: #f9f9f9;
            padding: 10px 15px 100px 15px;
            border-top: 1px solid #eee;
            margin: 0 0 10px 0;
            color: #777;
        }

        #gspb_addons .gspb-cards-list .gspb-card .gspb-inner .gspb-cta .button,
        #gspb_addons .gspb-cards-list .gspb-card .gspb-inner .gspb-cta .button-group {
            position: absolute;
            top: 112px;
            right: 10px;
            min-height: 20px;
            line-height: 30px;
            background: #2184f9;
        }

        @media screen and (min-width: 960px) {
            #gspb_addons .gspb-cards-list .gspb-card:hover .gspb-inner ul {
                top: -100px;
            }

            #gspb_addons .gspb-cards-list .gspb-card:hover .gspb-inner .gspb-title,
            #gspb_addons .gspb-cards-list .gspb-card:hover .gspb-inner .gspb-offer {
                color: #29abe1;
            }

            #gspb_addons .gspb-cards-list .gspb-card:hover .gspb-inner .gspb-title,
            #gspb_addons .gspb-cards-list .gspb-card:hover .gspb-inner .gspb-offer {
                color: #29abe1;
            }
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
            <a href="?page=greenshift_dashboard-addons" class="nav-tab nav-tab-active">
                <?php esc_html_e("Addons", 'greenshift-animation-and-page-builder-blocks'); ?>
            </a>
            <a href="?page=greenshift_upgrade" class="nav-tab">
                <?php esc_html_e("Upgrade", 'greenshift-animation-and-page-builder-blocks'); ?>
            </a>
            <a href="?page=greenshift_contact" class="nav-tab">
                <?php esc_html_e("Contact Us", 'greenshift-animation-and-page-builder-blocks'); ?>
            </a>
        </div>
    </nav>

    <div class="gs-padd">
        <div class="gs_grid">
            <div class="greenshift_form gs_grid_1" id="gspb_addons">
                <h1><?php esc_html_e("Your addons", 'greenshift-animation-and-page-builder-blocks'); ?></h1>
                <p class="gs-introtext"><?php esc_html_e("Here you can find your active addons. Each addon extends functionality of your shop by additional blocks and features. You can buy them separately or as part of plans.", 'greenshift-animation-and-page-builder-blocks'); ?></p>
                <ul class="gspb-cards-list">
                    <li class="gspb-card gspb-addon" data-slug="greenshiftgsap">
                        <div class="gspb-inner">
                            <ul>
                                <li class="gspb-card-banner" style="background-image: url('//s3-us-west-2.amazonaws.com/freemius/plugins/9741/card_banner.png');">
                                    <?php $is_active = ((!empty($licenses['all_in_one']) && $licenses['all_in_one'] == 'valid') || (!empty($licenses['gsap_addon']) && $licenses['gsap_addon'] == 'valid') || (!empty($licenses['all_in_one_design']) && $licenses['all_in_one_design'] == 'valid')) ? true : false; ?>
                                    <?php if (($is_active || defined('REHUB_ADMIN_DIR')) && defined('GREENSHIFTGSAP_DIR_URL')) : ?>
                                        <span class="gspb-badge">Active</span>
                                    <?php endif; ?>
                                </li>
                                <!-- <li class="gspb-tag"></li> -->
                                <li class="gspb-title">Advanced Animations</li>
                                <li class="gspb-offer">
                                    <span class="gspb-price">$19.99</span>
                                </li>
                                <li class="gspb-description">Add motion and animations like on top awwarded sites without code knowledge <br><br>
                                    <a class="gspb-buttonbox" href="https://greenshiftwp.com/block-gallery/#animation" target="_blank" rel="noopener">Details and Demo</a>
                                </li>
                                <li class="gspb-cta"><a class="button button-primary" href="https://shop.greenshiftwp.com/downloads/advanced-animation-addon/" target="_blank"><?php esc_html_e("Buy Now", "greenshift-animation-and-page-builder-blocks"); ?></a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="gspb-card gspb-addon" data-slug="greenshiftquery">
                        <div class="gspb-inner">
                            <ul>
                                <li class="gspb-card-banner" style="background-image: url('//s3-us-west-2.amazonaws.com/freemius/plugins/10066/card_banner.png');"></li>
                                <?php $is_active = ((!empty($licenses['all_in_one']) && $licenses['all_in_one'] == 'valid') || (!empty($licenses['query_addon']) && $licenses['query_addon'] == 'valid') || (!empty($licenses['all_in_one_design']) && $licenses['all_in_one_design'] == 'valid') || (!empty($licenses['all_in_one_seo']) && $licenses['all_in_one_seo'] == 'valid')) ? true : false; ?>
                                <?php if ($is_active && defined('GREENSHIFTQUERY_DIR_URL')) : ?>
                                    <span class="gspb-badge">Active</span>
                                <?php endif; ?>
                                <li class="gspb-title">Greenshift Query</li>
                                <li class="gspb-offer">
                                    <span class="gspb-price">$19.99</span>
                                </li>
                                <li class="gspb-description">Custom templates, dynamic content, listings, grid, taxonomy blocks<br><br>
                                    <a class="gspb-buttonbox" href="https://greenshiftwp.com/block-gallery/#query" target="_blank" rel="noopener">Details and Demo</a>
                                </li>
                                <li class="gspb-cta"><a class="button button-primary" href="https://shop.greenshiftwp.com/downloads/query-addon/" rel="noopener" target="_blank"><?php esc_html_e("Buy Now", "greenshift-animation-and-page-builder-blocks"); ?></a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="gspb-card gspb-addon" data-slug="greenshiftseo">
                        <div class="gspb-inner">
                            <ul>
                                <li class="gspb-card-banner" style="background-image: url('//s3-us-west-2.amazonaws.com/freemius/plugins/10102/card_banner.png');"></li>
                                <?php $is_active = (((!empty($licenses['all_in_one']) && $licenses['all_in_one'] == 'valid') || (!empty($licenses['seo_addon']) && $licenses['seo_addon'] == 'valid') || (!empty($licenses['all_in_one_seo']) && $licenses['all_in_one_seo'] == 'valid'))) ? true : false; ?>
                                <?php if ($is_active && defined('GREENSHIFTSEO_DIR_URL')) : ?>
                                    <span class="gspb-badge">Active</span>
                                <?php endif; ?>
                                <li class="gspb-title">Seo and Marketing Addon</li>
                                <li class="gspb-offer">
                                    <span class="gspb-price">$29.99</span>
                                </li>
                                <li class="gspb-description">Special mobile and seo optimized blocks which can help to earn money<br><br>
                                    <a class="gspb-buttonbox" href="https://greenshiftwp.com/block-gallery/#seo" target="_blank" rel="noopener">Details and Demo</a>
                                </li>
                                <li class="gspb-cta"><a class="button button-primary" href="https://shop.greenshiftwp.com/downloads/marketing-and-seo-addon/" target="_blank"><?php esc_html_e("Buy Now", "greenshift-animation-and-page-builder-blocks"); ?></a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="gspb-card gspb-addon" data-slug="greenshiftchart">
                        <div class="gspb-inner">
                            <ul>
                                <li class="gspb-card-banner" style="background-image: url('//s3-us-west-2.amazonaws.com/freemius/plugins/10835/card_banner.png');"></li>
                                <?php $is_active = (((!empty($licenses['all_in_one']) && $licenses['all_in_one'] == 'valid') || (!empty($licenses['chart_addon']) && $licenses['chart_addon'] == 'valid'))) ? true : false; ?>

                                <?php if ($is_active && defined('GSCBN_VERSION')) : ?>
                                    <span class="gspb-badge">Active</span>
                                <?php endif; ?>
                                <li class="gspb-title">Greenshift Chart</li>
                                <li class="gspb-offer">
                                    <span class="gspb-price">$9.50</span>
                                </li>
                                <li class="gspb-description">Do you want to improve visual hierarchy and presentation in your content<br><br>
                                    <a class="gspb-buttonbox" href="https://greenshiftwp.com/block-gallery/#chart" target="_blank" rel="noopener">Details and Demo</a>
                                </li>
                                <li class="gspb-cta"><a class="button button-primary" href="https://shop.greenshiftwp.com/downloads/greenshift-chart-plugin/" rel="noopener" target="_blank"><?php esc_html_e("Buy Now", "greenshift-animation-and-page-builder-blocks"); ?></a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
            <?php if(!$is_allinone):?>
            <div class="gs_grid_2">
                <div class="greenshift_form">
                    <h3><?php esc_html_e("Save your money with plans", 'greenshift-animation-and-page-builder-blocks'); ?></h3>

                    <div class="gs_main_text">
                        <a href="?page=greenshift_upgrade" class="gs_sec_btn" style="width:100%; display:block; margin-top:25px">
                            <?php esc_html_e("Upgrade", 'greenshift-animation-and-page-builder-blocks'); ?>
                        </a>
                    </div>
                </div>
            </div>
            <?php endif;?>
        </div>
    </div>
</div>