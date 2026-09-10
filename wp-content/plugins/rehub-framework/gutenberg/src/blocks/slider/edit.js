/**
 * WordPress dependencies
 */
import {Component, Fragment, createRef} from '@wordpress/element';
import {withFocusOutside} from '@wordpress/components';
import {compose} from "@wordpress/compose";

/**
 * External dependencies
 */
import classnames from "classnames";
import {cloneDeep} from "lodash";
import { v4 as uuidv4 } from 'uuid';

/**
 * Internal dependencies
 */
import Inspector from "./Inspector";
import Controls from './Controls';
import ImageUploadPlaceholder from "../../components/image-upload-placeholder";
import AddItemButton from "../../components/add-item-button";

class EditBlock extends Component {
	constructor(props) {
		super(props);
		this.sliderRef = createRef();
		this.sliderObject = null;
	}

	componentDidMount() {
		const sliderNode = this.sliderRef.current;

		if (typeof window.rehubSlider !== 'function') {
			return false;
		}

		this.sliderObject = new window.rehubSlider(sliderNode);
		this.sliderObject.init();
	}

	componentDidUpdate() {
		this.sliderObject.update();
	}

	componentWillUnmount() {
		this.sliderObject.destroy();
	}

	render() {
		const {className, isSelected, attributes, setAttributes} = this.props;
		const {slides} = attributes;
		const slidesClone = cloneDeep(slides);
		const mainClasses = classnames([className, 'rh-slider']);

		return (
			<Fragment>
				{isSelected && (
					<Fragment>
						<Inspector {...this.props} />
						<Controls {...this.props} />
					</Fragment>
				)}
				<div className={mainClasses} ref={this.sliderRef}>
					<div className='rh-slider__inner'>
						{slides.map((slide, index) => {
							const {image} = slide;
							const {id, url} = image;

							return (
								<div className='rh-slider-item' key={uuidv4()}>
									<ImageUploadPlaceholder
										imageID={id}
										imageURL={url}
										onRemove={() => {
											slidesClone[index].image.id = '';
											slidesClone[index].image.url = '';
											slidesClone[index].image.width = '';
											slidesClone[index].image.height = '';
											slidesClone[index].image.alt = '';
											setAttributes({slides: slidesClone});
										}}
										onChange={image => {
											slidesClone[index].image.id = image.id;
											slidesClone[index].image.url = image.url;
											slidesClone[index].image.width = image.width;
											slidesClone[index].image.height = image.height;
											slidesClone[index].image.alt = image.alt;
											setAttributes({slides: slidesClone});
										}}
									/>
								</div>
							);
						})}
						<div className='rh-slider-arrow rh-slider-arrow--prev'>
							<i className="rhicon rhi-chevron-left"/>
						</div>
						<div className='rh-slider-arrow rh-slider-arrow--next'>
							<i className="rhicon rhi-chevron-right"/>
						</div>
						<div className='rh-slider-dots'>
							{slides.map(() => {
								return (
									<div className='rh-slider-dots__item' key={uuidv4()}/>
								)
							})}
						</div>
					</div>
					<div className='rh-slider-thumbs'>
						<div className="rh-slider-thumbs__row">
							{slides.map((slide) => {
								const {image} = slide;
								const {url, alt} = image;

								return (
									<div className='rh-slider-thumbs-item' key={uuidv4()}>
										<img src={url} alt={alt}/>
									</div>
								);
							})}
						</div>
					</div>
					<AddItemButton handleClick={() => {
						slidesClone.push({
							image: {
								id: 0,
								url: `${window.RehubGutenberg.pluginDirUrl}/gutenberg/src/icons/noimage-placeholder.png`,
								width: '',
								height: '',
								alt: ''
							},
						});
						setAttributes({slides: slidesClone});
					}}
					/>
				</div>
			</Fragment>
		);
	}
}

export default compose(
	withFocusOutside
)(EditBlock);