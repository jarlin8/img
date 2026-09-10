/**
 * WordPress dependencies
 */
import {__} from '@wordpress/i18n';

export default function updateProductData(value, setAttributes) {
	if (value.length === 0) {
		return false;
	}
	setAttributes({
		loading: true,
		productId: value.toString(),
		parseError: '',
		parseSuccess: ''
	});

	wp.apiFetch({
		path: `/rehub/v2/product/${value}`,
		method: 'GET',
	}).then(response => {
		const data = JSON.parse(response);

		setAttributes({
			loading: false,
			parseError: '',
			parseSuccess: __('Fields updated', 'rehub-framework'),
			...data
		});
	}).catch(error => {
		setAttributes({
			loading: false,
			parseError: error.message,
			parseSuccess: ''
		});
	});
}