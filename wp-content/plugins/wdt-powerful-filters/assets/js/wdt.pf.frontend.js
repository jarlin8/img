var wpDataTablesHooks = wpDataTablesHooks || {
    onRenderFilter: []
};

/**
 * Hide table before filters selected
 */
wpDataTablesHooks.onRenderFilter.push(function hideTableBeforeFiltering(tableDescription) {
    (function ($) {

        if (tableDescription.hideTableBeforeFiltering) {
            var oTable = wpDataTables[tableDescription.tableId],
                displayLength = tableDescription.dataTableParams.iDisplayLength, isAdminPage = wdtPfDashboard;
            var hideTableBeforeFilteringContainer = '.wdt-hide-table-before-filtering-container[data-wpdatatable_id=' + tableDescription.tableWpId + ']';
            var $wpDataTablesWrapper = $('#' + tableDescription.tableId).closest('.wpDataTablesWrapper');
            oTable.onRenderCheckboxFilterModal = oTable.onRenderCheckboxFilterModal || [];

            $wpDataTablesWrapper.hide();

            // Hide all rows by default
            oTable.api().page.len(0).draw();

            $wpDataTablesWrapper.find('.length_menu').change(function () {
                displayLength = jQuery(this).selectpicker('val');
            });

            if (isAdminPage) {
                jQuery(document).off('change.hideTableBeforeFiltering keyup.hideTableBeforeFiltering blur.hideTableBeforeFiltering');
            }

            if (tableDescription.showSearchFiltersButton) {
                $(hideTableBeforeFilteringContainer + ' .wdt-pf-search-filters-button').on('click', function () {
                    showHiddenTable();
                });
            } else {
                jQuery(document).on('blur.hideTableBeforeFiltering', hideTableBeforeFilteringContainer + ' .wdt-filter-control.date-range-filter, .wdt-filter-control.time-range-filter, .wdt-filter-control.date-time-range-filter', function () {
                    showHiddenTable();
                });
                jQuery(document).on('keyup.hideTableBeforeFiltering ', hideTableBeforeFilteringContainer + ' .wdt-filter-control.text_filter, .wdt-filter-control.number_filter , .wdt-filter-control.number-range-filter', function () {
                    showHiddenTable();
                });
                jQuery(document).on('change.hideTableBeforeFiltering', hideTableBeforeFilteringContainer + ' .wdt-filter-control.wdt-select-filter, .wdt-filter-control.wdt-checkbox-filter, .wdt-filter-control.wdt-multiselect-filter', function () {
                    showHiddenTable();
                });
            }

            /**
             * Show hidden table if one of the filters is selected
             */
            function showHiddenTable() {
                // Check if all filters are cleared
                var optionSelected = false;
                $(hideTableBeforeFilteringContainer + ' .filter_column').each(function () {
                    optionSelected = isFilterOptionSelected($(this));
                    return !optionSelected;
                });

                // If all filters are cleared hide the table
                if (!optionSelected) {
                    oTable.api().page.len(0).draw();
                    $wpDataTablesWrapper.hide();
                } else {
                    oTable.api().page.len(displayLength).draw();
                    $wpDataTablesWrapper.find('.length_menu').selectpicker('val', displayLength);
                    $wpDataTablesWrapper.show();
                }
            }

            /**
             * Add selectors and data to modal for "Hide table before filtering" if checkbox filter is rendered in modal
             * @param $modal
             * @param iColumnIndex
             */
            wpDataTables[tableDescription.tableId].onRenderCheckboxFilterModal.push(function ($modal, iColumnIndex) {
                var $checkboxContainer = $modal.find('.modal-body >:first-child');
                $checkboxContainer.addClass('wdt-hide-table-before-filtering-container');
                $checkboxContainer.data('wpdatatable_id', tableDescription.tableWpId);
                $checkboxContainer.attr('data-wpdatatable_id', tableDescription.tableWpId);
                $checkboxContainer.data('index', iColumnIndex);
                $checkboxContainer.attr('data-index', iColumnIndex);
            });

        }

    })(jQuery);
});

/**
 * Cascade Filtering Function
 */
wpDataTablesHooks.onRenderFilter.push(function cascadeFiltering(tableDescription) {
    (function ($) {

        if (tableDescription.cascadeFiltering === 1) {

            var filterParams = {}, filterPosition, nextFilterPosition, lastFilterPosition, columnDistinctValues,
                $filter, columnIndex, filterType, currentColumnIndex, currentColumn, lastChangedFilterPosition = 0, isAdminPage = wdtPfDashboard;
            var cascadeFilteringContainer = '.wdt-cascade-filtering-container[data-wpdatatable_id=' + tableDescription.tableWpId + ']';
            var oTable = wpDataTables[tableDescription.tableId];
            oTable.onRenderCheckboxFilterModal = oTable.onRenderCheckboxFilterModal || [];

            if (tableDescription.serverSide) {

                if (tableDescription.cascadeFilteringLogic === 'independent') {

                    if (isAdminPage) {
                        jQuery(document).off('change.cascadeFiltering keyup.cascadeFiltering dp.change.cascadeFiltering');
                    }
                    jQuery(document).on('change.cascadeFiltering keyup.cascadeFiltering dp.change.cascadeFiltering', cascadeFilteringContainer + ' .wdt-filter-control:not("select")', function () {
                        // Get the filter params
                        filterParams = getFilterParamsServerSide();
                        // Data index of the current filter depends if is it in filter in form or in modal
                        if ($(this).closest('.modal-body').length) {
                            currentColumnIndex = $(this).closest('.wdt-cascade-filtering-container').data('index');
                        } else {
                            currentColumnIndex = $(this).closest('.filter_column').data('index');
                        }
                        // Data of the column which filter is currently changed
                        currentColumn = tableDescription.advancedFilterOptions.aoColumns[currentColumnIndex];

                        $.ajax({
                            url: ajaxurl + '?' + tableDescription.dataTableParams.ajax.url.split('action=get_wdtable&').pop(),
                            method: 'POST',
                            dataType: 'json',
                            data: {
                                action: 'wdt_get_cascade_columns_distinct_values',
                                tableId: tableDescription.tableWpId,
                                filterParams: filterParams,
                                currentColumnExactFilter: currentColumn.exactFiltering
                            },
                            success: function (columnsDistinctValues) {
                                for (var columnIndex in columnsDistinctValues) {
                                    // Filter that should be updated with the values
                                    $filter = $('.wdt-cascade-filtering-container[data-wpdatatable_id=' + tableDescription.tableWpId + '] .filter_column[data-index=' + columnIndex + ']');
                                    // Filter type of the filter that should be updated with the values
                                    filterType = $filter.data('filter_type');
                                    if ($.inArray(filterType, ['selectbox', 'multiselectbox', 'checkbox']) !== -1) {
                                        // Get the column distinct data
                                        columnDistinctValues = columnsDistinctValues[columnIndex];
                                        // Populate filter with the data
                                        populateFilterWithData($filter, filterType, columnDistinctValues);
                                    }
                                }
                            }
                        });

                    });

                } else {
                    // Disable (selectbox, multiselectbox) and empty (checkbox) all filters except the first one
                    disableAllFiltersExceptFirst();

                    if (isAdminPage) {
                        jQuery(document).off('change.cascadeFiltering keyup.cascadeFiltering dp.change.cascadeFiltering');
                    }
                    jQuery(document).on('change.cascadeFiltering keyup.cascadeFiltering dp.change.cascadeFiltering', cascadeFilteringContainer + ' .wdt-filter-control:not("select")', function () {
                        // If checkbox filter is in the modal
                        if ($(this).closest('.modal-body').length) {
                            filterPosition = $(this).closest('.wdt-cascade-filtering-container').data('index');
                            $filter = $('.wdt-cascade-filtering-container[data-wpdatatable_id=' + tableDescription.tableWpId + '] .filter_column[data-index=' + filterPosition + ']');
                        } else {
                            $filter = $(this).closest('.filter_column');
                        }
                        // Column Index/Filter position of the current changed filter
                        filterPosition = parseInt($filter.data('index'));
                        // Index of the next filter
                        nextFilterPosition = filterPosition + 1;
                        // Index of the last filter
                        lastFilterPosition = $('.wdt-cascade-filtering-container[data-wpdatatable_id=' + tableDescription.tableWpId + '] .filter_column').last().data('index');
                        // Check if some option is selected in the current filter
                        var filterOptionSelected = isFilterOptionSelected($filter);

                        // Check if new filter is applied or already applied filter is updated with new value
                        if (filterOptionSelected && filterPosition >= lastChangedFilterPosition) {

                            while ($('.wdt-cascade-filtering-container[data-wpdatatable_id=' + tableDescription.tableWpId + '] .filter_column[data-index=' + nextFilterPosition + ']').length === 0 && nextFilterPosition <= lastFilterPosition) {
                                nextFilterPosition++;
                            }

                            populateNextFilterServerSide();

                        } else {

                            disableFiltersOnRight(filterPosition, lastFilterPosition);

                            // If not blank value is selected we should enable first next filter from the right and populate it with data
                            if (filterOptionSelected) {
                                // Index of the next filter
                                nextFilterPosition = filterPosition + 1;

                                while ($('.wdt-cascade-filtering-container[data-wpdatatable_id=' + tableDescription.tableWpId + '] .filter_column[data-index=' + nextFilterPosition + ']').length === 0 && nextFilterPosition <= lastFilterPosition) {
                                    nextFilterPosition++;
                                }

                                populateNextFilterServerSide();

                            }
                        }

                        lastChangedFilterPosition = $(this).closest('.filter_column').data('index');

                    });

                }

            } else {

                if (tableDescription.cascadeFilteringLogic === 'independent') {

                    if (isAdminPage) {
                        jQuery(document).off('change.cascadeFiltering keyup.cascadeFiltering dp.change.cascadeFiltering');
                    }
                    jQuery(document).on('change.cascadeFiltering keyup.cascadeFiltering dp.change.cascadeFiltering', cascadeFilteringContainer + ' .wdt-filter-control:not("select")', function () {

                        $('.wdt-cascade-filtering-container[data-wpdatatable_id=' + tableDescription.tableWpId + '] .filter_column')
                            .filter(function () {
                                return !isFilterOptionSelected($(this));
                            })
                            .each(function () {
                                // Filter that should be updated with the values
                                $filter = $(this);
                                // Column Index of the filter that should be updated with the values
                                columnIndex = parseInt($filter.data('index'));
                                // Filter type of the filter that should be updated with the values
                                filterType = $filter.data('filter_type');

                                if ($.inArray(filterType, ['selectbox', 'multiselectbox', 'checkbox']) !== -1) {
                                    // Get the column distinct data
                                    columnDistinctValues = getColumnDistinctValues(tableDescription.tableId, columnIndex, true);
                                    // Populate filter with the data
                                    populateFilterWithData($filter, filterType, columnDistinctValues);
                                }
                            });

                    });

                } else {
                    // Disable (selectbox, multiselectbox) and empty (checkbox) all filters except the first one
                    disableAllFiltersExceptFirst();

                    if (isAdminPage) {
                        jQuery(document).off('change.cascadeFiltering keyup.cascadeFiltering dp.change.cascadeFiltering');
                    }
                    jQuery(document).on('change.cascadeFiltering keyup.cascadeFiltering dp.change.cascadeFiltering', cascadeFilteringContainer + ' .wdt-filter-control:not("select")', function () {
                        // If checkbox filter is in the modal
                        if ($(this).closest('.modal-body').length) {
                            var filterPosition = $(this).closest('.wdt-cascade-filtering-container').data('index');
                            $filter = $('.wdt-cascade-filtering-container[data-wpdatatable_id=' + tableDescription.tableWpId + '] .filter_column[data-index=' + filterPosition + ']');
                        } else {
                            $filter = $(this).closest('.filter_column');
                        }
                        // Column Index/Filter position of the current changed filter
                        filterPosition = parseInt($filter.data('index'));
                        // Index of the next filter
                        nextFilterPosition = filterPosition + 1;
                        // Index of the last filter
                        lastFilterPosition = $('.wdt-cascade-filtering-container[data-wpdatatable_id=' + tableDescription.tableWpId + '] .filter_column').last().data('index');
                        // Check if some option is selected in the current filter
                        var filterOptionSelected = isFilterOptionSelected($filter);

                        // Check if new filter is applied or already applied filter is updated with new value
                        if (filterOptionSelected && filterPosition >= lastChangedFilterPosition) {

                            while ($('.wdt-cascade-filtering-container[data-wpdatatable_id=' + tableDescription.tableWpId + '] .filter_column[data-index=' + nextFilterPosition + ']').length === 0 && nextFilterPosition <= lastFilterPosition) {
                                nextFilterPosition++;
                            }

                            populateNextFilterNonServerSide();

                        } else {

                            disableFiltersOnRight(filterPosition, lastFilterPosition);

                            // If option is selected we should enable first next filter to the right and populate it with data
                            if (filterOptionSelected) {

                                // Index of the next filter
                                nextFilterPosition = filterPosition + 1;

                                while ($('.wdt-cascade-filtering-container[data-wpdatatable_id=' + tableDescription.tableWpId + '] .filter_column[data-index=' + nextFilterPosition + ']').length === 0 && nextFilterPosition <= lastFilterPosition) {
                                    nextFilterPosition++;
                                }

                                populateNextFilterNonServerSide();
                            }
                        }
                        // Remember last changed filter position
                        lastChangedFilterPosition = $(this).closest('.filter_column').data('index');
                    });
                }
            }

            /**
             * Function that populates passed filter element with new data
             * @param $filter - HTML filter element with filter_column class
             * @param filterType - selectbox, multiselectbox, checkbox
             * @param data
             */
            function populateFilterWithData($filter, filterType, data) {
                if ($.inArray(filterType, ['selectbox', 'multiselectbox', 'checkbox']) !== -1) {
                    var columnIndex = parseInt($filter.data('index'));
                    var aoColumn = tableDescription.advancedFilterOptions.aoColumns[columnIndex];
                    var th = $filter.parent();
                    var sColumnLabel = tableDescription.advancedFilterOptions.aoColumns[columnIndex].displayHeader ?
                        tableDescription.advancedFilterOptions.aoColumns[columnIndex].displayHeader : tableDescription.advancedFilterOptions.aoColumns[columnIndex].origHeader;
                    aoColumn.values = data;

                    if (filterType === 'selectbox') {
                        wdtCreateSelectbox(oTable, aoColumn, columnIndex, sColumnLabel, th, tableDescription.serverSide);
                    } else if (filterType === 'multiselectbox') {
                        wdtCreateMultiSelectbox(oTable, aoColumn, columnIndex, sColumnLabel, th, tableDescription.serverSide);
                    } else {
                        wdtCreateCheckbox(oTable, aoColumn, columnIndex, sColumnLabel, th, tableDescription.serverSide);
                    }
                } else {
                    $filter.find('.wdt-filter-control').prop('disabled', false);
                }
            }

            /**
             * Function that returns filter params for server-side tables.
             * Filter params is an object where property is column original header and value is filter value of that column.
             * @returns {{}}
             */
            function getFilterParamsServerSide() {
                filterParams = {};

                for (var i in  oTable.api().ajax.params().columns) {

                    if (oTable.api().column(i).search() === '') {
                        continue;
                    }

                    filterParams[oTable.api().ajax.params().columns[i].name] =
                        oTable.api().column(i).search();
                }

                return filterParams;
            }

            /**
             * Function that disable selectbox, multiselectbox filters
             * and empty checkbox values if checkbox is not rendered in modal, if it is rendered in modal then
             * checkbox render button will be disabled
             */
            function disableAllFiltersExceptFirst() {
                var $filtersExceptFirst = $('.wdt-cascade-filtering-container[data-wpdatatable_id=' + tableDescription.tableWpId + '] .filter_column').not(':eq(0)');

                $.each($filtersExceptFirst, function () {

                    filterType = $(this).data('filter_type');

                    if (filterType === 'selectbox' || filterType === 'multiselectbox') {
                        $(this).find('select.wdt-filter-control').prop('disabled', true).selectpicker('refresh');
                    } else if (filterType === 'checkbox') {
                        if ($(this).find('.btn').length === 0) {
                            $(this).empty();
                            $(this).html('<p class="wdt-empty-checkbox-filter">' + wdtPfTranslationStrings.previousFilter + '</p>');
                        } else {
                            $(this).find('.btn').prop('disabled', true);
                        }
                    } else {
                        $(this).find('.wdt-filter-control').prop('disabled', true).val('');
                    }

                });
            }

            /**
             * Functions that go through the all filters right to the affected filter disable them
             * @param filterPosition
             * @param lastFilterPosition
             */
            function disableFiltersOnRight(filterPosition, lastFilterPosition) {
                for (var i = filterPosition + 1; i <= lastFilterPosition; i++) {
                    // Filter that should be disabled
                    $filter = $('.wdt-cascade-filtering-container[data-wpdatatable_id=' + tableDescription.tableWpId + '] .filter_column[data-index=' + i + ']');
                    // Filter type of the filter that should be disabled
                    filterType = $filter.data('filter_type');
                    // If filter exists reset it's value to blank and disable it
                    if ($filter.length) {
                        if (filterType === 'selectbox' || filterType === 'multiselectbox') {
                            $filter.find('select').selectpicker('val', '').prop('disabled', true).selectpicker('refresh');
                        } else if (filterType === 'checkbox') {
                            if ($filter.find('.btn').length === 0) {
                                $filter.empty();
                                $filter.html('<p class="wdt-empty-checkbox-filter">' + wdtPfTranslationStrings.previousFilter + '</p>');
                            } else {
                                $filter.find('.btn').prop('disabled', true);
                            }
                        } else {
                            $filter.find('.wdt-filter-control').prop('disabled', true).val('');
                        }
                        // Clear filter with datatables API
                        oTable.api().columns(i).search('');
                    }
                }
                // Redraw wpDataTable with datatables API
                if (!tableDescription.showSearchFiltersButton) {
                    oTable.api().draw();
                }
            }

            /**
             * Populate and enable next filter to the right for server-side tables
             */
            function populateNextFilterServerSide() {
                if ($('.wdt-cascade-filtering-container[data-wpdatatable_id=' + tableDescription.tableWpId + '] .filter_column[data-index=' + nextFilterPosition + ']').length) {
                    // Filter that should be enabled and updated with the values
                    $filter = $('.wdt-cascade-filtering-container[data-wpdatatable_id=' + tableDescription.tableWpId + '] .filter_column[data-index=' + nextFilterPosition + ']');
                    // Column Index of the filter that should be enabled and updated with the values
                    columnIndex = parseInt($filter.data('index'));
                    // Filter type of the filter that should be enabled and updated with the values
                    filterType = $filter.data('filter_type');
                    // Get the filter params
                    filterParams = getFilterParamsServerSide();
                    // Data index of the current filter
                    currentColumnIndex = $('.wdt-cascade-filtering-container[data-wpdatatable_id=' + tableDescription.tableWpId + '] .filter_column').data('index');
                    // Data of the column which filter is currently changed
                    currentColumn = tableDescription.advancedFilterOptions.aoColumns[currentColumnIndex];

                    $.ajax({
                        url: ajaxurl + '?' + tableDescription.dataTableParams.ajax.url.split('action=get_wdtable&').pop(),
                        method: 'POST',
                        dataType: 'json',
                        data: {
                            action: 'wdt_get_cascade_columns_distinct_values',
                            tableId: tableDescription.tableWpId,
                            filterParams: filterParams,
                            columnIndex: columnIndex,
                            currentColumnExactFilter: currentColumn.exactFiltering
                        },
                        success: function (columnDistinctValues) {
                            // Populate filter with the data
                            populateFilterWithData($filter, filterType, columnDistinctValues[columnIndex]);
                            // Remove disabled property for the filter
                            if (filterType === 'selectbox') {
                                $filter.find('select').prop('disabled', false).selectpicker('refresh');
                            }
                        }
                    });
                }
            }

            /**
             * Populate and enable next filter to the right for non-server-side tables
             */
            function populateNextFilterNonServerSide() {
                if ($('.wdt-cascade-filtering-container[data-wpdatatable_id=' + tableDescription.tableWpId + '] .filter_column[data-index=' + nextFilterPosition + ']').length) {
                    // Filter that should be enabled and updated with the values
                    $filter = $('.wdt-cascade-filtering-container[data-wpdatatable_id=' + tableDescription.tableWpId + '] .filter_column[data-index=' + nextFilterPosition + ']');
                    // Column Index of the filter that should be enabled and updated with the values
                    columnIndex = parseInt($filter.data('index'));
                    // Filter type of the filter that should be enabled and updated with the values
                    filterType = $filter.data('filter_type');
                    // Get the column distinct data
                    columnDistinctValues = getColumnDistinctValues(tableDescription.tableId, columnIndex, true);
                    // Populate filter with the data
                    populateFilterWithData($filter, filterType, columnDistinctValues);
                }
            }

            /**
             * Add selectors and data to modal for "Cascade filtering" if checkbox filter is rendered in modal
             * @param $modal
             * @param iColumnIndex
             */
            oTable.onRenderCheckboxFilterModal.push(function ($modal, iColumnIndex) {
                var $checkboxContainer = $modal.find('.modal-body >:first-child');
                $checkboxContainer.addClass('wdt-cascade-filtering-container');
                $checkboxContainer.data('wpdatatable_id', tableDescription.tableWpId);
                $checkboxContainer.attr('data-wpdatatable_id', tableDescription.tableWpId);
                $checkboxContainer.data('index', iColumnIndex);
                $checkboxContainer.attr('data-index', iColumnIndex);
            });

        }

    })(jQuery)
});

/**
 * Search filters on button
 */
wpDataTablesHooks.onRenderFilter.push(function searchFiltersOnButton(tableDescription) {
    (function ($) {

        if (tableDescription.showSearchFiltersButton) {

            var oTable = wpDataTables[tableDescription.tableId], allFiltersSelected = false,
                isAdminPage = wdtPfDashboard;
            var searchFiltersButtonContainer = '.wdt-search-filters-button-container[data-wpdatatable_id=' + tableDescription.tableWpId + ']';
            oTable.onRenderCheckboxFilterModal = oTable.onRenderCheckboxFilterModal || [];
            oTable.drawTable = false;
            oTable.gravityEditable = true;

            $(searchFiltersButtonContainer + ' .selectpicker').on('change', function () {
                var $filter = $(this).closest('.filter_column');
                var columnIndex = $filter.data('index');
                jQuery('.selectpicker[data-index=' + columnIndex + ']').selectpicker('refresh');
            });

            // Bind event on search button click
            $(searchFiltersButtonContainer + ' .wdt-pf-search-filters-button').on('click', function () {
                oTable.api().draw();
            });

            if (isAdminPage) {
                jQuery(document).off('change.searchFiltersOnButton keyup.searchFiltersOnButton dp.change.searchFiltersOnButton');
            }

            if (tableDescription.disableSearchFiltersButton) {
                jQuery(document).on('change.searchFiltersOnButton keyup.searchFiltersOnButton dp.change.searchFiltersOnButton', searchFiltersButtonContainer + ' .wdt-filter-control:not("select")', function () {
                    // Check if all filters are selected
                    $(searchFiltersButtonContainer + ' .filter_column').each(function () {
                        allFiltersSelected = isFilterOptionSelected($(this));
                        return allFiltersSelected;
                    });

                    // If all filters are selected remove disabled attribute from search button
                    if (allFiltersSelected) {
                        $(searchFiltersButtonContainer + ' .wdt-pf-search-filters-button').attr('disabled', false);
                    } else {
                        $(searchFiltersButtonContainer + ' .wdt-pf-search-filters-button').attr('disabled', true);
                        if (tableDescription.hideTableBeforeFiltering) {
                            $('#' + tableDescription.tableId).closest('.wpDataTablesWrapper').hide();
                            oTable.api().page.len(0).draw();
                        }
                    }

                });
            }

            if (!tableDescription.disableSearchFiltersButton && tableDescription.hideTableBeforeFiltering) {
                jQuery(document).on('change.searchFiltersOnButton keyup.searchFiltersOnButton dp.change.searchFiltersOnButton', searchFiltersButtonContainer + ' .wdt-filter-control:not("select")', function () {
                    // Check if all filters are cleared
                    var optionSelected = false;
                    $(searchFiltersButtonContainer + ' .filter_column').each(function () {
                        optionSelected = isFilterOptionSelected($(this));
                        return !optionSelected;
                    });

                    // If all filters are cleared hide the table
                    if (!optionSelected) {
                        oTable.api().page.len(0).draw();
                        $('#' + tableDescription.tableId).closest('.wpDataTablesWrapper').hide();
                    }
                });
            }

            /**
             * Add selectors and data to modal for "Show search filters button" if checkbox filter is rendered in modal
             * @param $modal
             * @param iColumnIndex
             */
            oTable.onRenderCheckboxFilterModal.push(function ($modal, iColumnIndex) {
                var $checkboxContainer = $modal.find('.modal-body >:first-child');
                $checkboxContainer.addClass('wdt-search-filters-button-container');
                $checkboxContainer.data('wpdatatable_id', tableDescription.tableWpId);
                $checkboxContainer.attr('data-wpdatatable_id', tableDescription.tableWpId);
                $checkboxContainer.data('index', iColumnIndex);
                $checkboxContainer.attr('data-index', iColumnIndex);
            });

        }

    })(jQuery);
});

/**
 * Returns true if one of the options is selected in the filter, otherwise returns false
 * @param $filter
 */
function isFilterOptionSelected($filter) {
    var filterType = $filter.data('filter_type');

    switch (filterType) {
        case 'selectbox':
            return $filter.find('select.wdt-select-filter').selectpicker('val') !== '';
        case 'multiselectbox':
            return $filter.find('select.wdt-multiselect-filter').selectpicker('val') !== null;
        case 'checkbox':
            // If it is rendered in modal
            if (typeof $filter.children('div').attr('class') !== 'undefined' && jQuery('.modal-body #' + $filter.children('div').attr('class').split(' ')[1]).length) {
                return jQuery('.modal-body #' + $filter.children('div').attr('class').split(' ')[1]).find('input:checkbox:checked').length > 0;
            }
            return $filter.find('input:checkbox:checked').length > 0;
        case 'number range':
        case 'date range':
        case 'datetime range':
        case 'time range':
            var rangeFilterSelected = false;
            $filter.find('.wdt-filter-control').each(function () {
                rangeFilterSelected = !jQuery(this).val();
                return rangeFilterSelected;
            });
            return !rangeFilterSelected;
        case 'text':
        case 'number':
        default:
            return $filter.find('.wdt-filter-control').val() !== '';
    }
}