import { __ } from '@wordpress/i18n';
import { iconColumn } from "../../icons";

import EditClass from './edit';
import save from './save';

const blockProperty = {
	title: __( 'Comparison item', 'rehub-framework' ),
	icon: iconColumn,
    parent: [ 'rehub/comparison-table' ],
    category: 'common',
	keywords: [__('table'), __('comparison'), __('content')],
	supports: {
		anchor: false,
        html: false,
        reusable: false
	},
    attributes: {
        productBadge: {
            type: 'string',
            default: ''
        },
        badgeColor: {
            type: 'string',
            default: '#7635f3'
        },
        productImage: {
            type: 'object',
            default: {}
        },
        productTitle: {
            type: 'string',
            default: ''
        },
        productSubtitle: {
            type: 'string',
            default: ''
        },
        numberValue: {
            string: '',
            default: '1'
        },
        numberColor: {
            type: 'string',
            default: '#7635f3'
        },
        starRating: {
            type: 'number',
            default: 5
        },
        bottomText: {
            type: 'string',
            default: ''
        },
        prosText: {
            type: 'string',
            default: ''
        },
        consText: {
            type: 'string',
            default: ''
        },
        specText: {
            type: 'string',
            default: ''
        },
        buttonUrl: {
            type: 'string',
            default: ''
        },
        buttonText: {
            type: 'string',
            default: 'Check Prices'
        },
        buttonRel: {
            type: 'boolean',
            default: false
        },
        buttonTarget: {
            type: 'boolean',
            default: false
        },
        buttonColor: {
            type: 'string',
            default: '#7635f3'
        },
        listTitle: {
            type: 'string',
            default: 'Check Latest Prices'
        },
        listItems: {
            type: 'array',
            default: [
               
            ]
        },

        // State variables
        enableBadge: {
            type: 'boolean',
            default: false
        },
        enableBadges: {
            type: 'boolean',
            default: false
        },
        enableImage: {
            type: 'boolean',
            default: true
        },
        enableTitle: {
            type: 'boolean',
            default: true
        },
        enableSubtitle: {
            type: 'boolean',
            default: true
        },
        enableStars: {
            type: 'boolean',
            default: true
        },
        enableNumbers: {
            type: 'boolean',
            default: false
        },
        enableList: {
            type: 'boolean',
            default: false
        },
        enableListTitle: {
            type: 'boolean',
            default: true
        },
        enableButton: {
            type: 'boolean',
            default: true
        },
        enableBottom: {
            type: 'boolean',
            default: true
        },
        enablePros: {
            type: 'boolean',
            default: true
        },
        enableCons: {
            type: 'boolean',
            default: true
        },
        enableSpec: {
            type: 'boolean',
            default: false
        },
        enableCallout: {
            type: 'boolean',
            default: false
        },
        titleTag: {
            type: 'string',
            default: 'div'
        },
        titleFont: {
            type: 'number',
            default: 18
        },
        contentFont: {
            type: 'number',
            default: 14
        },
        bottomTitle: {
            type: 'string',
            default: 'Bottom Line'
        },
        prosTitle: {
            type: 'string',
            default: 'Pros'
        },
        consTitle: {
            type: 'string',
            default: 'Cons'
        },
        specTitle: {
            type: 'string',
            default: 'Spec'
        },
        responsiveView: {
            type: 'string',
            default: 'stacked'
        }
    },
    usesContext: [
        'enableBadges', 'enableImage', 'enableTitle', 'enableSubtitle', 'enableStars', 'enableNumbers', 'enableButton', 'enableBottom', 'enablePros', 'enableCons', 'enableSpec', 'enableCallout', 'enableList', 'enableListTitle', 
        'titleTag', 'titleFont', 'contentFont',
        'bottomTitle', 'prosTitle', 'consTitle', 'specTitle', 'responsiveView'
    ],
    edit: EditClass,
    save
};

export default {
	slug: `rehub/comparison-item`,
	blockProperty,
};