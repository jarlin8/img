import {isEqual, debounce} from 'lodash';
const {
	Component,
	RawHTML,
} = wp.element;
import {__, sprintf} from '@wordpress/i18n'
const {addQueryArgs} = wp.url;

const {
	Placeholder,
	Spinner,
} = wp.components;

export function rendererPath(block, urlQueryArgs = {}) {
	return addQueryArgs(`/wp-json/gt3/v1/photo-gallery/block-renderer/${ block }`, {
		context: 'edit',
		...urlQueryArgs,
	});
}

export class ServerSideRender extends Component {
	constructor(props) {
		super(props);
		this.state = {
			response: null,
		};
	}

	componentDidMount() {
		this.isStillMounted = true;
		this.fetch(this.props);
		// Only debounce once the initial fetch occurs to ensure that the first
		// renders show data as soon as possible.
		this.fetch = debounce(this.fetch, 500);
	}

	componentWillUnmount() {
		this.isStillMounted = false;
	}

	componentDidUpdate(prevProps) {
		if (!isEqual(prevProps, this.props)) {
			this.fetch(this.props);
		}
	}

	fetch(props) {
		if (!this.isStillMounted) {
			return;
		}
		if (null !== this.state.response) {
			this.setState({response: null});
		}
		const {block, attributes = null, blacklist = []} = props,
			that = this;

		const path = `/rehub/v1/block-render/${ block }`;//rendererPath(block, attributes, urlQueryArgs);

		function buildFormData(formData, data, parentKey) {
			if (data && typeof data === 'object' && !(data instanceof Date) && !(data instanceof File)) {
				Object.keys(data).forEach(key => {
					!blacklist.includes(key) &&
					buildFormData(formData, data[key], parentKey ? `${parentKey}[${key}]` : key);
				});
			} else {
				const value = data == null ? '' : data;

				formData.append(parentKey, value);
			}
		}

		function jsonToFormData(data) {
			const formData = new FormData();

			buildFormData(formData, data);

			return formData;
		}

		let formData = jsonToFormData(attributes);

		// Store the latest fetch request so that when we process it, we can
		// check if it is the current request, to avoid race conditions on slow networks.
		const fetchRequest = this.currentFetchRequest = wp.apiFetch({
			path: path,
			method: 'POST',
			body: formData,
		})
			.then((response) => {
				if (that.isStillMounted && fetchRequest === that.currentFetchRequest && response) {
					that.setState({response: response.rendered});
					that.props.serverCallback && that.props.serverCallback.call && that.props.serverCallback(that.state.response)
				}
			})
			.catch((error) => {
				if (that.isStillMounted && fetchRequest === that.currentFetchRequest) {
					that.setState({
						response: {
							error: true,
							errorMsg: error.message,
						}
					});
				}
			});
		return fetchRequest;
	}

	render() {
		const response = this.state.response;
		const {className} = this.props;
		if (response === '') {
			return (
				<Placeholder
					className={className}
				>
					{__('Block rendered as empty.')}
				</Placeholder>
			);
		} else if (!response) {
			return (
				<Placeholder
					className={className}
				>
					<Spinner />
				</Placeholder>
			);
		} else if (response.error) {
			// translators: %s: error message describing the problem
			const errorMessage = sprintf(__('Error loading block: %s'), response.errorMsg);
			return (
				<Placeholder
					className={className}
				>
					{errorMessage}
				</Placeholder>
			);
		}

		return (
			<RawHTML
				key="html"
				className={className}
			>
				{response}
			</RawHTML>
		);
	}
}

export default ServerSideRender;
