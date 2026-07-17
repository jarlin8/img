/**
 * WordPress dependencies
 */
import {RichText} from '@wordpress/block-editor';
import {__} from '@wordpress/i18n';
import {cloneDeep} from "lodash";
import {Fragment} from "@wordpress/element";

/**
 * Internal dependencies
 */
import UrlInputPopover from "../../../components/url-input-popover";
import Coupon from "../../../components/coupon";

const ContentColumn = (props) => {
	const {attributes, setAttributes, onButtonClick, openUrlPopover, onButtonChange, writable} = props;
	const {
		      name,
		      sale_price,
		      old_price,
		      disclaimer,
		      button,
		      coupon_code,
		      mask_coupon_code,
		      mask_coupon_text,
		      expiration_date,
		      offer_is_expired,
		      description,
		      hide_old_price
	      } = attributes;

	const rating = parseInt(attributes.rating);

	const handleMaskChange = (value) => {
		setAttributes({
			mask_coupon_text: value
		});
	};

	const handleCouponChange = (value) => {
		setAttributes({
			coupon_code: value
		});
	};

	return (
		<div className="c-offer-box__column">
			{/* Title */}
			{writable && (
				<RichText
					placeholder={__('Product Name', 'rehub-framework')}
					tagName="h2"
					className="c-offer-box__title"
					value={name}
					onChange={(value) => {
						setAttributes({
							name: value
						});
					}}
					keepPlaceholderOnFocus/>
			)}
			{writable === false && (
				<h2 className='c-offer-box__title'>{name ? name : __('Product name', 'rehub-framework')}</h2>
			)}
			{rating > 0 && (
				<div className="c-offer-box__rating">
					{[...Array(rating).keys()].map((item) => {
						return <span key={item}>&#x2605;</span>;
					})}
					{[...Array(5 - rating).keys()].map((item) => {
						return <span key={item}>☆</span>;
					})}
				</div>
			)}

			{/* Price */}
			<div className="c-offer-box__price">
				{writable && (
					<Fragment>
						<RichText
							placeholder={__('50', 'rehub-framework')}
							tagName="span"
							value={sale_price}
							onChange={(value) => {
								setAttributes({
									sale_price: value
								});
							}}
							keepPlaceholderOnFocus/>
						{!hide_old_price && (
							<span className="retail-old">
							<RichText
								placeholder={__('100', 'rehub-framework')}
								tagName="strike"
								value={old_price}
								onChange={(value) => {
									setAttributes({
										old_price: value
									});
								}}
								keepPlaceholderOnFocus/>
						</span>
						)}
					</Fragment>
				)}
				{writable === false && (
					<Fragment>
						<span>{sale_price}</span>
						<span className='retail-old'><strike>{old_price}</strike></span>
					</Fragment>
				)}
			</div>

			{/* Disclaimer */}
			<div className="c-offer-box__disclaimer">
				{writable && (
					<RichText
						placeholder={__('Disclaimer', 'rehub-framework')}
						tagName="span"
						value={disclaimer}
						onChange={(value) => {
							setAttributes({
								disclaimer: value
							});
						}}
						keepPlaceholderOnFocus/>
				)}
				{writable === false && (
					<span>{disclaimer}</span>
				)}
			</div>

			{/* CTA */}
			<div className='priced_block'>
				{writable && (
					<div onClick={onButtonClick}>
						<div className="btn_offer_block">
							<RichText
								placeholder={__('Buy this item', 'rehub-framework')}
								tagName="span"
								value={button.text}
								onChange={(value) => {
									const buttonClone = cloneDeep(button);
									buttonClone.text = value;
									setAttributes({
										button: buttonClone
									});
								}}
								keepPlaceholderOnFocus/>
						</div>
						{openUrlPopover && (
							<UrlInputPopover
								value={button.url}
								newTab={button.newTab}
								noFollow={button.noFollow}
								onChange={value => onButtonChange(value, 'url')}
								onChangeNewTab={value => onButtonChange(value, 'newTab')}
								onChangeNoFollow={value => onButtonChange(value, 'noFollow')}/>
						)}
					</div>
				)}
				{writable === false && (
					<div>
						<div className='btn_offer_block'>
							<span>{button.text}</span>
						</div>
					</div>
				)}
				<Coupon
					couponCode={coupon_code}
					maskCoupon={mask_coupon_code}
					maskCouponText={mask_coupon_text}
					offerExpired={offer_is_expired}
					expirationDate={expiration_date}
					writable={writable}
					onMaskChange={handleMaskChange}
					onCouponChange={handleCouponChange}
					hideExpires={false}
				/>
			</div>

			{/* Description	*/}
			<div className="c-offer-box__desc">
				{writable && (
					<RichText
						placeholder={__('Description', 'rehub-framework')}
						tagName="span"
						value={description}
						onChange={(value) => {
							setAttributes({
								description: value
							});
						}}
						keepPlaceholderOnFocus
					/>
				)}
				{writable === false && (
					<span>{description}</span>
				)}
			</div>
		</div>
	);
};

export default ContentColumn;
