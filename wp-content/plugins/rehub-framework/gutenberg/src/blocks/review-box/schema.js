import {__} from '@wordpress/i18n';

const schema = {
	title: {
		type: 'string',
		default: __('Awesome', 'rehub-framework')
	},
	description: {
		type: 'string',
		default: __('Place here Description for your reviewbox', 'rehub-framework')
	},
	score: {
		type: 'number',
		default: 0
	},
	scoreManual: {
		type: 'number',
		default: 0
	},
	mainColor: {
		type: 'string',
		default: '#E43917'
	},
	criterias: {
		type: 'array',
		default: []
	},
	prosTitle: {
		type: 'string',
		default: __('Positive', 'rehub-framework')
	},
	positives: {
		type: 'array',
		default: []
	},
	consTitle: {
		type: 'string',
		default: __('Negatives', 'rehub-framework')
	},
	negatives: {
		type: 'array',
		default: []
	},
	uniqueClass: {
		type: 'string',
		default: ''
	},
	postId: {
		type: 'string',
		default: ''
	},
};

export default schema;