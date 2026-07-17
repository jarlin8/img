/**
 * WordPress dependencies
 */
import {__} from '@wordpress/i18n';
import {Component, Fragment} from '@wordpress/element';
import {RichText} from "@wordpress/block-editor";

/**
 * External dependencies
 */
import classnames from 'classnames';
import Expired from "../expired";
import {calculateExpiredDays} from "../../util";

class Coupon extends Component {
	render() {
		const {
			couponCode,
			maskCoupon,
			maskCouponText,
			offerExpired,
			expirationDate,
			writable,
			onMaskChange,
			onCouponChange,
			hideExpires
		} = this.props;

		let expiredByDate = false;

		if (expirationDate) {
			expiredByDate = calculateExpiredDays(expirationDate) < 0;
		}

		const classes = classnames([
			'rehub_offer_coupon',
			{
				'mt15': !maskCoupon || (maskCoupon && (offerExpired || expiredByDate)),
				'masked_coupon ': maskCoupon && !(offerExpired || expiredByDate),
				'btn_offer_block': maskCoupon && !(offerExpired || expiredByDate),
				'coupon_btn': maskCoupon && !(offerExpired || expiredByDate),
				'expired_coupon': offerExpired || expiredByDate
			}
		]);

		if (couponCode === '') {
			return null;
		}

		if (maskCoupon && !offerExpired && !expiredByDate) {
			return (
				<Fragment>
					<div className={classes}>
						{writable && (
							<RichText
								placeholder={__('Reveal', 'rehub-framework')}
								tagName="span"
								className="coupon_text"
								value={maskCouponText}
								onChange={onMaskChange}
								keepPlaceholderOnFocus
							/>
						)}
						{writable === false && (
							<span className='coupon_text'>{maskCouponText}</span>
						)}
						<i className="rhicon rhi-external-link-square"/>
					</div>
					{!hideExpires && (
						<Expired
							offerExpired={offerExpired}
							expirationDate={expirationDate}
						/>
					)}
				</Fragment>
			);
		} else {
			return (
				<Fragment>
					<div className={classes}>
						<i className="rhicon rhi-cut fa-rotate-180"/>
						{writable && (
							<RichText
								placeholder={__('code_of_coupon', 'rehub-framework')}
								tagName="span"
								className="coupon_text"
								value={couponCode}
								onChange={onCouponChange}
								keepPlaceholderOnFocus
							/>
						)}
						{writable === false && (
							<span className='coupon_text'>{couponCode}</span>
						)}
					</div>
					{!hideExpires && (
						<Expired
							offerExpired={offerExpired}
							expirationDate={expirationDate}
						/>
					)}
				</Fragment>
			);
		}
	}
}

export default Coupon;
