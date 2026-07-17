const schema = {
	slides: {
		type: 'array',
		default: [
			{
				image: {
					id: 0,
					url: `${window.RehubGutenberg.pluginDirUrl}/gutenberg/src/icons/noimage-placeholder.png`,
					width: '',
					height: '',
					alt: ''
				},
			}
		]
	}
};
export default schema;