const Discount = (props) => {
	const {discount_tag} = props;

	if (discount_tag > 0) {
		return (
			<span className='c-offer-box__discount'>-{discount_tag}%</span>
		);
	} else {
		return null;
	}
};

export default Discount;