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

/**
 * Internal dependencies
 */
import Inspector from "./Inspector";
import Controls from './Controls';

class EditBlock extends Component {
	render() {
		const {className, isSelected, attributes, setAttributes} = this.props;
		const {title, subtitle, backgroundColor, titleColor, subtitleColor} = attributes;
		const mainClasses = classnames([className, 'rh-color-heading', 'pt30', 'pb30', 'blackcolor', 'pl15', 'pr15']);

		const styles = {
			backgroundColor
		};

		const titleStyles = {
			color: titleColor
		};

		const subtitleStyles = {
			color: subtitleColor
		};

		return (
			<Fragment>
				{isSelected && (
					<Fragment>
						<Inspector {...this.props} />
						<Controls {...this.props} />
					</Fragment>
				)}
				<div className={mainClasses} style={styles}>
					<div className="rh-container">
						<div className="rh-flex-columns rh-flex-column">
							<RichText
								placeholder={__('Title', 'rehub-framework')}
								tagName="h2"
								className="mt0 mb0 font200 flex-3col-2"
								value={title}
								onChange={(value) => {
									setAttributes({title: value});
								}}
								keepPlaceholderOnFocus
								style={titleStyles}
							/>
							<RichText
								placeholder={__('Subtitle', 'rehub-framework')}
								tagName="p"
								className="mb15 mt0 font130"
								value={subtitle}
								onChange={(value) => {
									setAttributes({subtitle: value});
								}}
								keepPlaceholderOnFocus
								style={subtitleStyles}
							/>
						</div>
					</div>
				</div>
			</Fragment>
		);
	}
}

export default compose(
	withFocusOutside
)(EditBlock);