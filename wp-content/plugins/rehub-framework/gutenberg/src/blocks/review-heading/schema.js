/**
 * WordPress dependencies
 */
import {__} from '@wordpress/i18n';

const schema = {
	includePosition: {
		type: 'boolean',
		default: true
	},
	position: {
		type: 'string',
		default: '1'
	},
	title: {
		type: 'string',
		default: __('Sample title', 'rehub-framework')
	},
	titleTag: {
		type: 'string',
		default: 'h2'
	},
	subtitle: {
		type: 'string',
		default: __('Sample subtitle', 'rehub-framework')
	},
	includeImage: {
		type: 'boolean',
		default: true
	},
	image: {
		type: 'object',
		default: {
			id: 0,
			url: `${window.RehubGutenberg.pluginDirUrl}/gutenberg/src/icons/noimage-placeholder.png`,
			width: '',
			height: '',
			alt: ''
		}
	},
	link: {
		type: 'string',
		default: ''
	}
};
export default schema;