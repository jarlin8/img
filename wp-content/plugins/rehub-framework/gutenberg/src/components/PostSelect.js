/**
 * WordPress dependencies
 */
import {__} from '@wordpress/i18n';
import {SelectControl} from '@wordpress/components';
import {Component, Fragment} from '@wordpress/element';

class PostSelect extends Component {
	constructor() {
		super(...arguments);
		this.state = {
			posts: [],
			selectedPost: 2
		};
		this.getOptions = this.getOptions.bind(this);
		this.onChangeSelectPost = this.onChangeSelectPost.bind(this);
	}

	getOptions() {
		wp.apiFetch({path: '/wp/v2/posts'}).then((posts) => {
			this.setState({posts});
		});
	}

	onChangeSelectPost(value) {
		this.props.setAttributes({selectedPost: parseInt(value)});
		this.setState({selectedPost: parseInt(value)});
	}

	componentDidMount() {
		this.setState({selectedPost: this.props.attributes.selectedPost});
		this.getOptions();
	}

	render() {
		let output = 'Loading Posts...';
		// let selectedPost = {};
		let options = [{value: 0, label: 'Select a Post'}];

		if (this.state.posts.length > 0) {
			output = 'We have ' + this.state.posts.length + ' posts';
			this.state.posts.forEach((post) => {
				options.push({value: post.id, label: post.title.rendered});
			});
		}

		if (0 === this.state.selectedPost) {
			output = 'Please Select a Post';
		} else {
			output = 'A post is selected';
		}

		return (
			<Fragment>
				<SelectControl
					onChange={this.onChangeSelectPost}
					value={this.props.attributes.selectedPost}
					label={__('Select a Post', 'rehub-framework')}
					options={options}/>
				{output}
			</Fragment>
		);
	}
}

export default PostSelect;

