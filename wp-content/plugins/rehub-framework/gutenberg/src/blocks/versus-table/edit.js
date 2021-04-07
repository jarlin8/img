/**
 * WordPress dependencies
 */
import {Component, Fragment} from '@wordpress/element';
import {compose} from "@wordpress/compose";
import {withFocusOutside} from "@wordpress/components";
import {__} from '@wordpress/i18n';
import {RichText} from '@wordpress/block-editor';

/**
 * External dependencies
 */
import classnames from "classnames";


/**
 * Internal dependencies
 */
import Inspector from "./Inspector";
import Controls from './Controls';
import VersusItem from "./VersusItem";

class EditBlock extends Component {
	render() {
		const {className, isSelected, attributes, setAttributes} = this.props;
		const {heading, subheading, bg, color, firstColumn, type, secondColumn, thirdColumn} = attributes;
		const mainClasses = classnames([className, 'c-vs-table']);
		const styles = {
			backgroundColor: bg,
			color: color
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
					<div className='c-vs-header'>
						<RichText
							placeholder={__('Versus Title', 'rehub-framework')}
							tagName="span"
							className='c-vs-heading'
							value={heading}
							onChange={(value) => {
								setAttributes({
									heading: value
								});
							}}
							keepPlaceholderOnFocus
						/>
						<RichText
							placeholder={__('Versus subline', 'rehub-framework')}
							tagName="span"
							className='c-vs-subheading'
							value={subheading}
							onChange={(value) => {
								setAttributes({
									subheading: value
								});
							}}
							keepPlaceholderOnFocus
						/>
					</div>
					<div className='c-vs-cont'>
						<VersusItem
							data={firstColumn}
							propName='firstColumn'
							setAttributes={setAttributes}
							color={color}
						/>
						<div className="c-vs-circle-col">
							<div className="c-vs-circle">VS</div>
						</div>
						<VersusItem
							data={secondColumn}
							propName='secondColumn'
							setAttributes={setAttributes}
							color={color}
						/>
						{type === 'three' && (
							<Fragment>
								<div className="c-vs-circle-col">
									<div className="c-vs-circle">VS</div>
								</div>
								<VersusItem
									data={thirdColumn}
									propName='thirdColumn'
									setAttributes={setAttributes}
									color={color}
								/>
							</Fragment>
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