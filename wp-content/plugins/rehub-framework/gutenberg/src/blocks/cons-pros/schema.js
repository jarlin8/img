import {__} from '@wordpress/i18n';

const schema = {
	prosTitle: {
		type: 'string',
		default: __('Positive', 'rehub-framework')
	},
	positives: {
		type: 'array',
		default: [
			{
				title: __('Positive', 'rehub-framework')
			}
		]
	},
	consTitle: {
		type: 'string',
		default: __('Negatives', 'rehub-framework')
	},
	negatives: {
		type: 'array',
		default: [
			{
				title: __('Negative', 'rehub-framework')
			}
		]
	},
};

export default schema;