var frontend =
/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 579);
/******/ })
/************************************************************************/
/******/ ({

/***/ 579:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/* global require */

/**
 * All frontend scripts required by our blocks should be included here.
 *
 * This is the file that Webpack is compiling into blocks.frontend.build.js
 */
// Nodelist forEach polyfill.
if (window.NodeList && !window.NodeList.prototype.forEach) {
  window.NodeList.prototype.forEach = Array.prototype.forEach;
}

var context = __webpack_require__(580); // Import.


context.keys().forEach(function (key) {
  return context(key);
});

/***/ }),

/***/ 580:
/***/ (function(module, exports, __webpack_require__) {

var map = {
	"./slider/backend.js": 581
};
function webpackContext(req) {
	return __webpack_require__(webpackContextResolve(req));
};
function webpackContextResolve(req) {
	var id = map[req];
	if(!(id + 1)) // check for number or string
		throw new Error("Cannot find module '" + req + "'.");
	return id;
};
webpackContext.keys = function webpackContextKeys() {
	return Object.keys(map);
};
webpackContext.resolve = webpackContextResolve;
module.exports = webpackContext;
webpackContext.id = 580;

/***/ }),

/***/ 581:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _domReady = _interopRequireDefault(__webpack_require__(582));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

/**
 * WordPress dependencies
 */
window.rehubSlider = /*#__PURE__*/function () {
  function _class(sliderNode) {
    this.sliderNode = sliderNode;
    this.currentSlideIndex = 0;
    this.setupElements();
  }

  var _proto = _class.prototype;

  _proto.setupElements = function setupElements() {
    this.$prevArrow = jQuery(this.sliderNode).find('.rh-slider-arrow--prev');
    this.$nextArrow = jQuery(this.sliderNode).find('.rh-slider-arrow--next');
    this.$items = jQuery(this.sliderNode).find('.rh-slider-item');
    this.$thumbs = jQuery(this.sliderNode).find('.rh-slider-thumbs-item');
    this.$dots = jQuery(this.sliderNode).find('.rh-slider-dots__item');
  };

  _proto.getSlideIndex = function getSlideIndex(type) {
    if (type === 'right') {
      // check if last element that scroll from start
      if (this.$items.length === this.currentSlideIndex + 1) {
        this.currentSlideIndex = -1;
      }

      this.currentSlideIndex += 1;
    } else {
      // check if current element is first and start moving from end
      if (this.currentSlideIndex === 0) {
        this.currentSlideIndex = this.$items.length;
      }

      this.currentSlideIndex -= 1;
    }

    return this.currentSlideIndex;
  };

  _proto.removeActiveClasses = function removeActiveClasses() {
    this.$items.each(function (i, slide) {
      jQuery(slide).removeClass('rh-slider-item--visible');
    });
    this.$thumbs.each(function (i, slide) {
      jQuery(slide).removeClass('rh-slider-thumbs-item--active');
    });
    this.$dots.each(function (i, slide) {
      jQuery(slide).removeClass('rh-slider-dots__item--active');
    });
  };

  _proto.moveSlide = function moveSlide(index) {
    this.removeActiveClasses();
    this.$items.eq(index).addClass('rh-slider-item--visible');
    this.$thumbs.eq(index).addClass('rh-slider-thumbs-item--active');
    this.$dots.eq(index).addClass('rh-slider-dots__item--active');
  };

  _proto.addListeners = function addListeners() {
    var _this = this;

    var self = this;
    this.$prevArrow.on('click.bind', function (ev) {
      ev.preventDefault();

      _this.moveSlide(_this.getSlideIndex());
    });
    this.$nextArrow.on('click.bind', function (ev) {
      ev.preventDefault();

      _this.moveSlide(_this.getSlideIndex('right'));
    });
    this.$thumbs.each(function (i, item) {
      jQuery(item).on('click.bind', function (ev) {
        ev.preventDefault();
        self.currentSlideIndex = i;
        self.moveSlide(i);
      });
    });
    this.$dots.each(function (i, item) {
      jQuery(item).on('click.bind', function (ev) {
        ev.preventDefault();
        self.currentSlideIndex = i;
        self.moveSlide(i);
      });
    });
  };

  _proto.removeListeners = function removeListeners() {
    this.$prevArrow.off('click.bind');
    this.$nextArrow.off('click.bind');
    this.$thumbs.each(function (i, item) {
      jQuery(item).off('click.bind');
    });
    this.$dots.each(function (i, item) {
      jQuery(item).off('click.bind');
    });
  };

  _proto.swipeDetect = function swipeDetect() {
    var self = this;
    var swipeDirection,
        startX,
        distX,
        threshold = 100;
    var touchSurface = this.sliderNode.querySelectorAll('.rh-slider-item img');
    Array.prototype.forEach.call(touchSurface, function (element) {
      element.addEventListener('touchstart', function (e) {
        var touchObj = e.changedTouches[0];
        swipeDirection = 'none';
        startX = touchObj.pageX;
        e.preventDefault();
      }, false);
      element.addEventListener('touchmove', function (e) {
        e.preventDefault();
      }, false);
      element.addEventListener('touchend', function (e) {
        if (e.target.className.indexOf('rh-slider-arrow--prev') >= 0) {
          self.$prevArrow.trigger('click.bind');
          return;
        } else if (e.target.className.indexOf('rh-slider-arrow--next') >= 0) {
          self.$nextArrow.trigger('click.bind');
          return;
        }

        var touchObj = e.changedTouches[0];
        distX = touchObj.pageX - startX;

        if (Math.abs(distX) >= threshold) {
          swipeDirection = distX < 0 ? 'right' : 'left';
        }

        self.moveSlide(self.getSlideIndex(swipeDirection));
        e.preventDefault();
      }, false);
    });
  };

  _proto.init = function init() {
    var fromIndex = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 0;
    this.$items.eq(fromIndex).addClass('rh-slider-item--visible');
    this.$thumbs.eq(fromIndex).addClass('rh-slider-thumbs-item--active');
    this.$dots.eq(fromIndex).addClass('rh-slider-dots__item--active');
    this.addListeners();
    this.swipeDetect();
  };

  _proto.update = function update() {
    this.removeActiveClasses();
    this.removeListeners();
    this.setupElements();
    this.init(this.currentSlideIndex);
  };

  _proto.destroy = function destroy() {
    this.removeActiveClasses();
    this.removeListeners();
  };

  return _class;
}();

(0, _domReady.default)(function () {
  var $sliders = jQuery('.js-hook__slider');

  if ($sliders.length === 0) {
    return false;
  }

  $sliders.each(function (i, item) {
    var slider = new window.rehubSlider(item);
    slider.init();
  });
});

/***/ }),

/***/ 582:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony export (immutable) */ __webpack_exports__["default"] = domReady;
/**
 * @typedef {() => void} Callback
 *
 * TODO: Remove this typedef and inline `() => void` type.
 *
 * This typedef is used so that a descriptive type is provided in our
 * automatically generated documentation.
 *
 * An in-line type `() => void` would be preferable, but the generated
 * documentation is `null` in that case.
 *
 * @see https://github.com/WordPress/gutenberg/issues/18045
 */

/**
 * Specify a function to execute when the DOM is fully loaded.
 *
 * @param {Callback} callback A function to execute after the DOM is ready.
 *
 * @example
 * ```js
 * import domReady from '@wordpress/dom-ready';
 *
 * domReady( function() {
 * 	//do something after DOM loads.
 * } );
 * ```
 *
 * @return {void}
 */
function domReady(callback) {
  if (document.readyState === 'complete' || // DOMContentLoaded + Images/Styles/etc loaded, so we call directly.
  document.readyState === 'interactive' // DOMContentLoaded fires at this point, so we call directly.
  ) {
      return void callback();
    } // DOMContentLoaded has not fired yet, delay callback until then.


  document.addEventListener('DOMContentLoaded', callback);
}
//# sourceMappingURL=index.js.map

/***/ })

/******/ });
//# sourceMappingURL=frontend.js.map