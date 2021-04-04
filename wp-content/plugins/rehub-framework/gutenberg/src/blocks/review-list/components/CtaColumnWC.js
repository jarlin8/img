const CtaColumnWC = (props) => {
	const {attributes, index} = props;
	const {offers} = attributes;
	const {
		addToCartText,
		priceHtml
	} = offers[index];

	return (
		<div className='c-offer-listing-cta'>
			<span className="font120 fontbold mb10 lineheight20 blockstyle text-center">
				<span className="price" dangerouslySetInnerHTML={{__html: priceHtml}}></span>
			</span>
			<div className='priced_block priced_block--sm'>
				<button className={"btn_offer_block"}>{addToCartText}</button>
			</div>
		</div>
	);
};

export default CtaColumnWC;
