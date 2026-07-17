var contentEgg = angular.module('contentEgg', ['ui.bootstrap', 'ui.sortable', 'ngSanitize']);

contentEgg.controller('ContentEggController', function ($scope, ModuleService) {

    $scope.models = {};
    $scope.query_params = {};
    $scope.keywords = {};
    $scope.updateKeywords = {};
    $scope.updateParams = {};
    $scope.activeSearchTabs = {};
    $scope.activeResultTabs = {};
    $scope.shortcodes = {};
    $scope.productGroups = [];
    $scope.newProductGroup = '';

    $scope.blockShortcodeBuillder = {
        'template': '',
        'group': '',
        'limit': '',
        'offset': '',
        'next': '',
    };

    $scope.processCounter = 0;
    $scope.blockShortcode = '[content-egg-block]';
    $scope.active_modules = contentegg_params.active_modules;
    $scope.productGroups = contentegg_params.initProductGroups;

    angular.forEach($scope.active_modules, function (module_id, key) {
        $scope.models[module_id] = new ModuleService(module_id);
        $scope.keywords[module_id] = '';
        $scope.updateKeywords[module_id] = '';
        $scope.updateParams[module_id] = {};
        $scope.shortcodes[module_id] = '[content-egg module=' + module_id + ']';

        // init modules options
        $scope.query_params[module_id] = {};
        angular.forEach(contentegg_params.modulesOptions[module_id], function (value, option) {
            $scope.query_params[module_id][option] = value;
        });

        // init post metadata
        if (contentegg_params.initData[module_id]) {
            $scope.models[module_id].added = contentegg_params.initData[module_id];
            $scope.activeSearchTabs[module_id] = false;
            $scope.activeResultTabs[module_id] = true;
        } else {
            $scope.activeSearchTabs[module_id] = true;
            $scope.activeResultTabs[module_id] = false;
        }

        // init keywords
        if (contentegg_params.initKeywords[module_id]) {
            $scope.updateKeywords[module_id] = contentegg_params.initKeywords[module_id];
        }
        if (contentegg_params.initUpdateParams[module_id]) {
            $scope.updateParams[module_id] = contentegg_params.initUpdateParams[module_id];
        }

    });

    $scope.find = function (module_id) {
        if (!$scope.keywords[module_id])
            return;
        $scope.processCounter++;
        $scope.query_params[module_id].keyword = $scope.keywords[module_id];
        $scope.models[module_id].find($scope.query_params[module_id]).then(function (response) {
            $scope.processCounter--;
        });
    };

    $scope.add = function (result, module_id) {
        var index = $scope.models[module_id].results.indexOf(result);
        if ($scope.models[module_id].results[index].added)
            return;
        $scope.models[module_id].results[index].added = true;
        // check for dublicates
        for (var i = 0, len = $scope.models[module_id].added.length; i < len; i++) {
            var item = $scope.models[module_id].added[i];
            if (item['unique_id'] == $scope.models[module_id].results[index]['unique_id'])
                return;
        }
        $scope.models[module_id].results[index].keyword = $scope.keywords[module_id];
        $scope.models[module_id].added.push($scope.models[module_id].results[index]);
        $scope.models[module_id].added_changed = true;
    };

    $scope.addBlank = function (module_id, type = 'contentProduct') {
        if (type != 'contentProduct' && type != 'contentCoupon')
            return;
        var contentProduct = angular.copy(contentegg_params[type]);
        contentProduct.unique_id = Math.random().toString(36).slice(2);
        $scope.models[module_id].added.push(contentProduct);
        $scope.models[module_id].added_changed = true;
    };

    $scope.addAll = function (module_id) {
        if (!$scope.models[module_id].results.length)
            return;
        angular.forEach($scope.models[module_id].results, function (result, key) {
            $scope.add(result, module_id);
        });
        $scope.activeResultTabs[module_id] = true;
    };

    $scope.delete = function (data, module_id) {
        var index = $scope.models[module_id].added.indexOf(data);
        $scope.models[module_id].added.splice(index, 1);
        $scope.models[module_id].added_changed = true;
    };

    $scope.deleteAll = function (module_id) {
        $scope.models[module_id].added = [];
        $scope.models[module_id].added_changed = true;
        $scope.activeSearchTabs[module_id] = true;
    };

    $scope.copyKeywordProductIdsToClipboard = function (module_id, event) {

        let keyword = $scope.keywords[module_id];
        $scope.copyProductIdsToClipboard(module_id, event, keyword);

    }

    $scope.copyProductIdsToClipboard = function (module_id, event, keyword = '') {

        event.currentTarget.classList.add('btn-primary');
        event.currentTarget.classList.remove('btn-info');

        setTimeout(() => {
            event.currentTarget.classList.add('btn-info');
            event.currentTarget.classList.remove('btn-primary');
        }, 300);

        var product_ids = [];

        angular.forEach($scope.models[module_id].added, function (product, index) {

            if (module_id.indexOf("AE__") !== -1) {
                var asin = product['url'].match("/([a-zA-Z0-9]{10})(?:[/?]|$)");
                if (asin) {
                    product_ids.push(asin[1]);
                    return;
                }
            }

            var parts = product['unique_id'].split('-', 2);
            if (parts.length == 2)
                product_ids.push(parts[1]);
            else
                product_ids.push(parts[0]);

        });

        let res = keyword;
        if (res)
            res = res + ' ';
        res = res + JSON.stringify(product_ids);

        navigator.clipboard.writeText(res);

    };

    $scope.global_findAll = function () {
        if (!$scope.global_keywords)
            return;
        angular.forEach($scope.models, function (service, module_id) {
            $scope.keywords[module_id] = $scope.global_keywords;
            $scope.activeSearchTabs[module_id] = true;
            $scope.find(module_id);
        });
    };

    $scope.global_addAll = function () {
        angular.forEach($scope.models, function (service, module_id) {
            $scope.addAll(module_id);
        });
    };

    $scope.global_deleteAll = function () {
        angular.forEach($scope.models, function (service, module_id) {
            $scope.deleteAll(module_id);
        });
    };

    $scope.global_isSearchResults = function () {
        for (var i = 0, len = $scope.active_modules.length; i < len; i++) {
            var module_id = $scope.active_modules[i];
            if ($scope.models[module_id].results && $scope.models[module_id].results.length)
                return true;
        }
        return false;
    };

    $scope.global_isAddedResults = function () {
        for (var i = 0, len = $scope.active_modules.length; i < len; i++) {
            var module_id = $scope.active_modules[i];
            if ($scope.models[module_id].added.length)
                return true;
        }
        return false;
    };

    $scope.getYoutubeUri = function (id) {
        return 'https://www.youtube.com/embed/' + id;
    };

    $scope.setUpdateKeyword = function (module_id, event) {

        event.currentTarget.classList.add('btn-primary');
        event.currentTarget.classList.remove('btn-info');

        setTimeout(() => {
            event.currentTarget.classList.add('btn-info');
            event.currentTarget.classList.remove('btn-primary');
        }, 300);

        $scope.updateKeywords[module_id] = $scope.keywords[module_id];
        $scope.activeResultTabs[module_id] = true;
    };

    $scope.buildShortcode = function (module_id, template = '', group = '', product = '') {

        var shortcode = '[content-egg module=' + module_id;
        if (product)
            shortcode += ' products="' + product + '"';
        if (template)
            shortcode += ' template=' + template;
        if (group)
            shortcode += ' groups="' + group + '"';
        shortcode += ']';
        $scope.shortcodes[module_id] = shortcode;
    };

    $scope.buildBlockShortcode = function () {
        $scope.blockShortcode = '[content-egg-block template=' + $scope.blockShortcodeBuillder.template;
        if ($scope.blockShortcodeBuillder.group)
            $scope.blockShortcode += ' groups="' + $scope.blockShortcodeBuillder.group + '"';
        if ($scope.blockShortcodeBuillder.next) {
            var next = parseInt($scope.blockShortcodeBuillder.next);
            if (next)
                $scope.blockShortcode += ' next=' + next;
        }
        $scope.blockShortcode += ']';
    };

    $scope.addProductGroup = function () {
        var group = $scope.newProductGroup.replace(/(<([^>]+)>)/ig, '-');
        if (group === '-' || $scope.productGroups.includes(group))
            return;
        $scope.productGroups.unshift(group);
        $scope.newProductGroup = '';
    };

    $scope.wooRadioChange = function (unique_id, param_name) {
        angular.forEach($scope.models, function (service, module_id) {
            for (var i = 0, len = $scope.models[module_id].added.length; i < len; i++) {
                if ($scope.models[module_id].added[i].unique_id != unique_id) {
                    $scope.models[module_id].added[i][param_name] = false;
                }
            }
        });
    };

});

contentEgg.filter('stockStatus', function () {
    return function (item) {
        if (item == -1)
            return 'Out of stock';
        if (item == 1)
            return 'In stock';
    };
});

contentEgg.config(function ($sceDelegateProvider) {
    $sceDelegateProvider.resourceUrlWhitelist([
        'self',
        'https://www.youtube.com/**'
    ]);
});

contentEgg.directive('onEnter', function () {

    var linkFn = function (scope, element, attrs) {
        element.on('keypress', function (event) {
            if (event.which === 13) {
                scope.$apply(function () {
                    scope.$eval(attrs.onEnter);
                });
                event.preventDefault();
            }
        });
    };

    return {
        link: linkFn
    };
});

contentEgg.directive('imageloaded', [
    function () {

        'use strict';

        return {
            restrict: 'A',
            link: function (scope, element, attrs) {
                var cssClass = attrs.loadedclass;

                element.on('load', function (e) {
                    angular.element(element).addClass(cssClass);
                });
            }
        }
    }
]);

contentEgg.directive('justifiedGallery', ['$timeout', function ($timeout) {
    return {
        restrict: 'A',
        link: function (scope, el, attrs) {
            scope.$watch('$last', function (n, o) {
                if (n) {
                    $timeout(function () {
                        angular.element(el).justifiedGallery(scope.$eval(attrs.justifiedGallery)).on('jg.complete', function (e) {
                            //alert('on complete');
                        });
                        scope.$last = false;
                    });
                }
            });
        }
    };
}]);

contentEgg.directive('repeatDone', [function () {
    return {
        restrict: 'A',
        link: function (scope, element, iAttrs) {
            var parentScope = element.parent().scope();
            if (scope.$last) {
                parentScope.$last = true;
            }
        }
    };
}]);

contentEgg.directive('ngConfirmClick', function () {
    return {
        priority: -1,
        restrict: 'A',
        link: function (scope, element, attrs) {
            element.on('click', function (e) {
                var message = attrs.ngConfirmClick;
                if (message && !confirm(message)) {
                    e.stopImmediatePropagation();
                    e.preventDefault();
                }
            });
        }
    };
});

contentEgg.directive('selectOnClick', function () {
    return {
        restrict: 'A',
        link: function (scope, element, attrs) {
            element.on('click', function () {
                this.select();
            });
        }
    };
});

contentEgg.directive('convertToNumber', function () {
    return {
        require: 'ngModel',
        link: function (scope, element, attrs, ngModel) {
            ngModel.$parsers.push(function (val) {
                return val != null ? parseInt(val, 10) : null;
            });
            ngModel.$formatters.push(function (val) {
                return val != null ? '' + val : null;
            });
        }
    };
});
