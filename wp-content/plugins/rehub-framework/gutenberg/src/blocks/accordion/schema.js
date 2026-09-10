/**
 * WordPress dependencies
 */
import {__} from '@wordpress/i18n';

const schema = {
	tabs: {
		type: 'array',
		default: [
			{
				title: __('Sample title', 'rehub-framework'),
				content: __('Sample content', 'rehub-framework')
			}
		]
	}
};
export default schema;