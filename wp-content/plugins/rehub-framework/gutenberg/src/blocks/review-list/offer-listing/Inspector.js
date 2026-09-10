/**
 * Internal dependencies
 */
import OfferCardList from "../components/OfferCardList";

/**
 * External dependencies
 */
import {cloneDeep} from "lodash";

/**
 * WordPress dependencies
 */
import {__} from '@wordpress/i18n';
import {Component} from '@wordpress/element'
import {InspectorControls} from '@wordpress/block-editor';
import {PanelBody, Button, BaseControl} from '@wordpress/components';

/**
 * Create an Inspector Controls wrapper Component
 */
export default class Inspector extends Component {
	constructor(props) {
		super(props);
		this.handleClick = this.handleClick.bind(this);
	}

	handleClick() {
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

		setAttributes({
			offers: offersClone
		});
	}

	render() {
		return (
			<InspectorControls>
				<PanelBody title={__('Manual Fields', 'rehub-framework')} initialOpen={true}>
					<OfferCardList {...this.props}/>
				</PanelBody>
				<BaseControl className='text-center'>
					<Button isPrimary onClick={this.handleClick}>
						{__('Add item', 'rehub-framework')}
					</Button>
				</BaseControl>
			</InspectorControls>
		);
	}
}
