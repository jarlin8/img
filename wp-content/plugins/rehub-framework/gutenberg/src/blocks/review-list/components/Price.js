/**
 * WordPress dependencies
 */
import {Fragment} from "@wordpress/element";
import {RichText} from '@wordpress/block-editor';
import {__} from '@wordpress/i18n';

/**
 * External dependencies
 */
import {cloneDeep} from "lodash";

const Price = (props) => {
	const {attributes, setAttributes, index, writable} = props;
	const {offers} = attributes;
	const {currentPrice, oldPrice} = offers[index];

	return (
		<div className='c-offer-listing-price'>
			{writable && (
				<Fragment>
					<RichText
						placeholder={__('100', 'rehub-framework')}
						tagName="span"
						value={currentPrice}
						onChange={(value) => {
							const offersClone = cloneDeep(offers);
							offersClone[index].currentPrice = value;
							setAttributes({
								offers: offersClone
							});
						}}
						keepPlaceholderOnFocus
					/>
					<RichText
						placeholder={__('200', 'rehub-framework')}
						tagName="del"
						value={oldPrice}
						onChange={(value) => {
							const offersClone = cloneDeep(offers);
							offersClone[index].oldPrice = value;
							setAttributes({
								offers: offersClone
							});
						}}
						keepPlaceholderOnFocus
					/>
				</Fragment>
			)}
			{writable === false && (
				<Fragment>
					<span>{currentPrice}</span>
					<del>{oldPrice}</del>
				</Fragment>
			)}
		</div>
	);
};

export default Price;