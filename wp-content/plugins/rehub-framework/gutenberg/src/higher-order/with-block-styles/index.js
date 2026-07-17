/**
 * External dependencies
 */
import BlockStyles from '../../components/block-styles';

/**
 * WordPress dependencies
 */
import {applyFilters} from '@wordpress/hooks';
import classnames from 'classnames';
import {Component} from '@wordpress/element';
import {createHigherOrderComponent} from '@wordpress/compose';

const withBlockStyles = (styleFunction, options = {}) => createHigherOrderComponent(
	WrappedComponent => class extends Component {
		render() {
			const newClassName = classnames([
				this.props.className,
				this.props.attributes.uniqueClass,
			]);

			const {blockName} = this.props;
			const styleObject = applyFilters(`stackable.${blockName}.styles`, styleFunction(this.props), this.props);

			const BlockStyle = (
				<BlockStyles
					blockUniqueClassName={this.props.attributes.uniqueClass}
					blockMainClassName={this.props.mainClassName}
					style={styleObject}
					editorMode={options.editorMode || false}
				/>
			);

			return <WrappedComponent {...this.props} className={newClassName} styles={BlockStyle}/>
		}
	},
	'withBlockStyles'
);

export default withBlockStyles;
