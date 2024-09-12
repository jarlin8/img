import ReactSelect2Wrapper from 'react-select2-wrapper';
import {Component} from '@wordpress/element';
import {BaseControl, Spinner} from "@wordpress/components";
import {Fragment} from '@wordpress/element';
import {withSelect} from '@wordpress/data';

class MultiSelect extends Component {
	constructor() {
		super(...arguments);
		this.state = {
			currentPosts: null
		};
		this.defaultValue = this.props.selectedPosts;
		this.handleSelect = this.handleSelect.bind(this);
	}

	handleSelect(value) {
		this.setState({currentPosts: value});
		this.props.onChange(value);
	}

	render() {
		const {label, posts, selectedPosts} = this.props;
		const {currentPosts} = this.state;
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
							multiple
							data={selectData}
							defaultValue={selectedPosts}
							value={currentPosts}
							onChange={(event) => {
								const value = jQuery(event.currentTarget).val();

								if (value !== null && value.length) {
									this.handleSelect(value);
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
			posts: select('core').getEntityRecords('postType', 'post', {per_page: -1})
		};
	}
)(MultiSelect);
