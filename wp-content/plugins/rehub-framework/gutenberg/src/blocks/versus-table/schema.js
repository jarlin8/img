/**
 * WordPress dependencies
 */
import {__} from '@wordpress/i18n';

const schema = {
	heading: {
		type: 'string',
		default: __('Versus Title', 'rehub-framework')
	},
	subheading: {
		type: 'string',
		default: __('Versus subline', 'rehub-framework')
	},
	type: {
		type: 'string',
		default: 'two'
	},
	bg: {
		type: 'string',
		default: ''
	},
	color: {
		type: 'string',
		default: ''
	},
	firstColumn: {
		type: 'object',
		default: {
			type: 'text',
			isGrey: false,
			content: __('Value 1', 'rehub-framework'),
			image: '',
			imageId: ''
		}
	},
	secondColumn: {
		type: 'object',
		default: {
			type: 'text',
			isGrey: false,
			content: __('Value 2', 'rehub-framework'),
			image: '',
			imageId: ''
		}
	},
	thirdColumn: {
		type: 'object',
		default: {
			type: 'text',
			isGrey: false,
			content: __('Value 3', 'rehub-framework'),
			image: '',
			imageId: ''
		}
	},
};
export default schema;