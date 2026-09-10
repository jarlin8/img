/**
 * WordPress dependencies
 */
import {Component} from '@wordpress/element';
import {compose} from '@wordpress/compose';
import {withSelect, withDispatch} from '@wordpress/data';
import {SelectControl} from '@wordpress/components';

class PostsDropdownControl extends Component {
	render() {
		let options = [];

		if (this.props.posts) {
			options.push({value: 0, label: 'Select something'});
			this.props.posts.forEach((post) => { // simple foreach loop
				options.push({value: post.id, label: post.title.rendered});
			});
		} else {
			options.push({value: 0, label: 'Loading...'})
		}

		return (
			<SelectControl
				label='Select a post'
				options={options}
				onChange={(content) => {
					this.props.setMetaValue( content );
				}}
				value={this.props.metaValue}
			/>
		);
	}
}

export default compose(
	withDispatch((dispatch, props) => {
		return {
			setMetaValue: function (metaValue) {
				dispatch('core/editor').editPost(
					{meta: {[props.metaKey]: metaValue}}
				);
			}
		}
	}),
	withSelect((select, props) => {
		return {
			posts: select('core').getEntityRecords('postType', 'post'),
			metaValue: select('core/editor').getEditedPostAttribute('meta')[props.metaKey],
		}
	})
)(PostsDropdownControl);