/**
 * WordPress dependencies
 */
import {__} from '@wordpress/i18n';

const schema = {
	selectedPosts: {
		type: 'array',
		default: []
	},
	loading: {
		type: 'boolean',
		default: false
	},
	parseError: {
		type: 'string',
		default: ''
	},
	parseSuccess: {
		type: 'string',
		default: ''
	},
	offers: {
		type: 'array',
		default: [
			{
				score: 10,
				enableBadge: true,
				enableScore: true,
				thumbnail: {
					id: '',
					url: `${window.RehubGutenberg.pluginDirUrl}/gutenberg/src/icons/noimage-placeholder.png`,
					width: '',
					height: '',
					alt: ''
				},
				title: __('Post name', 'rehub-framework'),
				copy: __('Content', 'rehub-framework'),
				badge: '',
				customBadge: {
					text: __('Best Values', 'rehub-framework'),
					textColor: '#fff',
					backgroundColor: '#77B21D'
				},
				currentPrice: '',
				oldPrice: '',
				button: {
					text: __('Buy this item', 'rehub-framework'),
					url: '',
					newTab: false,
					noFollow: false
				},
				coupon: '',
				maskCoupon: false,
				maskCouponText: '',
				expirationDate: '',
				offerExpired: false,
				readMore: __('Read full review', 'rehub-framework'),
				readMoreUrl: '',
				addToCartText: __('Read more', 'rehub-framework'),
			}
		]
	}
};

export default schema;
