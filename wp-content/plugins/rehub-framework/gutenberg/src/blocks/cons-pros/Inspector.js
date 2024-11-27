/**
 * WordPress dependencies
 */
import {Component} from '@wordpress/element'
import {InspectorControls} from "@wordpress/block-editor";

/**
 * Internal dependencies
 */
import ConsProsInspector from '../../components/cons-pros/inspector';

/**
 * Create an Inspector Controls wrapper Component
 */
export default class Inspector extends Component {
	render() {
		const {attributes, setAttributes} = this.props;
		const {prosTitle, positives, consTitle, negatives} = attributes;

		return (
			<InspectorControls>
				<ConsProsInspector
					setAttributes={setAttributes}
					prosTitle={prosTitle}
					positives={positives}
					consTitle={consTitle}
					negatives={negatives}
				/>
			</InspectorControls>
		);
	}
}