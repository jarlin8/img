import schema from "../schema";
import {assign} from 'lodash';

const deprecatedAttrs = [
	{
		attributes: assign(schema, {
			hide_old_price: {
				type: 'boolean',
				default: false
			}
		}),
	}
];

export default deprecatedAttrs;