<?php defined('\ABSPATH') || exit; ?>
<div class="cegg-rightcol">

    <?php if (\ContentEgg\application\Plugin::isFree()) : ?>
        <div class="cegg-box" style="margin-top: 95px;">
            <div class="cegg-box-container">
                <h2 style="color: #479f76 !important;">Too Much Niche<br>WP Plugin</h2>
                <a target="_blank" href="https://www.keywordrush.com/toomuchniche?utm_source=cegg&utm_medium=referral&utm_campaign=plugin">
                    <img alt="Too Much Niche WP Plugin" width="100%" src="<?php echo esc_attr(\ContentEgg\PLUGIN_RES); ?>/img/product-roundup-article-type.webp">
                </a>
                <p>AI-generated quality content:</p>
                <ol>
                    <li>Product Roundups</li>
                    <li>Product Reviews</li>
                    <li>Informative Articles</li>
                    <li>How-to Guides</li>
                </ol>
                <div style="text-align: center">
                    <a target="_blank" class="button-cegg-banner" href="https://www.keywordrush.com/toomuchniche?utm_source=cegg&utm_medium=referral&utm_campaign=plugin">View...</a>
                </div>
                <br>
                <hr>
                <h2 style="color: #479f76 !important; font-weight: normal;">Limited-time Offer</h2>
                <p style="text-align: center">
                    Coupon code: <b>CEGG25</b>
                </p>
            </div>
        </div>
        <?php /**
        <div class="cegg-box" style="margin-top: 95px;">
            <div class="cegg-box-container">
                <img src="<?php echo esc_attr(\ContentEgg\PLUGIN_RES); ?>/img/external-importer-pro.jpg" alt="Logo" class="cegg-box-image">
                <a target="_blank" href="https://www.youtube.com/watch?v=GiUZF1U3bYM" class="cegg-box-icon" title="User Profile">
                    <span class="dashicons dashicons-video-alt3"></span>
                </a>
            </div>
            <h2 style="color:#8A2BE2 !important;">External Importer <span class="cegg-box-label">New</span></h2>
            <p>Automated Import from a Website into WooCommerce.</p>
            <ul>
                <li>No API access required</li>
                <li>No work with CSV data feeds</li>
            </ul>
            <p>
                <a target="_blank" class="button-cegg-banner" href="https://www.keywordrush.com/externalimporter?utm_source=cegg&utm_medium=referral&utm_campaign=plugin">View...</a>
            </p>
        </div>
         */ ?>
    <?php endif; ?>

    <?php if (\ContentEgg\application\Plugin::isEnvato()) : ?>
        <div class="cegg-box" style="margin-top: 95px;">
            <h2><?php esc_html_e('Activate plugin', 'content-egg'); ?></h2>
            <p><?php esc_html_e('In order to receive all benefits of Contennt Egg, you need to activate your copy of the plugin.', 'content-egg'); ?></p>
            <p><?php esc_html_e('By activating Contennt Egg license you will unlock premium options - direct plugin updates, access to user panel and official support.', 'content-egg'); ?></p>
            <p>
                <a class="button-cegg-banner" href="<?php echo esc_url(\get_admin_url(\get_current_blog_id(), 'admin.php?page=content-egg-lic')); ?>"><?php esc_html_e('Go to ', 'content-egg'); ?></a>
            </p>
        </div>
    <?php endif; ?>
</div>