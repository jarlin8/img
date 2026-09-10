(function(window) {
    function SmartManagerPro() {
        SmartManager.call(this); // Call parent constructor
    }
    SmartManagerPro.prototype = Object.create(SmartManagerPro.prototype);
    SmartManager.prototype.constructor = SmartManagerPro;
    //Function to determine if background process is running or not
    SmartManager.prototype.isBackgroundProcessRunning = function () {
        if (jQuery('#sa_background_process_progress').length > 0 && jQuery('#sa_background_process_progress').is(":visible")) {
            return true;
        }

        return false;
    }

    SmartManager.prototype.showProgressDialog = function (title = '') {
        SaCommonManager.prototype.showProgressDialog.call(this, title);
    }
    //function to clear the datepicker filter
    SmartManager.prototype.clearDateFilter = function () {
        let startDate = jQuery('#sm_date_selector_start_date').val(),
            endDate = jQuery('#sm_date_selector_end_date').val(),
            refresh = 0;

        if (startDate != '') {
            let startDateDatepicker = jQuery('.sm_date_range_container input.start-date').data('Zebra_DatePicker');
            jQuery('#sm_date_selector_start_date').val('');
            refresh = 1;
        }
        if (endDate != '') {
            let endDateDatepicker = jQuery('.sm_date_range_container input.end-date').data('Zebra_DatePicker');
            jQuery('#sm_date_selector_end_date').val('');
            refresh = 1;
        }

        if (typeof (window.smart_manager.currentGetDataParams.date_filter_params) != 'undefined' && typeof (window.smart_manager.currentGetDataParams.date_filter_query) != 'undefined') {
            delete window.smart_manager.currentGetDataParams.date_filter_params;
            delete window.smart_manager.currentGetDataParams.date_filter_query;
        }

        if (window.smart_manager.date_params.hasOwnProperty('date_filter_params')) {
            delete window.smart_manager.date_params['date_filter_params'];
        }

        if (window.smart_manager.date_params.hasOwnProperty('date_filter_query')) {
            delete window.smart_manager.date_params['date_filter_query'];
        }

        if (refresh == 1) {
            window.smart_manager.refresh();
        }
    }

    //function to process the datepicker filter
    SmartManager.prototype.sm_handle_date_filter = function (params) {

        let date_search_array = new Array(),
            dataParams = {};

        if (window.smart_manager.dashboardKey == 'user') {
            date_search_array = new Array({ "key": "User Registered", "value": params.start_date_default_format, "type": "date", "operator": ">=", "table_name": window.smart_manager.wpDbPrefix + "users", "col_name": "user_registered", "date_filter": 1 },
                { "key": "User Registered", "value": params.end_date_default_format, "type": "date", "operator": "<=", "table_name": window.smart_manager.wpDbPrefix + "users", "col_name": "user_registered", "date_filter": 1 });
        } else {
            date_search_array = new Array({ "key": "Post Date", "value": params.start_date_default_format, "type": "date", "operator": ">=", "table_name": window.smart_manager.wpDbPrefix + "posts", "col_name": "post_date", "date_filter": 1 },
                { "key": "Post Date", "value": params.end_date_default_format, "type": "date", "operator": "<=", "table_name": window.smart_manager.wpDbPrefix + "posts", "col_name": "post_date", "date_filter": 1 });
        }

        window.smart_manager.date_params['date_filter_params'] = JSON.stringify(params);
        window.smart_manager.date_params['date_filter_query'] = JSON.stringify(date_search_array);


        if (Object.getOwnPropertyNames(window.smart_manager.date_params).length > 0) {
            dataParams.data = window.smart_manager.date_params;
        }

        window.smart_manager.refresh(dataParams);
    }

    //function to append 0's to str
    SmartManager.prototype.strPad = function (str, len) {

        str += '';
        while (str.length < len) str = '0' + str;
        return str;

    },

    SmartManager.prototype.proSelectDate = function (dateValue) {

            if (dateValue == 'all') {
                if (typeof (window.smart_manager.clearDateFilter) !== "undefined" && typeof (window.smart_manager.clearDateFilter) === "function") {
                    window.smart_manager.clearDateFilter();
                }
                return;
            }

            let fromDate,
                toDate,
                from_time,
                to_time,
                from_date_formatted,
                from_date_default_format,
                to_date_formatted,
                to_date_default_format,
                now = new Date(),
                params = {
                    'start_date_formatted': '',
                    'start_date_default_format': '',
                    'end_date_formatted': '',
                    'end_date_default_format': ''
                };

            switch (dateValue) {

                case 'today':
                    fromDate = now;
                    toDate = now;
                    break;

                case 'yesterday':
                    fromDate = new Date(now.getFullYear(), now.getMonth(), now.getDate() - 1);
                    toDate = new Date(now.getFullYear(), now.getMonth(), now.getDate() - 1);
                    break;

                case 'this_week':
                    fromDate = new Date(now.getFullYear(), now.getMonth(), now.getDate() - (now.getDay() - 1));
                    toDate = now;
                    break;

                case 'last_week':
                    fromDate = new Date(now.getFullYear(), now.getMonth(), (now.getDate() - (now.getDay() - 1) - 7));
                    toDate = new Date(now.getFullYear(), now.getMonth(), (now.getDate() - (now.getDay() - 1) - 1));
                    break;

                case 'last_4_week':
                    fromDate = new Date(now.getFullYear(), now.getMonth(), (now.getDate() - 29)); //for exactly 30 days limit
                    toDate = now;
                    break;

                case 'this_month':
                    fromDate = new Date(now.getFullYear(), now.getMonth(), 1);
                    toDate = now;
                    break;

                case 'last_month':
                    fromDate = new Date(now.getFullYear(), now.getMonth() - 1, 1);
                    toDate = new Date(now.getFullYear(), now.getMonth(), 0);
                    break;

                case '3_months':
                    fromDate = new Date(now.getFullYear(), now.getMonth() - 2, 1);
                    toDate = now;
                    break;

                case '6_months':
                    fromDate = new Date(now.getFullYear(), now.getMonth() - 5, 1);
                    toDate = now;
                    break;

                case 'this_year':
                    fromDate = new Date(now.getFullYear(), 0, 1);
                    toDate = now;
                    break;

                case 'last_year':
                    fromDate = new Date(now.getFullYear() - 1, 0, 1);
                    toDate = new Date(now.getFullYear(), 0, 0);
                    break;

                default:
                    fromDate = new Date(now.getFullYear(), now.getMonth(), 1);
                    toDate = now;
                    break;
            }

            //Code for format
            if (typeof fromDate === 'object' && fromDate instanceof Date) {
                var y = fromDate.getFullYear() + '',
                    m = fromDate.getMonth(),
                    d = window.smart_manager.strPad(fromDate.getDate(), 2);

                from_time = '00:00:00';
                params.start_date_formatted = d + ' ' + window.smart_manager.month_names_short[m] + ' ' + y + ' ' + from_time;
                params.start_date_default_format = y + '-' + window.smart_manager.strPad((m + 1), 2) + '-' + d + ' ' + from_time;
            }

            if (typeof toDate === 'object' && toDate instanceof Date) {
                var y = toDate.getFullYear() + '',
                    m = toDate.getMonth(),
                    d = window.smart_manager.strPad(toDate.getDate(), 2);

                to_time = '23:59:59';
                params.end_date_formatted = d + ' ' + window.smart_manager.month_names_short[m] + ' ' + y + ' ' + to_time;
                params.end_date_default_format = y + '-' + window.smart_manager.strPad((m + 1), 2) + '-' + d + ' ' + to_time;
            }

            var start_date = jQuery('.sm_date_range_container input.start-date').data('Zebra_DatePicker'),
                end_date = jQuery('.sm_date_range_container input.end-date').data('Zebra_DatePicker');

            if (typeof (start_date) != 'undefined') {
                start_date.set_date(params.start_date_formatted);
                start_date.update({ 'current_date': new Date(params.start_date_default_format), 'start_date': new Date(params.start_date_formatted) });
            }

            if (typeof (end_date) != 'undefined') {
                end_date.set_date(params.end_date_formatted);
                end_date.update({ 'current_date': new Date(params.end_date_default_format), 'start_date': new Date(params.end_date_formatted) });
            }

            window.smart_manager.sm_handle_date_filter(params);

        };

    var sm_beta_hide_dialog = function (IDs, gID) {
        jQuery.jgrid.hideModal("#" + IDs.themodal, { gb: "#gbox_" + gID, jqm: true, onClose: null });
        index = 0;
    }

    // ========================================================================
    // PRINT INVOICE
    // ========================================================================

    SmartManager.prototype.printInvoice = function () {

        if (window.smart_manager.duplicateStore === false && window.smart_manager.selectedRows.length == 0 && !window.smart_manager.selectAll) {
            return;
        }

        let params = {};
        params.data = {
            cmd: 'get_print_invoice',
            active_module: window.smart_manager.dashboardKey,
            security: window.smart_manager.saCommonNonce,
            pro: true,
            sort_params: (window.smart_manager.currentDashboardModel.hasOwnProperty('sort_params')) ? window.smart_manager.currentDashboardModel.sort_params : '',
            table_model: (window.smart_manager.currentDashboardModel.hasOwnProperty('tables')) ? window.smart_manager.currentDashboardModel.tables : '',
            SM_IS_WOO30: window.smart_manager.sm_is_woo30,
            SM_IS_WOO22: window.smart_manager.sm_id_woo22,
            SM_IS_WOO21: window.smart_manager.sm_is_woo21,
            search_text: (window.smart_manager.searchType == 'simple') ? window.smart_manager.simpleSearchText : '',
            advanced_search_query: JSON.stringify((window.smart_manager.searchType != 'simple') ? window.smart_manager.advancedSearchQuery : []),
            storewide_option: (true === window.smart_manager.selectAll) ? 'entire_store' : '',
            selected_ids: (window.smart_manager.getSelectedKeyIds()) ? JSON.stringify(window.smart_manager.getSelectedKeyIds()) : ''
        };

        let url = window.smart_manager.commonManagerAjaxUrl + '&cmd=' + params.data['cmd'] + '&active_module=' + params.data['active_module'] + '&security=' + params.data['security'] + '&pro=' + params.data['pro'] + '&SM_IS_WOO30=' + params.data['SM_IS_WOO30'] + '&SM_IS_WOO30=' + params.data['SM_IS_WOO30'] + '&sort_params=' + encodeURIComponent(JSON.stringify(params.data['sort_params'])) + '&table_model=' + encodeURIComponent(JSON.stringify(params.data['table_model'])) + '&advanced_search_query=' + params.data['advanced_search_query'] + '&search_text=' + params.data['search_text'] + '&storewide_option=' + params.data['storewide_option'] + '&selected_ids=' + params.data['selected_ids'];

        url += (window.smart_manager.isFilteredData()) ? '&filteredResults=1' : '';
        params.call_url = url;
        params.data_type = 'html';

        url += (window.smart_manager.date_params.hasOwnProperty('date_filter_params')) ? '&date_filter_params=' + window.smart_manager.date_params['date_filter_params'] : '';
        url += (window.smart_manager.date_params.hasOwnProperty('date_filter_query')) ? '&date_filter_query=' + window.smart_manager.date_params['date_filter_query'] : '';

        window.smart_manager.sendRequest(params, function (response) {
            let win = window.open('', 'Invoice');
            win.document.write(response);
            win.document.close();
            win.print();
        });
    }

    // ========================================================================
    // DUPLICATE RECORDS
    // ========================================================================

    SmartManager.prototype.duplicateRecords = function () {
        if (!window.smart_manager.duplicateStore && window.smart_manager.selectedRows.length == 0 && !window.smart_manager.selectAll) {
            return;
        }
        window.smart_manager.hideFloatingActionBar();
        window.smart_manager.selectAll = (window.smart_manager.duplicateStore || window.smart_manager.selectAll) ? true : false;
        setTimeout(function () {

            window.smart_manager.showProgressDialog(_x('Duplicate Records', 'progressbar modal title', 'smart-manager-for-wp-e-commerce'));

            if (typeof (sa_background_process_heartbeat) !== "undefined" && typeof (sa_background_process_heartbeat) === "function") {
                sa_background_process_heartbeat(1000, 'duplicate', 'smart_manager');
            }

        }, 1);

        let params = {};
        params.data = {
            cmd: 'duplicate_records',
            active_module: window.smart_manager.dashboardKey,
            security: window.smart_manager.saCommonNonce,
            pro: true,
            storewide_option: (window.smart_manager.selectAll) ? 'entire_store' : '',
            selected_ids: JSON.stringify(window.smart_manager.getSelectedKeyIds()),
            table_model: (window.smart_manager.currentDashboardModel.hasOwnProperty('tables')) ? window.smart_manager.currentDashboardModel.tables : '',
            active_module_title: window.smart_manager.dashboardName,
            backgroundProcessRunningMessage: window.smart_manager.backgroundProcessRunningMessage,
            SM_IS_WOO30: window.smart_manager.sm_is_woo30,
            SM_IS_WOO22: window.smart_manager.sm_id_woo22,
            SM_IS_WOO21: window.smart_manager.sm_is_woo21
        };

        params.showLoader = false;

        if (window.smart_manager.isFilteredData()) {
            params.data.filteredResults = 1;
        }

        window.smart_manager.sendRequest(params, function (response) {

        });

        // setTimeout(function() {
        //     params = { 'func_nm' : 'duplicate_records', 'title' : 'Duplicate Records' }
        //     window.smart_manager.background_process_hearbeat( params );
        // }, 1000);

    };

    // ========================================================================
    // Function to handle request for both creating & updating view
    // ========================================================================
    SmartManager.prototype.saveView = function (params = {}) {
        if ((!params.hasOwnProperty('name') || params.name.trim() === '') || (!params.hasOwnProperty('action') || params.action.trim() === '')) {
            return;
        }
        try {
            let type = params.type || 'custom_views',
                action = params.action,
                viewSlug = window.smart_manager.getViewSlug(window.smart_manager.dashboardName),
                activeDashboard = (viewSlug) ? viewSlug : window.smart_manager.dashboardKey,
                currentDashboardState = '';
            if (type === 'custom_views' && typeof window.smart_manager.getCurrentDashboardState === "function") {
                currentDashboardState = window.smart_manager.getCurrentDashboardState();
                if (currentDashboardState) {
                    currentDashboardState = JSON.parse(currentDashboardState);
                    currentDashboardState['search_params'] = {
                        'isAdvanceSearch': (window.smart_manager.advancedSearchQuery.length > 0) ? 'true' : 'false',
                        'params': window.smart_manager.advancedSearchQuery.length > 0 ? window.smart_manager.advancedSearchQuery : window.smart_manager.simpleSearchText
                    };
                }
            }

            let requestParams = {
                data_type: 'json',
                data: {
                    module: 'custom_views',
                    cmd: action,
                    active_module: activeDashboard,
                    security: window.smart_manager.saCommonNonce,
                    name: params.name
                }
            };

            if (type === 'custom_views') {
                requestParams.data.isPublic = jQuery('#sm_view_access_public').is(":checked");
                requestParams.data.isSaveDashboardAndCols = jQuery('#sm_save_dashboard_and_cols').is(":checked");
                requestParams.data.isSaveAdvancedSearch = jQuery('#sm_save_advanced_search').is(":checked");
                requestParams.data.is_view = viewSlug ? 1 : 0;
                requestParams.data.currentView = window.smart_manager.prepareMultilistConfigForSave(JSON.stringify(currentDashboardState));
            } else if (type === 'saved_bulk_edits' && params.hasOwnProperty('bulk_edit_params')) {
                requestParams.data.bulk_edit_params = JSON.stringify(params.bulk_edit_params);
            }

            window.smart_manager.sendRequest(requestParams, function (response) {
                let ack = response.ACK || '';
                if (ack === 'Success') {
                    if (params.hasOwnProperty('onSuccess') && typeof params.onSuccess === 'function') {
                        params.onSuccess(response);
                    }
                    if (type === 'custom_views') {
                        window.smart_manager.notification = {
                            status: 'success', message: sprintf(
                                /* translators: %s: success notification message */
                                _x(`${((requestParams.data.isSaveDashboardAndCols && requestParams.data.isSaveAdvancedSearch) || (requestParams.data.isSaveDashboardAndCols && !requestParams.data.isSaveAdvancedSearch)) ? 'View' : 'Saved Search'} %sd successfully!`, 'notification', 'smart-manager-for-wp-e-commerce'), String(action).capitalize())
                        }
                        if (response?.slug && response.slug !== '') {
                            setTimeout(() => {
                                window.location.href = `${window.smart_manager.smAppAdminURL || window.location.href}${window.location.href.includes("?") ? "&" : "?"}dashboard=${response.slug}&is_view=1`;
                                window.smart_manager.showNotification();
                            }, 500);
                        }
                    }
                } else if (ack === 'Failed' && response.msg) {
                    if (params.hasOwnProperty('onError') && typeof params.onError === 'function') {
                        params.onError(response);
                    }
                    if (type === 'custom_views') {
                        jQuery('#sm_view_error_msg').text(response.msg).show();
                        jQuery('#sm_view_name').addClass('sm_border_red');
                    }
                }
            });
        } catch (e) {
            SaErrorHandler.log('In saveView:: ', e);
        }
    };

    // ========================================================================
    // function to display confirmdialog for create & update view
    // ========================================================================

    SmartManager.prototype.createUpdateViewDialog = function (action = 'create', params = {}) {
        let viewSlug = window.smart_manager.getViewSlug(window.smart_manager.dashboardName);

        let isView = (viewSlug) ? 1 : 0,
            isPublicView = (window.smart_manager.publicViews.includes(viewSlug)) ? 1 : 0;
        isSaveDashboardAndCols = ((!params.hasOwnProperty('dashboardChecked')) || (!params.dashboardChecked === true)) ? 0 : 1;
        isSaveAdvancedSearch = ((!params.hasOwnProperty('advancedSearchChecked')) || (!params.advancedSearchChecked === true)) ? 0 : 1;

        params.btnParams = {}
        params.title = _x('Custom Views', 'modal title', 'smart-manager-for-wp-e-commerce');
        params.modalClass = 'max-w-xl';
        params.content = `<span id="sm_view_descrip" class="font-normal text-sm leading-5 text-[#525252] m-0">${_x('Create a custom view to save selected columns from a dashboard. Use it for saved searches, giving specific columns access to other users, etc.', 'modal content', 'smart-manager-for-wp-e-commerce')}</span>
        <div class="text-sm-base-foreground font-medium text-sm leading-5 text-[#0a0a0a] mt-5 mb-2">${_x("Name to this view", "placeholder", "smart-manager-for-wp-e-commerce")}</div>
        <input id="sm_view_name" class="w-full h-9" type="text" placeholder="${_x("For eg: Stock less than 15", "placeholder", "smart-manager-for-wp-e-commerce")}" value="${(isView == 1 && action != 'create') ? window.smart_manager.dashboardName : ''}" />
        <div id="sm_view_error_msg" style="display:none;"></div>
        <div id="sm_view_access" class="mt-5 text-sm-base-foreground">
            <label id="sm_view_access_public_lbl">
                <input type="checkbox" class="mr-1" id="sm_view_access_public" style="height: 1.5em;width: 1.5em;" ${(isPublicView === 1) ? 'checked' : ''} >
                ${_x('Set to Public', 'checkbox for custom view access', 'smart-manager-for-wp-e-commerce')}
            </label>
            <div class="font-normal text-sm leading-4 text-[#737373] my-3 ml-[2.625rem]">${_x('Marking this view public will make it available to all users with access to Smart Manager.', 'description', 'smart-manager-for-wp-e-commerce')}</div>
        </div>
        <div class="mt-6 text-sm-base-foreground">
            <div class="mt-4 sm_view_save_option">
                <label for="sm_save_dashboard_and_cols">
                    <input type="checkbox" class="mr-1" id="sm_save_dashboard_and_cols" style="height: 1.5em;width: 1.5em;" ${(isSaveDashboardAndCols === 1) ? 'checked' : ''} >
                    <span>
                        ${_x('Save dashboard along with save columns', 'checkbox to save dashboard along with save columns', 'smart-manager-for-wp-e-commerce')}
                    </span>
                </label>
            </div>
            <div class="mt-4 sm_view_save_option">
                <label for="sm_save_advanced_search">
                    <input type="checkbox" class="mr-1" id="sm_save_advanced_search" style="height: 1.5em;width: 1.5em;" ${(isSaveAdvancedSearch === 1) ? 'checked' : ''} >
                    <span>
                        ${_x('Save Advanced Search conditions', 'checkbox to save advanced search conditions', 'smart-manager-for-wp-e-commerce')}
                    </span>
                </label>
            </div>
            <div id="sm_view_save_options_error_msg"></div>
        </div>`;

        if (typeof (window.smart_manager.createUpdateView) !== "undefined" && typeof (window.smart_manager.createUpdateView) === "function") {
            params.btnParams.yesText = String(action).capitalize();
            params.btnParams.yesCallback = window.smart_manager.createUpdateView;
            params.btnParams.yesCallbackParams = action;
            params.btnParams.hideOnYes = false
        }

        window.smart_manager.showConfirmDialog(params);
    }

    // ========================================================================
    // function to handle functionality for checking iof view exists & if not then creating or updating the same
    // ========================================================================

    SmartManager.prototype.createUpdateView = function (action = 'create') {
        let name = jQuery('#sm_view_name').val()
        // Code to validate name field
        if (!name) {
            jQuery('#sm_view_error_msg').html('Please add view name').show();
            jQuery('#sm_view_name').addClass('sm_border_red')
        } else if ((!jQuery('#sm_save_dashboard_and_cols').is(":checked")) && (!jQuery('#sm_save_advanced_search').is(":checked"))) {
            jQuery('#sm_view_save_options_error_msg').html(_x('You must select at least one option before saving', 'notifiacation', 'smart-manager-for-wp-e-commerce')).show();
            jQuery('#sm_save_dashboard_and_cols, #sm_save_advanced_search').addClass('sm_border_red');
        } else {
            jQuery('#sm_view_name').removeClass('sm_border_red');
            jQuery('#sm_view_error_msg').html('').hide();
            jQuery('#sm_save_dashboard_and_cols, #sm_save_advanced_search').removeClass('sm_border_red');
            jQuery('#sm_view_save_options_error_msg').html('').hide();
            if (typeof (window.smart_manager.saveView) !== "undefined" && typeof (window.smart_manager.saveView) === "function") {
                window.smart_manager.saveView({ action, name, type: 'custom_views' });
            }
        }
    };

    // ========================================================================
    // DELETE VIEW
    // ========================================================================
    SmartManager.prototype.deleteView = function (params = {}, bulkEditDialogObj = {}) {
        let viewSlug = params.view_slug || window.smart_manager.getViewSlug(window.smart_manager.dashboardName),
            ajaxParams = {
                data_type: 'json',
                data: {
                    module: 'custom_views',
                    cmd: 'delete',
                    security: window.smart_manager.saCommonNonce || '',
                    active_module: viewSlug
                }
            };

        window.smart_manager.sendRequest(ajaxParams, function (response) {
            if (response?.ACK !== 'Success') return;

            window.smart_manager.notification = {
                status: 'success',
                message: params?.success_msg
            };
            let type = params?.type || 'custom_views';
            switch (type) {
                case 'custom_views':
                    window.smart_manager.showNotification()
                    location.reload();
                    break;

                case 'saved_bulk_edits':
                    bulkEditDialogObj.BulkEditSavedActions = window.smart_manager.saved_bulk_edits = Array.isArray(window.smart_manager.saved_bulk_edits) ? window.smart_manager.saved_bulk_edits.filter(item => item?.slug !== viewSlug) : [];

                    bulkEditDialogObj.selectedSavedBulkEdit = window.smart_manager.selectedSavedBulkEdit = document.querySelector('.saved-bulk-edit-item.selected')?.getAttribute('slug') || '';
                    // Reset conditions if the deleted item was selected
                    if (params.event?.target?.closest(".saved-bulk-edit-item")?.classList?.contains("selected")) {
                        window.smart_manager.savedBulkEditConditions = [];
                        if ("undefined" !== typeof (bulkEditDialogObj.initialize) && "function" === typeof (bulkEditDialogObj.initialize)) {
                            bulkEditDialogObj.initialize();
                        }
                    }
                    //Re-render the bulk edit pannel dialog.
                    window.smart_manager.showPannelDialog('bulkEdit', m.route.get())
                    break;
            }
        });
    };

    SmartManager.prototype.resetBatchUpdate = function () {

    }

    SmartManager.prototype.displayDefaultBatchUpdateValueHandler = function (row_id) {

        if (row_id == '') {
            return;
        }

        let selected_field = jQuery("#" + row_id + " .batch_update_field option:selected").val(),
            type = window.smart_manager.columnNamesBatchUpdate[selected_field].type,
            editor = window.smart_manager.columnNamesBatchUpdate[selected_field].editor,
            multiSelectSeparator = window.smart_manager.columnNamesBatchUpdate[selected_field].multiSelectSeparator,
            col_val = window.smart_manager.columnNamesBatchUpdate[selected_field].values,
            allowMultiSelect = window.smart_manager.columnNamesBatchUpdate[selected_field].allowMultiSelect,
            skip_default_action = false;

        if (type == 'checkbox') {

            let checkedVal = '',
                uncheckedVal = '',
                checkedDisplayVal = '',
                uncheckedDisplayVal = '';

            if (type == 'checkbox') {
                checkedVal = window.smart_manager.columnNamesBatchUpdate[selected_field].checkedTemplate;
                uncheckedVal = window.smart_manager.columnNamesBatchUpdate[selected_field].uncheckedTemplate;

                checkedDisplayVal = checkedVal.substr(0, 1).toUpperCase() + checkedVal.substr(1, checkedVal.length);
                uncheckedDisplayVal = uncheckedVal.substr(0, 1).toUpperCase() + uncheckedVal.substr(1, uncheckedVal.length);
            }

            jQuery("#" + row_id + " #batch_update_value_td").empty().append('<select class="batch_update_value" style="min-width:130px !important;">' +
                '<option value="' + checkedVal + '"> ' + checkedDisplayVal + ' </option>' +
                '<option value="' + uncheckedVal + '"> ' + uncheckedDisplayVal + ' </option>' +
                '</select>')
            jQuery("#" + row_id + " #batch_update_value_td").find(".batch_update_value").select2({ width: '15em', dropdownCssClass: 'sm_beta_batch_update_field', dropdownParent: jQuery('[aria-describedby="sm_inline_dialog"]') });

        } else if (col_val != '' && type == 'dropdown') {

            var batch_update_value_options = '<select class="batch_update_value" style="min-width:130px !important;">',
                value_options_empty = true;

            for (var key in col_val) {
                if (typeof (col_val[key]) != 'object' && typeof (col_val[key]) != 'Array') {
                    value_options_empty = false;
                    batch_update_value_options += '<option value="' + key + '">' + col_val[key] + '</option>';
                }
            }

            batch_update_value_options += '</select>';

            if (value_options_empty === false) {
                jQuery("#" + row_id + " #batch_update_value_td").empty().append(batch_update_value_options);

                let args = { width: '15em', dropdownCssClass: 'sm_beta_batch_update_field', dropdownParent: jQuery('[aria-describedby="sm_inline_dialog"]') };

                if (editor == 'select2' && allowMultiSelect) {
                    args['multiple'] = true;
                }

                jQuery("#" + row_id + " #batch_update_value_td").find('.batch_update_value').select2(args);
            }

        } else if (col_val != '' && type == 'sm.multilist') {

            let options = {},
                index = 0;

            Object.entries(col_val).forEach(([key, value]) => {
                index = key;

                if (value.hasOwnProperty('parent')) {
                    if (value.parent > 0) {
                        index = value.parent + '_childs';
                        value.term = ' – ' + value.term;
                    }
                }

                if (options.hasOwnProperty(index)) {
                    options[index] += '<option value="' + key + '"> ' + value.term + ' </option>';
                } else {
                    options[index] = '<option value="' + key + '"> ' + value.term + ' </option>';
                }


            });

            let batch_update_value_options = '<select class="batch_update_value" style="min-width:130px !important;">' + Object.values(options).join() + '</select>';

            jQuery("#" + row_id + " #batch_update_value_td").empty().append(batch_update_value_options)
            jQuery("#" + row_id + " #batch_update_value_td").find('.batch_update_value').select2({ width: '15em', dropdownCssClass: 'sm_beta_batch_update_field', dropdownParent: jQuery('[aria-describedby="sm_inline_dialog"]') });

        } else if (type == 'sm.longstring') {
            jQuery("#" + row_id + " #batch_update_value_td").empty().append('<textarea class="batch_update_value" placeholder="' + _x('Enter a value...', 'placeholder', 'smart-manager-for-wp-e-commerce') + '" class="FormElement ui-widget-content"></textarea>');
        } else if (type == 'sm.image') {
            jQuery("#" + row_id + " #batch_update_value_td").empty().append('<div class="batch_update_image" style="width:15em;"><span style="color:#0073aa;cursor:pointer;font-size: 2.25em;line-height: 1;" class="dashicons dashicons-camera"></span></div>');
        } else if (type == 'numeric') {
            jQuery("#" + row_id + " #batch_update_value_td").empty().append('<input type="number" class="batch_update_value" placeholder="' + _x('Enter a value...', 'placeholder', 'smart-manager-for-wp-e-commerce') + '" class="FormElement ui-widget-content" />');
        }
    }


    SmartManager.prototype.createBatchUpdateDialog = function () {

        if (window.smart_manager.selectedRows.length <= 0 && window.smart_manager.selectAll === false) {
            return;
        }

        let allItemsOptionText = (window.smart_manager.simpleSearchText != '' || window.smart_manager.advancedSearchQuery.length > 0) ? _x('All Items In Search Results', 'bulk edit option', 'smart-manager-for-wp-e-commerce') : _x('All Items In Store', 'bulk edit option', 'smart-manager-for-wp-e-commerce');

        let entire_store_batch_update_html = "<tr>" +
            ((window.smart_manager.selectAll === false) ? "<td style='white-space: pre;'><input type='radio' name='batch_update_storewide' value='selected_ids' checked/>" + _x('Selected Items', 'bulk edit option', 'smart-manager-for-wp-e-commerce') + "</td>" : '') +
            "<td style='white-space: pre;'><input type='radio' name='batch_update_storewide' value='entire_store' " + ((window.smart_manager.selectAll === true) ? 'checked' : '') + " />" + allItemsOptionText + "</td>" +
            "</tr>",
            batch_update_field_options = '<option value="" disabled selected>' + _x('Select Field', 'bulk edit field', 'smart-manager-for-wp-e-commerce') + '</option>',
            batch_update_action_options_string = '',
            batch_update_action_options_number = '',
            batch_update_action_options_datetime = '',
            batch_update_action_options_multilist = '',
            batch_update_actions_row = '',
            batch_update_dlg_content = '',
            dlgParams = {};

        if (Object.getOwnPropertyNames(window.smart_manager.columnNamesBatchUpdate).length > 0) {
            for (let key in window.smart_manager.columnNamesBatchUpdate) {
                batch_update_field_options += '<option value="' + key + '" data-type="' + window.smart_manager.columnNamesBatchUpdate[key].type + '" data-editor="' + window.smart_manager.columnNamesBatchUpdate[key].editor + '" data-multiSelectSeparator="' + window.smart_manager.columnNamesBatchUpdate[key].multiSelectSeparator + '">' + window.smart_manager.columnNamesBatchUpdate[key].name + '</option>';
            };
        }

        //Formating options for default actions
        window.smart_manager.batch_update_action_options_default = '<option value="" disabled selected>' + _x('Select Action', 'bulk edit default action', 'smart-manager-for-wp-e-commerce') + '</option>';
        window.smart_manager.batch_update_action_options_default += '<option value="set_to">' + _x('set to', 'bulk edit action', 'smart-manager-for-wp-e-commerce') + '</option>';
        window.smart_manager.batch_update_action_options_default += window.smart_manager.batchUpdateCopyFromOption;

        //Formating options for number actions
        batch_update_action_options_string = window.smart_manager.batchUpdateSelectActionOption;
        let selected = '';
        for (let key in window.smart_manager.batch_update_action_string) {
            selected = '';
            if (key == 'set_to') {
                selected = 'selected';
            }

            batch_update_action_options_string += '<option value="' + key + '" ' + selected + '>' + window.smart_manager.batch_update_action_string[key] + '</option>';
        }
        batch_update_action_options_string += window.smart_manager.batchUpdateCopyFromOption;

        //Formating options for datetime actions
        batch_update_action_options_datetime = window.smart_manager.batchUpdateSelectActionOption;
        for (let key in window.smart_manager.batch_update_action_datetime) {
            selected = '';
            if (key == 'set_datetime_to') {
                selected = 'selected';
            }
            batch_update_action_options_datetime += '<option value="' + key + '" ' + selected + '>' + window.smart_manager.batch_update_action_datetime[key] + '</option>';
        }
        batch_update_action_options_datetime += window.smart_manager.batchUpdateCopyFromOption;

        //Formating options for multilist actions
        batch_update_action_options_multilist = window.smart_manager.batchUpdateSelectActionOption;
        for (let key in window.smart_manager.batch_update_action_multilist) {
            selected = '';
            if (key == 'set_to') {
                selected = 'selected';
            }
            batch_update_action_options_multilist += '<option value="' + key + '" ' + selected + '>' + window.smart_manager.batch_update_action_multilist[key] + '</option>';
        }
        batch_update_action_options_multilist += window.smart_manager.batchUpdateCopyFromOption;

        //Formating options for string actions
        batch_update_action_options_number = window.smart_manager.batchUpdateSelectActionOption;
        for (let key in window.smart_manager.batch_update_action_number) {
            selected = '';
            if (key == 'set_to') {
                selected = 'selected';
            }
            batch_update_action_options_number += '<option value="' + key + '" ' + selected + '>' + window.smart_manager.batch_update_action_number[key] + '</option>';
        }
        batch_update_action_options_number += window.smart_manager.batchUpdateCopyFromOption;


        batch_update_actions_row = "<td style='white-space: pre;'><select required class='batch_update_field' style='min-width:130px;width:auto !important;'>" + batch_update_field_options + "</select></td>" +
            "<td style='white-space: pre;'><select required class='batch_update_action' style='min-width:130px !important;'>" + window.smart_manager.batch_update_action_options_default + "</select></td>" +
            "<td id='batch_update_value_td' style='white-space: pre;'><input type='text' class='batch_update_value' placeholder='" + _x('Enter a value...', 'placeholder', 'smart-manager-for-wp-e-commerce') + "' class='FormElement ui-widget-content'></td>" +
            "<td id='batch_update_add_delete_row' style='float:right;'><div class='dashicons dashicons-plus' style='color:#0073aa;cursor:pointer;line-height:1.7em;'></div><div class='dashicons dashicons-trash' style='color:#FF5B5E;cursor:pointer;line-height:1.5em;'></div></td>";



        batch_update_dlg_content = "<div id='batchUpdateform' class='formdata' style='width: 100%; overflow: auto; position: relative; height: auto;'>" +
            "<table class='batch_update_table' width='100%'>" +
            "<tbody>" +
            entire_store_batch_update_html +
            "<tr id='batch_update_action_row_0'>" +
            batch_update_actions_row +
            "</tr>" +
            "<tr>" +
            "<td>&#160;</td>" +
            "</tr>" +
            "</tbody>" +
            "</table>" +
            "</div>";

        dlgParams.btnParams = {};
        dlgParams.btnParams.yesText = _x('Update', 'button', 'smart-manager-for-wp-e-commerce');
        if (typeof (window.smart_manager.processBatchUpdate) !== "undefined" && typeof (window.smart_manager.processBatchUpdate) === "function") {
            dlgParams.btnParams.yesCallback = window.smart_manager.processBatchUpdate;
        }

        dlgParams.btnParams.noText = _x('Reset', 'button', 'smart-manager-for-wp-e-commerce');
        if (typeof (window.smart_manager.resetBatchUpdate) !== "undefined" && typeof (window.smart_manager.resetBatchUpdate) === "function") {
            dlgParams.btnParams.noCallback = window.smart_manager.resetBatchUpdate;
        }

        dlgParams.title = _x('Bulk Edit', 'modal title', 'smart-manager-for-wp-e-commerce');
        dlgParams.content = batch_update_dlg_content;
        dlgParams.height = 300;
        dlgParams.width = 850;

        window.smart_manager.showConfirmDialog(dlgParams);

        jQuery('.batch_update_field, .batch_update_action').each(function () {
            jQuery(this).select2({ width: '15em', dropdownCssClass: 'sm_beta_batch_update_field', dropdownParent: jQuery('[aria-describedby="sm_inline_dialog"]') });
        })

        jQuery(".batch_update_action_row_0").find('#batch_update_add_delete_row .dashicons-trash').hide(); //for hiding the delete icon for the first row

        //function for handling add row in batch update dialog
        jQuery(document).off('click', '#batch_update_add_delete_row .dashicons-plus').on('click', '#batch_update_add_delete_row .dashicons-plus', function () {
            let count = jQuery('tr[id^=batch_update_action_row_]').length,
                current_id = 'batch_update_action_row_' + count;
            jQuery('.batch_update_table tr:last').before("<tr id=" + current_id + ">" + batch_update_actions_row + "</tr>");

            jQuery("#" + current_id).find('.batch_update_field, .batch_update_action').select2({ width: '15em', dropdownCssClass: 'sm_beta_batch_update_field', dropdownParent: jQuery('[aria-describedby="sm_inline_dialog"]') });

            jQuery(this).hide();

        });

        //function for handling delete row in batch update dialog
        jQuery(document).off('click', '#batch_update_add_delete_row .dashicons-trash').on('click', '#batch_update_add_delete_row .dashicons-trash', function () {

            let add_row_visible = jQuery(this).closest('td').find('.dashicons-plus').is(":visible");
            jQuery(this).closest('tr').remove();

            if (add_row_visible === true) { //condition for removing plus icon only if visible
                jQuery('tr[id^=batch_update_action_row_]:last()').find('.dashicons-plus').show();
            }

        });

        // For the time now
        Date.prototype.timeNow = function () {
            return ((this.getHours() < 10) ? "0" : "") + this.getHours() + ":" + ((this.getMinutes() < 10) ? "0" : "") + this.getMinutes() + ":" + ((this.getSeconds() < 10) ? "0" : "") + this.getSeconds();
        }

        jQuery(document).on('change', '.batch_update_field', function () {

            let row_id = jQuery(this).closest('tr').attr('id');

            let selected_field = jQuery("#" + row_id + " .batch_update_field option:selected").val(),
                type = window.smart_manager.columnNamesBatchUpdate[selected_field].type,
                editor = window.smart_manager.columnNamesBatchUpdate[selected_field].editor,
                col_val = window.smart_manager.columnNamesBatchUpdate[selected_field].values,
                skip_default_action = false;

            // Formating options for default actions
            window.smart_manager.batch_update_action_options_default = window.smart_manager.batchUpdateSelectActionOption +
                '<option value="set_to">' + _x('set to', 'bulk edit action', 'smart-manager-for-wp-e-commerce') + '</option>' +
                window.smart_manager.batchUpdateCopyFromOption;

            jQuery(document).trigger("sm_batch_update_field_on_change", [row_id, selected_field, type, col_val]);

            if (type == 'numeric') {
                jQuery("#" + row_id + " .batch_update_action").empty().append(batch_update_action_options_number);
            } else if (type == 'text' || type == 'sm.longstring') {
                jQuery("#" + row_id + " .batch_update_action").empty().append(batch_update_action_options_string);
            } else if (type == 'sm.datetime') {
                jQuery("#" + row_id + " .batch_update_action").empty().append(batch_update_action_options_datetime);
            } else if (type == 'sm.multilist' || (type == 'dropdown' && editor == 'select2')) {
                jQuery("#" + row_id + " .batch_update_action").empty().append(batch_update_action_options_multilist);
            } else {
                jQuery("#" + row_id + " .batch_update_action").empty().append(window.smart_manager.batch_update_action_options_default);
                jQuery("#" + row_id + " .batch_update_action").find('[value="set_to"]').attr('selected', 'selected');
            }

            let actionOptions = {
                'batch_update_action_options_number': batch_update_action_options_number,
                'batch_update_action_options_string': batch_update_action_options_string,
                'batch_update_action_options_datetime': batch_update_action_options_datetime,
                'batch_update_action_options_multilist': batch_update_action_options_multilist
            };

            jQuery(document).trigger("sm_batch_update_field_post_on_change", [row_id, selected_field, type, col_val, actionOptions]);

            jQuery("#" + row_id + " .batch_update_value").val('');

            jQuery("#" + row_id + " #batch_update_value_td").empty().append('<input type="text" class="batch_update_value" placeholder="' + _x("Enter a value...", "placeholder", "smart-manager-for-wp-e-commerce") + '" class="FormElement ui-widget-content" >');

            if (skip_default_action === true) {
                return;
            }

            if (type == 'sm.date' || type == 'sm.time' || type == 'sm.datetime') {
                let placeholder = 'YYYY-MM-DD' + ((type == 'sm.datetime') ? ' HH:MM:SS' : '');
                placeholder = (type == 'sm.time') ? 'H:i' : placeholder;

                jQuery("#" + row_id + " .batch_update_value").attr('placeholder', placeholder);

                let format = 'Y-m-d' + ((type == 'sm.datetime') ? ' H:i:s' : '');
                format = (type == 'sm.time') ? 'H:i' : format;

                jQuery("#" + row_id + " .batch_update_value").Zebra_DatePicker({
                    format: format,
                    show_icon: false,
                    show_select_today: false,
                    default_position: 'below',
                });
            } else {
                jQuery("#" + row_id + " .batch_update_value").attr('placeholder', _x('Enter a value...', 'placeholder', 'smart-manager-for-wp-e-commerce'));
                let datepicker = jQuery("#" + row_id + " .batch_update_value").data('Zebra_DatePicker');
            }

            if (typeof (window.smart_manager.displayDefaultBatchUpdateValueHandler) !== "undefined" && typeof (window.smart_manager.displayDefaultBatchUpdateValueHandler) === "function") {
                dlgParams.btnParams.noCallback = window.smart_manager.displayDefaultBatchUpdateValueHandler(row_id);
            }
        });

        //Handling action change event only for 'datetime' type fields
        jQuery(document).on('change', '.batch_update_action', function () {

            let row_id = jQuery(this).closest('tr').attr('id');

            let selected_field = jQuery("#" + row_id + " .batch_update_field option:selected").val(),
                selected_action = jQuery("#" + row_id + " .batch_update_action option:selected").val(),
                type = window.smart_manager.columnNamesBatchUpdate[selected_field].type;

            if (jQuery("#" + row_id + " #batch_update_value_td").find(".sm_batch_update_copy_from_ids").length > 0 || jQuery("#" + row_id + " #batch_update_value_td").find(".sm_batch_update_search_value").length > 0) {
                jQuery("#" + row_id + " #batch_update_value_td").empty().append('<input type="text" class="batch_update_value" placeholder="' + _x('Enter a value...', 'placeholder', 'smart-manager-for-wp-e-commerce') + '" class="FormElement ui-widget-content" >');
            }

            if (type == 'sm.datetime') {
                let placeholder = (selected_action == 'set_datetime_to') ? 'YYYY-MM-DD HH:MM:SS' : ((selected_action == 'set_date_to') ? 'YYYY-MM-DD' : 'HH:MM:SS');

                jQuery("#" + row_id + " .batch_update_value").attr('placeholder', placeholder);

                let format = (selected_action == 'set_datetime_to') ? 'Y-m-d H:i:s' : ((selected_action == 'set_date_to') ? 'Y-m-d' : 'H:i:s');
                jQuery("#" + row_id + " .batch_update_value").Zebra_DatePicker({
                    format: format,
                    show_icon: false,
                    show_select_today: false,
                    default_position: 'below',
                });
            }

            //Code for handling 'copy from' functionality
            if (selected_action == 'copy_from') {

                let select_str = '<select class="batch_update_value sm_batch_update_copy_from_ids" style="min-width:130px !important;">' +
                    '<option></option></select>';

                let select2Params = {
                    width: '15em',
                    dropdownCssClass: 'sm_beta_batch_update_field',
                    placeholder: sprintf(
                        /* translators: %s: dashboard display name */
                        _x('Select %s', 'placeholder', 'smart-manager-for-wp-e-commerce'), window.smart_manager.dashboardDisplayName),
                    dropdownParent: jQuery('[aria-describedby="sm_inline_dialog"]'),
                    ajax: {
                        url: window.smart_manager.commonManagerAjaxUrl,
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                search_term: params.term,
                                cmd: 'get_batch_update_copy_from_record_ids',
                                active_module: window.smart_manager.dashboardKey,
                                security: window.smart_manager.saCommonNonce,
                                is_taxonomy: window.smart_manager.isTaxonomyDashboard()
                            };
                        },
                        processResults: function (data) {
                            var terms = [];
                            if (data) {
                                jQuery.each(data, function (id, title) {
                                    terms.push({
                                        id: id,
                                        text: title
                                    });
                                });
                            }
                            return {
                                results: terms
                            };
                        },
                        cache: true
                    }
                }

                jQuery("#" + row_id + " #batch_update_value_td").empty().append(select_str);
                jQuery("#" + row_id + " #batch_update_value_td").find(".batch_update_value").select2(select2Params);
            } else if (selected_action == 'search_and_replace') {
                jQuery("#" + row_id + " #batch_update_value_td").empty().append('<input type="text" class="batch_update_value sm_batch_update_search_value" placeholder="' + _x('Search for...', 'placeholder', 'smart-manager-for-wp-e-commerce') + '" class="FormElement ui-widget-content" >');
                jQuery("#" + row_id + " #batch_update_value_td").append('<input type="text" style="margin-left: 1em;" class="batch_update_value sm_batch_update_replace_value" placeholder="' + _x('Replace with...', 'placeholder', 'smart-manager-for-wp-e-commerce') + '" class="FormElement ui-widget-content" >');
            } else {
                if (typeof (window.smart_manager.displayDefaultBatchUpdateValueHandler) !== "undefined" && typeof (window.smart_manager.displayDefaultBatchUpdateValueHandler) === "function") {
                    dlgParams.btnParams.noCallback = window.smart_manager.displayDefaultBatchUpdateValueHandler(row_id);
                }
            }
        });

        jQuery(document).off('click', ".batch_update_image").on('click', ".batch_update_image", function (event) {

            let row_id = jQuery(this).closest('tr').attr('id');

            let file_frame;

            // If the media frame already exists, reopen it.
            if (file_frame) {
                file_frame.open();
                return;
            }

            // Create the media frame.
            file_frame = wp.media.frames.file_frame = wp.media({
                title: jQuery(this).data('uploader_title'),
                button: {
                    text: jQuery(this).data('uploader_button_text')
                },
                multiple: false  // Set to true to allow multiple files to be selected
            });

            file_frame.on('open', function () {
                jQuery('[aria-describedby="sm_inline_dialog"]').hide();
            });

            file_frame.on('close', function () {
                jQuery('[aria-describedby="sm_inline_dialog"]').show();
            });

            // When an image is selected, run a callback.
            file_frame.on('select', function () {
                // We set multiple to false so only get one image from the uploader
                attachment = file_frame.state().get('selection').first().toJSON();

                jQuery('#' + row_id + ' .batch_update_image').attr('data-imageId', attachment['id']);
                jQuery('#' + row_id + ' .batch_update_image').html('<img style="cursor:pointer;" src="' + attachment['url'] + '" width="32" height="32">');
            });

            file_frame.open();
        });
    };

    // ========================================================================

    SmartManager.prototype.smToggleFullScreen = function (elem) {
        // ## The below if statement seems to work better ## if ((document.fullScreenElement && document.fullScreenElement !== null) || (document.msfullscreenElement && document.msfullscreenElement !== null) || (!document.mozFullScreen && !document.webkitIsFullScreen)) {
        if ((document.fullScreenElement !== undefined && document.fullScreenElement === null) || (document.msFullscreenElement !== undefined && document.msFullscreenElement === null) || (document.mozFullScreen !== undefined && !document.mozFullScreen) || (document.webkitIsFullScreen !== undefined && !document.webkitIsFullScreen)) {
            if (elem.requestFullScreen) {
                elem.requestFullScreen();
            } else if (elem.mozRequestFullScreen) {
                elem.mozRequestFullScreen();
            } else if (elem.webkitRequestFullScreen) {
                elem.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
            } else if (elem.msRequestFullscreen) {
                elem.msRequestFullscreen();
            }
        } else {
            if (document.cancelFullScreen) {
                document.cancelFullScreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.webkitCancelFullScreen) {
                document.webkitCancelFullScreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            }
        }
    }

    SmartManager.prototype.smScreenHandler = function () {
        if (window.smart_manager.wpToolsPanelWidth === 0) {
            window.smart_manager.wpToolsPanelWidth = jQuery('#adminmenuwrap').width();
            jQuery('#adminmenuback').hide();
            jQuery('#adminmenuwrap').hide();
            jQuery('#wpadminbar').hide();
            jQuery('#wpcontent').css('margin-left', '0px');
            window.smart_manager.grid_width = window.smart_manager.grid_width + window.smart_manager.wpToolsPanelWidth;
            window.smart_manager.grid_height = document.documentElement.offsetHeight - 260;
        } else {
            jQuery('#adminmenuback').show();
            jQuery('#adminmenuwrap').show();
            jQuery('#wpadminbar').show();
            jQuery('#wpcontent').removeAttr("style");
            window.smart_manager.grid_width = window.smart_manager.grid_width - window.smart_manager.wpToolsPanelWidth;
            window.smart_manager.grid_height = document.documentElement.offsetHeight - 360;
            window.smart_manager.wpToolsPanelWidth = 0;
        }

        window.smart_manager.hot.updateSettings({ 'width': window.smart_manager.grid_width, 'height': window.smart_manager.grid_height });
        window.smart_manager.hot.render();

        jQuery('#sm_top_bar, #sm_bottom_bar').css('width', window.smart_manager.grid_width + 'px');
    }

    if (document.addEventListener) {
        document.addEventListener('webkitfullscreenchange', window.smart_manager.smScreenHandler, false);
        document.addEventListener('mozfullscreenchange', window.smart_manager.smScreenHandler, false);
        document.addEventListener('fullscreenchange', window.smart_manager.smScreenHandler, false);
        document.addEventListener('MSFullscreenChange', window.smart_manager.smScreenHandler, false);
    }

    // Function to handle deletion of records
    SmartManager.prototype.deleteAllRecords = function (actionArgs) {
        if ("undefined" !== typeof (window.smart_manager.deleteAndUndoRecords) && "function" === typeof (window.smart_manager.deleteAndUndoRecords)) {
            window.smart_manager.deleteAndUndoRecords({ cmd: 'delete_all', args: ('undefined' !== typeof ('actionArgs')) ? actionArgs : {} });
        }
    }
    // Function to handle undo tasks
    SmartManager.prototype.undoTasks = function () {
        if ("undefined" !== typeof (window.smart_manager.deleteAndUndoRecords) && "function" === typeof (window.smart_manager.deleteAndUndoRecords)) {
            window.smart_manager.deleteAndUndoRecords({ cmd: 'undo' });
        }
    }
    // Function to handle tasks deletion
    SmartManager.prototype.deleteTasks = function () {
        if ("undefined" !== typeof (window.smart_manager.deleteAndUndoRecords) && "function" === typeof (window.smart_manager.deleteAndUndoRecords)) {
            window.smart_manager.deleteAndUndoRecords({ cmd: 'delete' });
        }
    }

    // Function to handle displaying tasks
    SmartManager.prototype.showTasks = function () {
        let props = [window.smart_manager.displayTasks, window.smart_manager.resetSearch, window.smart_manager.setDashboardDisplayName, window.smart_manager.loadDashboard, window.smart_manager.isTasksEnabled, window.smart_manager.reset];
        if (!props && !(props.every(prop => ("undefined" === typeof (prop) && "function" !== typeof (prop))))) {
            return;
        }
        window.smart_manager.displayTasks({ showHideTasks: window.smart_manager.isTasksEnabled() });
        if(!window.location.search.includes('show_edit_history')){
            window.smart_manager.reset(true);
        }
        window.smart_manager.resetSearch();
        if (window.smart_manager.isTasksEnabled() === 1) {
            if (window.smart_manager.savedSearchConds && window.smart_manager.savedSearchConds['task'] && ((typeof (window.smart_manager.handleSavedSearchConditions) !== "undefined") && (typeof (window.smart_manager.handleSavedSearchConditions) === "function"))) {
                window.smart_manager.handleSavedSearchConditions(window.smart_manager.savedSearchConds['task']);
            }
        } else if (window.smart_manager.savedSearchConds && window.smart_manager.savedSearchConds['postType'] && ((typeof (window.smart_manager.handleSavedSearchConditions) !== "undefined") && (typeof (window.smart_manager.handleSavedSearchConditions) === "function"))) {
            window.smart_manager.handleSavedSearchConditions(window.smart_manager.savedSearchConds['postType']);
        }
        window.smart_manager.setDashboardDisplayName();
        if(!window.location.search.includes('show_edit_history')){
            window.smart_manager.loadDashboard();
        }
    }
    // Function to handle records deletion, tasks undo and deletion of tasks records
    SmartManager.prototype.deleteAndUndoRecords = function (params = {}) {
        if (!params || (0 === Object.keys(params).length) || (false === params.hasOwnProperty('cmd')) || !params['cmd'] || ((0 === window.smart_manager.selectedRows.length && !window.smart_manager.selectAll) && (!window.smart_manager.selectedAllTasks))) {
            return;
        }
        window.smart_manager.selectAll = (window.smart_manager.selectedAllTasks || window.smart_manager.selectAll) ? true : false;
        let selectedIDs = (window.smart_manager.taskId) ? [window.smart_manager.taskId.toString()] : window.smart_manager.getSelectedKeyIds().sort(function (a, b) { return b - a });
        params.data = {
            cmd: params['cmd'],
            active_module: window.smart_manager.dashboardKey,
            security: window.smart_manager.saCommonNonce,
            storewide_option: (window.smart_manager.selectAll) ? 'entire_store' : '',
            active_module_title: window.smart_manager.dashboardName,
            backgroundProcessRunningMessage: window.smart_manager.backgroundProcessRunningMessage,
            pro: true,
            SM_IS_WOO30: (window.smart_manager.sm_is_woo30) ? window.smart_manager.sm_is_woo30 : '',
            SM_IS_WOO22: (window.smart_manager.sm_id_woo22) ? window.smart_manager.sm_id_woo22 : '',
            SM_IS_WOO21: (window.smart_manager.sm_is_woo21) ? window.smart_manager.sm_is_woo21 : ''
        };
        let processName = '', tasksParams = ['undo', 'delete'];
        let currentProcessName = '';
        if (tasksParams.includes(params['cmd'])) {
            params.data.isTasks = 1;
        }
        switch (params['cmd']) {
            case 'delete_all':
                processName = _x('Delete Records', 'progressbar modal title', 'smart-manager-for-wp-e-commerce');
                params.data.deletePermanently = (params.hasOwnProperty('args') && (params.args.hasOwnProperty('deletePermanently'))) ? params.args.deletePermanently : 0;
                currentProcessName = (params.data.deletePermanently) ? 'delete_permanently' : 'move_to_trash';
                break;
            case 'undo':
                if(false === window.smart_manager.selectAll && selectedIDs){
                    let importedTasksData = window.smart_manager.getImportedTasksDataFromSelectedIds(window.smart_manager.selectedRows, window.smart_manager.currentDashboardData);
                    if(importedTasksData && importedTasksData.hasOwnProperty('importedTaskIds') && Array.isArray(importedTasksData.importedTaskIds)){
                        selectedIDs = selectedIDs.filter(id => !importedTasksData.importedTaskIds.includes(id));
                    }
                }
                processName = _x('Undo Tasks', 'progressbar modal title', 'smart-manager-for-wp-e-commerce');
                currentProcessName = 'undo';
                break;
            case 'delete':
                processName = _x('Delete Tasks', 'progressbar modal title', 'smart-manager-for-wp-e-commerce');
                currentProcessName = 'delete';
                break;
        }
        params.data.selected_ids = JSON.stringify(selectedIDs)
        setTimeout(function () {
            window.smart_manager.showProgressDialog(processName);
            if ("undefined" !== typeof (sa_background_process_heartbeat) && "function" === typeof (sa_background_process_heartbeat)) {
                sa_background_process_heartbeat(1000, currentProcessName, 'smart_manager');
            }
        }, 1);
        params.showLoader = false;
        if (window.smart_manager.isFilteredData()) {
            params.data.filteredResults = 1;
        }
        window.smart_manager.sendRequest(params, function (response) {
            window.smart_manager.taskId = 0;
        });
    }
    // Function for displaying warning modal before doing undo/delete tasks records
    SmartManager.prototype.taskActionsModal = function (args = {}) {
        if (!args || ('object' !== typeof (args))) {
            return;
        }
        window.smart_manager.selectedAllTasks = (['sm_beta_undo_all_tasks', 'sm_beta_delete_all_tasks'].includes(args.id)) ? true : false;
        if (0 === window.smart_manager.selectedRows.length && !window.smart_manager.selectAll && !window.smart_manager.selectedAllTasks) {
            window.smart_manager.notification = { message: _x('Please select a task', 'notification', 'smart-manager-for-wp-e-commerce') }
            window.smart_manager.showNotification()
            return false;
        }

        let undoTaskIds = (['sm_beta_undo_selected', 'sm_beta_undo_all_tasks'].includes(args.id)) ? 1 : 0,
            deleteTasks = (['sm_beta_delete_selected_tasks', 'sm_beta_delete_all_tasks'].includes(args.id)) ? 1 : 0,
            params = {},
            paramsContent = '',
            isBackgroundProcessRunning = window.smart_manager.backgroundProcessRunningNotification(false);
        params.btnParams = {}
        params.title = '<span class="sm-error-icon"><span class="dashicons dashicons-warning" style="vertical-align: text-bottom;"></span>&nbsp;' + _x('Attention!', 'modal title', 'smart-manager-for-wp-e-commerce') + '</span>';
        let taskInlineEditMessage = args.hasOwnProperty('taskInlineEditMessage') ? args.taskInlineEditMessage : '';
        switch (true) {
            case (('' !== taskInlineEditMessage) || (undoTaskIds && "undefined" !== typeof (window.smart_manager.undoTasks) && "function" === typeof (window.smart_manager.undoTasks))):
                paramsContent = 'undo';
                paramsContent = (taskInlineEditMessage) ? (paramsContent + ' ' + args.taskInlineEditMessage) : paramsContent;
                params.btnParams.yesCallback = window.smart_manager.undoTasks;
                if(window.smart_manager.selectedAllTasks){
                    params.title = _x('Undo all?', 'smart-manager-for-wp-e-commerce');
                    params.showCloseCTAFirst = false
                }
                break;
            case (deleteTasks && "undefined" !== typeof (window.smart_manager.deleteTasks) && "function" === typeof (window.smart_manager.deleteTasks)):
                paramsContent = '<span class="sm-error-icon">' + _x('delete', 'modal content', 'smart-manager-for-wp-e-commerce') + '</span>';
                if(window.smart_manager.selectedAllTasks){
                    params.title = _x('Clear History?', 'smart-manager-for-wp-e-commerce');
                    params.content = `<span class="text-sm-base-muted-foreground">${_x('Clearing history cannot be reverted. Are you sure to delete them all?', 'smart-manager-for-wp-e-commerce')}</span>`;
                    params.btnParams.yesBtnClass = 'bg-transparent text-sm-base-destructive hover:bg-red-50';
                    params.btnParams.noBtnClass = 'bg-sm-base-primary text-sm-base-primary-foreground hover:bg-[#5850d6]';
                    params.btnParams.yesText = _x('Yes, Clear', 'smart-manager-for-wp-e-commerce')
                    params.showCloseCTAFirst = false
                }
                params.btnParams.yesCallback = window.smart_manager.deleteTasks;
                break;
        }
        if(!params.content){
            params.content = _x('Are you sure you want to ' + paramsContent + ' ', 'modal content', 'smart-manager-for-wp-e-commerce');
            params.content = (taskInlineEditMessage) ? params.content : (params.content + '<strong class="text-sm-base-foreground">' + args.btnText.toLowerCase() + '</strong>?');
        }
        if(args.notesText && args.notesText.length){
            params.content += args.notesText;
        }
        if (!isBackgroundProcessRunning) {
            if (window.smart_manager.selectedRows.length > 0 || window.smart_manager.totalRecords) {
                params.btnParams.hideOnYes = false;
                window.smart_manager.showConfirmDialog(params);
            } else {
                window.smart_manager.notification = { message: _x('No task to ' + paramsContent, 'warning', 'smart-manager-for-wp-e-commerce') }
                window.smart_manager.showNotification()
            }
        }
    }

    // Function for handling display of editor for column titles
    SmartManager.prototype.displayColumnTitleEditor = function (e) {
        let parent = e.target.closest('li');
        if (!parent) return;
        let input = parent.querySelector("input[type='text']");
        if (!input) return;
        let cssClass = 'sm-column-title-input-edit';
        input.readOnly = !input.readOnly;
        if (input.readOnly) {
            input.classList.remove(cssClass)
        } else {
            input.classList.add(cssClass);
            input.focus();
            //Code for setting the cursor at end of input
            let val = input.value;
            input.value = '';
            input.value = val;
        }
    }

    // Function for scheduling bulk edit
    SmartManager.prototype.showScheduleModal = function (params = {}) {
        SaCommonManagerPro.prototype.showScheduleModal.call(this, params);
    }
    // Function for getting scheduled for value
    SmartManager.prototype.scheduledForVal = function () {
        SaCommonManagerPro.prototype.scheduledForVal.call(this);
    }

    SmartManager.prototype.scheduleDatePicker = function(selector) {
        SaCommonManagerPro.prototype.scheduleDatePicker.call(this, selector);
    }
    // ========================================================================
    // PRIVILEGE SETTINGS UPDATE
    // ========================================================================
    SmartManager.prototype.privilegeSettingsUpdate = function () {
        if ((window.smart_manager.privilegeSettingsRules.length <= 0)) {
            return;
        }
        window.smart_manager.sendRequest({
            data: {
                cmd: 'save_access_privilege_settings',
                active_module: 'access-privilege',
                security: window.smart_manager.saCommonNonce,
                access_privileges: JSON.stringify(window.smart_manager.privilegeSettingsRules)
            },
            data_type: 'json'
        }, function (response) {
            let ack = (response.hasOwnProperty('ACK')) ? response.ACK : ''
            if ('success' === ack) {
                window.smart_manager.notification = {
                    status: 'success', message:
                        _x('Privilege settings saved successfully!', 'access privilege settings notification', 'smart-manager-for-wp-e-commerce'), hideDelay: 2000
                }
                window.smart_manager.showNotification()
            }
        });
    }
    // Function for resetting tasks view state when click on it during unsaved changes.
    SmartManager.prototype.handleShowTasks = function () {
        window.smart_manager.isTasksViewActive = false;
    }
    // Function for handling saved search conditions.
    SmartManager.prototype.handleSavedSearchConditions = function (type = {}) {
        if (!type) {
            return;
        }
        if (type.simpleSearchCond) {
            window.smart_manager.simpleSearchText = type.simpleSearchCond;
            jQuery('#sm_simple_search_box').val(window.smart_manager.simpleSearchText);
        } else if (type.advancedSearchCond) {
            window.smart_manager.advancedSearchQuery = type.advancedSearchCond;
            if (("undefined" !== typeof (window.smart_manager.updateAdvancedSearchRuleCount)) && ("function" === typeof (window.smart_manager.updateAdvancedSearchRuleCount))) {
                window.smart_manager.updateAdvancedSearchRuleCount();
            }
            jQuery('#search_switch').prop('checked', true).trigger('change');
        }
    }

    // Function to check if the saved search params contains post type rules.
    SmartManager.prototype.checkPostParamsInSavedSearch = function (savedSearchParams = {}) {
        if ((!savedSearchParams) || (!savedSearchParams.hasOwnProperty("params")) || (!savedSearchParams.params.hasOwnProperty("search_params")) || (!savedSearchParams.params.search_params.hasOwnProperty("params"))) {
            return false;
        }
        return savedSearchParams.params.search_params.params.some((param) => {
            if ((param.hasOwnProperty("rules")) && (Array.isArray(param.rules))) {
                return param.rules.some((rule) => {
                    if ((rule.hasOwnProperty("rules")) && (Array.isArray(rule.rules))) {
                        return rule.rules.some((nestedRule) => {
                            return ((nestedRule.hasOwnProperty("type")) && (nestedRule.type.includes("_posts.")));
                        });
                    }
                    return false;
                });
            }
            return false;
        });
    }

    // Function to Get list of eligible dashboards.
    SmartManager.prototype.GetEligibleDashboardsForSavedSearch = function (SavedSearch = {}) {
        if ((!window.smart_manager) || (!window.smart_manager.sm_dashboards) || (!SavedSearch) || (!SavedSearch.hasOwnProperty("parent_post_type"))) {
            return false;
        }
        //map params object to load dashboard.
        let eligibleDashboards = Object.keys(window.smart_manager.sm_dashboards);
        if (!eligibleDashboards.length) {
            return false;
        }
        // Move parent post type to the top
        eligibleDashboards = eligibleDashboards.filter(dashboard => ((dashboard !== 'user') && (dashboard !== SavedSearch.parent_post_type)));
        eligibleDashboards.unshift(SavedSearch.parent_post_type);
        return eligibleDashboards.map(type => ({ id: type, text: window.smart_manager.sm_dashboards[type] }));
    }

    // Function to show eligible dashboards list.
    SmartManager.prototype.eligibleDashboardsDialog = function (eligibleDashboards = []) {
        if (!eligibleDashboards.length) {
            return;
        }
        window.smart_manager.modal = {
            title: _x('Select Dashboard', 'saved search dashboards list modal title', 'smart-manager-for-wp-e-commerce'),
            content: window.smart_manager.getEligibleDashboardsHtml(eligibleDashboards),
            showCloseIcon: true,
            cta: {},
            contentClass:"eligible_dashboards_section_content",
		onCreate: function(){
		    if (jQuery('#eligible_dashboards_select').length) {
		        jQuery('#eligible_dashboards_select').select2({
		            dropdownCssClass: 'sm-eligible-dashboards-select2-dropdown'
		        });
		    }
		},
        }
        window.smart_manager.showModal();
    };

    // Function to generate modal HTML with a select dropdown.
    SmartManager.prototype.getEligibleDashboardsHtml = function (eligibleDashboards = []) {
        return `
    <div id="sm_eligible_dashboards_section">
        <div style="font-size: 1.2em;">
            <div style="margin-bottom: 1.3em;">
                ${_x('Choose a dashboard where you want to apply this saved search.', 'dashboard display test', 'smart-manager-for-wp-e-commerce')}
            </div>
        </div>
        <select id="eligible_dashboards_select" style="width: 100%;">
        <option value="">Select Dashboard</option>
            ${eligibleDashboards.map(dashboard => `
                <option value="${dashboard.id}">${dashboard.text}</option>
            `).join('')}
        </select>
    </div>
`;
    };

    //Function to filter advanced search query to keep only "posts" type columns.
    SmartManager.prototype.getPostsColumnsFromQuery = function (query = []) {
        if (!Array.isArray(query) || query.length === 0) {
            return [];
        }
        return query.reduce((filteredQuery, group) => {
            const filteredRules = group.rules.reduce((filteredRules, rule) => {
                const validRules = (rule.rules || []).filter(r => r.type.includes("_posts."));
                if (validRules.length) {
                    filteredRules.push({ ...rule, rules: validRules });
                }
                return filteredRules;
            }, []);

            if (filteredRules.length) {
                filteredQuery.push({ ...group, rules: filteredRules });
            }
            return filteredQuery;
        }, []);
    }
    SmartManagerPro.prototype.getDataDefaultParams = function (params) {
        SmartManager.prototype.getDataDefaultParams.apply(this, [params]);
        if (typeof window.smart_manager.date_params.date_filter_params != 'undefined') {
            window.smart_manager.currentGetDataParams.data['date_filter_params'] = window.smart_manager.date_params.date_filter_params;
        }
        if (typeof window.smart_manager.date_params.date_filter_query != 'undefined') {
            window.smart_manager.currentGetDataParams.data['date_filter_query'] = window.smart_manager.date_params.date_filter_query;
        }
    }
    SmartManager.prototype.scheduleCSVExportModalHTML = function () {
        return `
        <div class="container">
            <form id="sm_schedule_export_form">
                <!-- Start Time -->
                <div class="flex items-center field-parent">
                    <label for="sm_schedule_export_start_time">${_x('Start Time', 'label', 'smart-manager-for-wp-e-commerce')}</label>
                    <input class="sa_bulk_edit_content" type="text" id="sm_schedule_export_start_time" name="schedule_export_start_time" placeholder="${_x('Select future date', 'placeholder', 'smart-manager-for-wp-e-commerce')}" required>
                </div>
                <!-- Recurring Interval -->
                <div class="flex items-center field-parent">
                    <label for="sm_schedule_export_interval">${_x('Export Interval', 'label', 'smart-manager-for-wp-e-commerce')}</label>
                    <div class="flex export-interval-section">
                        <input class="sa_bulk_edit_content" type="number" value="30" id="sm_schedule_export_interval" name="schedule_export_interval" min="1" placeholder="${_x('Interval', 'placeholder', 'smart-manager-for-wp-e-commerce')}" required >
                        <select class="sa_bulk_edit_content" id="sm_schedule_export_interval_unit" name="schedule_export_interval_unit" required>
                            <option value="days">${_x('Days', 'interval unit', 'smart-manager-for-wp-e-commerce')}</option>
                        </select>
                    </div>
                </div>
                <!-- Email -->
                <div class="flex items-center field-parent">
                    <label for="sm_schedule_export_email">${_x('Email', 'label', 'smart-manager-for-wp-e-commerce')}</label>
                    <input class="sa_bulk_edit_content" type="email" id="sm_schedule_export_email" value="${window.smart_manager?.sm_admin_email || ''}" name="schedule_export_email" placeholder="${_x('Enter email for CSV link', 'placeholder', 'smart-manager-for-wp-e-commerce')}" required>
                </div>
                <!-- Order Statuses -->
                <div class="flex items-center field-parent">
                    <label for="sm_schedule_export_order_statuses">
                        ${_x('Order Statuses', 'label', 'smart-manager-for-wp-e-commerce')}
                    </label>
                    <select class="form-control" id="sm_schedule_export_order_statuses" name="schedule_export_order_statuses" multiple="multiple"">
                        ${window.smart_manager.hasOwnProperty('orderStatuses') && Object.entries(window.smart_manager.orderStatuses)
                .map(function ([value, label]) {
                    return `<option value="${value}">${label}</option>`;
                }).join('')}
                    </select>
                </div>
                <div class="mt-4">
                    <div class="scheduled-export-modal-note">
                        <strong class="text-sm-base-foreground">${_x('Notes:', 'modal description', 'smart-manager-for-wp-e-commerce')}</strong>
                        <ul class="mt-2">
                            <li>
                                ${_x('Scheduled actions follow timezone of your site. Avoid overlaps to prevent delays.', 'modal description', 'smart-manager-for-wp-e-commerce')}
                            </li>
                            <li>
                                ${_x('If the CSV export link is unavailable, the file can be accessed directly from the', 'modal description', 'smart-manager-for-wp-e-commerce')}<code>woocommerce_uploads</code> directory.
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- Link to Scheduled Actions -->
                <div class="mt-4">
                    ${_x(
                    `Check all scheduled export actions <a target='_blank' href=${window.smart_manager?.scheduledExportActionAdminUrl || ''}>here</a>.`,
                    'scheduled action list',
                    'smart-manager-for-e-commerce'
                )}
                </div>
            </form>
        </div>`;
    };

    //Initialize and configure the Select2 order statuses dropdown for the schedule export form.
    SmartManager.prototype.initOrderStatusesSelect2 = function () {
        jQuery('#sm_schedule_export_order_statuses').select2({
            tags: true,
            placeholder: _x('Leave blank to include all order statuses.', 'placeholder', 'smart-manager-for-wp-e-commerce'),
            width: '100%',
            containerCssClass: 'sm-schedule-export-order-status-select2-container',
            dropdownCssClass: 'sm-schedule-export-order-status-select2-dropdown',
        });
    }

    // Retrieves and validates the form data from the schedule export form.
    SmartManager.prototype.validateAndGetScheduleExportFormData = function () {
        const form = document.getElementById('sm_schedule_export_form');
        if (!form) {
            return;
        }
        // Remove error classes from all inputs.
        Array.from(form.querySelectorAll('input')).forEach(input => {
            input.classList.remove('border-red');
        });
        let message = '';
        // Remove previous error messages.
        const previousErrors = document.querySelectorAll('.sm-schedule-export-fields-error');
        previousErrors.forEach(el => el.remove());
        // Validate Email
        const emailEl = document.getElementById('sm_schedule_export_email');
        if (emailEl && !emailEl.checkValidity()) {
            emailEl.classList.add('border-red');
            message += `${_x('Please enter a valid email address.', 'validation message', 'smart-manager-for-wp-e-commerce')}<br>`;
        }
        // Validate Start Time.
        const startTimeEl = document.getElementById('sm_schedule_export_start_time');
        if (startTimeEl) {
            if (!startTimeEl.checkValidity()) {
                startTimeEl.classList.add('border-red');
                message += `${_x('Please select a start time.', 'validation message', 'smart-manager-for-wp-e-commerce')}<br>`;
            } else if (new Date(startTimeEl.value) < new Date(Date.now() + 2 * 60 * 60 * 1000)) {
                startTimeEl.classList.add('border-red');
                message += `${_x('Start time must be at least 2 hours from now.', 'validation message', 'smart-manager-for-wp-e-commerce')}<br>`;
            }
        }
        // Validate Interval.
        const intervalEl = document.getElementById('sm_schedule_export_interval');
        if (intervalEl && !intervalEl.checkValidity()) {
            intervalEl.classList.add('border-red');
            message += `${_x('Please select an export interval.', 'validation message', 'smart-manager-for-wp-e-commerce')}<br>`;
        }
        // Show error notification or return form data.
        if (message) {
            window.smart_manager.hidePannelDialog = true;
            window.smart_manager.notification = {
                status: 'error',
                message: message
            }
            return;
        }
        // Convert form data to an object.
        const formData = {};
        const formDataArray = new FormData(form);
        for (let [name, value] of formDataArray.entries()) {
            value = value.trim();
            if (value.length) {
                if (formData[name]) {
                    if (!Array.isArray(formData[name])) {
                        formData[name] = [formData[name]];
                    }
                    formData[name].push(value);
                } else {
                    formData[name] = (name === 'schedule_export_order_statuses') ? [value] : value;
                }
            }
        }
        return formData;
    };

    //schedule Exports Ajax Callback function.
    SmartManager.prototype.scheduleExportAjaxCallback = function (response = {}) {
        if (response && response.hasOwnProperty('ACK') && "success" === response.ACK && response.hasOwnProperty('data') && response.data.hasOwnProperty('msg')) {
            window.smart_manager.notification = { status: 'success', message: response.data.msg }
            window.smart_manager.showNotification()
        } else {
            window.smart_manager.notification = { status: 'error', message: _x('Error in scheduling export, please try again later', 'error message', 'smart-manager-for-wp-e-commerce') }
            window.smart_manager.showNotification()
        }
    }

    SmartManager.prototype.processBatchUpdate = function () {
        SaCommonBulkEdit.prototype.processBatchUpdate.call(this, false);
        if (!this.ajaxParams || (typeof this.ajaxParams !== 'object')) {
            return;
        }
        jQuery(document).trigger("sm_batch_update_on_submit"); //trigger to make changes in batch_update_actions
        //Ajax request to batch update the selected records
        Object.assign(this.ajaxParams.data, {
            SM_IS_WOO30: window.smart_manager.sm_is_woo30,
            SM_IS_WOO22: window.smart_manager.sm_id_woo22,
            SM_IS_WOO21: window.smart_manager.sm_is_woo21
        });
        // Code for passing tasks params
        this.ajaxParams.data = ("undefined" !== typeof (window.smart_manager.addTasksParams) && "function" === typeof (window.smart_manager.addTasksParams) && 1 == window.smart_manager.sm_beta_pro) ? window.smart_manager.addTasksParams(this.ajaxParams.data) : this.ajaxParams.data;
        if (window.smart_manager.isFilteredData()) {
            this.ajaxParams.data.filteredResults = 1;
        }
        if (true === window.smart_manager.isScheduled) {
            this.ajaxParams.data_type = 'text';
        }
        window.smart_manager.sendRequest(this.ajaxParams, function (response) {
            if (window.smart_manager.isScheduled) {
                window.smart_manager.refresh();
                window.smart_manager.notification = { message: response, hideDelay: 25000, status: 'success' }
                window.smart_manager.showNotification()
            }
        });
    }
    //Initializes NLP module for handling advanced search functionality
	SmartManager.prototype.addNLPForAdvancedSearch = function(){
		window.smart_manager.NLConverter.addModule(
			{
				moduleId:'advanced_search',
				//Before send request callback.
				beforeSend: () => {
					jQuery('.sa-loader-container').show();
				},
				// Success callback.
				onSuccess: (result, prompt) => {
					if(result && Array.isArray(result) && result[0] && result[0].hasOwnProperty('text') && window.smart_manager.isJSON(result[0].text)) {
						window.smart_manager.searchType = 'advanced';
						window.smart_manager.advancedSearchQuery = [JSON.parse(result[0].text)];
						// code to update the advanced seach rule count.
						window.smart_manager.advancedSearchRuleCount = 0;
						if (("undefined" !== typeof (window.smart_manager.updateAdvancedSearchRuleCount)) && ("function" === typeof (window.smart_manager.updateAdvancedSearchRuleCount))) {
							window.smart_manager.updateAdvancedSearchRuleCount();
						}
                        // Update the advanced search button state
                        if (typeof (window.smart_manager.updateAdvancedSearchButtonState) !== "undefined" && typeof (window.smart_manager.updateAdvancedSearchButtonState) === "function") {
                            window.smart_manager.updateAdvancedSearchButtonState()
                        }
                        window.smart_manager.advancedSearchPrompt = prompt;
						window.smart_manager.hideModal();
                        if (!jQuery('#search_switch').is(':checked')) {
                            jQuery('#search_switch').prop('checked', true).trigger('change').attr('switchsearchtype', 'simple');
                            return;
                        }
                        window.smart_manager.refresh()
					}
				},
				// Failure callback.
				onFailure: (errorMsg) => {
					jQuery('.sm-ai-assistant-error').show().html(errorMsg);
				}
			}
		);

	}
    //Function to apply advanced search based on its params.
    SmartManager.prototype.applyAdvancedSearch = function(advancedSearchQuery=''){
        if(advancedSearchQuery==='' || advancedSearchQuery==='{}'){
            return;
        }
        window.smart_manager.searchType = 'advanced';
        window.smart_manager.advancedSearchRuleCount = 0;
        window.smart_manager.advancedSearchQuery = advancedSearchQuery;
        if (("undefined" !== typeof (window.smart_manager.updateAdvancedSearchRuleCount)) && ("function" === typeof (window.smart_manager.updateAdvancedSearchRuleCount))) {
            window.smart_manager.updateAdvancedSearchRuleCount();
        }
        if (!jQuery('#search_switch').is(':checked')) {
            jQuery('#search_switch').prop('checked', true).trigger('change').attr('switchsearchtype', 'simple');
        } else{
            window.smart_manager.refresh()
        }
    }

    //Function to Remove params from URL.
    SmartManager.prototype.removeURLParams = function (paramsToRemove = []){
        const url = new URL(window.location.href);
        paramsToRemove.forEach(param => {
            url.searchParams.delete(param);
        });
        history.replaceState({}, '', url.pathname + url.search + url.hash);
    }
    //Check imported status for selected Smart Manager tasks rows.
    SmartManager.prototype.getImportedTasksDataFromSelectedIds = function (selectedRows = [], dashboardData = []) {
        // Sanity checks.
        if ( !Array.isArray(selectedRows) || !Array.isArray(dashboardData) || selectedRows.length === 0 ) {
            return {
                allImported: false,
                someImported: false
            };
        }
        let importedTaskIds = [];
        selectedRows.forEach(function(rowIndex) {
            const rowData = dashboardData[rowIndex] && typeof dashboardData[rowIndex] === 'object' ? dashboardData[rowIndex] : null;
            if (!rowData || !rowData.sm_tasks_type) {
                return;
            }
            if (rowData.sm_tasks_type === 'imported') {
                importedTaskIds.push(rowData.sm_tasks_id);
            }
        });
        const total = selectedRows?.length || 0;
        const importedCount = importedTaskIds?.length || 0;
        return {
            allImported: importedCount === total,
            someImported: importedCount > 0,
            importedTaskIds: importedTaskIds
        };
    }

    SmartManager.prototype.displayUndoTaskModal = function (id = '', btnText = '', notesText = '') {
        if("undefined" !== typeof(window.smart_manager.taskActionsModal) && "function" === typeof(window.smart_manager.taskActionsModal)){
            window.smart_manager.taskActionsModal({id: id, btnText: btnText, notesText: notesText});
        }
    }

    // ========================================================================
    // TASKS/HISTORY UNDO & DELETE FUNCTIONS
    // ========================================================================
    // Bind event handlers for tasks floating bar buttons
    SmartManager.prototype.bindTasksFloatingBarEvents = function () {
        // Undo selected tasks
        jQuery(document).off('click', '#sm-floating-undo-selected-btn').on('click', '#sm-floating-undo-selected-btn', function(e) {
            e.preventDefault();
            if (typeof window.smart_manager.displayUndoTaskModal === 'function') {
                let importedTasksStatus = window.smart_manager.getImportedTasksDataFromSelectedIds(window.smart_manager.selectedRows, window.smart_manager.currentDashboardData);
                let note = '<div class="mt-2 text-gray-500" style="font-style: italic;"><strong class="text-sm-base-foreground">' + _x('Note:', 'modal title', 'smart-manager-for-wp-e-commerce') + '</strong> ' + _x('Records of type "Imported" do not support the Undo action', 'modal content', 'smart-manager-for-wp-e-commerce') + '. </div>';
                
                if (importedTasksStatus && importedTasksStatus.hasOwnProperty('allImported') && importedTasksStatus.hasOwnProperty('someImported')) {
                    if (true === importedTasksStatus.allImported) {
                        window.smart_manager.notification = {
                            message: _x('Records of type "Imported" do not support the Undo action.', 'notification', 'smart-manager-for-wp-e-commerce')
                        };
                        window.smart_manager.showNotification();
                        return;
                    } else if (true === importedTasksStatus.someImported && false === importedTasksStatus.allImported) {
                        window.smart_manager.displayUndoTaskModal('sm_beta_undo_selected', _x('Selected Tasks', 'undo button', 'smart-manager-for-wp-e-commerce'), note);
                        return;
                    }
                }
                window.smart_manager.displayUndoTaskModal('sm_beta_undo_selected', _x('Selected Tasks', 'undo button', 'smart-manager-for-wp-e-commerce'));
            }
        });
        
        // Undo all tasks
        jQuery(document).off('click', '#sm-floating-undo-all-btn').on('click', '#sm-floating-undo-all-btn', function(e) {
            e.preventDefault();
            if (typeof window.smart_manager.displayUndoTaskModal === 'function') {
                let note = '<div class="mt-2 text-gray-500" style="font-style: italic;"><strong class="text-sm-base-foreground">' + _x('Note:', 'modal title', 'smart-manager-for-wp-e-commerce') + '</strong> ' + _x('Records of type "Imported" do not support the Undo action', 'modal content', 'smart-manager-for-wp-e-commerce') + '. </div>';
                window.smart_manager.displayUndoTaskModal('sm_beta_undo_all_tasks', _x('All Tasks', 'undo button', 'smart-manager-for-wp-e-commerce'), note);
            }
        });
        
        // Delete selected tasks
        jQuery(document).off('click', '#sm-floating-delete-selected-tasks-btn').on('click', '#sm-floating-delete-selected-tasks-btn', function(e) {
            e.preventDefault();
            if (typeof window.smart_manager.displayUndoTaskModal === 'function') {
                window.smart_manager.displayUndoTaskModal('sm_beta_delete_selected_tasks', _x('Selected Tasks', 'delete tasks button', 'smart-manager-for-wp-e-commerce'));
            }
        });
        
        // Delete all tasks
        jQuery(document).off('click', '#sm-floating-delete-all-tasks-btn').on('click', '#sm-floating-delete-all-tasks-btn', function(e) {
            e.preventDefault();
            if (typeof window.smart_manager.displayUndoTaskModal === 'function') {
                window.smart_manager.displayUndoTaskModal('sm_beta_delete_all_tasks', _x('All Tasks', 'delete tasks button', 'smart-manager-for-wp-e-commerce'));
            }
        });
    }

    // Function to undo a single history row
    SmartManager.prototype.undoHistoryRow = function (rowIndex) {
        // Get the task ID from the row data for display purposes
        const taskId = window.smart_manager.hot.getDataAtRowProp(rowIndex, window.smart_manager.getKeyID());
        if (taskId && typeof window.smart_manager.displayUndoTaskModal === 'function') {
            // Set selectedRows to contain the row index (getSelectedKeyIds will get the ID from this)
            window.smart_manager.selectedRows = [rowIndex];
            /* translators: %s is the task ID number */
            window.smart_manager.displayUndoTaskModal('sm_beta_undo_selected', sprintf(_x('Task #%s', 'undo single task', 'smart-manager-for-wp-e-commerce'), taskId));
        }
    }

    // Function to delete a single history row
    SmartManager.prototype.deleteHistoryRow = function (rowIndex) {
        // Get the task ID from the row data for display purposes
        const taskId = window.smart_manager.hot.getDataAtRowProp(rowIndex, window.smart_manager.getKeyID());
        if (taskId && typeof window.smart_manager.displayUndoTaskModal === 'function') {
            // Set selectedRows to contain the row index (getSelectedKeyIds will get the ID from this)
            window.smart_manager.selectedRows = [rowIndex];
            /* translators: %s is the task ID number */
            window.smart_manager.displayUndoTaskModal('sm_beta_delete_selected_tasks', sprintf(_x('Task #%s', 'delete single task', 'smart-manager-for-wp-e-commerce'), taskId));
        }
    }
    // Helper method to get recent searches for current dashboard
    SmartManager.prototype.getCurrentDashboardSearches = function () {
        return (this.recentSimpleSearches.hasOwnProperty(this.dashboardKey) && Array.isArray(this.recentSimpleSearches[this.dashboardKey])) ? this.recentSimpleSearches[this.dashboardKey] : [];
    }

    // Function to generate the recent searches dropdown HTML
    SmartManager.prototype.getRecentSearchesDropdownHtml = function () {
        const currentSearches = this.getCurrentDashboardSearches();
        if (!currentSearches || currentSearches.length === 0) {
            return '';
        }
        let itemsHtml = '';
        currentSearches.forEach((searchTerm) => {
            // const escapedTerm = searchTerm.replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
            itemsHtml += `
                <div class="sm-recent-search-item flex gap-2 items-center pl-8 pr-2 py-1.5 rounded-md cursor-pointer hover:bg-sm-base-muted relative" data-search-term="${searchTerm}">
                    <div class="absolute left-2 top-1/2 -translate-y-1/2 w-4 h-4 flex items-center justify-center"><svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0.665039 6.66501C0.665039 7.8517 1.01693 9.01173 1.67622 9.99843C2.33551 10.9851 3.27258 11.7542 4.36894 12.2083C5.4653 12.6624 6.6717 12.7812 7.83558 12.5497C8.99947 12.3182 10.0686 11.7468 10.9077 10.9076C11.7468 10.0685 12.3182 8.99944 12.5498 7.83555C12.7813 6.67166 12.6624 5.46526 12.2083 4.36891C11.7542 3.27255 10.9852 2.33548 9.99846 1.67619C9.01177 1.0169 7.85173 0.665009 6.66504 0.665009C4.98767 0.671319 3.37769 1.32582 2.17171 2.49168L0.665039 3.99834M0.665039 3.99834V0.665009M0.665039 3.99834H3.99837M6.66504 3.33168V6.66501L9.33171 7.99834" stroke="#737373" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
                    <p class="flex-1 text-sm leading-5 text-sm-base-foreground overflow-hidden whitespace-nowrap text-ellipsis m-0">${searchTerm}</p>
                </div>`;
        });
        return `
            <div id="sm-recent-searches-dropdown" class="absolute top-full left-0 right-0 mt-1 bg-sm-base-background border border-sm-base-border rounded-lg shadow-[0_0.25rem_0.375rem_-0.0625rem_rgba(0,0,0,0.1),0_0.125rem_0.25rem_-0.125rem_rgba(0,0,0,0.1)] z-[999] overflow-hidden hidden">
                <div class="flex flex-col gap-0.5 p-1">
                    <div class="flex items-center justify-between px-2 pt-1 pb-1 bg-sm-base-background sticky top-0">
                        <p class="text-xs leading-4 text-sm-base-muted-foreground m-0">${_x('Recent searches', 'dropdown header', 'smart-manager-for-wp-e-commerce')}</p>
                        <button type="button" id="sm-clear-recent-searches" class="text-xs leading-4 font-medium text-sm-base-primary hover:underline cursor-pointer bg-transparent border-0 px-1 py-0">${_x('Clear all', 'button', 'smart-manager-for-wp-e-commerce')}</button>
                    </div>
                    <div class="max-h-[15rem] overflow-y-auto sm-custom-scrollbar">
                        ${itemsHtml}
                    </div>
                </div>
            </div>`;
    }

    // Function to show the recent searches dropdown
    SmartManager.prototype.showRecentSearchesDropdown = function () {
        if (jQuery('#sm-recent-searches-dropdown').length === 0) {
            // Inject the dropdown if not exists
            const dropdownHtml = window.smart_manager.getRecentSearchesDropdownHtml();
            if (dropdownHtml) {
                jQuery('#sm_search_content_parent').parent().parent().css('position', 'relative');
                jQuery('#sm_search_content_parent').parent().append(dropdownHtml);
            }
        }
        jQuery('#sm-recent-searches-dropdown').removeClass('hidden');
    }

    // Function to clear all recent searches via AJAX
    SmartManager.prototype.clearRecentSearches = function () {
        let params = {};
        params.data = {
            cmd: 'clear_recent_searches',
            active_module: window.smart_manager.dashboardKey,
            security: window.smart_manager.saCommonNonce
        };
        params.showLoader = false;

        window.smart_manager.sendRequest(params, function (response) {
            if (window.smart_manager.isJSON(response)) {
                response = JSON.parse(response);
                if (response && response.ACK === 'Success') {
                    // Clear for current dashboard only
                    window.smart_manager.recentSimpleSearches[window.smart_manager.dashboardKey] = [];
                    jQuery('#sm-recent-searches-dropdown').remove();
                    window.smart_manager.hideRecentSearchesDropdown();
                }
            }
        });
    }

    // Function to update the recent searches list via AJAX
    SmartManager.prototype.updateRecentSearches = function (searchTerm) {
        if (!searchTerm || searchTerm.trim() === '') {
            return;
        }

        let params = {};
        params.data = {
            cmd: 'update_recent_searches',
            active_module: window.smart_manager.dashboardKey,
            search_term: searchTerm.trim(),
            security: window.smart_manager.saCommonNonce
        };
        params.showLoader = false;

        window.smart_manager.sendRequest(params, function (response) {
            if (window.smart_manager.isJSON(response)) {
                response = JSON.parse(response);
                if (response && response.ACK === 'Success' && response.recent_searches) {
                    // Update the dashboard-specific searches
                    window.smart_manager.recentSimpleSearches[window.smart_manager.dashboardKey] = response.recent_searches;
                    // Update the dropdown HTML
                    jQuery('#sm-recent-searches-dropdown').remove();
                }
            }
        });
    }

    // Function to hide the recent searches dropdown
    SmartManager.prototype.hideRecentSearchesDropdown = function () {
        jQuery('#sm-recent-searches-dropdown').addClass('hidden');
    }

    // ========================================================================
    // TASKS VIEW ACTIONS COLUMN HANDLER
    // ========================================================================
    SmartManager.prototype.handleTasksViewActionsColumn = function (current_cell_value, coords) {
        // Use the new "Changes Made" table format for Tasks view Actions column
        let generateChangesTableContent = function () {
            let val = (window.smart_manager.isJSON(current_cell_value)) ? JSON.parse(current_cell_value) : current_cell_value;
            let tableRows = '';
            // Helper function to format field name from key (for inline edit)
            let formatFieldName = function(key) {
                // Extract the last part after the last '=' or '/'
                let parts = key.split('/');
                let lastPart = parts[parts.length - 1];
                if (lastPart.includes('=')) {
                    lastPart = lastPart.split('=').pop();
                }
                // Remove underscores and capitalize each word
                return lastPart.split('_').map(function(word) {
                    return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
                }).join(' ');
            };
             // Check if it's a bulk edit or inline edit
            let rowData = window.smart_manager.currentDashboardData[coords.row];
            if (rowData && rowData.sm_tasks_type === 'bulk_edit') {
                // Bulk edit format: object with numbered keys and action objects
                Object.keys(val).forEach(function(key) {
                    if (key === 'is_scheduled') return; // Skip is_scheduled key
                    
                    let action = val[key];
                    if (!action || typeof action !== 'object') return;
                    
                    // Get field name from displayTitles or fallback to type
                    const fieldName = (action.meta && action.meta.displayTitles && action.meta.displayTitles.field) ? action.meta.displayTitles.field : (action.type ? formatFieldName(action.type) : '-');
                    
                    // Get operator/action from displayTitles or fallback to operator
                    const operatorName = (action.meta && action.meta.displayTitles && action.meta.displayTitles.operator) ? action.meta.displayTitles.operator : (action.operator ? action.operator.replace(/_/g, ' ') : '-');
                    
                    // Get value display text or fallback to raw value
                    let valueText = '-';
                    if (action.value_display_text) {
                        valueText = action.value_display_text;
                    } else if (Array.isArray(action.value)) {
                        valueText = action.value.join(', ') || '-';
                    } else if (action.value !== undefined && action.value !== null) {
                        valueText = String(action.value);
                    }
                    
                    tableRows += `
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm-base-muted-foreground align-middle border border-gray-100">${window.smart_manager.escapeHtml(fieldName)}</td>
                            <td class="px-4 py-3 text-sm-base-muted-foreground align-middle border border-gray-100">${window.smart_manager.escapeHtml(operatorName)}</td>
                            <td class="px-4 py-3 align-middle border border-gray-100 rounded">${window.smart_manager.escapeHtml(valueText)}</td>
                        </tr>
                    `;
                });
            } else {
                // Inline edit format: array of key-value pair objects
                let inlineEditArray = Array.isArray(val) ? val : [val];
                inlineEditArray.forEach(function(item) {
                    if (!item || typeof item !== 'object') return;
                    Object.keys(item).forEach(function(key) {
                        tableRows += `
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm-base-muted-foreground align-middle border border-gray-100">${window.smart_manager.escapeHtml(formatFieldName(key))}</td>
                                <td class="px-4 py-3 text-sm-base-muted-foreground align-middle border border-gray-100">${window.smart_manager.escapeHtml('set to')}</td>
                                <td class="px-4 py-3 align-middle border border-gray-100 rounded">${window.smart_manager.escapeHtml(item[key] !== undefined && item[key] !== null ? String(item[key]) : '-')}</td>
                            </tr>
                        `;
                    });
                });
            }

            // If no valid actions found
            if (!tableRows) {
                tableRows = `
                    <tr class="hover:bg-gray-50">
                        <td colspan="3" class="px-4 py-3 text-center text-gray-500 align-middle border border-gray-100">${_x('No changes recorded', 'changes modal', 'smart-manager-for-wp-e-commerce')}</td>
                    </tr>
                `;
            }

            return `
                <div>
                    <table class="w-full border-collapse text-sm leading-normal sm-changes-made-table">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="text-sm-base-muted-foreground bg-sm-base-muted px-4 py-3 text-left tracking-wider border">${_x('Field', 'changes modal header', 'smart-manager-for-wp-e-commerce')}</th>
                                <th class="text-sm-base-muted-foreground bg-sm-base-muted px-4 py-3 text-left tracking-wider border">${_x('Action', 'changes modal header', 'smart-manager-for-wp-e-commerce')}</th>
                                <th class="text-sm-base-muted-foreground bg-sm-base-muted px-4 py-3 text-left tracking-wider border">${_x('Value', 'changes modal header', 'smart-manager-for-wp-e-commerce')}</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${tableRows}
                        </tbody>
                    </table>
                </div>
            `;
        };

        window.smart_manager.modal = {
            title: _x('Changes Made', 'modal title', 'smart-manager-for-wp-e-commerce'),
            content: generateChangesTableContent(),
            autoHide: false,
            cta: {
                title: _x('Close', 'button', 'smart-manager-for-wp-e-commerce'),
                callback: function () {
                }
            }
        }
        window.smart_manager.showModal()
    }

    // ========================================================================
    // EMPTY Tasks List UI
    // ========================================================================
    SmartManager.prototype.getEmptyTasksListUI = function () {
        const svg = `<svg width="135" height="135" viewBox="0 0 135 135" fill="none" xmlns="http://www.w3.org/2000/svg">
            <g style="mix-blend-mode: luminosity">
                <path d="M127.288 60.5214L123.868 69.2518L106.027 114.812L88.1191 107.85L105.868 62.6629L109.455 53.5456C114.312 41.0897 132.161 48.051 127.288 60.5214Z" fill="url(#paint0_linear_304_16804)"/>
                <path d="M127.289 60.5214L123.869 69.2518C118.563 64.6708 112.574 62.4145 105.869 62.6629L109.455 53.5456C114.313 41.0897 132.162 48.051 127.289 60.5214Z" fill="url(#paint1_linear_304_16804)"/>
                <path d="M125.033 66.2801L127.288 60.5214C132.161 48.051 114.312 41.0897 109.455 53.5456L107.053 59.6592C113.871 59.0171 119.843 61.3009 125.033 66.2801Z" fill="url(#paint2_linear_304_16804)"/>
                <path d="M106.027 114.81C105.596 115.92 104.935 116.926 104.091 117.771L98.3021 123.58L91.0775 130.828C90.0855 131.822 88.383 131.161 88.322 129.758L87.8853 119.533L87.5357 111.34C87.4836 110.147 87.6748 108.958 88.1057 107.849C90.1279 102.643 108.049 109.605 106.027 114.81Z" fill="url(#paint3_linear_304_16804)"/>
                <path d="M88.12 107.85L88.0293 108.059C88.0595 107.985 88.0747 107.925 88.105 107.85L88.12 107.85Z" fill="url(#paint4_linear_304_16804)"/>
                <path d="M98.2963 123.58L91.0698 130.825C90.0872 131.815 88.3875 131.153 88.3221 129.761L87.8906 119.532C89.7591 119.086 91.7894 119.177 93.7271 119.928C95.6647 120.678 97.2256 121.991 98.2963 123.58Z" fill="url(#paint5_linear_304_16804)"/>
                <path d="M80.4941 131.696H74.207C73.3789 131.696 72.707 131.024 72.707 130.196C72.707 129.367 73.3789 128.696 74.207 128.696H80.4941C81.3223 128.696 81.9941 129.367 81.9941 130.196C81.9941 131.024 81.3223 131.696 80.4941 131.696ZM63.5781 131.696H46.3232C41.9629 131.696 38.415 128.148 38.415 123.788C38.415 119.427 41.9629 115.879 46.3232 115.879H66.1943C68.8457 115.879 71.002 113.722 71.002 111.071C71.002 108.42 68.8457 106.264 66.1943 106.264H28.4912C27.6631 106.264 26.9912 105.592 26.9912 104.764C26.9912 103.935 27.6631 103.264 28.4912 103.264H66.1943C70.499 103.264 74.002 106.766 74.002 111.071C74.002 115.377 70.499 118.879 66.1943 118.879H46.3232C43.6172 118.879 41.415 121.081 41.415 123.788C41.415 126.494 43.6172 128.696 46.3232 128.696H63.5781C64.4062 128.696 65.0781 129.367 65.0781 130.196C65.0781 131.024 64.4062 131.696 63.5781 131.696ZM19.4834 106.264H15.0977C14.2695 106.264 13.5977 105.592 13.5977 104.764C13.5977 103.935 14.2695 103.264 15.0977 103.264H19.4834C20.3115 103.264 20.9834 103.935 20.9834 104.764C20.9834 105.592 20.3115 106.264 19.4834 106.264Z" fill="#A0A8BA"/>
                <path d="M75.7768 14.0781L93.4852 66.9436C94.9277 71.25 92.606 75.9103 88.2997 77.3528L35.4226 95.0651C31.1162 96.5076 26.4559 94.186 25.0134 89.8796L7.30501 37.0141C5.86251 32.7078 8.18412 28.0474 12.4905 26.6049L65.3676 8.89266C69.6739 7.45016 74.3343 9.77177 75.7768 14.0781Z" fill="url(#paint6_linear_304_16804)"/>
                <path d="M21.4157 18.4922L24.5017 27.7049C25.099 29.488 24.1377 31.4177 22.3546 32.0149C20.5714 32.6122 18.6417 31.6509 18.0445 29.8678L14.9585 20.6552C14.3612 18.8721 15.3225 16.9424 17.1056 16.3451C18.8887 15.7478 20.8184 16.7091 21.4157 18.4922Z" fill="url(#paint7_linear_304_16804)"/>
                <path d="M34.2145 14.2047L37.3005 23.4174C37.8978 25.2005 36.9365 27.1302 35.1534 27.7275C33.3703 28.3248 31.4406 27.3635 30.8433 25.5804L27.7573 16.3677C27.16 14.5846 28.1213 12.6549 29.9044 12.0576C31.6875 11.4603 33.6172 12.4216 34.2145 14.2047Z" fill="url(#paint8_linear_304_16804)"/>
                <path d="M47.0153 9.91726L50.1013 19.1299C50.6986 20.913 49.7373 22.8427 47.9542 23.44C46.1711 24.0373 44.2414 23.076 43.6441 21.2929L40.5581 12.0802C39.9608 10.2971 40.9221 8.36743 42.7052 7.77014C44.4883 7.17285 46.418 8.13415 47.0153 9.91726Z" fill="url(#paint9_linear_304_16804)"/>
                <path d="M60.7563 19.1527C60.3945 19.2769 60.0326 19.3284 59.6708 19.3284C58.2441 19.3284 56.9208 18.4294 56.4452 17.0023L53.3541 7.79124C52.7545 6.01283 53.716 4.07945 55.5045 3.48025C55.8663 3.35607 56.2282 3.30408 56.59 3.30408C58.0063 3.30408 59.34 4.20363 59.8155 5.63019L62.8964 14.8418C63.4959 16.6303 62.5345 18.553 60.7563 19.1527Z" fill="url(#paint10_linear_304_16804)"/>
                <path d="M50.8125 73.328C61.4214 73.328 70.0215 64.7279 70.0215 54.119C70.0215 43.5102 61.4214 34.91 50.8125 34.91C40.2037 34.91 31.6035 43.5102 31.6035 54.119C31.6035 64.7279 40.2037 73.328 50.8125 73.328Z" fill="url(#paint11_linear_304_16804)"/>
                <path d="M59.1661 62.4535C58.0573 63.5624 56.2704 63.5625 55.1617 62.4538L51.2292 58.5213L47.297 62.4535C46.1881 63.5623 44.4013 63.5624 43.2926 62.4537C42.1838 61.345 42.1759 59.5501 43.2848 58.4413L47.217 54.5091L43.2925 50.5846C42.1838 49.4758 42.1759 47.681 43.2848 46.5722C44.3876 45.4693 46.1936 45.4608 47.305 46.5722L51.2295 50.4966L55.1539 46.5722C56.2627 45.4633 58.0653 45.4634 59.1741 46.5722C60.2897 47.6878 60.2708 49.4797 59.1661 50.5844L55.2416 54.5088L59.1741 58.4413C60.2829 59.55 60.2746 61.3451 59.1661 62.4535Z" fill="url(#paint12_linear_304_16804)"/>
                <path d="M19.5823 120.933C20.387 120.419 21.463 120.666 21.977 121.471C22.491 122.275 22.2554 123.344 21.4507 123.858C20.6462 124.372 19.5662 124.143 19.0522 123.339C18.5382 122.534 18.7778 121.447 19.5823 120.933Z" fill="#D6DCE8"/>
            </g>
            <defs>
                <linearGradient id="paint0_linear_304_16804" x1="88.1191" y1="80.9009" x2="128.123" y2="80.9009" gradientUnits="userSpaceOnUse">
                <stop stop-color="#D6DCE8"/>
                <stop offset="1" stop-color="#F1F3F9"/>
                </linearGradient>
                <linearGradient id="paint1_linear_304_16804" x1="89.4448" y1="57.96" x2="130.665" y2="58.1765" gradientUnits="userSpaceOnUse">
                <stop stop-color="#AAB2C5"/>
                <stop offset="1" stop-color="#D6DCE8"/>
                </linearGradient>
                <linearGradient id="paint2_linear_304_16804" x1="91.486" y1="56.4817" x2="130.539" y2="56.6869" gradientUnits="userSpaceOnUse">
                <stop stop-color="#989FB0"/>
                <stop offset="1" stop-color="#AAB2C5"/>
                </linearGradient>
                <linearGradient id="paint3_linear_304_16804" x1="85.0215" y1="115.85" x2="102.943" y2="122.812" gradientUnits="userSpaceOnUse">
                <stop stop-color="#AAB2C5"/>
                <stop offset="1" stop-color="#D6DCE8"/>
                </linearGradient>
                <linearGradient id="paint4_linear_304_16804" x1="88.0363" y1="107.96" x2="88.1263" y2="107.961" gradientUnits="userSpaceOnUse">
                <stop stop-color="#D6DCE8"/>
                <stop offset="1" stop-color="#F1F3F9"/>
                </linearGradient>
                <linearGradient id="paint5_linear_304_16804" x1="86.3381" y1="123.538" x2="96.7465" y2="127.582" gradientUnits="userSpaceOnUse">
                <stop stop-color="#989FB0"/>
                <stop offset="1" stop-color="#AAB2C5"/>
                </linearGradient>
                <linearGradient id="paint6_linear_304_16804" x1="6.87685" y1="51.979" x2="93.9128" y2="51.979" gradientUnits="userSpaceOnUse">
                <stop stop-color="#D6DCE8"/>
                <stop offset="1" stop-color="#F1F3F9"/>
                </linearGradient>
                <linearGradient id="paint7_linear_304_16804" x1="14.7815" y1="24.18" x2="24.6785" y2="24.18" gradientUnits="userSpaceOnUse">
                <stop stop-color="#989FB0"/>
                <stop offset="1" stop-color="#AAB2C5"/>
                </linearGradient>
                <linearGradient id="paint8_linear_304_16804" x1="27.5796" y1="19.893" x2="37.4776" y2="19.893" gradientUnits="userSpaceOnUse">
                <stop stop-color="#989FB0"/>
                <stop offset="1" stop-color="#AAB2C5"/>
                </linearGradient>
                <linearGradient id="paint9_linear_304_16804" x1="40.3807" y1="15.605" x2="50.2787" y2="15.605" gradientUnits="userSpaceOnUse">
                <stop stop-color="#989FB0"/>
                <stop offset="1" stop-color="#AAB2C5"/>
                </linearGradient>
                <linearGradient id="paint10_linear_304_16804" x1="53.1754" y1="11.3159" x2="63.0744" y2="11.3159" gradientUnits="userSpaceOnUse">
                <stop stop-color="#989FB0"/>
                <stop offset="1" stop-color="#AAB2C5"/>
                </linearGradient>
                <linearGradient id="paint11_linear_304_16804" x1="31.6025" y1="54.119" x2="70.0215" y2="54.119" gradientUnits="userSpaceOnUse">
                <stop stop-color="#989FB0"/>
                <stop offset="1" stop-color="#AAB2C5"/>
                </linearGradient>
                <linearGradient id="paint12_linear_304_16804" x1="42.4567" y1="54.513" x2="60.0027" y2="54.513" gradientUnits="userSpaceOnUse">
                <stop stop-color="#D6DCE8"/>
                <stop offset="1" stop-color="#F1F3F9"/>
                </linearGradient>
            </defs>
        </svg>`;
        return `
            <div class="flex flex-col items-center justify-center py-16 px-6 sm-empty-tasks-list-section">
                <div class="mb-6">
                    ${svg}
                </div>
                <h2 class="m-0 text-2xl font-semibold text-gray-900 mb-3 text-center">
                    ${_x('Your task list is empty', 'empty state title', 'smart-manager-for-wp-e-commerce')}
                </h2>
                <p class="m-0 text-base text-gray-500 text-center max-w-md leading-relaxed">
                    ${_x('Any inline edits or bulk updates you perform will be tracked here in real time.', 'empty state description', 'smart-manager-for-wp-e-commerce')}
                </p>
            </div>
        `;
    }

    if (typeof window.smart_manager_pro === 'undefined') {
        window.smart_manager = new SmartManagerPro();
    }
})(window);

jQuery(document).on('smart_manager_init','#sm_editor_grid', function() {
    window.smart_manager.date_params = {}; //params for date filter

    let additionalDateOperators = {increase_date_by:_x('increase by', "bulk edit action - 'date' fields", 'smart-manager-for-wp-e-commerce'), decrease_date_by:_x('decrease by', "bulk edit action - 'date' fields", 'smart-manager-for-wp-e-commerce')};

    window.smart_manager.batch_update_actions = {
		'numeric': {increase_by_per:_x('increase by %', "bulk edit action - 'number' fields", 'smart-manager-for-wp-e-commerce'), decrease_by_per:_x('decrease by %', "bulk edit action - 'number' fields", 'smart-manager-for-wp-e-commerce'), increase_by_num:_x('increase by number', "bulk edit action - 'number' fields", 'smart-manager-for-wp-e-commerce'), decrease_by_num:_x('decrease by number', "bulk edit action - 'number' fields", 'smart-manager-for-wp-e-commerce')},
		'image': {},
        	'multipleImage': {},
		'datetime': Object.assign({set_datetime_to:_x('set datetime to', "bulk edit action - 'datetime' fields", 'smart-manager-for-wp-e-commerce'), set_date_to:_x('set date to', "bulk edit action - 'datetime' fields", 'smart-manager-for-wp-e-commerce'), set_time_to:_x('set time to', "bulk edit action - 'datetime' fields", 'smart-manager-for-wp-e-commerce')}, additionalDateOperators),
        	'date': Object.assign({set_date_to:_x('set date to', "bulk edit action - 'date' fields", 'smart-manager-for-wp-e-commerce')},additionalDateOperators),
        	'time': Object.assign({set_time_to:_x('set time to', "bulk edit action - 'time' fields", 'smart-manager-for-wp-e-commerce')},additionalDateOperators),
		'dropdown': {},
		'multilist': {add_to:_x('add to', "bulk edit action - 'multiselect list' fields", 'smart-manager-for-wp-e-commerce'), remove_from:_x('remove from', "bulk edit action - 'multiselect list' fields", 'smart-manager-for-wp-e-commerce')},
        'serialized': {},
		'text': {prepend:_x('prepend', "bulk edit action - 'text' fields", 'smart-manager-for-wp-e-commerce'), append:_x('append', "bulk edit action - 'text' fields", 'smart-manager-for-wp-e-commerce'), search_and_replace:_x('search & replace', "bulk edit action - 'text' fields", 'smart-manager-for-wp-e-commerce')}
	}

    	let types_exclude_set_to = ['datetime', 'date', 'time']

	Object.keys(window.smart_manager.batch_update_actions).forEach(key => {
        let setToObj = (types_exclude_set_to.includes(key)) ? {} : {set_to: _x('set to', 'bulk edit action', 'smart-manager-for-wp-e-commerce')}
		window.smart_manager.batch_update_actions[key] = Object.assign(setToObj, window.smart_manager.batch_update_actions[key],{copy_from: _x('copy from', 'bulk edit action', 'smart-manager-for-wp-e-commerce')}, {copy_from_field: _x('copy from field', 'bulk edit action', 'smart-manager-for-wp-e-commerce')});
	});
})
.on('sm_top_bar_loaded', '#sm_top_bar', function() {

        jQuery(document).off('click', '.sm_date_range_container .smart-date-icon').on('click', '.sm_date_range_container .smart-date-icon', function() {

            if( jQuery('.sm_date_range_container .dropdown-menu').is(':visible') === false ){
                jQuery('.sm_date_range_container .dropdown-menu').show();
            } else {
                jQuery('.sm_date_range_container .dropdown-menu').hide();
            }
        });

        jQuery(document).off('click', ':not(.sm_date_range_container .dropdown-menu)').on('click', ':not(.sm_date_range_container .dropdown-menu)', function( e ){
            if ( jQuery(e.target).hasClass('smart-date-icon') === false && jQuery('.sm_date_range_container .dropdown-menu').is(':visible') === true ) {
                jQuery('.sm_date_range_container .dropdown-menu').hide();
            }
        });

        jQuery(document).off('click', '.sm_date_range_container .dropdown-menu li a').on('click', '.sm_date_range_container .dropdown-menu li a', function(e) {
            e.preventDefault();

            jQuery('.sm_date_range_container .dropdown-menu').hide();
            window.smart_manager.proSelectDate(jQuery(this).attr('data-key'));
        });

        //Code for initializing the date picker
        jQuery('.sm_date_range_container input.sm_date_selector').Zebra_DatePicker({
                                                                                                    format: 'd M Y H:i:s',
                                                                                                    // format: 'dd-mm-yy H:i:s',
                                                                                                    show_icon: false,
                                                                                                    show_select_today: false,
                                                                                                    readonly_element: false,
                                                                                                    default_position: 'below',
                                                                                                    lang_clear_date: 'Clear dates',
                                                                                                    onClear: window.smart_manager.clearDateFilter,
                                                                                                    start_date: new Date( new Date().setHours(0, 0, 0) ),
                                                                                                    onSelect: function(fdate, jsdate) {
                                                                                                        jQuery(this).change();
                                                                                                        let id = jQuery(this).attr('id'),
                                                                                                            selected_date_obj = new Date(fdate),
                                                                                                            params = {'start_date_formatted':'',
                                                                                                                        'start_date_default_format':'',
                                                                                                                        'end_date_formatted':'',
                                                                                                                        'end_date_default_format':''};

                                                                                                        if( id == 'sm_date_selector_start_date' ) { //if end_date is not set

                                                                                                            params.start_date_formatted = fdate;
                                                                                                            params.start_date_default_format = jsdate;

                                                                                                            var end_date = jQuery('#sm_date_selector_end_date').val(),
                                                                                                                end_time = '';

                                                                                                            if( end_date == '' ) {
                                                                                                                end_date_obj = new Date( selected_date_obj.getFullYear(), selected_date_obj.getMonth(), ( selected_date_obj.getDate() + 29 ) );
                                                                                                                end_time =  '23:59:59';
                                                                                                            } else {
                                                                                                                end_date_obj = new Date(end_date);
                                                                                                                end_time =  window.smart_manager.strPad(end_date_obj.getHours(), 2) + ':' + window.smart_manager.strPad(end_date_obj.getMinutes(), 2) + ':' + window.smart_manager.strPad(end_date_obj.getSeconds(), 2);
                                                                                                            }
                                                                                                            var y = end_date_obj.getFullYear() + '',
                                                                                                                m = end_date_obj.getMonth(),
                                                                                                                d = window.smart_manager.strPad(end_date_obj.getDate(), 2);

                                                                                                            params.end_date_formatted = d + ' ' + window.smart_manager.month_names_short[m] + ' ' + y + ' ' + end_time;
                                                                                                            params.end_date_default_format = y + '-' + window.smart_manager.strPad((m+1), 2) + '-' + d + ' ' + end_time;

                                                                                                            if( end_date == '' ) {
                                                                                                                end_date_datepicker = jQuery('.sm_date_range_container input.end-date').data('Zebra_DatePicker');
                                                                                                                end_date_datepicker.set_date(params.end_date_formatted);
                                                                                                                end_date_datepicker.update({'current_date': new Date(params.end_date_default_format)});
                                                                                                            }


                                                                                                        } else if( id == 'sm_date_selector_end_date' ) { //if start_date is not set

                                                                                                            params.end_date_formatted = fdate;
                                                                                                            params.end_date_default_format = jsdate;

                                                                                                            var start_date = jQuery('#sm_date_selector_start_date').val(),
                                                                                                                start_time = '';

                                                                                                            if( start_date == '' ) {
                                                                                                                start_date_obj = new Date( selected_date_obj.getFullYear(), selected_date_obj.getMonth(), ( selected_date_obj.getDate() - 29 ) );
                                                                                                                start_time = '23:59:59';
                                                                                                            } else {
                                                                                                                start_date_obj = new Date(start_date);
                                                                                                                start_time = window.smart_manager.strPad(start_date_obj.getHours(), 2) + ':' + window.smart_manager.strPad(start_date_obj.getMinutes(), 2) + ':' + window.smart_manager.strPad(start_date_obj.getSeconds(), 2);
                                                                                                            }
                                                                                                            var y = start_date_obj.getFullYear() + '',
                                                                                                                m = start_date_obj.getMonth(),
                                                                                                                d = window.smart_manager.strPad(start_date_obj.getDate(), 2);


                                                                                                            params.start_date_formatted = d + ' ' + window.smart_manager.month_names_short[m] + ' ' + y + ' ' + start_time;
                                                                                                            params.start_date_default_format = y + '-' + window.smart_manager.strPad((m+1), 2) + '-' + d + ' ' + start_time;

                                                                                                            if( start_date == '' ) {
                                                                                                                start_date_datepicker = jQuery('.sm_date_range_container input.start-date').data('Zebra_DatePicker');
                                                                                                                start_date_datepicker.set_date(params.start_date_formatted);
                                                                                                                start_date_datepicker.update({'current_date': new Date(params.start_date_default_format)});
                                                                                                            }
                                                                                                        }

                                                                                                        window.smart_manager.sm_handle_date_filter(params);
                                                                                                    }
                                                                                                });

        if( typeof( window.smart_manager.date_params.date_filter_params ) != 'undefined' && window.smart_manager.isJSON( window.smart_manager.date_params.date_filter_params ) ) {

            selected_dates = JSON.parse(window.smart_manager.date_params.date_filter_params);

            start_date_datepicker = jQuery('.sm_date_range_container input.start-date').data('Zebra_DatePicker');
            start_date_datepicker.set_date(selected_dates.start_date_formatted);
            start_date_datepicker.update({'current_date': new Date(selected_dates.start_date_default_format)});

            end_date_datepicker = jQuery('.sm_date_range_container input.end-date').data('Zebra_DatePicker');
            end_date_datepicker.set_date(selected_dates.end_date_formatted);
            end_date_datepicker.update({'current_date': new Date(selected_dates.end_date_default_format)});

        }

    })
// Code for handling the undo & delete tasks functionality
.on('sm_show_tasks_change', '#sm_editor_grid', function(){
    let simpleSearchCond = (window.smart_manager.searchType === 'simple') ? window.smart_manager.simpleSearchText : '';
    let advancedSearchCond = (window.smart_manager.searchType === 'advanced') ? window.smart_manager.advancedSearchQuery : '';
    let type = (window.smart_manager.isTasksEnabled() === 1) ? 'postType' : 'task';
    window.smart_manager.savedSearchConds[type] = {
        simpleSearchCond,
        advancedSearchCond
    };
    if((typeof window.smart_manager.dirtyRowColIds !== 'undefined') && Object.getOwnPropertyNames(window.smart_manager.dirtyRowColIds).length > 0){
		window.smart_manager.confirmUnsavedChanges({'yesCallback': window.smart_manager.showTasks, 'noCallback': window.smart_manager.handleShowTasks})
    }else if("undefined" !== typeof(window.smart_manager.showTasks) && "function" === typeof(window.smart_manager.showTasks)){
        window.smart_manager.showTasks();
    }
})

.off( 'click', ".sm_top_bar_action_btns #sm_beta_undo_selected, .sm_top_bar_action_btns #sm_beta_undo_all_tasks").on( 'click', ".sm_top_bar_action_btns #sm_beta_undo_selected, .sm_top_bar_action_btns #sm_beta_undo_all_tasks", function(){
   let importedTasksStatus = window.smart_manager.getImportedTasksDataFromSelectedIds(window.smart_manager.selectedRows, window.smart_manager.currentDashboardData)
   let note = `<div class="mt-2 text-gray-500" style="font-style: italic;"><strong class="text-sm-base-foreground">${_x('Note:', 'modal title', 'smart-manager-for-wp-e-commerce')}</strong> ${_x('Records of type “Imported” do not support the Undo action', 'modal content', 'smart-manager-for-wp-e-commerce')}. </div>`
   if (importedTasksStatus && importedTasksStatus.hasOwnProperty('allImported') && importedTasksStatus.hasOwnProperty('someImported')) {
        if(true === importedTasksStatus.allImported) {
            //Show Notification.
            window.smart_manager.notification = {
                message: _x('Records of type “Imported” do not support the Undo action.','notification', 'smart-manager-for-wp-e-commerce')
            }
            window.smart_manager.showNotification();
            return;
       } else if((true === importedTasksStatus.someImported && false === importedTasksStatus.allImported)) {
            //Show Modal.
            window.smart_manager.displayUndoTaskModal(jQuery(this).attr('id'), jQuery(this).text(), note);
            return;
       }
   }
   window.smart_manager.displayUndoTaskModal(jQuery(this).attr('id'), jQuery(this).text(), (( 'sm_beta_undo_all_tasks' === jQuery(this).attr('id')) ? note : '') );
})
// Code for handling renaming of column titles
.off('focusout','.sm-title-input').on('focusout','.sm-title-input', function(e){
    e.target.readOnly = true;
    e.target.classList.remove('sm-column-title-input-edit')
    let parent = e.target.closest('li');
    if(!parent) return;
    let keyInput = parent.querySelector(".js-column-key");
    if(!keyInput) return;

    if(!e.target.value){ //handling for empty values
        (window.smart_manager.editedColumnTitles.hasOwnProperty(keyInput.value)) ? delete window.smart_manager.editedColumnTitles[keyInput.value] : ''
        return;
    }

    let titleInput = parent.querySelector(".js-column-title");
    if(!titleInput) return;
    if(titleInput.value == e.target.value) return;
    window.smart_manager.editedColumnTitles[keyInput.value] = e.target.value;
})
//Code to handle dashboard change in eligible dashboards modal.
.off('change', '#eligible_dashboards_select').on('change', '#eligible_dashboards_select',function(){
    let savedSearchParams = window.smart_manager.findSavedSearchBySlug(window.smart_manager.eligibleDashboardSavedSearch);// need to change.
    if((jQuery(this).val()) !== (savedSearchParams.parent_post_type)){
        savedSearchParams.params.search_params.params = window.smart_manager.getPostsColumnsFromQuery(savedSearchParams.params.search_params.params);
    }
    window.smart_manager.advancedSearchQuery = savedSearchParams.params.search_params.params;
    window.smart_manager.savedSearchParams = savedSearchParams.params.search_params;
    window.smart_manager.loadingDashboardForsavedSearch = true;
    window.smart_manager.savedSearchDashboardKey = jQuery(this).val();
    window.smart_manager.savedSearchDashboardName = jQuery( "#eligible_dashboards_select option:selected" ).text();
    jQuery("#sm_dashboard_select").val(window.smart_manager.eligibleDashboardSavedSearch).trigger('change');
    jQuery(".sm-modal-close").trigger("click");
    window.smart_manager.eligibleDashboardSavedSearch="";
})

// Code to handle undo action from inline edit success message.
.off( 'click', '#undo_action' ).on( 'click', '#undo_action' ,function(e){
    e.preventDefault();
    window.smart_manager.selectedRows = [0]
    let taskId = jQuery(this).data("task-id");
    window.smart_manager.taskId = (taskId) ? taskId : 0;
    if("undefined" !== typeof(window.smart_manager.taskActionsModal) && "function" === typeof(window.smart_manager.taskActionsModal)){
        window.smart_manager.taskActionsModal({taskInlineEditMessage: 'last update?'});
    }
})
//code to delete saved search.
.off('mousedown', ".dashboard-combobox-saved-search-delete").on('mousedown', ".dashboard-combobox-saved-search-delete", function(e){
    if (e.button !== 0) {
        return;
    }
    e.preventDefault();
    let view_name = jQuery(this).attr('view_name');
    if((!view_name) || (!view_name.length)){
        return;
    }
    let params = {};
    params.btnParams = {}
    params.title = '<span class="sm-error-icon"><span class="dashicons dashicons-warning" style="vertical-align: text-bottom;"></span>&nbsp;'+_x('Attention!', 'modal title', 'smart-manager-for-wp-e-commerce')+'</span>';
    params.content = '<span style="font-size: 1.2em;">'+_x('Do you really want to', 'modal content', 'smart-manager-for-wp-e-commerce')+' <span class="sm-error-icon"><strong class="text-sm-base-foreground">'+_x('delete', 'modal content', 'smart-manager-for-wp-e-commerce')+'</strong></span> '+_x(`"${view_name}" saved search?`, 'modal content', 'smart-manager-for-wp-e-commerce')+'</span>';
    params.titleIsHtml = true;
    params.height = 200;
    if ( typeof (window.smart_manager.deleteView) !== "undefined" && typeof (window.smart_manager.deleteView) === "function" ) {
        params.btnParams.yesCallbackParams = {view_slug:jQuery(this).attr('view_slug'),success_msg:`${view_name} saved search deleted successfully!`}
        params.btnParams.yesCallback = window.smart_manager.deleteView;
    }
    window.smart_manager.showConfirmDialog(params);
    jQuery('#sm_select2_childs_section').removeClass("visible");
})
// Toggle the floating text box visibility on save button click in bulk edit section.
.off('click','#sm_bulk_edit_save_btn').on('click','#sm_bulk_edit_save_btn', function(e){
    document.getElementById('sm-bulk-edit-save-floating-box')?.classList?.toggle('hidden');
    document.getElementById('bulk_edit_title')?.focus()
})
//validation on create saved bulk edits input field.
.off('input','#bulk_edit_title').on('input','#bulk_edit_title', function(e){
    let errorDiv = document.getElementById('sm-saved-bulk-edit-validation');
    let input = document.getElementById('bulk_edit_title');
    if((!errorDiv) || ('undefined' === typeof (errorDiv))){
        return
    }
    if (this.value.trim() === '') {
        errorDiv.textContent = _x('Name cannot be empty.', 'saved bulk edit error msg', 'smart-manager-for-wp-e-commerce');
        errorDiv.classList.remove('hidden')
        errorDiv.classList.add('text-error')
        input.classList.add('border-red')
    } else {
        errorDiv.textContent = '';
        errorDiv.classList.add('hidden');
        errorDiv.classList.remove('text-error')
        input.classList.remove('border-red')
    }
})
.off('click','.save-bulk-edit-actions .be-save-close').on('click','.save-bulk-edit-actions .be-save-close', function(e){
    document.getElementById('sm-bulk-edit-save-floating-box')?.classList?.add('hidden');
})
//Hide save bulk edit actions floating box on click outside it.
.on('click', function (event) {
    if (!jQuery(event.target).closest('#sm_bulk_edit_save_btn').length){
        window.smart_manager?.hideElementOnClickOutside(event, "sm-bulk-edit-save-floating-box");
    }
})
//show Schedule Export CSV modal.
.off( 'click', "#sm_export_csv #sm_schedule_export").on('click','#sm_export_csv #sm_schedule_export',function (event) {
    window.smart_manager.modal = {
        title:
            /* translators: %s: Schedule Export CSV modal title */
            _x(`Schedule ${window.smart_manager?.dashboardDisplayName || ''} Export CSV`, 'modal title', 'smart-manager-for-wp-e-commerce'),
        content: window.smart_manager.scheduleCSVExportModalHTML(),
        autoHide: false,
        cta: {
            closeModalOnClick: false,
            title: _x('Create', 'button', 'smart-manager-for-wp-e-commerce'),
            callback: function() {
                // Retrieve form data from the modal form
                if("undefined" !== typeof(window.smart_manager.validateAndGetScheduleExportFormData) && "function" === typeof(window.smart_manager.validateAndGetScheduleExportFormData)){
                    let formData = window.smart_manager.validateAndGetScheduleExportFormData();
                    if(formData){
                        formData.is_new_schedule_export = true;
                        formData.scheduledExportActionAdminUrl = window.smart_manager?.scheduledExportActionAdminUrl || ''
                        window.smart_manager.hideModal();
                        window.smart_manager.hidePannelDialog = false;
                        window.smart_manager.generateCsvExport({
                            isScheduledExport: true,
                            scheduleParams: formData,
                            scheduleExportAjaxCallback: window.smart_manager.scheduleExportAjaxCallback,
                        })
                    }
                }
            }
        },
        onCreate: function(){
            if("undefined" !== typeof(window.smart_manager.initOrderStatusesSelect2) && "function" === typeof(window.smart_manager.initOrderStatusesSelect2)){
                window.smart_manager.initOrderStatusesSelect2();
            }
            if("undefined" !== typeof(window.smart_manager.scheduleDatePicker) && "function" === typeof(window.smart_manager.scheduleDatePicker)){
                window.smart_manager.scheduleDatePicker('#sm_schedule_export_start_time');
            }
        },
        closeCTA: { title: _x('Cancel', 'button', 'smart-manager-for-wp-e-commerce'),
            callback: function() {
                window.smart_manager.hidePannelDialog = false;
            }
        },
        contentClass: "sm-scheduled-export-modal"
    }
    window.smart_manager.showModal();
})
.off( 'click', "#sm_manage_schedule_export").on( 'click', "#sm_manage_schedule_export", function(e){
    if(window.smart_manager.hasOwnProperty('scheduledExportActionAdminUrl')){
        window.open(window.smart_manager.scheduledExportActionAdminUrl, '_blank');
    }
})
.off( 'change', '#sm_schedule_export_interval, #sm_schedule_export_email' ).on( 'change', '#sm_schedule_export_interval, #sm_schedule_export_email', function() {
    jQuery( this ).removeClass( 'border-red' );
})
.off( 'click', '#sm_schedule_export_start_time' ).on( 'click', '#sm_schedule_export_start_time', function() {
    jQuery( this ).removeClass( 'border-red' );
})
//Highlight input area in focus.
.off( 'focus', '#sm_ai_prompt_input' ).on( 'focus', '#sm_ai_prompt_input', function() {
    jQuery('.sm-ai-prompt-input-parent').addClass( 'active' );
} )
.off( 'blur', '#sm_ai_prompt_input' ).on( 'blur', '#sm_ai_prompt_input', function() {
	jQuery('.sm-ai-prompt-input-parent').removeClass( 'active' );
} )
//Open AI assistant modal.
.off( 'click', '.sm-ai-assistant-icon' ).on('click', '.sm-ai-assistant-icon', function (event) {
    if(!('product'===window.smart_manager.dashboardKey || ('product'===window.smart_manager.viewPostTypes[window.smart_manager.dashboardKey]))){
        window.smart_manager.showNotificationDialog(
            _x('Note:', 'modal title', 'smart-manager-for-wp-e-commerce'),
            `<div class="text-gray-500" style="font-style: italic;">
                ${_x(`This feature is currently not available for <strong class="text-sm-base-foreground">${window.smart_manager.dashboardName}</strong> dashboard. Please consider submitting a`, 'feature request modal content', 'smart-manager-for-wp-e-commerce')} <a href="https://www.storeapps.org/contact-us/?utm_source=sm&utm_medium=in_app_modal&utm_campaign=feature_request" target="_blank">${_x('feature request', 'modal content', 'smart-manager-for-wp-e-commerce')}</a> ${_x('to help us prioritize this enhancement.', 'feature request modal content', 'smart-manager-for-wp-e-commerce')}
            </div>`
        )
        return;
    }
    if((!sm_beta_params.hasOwnProperty('is_ai_integration_enabled')||(1!==parseInt(sm_beta_params.is_ai_integration_enabled)))){
        window.smart_manager.notification = {
            message: _x('Please configure AI Integration settings under Settings > General Settings to use this feature.','notification', 'smart-manager-for-wp-e-commerce')
        }
        window.smart_manager.showNotification();
        return;
    }
    if(window.smart_manager.hasOwnProperty('isViewContainSearchParams') && window.smart_manager.isViewContainSearchParams===true){
        window.smart_manager.notification = {
            message: _x('Cannot switch search when using Custom Views.','notification', 'smart-manager-for-wp-e-commerce')
        }
        window.smart_manager.showNotification();
        return;
    }
    window.smart_manager.modal = {
        title: _x('Your Smart AI Assistant is here!','ai assistant modal title','smart-manager-for-wp-e-commerce'),
        content: `
        <div class="sm-ai-assistant-modal">
            <div class="sm-ai-section sm-ai-command-section">
                <p class="sm-ai-description text-gray-700 text-sm font-medium pb-2 m-0">
                ${_x('Tell me what you need, and I’ll find it for you.','ai assistant modal content','smart-manager-for-wp-e-commerce')}
                </p>
            </div>
            <div class="sm-ai-input-container mt-2 command-mode">
                <div class="sm-ai-prompt-input-parent flex items-center w-full border border-gray-300 rounded-lg shadow-sm focus-within:border-indigo-500">
                    <textarea id="sm_ai_prompt_input" type="text"
                        class="flex-1 px-4 py-3 text-sm text-gray-700 bg-transparent mr-2 border-0 focus:border-none focus:shadow-none focus:outline-none"
                        placeholder="${_x('e.g. Show products with sale price greater than 100','ai assistant modal content','smart-manager-for-wp-e-commerce')}" rows="1">${ ( jQuery('#search_switch').is(':checked') && window.smart_manager.hasOwnProperty('advancedSearchPrompt') ) ? window.smart_manager.advancedSearchPrompt : ''}</textarea>
                    <button id="sm_ai_mic_btn"
                        class="flex justify-center mr-2 w-8 h-8 text-gray-500 hover:text-indigo-600 focus:outline-none focus:shadow-outline">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                        class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 18.75a6 6 0 006-6v-1.5m-6 7.5a6 6 0 01-6-6v-1.5m6 7.5v3.75m-3.75 0h7.5M12 15.75a3 3 0 01-3-3V4.5a3 3 0 116 0v8.25a3 3 0 01-3 3z" />
                        </svg>
                    </button>
                </div>
                <div class="sm-ai-assistant-error text-sm pt-3"></div>
                <p class="description mt-4"><strong class="text-sm-base-foreground">Note:</strong> ${_x('We securely log your AI interactions to improve our service. Your data is handled with strict privacy. See our <a href="https://www.storeapps.org/privacy-policy/" target="_blank" rel="noopener noreferrer">Privacy Policy</a> for details.','ai assistant modal privacy note','smart-manager-for-wp-e-commerce')}</p>
            </div>
        </div>
        `,
        autoHide: false,
        modalClass: 'w-2/6 max-w-xl',
        cta: {
            title: _x('GO', 'button', 'smart-manager-for-wp-e-commerce'),
            closeModalOnClick: false,
            callback: function () {
                jQuery('#sm_ai_prompt_input').parent().removeClass('sm_border_red');
                jQuery('.sm-ai-assistant-error').hide().html('');
                let prompt = jQuery('#sm_ai_prompt_input').val();
                if(prompt.trim() === ''){
                    jQuery('#sm_ai_prompt_input').parent().addClass('sm_border_red');
                    jQuery('.sm-ai-assistant-error').show().html(_x('Please enter a prompt to continue.','ai assistant modal content','smart-manager-for-wp-e-commerce'));
                    return;
                }
                window.smart_manager.NLConverter.convert('advanced_search',prompt);
            }
        },
        onCreate: function(){
            jQuery('.sm-ai-assistant-error').hide().html('');
            window.smart_manager.NLConverter = new SmartManagerNLConverter({
                micBtnID:'sm_ai_mic_btn',
                promptInputID:'sm_ai_prompt_input',
                // onVoiceRecognitionComplete: window.smart_manager.onVoiceRecognitionComplete
            });
            window.smart_manager.addNLPForAdvancedSearch();
        }
    }
    window.smart_manager.showModal()
})
// Show recent searches dropdown on focus
.off('focus', '#sm_simple_search_box').on('focus', '#sm_simple_search_box', function () {
	if (window.smart_manager.searchType !== 'simple') {
		return;
	}
	const currentSearches = window.smart_manager.getCurrentDashboardSearches();
	if (currentSearches && currentSearches.length > 0) {
		window.smart_manager.showRecentSearchesDropdown();
	}
})

// Hide recent searches dropdown on blur (with delay to allow click on items)
.off('blur', '#sm_simple_search_box').on('blur', '#sm_simple_search_box', function () {
	setTimeout(function () {
		if (!jQuery('#sm-recent-searches-dropdown:hover').length) {
			window.smart_manager.hideRecentSearchesDropdown();
		}
	}, 200);
})

// Handle click on recent search item
.off('click', '.sm-recent-search-item').on('click', '.sm-recent-search-item', function (e) {
	const searchTerm = jQuery(this).data('search-term');
	if (searchTerm) {
		jQuery('#sm_simple_search_box').val(searchTerm);
		window.smart_manager.simpleSearchText = searchTerm;
		window.smart_manager.hideRecentSearchesDropdown();
		if ("undefined" !== typeof (window.smart_manager.refresh) && "function" === typeof (window.smart_manager.refresh)) {
			window.smart_manager.refresh();
		}
	}
})

// Handle click on clear all recent searches
.off('click', '#sm-clear-recent-searches').on('click', '#sm-clear-recent-searches', function (e) {
	window.smart_manager.clearRecentSearches();
})

// Hide recent searches dropdown when clicking outside
.off('click.smRecentSearches').on('click.smRecentSearches', function (e) {
	if (!jQuery(e.target).closest('#sm_simple_search_box, #sm-recent-searches-dropdown').length) {
		window.smart_manager.hideRecentSearchesDropdown();
	}
})
