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
import UrlInputPopover from "../../components/url-input-popover";

const BORDER_ALIGN = ['Top', 'Right', 'Bottom', 'Left'];

class EditBlock extends Component {
	constructor(props) {
		super(props);
		this.state = {
			openUrlPopover: null
		};
		this.handleFocusOutside = this.handleFocusOutside.bind(this);
		this.handleButtonClick = this.handleButtonClick.bind(this);
	}

	handleFocusOutside() {
		this.setState({
			openUrlPopover: null,
		});
	}

	handleButtonClick() {
		this.setState({openUrlPopover: true});
	}

	render() {
		const {className, isSelected, attributes, setAttributes} = this.props;
		const mainClasses = classnames([className, 'wpsm_promobox']);
		const {
			      backgroundColor,
			      textColor,
			      showBorder,
			      borderSize,
			      borderColor,
			      showHighlightBorder,
			      highlightPosition,
			      highlightColor,
			      showButton,
			      buttonText,
			      buttonLink,
			      title,
			      content
		      } = attributes;


		const styles = {
			backgroundColor: backgroundColor,
			color: textColor
		};

		if (showBorder) {
			BORDER_ALIGN.forEach((align) => {
				if (align === highlightPosition && showHighlightBorder) {
					return;
				}

				styles[`border${align}Color`] = borderColor;
				styles[`border${align}Width`] = `${borderSize}px`;
			});
		}

		if (showHighlightBorder) {
			styles[`border${highlightPosition}Width`] = '3px';
			styles[`border${highlightPosition}Color`] = highlightColor;
		}

		return (
			<Fragment>
				{isSelected && (
					<Fragment>
						<Inspector {...this.props} />
						<Controls {...this.props} />
					</Fragment>
				)}
				<div className={mainClasses} style={styles}>
					{showButton && (
						<div className='wpsm-button rehub_main_btn' onClick={this.handleButtonClick}>
							<RichText
								placeholder={__('Buy item', 'rehub-framework')}
								tagName="span"
								value={buttonText}
								onChange={(value) => {
									setAttributes({buttonText: value});
								}}
								keepPlaceholderOnFocus
							/>
							{this.state.openUrlPopover && (
								<UrlInputPopover
									value={buttonLink}
									onChange={value => {
										setAttributes({buttonLink: value})
									}}
								/>
							)}
						</div>
					)}
					<div className='title_promobox'>
						<RichText
							placeholder={__('Title', 'rehub-framework')}
							tagName="span"
							value={title}
							onChange={(value) => {
								setAttributes({title: value});
							}}
							keepPlaceholderOnFocus
						/>
					</div>
					<RichText
						placeholder={__('Content', 'rehub-framework')}
						tagName="p"
						value={content}
						onChange={(value) => {
							setAttributes({content: value});
						}}
						keepPlaceholderOnFocus
					/>
				</div>
			</Fragment>
		);
	}
}

export default compose(
	withFocusOutside
)(EditBlock);