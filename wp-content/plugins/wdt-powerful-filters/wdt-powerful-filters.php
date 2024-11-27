<?php

namespace WDTPowerfulFilters;
/**
 * @package Powerful Filters for wpDataTables
 * @version 1.2.1
 */

/*
Plugin Name: Powerful Filters for wpDataTables
Plugin URI: https://wpdatatables.com/documentation/addons/powerful-filters/
Description: An add-on for wpDataTables that provides powerful filtering features: cascade filtering, applying filters on button click, show only filter without the table before user defines the search values.
Version: 1.2.1
Author: TMS-Plugins
Author URI: https://tms-plugins.com
Text Domain: wpdatatables
Domain Path: /languages
*/

use Exception;
use WDTTools;
use WP_Error;
use WPDataTable;

defined('ABSPATH') or die('Access denied');
// Full path to the WDT PF root directory
define('WDT_PF_ROOT_PATH', plugin_dir_path(__FILE__));
// URL of WDT PF integration plugin
define('WDT_PF_ROOT_URL', plugin_dir_url(__FILE__));
// Current version of WDT PF integration plugin
define('WDT_PF_VERSION', '1.2.1');
// Required wpDataTables version
define('WDT_PF_VERSION_TO_CHECK', '3.0');

// Init Powerful Filters for wpDataTables add-on
add_action('plugins_loaded', array('WDTPowerfulFilters\Plugin', 'init'), 10);

// Enqueue Powerful Filters add-on files on back-end edit page
add_action('wdt_enqueue_on_edit_page', array('WDTPowerfulFilters\Plugin', 'wdtPowerfulEnqueueBackendEdit'));

// Enqueue Powerful Filters add-on files on front-end
add_action('wdt_enqueue_on_frontend', array('WDTPowerfulFilters\Plugin', 'wdtPowerfulEnqueueFrontend'), 10, 1);

// Add "Cascade filtering" checkbox on the "Sorting and Filtering" tabpanel
add_action('wdt_add_sorting_and_filtering_element', array('WDTPowerfulFilters\Plugin', 'addPowerfulFiltersElements'));

// Add "Search button" on the Filtering Form
add_action('wpdatatables_filtering_form_search_button', array('WDTPowerfulFilters\Plugin', 'addSearchFiltersButton'), 10, 2);

// Extend table config before saving table to DB
add_filter('wpdatatables_filter_insert_table_array', array('WDTPowerfulFilters\Plugin', 'extendTableConfig'), 10, 1);

// Extend WPDataTable Object with new properties
add_action('wdt_extend_wpdatatable_object', array('WDTPowerfulFilters\Plugin', 'extendTableObject'), 10, 2);

// Extend table description before returning it to the front-end
add_filter('wpdatatables_filter_table_description', array('WDTPowerfulFilters\Plugin', 'extendJSONDescription'), 10, 3);

// Add additional classes to main table HTML element
add_filter('wdt_add_class_to_table_html_element', array('WDTPowerfulFilters\Plugin', 'addAdditionalClasses'), 10, 2);

// Add additional classes to filter in form
add_filter('wdt_add_class_to_filter_in_form_element', array('WDTPowerfulFilters\Plugin', 'addAdditionalClasses'), 10, 2);

// Add additional classes to filter widget
add_action('wdt_add_class_to_filter_widget', array('WDTPowerfulFilters\Plugin', 'addAdditionalClassedFilterWidget'));

// Add Powerful Filters activation setting
add_action('wdt_add_activation', array('WDTPowerfulFilters\Plugin', 'addPowerfulFiltersActivation'));

// Enqueue Powerful Filters add-on files on back-end settings page
add_action('wdt_enqueue_on_settings_page', array('WDTPowerfulFilters\Plugin', 'wdtPowerfulEnqueueBackendSettings'));

// Check auto update
add_filter('pre_set_site_transient_update_plugins', array('WDTPowerfulFilters\Plugin', 'wdtCheckUpdatePowerful'));

// Check plugin info
add_filter('plugins_api', array('WDTPowerfulFilters\Plugin', 'wdtCheckInfoPowerful'), 10, 3);

// Get distinct values for all filters (Independent logic) or for one filter (Dependent logic)
add_action('wp_ajax_wdt_get_cascade_columns_distinct_values', array('WDTPowerfulFilters\Plugin', 'getFiltersDistinctValues'));
add_action('wp_ajax_nopriv_wdt_get_cascade_columns_distinct_values', array('WDTPowerfulFilters\Plugin', 'getFiltersDistinctValues'));

// Add a message for unavailable auto update if plugin is not activated
add_action('in_plugin_update_message-' . plugin_basename(__FILE__), array('WDTPowerfulFilters\Plugin', 'addMessageOnPluginsPagePowerful'));

// Add error message on plugin update if plugin is not activated
add_filter('upgrader_pre_download', array('WDTPowerfulFilters\Plugin', 'addMessageOnUpdatePowerful'), 10, 4);

/**
 * Class Plugin
 * Main entry point of the wpDataTables Powerful Filters
 *
 * @package WDTPowerfulFilters
 */
class Plugin
{


    public static $initialized = false;

    /**
     * Instantiates the class
     *
     * @return bool
     */
    public static function init()
    {
        // Check if wpDataTables is installed
        if (!defined('WDT_ROOT_PATH')) {
            // Show message if wpDataTables is not installed
            add_action('admin_notices', array('WDTPowerfulFilters\Plugin', 'wdtNotInstalled'));
            return false;
        }

        // Check if wpDataTables required version is installed
        if (version_compare(WDT_CURRENT_VERSION, WDT_PF_VERSION_TO_CHECK) < 0) {
            // Show message if required wpDataTables version is not installed
            add_action('admin_notices', array('WDTPowerfulFilters\Plugin', 'wdtRequiredVersionMissing'));
            return false;
        }

        return self::$initialized = true;
    }

    /**
     * Show message if wpDataTables is not installed
     */
    public static function wdtNotInstalled()
    {
        $message = __('Powerful Filters for wpDataTables is an add-on - 
            please install and activate wpDataTables to be able to use it!', 'wpdatatables');
        echo "<div class=\"error\"><p>{$message}</p></div>";
    }

    /**
     * Show message if required wpDataTables version is not installed
     */
    public static function wdtRequiredVersionMissing()
    {
        $message = __('Powerful Filters for wpDataTables add-on requires wpDataTables version '
            . WDT_PF_VERSION_TO_CHECK .
            '. Please update wpDataTables plugin to be able to use it!', 'wpdatatables');
        echo "<div class=\"error\"><p>{$message}</p></div>";
    }

    /**
     * Enqueue Powerful Filters add-on files on back-end
     */
    public static function wdtPowerfulEnqueueBackendEdit()
    {
        if (self::$initialized) {
            wp_enqueue_style(
                'wdt-pf-stylesheet',
                WDT_PF_ROOT_URL . 'assets/css/wdt.pf.css',
                array(),
                WDT_PF_VERSION
            );
            wp_enqueue_script(
                'wdt-pf-backend',
                WDT_PF_ROOT_URL . 'assets/js/wdt.pf.backend.js',
                array(),
                WDT_PF_VERSION,
                true
            );
            wp_enqueue_script(
                'wdt-pf-frontend',
                WDT_PF_ROOT_URL . 'assets/js/wdt.pf.frontend.js',
                array(),
                WDT_PF_VERSION,
                true
            );

            \WDTTools::exportJSVar('wdtPfDashboard', is_admin());
            \WDTTools::exportJSVar('wdtPfTranslationStrings', \WDTTools::getTranslationStrings());
        }
    }

    /**
     * Enqueue Powerful Filters add-on files on front-end
     *
     * @param $wpDataTable \WPDataTable
     */
    public static function wdtPowerfulEnqueueFrontend($wpDataTable)
    {
        if (self::$initialized && $wpDataTable->filterEnabled()) {
            wp_enqueue_script(
                'wdt-pf-frontend',
                WDT_PF_ROOT_URL . 'assets/js/wdt.pf.frontend.js',
                array(),
                WDT_PF_VERSION,
                true
            );

            wp_enqueue_style(
                'wdt-pf-stylesheet',
                WDT_PF_ROOT_URL . 'assets/css/wdt.pf.css',
                array(),
                WDT_PF_VERSION
            );

            \WDTTools::exportJSVar('ajaxurl', admin_url('admin-ajax.php'));
            \WDTTools::exportJSVar('wdtPfDashboard', is_admin());
            \WDTTools::exportJSVar('wdtPfTranslationStrings', \WDTTools::getTranslationStrings());
        }
    }

    /**
     * Add Power Filters elements on table configuration page checkbox on the "Sorting and Filtering" tabpanel
     */
    public static function addPowerfulFiltersElements()
    {
        if (self::$initialized) {
            ob_start();
            include WDT_PF_ROOT_PATH . 'templates/cascade_filter_checkbox.inc.php';
            $cascadeFilteringCheckbox = ob_get_contents();
            ob_end_clean();

            echo $cascadeFilteringCheckbox;

            ob_start();
            include WDT_PF_ROOT_PATH . 'templates/cascade_filter_logic_selectbox.inc.php';
            $cascadeFilteringLogicSelectbox = ob_get_contents();
            ob_end_clean();

            echo $cascadeFilteringLogicSelectbox;

            ob_start();
            include WDT_PF_ROOT_PATH . 'templates/hide_table_before_filters_selected.inc.php';
            $hideTableBeforeFilteringCheckbox = ob_get_contents();
            ob_end_clean();

            echo $hideTableBeforeFilteringCheckbox;

            ob_start();
            include WDT_PF_ROOT_PATH . 'templates/search_filters_button_checkbox.inc.php';
            $searchFiltersButtonCheckbox = ob_get_contents();
            ob_end_clean();

            echo $searchFiltersButtonCheckbox;

            ob_start();
            include WDT_PF_ROOT_PATH . 'templates/disable_search_filters_button_checkbox.inc.php';
            $disableSearchFiltersButtonCheckbox = ob_get_contents();
            ob_end_clean();

            echo $disableSearchFiltersButtonCheckbox;
        }
    }

    /**
     * Add "Search button" on the Filtering Form
     *
     * @param $showSearchFiltersButton
     * @param $disableSearchFiltersButton
     */
    public static function addSearchFiltersButton($showSearchFiltersButton, $disableSearchFiltersButton)
    {
        if ($showSearchFiltersButton !== null && $showSearchFiltersButton) {

            /** @noinspection PhpUnusedLocalVariableInspection */
            $disableSearchFiltersButton = ($disableSearchFiltersButton !== null && $disableSearchFiltersButton);

            ob_start();
            include WDT_PF_ROOT_PATH . 'templates/search_filters_button.inc.php';
            $searchFiltersButton = ob_get_contents();
            ob_end_clean();

            echo $searchFiltersButton;
        }
    }

    /**
     * Function that extend table config before saving table to the database
     *
     * @param $tableConfig - array that contains table configuration
     * @return mixed
     */
    public static function extendTableConfig($tableConfig)
    {
        $table = apply_filters(
            'wpdatatables_before_save_table',
            json_decode(
                stripslashes_deep($_POST['table'])
            )
        );

        $advancedSettings = json_decode($tableConfig['advanced_settings']);
        $advancedSettings->cascadeFiltering = $table->cascadeFiltering;
        $advancedSettings->cascadeFilteringLogic = $table->cascadeFilteringLogic;
        $advancedSettings->hideTableBeforeFiltering = $table->hideTableBeforeFiltering;
        $advancedSettings->showSearchFiltersButton = $table->showSearchFiltersButton;
        $advancedSettings->disableSearchFiltersButton = $table->disableSearchFiltersButton;

        $tableConfig['advanced_settings'] = json_encode($advancedSettings);

        return $tableConfig;
    }

    /**
     * Function that extend $wpDataTable object with new properties
     *
     * @param $wpDataTable \WPDataTable
     * @param $tableData \stdClass
     */
    public static function extendTableObject($wpDataTable, $tableData)
    {
        if (!empty($tableData->advanced_settings)) {
            $advancedSettings = json_decode($tableData->advanced_settings);

            if (isset($advancedSettings->cascadeFiltering)) {
                $wpDataTable->cascadeFiltering = $advancedSettings->cascadeFiltering;
            }

            if (isset($advancedSettings->cascadeFilteringLogic)) {
                $wpDataTable->cascadeFilteringLogic = $advancedSettings->cascadeFilteringLogic;
            }

            if (isset($advancedSettings->hideTableBeforeFiltering)) {
                $wpDataTable->hideTableBeforeFiltering = $advancedSettings->hideTableBeforeFiltering;
            }

            if (isset($advancedSettings->showSearchFiltersButton)) {
                $wpDataTable->showSearchFiltersButton = $advancedSettings->showSearchFiltersButton;
            }

            if (isset($advancedSettings->disableSearchFiltersButton)) {
                $wpDataTable->disableSearchFiltersButton = $advancedSettings->disableSearchFiltersButton;
            }

        }
    }

    /**
     * Function that extend table description before returning it to the front-end
     *
     * @param $tableDescription \stdClass
     * @param $wpDataTable \WPDataTable
     * @return mixed
     */
    public static function extendJSONDescription($tableDescription, $tableId, $wpDataTable)
    {

        if (isset($wpDataTable->cascadeFiltering)) {
            $tableDescription->cascadeFiltering = $wpDataTable->cascadeFiltering;
        }

        if (isset($wpDataTable->cascadeFilteringLogic)) {
            $tableDescription->cascadeFilteringLogic = $wpDataTable->cascadeFilteringLogic;
        }

        if (isset($wpDataTable->hideTableBeforeFiltering)) {
            $tableDescription->hideTableBeforeFiltering = $wpDataTable->hideTableBeforeFiltering;
        }

        if (isset($wpDataTable->showSearchFiltersButton)) {
            $tableDescription->showSearchFiltersButton = $wpDataTable->showSearchFiltersButton;
        }

        if (isset($wpDataTable->disableSearchFiltersButton)) {
            $tableDescription->disableSearchFiltersButton = $wpDataTable->disableSearchFiltersButton;
        }

        return $tableDescription;
    }

    /**
     * Add classes depending on wpDataTable options
     *
     * @param $cssClasses
     * @param $tableId
     * @return string
     * @internal param $wpDataTable
     */
    public static function addAdditionalClasses($cssClasses, $tableId)
    {
        try {
            $tableConfig = \WDTConfigController::loadTableFromDB($tableId);
            $advancedSettings = json_decode($tableConfig->advanced_settings);

            if ($advancedSettings !== null &&
                isset($advancedSettings->cascadeFiltering) &&
                $advancedSettings->cascadeFiltering === 1) {
                $cssClasses .= ' wdt-cascade-filtering-container';
            }

            if ($advancedSettings !== null &&
                isset($advancedSettings->hideTableBeforeFiltering) &&
                $advancedSettings->hideTableBeforeFiltering === 1) {
                $cssClasses .= ' wdt-hide-table-before-filtering-container';
            }

            if ($advancedSettings !== null &&
                isset($advancedSettings->showSearchFiltersButton) &&
                $advancedSettings->showSearchFiltersButton === 1) {
                $cssClasses .= ' wdt-search-filters-button-container';
            }

        } catch (Exception $e) {
            $cssClasses = __('Error: ', 'wpdatatables') . $e->getMessage();
        }

        return $cssClasses;
    }

    /**
     * Add classes on filtering widget
     */
    public static function addAdditionalClassedFilterWidget()
    {
        echo '  wdt-cascade-filtering-container 
                wdt-hide-table-before-filtering-container 
                wdt-search-filters-button-container';
    }

    /**
     * Function that returns array of columns distinct values where key
     * is column positions (filter index) and value is array with column
     * distinct values
     *
     * @throws \WDTException
     */
    public static function getFiltersDistinctValues()
    {
        global $wdtVar1, $wdtVar2, $wdtVar3, $wpdb;

        $result = array();
        $tableId = (int)$_POST['tableId'];
        $filterParams = isset($_POST['filterParams']) ? $_POST['filterParams'] : array();
        $columnIndex = isset($_POST['columnIndex']) ? $_POST['columnIndex'] : null;
        $currentColumnExactFilter = isset($_POST['currentColumnExactFilter']) ?
            $_POST['currentColumnExactFilter'] :
            false;

        if (!empty($tableId)) {
            $tableConfig = \WDTConfigController::loadTableFromDB($tableId);
            $columnsConfig = \WDTConfigController::loadColumnsFromDB($tableId);

            $placeholderBeforeFilterValue = "'%";
            $placeholderAfterFilterValue = "%'";
            $placeholderCondition = "LIKE";

            if ($currentColumnExactFilter == 1) {
                $placeholderBeforeFilterValue = "'";
                $placeholderAfterFilterValue = "'";
                $placeholderCondition = "=";
            }

            $wdtVar1 = isset($_GET['wdt_var1']) ? wdtSanitizeQuery($_GET['wdt_var1']) : $tableConfig->var1;
            $wdtVar2 = isset($_GET['wdt_var2']) ? wdtSanitizeQuery($_GET['wdt_var2']) : $tableConfig->var2;
            $wdtVar3 = isset($_GET['wdt_var3']) ? wdtSanitizeQuery($_GET['wdt_var3']) : $tableConfig->var3;
            $tableContent = $tableConfig->content;

            $tableContent = \WDTTools::applyPlaceholders($tableContent);

            $vendor = \Connection::getVendor($tableConfig->connection);
            $isMySql = $vendor === \Connection::$MYSQL;
            $isMSSql = $vendor === \Connection::$MSSQL;
            $isPostgreSql = $vendor === \Connection::$POSTGRESQL;


            if ($isMSSql) {
                $leftSysIdentifier = '[';
                $rightSysIdentifier = ']';
            }

            if ($isPostgreSql) {
                $leftSysIdentifier = '"';
                $rightSysIdentifier = '"';
            }

            if ($isMySql) {
                $leftSysIdentifier = '`';
                $rightSysIdentifier = '`';
            }

            foreach ($columnsConfig as $column) {
                if (!in_array($column->filter_type, array('select', 'multiselect', 'checkbox')) ||
                    ($columnIndex !== null && $columnIndex !== $column->pos) ||
                    array_key_exists($column->orig_header, $filterParams)
                ) {
                    continue;
                }

                $query = 'SELECT DISTINCT '
                    . $leftSysIdentifier
                    . $column->orig_header
                    . $rightSysIdentifier
                    . ' FROM (' . $tableContent . ') AS orig_table WHERE 1 = 1';

                // If Users see and edit only own data is enabled
                if ($tableConfig->edit_only_own_rows === 1) {
                    $userIdColumn = \WDTConfigController::loadSingleColumnFromDB($tableConfig->userid_column_id);
                    $query .= ' AND '
                        . $leftSysIdentifier
                        . $userIdColumn['orig_header']
                        . $rightSysIdentifier
                        . ' = '
                        . get_current_user_id();
                }

                /** @var array $filterParams */
                foreach ($filterParams as $columnKey => $filterSearchValues) {
                    $columnKey = sanitize_text_field($columnKey);
                    if (in_array($filterSearchValues, array('~', '|'))) {
                        continue;
                    }

                    if ($currentColumnExactFilter == 1) {
                        if (strpos($filterSearchValues, '$') !== false) {
                            $filterValuesArray = explode('$|^', $filterSearchValues);
                            $filterValuesArray[0] = substr($filterValuesArray[0], 1);
                            if (count($filterValuesArray) > 1) {
                                $filterValuesArray[count($filterValuesArray) - 1] =
                                    substr($filterValuesArray[count($filterValuesArray) - 1], 0, -1);
                            } else {
                                $filterValuesArray[0] = substr($filterValuesArray[0], 0, -1);
                            }
                        } else {
                            if (strpos($filterSearchValues, '||')) {
                                $filterValuesArray = preg_split('/(?<!\|)\|(?!\|)/', $filterSearchValues);
                            } else {
                                $filterValuesArray = explode('|', $filterSearchValues);
                            }
                        }
                    } else {
                        if (strpos($filterSearchValues, '||')) {
                            $filterValuesArray = preg_split('/(?<!\|)\|(?!\|)/', $filterSearchValues);
                        } else {
                            $filterValuesArray = explode('|', $filterSearchValues);
                        }
                    }

                    $j = 0;
                    $query .= ' AND (';

                    foreach ($filterValuesArray as $filterValue) {

                        $filterValue = $placeholderBeforeFilterValue . $filterValue . $placeholderAfterFilterValue;

                        if ($j > 0) {
                            $query .= ' OR ';
                        }

                        $query .= "orig_table."
                            . $leftSysIdentifier
                            . $columnKey
                            . $rightSysIdentifier
                            . " "
                            . $placeholderCondition
                            . " "
                            . $filterValue;
                        $j++;
                    }

                    $query .= ') ';

                }

                if (\Connection::isSeparate($tableConfig->connection)) {
                    $sql = \Connection::create($tableConfig->connection);
                    $fetchedResults = array_filter($sql->getArray($query));
                    foreach ($fetchedResults as $oneFetchedResult) {
                        $result[$column->pos][] = $oneFetchedResult[0];
                    }
                    array_filter($result[$column->pos]);
                } else {
                    global $wpdb;
                    $result[$column->pos] = array_filter($wpdb->get_col($query));
                    if ($wpdb->last_error) {
                        return false;
                    }
                }

                sort($result[$column->pos]);

                $advancedSettings = json_decode($column->advanced_settings);

                if (isset($advancedSettings->foreignKeyRule) &&
                    $advancedSettings->foreignKeyRule !== null) {
                    $foreignKeyRule = $advancedSettings->foreignKeyRule;
                    $joinedTable = WPDataTable::loadWpDataTable($foreignKeyRule->tableId);
                    $distinctValues = $joinedTable->getDistinctValuesForColumns($foreignKeyRule);
                    $intersectedValues = array_intersect_key($distinctValues, array_flip($result[$column->pos]));

                    $result[$column->pos] = [];
                    foreach ($intersectedValues as $key => $value) {
                        $result[$column->pos][] = [
                            'value' => $key,
                            'label' => $value
                        ];
                    }
                } else {
                    $resultCopy = $result;
                    $result[$column->pos] = [];
                    foreach ($resultCopy[$column->pos] as $value) {
                        $labelValue = self::prepareLabel($column, $value);
                        $result[$column->pos][] = [
                            'value' => $value,
                            'label' => $labelValue
                        ];
                    }
                }

            }

            echo json_encode($result);
            exit();
        }
    }

    public static function prepareLabel($column, $value)
    {
        switch ($column->column_type) {
            case 'date':
                $labelValue = date(get_option('wdtDateFormat'), $value);
                break;

            case 'datetime':
                $timestamp = strtotime(str_replace('/', '-', $value));
                $labelValue = date(get_option('wdtDateFormat') . ' ' . get_option('wdtTimeFormat'), $timestamp);
                break;

            case 'time':
                $timestamp = strtotime(str_replace('/', '-', $value));
                $labelValue = date(get_option('wdtTimeFormat'), $timestamp);
                break;

            case 'float':
                $numberFormat = get_option('wdtNumberFormat') ? get_option('wdtNumberFormat') : 1;
                $decimalPlaces = get_option('wdtDecimalPlaces');
                if ($numberFormat == 1) {
                    $labelValue = number_format(
                        (float)$value,
                        $decimalPlaces,
                        ',',
                        '.'
                    );
                } else {
                    $labelValue = number_format(
                        (float)$value,
                        $decimalPlaces
                    );
                }
                break;

            case 'int':
                $numberFormat = get_option('wdtNumberFormat') ? get_option('wdtNumberFormat') : 1;
                if ($numberFormat == 1) {
                    $labelValue = number_format(
                        (int)$value,
                        0,
                        ',',
                        $column->skip_thousands_separator ? '' : '.'
                    );
                } else {
                    $labelValue = number_format(
                        (int)$value,
                        0,
                        '.',
                        $column->skip_thousands_separator ? '' : ','
                    );
                }
                break;

            case 'link':
                if (strpos($value, '||') !== false) {
                    $value = explode('||', $value);
                    $labelValue = $value[1];
                } else {
                    $labelValue = $value;
                }
                break;

            default:
                $labelValue = $value;

        }

        return $labelValue;
    }

    /**
     * Add Powerful Filters activation on wpDataTables settings page
     */
    public static function addPowerfulFiltersActivation()
    {
        ob_start();
        include WDT_PF_ROOT_PATH . 'templates/activation.inc.php';
        $activation = ob_get_contents();
        ob_end_clean();

        echo $activation;
    }

    /**
     * Enqueue Powerful Filters add-on files on back-end settings page
     */
    public static function wdtPowerfulEnqueueBackendSettings()
    {
        if (self::$initialized) {
            wp_enqueue_script(
                'wdt-pf-settings',
                WDT_PF_ROOT_URL . 'assets/js/wdt.pf.settings.js',
                array(),
                WDT_PF_VERSION,
                true
            );
        }
    }

    /**
     * @param $transient
     *
     * @return mixed
     */
    public static function wdtCheckUpdatePowerful($transient)
    {
        if (class_exists('WDTTools')) {

            $pluginSlug = plugin_basename(__FILE__);

            if (empty($transient->checked)) {
                return $transient;
            }

            $purchaseCode = get_option('wdtPurchaseCodeStorePowerful');

            $envatoTokenEmail = get_option('wdtEnvatoTokenEmailPowerful');

            // Get the remote info
            $remoteInformation = WDTTools::getRemoteInformation('wdt-powerful-filters', $purchaseCode, $envatoTokenEmail);

            // If a newer version is available, add the update
            if ($remoteInformation && version_compare(WDT_PF_VERSION, $remoteInformation->new_version, '<')) {
                $remoteInformation->package = $remoteInformation->download_link;
                $transient->response[$pluginSlug] = $remoteInformation;
            }
        }

        return $transient;
    }

    /**
     * @param $response
     * @param $action
     * @param $args
     *
     * @return bool|mixed
     */
    public static function wdtCheckInfoPowerful($response, $action, $args)
    {

        if (class_exists('WDTTools')) {

            $pluginSlug = plugin_basename(__FILE__);

            if ('plugin_information' !== $action) {
                return $response;
            }

            if (empty($args->slug)) {
                return $response;
            }

            $purchaseCode = get_option('wdtPurchaseCodeStorePowerful');

            $envatoTokenEmail = get_option('wdtEnvatoTokenEmailPowerful');

            if ($args->slug === $pluginSlug) {
                return WDTTools::getRemoteInformation('wdt-powerful-filters', $purchaseCode, $envatoTokenEmail);
            }
        }

        return $response;
    }

    public static function addMessageOnPluginsPagePowerful()
    {
        /** @var bool $activated */
        $activated = get_option('wdtActivatedPowerful');

        /** @var string $url */
        $url = get_site_url() . '/wp-admin/admin.php?page=wpdatatables-settings&activeTab=activation';

        /** @var string $redirect */
        $redirect = '<a href="' . $url . '" target="_blank">' . __('settings', 'wpdatatables') . '</a>';

        if (!$activated) {
            echo sprintf(' ' . __('To receive automatic updates license activation is required. Please visit %s to activate Powerful Filters for wpDataTables.', 'wpdatatables'), $redirect);
        }
    }

    public static function addMessageOnUpdatePowerful($reply, $package, $updater)
    {
        if (isset($updater->skin->plugin_info['Name']) && $updater->skin->plugin_info['Name'] === get_plugin_data( __FILE__ )['Name']) {
            /** @var string $url */
            $url = get_site_url() . '/wp-admin/admin.php?page=wpdatatables-settings&activeTab=activation';

            /** @var string $redirect */
            $redirect = '<a href="' . $url . '" target="_blank">' . __('settings', 'wpdatatables') . '</a>';

            if (!$package) {
                return new WP_Error(
                    'wpdatatables_powerful_not_activated',
                    sprintf(' ' . __('To receive automatic updates license activation is required. Please visit %s to activate Powerful Filters for wpDataTables.', 'wpdatatables'), $redirect)
                );
            }

            return $reply;
        }

        return $reply;
    }
}
