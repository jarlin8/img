import {sprintf} from '@wordpress/i18n';

/**
 * Simple CSS minification.
 *
 * @see https://stackoverflow.com/questions/15411263/how-do-i-write-a-better-regexp-for-css-minification-in-php
 *
 * @param {string} css CSS to minify.
 * @param {boolean} important Add !important to all rules.
 *
 * @return {string} Minified CSS
 */
export const minifyCSS = (css, important = false) => {
	const minified = css.replace(/\/\*.*?\*\//g, '') // Comments.
		.replace(/\n\s*\n/g, '') // Comments.
		.replace(/[\n\r \t]/g, ' ') // Spaces.
		.replace(/ +/g, ' ') // Multi-spaces.
		.replace(/ ?([,:;{}]) ?/g, '$1') // Extra spaces.
		.replace(/[^\}\{]+\{\}/g, '') // Blank selectors.
		.replace(/[^\}\{]+\{\}/g, '') // Blank selectors. Repeat to catch empty media queries.
		.replace(/;}/g, '}') // Trailing semi-colon.
		.trim();

	if (!important) {
		return minified
	}

	return minified
		.replace(/\s?\!important/g, '') // Remove all !important
		.replace(/([;\}])/g, ' !important$1') // Add our own !important.
		.replace(/\} !important\}/g, '}}') // Ending of media queries "}}" get an added !important from the previous line, remove it.
		.trim()
};

/**
 * Ensures the cssSelector is only applied to the uniqueClassName element.
 * Wraps the cssSelector with a uniqueClassName, and takes into account the mainClassName.
 *
 * For example:
 * .title-block -> .my-title-be8d9a.title-block
 * .title-block span -> .my-title-be8d9a.title-block span
 * span -> .my-title-be8d9a span
 *
 * @param {string} cssSelector The CSS selector.
 * @param {string} mainClassName The main class of the block to target.
 * @param {string} uniqueClassName The unique parent classname to wrap the selector.
 * @param {string} wrapSelector All selectors will be wrapped in this if provided.
 *
 * @return {string} The modified CSS selector.
 */
export const prependCSSClass = (cssSelector, mainClassName = '', uniqueClassName = '', wrapSelector = '') => {
	return cssSelector.trim().replace(/[\n\s\t]+/g, ' ')
		.split(',')
		.map(s => {
			let newSelector = '';
			if (!uniqueClassName || !mainClassName) {
				newSelector = s
			} else if (uniqueClassName && !mainClassName) {
				newSelector = `.${uniqueClassName} ${s.trim()}`
			} else {
				newSelector = `.${uniqueClassName} ${s.trim()}`
					.replace(new RegExp(`(.${uniqueClassName}) (.${mainClassName}(#|:|\\[|\\.|\\s|$))`, 'g'), '$1$2')
			}
			return wrapSelector ? `${wrapSelector} ${newSelector}` : newSelector
		})
		.join(', ')
};

/**
 * Creates a getValue function that's used for getting attributes for style generation.
 *
 * @param {Object} attributes Block attribbutes
 * @param {Function} attrNameCallback Optional function where the attrName will be run through for formatting
 * @param {Object} defaultValue_ Value to return if the attribute value is blank. Defaults to undefined.
 *
 * @return {Function} getValue function
 */
export const __getValue = (attributes, attrNameCallback = null, defaultValue_ = undefined) => (attrName, format = '', defaultValue = defaultValue_) => {
	const attrNameFunc = attrNameCallback !== null ? attrNameCallback : (s => s);
	const value = typeof attributes[attrNameFunc(attrName)] === 'undefined' ? '' : attributes[attrNameFunc(attrName)];
	return value !== '' ? (format ? sprintf(format.replace(/%$/, '%%'), value) : value) : defaultValue
};

export const calculateExpiredDays = (value) => {
	const currentTimestamp = Date.now();
	const expiredTimestamp = Date.parse(value);
	const difference = (expiredTimestamp - currentTimestamp);
	return Math.floor(difference / 1000 / 60 / 60 / 24);
};
