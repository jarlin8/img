/**
 * WordPress dependencies
 */
import {Component, Fragment, RawHTML, createRef} from '@wordpress/element';
import {compose} from "@wordpress/compose";
import {withFocusOutside, Spinner} from "@wordpress/components";
import {__} from '@wordpress/i18n';

/**
 * External dependencies
 */
import classnames from "classnames";

/**
 * Internal dependencies
 */
import Inspector from "./Inspector";
import Controls from './Controls';
import ContentColumn from "./components/ContentColumn";
import CtaColumn from "./components/CtaColumn";
import Gallery from "./components/Gallery";
import Videos from "./components/Videos";
import updateProductData from "./util/updateProductData";

class EditBlock extends Component {
	constructor() {
		super(...arguments);
		this.blockRef = createRef();
		updateProductData(this.props.attributes.productId, this.props.setAttributes);
	}

	componentDidMount() {
		const block = this.blockRef.current;
		const tabs = jQuery(block).find('.c-ws-box-tabs');

		tabs.on('click', 'li:not(.current)', function () {
			jQuery(this).addClass('current').siblings().removeClass('current');
			jQuery(block).find('.c-ws-box-tab').hide().eq(jQuery(this).index()).fadeIn(700);
		});
	}

	render() {
		const {className, isSelected, attributes} = this.props;
		const {
			      loading,
			      imageUrl,
			      description,
			      productAttributes,
			      galleryImages,
			      videoThumbnails,
			      syncItems,
			      isCouponExpired
		      } = attributes;
		const mainClasses = classnames([
			'c-ws-box',
			{
				'c-ws-box--loading': loading,
				'c-ws-box--expired': isCouponExpired
			}
		]);

		const showTabs = (
			productAttributes.length > 0 || galleryImages.length > 0 || videoThumbnails.length > 0 || syncItems !== ''
		);

		return (
			<Fragment>
				{isSelected && (
					<Fragment>
						<Inspector {...this.props} />
						<Controls {...this.props} />
					</Fragment>
				)}

				<div className={className} ref={this.blockRef}>
					<ul className='c-ws-box-tabs'>
						{showTabs && (
							<Fragment>
								<li className='current'>
									{__('Product', 'rehub-framework')}
								</li>
								{productAttributes.length > 0 && (
									<li>
										{__('Specification', 'rehub-framework')}
									</li>
								)}
								{galleryImages.length > 0 && (
									<li>
										{__('Photos', 'rehub-framework')}
									</li>
								)}
								{videoThumbnails.length > 0 && (
									<li>
										{__('Videos', 'rehub-framework')}
									</li>
								)}
								{syncItems !== '' && (
									<li>
										{__('Deals', 'rehub-framework')}
									</li>
								)}
							</Fragment>
						)}
					</ul>
					<div className={mainClasses}>
						<Spinner/>
						<div className='c-ws-box-tab'>
							<div className="c-ws-box__wrapper">
								<div className="c-ws-box-image">
									<img src={imageUrl} alt=""/>
								</div>
								<ContentColumn {...this.props} />
								<div className="c-ws-box-content-desc">
									<RawHTML>{description}</RawHTML>
								</div>
								<CtaColumn {...this.props} />
							</div>
						</div>
						{productAttributes.length > 0 && (
							<div className="c-ws-box-tab d-none">
								<RawHTML>{productAttributes}</RawHTML>
							</div>
						)}
						{galleryImages.length > 0 && (
							<div className="c-ws-box-tab d-none">
								<Gallery items={galleryImages}/>
							</div>
						)}
						{videoThumbnails.length > 0 && (
							<div className="c-ws-box-tab d-none">
								<Videos items={videoThumbnails}/>
							</div>
						)}
						{syncItems !== '' && (
							<div className="c-ws-box-tab d-none">
								<RawHTML>{syncItems}</RawHTML>
							</div>
						)}
					</div>
				</div>
			</Fragment>
		);
	}
}

export default compose(
	withFocusOutside
)(EditBlock);