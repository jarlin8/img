/**
 * Internal dependencies
 */
import ImageUploadPlaceholder from "../../../components/image-upload-placeholder";
import {cloneDeep} from "lodash";

const ImageColumn = (props) => {
	const {attributes, setAttributes, index, writable} = props;
	const {offers} = attributes;
	const {score, thumbnail} = offers[index];
	return (
		<div className='c-offer-listing-image'>
			{(parseInt(score) > 0) && (
				<div className="c-offer-listing-score">
					<span className={`score--${Math.round(+score)}`}>{score}</span>
				</div>
			)}
			<figure>
				{writable && (
					<ImageUploadPlaceholder
						imageID={thumbnail.id}
						imageURL={thumbnail.url}
						onRemove={() => {
							const offersClone = cloneDeep(offers);
							offersClone[index].thumbnail.id = '';
							offersClone[index].thumbnail.url = '';
							offersClone[index].thumbnail.width = '';
							offersClone[index].thumbnail.height = '';
							offersClone[index].thumbnail.alt = '';

							setAttributes({
								offers: offersClone
							});
						}}
						onChange={image => {
							const offersClone = cloneDeep(offers);
							offersClone[index].thumbnail.id = image.id;
							offersClone[index].thumbnail.url = image.url;
							offersClone[index].thumbnail.width = image.width;
							offersClone[index].thumbnail.height = image.height;
							offersClone[index].thumbnail.alt = image.alt;

							setAttributes({
								offers: offersClone
							});
						}}
					/>
				)}
				{writable === false && (
					<img src={thumbnail.url} alt=""/>
				)}
			</figure>
		</div>
	);
};

export default ImageColumn;