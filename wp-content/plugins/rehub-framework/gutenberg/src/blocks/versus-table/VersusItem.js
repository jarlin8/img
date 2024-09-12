/**
 * External dependencies
 */
import classnames from "classnames";
import {cloneDeep} from "lodash";

/**
 * Internal dependencies
 */
import ImageUploadPlaceholder from "../../components/image-upload-placeholder";

/**
 * WordPress dependencies
 */
import {__} from '@wordpress/i18n';
import {RichText} from '@wordpress/block-editor';

const VersusItem = (props) => {
	const {data, propName, setAttributes, color} = props;
	const {type, isGrey, image, content} = data;
	const dataClone = cloneDeep(data);
	const classes = classnames([
		'c-vs-item',
		{
			'c-vs-col-1': propName === 'firstColumn',
			'c-vs-col-2': propName === 'secondColumn',
			'c-vs-col-3': propName === 'thirdColumn',
			'c-vs-tick': type === 'tick',
			'c-vs-times': type === 'times',
			'c-vs-image': type === 'image',
			'c-vs-text': type === 'text',
			'c-vs-greyscale': isGrey,
		}
	]);
	const styles = {
		color
	};

	return (
		<div className={classes} style={styles}>
			{type === 'tick' && (
				<i className="rhicon rhi-check-circle-solid" aria-hidden="true"/>
			)}
			{type === 'times' && (
				<i className="rhicon rhi-times" aria-hidden="true"/>
			)}
			{type === 'image' && (
				<div className='c-vs-image__placeholder'>
					<ImageUploadPlaceholder
						imageURL={image}
						onRemove={() => {
							dataClone.image = '';
							dataClone.imageId = '';
							setAttributes({
								[propName]: dataClone
							});
						}}
						onChange={image => {
							dataClone.image = image.url;
							dataClone.imageId = image.id;
							setAttributes({
								[propName]: dataClone
							});
						}}
					/>
				</div>
			)}
			{type === 'text' && (
				<RichText
					placeholder={__('Value', 'rehub-framework')}
					tagName="div"
					value={content}
					onChange={(value) => {
						dataClone.content = value;
						setAttributes({
							[propName]: dataClone
						});
					}}
					keepPlaceholderOnFocus
				/>
			)}
		</div>
	);
};

export default VersusItem;