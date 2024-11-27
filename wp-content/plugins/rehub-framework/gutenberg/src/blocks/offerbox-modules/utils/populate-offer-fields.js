import {select} from '@wordpress/data';

const META_BOX_SELECTORS = {
	offerUrl: '#rehub_offer_product_url',
	name: '#rehub_offer_name',
	description: '#rehub_offer_product_desc',
	disclaimer: '#rehub_offer_disclaimer',
	old_price: '#rehub_offer_product_price_old',
	sale_price: '#rehub_offer_product_price',
	coupon_code: '#rehub_offer_product_coupon',
	expiration_date: '#rehub_offer_coupon_date',
	mask_coupon_code: '#rehub_offer_coupon_mask',
	// offer_is_expired: '#re_post_expired',
	button_text: '#rehub_offer_btn_text',
	thumbnail_url: "[name='rehub_offer_product_thumb']",
	// brand_logo_url: '#rehub_offer_logo_url',
	// discount: '#rehub_offer_discount'
};

function isMetaBoxExist() {
	return select('core/edit-post').getAllMetaBoxes().some((metaBox) => {
		return metaBox.id = 'post_rehub_offers';
	});
}

export function populateOfferFields(props) {
	const {attributes} = props;
	const {button, thumbnail, expiration_date, mask_coupon_code} = attributes;

	if (isMetaBoxExist() === false) {
		return;
	}

	for (let key in META_BOX_SELECTORS) {
		switch (key) {
			case 'offerUrl':
				document.querySelector(META_BOX_SELECTORS[key]).value = button.url;
				break;
			case 'expiration_date':
				document.querySelector(META_BOX_SELECTORS[key]).value = expiration_date.substring(0, 10);
				break;
			case 'mask_coupon_code':
				document.querySelector(META_BOX_SELECTORS[key]).checked = mask_coupon_code;
				break;
			case 'button_text':
				document.querySelector(META_BOX_SELECTORS[key]).value = button.text;
				break;
			case 'thumbnail_url':
				document.querySelector(META_BOX_SELECTORS[key]).value = thumbnail.url;
				break;
			default:
				document.querySelector(META_BOX_SELECTORS[key]).value = attributes[key];
				break;
		}
	}
}

export default populateOfferFields;