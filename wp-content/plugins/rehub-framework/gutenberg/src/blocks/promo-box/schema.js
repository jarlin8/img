/**
 * WordPress dependencies
 */
import {__} from '@wordpress/i18n';

const schema = {
	title: {
		type: 'string',
		default: __('Sample title', 'rehub-framework')
	},
	content: {
		type: 'string',
		default: __('Sample content', 'rehub-framework')
	},

	backgroundColor: {
		type: 'string',
		default: '#f8f8f8'
	},
	textColor: {
		type: 'string',
		default: '#333'
	},

	showBorder: {
		type: 'boolean',
		default: false
	},
	borderSize: {
		type: 'number',
		default: 1
	},
	borderColor: {
		type: 'string',
		default: '#dddddd'
	},

	showHighlightBorder: {
		type: 'boolean',
		default: false
	},
	highlightColor: {
		type: 'string',
		default: '#fb7203'
	},
	highlightPosition: {
		type: 'string',
		default: 'Left'
	},

	showButton: {
		type: 'boolean',
		default: false
	},
	buttonText: {
		type: 'string',
		default: __('Purchase Now', 'rehub-framework')
	},
	buttonLink: {
		type: 'string',
		default: ''
	}
};
export default schema;