/**
 * WordPress dependencies
 */
import {__} from '@wordpress/i18n';
import {Fragment, RawHTML} from "@wordpress/element";

const ContentColumn = (props) => {
	const {attributes} = props;
	const {
		      isExpired,
		      productName,
		      codeZone,
		      regularPrice,
		      salePrice,
		      currencySymbol,
		      priceLabel
	      } = attributes;

	return (
		<div className="c-ws-box-content">
			<h3 className='c-ws-box-title'>
				{isExpired && (
					<span className="rh-expired-notice">{__('Expired', 'rehub-framework')}</span>
				)}
				{productName}
			</h3>
			{codeZone !== '' && (
				<div className='c-ws-box-content__code-zone'>
					<RawHTML>{codeZone}</RawHTML>
				</div>
			)}
			{+regularPrice > 0 && (
				<Fragment>
					<span className='c-ws-box-price'>
						{+salePrice > 0 && (
							<Fragment>
								<del><RawHTML>{currencySymbol}</RawHTML>{Math.ceil(regularPrice * 100) / 100}</del>
								<ins><RawHTML>{currencySymbol}</RawHTML>{Math.ceil(salePrice * 100) / 100}</ins>
							</Fragment>
						)}
						{+salePrice === 0 && (
							<ins><RawHTML>{currencySymbol}</RawHTML>{Math.ceil(regularPrice * 100) / 100}</ins>
						)}
					</span>
				</Fragment>
			)}
			{priceLabel && (
				<span className='c-ws-box-price__label'>{priceLabel}</span>
			)}
			<div className="clearfix"/>
		</div>
	);
};

export default ContentColumn;