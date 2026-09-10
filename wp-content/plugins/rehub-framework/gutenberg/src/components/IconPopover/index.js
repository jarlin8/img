/**
 * WordPress dependencies
 */
import {__} from '@wordpress/i18n';
import {Component} from '@wordpress/element'
import {PanelBody, Button, Popover, TextControl} from '@wordpress/components';

/**
 * Internal dependencies
 */
import icons from "./icons";

// Limit to 100 searches as not to stall the browser.
const MAX_SEARCH_ICONS = 100;

const searchIcon = (search) => {
	const lowerSearch = search && search.toLowerCase();
	const results = icons.filter((icon) => {
		return icon.indexOf(lowerSearch) >= 0;
	});

	return results.slice(0, MAX_SEARCH_ICONS)
};

export default class IconPopover extends Component {
	constructor(props) {
		super(props);
		this.state = {
			openPopover: false,
			clickedOnButton: false,
			value: ''
		};

		this.handleIconButtonClick = this.handleIconButtonClick.bind(this);
		this.closePopover = this.closePopover.bind(this);
		this.handleIconClick = this.handleIconClick.bind(this);
		this.handleClickOutside = this.handleClickOutside.bind(this);
	}

	handleIconButtonClick() {
		if (!this.state.clickedOnButton) {
			this.setState({openPopover: true});
		} else {
			this.setState({
				openPopover: false,
				clickedOnButton: false
			});
		}
	}

	closePopover() {
		this.setState({openPopover: false});
	}

	handleIconClick(value) {
		this.props.onChange(value);
		this.setState({openPopover: false});
	}

	handleClickOutside(event) {
		if (event.target) {
			if (event.target.closest('.rehub-icon-popover')) {
				this.setState({clickedOnButton: true});
				return;
			}
		}
		this.setState({
			openPopover: false,
			clickedOnButton: false
		})
	}

	render() {
		const {currentIcon} = this.props;
		const result = searchIcon(this.state.value);

		return (
			<div className='rehub-icon-popover'>
				<div className="rehub-icon-popover__button-wrapper">
					<div>
						<Button className='rehub-icon-popover__icon-button'
						        isDefault
						        onClick={this.handleIconButtonClick}
						>
							{currentIcon !== '' ? (
								<i className={currentIcon}/>
							) : (
								<i className='rhicon rhi-plus'/>
							)}
						</Button>
						{this.state.openPopover && (
							<Popover
								onClose={this.closePopover}
								onClickOutside={this.handleClickOutside}>
								<PanelBody>
									<div className="rehub-icon-popover__label-container">
										<TextControl
											className="rehub-icon-popover__input"
											placeholder={__('Type to search icon', 'rehub-framework')}
											value={this.state.value}
											onChange={(value) => this.setState({value})}
										/>
										<Button isSmall
										        isDefault
										        className='rehub-icon-popover__reset'
										        onClick={() => this.setState({value: ''})}
										>
											{__('Reset', 'rehub-framework')}
										</Button>
									</div>
									<div className='rehub-icon-popover__list'>
										{result.map((icon, index) => {
											return (
												<button key={index} className='rehub-icon-popover__icon'
												        onClick={() => this.handleIconClick(icon)}>
													<i className={icon}/>
												</button>
											);
										})}
									</div>
								</PanelBody>
							</Popover>
						)}
					</div>
					<Button className="rehub-icon-popover__reset"
					        isSmall
					        isDefault
					        onClick={this.props.onReset}>
						{__('Reset', 'rehub-framework')}
					</Button>
				</div>
			</div>
		);
	}
}