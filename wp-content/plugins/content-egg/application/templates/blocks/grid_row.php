<?php
defined('\ABSPATH') || exit;

use ContentEgg\application\helpers\TemplateHelper;

?>

<?php

if (isset($cols) && $cols == 3)
    $col_size_xs = 12;
else
    $col_size_xs = 6;

?>

<div class="col-md-<?php echo esc_attr($col_size); ?> col-xs-<?php echo esc_attr($col_size_xs); ?> cegg-gridbox">
    <a<?php TemplateHelper::printRel(); ?> target="_blank" href="<?php echo esc_url_raw($item['url']); ?>">

        <div class="cegg-thumb" style="padding: 5px;">
            <?php if ($item['percentageSaved']) : ?>
                <div class="cegg-promotion">
                    <span class="cegg-discount">- <?php echo esc_html($item['percentageSaved']); ?>%</span>
                </div>
            <?php endif; ?>

            <?php if ($item['img']) : ?>
                <div <?php if ($cols > 2) echo 'class="cegg-image-square"'; ?>>
                    <?php TemplateHelper::displayImage($item, 190, 170); ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="producttitle" style="min-height: 90px;">
            <?php if ($merchant = TemplateHelper::getMerhantName($item)) : ?>
                <div class="cegg-mb10">
                    <span class="text-muted title-case"><?php echo \esc_html($merchant); ?></span>
                </div>
            <?php endif; ?>

            <?php if ($item['rating']) : ?>
                <div class="cegg-title-rating">
                    <?php TemplateHelper::printRating($item, 'small'); ?>
                </div>
            <?php endif; ?>

            <?php echo \esc_html(TemplateHelper::truncate($item['title'], 80)); ?>
        </div>
        <div class="productprice">
            <?php if ($item['price']) : ?>
                <span class="cegg-price cegg-price-color cegg-price-<?php echo \esc_attr(TemplateHelper::getStockStatusClass($item)); ?>"><?php echo esc_html(TemplateHelper::formatPriceCurrency($item['price'], $item['currencyCode'])); ?></span>
                <?php if ($item['priceOld']) : ?><strike class="text-muted"><?php echo esc_html(TemplateHelper::formatPriceCurrency($item['priceOld'], $item['currencyCode'])); ?></strike>&nbsp;<?php endif; ?>
                <?php endif; ?>

                <?php if ($item['stock_status'] == -1) : ?>
                    <div title="<?php echo \esc_attr(sprintf(TemplateHelper::__('Last updated on %s'), TemplateHelper::getLastUpdateFormatted($item['module_id'], $post_id))); ?>" class="cegg-lineheight15 stock-status status-<?php echo \esc_attr(TemplateHelper::getStockStatusClass($item)); ?>">
                        <?php echo \esc_html(TemplateHelper::getStockStatusStr($item)); ?>
                    </div>
                <?php endif; ?>

        </div>

        <div class="cegg-btn-grid">
            <a<?php TemplateHelper::printRel(); ?> target="_blank" href="<?php echo esc_url_raw($item['url']); ?>" class="btn btn-danger"><?php TemplateHelper::buyNowBtnText(true, $item, $btn_text); ?></a>
        </div>
        </a>
</div>