/**
 * WordPress dependencies
 */
import {RichText} from '@wordpress/block-editor';
import {__} from '@wordpress/i18n';
import {Fragment, RawHTML} from "@wordpress/element";

/**
 * External dependencies
 */
import {cloneDeep} from "lodash";

const ContentColumn = (props) => {
	const {attributes, setAttributes, index, writable} = props;
	const {offers} = attributes;
	const {title, copy, badge, enableBadge, customBadge} = offers[index];

	return (
		<div className="c-offer-listing-content">
			{writable && (
				<Fragment>
					<h3 className="c-offer-listing__title">
						<RichText
							placeholder={__('Post name', 'rehub-framework')}
							tagName="span"
							value={title}
							onChange={(value) => {
								const offersClone = cloneDeep(offers);
								offersClone[index].title = value;
								setAttributes({
									offers: offersClone
								});
							}}
							keepPlaceholderOnFocus
						/>
						{enableBadge && (
							<span className='blockstyle'>
								<span className='re-line-badge re-line-badge--default'
								      style={{
									      backgroundColor: customBadge.backgroundColor,
									      color: customBadge.textColor
								      }}>
									<RichText
										placeholder={__('Best values', 'rehub-framework')}
										tagName='span'
										value={customBadge.text}
										onChange={(value) => {
											const offersClone = cloneDeep(offers);
											offersClone[index].customBadge.text = value;
											setAttributes({offers: offersClone});
										}}
										keepPlaceholderOnFocus
									/>
								</span>
							</span>
						)}
					</h3>
					<div className='c-offer-listing__copy'>
						<RichText
							placeholder={__('Content', 'rehub-framework')}
							tagName="span"
							value={copy}
							onChange={(value) => {
								const offersClone = cloneDeep(offers);
								offersClone[index].copy = value;
								setAttributes({
									offers: offersClone
								});
							}}
							keepPlaceholderOnFocus
						/>
					</div>
				</Fragment>
			)}
			{writable === false && (
				<Fragment>
					<h3 className='c-offer-listing__title'>
						{title}
						{badge !== '' && (
							<span className='blockstyle'>
								<RawHTML>{badge}</RawHTML>
							</span>
						)}
					</h3>
					<div className='c-offer-listing__copy'>{copy}</div>
				</Fragment>
			)}
		</div>
	);
};

export default ContentColumn;