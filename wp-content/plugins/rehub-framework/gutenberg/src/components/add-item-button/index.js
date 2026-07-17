/**
 * WordPress dependencies
 */
import {Button} from "@wordpress/components";

/**
 * External dependencies
 */
import classnames from "classnames";

const AddItemButton = (props) => {
	const {handleClick, className} = props;
	const classes = classnames([className, 'component-add-item-button-wrapper']);

	return (
		<div className={classes}>
			<Button isTertiary
			        isSmall
			        onClick={handleClick}
			        className='component-add-item-button'>
				<i className="rhicon rhi-plus-circle"/>
			</Button>
		</div>
	);
};

export default AddItemButton;