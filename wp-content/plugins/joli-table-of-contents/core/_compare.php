<div class="wrap">
    <h1 class="h1-title">Joli FAQ SEO</h1>
    <?php settings_errors(); ?>

    <div class="jfaq-seo-wrap">
        <header class="joli-header">
            <a class="joli-logo" href="https://wpjoli.com" title="WPJoli" target="_blank">
                <img src="<?= $logo_url; ?>" alt="">
            </a>
            <div class="joli-nav">
                <?php foreach ($tabs as $id => $title) : ?>
                    <div id="tab-<?= $id; ?>" class="joli-nav-item">
                        <div class="joli-nav-title">
                            <?= $title; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="joli-version">
                <div class="joli-submit joli-submit-inline">
                    <div class="joli-save-info">
                        <?php submit_button(__('Save settings', 'joli_faq_seo'), 'primary joli-do-submit', 'submit-menu', false); ?>
                    </div>
                </div>
                <!-- <button id="aaa">TEst</button> -->
                <p>v<?= $version; ?></p>
            </div>
        </header>
        <section class="joli-content">
            <form id="jfaq-settings" method="post" action="options.php">
                <div class="tab-content joli-tab-content">
                    <div class="joli-quickstart-notice">
                        <p>
                            <?= __('Quick start: How to create & display FAQs ?', 'joli_faq_seo'); ?>
                        </p>
                        <ol>
                            <li><?= __('From the', 'joli_faq_seo'); ?> <?= sprintf('<a href="%sadmin.php?page=joli_faq_seo_faq_editor">', get_admin_url()) . __('FAQ Editor', 'joli_faq_seo') . '</a>'; ?><?= __(', create a FAQ Group and add FAQs inside.', 'joli_faq_seo'); ?></li>
                            <li><?= __('Next to the FAQ Group name, double click the shortcode to copy it in the clipboard.', 'joli_faq_seo'); ?></li>
                            <li><?= __('Paste the shortcode anywhere in your posts/pages.', 'joli_faq_seo'); ?></li>
                        </ol>
                    </div>
                    <div class="joli-preview">
                        <div id="tab-settings" class="tab-pane">
                            <div class="joli-header-wrap">
                                <p class="joli-title"><span class="joli-styling">Joli</span> FAQ SEO</p>
                            </div>
                            <?php
                            $option_group = JFAQ()::SLUG . '_settings';
                            settings_fields($option_group);
                            do_settings_sections($option_group);
                            ?>
                        </div>
                        <div id="jfaq-demo">
                            <?php if (jfaq_xy()->is_free_plan()) : ?>
                                <div class="joli-gopro-notice">
                                    <p>
                                        <?= __('Get WooCommerce integration, Insights, Emojis, extra Themes/Customization and more with Joli FAQ SEO Pro', 'joli_faq_seo'); ?>
                                    </p>
                                    <p>
                                        <a href="<?= sprintf('%sadmin.php?page=' . JFAQ()::SETTINGS_SLUG .  ' -pricing', get_admin_url()); ?>" class="button button-primary"><?= __('Get Pro now', 'joli_faq_seo'); ?></a>
                                    </p>
                                </div>
                            <?php endif; ?>
                            <h2><?= __('Preview', 'joli_faq_seo'); ?></h2>
                            <div id="jfaq-demo-wrap">
                                <?= $demo_faq ?>
                            </div>
                            <p><em><?= __('Because of your theme\'s style maybe not applying here, this preview may differ slightly from what you will actually see on the frontend. Especially if you use a custom tag for the questions.', 'joli_faq_seo'); ?></em></p>
                        </div>
                    </div>
                </div>
                <div class="joli-submit">
                    <div class="joli-save-info">
                        <div class="joli-info-text"><?= __('Changes unsaved', 'joli_faq_seo'); ?></div>
                        <?php submit_button(__('Save settings', 'joli_faq_seo'), 'primary', 'submit-float', false); ?>
                    </div>
                </div>
            </form>
            <form action="<?= $_SERVER['REQUEST_URI']; ?>" method="post">
                <p>
                    <input type="submit" id="jfaq_reset_settings" name="jfaq_reset_settings" class="button button-secondary button-small" value="<?= __("Reset settings", "joli-table-of-contents"); ?>" data-prompt="<?= __("Are you sure you want to reset settings ? All current settings will be lost.", "joli-table-of-contents"); ?>">
                </p>
            </form>
        </section>
    </div>

</div>