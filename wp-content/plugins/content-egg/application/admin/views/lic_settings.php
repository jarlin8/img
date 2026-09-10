<?php defined('\ABSPATH') || exit; ?>
<?php

use ContentEgg\application\Plugin;
use ContentEgg\application\admin\LicConfig;
use ContentEgg\application\admin\LicenseNotices;

$has_key = (string) LicConfig::getInstance()->option('license_key') !== '';
// Keep the "change key" form open when something needs the user's attention
// (a failed activation, an empty-key error) so they can see and fix it.
$has_errors = (bool) \get_settings_errors('license_key');
$open       = $has_errors || !$has_key;

// The secondary actions live inside the status card, so they are built here and
// handed to renderPanel(). When there is no card yet (fresh install, or no status
// check done) renderPanel() falls back to rendering this bare.
ob_start();
?>
<?php if ($has_key): ?><details class="cegg-lic-change"<?php echo $open ? ' open' : ''; ?>><summary><?php \esc_html_e('Change license key', 'content-egg'); ?></summary><?php endif; ?>
<form action="options.php" method="POST">
    <?php \settings_fields($page_slug); ?>
    <table class="form-table">
        <?php \do_settings_fields($page_slug, 'default'); ?>
    </table>
    <?php \submit_button($has_key ? __('Save license key', 'content-egg') : __('Activate license', 'content-egg')); ?>
</form>
<?php if ($has_key): ?></details><?php endif; ?>

<?php if (Plugin::isActivated() && Plugin::canDeactivateLicense()): ?>
    <?php // The slug is hardcoded to match LicConfig::resetLicense()'s own guard,
          // which checks for page=content-egg-lic. Do not swap in page_slug(). ?>
    <form class="cegg-lic-deact"
          action="<?php echo \esc_url(\get_admin_url(\get_current_blog_id(), 'admin.php?page=content-egg-lic')); ?>"
          method="POST"
          onsubmit="return confirm('<?php echo \esc_js(__('Deactivate this license on this site? To move it to another domain, deactivate here first, then remove the plugin from this site.', 'content-egg')); ?>');">
        <input type="hidden" name="cmd" value="lic_reset" />
        <input type="hidden" name="nonce_reset" value="<?php echo \esc_attr(\wp_create_nonce('license_reset')); ?>" />
        <button type="submit" class="cegg-lic-linkbtn"><?php \esc_html_e('Deactivate license', 'content-egg'); ?></button>
    </form>
<?php endif; ?>
<?php
$footer = ob_get_clean();
?>
<div class="wrap">
    <h2>Content Egg Pro <?php \esc_html_e('License', 'content-egg'); ?></h2>

    <?php \settings_errors(); ?>

    <?php LicenseNotices::getInstance()->renderPanel($footer); ?>
</div>
