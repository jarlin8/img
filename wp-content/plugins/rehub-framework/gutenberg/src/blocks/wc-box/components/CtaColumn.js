/**
 * WordPress dependencies
 */
import {__} from '@wordpress/i18n';

/**
 * External dependencies
 */
import classnames from "classnames";

const CtaColumn = (props) => {
	const {attributes} = props;
	const {
		      productType,
		      productUrl,
		      addToCartText,
		      maskText,
		      coupon,
		      couponExpiredDate,
		      brandList,
		      couponMasked,
		      isCompareEnabled,
		      isCouponExpired,
		      isItemSyncEnabled,
		      productInStock
	      } = attributes;

	let button = null;
	let maskedButton = null;
	const classes = classnames([
		'c-ws-box-cta',
		{
			'c-ws-box-cta--masked': couponMasked,
			'c-ws-box-cta--expired': isCouponExpired,
		}
	]);

	if (productType === 'external' && productUrl === '' && isItemSyncEnabled) {
		button = <button className='c-ws-box-cta__btn'>{__('Check Deals', 'rehub-framework')}</button>;
	} else {
		if (productInStock && productUrl !== '') {
			button = <button className='c-ws-box-cta__btn'>{addToCartText}</button>;
		}

		if (productInStock && couponMasked && isCouponExpired === false) {
			maskedButton = <button className='c-ws-box-cta__btn c-ws-box-cta__btn--masked'>{maskText}</button>;
		}
	}

	return (
		<div className={classes}>
			<div className='c-ws-box-actions'>
				<div className='c-ws-box-wish'/>
				{isCompareEnabled && (
					<div className='c-ws-box-compare'>
						<i className="rhicon re-icon-compare"/>
					</div>
				)}
			</div>
			{button}
			{maskedButton}
			{((couponMasked === false || isCouponExpired) && coupon !== '') && (
				<div className='c-ws-box-coupon'>
					<i className='rhicon rhi-cut fa-rotate-180'/>
					<span className='c-ws-box-coupon__text'>{coupon}</span>
				</div>
			)}
			{couponExpiredDate !== '' && (
				<div className="c-ws-box-expire">{couponExpiredDate}</div>
			)}
			{brandList !== '' && (
				<div className='c-ws-box-brands'>{brandList}</div>
			)}
		</div>
	);
};

export default CtaColumn;