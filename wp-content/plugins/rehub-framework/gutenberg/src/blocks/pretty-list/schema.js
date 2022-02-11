/**
 * WordPress dependencies
 */
import {__} from '@wordpress/i18n';

const schema = {
	type: {
		type: 'string',
		default: 'arrow'
	},
	smallGap: {
		type: 'boolean',
		default: false
	},
	prettyHover: {
		type: 'boolean',
		default: false
	},
	darkLink: {
		type: 'boolean',
		default: false
	},
	items: {
		type: 'array',
		default: [
			{
				text: __('Sample Item #1', 'rehub-framework')
			},
			{
				text: __('Sample Item #2', 'rehub-framework')
			},
			{
				text: __('Sample Item #3', 'rehub-framework')
			}
		]
	}
};
export default schema;