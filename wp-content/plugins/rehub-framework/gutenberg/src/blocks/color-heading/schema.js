/**
 * WordPress dependencies
 */
import {__} from '@wordpress/i18n';

const schema = {
	align: {
		type: 'string',
		default: 'full'
	},
	title: {
		type: 'string',
		default: __('Sample title', 'rehub-framework')
	},
	subtitle: {
		type: 'string',
		default: __('Sample subtitle', 'rehub-framework')
	},
	backgroundColor: {
		type: 'string',
		default: '#ebf2fc'
	},
	titleColor: {
		type: 'string',
		default: '#111'
	},
	subtitleColor: {
		type: 'string',
		default: '#111'
	}
};
export default schema;