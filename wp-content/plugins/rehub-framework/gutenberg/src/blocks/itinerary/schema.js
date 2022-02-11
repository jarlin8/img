/**
 * WordPress dependencies
 */
import {__} from '@wordpress/i18n';

const schema = {
	items: {
		type: 'array',
		default: [
			{
				icon: 'rhicon rhi-circle-solid',
				color: '#409cd1',
				content: __('Box Content', 'rehub-framework')
			},
			{
				icon: 'rhicon rhi-circle-solid',
				color: '#409cd1',
				content: __('Box Content', 'rehub-framework')
			},
			{
				icon: 'rhicon rhi-circle-solid',
				color: '#409cd1',
				content: __('Box Content', 'rehub-framework')
			}
		]
	}
};
export default schema;