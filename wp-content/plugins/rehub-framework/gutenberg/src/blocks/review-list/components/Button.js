/**
 * WordPress dependencies
 */
import {RichText} from '@wordpress/block-editor';
import {__} from '@wordpress/i18n';

/**
 * External dependencies
 */
import {cloneDeep} from "lodash";

/**
 * Internal dependencies
 */
import UrlInputPopover from "../../../components/url-input-popover";

const Button = (props) => {
	const {attributes, setAttributes, index, writable, handleButtonChange, handleButtonClick, openUrlPopover} = props;
	const {offers} = attributes;
	const {button} = offers[index];
	if (writable === false && button.url === '') {
		return null;
	}

	return (
		<div className='btn_offer_block' onClick={() => handleButtonClick(index)}>
			{writable && (
				<RichText
					placeholder={__('Buy this item', 'rehub-framework')}
					tagName="span"
					value={button.text}
					onChange={(value) => {
						const offersClone = cloneDeep(offers);
						offersClone[index].button.text = value;
						setAttributes({
							offers: offersClone
						});
					}}
					keepPlaceholderOnFocus
				/>
			)}
			{openUrlPopover === index && (
				<UrlInputPopover
					value={button.url}
					newTab={button.newTab}
					noFollow={button.noFollow}
					onChange={value => handleButtonChange(value, 'url', index)}
					onChangeNewTab={value => handleButtonChange(value, 'newTab', index)}
					onChangeNoFollow={value => handleButtonChange(value, 'noFollow', index)}
				/>
			)}
			{writable === false && (
				<span>{button.text}</span>
			)}
		</div>
	);
};

export default Button;
