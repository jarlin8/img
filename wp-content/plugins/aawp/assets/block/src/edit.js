/**
 * global aawp_data
 */

/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-block-editor/#useBlockProps
 */
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';

/**
 * Get required utilities from @wordpress/components
 */
import { SelectControl, PanelBody, Placeholder, TextControl, RadioControl, ToggleControl, RangeControl, Button } from '@wordpress/components';

/**
 * ServerSideRender is a component used for server-side rendering a preview of dynamic blocks to display in the editor. 
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-server-side-render/
 */
import ServerSideRender from '@wordpress/server-side-render';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */
export default function Edit( props ) {

    const {
        attributes: {
            look = '',
            asin = '',
            keywords = '',

            /** Lists (multiple boxes) fields start */
            items = 10,
            order = 'ASC',
            orderby = '',
            order_items = 10,
            filterby = '',
            filter = '',
            filter_items = 10,
            filter_type = 'include',
            filter_compare= 'equal',
            ribbon = true,
            ribbon_text = '',
            /** Lists (multiple boxes) fields end */

            /** Title and links fields start */
            title = '',
            title_length = undefined !== aawp_data.options.output.title_length ? aawp_data.options.output.title_length : '100',
            link_title = '',
            link_overwrite = '',
            link_type = '',
            link_icon = '',
            link_class = '',
            /** Title and links fields end */

            /** Description fields start */
            description = '',
            description_items = undefined !== aawp_data.options.output.description_items ? parseInt( aawp_data.options.output.description_items ) : 5,
            description_length = undefined !== aawp_data.options.output.description_length ? aawp_data.options.output.description_length : '200',
            /** Description fields end */

            /** Images fields start */
            image = '',
            image_size = undefined !== aawp_data.options.output.image_size ? aawp_data.options.output.image_size : 'medium',
            image_alt = '',
            image_title = '',
            image_align = 'center',
            image_width = '',
            image_height = '',
            image_class = '',
            /** Images fields end */

            /** Buttons fields start */
            button = true,
            button_text = undefined !== aawp_data.options.output.button_text ? aawp_data.options.output.button_text : 'Buy on Amazon',
            button_detail = '',
            button_detail_text = undefined !== aawp_data.options.output.button_detail_text ? aawp_data.options.output.button_detail_text : 'Details',
            button_detail_title = '',
            button_detail_target = '',
            button_detail_rel = '',
            /** Buttons fields end */

            /** Pricing fields start */
            price = '',
            sale_ribbon_text = undefined !== aawp_data.options.output.pricing_sale_ribbon_text ? aawp_data.options.output.pricing_sale_ribbon_text : 'Sale',
            /** Pricing fields start */

            /** Star ratings fields start */
            rating = '',
            star_rating = true,
            reviews = true,
            /** Star ratings fields end */

            /** Templates & Styles fields start */
            template = '',
            grid = '',
            class_attr = '',
            numbering = true,
            /** Templates & Styles fields end */

            /** Other fields start */
            tracking_id = '',
            /** Other fields end */

            // For Fields Look.
            value_attr = '',
            apply_link = false,

            // Comparison table.
            table = ''
        },

        setAttributes
     } = props;

    let jsx;
    let nextInput, valueSelect, panel = [];

    // Comparison table options.
    const options = Object.entries( aawp_data.tables ).map( function ( [ key, value ] ) {
        return { value: key, label: value }
    });

    // Initialize options for the comparison tables.
    options.unshift( { value: '', label: '-- Select A Table --'  } );

    var looks = [ 'box', 'fields', 'link'];

    if ( looks.includes( look ) ) {

        nextInput =  <TextControl
                ref={input => input && '' === asin && input.focus()}
                key="aawp-asin-input-control"
                className = "aawp-asin-input-control"
                label={ __( 'ASIN', 'aawp' ) }
                value={asin}
                placeholder = {__( 'Enter ASIN here...', 'aawp') }
                onChange= { (value) => setAttributes( { asin: value } ) }
                help = { __( 'Multiple ASIN values can be separated by comma.', 'aawp')}
            />;
        
        let buttonInput =   <div key="components-base-control aawp-products-search-container" className="aawp-products-search-container">
                                <Button variant="secondary" key="aawp-table-add-products-search" className="aawp-table-add-products-search" href="#aawp-modal-table-product-search" data-aawp-modal="true" data-aawp-table-add-products-search="true">
                                    <span className="dashicons dashicons-search"></span>
                                        { __( ' Search for product(s) without ASIN', 'aawp')}
                                </Button>
                            </div>

        nextInput = [ nextInput, 'OR', buttonInput ];

        if ( 'fields' === look ) {
            valueSelect = <SelectControl
                    key="aawp-fields-value-input-control"
                    label={ __( 'Value', 'aawp') }
                    value= {value_attr}
                    options={[
                        { value: '', label: __( '-- Select An Option --', 'aawp' ) },
                        { value: 'title', label: __( 'Title', 'aawp' ) },
                        { value: 'description', label: __( 'Description', 'aawp' ) },
                        { value: 'thumb', label: __( 'Thumbnail', 'aawp' ) },
                        { value: 'star_rating', label: __( 'Star Rating', 'aawp' ) },
                        { value: 'price', label: __( 'Price', 'aawp' ) },
                        { value: 'button', label: __( 'Button', 'aawp' ) },
                    ]}
                    onChange= { (value) => setAttributes( { value_attr: value } ) }
                    help = { __( 'To display single product data e.g. title, description or price.', 'aawp')}
                />;
        }

    } else if ( 'bestseller' === look || 'new' === look ) {
        nextInput =  <TextControl
                ref={input => input && '' === keywords && input.focus()}
                key="aawp-keywords-input-control"
                label={ __( 'Keywords', 'aawp' ) }
                value = {keywords}
                placeholder = {__( 'Enter keywords here. E.g. "top 4k monitors"', 'aawp') }
                onChange= { (value) => setAttributes( { keywords: value } ) }
            />;
    } else if ( 'table' === look ) {
        nextInput = <SelectControl
                autoFocus
                key="aawp-comparison-table-select-control"
                label={__( 'Select A Table', 'aawp' )}
                value= {table}
                options= {options}
                onChange= { (value) => setAttributes( { table: value } ) }
                help={__( 'To display the comparison table.', 'aawp' )}
            />;
    }

    /** Lists (multiple boxes) fields start */
    let itemsRange = <RangeControl
                key="aawp-items-number-control"
                label={ __( 'Number of items', 'aawp' ) }
                value= {items}
                onChange= { (value) => setAttributes( { items: value } ) }
                min={ 1 }
                max={ 25 }
                help= {__('Defines the maximum amount of products which will be shown.', 'aawp')}
            />;

    let orderbySelect = <SelectControl
            label = {__( 'Order By', 'aawp')}
            key="aawp-orderby-selector-select-control"
            value={ orderby }
            options={[
                { value: '', label: __( '-- Select An Option --', 'aawp' ) },
                { value: 'amount_saved', label: __( 'Amount Saved', 'aawp' ) },
                { value: 'percentage_saved', label: __( 'Percentage Saved', 'aawp' ) },
                { value: 'price', label: __( 'Price', 'aawp' ) },
                { value: 'rating', label: __( 'Rating', 'aawp' ) },
                { value: 'title', label: __( 'Title', 'aawp' ) },
             ]}
            onChange= { (value) => setAttributes( { orderby: value } ) }
            help = { __( 'Ordering on the basis of certain attributes.', 'aawp' ) }
        />;

    let orderSelect = <SelectControl
            label = {__( 'Order', 'aawp')}
            key="aawp-order-selector-select-control"
            value={ order }
            options={[
                { value: 'ASC', label: __( 'Ascending', 'aawp' ) },
                { value: 'DESC', label: __( 'Descending', 'aawp' ) },
             ]}
            onChange= { (value) => setAttributes( { order: value } ) }
            help = { __( 'Direction of ordering.', 'aawp' ) }
        />;

    let orderItemsRange = <RangeControl
            label = {__( 'Order Items', 'aawp')}
            key="aawp-order-items-text-control"
            value={ order_items }
            onChange= { (value) => setAttributes( { order_items: value } ) }
            min={1}
            max={10}
            help = { __( 'Similar to the global "items" attribute but defining the maximum order (search) radius.', 'aawp' ) }
        />;

    let filterbySelect = <SelectControl
            label = {__( 'Filter By', 'aawp')}
            key="aawp-filterby-selector-select-control"
            value={ filterby }
            options={[
                { value: '', label: __( '-- Select A Filter --', 'aawp' ) },
                { value: 'price', label: __( 'Price', 'aawp' ) },
                { value: 'title', label: __( 'Title', 'aawp' ) },
             ]}
            onChange= { (value) => setAttributes( { filterby: value } ) }
            help = { __( 'Filtering on the basis of certain attributes.', 'aawp' ) }
        />;

    let filterInput = <TextControl
            label = {__( 'Filter', 'aawp')}
            key="aawp-filter-text-control"
            value={ filter }
            onChange= { (value) => setAttributes( { filter: value } ) }
            help = { __( 'Defining the characteristic for applying the filter.', 'aawp' ) }
        />;

    let filterItemsRange = <RangeControl
        label = {__( 'Filter Items', 'aawp')}
        type = 'number'
        key="aawp-filter-items-text-control"
        value={ filter_items }
        onChange= { (value) => setAttributes( { filter_items: value } ) }
        min={1}
        max={10}
        help = { __( 'Similar to the global "items" attribute but defining the maximum filter (search) radius.', 'aawp' ) }
    />;

    let filterTypeSelect = <SelectControl
            label = {__( 'Filter Type', 'aawp')}
            key="aawp-filter-type-selector-select-control"
            value={ filter_type }
            options={[
                { value: 'include', label: __( 'Include', 'aawp' ) },
                { value: 'exclude', label: __( 'Exclude', 'aawp' ) },
             ]}
            onChange= { (value) => setAttributes( { filter_type: value } ) }
            help = { __( 'Defining the filter direction.', 'aawp' ) }
        />;

    let filterCompareSelect = <SelectControl
            label = {__( 'Filter Compare', 'aawp')}
            key="aawp-filter-compare-selector-select-control"
            value={ filter_compare }
            options={[
                { value: 'equal', label: __( 'Equal', 'aawp' ) },
                { value: 'less', label: __( 'Less', 'aawp' ) },
                { value: 'more', label: __( 'More', 'aawp' ) },
                { value: 'range', label: __( 'Range', 'aawp' ) },
             ]}
            onChange= { (value) => setAttributes( { filter_compare: value } ) }
            help = { __( 'Required for comparing prices.', 'aawp' ) }
        />;

    let ribbonSelect =  <ToggleControl
            label = {__( 'Show Ribbon', 'aawp')}
            key="aawp-ribbon-selector-select-control"
            checked={ ribbon }   
            onChange= { (value) => setAttributes( { ribbon: value } ) }
            help = { __( 'Showing the ribbon (e.g. bestseller no X) on the top left.', 'aawp' ) }
        />;

    let ribbonTextInput =  <TextControl
            label = {__( 'Custom Ribbon Text', 'aawp')}
            key="aawp-ribbon-text-text-control"
            value={ ribbon_text }
            onChange= { (value) => setAttributes( { ribbon_text: value } ) }
            help = { __( 'Overwriting the ribbon text (e.g. bestseller no X) on the top left.', 'aawp' ) }
        />;

    /** Lists (multiple boxes) fields end */

    /** Title and links fields start */

    let titleInput = <TextControl
            label = {__( 'Title', 'aawp')}
            key="aawp-title-text-control"
            value={ title }
            onChange= { (value) => setAttributes( { title: value } ) }
            help = { __( 'Overwriting the original product title.', 'aawp' ) }
        />;

    let titleLengthInput = <TextControl
        label = {__( 'Title Length', 'aawp')}
        type = 'number'
        key="aawp-title-length-text-control"
        value={ title_length }
        onChange= { (value) => setAttributes( { title_length: value } ) }
        help = { __( 'Specifies a maximum amount of characters for the product title.', 'aawp' ) }
    />;

    let linkTitleInput = <TextControl
        label = {__( 'Link Title', 'aawp')}
        key="aawp-link-title-text-control"
        value={ link_title }
        onChange= { (value) => setAttributes( { link_title: value } ) }
        help = { __( 'Overwriting HTML link "title“ attribute.', 'aawp' ) }
    />;

    let linkOverwriteInput = <TextControl
        label = {__( 'Link Overwrite', 'aawp')}
        key="aawp-link-overwrite-text-control"
        type = 'url'
        value={ link_overwrite }
        onChange= { (value) => setAttributes( { link_overwrite: value } ) }
        help = { __( 'Replacing the links.', 'aawp' ) }
    />;

    let linkTypeSelect = <SelectControl
        label = {__( 'Link Type', 'aawp')}
        key="aawp-link-type-select-control"
        value={ link_type }
        options={[
            { value: 'basic', label: __( 'Basic', 'aawp' ) },
            { value: 'reviews', label: __( 'Reviews', 'aawp' ) },
            { value: 'cart', label: __( 'Cart', 'aawp' ) },
         ]}  
        onChange= { (value) => setAttributes( { link_type: value } ) }
        help = { __( 'Specify the link type.', 'aawp' ) }
    />;    

    let linkIconSelect = <SelectControl
        label = {__( 'Link Icon', 'aawp')}
        key="aawp-link-icon-select-control"
        value={ link_icon }
        options={[
            { value: 'none', label: __( 'None', 'aawp' ) },
            { value: 'amazon', label: __( 'Amazon', 'aawp' ) },
            { value: 'amazon-logo', label: __( 'Amazon Logo', 'aawp' ) },
            { value: 'cart', label: __( 'Cart', 'aawp' ) },
         ]}  
        onChange= { (value) => setAttributes( { link_icon: value } ) }
        help = { __( 'Specify the link icon.', 'aawp' ) }
    />;

    let linkClassInput = <TextControl
        label = {__( 'Link Class', 'aawp')}
        key="aawp-link-class-text-control"
        value={ link_class }
        onChange= { (value) => setAttributes( { link_class: value } ) }
        help = { __( 'Specify your own link CSS classes.', 'aawp' ) }
    />;

    /** Title and links fields end */

    /** Description fields start */

    let descriptionInput = <TextControl
        label = {__( 'Custom Description', 'aawp')}
        key="aawp-description-text-control"
        value={ description }
        onChange= { (value) => setAttributes( { description: value } ) }
        help = { __( 'Using a custom product description.', 'aawp' ) }
    />;

    let descriptionItemsRange = <RangeControl
        label = {__( 'Description Items', 'aawp')}
        type = 'number'
        key="aawp-description-items-text-control"
        value={ description_items }
        onChange= { (value) => setAttributes( { description_items: value } ) }
        min={1}
        max={10}
        help = { __( 'Specify a maximum amount for list items.', 'aawp' ) }
    />;

    let descriptionLengthInput = <TextControl
        label = {__( 'Description Length', 'aawp')}
        type = 'number'
        key="aawp-description-length-text-control"
        value={ description_length }
        onChange= { (value) => setAttributes( { description_length: value } ) }
        help = { __( 'Specifies a maximum amount of characters for each list item.', 'aawp' ) }
    />;
    /** Description fields end */

    /** Image fields start */

    let imageInput = <TextControl
        label = {__( 'Image Selection', 'aawp')}
        key="aawp-image-text-control"
        value={ image }
        onChange= { (value) => setAttributes( { image: value } ) }
        help = { __( 'You can select another product image by entering a number from 1 to 5 or a direct link to an image file.', 'aawp' ) }
    />;

    let imageSizeSelect = <SelectControl
        label = {__( 'Image Size', 'aawp')}
        key="aawp-image-size-select-control"
        value={ image_size }
        options={[
            { value: 'small', label: __( 'Small', 'aawp' ) },
            { value: 'medium', label: __( 'Medium', 'aawp' ) },
            { value: 'large', label: __( 'Large', 'aawp' ) },
         ]}
        onChange= { (value) => setAttributes( { image_size: value } ) }
        help = { __( 'Overwriting the thumbnail size.', 'aawp' ) }
    />;

    let imageAltInput = <TextControl
        label = {__( 'Image Alt', 'aawp')}
        key="aawp-image-alt-text-control"
        value={ image_alt }
        onChange= { (value) => setAttributes( { image_alt: value } ) }
        help = { __( 'Overwriting HTML image "alt" attribute..', 'aawp' ) }
    />;

    let imageTitleInput = <TextControl
        label = {__( 'Image Title', 'aawp')}
        key="aawp-image-title-text-control"
        value={ image_title }
        onChange= { (value) => setAttributes( { image_title: value } ) }
        help = { __( 'Adding HTML image "title" attribute.', 'aawp' ) }
    />;

    let imageAlignSelect = <SelectControl
        label = {__( 'Image Align', 'aawp')}
        key="aawp-image-align-select-control"
        value={ image_align }
        options={[
            { value: 'center', label: __( 'Center', 'aawp' ) },
            { value: 'left', label: __( 'Left', 'aawp' ) },
            { value: 'right', label: __( 'Right', 'aawp' ) },
        ]}
        onChange= { (value) => setAttributes( { image_align: value } ) }
        help = { __( 'Align images right or left', 'aawp' ) }
    />;

    let imageWidthInput = <TextControl
        label = {__( 'Image Width', 'aawp')}
        type = 'number'
        key="aawp-image-width-text-control"
        value={ image_width }
        onChange= { (value) => setAttributes( { image_width: value } ) }
        help = { __( 'Specifies the width of a single image.', 'aawp' ) }
    />;

    let imageHeightInput = <TextControl
        label = {__( 'Image Height', 'aawp')}
        key="aawp-image-height-text-control"
        value={ image_height }
        onChange= { (value) => setAttributes( { image_height: value } ) }
        help = { __( 'Specifies the height of a single image.', 'aawp' ) }
    />;

    let imageClassInput = <TextControl
        label = {__( 'Image Class', 'aawp')}
        key="aawp-image-class-text-control"
        value={ image_class }
        onChange= { (value) => setAttributes( { image_class: value } ) }
        help = { __( 'Adding HTML image "title" attribute.', 'aawp' ) }
    />;

    /** Image fields end */

    /** Button fields start */

    let buttonToggle = <ToggleControl
        label = {__( 'Display "Buy on Amazon" Button', 'aawp')}
        key="aawp-button-select-control"
        checked = { button }
        onChange= { (value) => setAttributes( { button: value } ) }
        help = { __( 'Showing or hiding the "Buy on Amazon" button.', 'aawp' ) }
    />;

    let buttonTextInput = <TextControl
        label = {__( 'Button Text', 'aawp')}
        key="aawp-button-text-text-control"
        value={ button_text }
        onChange= { (value) => setAttributes( { button_text: value } ) }
        help = { __( 'Overwriting the button text.', 'aawp' ) }
    />;

    let buttonDetailInput = <TextControl
        label = {__( 'Link', 'aawp')}
        key="aawp-button-detail-text-control"
        value={ button_detail }
        onChange= { (value) => setAttributes( { button_detail: value } ) }
        help = { __( 'Displaying an extra button and setting up the link target.', 'aawp' ) }
    />;

    let buttonDetailTextInput = <TextControl
        label = {__( 'Text', 'aawp')}
        key="aawp-button-detail-text-text-control"
        value={ button_detail_text }
        onChange= { (value) => setAttributes( { button_detail_text: value } ) }
        help = { __( 'Overwriting the button detail text.', 'aawp' ) }
    />;

    let buttonDetailTitleInput = <TextControl
        label = {__( 'Title', 'aawp')}
        key="aawp-button-detail-title-text-control"
        value={ button_detail_title }
        onChange= { (value) => setAttributes( { button_detail_title: value } ) }
        help = { __( 'Overwriting HTML link "title" attribute.', 'aawp' ) }
    />;

    let buttonDetailTargetInput = <TextControl
        label = {__( 'Target', 'aawp')}
        key="aawp-button-detail-target-text-control"
        value={ button_detail_target }
        onChange= { (value) => setAttributes( { button_detail_target: value } ) }
        help = { __( 'Overwriting HTML link "target" attribute (standard = current window).', 'aawp' ) }
    />;

    let buttonDetailRelInput = <TextControl
        label = {__( 'Rel', 'aawp')}
        key="aawp-button-detail-rel-text-control"
        value={ button_detail_rel }
        onChange= { (value) => setAttributes( { button_detail_rel: value } ) }
        help = { __( 'Setting a custom HTML link "rel" attribute', 'aawp' ) }
    />;

    /** Button fields end */

    /** Pricing fields start */

    let priceInput = <TextControl
        label = {__( 'Custom Price', 'aawp')}
        key="aawp-price-text-control"
        value={ price }
        onChange= { (value) => setAttributes( { price: value } ) }
        help = { __( 'Overwriting the price text.', 'aawp' ) }
    />;

    let saleRibbonTextInput = <TextControl
        label = {__( 'Sale Ribbon Text', 'aawp')}
        key="aawp-sale-ribbon-text-text-control"
        value={ sale_ribbon_text }
        onChange= { (value) => setAttributes( { sale_ribbon_text: value } ) }
        help = { __( 'Overwriting the sale ribbon text on the top right.', 'aawp' ) }
    />;

    /** Pricing fields end */

    /** Star rating fields start */

    let ratingInput = <TextControl
        label = {__( 'Custom Rating', 'aawp')}
        key="aawp-rating-text-control"
        value={ rating }
        onChange= { (value) => setAttributes( { rating: value } ) }
        help = { __( 'Overwriting the rating value.', 'aawp' ) }
    />;

    let starRatingToggle = <ToggleControl
        label = {__( 'Show Star Rating', 'aawp')}
        key="aawp-star-rating-select-control"
        checked={ star_rating }
        onChange= { (value) => setAttributes( { star_rating: value } ) }
        help = { __( 'Showing or hiding the star rating.', 'aawp' ) }
    />;

    let reviewsToggle = <ToggleControl
        label = {__( 'Show Reviews', 'aawp')}
        key="aawp-reviews-rating-select-control"
        checked={ reviews }
        onChange= { (value) => setAttributes( { reviews: value } ) }
        help = { __( 'Showing or hiding the amount of reviews.', 'aawp' ) }
    />;

    /** Star rating fields end */

    /** Templates & Styles fields start */

    let templateSelect = <SelectControl
        label = {__( 'Template', 'aawp')}
        key="aawp-template-select-control"
        value={ template }
        options={[
            { value: '', label: __( 'Default', 'aawp' ) },
            { value: 'horizontal', label: __( 'Horizontal', 'aawp' ) },
            { value: 'vertical', label: __( 'Vertical', 'aawp' ) },
            { value: 'list', label: __( 'List', 'aawp' ) },
            { value: 'table', label: __( 'Table', 'aawp' ) },
            { value: 'widget', label: __( 'Widget', 'aawp' ) },
            { value: 'widget-vertical', label: __( 'Widget Vertical', 'aawp' ) },
            { value: 'widget-small', label: __( 'Widget Small', 'aawp' ) },
        ]}
        onChange= { (value) => setAttributes( { template: value } ) }
        help = { __( 'Replacing the PHP template which will be used for the output.', 'aawp' ) }
    />;

    let gridSelect = <SelectControl
        label = {__( 'Grid', 'aawp') }
        key="aawp-grid-select-control"
        value={ grid }
        onChange= { (value) => setAttributes( { grid: value } ) }
        options={[
            { value: '0', label: __( 'Default', 'aawp' ) },
            { value: '2', label: __( '2 Columns', 'aawp' ) },
            { value: '3', label: __( '3 Columns', 'aawp' ) },
            { value: '4', label: __( '4 Columns', 'aawp' ) },
            { value: '5', label: __( '5 Columns', 'aawp' ) },
            { value: '6', label: __( '6 Columns', 'aawp' ) },
        ]}
        help = { __( 'Displaying product boxes side by side.', 'aawp' ) }
    />;

    let numberingToggle = <ToggleControl
        label = {__( 'Show Numbering', 'aawp')}
        key="aawp-show-numbering-toggle-control"
        checked={ numbering }
        onChange= { (value) => setAttributes( { numbering: value } ) }
        help = { __( 'Shows the numbering col when using table template for unordered lists.', 'aawp' ) }
    />;

    let classInput = <TextControl
        label = {__( 'Custom CSS Class', 'aawp')}
        key="aawp-class-text-control"
        value={ class_attr }
        onChange= { (value) => setAttributes( { class_attr: value } ) }
        help = { __( 'Adding a new class to the product container.', 'aawp' ) }
    />;

    /** Templates & Styles fields end */

    /** Other fields start */
    let trackingIdInput = <TextControl
        label = {__( 'Tracking ID', 'aawp')}
        key="aawp-tracking-id-text-control"
        value={ tracking_id }
        onChange= { (value) => setAttributes( { tracking_id: value } ) }
        help = { __( 'Replacing the tracking id which will be used for affiliate links.', 'aawp' ) }
    />;

    /** Other fields end */

    switch( look ) {
        case 'box':
            itemsRange = linkIconSelect = linkClassInput = imageAlignSelect = imageWidthInput = imageHeightInput = imageClassInput = saleRibbonTextInput = [];
        break;

        case 'bestseller':
        case 'new':
            titleInput = linkTitleInput = linkOverwriteInput = linkIconSelect = linkClassInput = descriptionInput = imageInput = imageTitleInput = imageAltInput = imageAlignSelect = imageWidthInput = imageHeightInput = imageClassInput = ratingInput = [];
        break;

        case 'fields':
            itemsRange = orderSelect = orderbySelect = orderItemsRange = filterbySelect = filterInput = filterItemsRange = filterTypeSelect = filterCompareSelect = ribbonSelect = ribbonTextInput = linkIconSelect = linkClassInput = buttonToggle =  saleRibbonTextInput = starRatingToggle = reviewsToggle = gridSelect = numberingToggle = classInput = [];
        break;

        case 'link':
            itemsRange = orderSelect = orderbySelect = orderItemsRange = filterbySelect = filterInput = filterItemsRange = filterTypeSelect = filterCompareSelect = ribbonSelect = ribbonTextInput = ratingInput = starRatingToggle = reviewsToggle = gridSelect = numberingToggle = classInput = [];
        break;
        
        default:
    }

    if ( 'bestseller' === look || 'new' === look || ( 'box' === look && asin.includes( ',' ) ) ) {

        panel = [ ...panel,
                    <PanelBody key="lists" title= {__( 'Lists', 'aawp')} initialOpen={ false } >
                        {'box' !== look ? itemsRange : ''}
                        {ribbonSelect}
                        {ribbon === true ? ribbonTextInput : ''}
                    </PanelBody>
                ]

        panel = [ ...panel,
            <PanelBody key="order-products" title= {__( 'Order Products', 'aawp')} initialOpen={ false } >
                { [ orderbySelect, orderSelect, orderItemsRange ] }
            </PanelBody>
        ]

        panel = [ ...panel,
            <PanelBody key="filter-products" title= {__( 'Filter Products', 'aawp')} initialOpen={ false } >
                { [ filterbySelect, filterInput, filterItemsRange, filterTypeSelect, filterCompareSelect ] } 
            </PanelBody>
        ]
    }

    if ( look !== 'fields' || ( look === 'fields' && value_attr === 'title' ) ) {

        panel = [ ...panel,
                    <PanelBody key="title" title={__('Title', 'aawp')} initialOpen={ false }>
                        { ! asin.includes( ',' ) ? titleInput : '' }
                        {titleLengthInput}
                    </PanelBody>
                ]
    }


    if ( 'fields' !== look ) {

        panel = [ ...panel,
            <PanelBody key="links" title={__('Links', 'aawp')} initialOpen={ false }>
                { ! asin.includes( ',' ) ? [ linkTitleInput, linkOverwriteInput ] : '' }
                { [ linkTypeSelect, linkIconSelect, linkClassInput ] }
            </PanelBody>
        ]
    } else {
        let values = [ 'title', 'thumb', 'star_rating', 'price', 'button' ];

        if ( values.includes( value_attr ) ) {
             panel = [ ...panel,
                <PanelBody key="links" title={__('Links', 'aawp')} initialOpen={ false }>

                { ! ['button', 'thumb'].includes( value_attr ) ?
                    <ToggleControl
                        label = {__( 'Apply a link to the output', 'aawp')}
                        key="aawp-apply-link-toggle-control"
                        checked={ apply_link }
                        onChange= { (value) => setAttributes( { apply_link: value } ) }
                    />
                : '' }

                { ( true === apply_link || ['button', 'thumb'].includes( value_attr ) ) ? [ linkTitleInput, linkTypeSelect] : '' }

                </PanelBody>
            ]
        }
    }

    if ( 'link' !== look ) {

        if ( look !== 'fields' || ( look === 'fields' && value_attr === 'description' ) ) {

            panel = [ ...panel,
                        <PanelBody key="description" title={__('Description', 'aawp')} initialOpen={ false }>
                            { ! asin.includes( ',' ) ? descriptionInput : '' }
                            { [ descriptionItemsRange, descriptionLengthInput ] }
                        </PanelBody>
                    ]
        }

        if ( look !== 'fields' || ( look === 'fields' && value_attr === 'thumb' ) ) {

            if ( asin.includes( ',' ) ) {
                imageInput = imageAltInput = imageTitleInput = imageAlignSelect = imageWidthInput = imageHeightInput = imageClassInput = [];
            }

            panel = [ ...panel, 
                        <PanelBody key="images" title={__('Thumbnail', 'aawp')} initialOpen={ false }>
                            { [ imageInput, imageSizeSelect, imageAltInput, imageTitleInput, imageAlignSelect, imageWidthInput, imageHeightInput, imageClassInput ] }
                        </PanelBody>
                    ]
        }

        if ( look !== 'fields' || ( look === 'fields' && value_attr === 'button' ) ) {
        
            panel = [ ...panel,
                        <PanelBody key="button" title={__('Amazon Button', 'aawp')} initialOpen={ false }>
                            {buttonToggle}
                            {button === true ? buttonTextInput : ''}
                        </PanelBody>
                    ]
        }

         if ( ! ( asin.includes( ',') || 'bestseller' === look || 'new' === look  ) ) {

            if ( 'fields' !== look ) {

                panel = [ ...panel,
                    <PanelBody key="extra-detail" title={__('Extra Button', 'aawp')} initialOpen={ false }>
                        { [ buttonDetailInput, buttonDetailTextInput, buttonDetailTitleInput, buttonDetailTargetInput, buttonDetailRelInput ] }
                    </PanelBody>
                ]
            }

            if ( look !== 'fields' || ( look === 'fields' && value_attr === 'price' ) ) {
                panel = [ ...panel,
                            <PanelBody key="pricing" title={__('Price', 'aawp')} initialOpen={ false }>
                                {priceInput}
                                {saleRibbonTextInput}
                            </PanelBody>
                        ]
            } 
        }
    }

    if ( look !== 'fields' || ( look === 'fields' && value_attr === 'star_rating' ) ) {    
        panel = [ ...panel,
                    <PanelBody key="star-ratings" title={__('Rating', 'aawp')} initialOpen={ false }>
                        {starRatingToggle}
                        {star_rating === true && ! asin.includes( ',' ) ? ratingInput : '' }
                        {reviewsToggle}
                    </PanelBody>
                ]
    }

    if ( look !== 'fields' ) {

        panel = [ ...panel,
                    <PanelBody key="templates-and-styles" title={__('Templates & Styles', 'aawp')} initialOpen={ false }>
                        {templateSelect}
                        {asin.includes( ',' ) || 'bestseller' === look || 'new' === look ? gridSelect : ''}
                        {template === 'table' ? numberingToggle : '' }
                        {classInput}
                    </PanelBody>
                ] 
    }

    let oPanel =  <PanelBody key="other" title={__('Other', 'aawp')} initialOpen={ false }>
                    {trackingIdInput}
                </PanelBody>;

    panel = [ ...panel, oPanel ]

    // Because table has only "Other" panel.
    if ( 'table' === look ) {
        panel = oPanel;
    }

    jsx = [
            <InspectorControls key="aawp-look-selector-inspector-controls">
                <PanelBody title= { __( 'General Settings', 'aawp' ) }>
                    <SelectControl
                        label= { __( 'Select A Display Type', 'aawp' ) }
                        value= { look }
                        options={[
                            { value: '', label: __( '-- Select A Display Type --', 'aawp' ) },
                            { value: 'box', label: __( 'Product Boxes', 'aawp' ) },
                            { value: 'bestseller', label: __( 'Bestseller (Lists)', 'aawp' ) },
                            { value: 'new', label: __( 'New Releases (Lists)', 'aawp' ) },
                            { value: 'fields', label: __( 'Fields (Single product data)', 'aawp' ) },
                            { value: 'link', label: __( 'Text Links', 'aawp' ) },
                            { value: 'table', label: __( 'Comparison  Table', 'aawp' ) }
                        ]}   
                        onChange= { (value) => setAttributes( { look: value } ) }
                    />
                    { 'fields' !== look ? nextInput : [nextInput, valueSelect] }
                </PanelBody>
            
                { '' === look ? [] : panel }

            </InspectorControls>
        ];

        if ( look && ( asin || keywords || table ) ) {

            jsx.push(
                <ServerSideRender
                    key="aawp-server-side-renderer"
                    block="aawp/aawp-block"
                    attributes={ props.attributes }
                />
            );
        } else {

            jsx.push(
                <Placeholder
                    key="aawp-look-selector-wrap"
                    className="aawp-look-selector-wrap">
                    <img src={ aawp_data.icons.logo }/>
                    <p className="block-selector-text">{ __( 'Choose your display variant:', 'aawp' ) }</p>

                    <RadioControl
                        key="aawp-look-selector-radio-control"
                        className="aawp-look-selector-radio-control"
                        selected={ look }
                        options={[
                            { value: 'box', label: [ <img key="aawp-look-selector-box-image" src={aawp_data.icons.box} alt="Product Boxes"/>, <p key="aawp-look-selector-box-label"> { __( 'Product Boxes', 'aawp' ) } </p> ] },
                            { value: 'bestseller', label: [ <img src={aawp_data.icons.bestseller} key="aawp-look-selector-bestseller-image" alt="Bestseller (Lists)" /> , <p key="aawp-look-selector-bestseller-label"> { __( 'Bestseller Lists', 'aawp' ) } </p> ] },
                            { value: 'new', label: [ <img src={aawp_data.icons.new} key="aawp-look-selector-new-image" alt="New Releases (Lists)" />, <p key="aawp-look-selector-new-label"> { __( 'New Releases', 'aawp' ) } </p> ] },
                            { value: 'fields', label: [ <img src={aawp_data.icons.fields} key="aawp-look-selector-fields-image" alt="Fields (Single product data)" />, <p key="aawp-look-selector-fields-label"> { __( 'Data Fields', 'aawp' ) } </p> ] },
                            { value: 'link', label: [ <img src={aawp_data.icons.link} key="aawp-look-selector-links-image" alt="Text Links" />, <p key="aawp-look-selector-links-label"> { __( 'Text Links', 'aawp' ) } </p> ] },
                            { value: 'table', label: [ <img src={aawp_data.icons.table} key="aawp-look-selector-table-image" alt="Table" />, <p key="aawp-look-selector-table-label"> { __( 'Comparison Tables', 'aawp' ) } </p> ] }
                        ]}
                        onChange= { (value) => setAttributes( { look: value } ) }
                    />
                </Placeholder>                
            );
        }

        return (
            <div { ...useBlockProps() }>
                {jsx}
            </div>
        );
}
