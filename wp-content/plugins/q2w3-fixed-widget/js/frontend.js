'use strict';

/*! *****************************************************************************
Copyright (c) Microsoft Corporation.

Permission to use, copy, modify, and/or distribute this software for any
purpose with or without fee is hereby granted.

THE SOFTWARE IS PROVIDED "AS IS" AND THE AUTHOR DISCLAIMS ALL WARRANTIES WITH
REGARD TO THIS SOFTWARE INCLUDING ALL IMPLIED WARRANTIES OF MERCHANTABILITY
AND FITNESS. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY SPECIAL, DIRECT,
INDIRECT, OR CONSEQUENTIAL DAMAGES OR ANY DAMAGES WHATSOEVER RESULTING FROM
LOSS OF USE, DATA OR PROFITS, WHETHER IN AN ACTION OF CONTRACT, NEGLIGENCE OR
OTHER TORTIOUS ACTION, ARISING OUT OF OR IN CONNECTION WITH THE USE OR
PERFORMANCE OF THIS SOFTWARE.
***************************************************************************** */
/* global Reflect, Promise */

var extendStatics = function(d, b) {
    extendStatics = Object.setPrototypeOf ||
        ({ __proto__: [] } instanceof Array && function (d, b) { d.__proto__ = b; }) ||
        function (d, b) { for (var p in b) if (Object.prototype.hasOwnProperty.call(b, p)) d[p] = b[p]; };
    return extendStatics(d, b);
};

function __extends(d, b) {
    if (typeof b !== "function" && b !== null)
        throw new TypeError("Class extends value " + String(b) + " is not a constructor or null");
    extendStatics(d, b);
    function __() { this.constructor = d; }
    d.prototype = b === null ? Object.create(b) : (__.prototype = b.prototype, new __());
}

var __assign = function() {
    __assign = Object.assign || function __assign(t) {
        for (var s, i = 1, n = arguments.length; i < n; i++) {
            s = arguments[i];
            for (var p in s) if (Object.prototype.hasOwnProperty.call(s, p)) t[p] = s[p];
        }
        return t;
    };
    return __assign.apply(this, arguments);
};

var StopWidgetClassName = 'FixedWidget__stop_widget';
var FixedWidgetClassName = 'FixedWidget__fixed_widget';
// TODO export offset values, calculations in separate class Offsets
var Widget = /** @class */ (function () {
    function Widget(el) {
        this.el = el;
        this.top_offset = 0;
        this.root_offset = 0;
        this.need_to_calc_el_offset = function (_) { return false; };
        this.prevSibling = function (el) {
            return el && el.previousElementSibling;
        };
    }
    Widget.prototype.render = function () { };
    Widget.prototype.mount = function (user_margins, layer, _max_top_offset) {
        if (!this.el || !this.el.parentElement) {
            return;
        }
        this.el.style.zIndex = "" + layer;
        this.top_offset = this.get_total_top_offset(user_margins);
        this.root_offset = scrollY + this.el.getBoundingClientRect().y;
    };
    Widget.prototype.getElement = function () {
        return this.el;
    };
    Widget.prototype.toString = function () {
        var _a;
        return "" + ((_a = this.el) === null || _a === void 0 ? void 0 : _a.innerHTML);
    };
    Widget.prototype.get_total_top_offset = function (margins) {
        return get_sibilings_offset(this.prevSibling, this.need_to_calc_el_offset, this.prevSibling(this.el), margins.margin_top);
    };
    Widget.queryAllWidgetsContainers = function (className) {
        return []
            .concat(Array.from(document.querySelectorAll("." + className)), Array.from(document.querySelectorAll("[data-fixed_widget=" + className)))
            .map(function (el) {
            el.classList.remove(className);
            el.removeAttribute('data-fixed_widget');
            var container = getWidgetContainer(el);
            container.classList.remove(FixedWidgetClassName);
            container.classList.remove(StopWidgetClassName);
            return container;
        });
    };
    Widget.from = function (root, className) {
        var _this = this;
        return Array.from(root.querySelectorAll("." + className))
            .filter(function (el) { return el !== null; })
            .map(function (e) { return new _this(e); });
    };
    return Widget;
}());
var getWidgetContainer = function (el) {
    return el.parentElement && (el.parentElement.childElementCount === 1 ||
        /**
         * Group can contain multiple children, but this is one Widget,
         */
        el.parentElement.classList.toString().includes('wp-block-group') ||
        el.parentElement.classList.toString().includes('wp-block-column') ||
        el.parentElement.classList.contains('widget')) ? getWidgetContainer(el.parentElement) : el;
};
/**
 * Calc total offset of all fixed/sticked sibislings
 * @param next
 * @param el
 * @param offset
 * @returns
 */
var get_sibilings_offset = function (next, need_to_calc_el_offset, el, offset) {
    if (offset === void 0) { offset = 0; }
    if (!el) {
        return offset;
    }
    if (!need_to_calc_el_offset(el)) {
        return get_sibilings_offset(next, need_to_calc_el_offset, next(el), offset);
    }
    var _a = getComputedStyle(el), marginTop = _a.marginTop, marginBottom = _a.marginBottom;
    return get_sibilings_offset(next, need_to_calc_el_offset, next(el), offset + el.offsetHeight + parseInt(marginTop || '0') + parseInt(marginBottom || '0'));
};

var PositionWidget = /** @class */ (function (_super) {
    __extends(PositionWidget, _super);
    function PositionWidget() {
        var _this = _super !== null && _super.apply(this, arguments) || this;
        _this.bottom_offset = 0;
        _this.top_margin = 0;
        _this.borderBox = 0; /** cleintHeight+ top & bottom margins */
        /** Top offset of StopWidget */
        _this.max_top_offset = 0;
        _this.bottom_margin = 0;
        _this.user_margins = {};
        _this.prevSibling = function (el) {
            return el
                && !el.classList.contains(StopWidgetClassName)
                && el.previousElementSibling
                || null;
        };
        return _this;
    }
    PositionWidget.prototype.mount = function (user_margins, layer, max_top_offset) {
        _super.prototype.mount.call(this, user_margins, layer);
        if (!this.el || !this.el.parentElement) {
            return;
        }
        this.user_margins = user_margins;
        var _a = getComputedStyle(this.el), marginTop = _a.marginTop, marginBottom = _a.marginBottom;
        this.bottom_margin = parseInt(marginBottom);
        this.top_margin = parseInt(marginTop);
        this.bottom_offset = this.get_total_bottom_offset(user_margins);
        this.max_top_offset = max_top_offset;
        this.borderBox = this.el.clientHeight + this.top_margin + this.bottom_margin;
    };
    PositionWidget.prototype.render = function () {
        if (!this.el || !this.el.parentElement) {
            return;
        }
        var scrollTop = scrollY;
        this.onScroll(scrollTop);
    };
    PositionWidget.from = function (root) {
        return _super.from.call(this, root, FixedWidgetClassName);
    };
    PositionWidget.prototype.onScroll = function (_scrollTop) { };
    PositionWidget.prototype.get_total_bottom_offset = function (margins) {
        var next = function (el) { return el && !el.classList.contains(StopWidgetClassName) ? el.nextElementSibling : null; };
        return get_sibilings_offset(next, this.need_to_calc_el_offset, next(this.el), margins.margin_bottom);
    };
    return PositionWidget;
}(Widget));

var FixedWidget = /** @class */ (function (_super) {
    __extends(FixedWidget, _super);
    function FixedWidget(el) {
        var _this = _super.call(this, el) || this;
        _this.is_pinned = false;
        _this.relative_top = 0;
        _this.init_style = { position: 'static', marginTop: '', marginBottom: '', width: '', height: '' };
        _this.need_to_calc_el_offset = function (el) {
            return el.classList.contains(FixedWidgetClassName);
        };
        if (!_this.el || !_this.el.parentElement) {
            return _this;
        }
        _this.el.classList.add(FixedWidgetClassName);
        return _this;
    }
    FixedWidget.prototype.mount = function (margins, layer, max_top_offset) {
        _super.prototype.mount.call(this, margins, layer, max_top_offset);
        if (!this.el) {
            return;
        }
        /** StopWidget can limit top offset if it is placed only after widget*/
        if (max_top_offset < this.root_offset) {
            this.max_top_offset = 0;
        }
        this.relative_top =
            this.max_top_offset
                - this.top_offset
                - this.borderBox
                - this.bottom_offset;
        this.store_style(getComputedStyle(this.el));
        this.clone();
    };
    FixedWidget.prototype.clone = function () {
        var _this = this;
        if (!this.el || !this.el.parentElement) {
            return;
        }
        this.clone_el = this.el.cloneNode(false);
        this.clone_el.getAttributeNames().forEach(function (attr) {
            _this.clone_el.removeAttribute(attr);
        });
        for (var prop in this.init_style) {
            this.clone_el.style[prop] = this.init_style[prop];
        }
        this.clone_el.style.display = 'none';
        this.el.parentElement.insertBefore(this.clone_el, this.el);
    };
    FixedWidget.prototype.store_style = function (style) {
        this.init_style.position = style.position;
        this.init_style.marginTop = style.marginTop;
        this.init_style.marginBottom = style.marginBottom;
        this.init_style.width = style.width;
        this.init_style.height = style.height;
    };
    FixedWidget.prototype.restore_style = function (style) {
        if (!this.is_pinned) {
            return;
        }
        this.is_pinned = false;
        style.position = this.init_style.position;
        if (this.clone_el) {
            this.clone_el.style.display = 'none';
        }
    };
    FixedWidget.prototype.onScroll = function (scrollTop) {
        if (!this.el) {
            return;
        }
        var need_to_fix = scrollTop > this.root_offset - this.top_offset;
        var limited_by_stop_element = this.max_top_offset !== 0 && scrollTop > this.relative_top;
        var top = limited_by_stop_element ? this.relative_top - scrollTop + this.top_offset : this.top_offset;
        need_to_fix ?
            this.fix(top) :
            this.restore_style(this.el.style);
    };
    FixedWidget.prototype.fix = function (top) {
        if (!this.el) {
            return;
        }
        this.el.style.top = top + "px";
        if (this.is_pinned) {
            return;
        }
        this.is_pinned = true;
        this.el.style.position = 'fixed';
        this.el.style.transition = 'transform 0.5s';
        this.el.style.width = this.init_style.width;
        this.el.style.height = this.init_style.height;
        if (!this.clone_el) {
            return;
        }
        this.clone_el.style.display = 'block';
    };
    FixedWidget.new = function (selector) {
        return new FixedWidget(document.querySelector(selector));
    };
    FixedWidget.is = function (selector) {
        var el = document.querySelector(selector);
        return !!el && el.classList.contains(FixedWidgetClassName);
    };
    return FixedWidget;
}(PositionWidget));

var StickyWidget = /** @class */ (function (_super) {
    __extends(StickyWidget, _super);
    function StickyWidget(el) {
        var _this = _super.call(this, el) || this;
        _this.margins = 0;
        _this.need_to_calc_el_offset = function (el) {
            return el.classList.contains(FixedWidgetClassName);
        };
        if (!_this.el || !_this.el.parentElement) {
            return _this;
        }
        _this.el.classList.add(FixedWidgetClassName);
        return _this;
    }
    StickyWidget.prototype.mount = function (margins, layer, max_top_offset) {
        _super.prototype.mount.call(this, margins, layer, max_top_offset);
        if (!this.el || !this.el.parentElement) {
            return;
        }
        /** StopWidget can limit top offset if it is placed only after widget*/
        if (max_top_offset < this.el.offsetTop) {
            this.max_top_offset = 0;
        }
        this.margins = this.el.parentElement.clientHeight - this.borderBox;
        this.el.style.position = 'sticky';
        this.el.style.position = '-webkit-sticky';
        this.el.style.transition = 'transform 0s';
        this.el.style.boxSizing = 'border-box';
        this.el.style.top = this.top_offset + "px";
    };
    StickyWidget.prototype.onScroll = function () {
        if (!this.el || !this.el.parentElement) {
            return;
        }
        var bottom_margin = this.max_top_offset ?
            Math.min(this.max_top_offset - this.el.offsetTop - this.borderBox, this.margins - this.el.offsetTop)
            : this.margins - this.el.offsetTop;
        if (bottom_margin >= this.bottom_offset) {
            this.el.style.transform = "translateY(0px)";
            return;
        }
        this.el.style.transform = "translateY(" + (bottom_margin - this.bottom_offset) + "px)";
    };
    StickyWidget.new = function (selector) {
        return new StickyWidget(document.querySelector(selector));
    };
    StickyWidget.is = function (selector) {
        var el = document.querySelector(selector);
        return !!el && el.classList.contains(FixedWidgetClassName);
    };
    return StickyWidget;
}(PositionWidget));

var StopWidget = /** @class */ (function (_super) {
    __extends(StopWidget, _super);
    function StopWidget(el) {
        var _this = _super.call(this, el) || this;
        _this.need_to_calc_el_offset = function () { return true; };
        if (!_this.el || !_this.el.parentElement) {
            return _this;
        }
        _this.el.classList.add(StopWidgetClassName);
        return _this;
    }
    StopWidget.new = function (selector) {
        return new StopWidget(document.querySelector(selector));
    };
    StopWidget.is = function (selector) {
        var el = document.querySelector(selector);
        return !!el && el.classList.contains(StopWidgetClassName);
    };
    StopWidget.from = function (root) {
        return _super.from.call(this, root, StopWidgetClassName);
    };
    return StopWidget;
}(Widget));

/**
 *
 * @param arr1
 * @param arr2
 * @returns [uniq elements from arr2, dublicates]
 */
var findIntersections = function (arr1, arr2) {
    return [
        arr2.filter(function (e) { return !arr1.includes(e); }),
        arr1.filter(function (e) { return arr2.includes(e); }),
    ];
};
var splitSelectors = function (s) {
    if (s === void 0) { s = ''; }
    return s.replace(/[\r\n]|[\r]/gi, '\n')
        .split('\n')
        .map(function (s) { return s.trim(); })
        .filter(function (s) { return s !== ''; });
};
/**
 * For compatabilty with Fixed Widget 5.3.0 (had a ids without #)
 * Clone and add selectors with #-prefix
 * @see https://github.com/webgilde/fixed-widget/issues/75
 */
var compatabilty_FW_v5 = function (selectors) {
    if (selectors === void 0) { selectors = []; }
    /** If `selectors` includes extended selectors, not id names only, then it's v 6.0.0 */
    if (selectors.some(function (s) { return !/^[a-z]/i.test(s); })) {
        return selectors;
    }
    return selectors.concat(selectors.map(function (s) { return "#" + s; }));
};

var initSidebars = function (options) {
    var fixedWidgetsContainers = Array.from(new Set(// use Set to remove duplicates
    Widget
        .queryAllWidgetsContainers(FixedWidgetClassName) // widgets by classNames from editor's plugin
        .concat(queryElements(compatabilty_FW_v5(options.widgets))) // widgets from option's custom selectors
    ));
    var stopWidgetsSelectors = compatabilty_FW_v5(splitSelectors(options.stop_elements_selectors));
    var stopWidgetsContainers = Array.from(new Set(// use Set to remove duplicates
    Widget
        .queryAllWidgetsContainers(StopWidgetClassName) // widgets by classNames from editor's plugin;
        .concat(queryElements(stopWidgetsSelectors)) // widgets from option's custom selectors
    ));
    var _a = findIntersections(fixedWidgetsContainers, stopWidgetsContainers), stopWidgetsUniqContainers = _a[0], duplicates = _a[1];
    duplicates.forEach(function (w) {
        console.error("The Widget is detected as fixed block and stop block!\n" + w.innerHTML);
    });
    fixedWidgetsContainers.forEach(function (c) { c.classList.add(FixedWidgetClassName); });
    stopWidgetsUniqContainers.forEach(function (c) { c.classList.add(StopWidgetClassName); });
    var sidebars = Sidebar.create(fixedWidgetsContainers.concat(stopWidgetsUniqContainers), options);
    sidebars.forEach(function (sidebar) { sidebar.mount(); });
    document.addEventListener('scroll', function () {
        sidebars.forEach(function (sidebar) { return sidebar.render(); });
    });
};
var Sidebar = /** @class */ (function () {
    function Sidebar(el, margins, use_sticky_position) {
        this.el = el;
        this.margins = margins;
        this.use_sticky_position = use_sticky_position;
        this.widgets = [];
        this.stop_widgets = [];
        this.min_top_offset = 0;
        this.stop_widgets = StopWidget.from(this.el);
        var WidgetContructor = typeof use_sticky_position === 'undefined' || use_sticky_position ? StickyWidget : FixedWidget;
        this.widgets = WidgetContructor.from(this.el);
        if (!use_sticky_position) {
            return;
        }
        this.el.style.position = 'relative';
        if (this.stop_widgets.length !== 0) {
            return;
        }
        this.el.style.minHeight = '100%';
    }
    Sidebar.prototype.mount = function () {
        var _this = this;
        this.stop_widgets.forEach(function (widget, i) { return widget.mount({}, 0, 0); });
        this.min_top_offset = this.stop_widgets.length !== 0 ? Math.min.apply(Math, this.stop_widgets.map(this.use_sticky_position ?
            function (w) { return w.top_offset; } :
            function (w) { return w.root_offset; })) :
            0;
        this.widgets.forEach(function (widget, i) { return widget.mount(_this.margins, i, _this.min_top_offset); });
    };
    Sidebar.prototype.render = function () {
        this.widgets.forEach(function (widget) { return widget.render(); });
    };
    Sidebar.create = function (elements, options) {
        return Array.from(new Set(elements.map(function (widget) { return widget.parentElement; })))
            .filter(function (sidebar_el) { return sidebar_el !== null; })
            .map(function (sidebar_el) {
            return new Sidebar(sidebar_el, options, typeof options.use_sticky_position === 'undefined' || options.use_sticky_position);
        });
    };
    return Sidebar;
}());
var queryElements = function (selectors) {
    if (selectors === void 0) { selectors = []; }
    return Array.from((selectors)
        .map(function (selector) { return Array.from(document.querySelectorAll(selector)); }))
        .reduce(function (all, elements) { return all.concat(elements); }, [])
        .filter(function (e) { return e instanceof HTMLElement; });
};

var initPlugin = function (options) {
    if (options === void 0) { options = []; }
    initSidebars(options.reduce(function (prev, cur) { return (__assign(__assign(__assign({}, prev), cur), { widgets: prev.widgets.concat(cur.widgets || []) })); }, { widgets: [] }));
};

window.addEventListener('load', onDocumentLoaded);
document.readyState === "complete" && onDocumentLoaded();
function onDocumentLoaded() {
    var admin_panel = document.querySelector('#wpadminbar');
    // @ts-ignore
    var user_options = window['q2w3_sidebar_options'] || [{}];
    var options = user_options.map(function (option) {
        option.margin_top = (option.margin_top || 0) + ((admin_panel === null || admin_panel === void 0 ? void 0 : admin_panel.clientHeight) || 0);
        return option;
    });
    if (options.some(function (option) {
        return window.innerWidth < option.screen_max_width ||
            window.innerHeight < option.screen_max_height;
    })) {
        return;
    }
    initPlugin(options);
}
