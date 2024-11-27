/* global require */

/**
 * All frontend scripts required by our blocks should be included here.
 *
 * This is the file that Webpack is compiling into blocks.frontend.build.js
 */

// Nodelist forEach polyfill.
if (window.NodeList && !window.NodeList.prototype.forEach) {
	window.NodeList.prototype.forEach = Array.prototype.forEach
}

const context = require.context(
	'./blocks', // Search within the src/blocks directory.
	true, // Search recursively.
	/backend\.js$/ // Match any frontend.js.
);

// Import.
context.keys().forEach(key => context(key));