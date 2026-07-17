import ReactSelect2Wrapper from 'react-select2-wrapper';
import {Component} from '@wordpress/element';
import {BaseControl, Spinner} from "@wordpress/components";
import {Fragment} from '@wordpress/element';
import {withSelect} from '@wordpress/data';

class ProductsSelect extends Component {
	constructor() {
		super(...arguments);
		this.state = {
			currentPost: null
		};
		this.defaultValue = this.props.selectedPost;
		this.onChangeSelectPost = this.onChangeSelectPost.bind(this);
	}

	onChangeSelectPost(value) {
		this.setState({currentPost: parseInt(value)});
		this.props.onChange(value);
	}

	render() {
		const {label, posts} = this.props;
		const {currentPost} = this.state;
		let selectData = null;

		if (posts && posts.length) {
			selectData = posts.map((post) => {
				return {
					text: post.title.rendered,
					id: post.id
				};
			});
		}

		return (
			<Fragment>
				<BaseControl label={label}>
					{selectData && selectData.length > 0 ? (
						<ReactSelect2Wrapper
							defaultValue={this.defaultValue}
							value={currentPost}
							data={selectData}
							onChange={(event) => {
								const value = jQuery(event.currentTarget).val();

								if (value !== null && value.length) {
									this.onChangeSelectPost(value);
								}
							}}
						/>
					) : (
						<Spinner/>
					)}
				</BaseControl>
			</Fragment>
		);
	}

}

export default withSelect(
	(select) => {
		return {
			posts: select('core').getEntityRecords('postType', 'product')
		};
	}
)(ProductsSelect);
