/**
 * Internal dependencies
 */
import Price from "./Price";
import Button from "./Button";

/**
 * WordPress dependencies
 */
import {RichText} from '@wordpress/block-editor';
import {__} from '@wordpress/i18n';

/**
 * External dependencies
 */
import {cloneDeep} from "lodash";
import Coupon from "../../../components/coupon";

const CtaColumn = (props) => {
	const {attributes, setAttributes, index, writable} = props;
	const {offers} = attributes;
	const {
		readMore,
		coupon,
		maskCoupon,
		maskCouponText,
		expirationDate,
		offerExpired,
	} = offers[index];

	const handleMaskChange = (value) => {
		const offersClone = cloneDeep(offers);
		offersClone[index].maskCouponText = value;
		setAttributes({offers: offersClone});
	};

	const handleCouponChange = (value) => {
		const offersClone = cloneDeep(offers);
		offersClone[index].coupon = value;
		setAttributes({offers: offersClone});
	};

	return (
		<div className='c-offer-listing-cta'>
			<Price {...props}/>
			<div className='priced_block priced_block--sm'>
				<Button {...props}/>
				<Coupon
					couponCode={coupon}
					maskCoupon={maskCoupon}
					maskCouponText={maskCouponText}
					offerExpired={offerExpired}
					expirationDate={expirationDate}
					writable={writable}
					onMaskChange={handleMaskChange}
					onCouponChange={handleCouponChange}
					hideExpires
				/>
			</div>
			{writable && (
				<RichText
					placeholder={__('Read full review', 'rehub-framework')}
					tagName="span"
					className='c-offer-listing__read-more'
					value={readMore}
					onChange={(value) => {
						const offersClone = cloneDeep(offers);
						offersClone[index].readMore = value;
						setAttributes({
							offers: offersClone
						});
					}}
				/>
			)}
			{writable === false && (
				<span className='c-offer-listing__read-more'>{readMore}</span>
			)}
		</div>
	);
};

export default CtaColumn;
