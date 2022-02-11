import schema from "./schema";
import {__} from '@wordpress/i18n';
import {assign} from 'lodash';

function checkMissedProps(attributes) {
	const {offers} = attributes;
	const propsToCheck = ['enableBadge', 'enableScore', 'maskCouponText', 'expirationDate'];

	return !propsToCheck.some((prop) => {
		return offers.some((offer) => {
			return prop in offer;
		})
	});
}

const deprecatedAttrs = [
	{
		attributes: schema,
		save: () => null,
		supports: {
			align: ['wide', 'full'],
			customClassName: false,
			html: false,
		},
		migrate(attributes) {
			const {offers} = attributes;
			const updatedOffers = offers.map((offer) => {
				return assign(offer, {
					enableBadge: true,
					enableScore: true,
					customBadge: {
						text: __('Best Values', 'rehub-framework'),
						textColor: '#fff',
						backgroundColor: '#77B21D'
					},
					maskCouponText: '',
					expirationDate: ''
				});
			});

			return assign(attributes, {
				offers: updatedOffers
			});
		},
		isEligible: function (attrs) {
			if (attrs) {
				if (!attrs.offers) {
					return false;
				}

				return checkMissedProps(attrs);
			}

			return false;
		}
	}
];

export default deprecatedAttrs;
