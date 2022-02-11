const Videos = (props) => {
	const {items} = props;

	return (
		<div className='c-ws-box-videos'>
			{items.map((item, index) => {
				return (
					<div className='c-ws-box-videos__item' key={index}>
						<img src={item} alt=""/>
					</div>
				)
			})}
		</div>
	);
};

export default Videos;