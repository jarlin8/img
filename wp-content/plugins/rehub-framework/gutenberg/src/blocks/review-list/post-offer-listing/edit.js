/**
 * WordPress dependencies
 */
import {Component, Fragment} from "@wordpress/element";
import {compose} from "@wordpress/compose";
import {Spinner, withFocusOutside} from "@wordpress/components";

/**
 * Internal dependencies
 */
import Inspector from "./Inspector";
import Controls from "../components/Controls";
import OfferItem from '../components/OfferItem';
import {fetchReviewData} from '../utils/fetchReviewData';

/**
 * External dependencies
 */
import classnames from "classnames";

class EditBlock extends Component {
	constructor() {
		super(...arguments);
		fetchReviewData(this.props.attributes.selectedPosts, this.props.setAttributes);
	}

	render() {
		const {isSelected, className, attributes} = this.props;
		const {loading, offers} = attributes;
		const mainClasses = classnames([
			className,
			'c-offer-listing',
			{'c-offer-listing--loading': loading}
		]);

		return (
			<Fragment>
				{isSelected && (
					<Fragment>
						<Inspector {...this.props} writable={false}/>
						<Controls {...this.props} />
					</Fragment>
				)}
				<div className={mainClasses}>
					<Spinner/>
					{offers.map((offer, index) => {
						return (
							<OfferItem
								{...this.props}
								index={index}
								key={index}
								writable={false}
							/>
						);
					})}
				</div>
			</Fragment>
		);
	}
}

export default compose(
	withFocusOutside,
)(EditBlock);
