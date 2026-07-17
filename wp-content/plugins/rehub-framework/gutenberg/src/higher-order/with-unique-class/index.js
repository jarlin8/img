/**
 * WordPress dependencies
 */
import {Component} from '@wordpress/element';
import {createHigherOrderComponent} from '@wordpress/compose';

export const createUniqueClass = (uid) => {
	return `rehub-${uid.substring(0, 7)}`;
};

const withUniqueClass = createHigherOrderComponent(
	WrappedComponent => class extends Component {
		componentDidMount() {
			const {
				attributes, setAttributes, clientId,
			} = this.props;

			const newUniqueClass = createUniqueClass(clientId);

			if (typeof attributes.uniqueClass === 'undefined' || attributes.uniqueClass !== newUniqueClass) {
				setAttributes({uniqueClass: newUniqueClass})
			}
		}

		render() {
			return (
				<WrappedComponent {...this.props} />
			)
		}
	},
	'withUniqueClass'
);

export default withUniqueClass;
