/**
 * Internal dependencies
 */
import ImageColumn from "./ImageColumn";
import ContentColumn from "./ContentColumn";
import CtaColumn from "./CtaColumn";
import CtaColumnWC from "./CtaColumnWC";
import Disclaimer from "./Disclaimer";
import {calculateExpiredDays} from "../../../util";

/**
 * External dependencies
 */
import classnames from "classnames";

const OfferItem = (props) => {
	const {attributes, setAttributes, index, writable, handleButtonChange, handleButtonClick, openUrlPopover} = props;
	const {maskCoupon, expirationDate, offerExpired} = attributes.offers[index];

	let expiredByDate = false;

	if (expirationDate) {
		expiredByDate = calculateExpiredDays(expirationDate) < 0;
	}

	const classes = classnames([
		'c-offer-listing-item',
		{'reveal_enabled': maskCoupon && !(offerExpired || expiredByDate)}
	]);

	return (
		<div className={classes}>
			<div className="c-offer-listing-item__wrapper">
				<ImageColumn
					attributes={attributes}
					setAttributes={setAttributes}
					index={index}
					writable={writable}
				/>
				<ContentColumn
					attributes={attributes}
					setAttributes={setAttributes}
					index={index}
					writable={writable}
				/>
				{ props.name !== "rehub/woocommerce-list" && <CtaColumn
					attributes={attributes}
					setAttributes={setAttributes}
					index={index}
					writable={writable}
					handleButtonChange={handleButtonChange}
					handleButtonClick={handleButtonClick}
					openUrlPopover={openUrlPopover}
					name = {props.name}
				/> }
				{ props.name === "rehub/woocommerce-list" && <CtaColumnWC
					attributes={attributes}
					setAttributes={setAttributes}
					index={index}
					writable={writable}
					handleButtonChange={handleButtonChange}
					handleButtonClick={handleButtonClick}
					openUrlPopover={openUrlPopover}
					name = {props.name}
				/> }
			</div>
			{ props.name !== "rehub/woocommerce-list" && <Disclaimer
				attributes={attributes}
				setAttributes={setAttributes}
				index={index}
				writable={writable}
			/> }
		</div>
	);
};

export default OfferItem;
