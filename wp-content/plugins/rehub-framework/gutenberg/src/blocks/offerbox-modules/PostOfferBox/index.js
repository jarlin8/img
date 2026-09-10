/**
 * BLOCK: Offerbox.
 */


/**
 * WordPress dependencies
 */
import {__} from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import schema from '../schema';
import edit from './edit';
import {OfferBoxIcon} from '../../../icons';

const blockProperty = {
	title: __('Post OfferBox', 'rehub-framework'),
	description: __('Post OfferBox description', 'rehub-framework'),
	icon: OfferBoxIcon,
	category: 'helpler-modules',
	keywords: [],
	supports: {
		align: ['wide', 'full'],
		customClassName: false,
		html: false,
	},
	example: {},
	attributes: schema,
	save: () => null,
	edit
};

export default {
	slug: `rehub/post-offerbox`,
	blockProperty,
};
