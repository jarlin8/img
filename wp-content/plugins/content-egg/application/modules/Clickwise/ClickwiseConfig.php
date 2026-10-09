<?php

namespace ContentEgg\application\modules\Clickwise;

defined('\ABSPATH') || exit;

use ContentEgg\application\components\AffiliateParserModuleConfig;

/**
 * ClickwiseConfig class file
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link https://www.keywordrush.com
 * @copyright Copyright &copy; 2026 keywordrush.com
 */
class ClickwiseConfig extends AffiliateParserModuleConfig
{
    public function options()
    {
        $options = array(
            'apiKey' => array(
                'title' => 'API Key <span class="cegg_required">*</span>',
                'description' => sprintf(__('Your Clickwise publisher API key. You can find it in your <a target="_blank" href="%s">Clickwise account</a>.', 'content-egg'), 'https://partners.clickwise.net/developers/'),
                'callback' => array($this, 'render_password'),
                'default' => '',
                'validator' => array(
                    'trim',
                    array(
                        'call' => array('\ContentEgg\application\helpers\FormValidator', 'required'),
                        'when' => 'is_active',
                        'message' => sprintf(__('The field "%s" can not be empty.', 'content-egg'), 'API Key'),
                    ),
                ),
            ),
            'country' => array(
                'title' => __('Country', 'content-egg'),
                'description' => __('Search products in one market only.', 'content-egg'),
                'callback' => array($this, 'render_dropdown'),
                'dropdown_options' => self::getCountries(),
                'default' => '',
            ),
            'merchant' => array(
                'title' => __('Merchant ID', 'content-egg'),
                'description' => __('Optional. Search products of one merchant only.', 'content-egg'),
                'callback' => array($this, 'render_input'),
                'default' => '',
                'validator' => array(
                    'trim',
                    'absint',
                ),
            ),
            'entries_per_page' => array(
                'title' => __('Results', 'content-egg'),
                'description' => __('Specify the number of results to display for a single search query.', 'content-egg'),
                'callback' => array($this, 'render_input'),
                'default' => 10,
                'validator' => array(
                    'trim',
                    'absint',
                    array(
                        'call' => array('\ContentEgg\application\helpers\FormValidator', 'less_than_equal_to'),
                        'arg' => 100,
                        'message' => sprintf(__('The field "%s" can not be more than %d.', 'content-egg'), 'Results', 100),
                    ),
                ),
            ),
            'entries_per_page_update' => array(
                'title' => __('Results for updates', 'content-egg'),
                'description' => __('Specify the number of results for automatic updates and autoblogging.', 'content-egg'),
                'callback' => array($this, 'render_input'),
                'default' => 6,
                'validator' => array(
                    'trim',
                    'absint',
                    array(
                        'call' => array('\ContentEgg\application\helpers\FormValidator', 'less_than_equal_to'),
                        'arg' => 100,
                        'message' => sprintf(__('The field "%s" can not be more than %d.', 'content-egg'), 'Results for updates', 100),
                    ),
                ),
            ),
            'priceFrom' => array(
                'title' => __('Price From', 'content-egg'),
                'description' => __('In the currency of the selected country.', 'content-egg'),
                'callback' => array($this, 'render_input'),
                'default' => '',
                'validator' => array(
                    'trim',
                ),
                'metaboxInit' => true,
            ),
            'priceTo' => array(
                'title' => __('Price To', 'content-egg'),
                'description' => __('In the currency of the selected country.', 'content-egg'),
                'callback' => array($this, 'render_input'),
                'default' => '',
                'validator' => array(
                    'trim',
                ),
                'metaboxInit' => true,
            ),
            'group_variants' => array(
                'title' => __('Group size variants', 'content-egg'),
                'description' => __('Show one result per product and color instead of one result per size.', 'content-egg'),
                'callback' => array($this, 'render_checkbox'),
                'default' => true,
            ),
            'save_img' => array(
                'title' => __('Save images', 'content-egg'),
                'description' => __('Save images localy', 'content-egg'),
                'callback' => array($this, 'render_checkbox'),
                'default' => false,
            ),
        );

        $parent = parent::options();
        $options = array_merge($parent, $options);
        $options['update_mode']['default'] = 'cron';

        return self::moveRequiredUp($options);
    }

    /**
     * Markets with a product catalog, per the public catalog stats
     * (/api/v1/public/catalog-stats/, October 2026).
     */
    public static function getCountries()
    {
        return array(
            '' => __('All countries', 'content-egg'),
            'AR' => 'Argentina',
            'AT' => 'Austria',
            'BE' => 'Belgium',
            'CA' => 'Canada',
            'CL' => 'Chile',
            'CO' => 'Colombia',
            'CZ' => 'Czech Republic',
            'DK' => 'Denmark',
            'FR' => 'France',
            'DE' => 'Germany',
            'HU' => 'Hungary',
            'IE' => 'Ireland',
            'IL' => 'Israel',
            'IT' => 'Italy',
            'MX' => 'Mexico',
            'NL' => 'Netherlands',
            'NO' => 'Norway',
            'PE' => 'Peru',
            'PT' => 'Portugal',
            'SK' => 'Slovakia',
            'ES' => 'Spain',
            'SE' => 'Sweden',
            'GB' => 'United Kingdom',
            'US' => 'United States',
        );
    }
}
