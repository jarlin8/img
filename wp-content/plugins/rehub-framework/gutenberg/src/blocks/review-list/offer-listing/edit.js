/**
 * WordPress dependencies
 */
import {__} from '@wordpress/i18n';
import {Component, Fragment} from "@wordpress/element";
import {compose} from "@wordpress/compose";
import {withFocusOutside} from "@wordpress/components";

/**
 * Internal dependencies
 */
import Inspector from "./Inspector";
import Controls from "../components/Controls";
import OfferItem from '../components/OfferItem';
import AddItemButton from "../../../components/add-item-button";

/**
 * External dependencies
 */
import classnames from "classnames";
import {cloneDeep} from "lodash";

class EditBlock extends Component {
	constructor() {
		super(...arguments);
		this.state = {
			openUrlPopover: false
		};
		this.handleFocusOutside = this.handleFocusOutside.bind(this);
		this.handleButtonChange = this.handleButtonChange.bind(this);
		this.handleButtonClick = this.handleButtonClick.bind(this);
		this.handleAddItem = this.handleAddItem.bind(this);
	}

	handleFocusOutside() {
		this.setState({
			openUrlPopover: null,
		});
	}

	handleButtonChange(value, type, index) {
		const {attributes, setAttributes} = this.props;
		const {offers} = attributes;
		const offersClone = cloneDeep(offers);
		offersClone[index].button[type] = value;

		setAttributes({
			offers: offersClone
		});
	}

	handleButtonClick(index) {
		this.setState({openUrlPopover: index});
	}

	handleAddItem() {
		const {setAttributes, attributes} = this.props;
		const {offers} = attributes;
		const offersClone = cloneDeep(offers);

		offersClone.push({
			score: 10,
			enableBadge: true,
			enableScore: true,
			thumbnail: {
				id: '',
				url: `${window.RehubGutenberg.pluginDirUrl}/gutenberg/src/icons/noimage-placeholder.png`,
				width: '',
				height: '',
				alt: ''
			},
			title: __('Post name', 'rehub-framework'),
			copy: __('Content', 'rehub-framework'),
			customBadge: {
				text: __('Best Values', 'rehub-framework'),
				textColor: '#fff',
				backgroundColor: '#77B21D'
			},
			currentPrice: '',
			oldPrice: '',
			button: {
				text: __('Buy this item', 'rehub-framework'),
				url: '',
				newTab: false,
				noFollow: false
			},
			coupon: '',
			maskCoupon: false,
			maskCouponText: '',
			expirationDate: '',
			readMore: __('Read full review', 'rehub-framework'),
			readMoreUrl: '',
			disclaimer: __('Disclaimer text....', 'rehub-framework')
		});

		setAttributes({offers: offersClone});
	}

	render() {
		const {isSelected, className, attributes} = this.props;
		const {offers} = attributes;
		const mainClasses = classnames([className, 'c-offer-listing']);

		return (
			<Fragment>
				{isSelected && (
					<Fragment>
						<Inspector {...this.props} writable={false}/>
						<Controls {...this.props} />
					</Fragment>
				)}
				<div className={mainClasses}>
					{offers.map((offer, index) => {
						return (
							<OfferItem
								{...this.props}
								index={index}
								key={index}
								writable
								handleButtonChange={this.handleButtonChange}
								handleButtonClick={this.handleButtonClick}
								openUrlPopover={this.state.openUrlPopover}
							/>
						);
					})}
					<AddItemButton handleClick={this.handleAddItem} className='pt15'/>
				</div>
			</Fragment>
		);
	}
}

export default compose(
	withFocusOutside,
)(EditBlock);
