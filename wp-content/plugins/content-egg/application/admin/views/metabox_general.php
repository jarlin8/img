<?php defined('\ABSPATH') || exit; ?>
<?php \wp_nonce_field('contentegg_metabox', 'contentegg_nonce'); ?>

<div class="row" style="padding-top: 7px;">
    <div class="col-sm-12 col-md-9">
        <?php
        $tpl_manager = ContentEgg\application\components\BlockTemplateManager::getInstance();
        $templates = $tpl_manager->getTemplatesList(true);
        ?>
        <input class="input-sm col-sm-12 col-md-5 shortcode-input" ng-model="blockShortcode" select-on-click readonly type="text" />
        <select class="input-sm col-md-3" ng-init="blockShortcodeBuillder.template = '<?php echo esc_attr(key($templates)); ?>'; buildBlockShortcode();" ng-model="blockShortcodeBuillder.template" ng-change="buildBlockShortcode();">
            <?php foreach ($templates as $id => $name) : ?>
                <option value="<?php echo esc_attr($id); ?>"><?php echo esc_html($name); ?></option>
            <?php endforeach; ?>
        </select>
        <select ng-show="productGroups.length" class="input-sm col-md-2" ng-model="blockShortcodeBuillder.group" ng-change="buildBlockShortcode();">
            <option value="">- <?php esc_html_e('Groups', 'content-egg'); ?> ({{productGroups.length}}) -</option>
            <option ng-repeat="group in productGroups" value="{{group}}">{{group}}</option>
        </select>
        <input class="input-sm col-md-2" ng-model="blockShortcodeBuillder.next" ng-change="buildBlockShortcode();" placeholder="Next" type="number" step="1" />
    </div>
    <div class="col-sm-12 col-md-3 text-right">

        <?php if ($keywordsExist) : ?>
            <input type="submit" id="cegg_update_lists" class="button button-small" value="<?php esc_html_e('Refresh listings', 'content-egg'); ?>" title="<?php esc_html_e('Refresh all product listings by autoupdate keywords', 'content-egg'); ?>">
        <?php endif; ?>
        <?php if ($dataExist) : ?>
            <input type="submit" id="cegg_update_prices" class="button button-small" value="<?php esc_html_e('Update prices', 'content-egg'); ?>" title="<?php esc_html_e('Update all prices', 'content-egg'); ?>">
        <?php endif; ?>
        <style>
            body.cegg_wait *,
            body.cegg_wait {
                cursor: progress !important;
            }
        </style>
        <script>
            jQuery(document).ready(function($) {

                $('#cegg_update_lists, #cegg_update_prices').click(function(e) {
                    e.preventDefault();
                    var this_btn = $(this);
                    $('#cegg_update_lists, #cegg_update_prices, .button, .btn').attr('disabled', true);
                    var nonce = $('#contentegg_nonce').val();
                    $('body').addClass('cegg_wait');

                    $.ajax({
                        type: "POST",
                        url: ajaxurl,
                        data: {
                            action: 'cegg_update_products',
                            btn: this_btn.attr('id'),
                            contentegg_nonce: nonce,
                            post_id: <?php echo \esc_attr($post->ID); ?>
                        },
                        success: function(data) {
                            location.reload();
                        },
                        error: function(errorThrown) {
                            location.reload();
                        },
                        timeout: 180000
                    });
                    return false;
                });
            });
        </script>
    </div>
</div>

<div class="row">

    <div class="col-md-7">

        <div class="input-group">
            <input ng-disabled="processCounter" type="text" ng-model="global_keywords" select-on-click on-enter="global_findAll()" class="form-control" placeholder="<?php esc_html_e('Keyword to search across all modules', 'content-egg'); ?>" aria-label="<?php esc_html_e('Keyword to search across all modules', 'content-egg'); ?>">
            <div class="input-group-btn">
                <button ng-disabled='processCounter || !global_keywords' ng-click="global_findAll()" type="button" class="btn btn-info" aria-label="Find ">
                    <?php esc_html_e('Find all', 'content-egg'); ?>
                </button>
                <button ng-show='!processCounter && global_isSearchResults()' ng-click="global_addAll()" type="button" class="btn btn-default"><?php esc_html_e('Add all', 'content-egg'); ?></button>
                <button ng-show='global_isAddedResults()' ng-click="global_deleteAll()" ng-confirm-click="<?php esc_html_e('Are you sure you want to delete the results of all modules?', 'content-egg'); ?>" type="button" class="btn btn-default"><?php esc_html_e('Remove all', 'content-egg'); ?></button>

            </div>
        </div>

    </div>

    <div class="col-md-5">
        <div class="col-md-6" class="text-right">
            <div class="input-group">
                <input type="text" ng-model="newProductGroup" select-on-click on-enter="addProductGroup()" class="form-control input-sm" placeholder="<?php esc_html_e('Add product group', 'content-egg'); ?>" aria-label="<?php esc_html_e('Add product group', 'content-egg'); ?>">
                <div class="input-group-btn">
                    <button ng-disabled="!newProductGroup" ng-click="addProductGroup()" type="button" class="btn btn-success btn-sm" aria-label="Add">
                        +
                    </button>
                </div>
            </div>
        </div>
        <?php
        if (!$global_keyword = \get_post_meta($post->ID, '_cegg_global_autoupdate_keyword', true))
            $global_keyword = '';
        ?>
        <input class="input-sm col-md-6" name="globalUpdateKeyword" value="<?php echo esc_attr($global_keyword); ?>" type="text" placeholder="<?php esc_html_e('Global autoupdate keyword', 'content-egg'); ?>" title="<?php esc_html_e('Global autoupdate keyword for all active modules', 'content-egg'); ?>">

    </div>
</div>