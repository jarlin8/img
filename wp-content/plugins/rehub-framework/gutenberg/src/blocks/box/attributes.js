import {slug} from './help';


const backAttrs = RehubGutenberg.attributes[slug];

const attributes = Object.assign({},
	{
		...backAttrs
	}
);

export default attributes;
