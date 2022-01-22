(function ($) {

    $(function () {

        /**
         * Toggle advanced filtering
         */
        $('#wdt-advanced-filter').change(function () {
            if (!$(this).is(':checked')) {
                wpdatatable_config.setCascadeFiltering(0);
                wpdatatable_config.setHideTableBeforeFiltering(0);
                wpdatatable_config.setShowSearchFiltersButton(0);
                wpdatatable_config.setDisableSearchFiltersButton(0);
                $('.wdt-pf-hide-table-before-filtering-block').hide();
                $('.wdt-pf-show-search-filters-button-block').hide();
                $('.wdt-pf-disable-search-filters-button-block').hide();
            }
        });

        /**
         * Toggle Filters in form
         */
        $('#wdt-filter-in-form').change(function () {
            if ($(this).is(':checked')) {
                $('.wdt-pf-hide-table-before-filtering-block').animateFadeIn();
                $('.wdt-pf-show-search-filters-button-block').animateFadeIn();
            } else {
                wpdatatable_config.setHideTableBeforeFiltering(0);
                wpdatatable_config.setShowSearchFiltersButton(0);
                $('.wdt-pf-hide-table-before-filtering-block').hide();
                $('.wdt-pf-show-search-filters-button-block').hide();
            }
        });

        /**
         * Toggle "Cascade Filtering"
         */
        $('#wdt-pf-cascade-filtering').change(function () {
            wpdatatable_config.setCascadeFiltering($(this).is(':checked') ? 1 : 0);
        });

        /**
         * Toggle "Cascade Filtering Logic"
         */
        $('#wdt-pf-cascade-filtering-logic').change(function () {
            wpdatatable_config.setCascadeFilteringLogic($(this).val());
        });

        /**
         * Toggle "Hide table before filters selected"
         */
        $('#wdt-pf-hide-table-before-filtering').change(function () {
            wpdatatable_config.setHideTableBeforeFiltering($(this).is(':checked') ? 1 : 0);
        });

        /**
         * Toggle "Show search filters button"
         */
        $('#wdt-pf-show-search-filters-button').change(function () {
            wpdatatable_config.setShowSearchFiltersButton($(this).is(':checked') ? 1 : 0);
        });

        /**
         * Toggle "Disable search filters button"
         */
        $('#wdt-pf-disable-search-filters-button').change(function () {
            wpdatatable_config.setDisableSearchFiltersButton($(this).is(':checked') ? 1 : 0);
        });


        /**
         * Extend wpdatatable_config object with new properties and methods
         */
        $.extend(wpdatatable_config, {
            cascadeFiltering: 0,
            cascadeFilteringLogic: '',
            hideTableBeforeFiltering: 0,
            showSearchFiltersButton: 0,
            disableSearchFiltersButton: 0,
            setCascadeFiltering: function (cascadeFiltering) {
                wpdatatable_config.cascadeFiltering = cascadeFiltering;
                if (cascadeFiltering === 1) {
                    $('.wdt-pf-cascade-filtering-logic-block').animateFadeIn();
                    $('#wdt-pf-cascade-filtering-logic').selectpicker('refresh').trigger('change');
                    $('#wdt-possible-values-ajax').prop('disabled', true).selectpicker('refresh');
                    $('#wdt-column-range-slider')
                        .prop('disabled', true)
                        .siblings('label')
                        .addClass('c-gray')
                        .parents('div.form-group')
                        .siblings('h4')
                        .addClass('c-gray');
                } else {
                    $('.wdt-pf-cascade-filtering-logic-block').hide();
                    $('#wdt-possible-values-ajax').prop('disabled', false).selectpicker('refresh');
                    $('#wdt-column-range-slider')
                        .prop('disabled', false)
                        .siblings('label')
                        .removeClass('c-gray')
                        .parents('div.form-group')
                        .siblings('h4')
                        .removeClass('c-gray');
                }
                this.dealWithFilterPredefinedValues(cascadeFiltering);
                $('#wdt-pf-cascade-filtering').prop('checked', cascadeFiltering);
            },
            setCascadeFilteringLogic: function (cascadeFilteringLogic) {
                wpdatatable_config.cascadeFilteringLogic = cascadeFilteringLogic;
                $('#wdt-pf-cascade-filtering-logic').selectpicker('val', cascadeFilteringLogic);
            },
            setHideTableBeforeFiltering: function (hideTableBeforeFiltering) {
                wpdatatable_config.hideTableBeforeFiltering = hideTableBeforeFiltering;
                $('#wdt-pf-hide-table-before-filtering').prop('checked', hideTableBeforeFiltering);
            },
            setShowSearchFiltersButton: function (showSearchFiltersButton) {
                wpdatatable_config.showSearchFiltersButton = showSearchFiltersButton;
                $('#wdt-pf-show-search-filters-button').prop('checked', showSearchFiltersButton);
                if (showSearchFiltersButton) {
                    $('.wdt-pf-disable-search-filters-button-block').animateFadeIn();
                } else {
                    wpdatatable_config.setDisableSearchFiltersButton(0);
                    $('.wdt-pf-disable-search-filters-button-block').hide();
                }
            },
            setDisableSearchFiltersButton: function (disableSearchFiltersButton) {
                wpdatatable_config.disableSearchFiltersButton = disableSearchFiltersButton;
                $('#wdt-pf-disable-search-filters-button').prop('checked', disableSearchFiltersButton);
            },
            dealWithFilterPredefinedValues: function (cf) {
                if (cf === 1) {
                    jQuery('.wdt-clear-filters-button, .wdt-clear-filters-widget-button').click();
                    for (var i in wpdatatable_config.columns) {
                        wpdatatable_config.columns[i].filterDefaultValue = null;
                    }
                    $('a[href="#column-filtering-settings"]').on('click', function () {
                        $('#wdt-filter-default-value-selectpicker,' +
                            '#wdt-filter-default-value,' +
                            '#wdt-filter-default-value-to,' +
                            '#wdt-filter-default-value-from')
                            .prop('disabled', true)
                            .parents('div.form-group')
                            .siblings('h4')
                            .addClass('c-gray');
                        $('#wdt-column-filter-type').on('hide.bs.select', function () {
                            $('#wdt-filter-default-value-selectpicker')
                            .prop('disabled', true);
                        })
                    });
                } else {
                    $('a[href="#column-filtering-settings"]').on('click', function () {
                        $('#wdt-filter-default-value-selectpicker,' +
                            '#wdt-filter-default-value,' +
                            '#wdt-filter-default-value-to,' +
                            '#wdt-filter-default-value-from')
                            .prop('disabled', false)
                            .parents('div.form-group')
                            .siblings('h4')
                            .removeClass('c-gray');
                    });

                }

            }
        });

        /**
         * Load the table for editing
         */
        if (typeof wpdatatable_init_config !== 'undefined' && wpdatatable_init_config.advanced_settings !== '') {

            var advancedSettings = JSON.parse(wpdatatable_init_config.advanced_settings);

            if (wpdatatable_init_config.filtering_form === 1) {
                $('.wdt-pf-hide-table-before-filtering-block').animateFadeIn();
                $('.wdt-pf-show-search-filters-button-block').animateFadeIn();
            }

            if (advancedSettings !== null) {

                var cascadeFiltering = advancedSettings.cascadeFiltering;
                var cascadeFilteringLogic = advancedSettings.cascadeFilteringLogic;
                var hideTableBeforeFiltering = advancedSettings.hideTableBeforeFiltering;
                var showSearchFiltersButton = advancedSettings.showSearchFiltersButton;
                var disableSearchFiltersButton = advancedSettings.disableSearchFiltersButton;

                if (typeof cascadeFiltering !== 'undefined') {
                    wpdatatable_config.setCascadeFiltering(cascadeFiltering);
                }

                if (typeof cascadeFilteringLogic !== 'undefined') {
                    wpdatatable_config.setCascadeFilteringLogic(cascadeFilteringLogic);
                }

                if (typeof hideTableBeforeFiltering !== 'undefined') {
                    wpdatatable_config.setHideTableBeforeFiltering(hideTableBeforeFiltering);
                }

                if (typeof showSearchFiltersButton !== 'undefined') {
                    wpdatatable_config.setShowSearchFiltersButton(showSearchFiltersButton);
                }

                if (typeof disableSearchFiltersButton !== 'undefined') {
                    wpdatatable_config.setDisableSearchFiltersButton(disableSearchFiltersButton);
                }

            }
        }

    });

})(jQuery);