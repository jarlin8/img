import classnames from 'classnames';

export default function LocalRender(props) {
	const {
		      offer_url,
		      offer_title,
		      offer_desc,
		      disclaimer,
		      offer_price_old,
		      offer_price,
		      percentageSaved,
		      rating,
		      offer_coupon,
		      offer_coupon_date,
		      offer_coupon_mask,
		      offer_coupon_mask_text,
		      image_id,
		      coupon_text
	      } = props.attributes;


	return <div className={classnames([
		'bigofferblock', 'pt20', 'pl20', 'pr20'
	])}>
		<div className={'col_wrap_two mb0'}>
			<div className={'product_egg'}>
				<div className={'image col_item mobileblockdisplay'}>
					<a href={offer_url} className="re_track_btn">
						<img src={image_id}/>
						{percentageSaved && <span className="sale_a_proc">-{percentageSaved}%</span>}
					</a>
				</div>
				<div className="product-summary col_item mobileblockdisplay">
					<h2 className="product_title entry-title">
						<a href={offer_url} className="re_track_btn">{offer_title}</a>
					</h2>
					{rating > 0 && rating <= 5 && <div className="cegg-rating">
						{Array(rating).fill(<span>★</span>)}
						{Array(5 - rating).fill(<span>☆</span>)}
					</div>}

					{offer_price && <div className="deal-box-price">
						{offer_price}
						{offer_price_old &&
						<span className="retail-old">
							<strike>{offer_price_old}</strike>
						</span>}

					</div>}
					{disclaimer &&
					<div className="rev_disclaimer font70 greencolor lineheight15 mb15">{disclaimer}</div>}
					<div className="buttons_col">
						<div className="priced_block clearfix">
							<div>
								<a className="re_track_btn btn_offer_block" href={offer_url}>
									Buy this item
								</a>
							</div>
							{offer_coupon && (offer_coupon_mask ? <div
								className="rehub_offer_coupon mt15 not_masked_coupon"
								data-clipboard-text={offer_coupon}
							>
								<i className="rhicon rhi-cut fa-rotate-180"></i>
								<span className="coupon_text">{offer_coupon}</span>
							</div> : <div
								className="rehub_offer_coupon mt15 free_coupon_width masked_coupon"
								data-clipboard-text={offer_coupon}
								data-codetext={offer_coupon}
								data-dest={offer_url}
							>
								{offer_coupon_mask_text}
								<i className="rhicon rhi-external-link-square"></i>
							</div>)}
							{offer_coupon_date && <div className="time_offer">{coupon_text}</div>}
						</div>
					</div>
					{offer_desc && <div className="bigofferdesc">{offer_desc}</div>}
				</div>

			</div>
		</div>
	</div>
}
