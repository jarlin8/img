/**
 * WordPress dependencies
 */
import {Component, Fragment} from '@wordpress/element';
import {withFocusOutside} from '@wordpress/components';
import {compose} from "@wordpress/compose";

/**
 * External dependencies
 */
import classnames from "classnames";

/**
 * Internal dependencies
 */
import Inspector from "./Inspector";
import Controls from './Controls';
import ConsPros from "../../components/cons-pros";

class EditBlock extends Component {
	render() {
		const {className, isSelected, attributes, setAttributes} = this.props;
		const {prosTitle, positives, consTitle, negatives} = attributes;
		const mainClasses = classnames([className, 'cons-pros-block']);

		return (
			<Fragment>
				{isSelected && (
					<Fragment>
						<Inspector {...this.props} />
						<Controls {...this.props} />
					</Fragment>
				)}
				<div className={mainClasses}>
					<ConsPros
						setAttributes={setAttributes}
						prosTitle={prosTitle}
						consTitle={consTitle}
						positives={positives}
						negatives={negatives}
					/>
				</div>
			</Fragment>
		);
	}
}

export default compose(
	withFocusOutside
)(EditBlock);
