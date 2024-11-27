/**
 * WordPress dependencies
 */
import {__} from '@wordpress/i18n';
import {RichText} from "@wordpress/block-editor";
import {Component, Fragment} from '@wordpress/element';
import {compose} from "@wordpress/compose";
import {withFocusOutside} from "@wordpress/components";

/**
 * External dependencies
 */
import classnames from "classnames";
import {cloneDeep} from "lodash";

/**
 * Internal dependencies
 */
import Inspector from "./Inspector";
import Controls from './Controls';
import ImageUploadPlaceholder from "../../components/image-upload-placeholder";

class EditBlock extends Component {
	render() {
		const {className, isSelected, attributes, setAttributes} = this.props;
		const mainClasses = classnames([className, 'rh-review-heading', 'rh-flex-center-align']);
		const {includePosition, position, title, titleTag, subtitle, includeImage, image} = attributes;

		return (
			<Fragment>
				{isSelected && (
					<Fragment>
						<Inspector {...this.props} />
						<Controls {...this.props} />
					</Fragment>
				)}
				<div className={mainClasses}>
					{includePosition && (
						<div className="rh-review-heading__position mr15 font150">
							<RichText
								placeholder={__('1', 'rehub-framework')}
								tagName="span"
								className="fontbold lightgreycolor font250"
								value={position}
								onChange={(value) => {
									setAttributes({position: value});
								}}
								keepPlaceholderOnFocus
							/>
						</div>
					)}
					<div>
						<RichText
							placeholder={__('Title', 'rehub-framework')}
							tagName={titleTag}
							className="mt0 mb0"
							value={title}
							onChange={(value) => {
								setAttributes({title: value});
							}}
							keepPlaceholderOnFocus
						/>
						<RichText
							placeholder={__('Subtitle', 'rehub-framework')}
							tagName="div"
							className="mt5 lineheight20 greycolor"
							value={subtitle}
							onChange={(value) => {
								setAttributes({subtitle: value});
							}}
							keepPlaceholderOnFocus
						/>
					</div>
					{includeImage && (
						<div className="rh-review-heading__logo rh-flex-right-align">
							<div className="rh-review-heading__logo-container">
								<ImageUploadPlaceholder
									imageID={image.id}
									imageURL={image.url}
									onRemove={() => {
										const imageClone = cloneDeep(image);
										imageClone.id = '';
										imageClone.url = '';
										imageClone.width = '';
										imageClone.height = '';
										imageClone.alt = '';
										setAttributes({image: imageClone});
									}}
									onChange={media => {
										const imageClone = cloneDeep(image);
										imageClone.id = media.id;
										imageClone.url = media.url;
										imageClone.width = media.width;
										imageClone.height = media.height;
										imageClone.alt = media.alt;
										setAttributes({image: imageClone});
									}}
								/>
							</div>
						</div>
					)}
				</div>
			</Fragment>
		);
	}
}

export default compose(
	withFocusOutside
)(EditBlock);