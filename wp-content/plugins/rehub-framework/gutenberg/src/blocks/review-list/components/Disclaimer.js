/**
 * WordPress dependencies
 */
import {RichText} from '@wordpress/block-editor';
import {__} from '@wordpress/i18n';

/**
 * External dependencies
 */
import {cloneDeep} from "lodash";

const Disclaimer = (props) => {
	const {attributes, setAttributes, index, writable} = props;
	const {offers} = attributes;
	const {disclaimer} = offers[index];

	if (writable === false && disclaimer === '') {
		return null;
	}

	return (
		<div className='c-offer-listing-disclaimer'>
			{writable && (
				<RichText
					placeholder={__('Disclaimer', 'rehub-framework')}
					tagName="span"
					value={disclaimer}
					onChange={(value) => {
						const offersClone = cloneDeep(offers);
						offersClone[index].disclaimer = value;
						setAttributes({
							offers: offersClone
						});
					}}
					keepPlaceholderOnFocus
				/>
			)}
			{writable === false && (
				<span>{disclaimer}</span>
			)}
		</div>
	);
};

export default Disclaimer;