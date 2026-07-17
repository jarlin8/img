const Gallery = (props) => {
	const {items} = props;
	return (
		<div className='c-ws-box-gallery'>
			{items.map((item, index) => {
				return (
					<div className='c-ws-box-gallery__item' key={index}>
						<img src={item} alt=""/>
					</div>
				);
			})}
		</div>
	);
};

export default Gallery;