/**
 * External dependencies
 */
import classnames from "classnames";

/**
 * WordPress dependencies
 */
import {Component, Fragment} from "@wordpress/element";
import {compose} from "@wordpress/compose";
import {Spinner, withFocusOutside} from "@wordpress/components";

/**
 * Internal dependencies
 */
import Inspector from "./inspector";
import Controls from "../editor-components/controls";
import ImageColumn from "../components/ImageColumn";
import ContentColumn from "../components/ContentColumn";
import {updateOfferData} from '../utils/fetchService';
import {calculateExpiredDays} from "../../../util";

class EditBlock extends Component {
	constructor() {
		super(...arguments);

		updateOfferData(this.props.attributes.selectedPost, this.props.setAttributes, this.props.attributes);
	}

	render() {
		const {className, isSelected, attributes} = this.props;
		const {borderColor, loading, mask_coupon_code, offer_is_expired, expiration_date} = attributes;

		let expiredByDate = false;

		if (expiration_date) {
			expiredByDate = calculateExpiredDays(expiration_date) < 0;
		}

		const mainClasses = classnames([
			className,
			'c-offer-box',
			{
				'c-offer-box--loading': loading,
				'reveal_enabled': mask_coupon_code && !(expiredByDate || offer_is_expired)
			}
		]);

		const styles = {
			border: borderColor ? '2px solid' : '',
			borderColor: borderColor
		};

		return (
			<Fragment>
				{isSelected && (
					<Fragment>
						<Inspector {...this.props} writable={false}/>
						<Controls {...this.props} />
					</Fragment>
				)}
				<div className={mainClasses} style={styles}>
					<Spinner/>
					<div className="c-offer-box__wrapper">
						<ImageColumn {...this.props} writable={false}/>
						<ContentColumn{...this.props} writable={false}/>
					</div>
				</div>
			</Fragment>
		);
	}
}

export default compose(
	withFocusOutside,
)(EditBlock);
