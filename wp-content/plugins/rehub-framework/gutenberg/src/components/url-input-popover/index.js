/**
 * WordPress dependencies
 */
import {withState} from '@wordpress/compose';
import {
	Dashicon, IconButton, Popover, PanelBody, ToggleControl, TextControl,
} from '@wordpress/components';
import {URLInput} from '@wordpress/block-editor';
import {__} from '@wordpress/i18n';

/**
 * External dependencies
 */
import classnames from 'classnames';


const ariaClosed = __('Show more tools & options', 'rehub-framework');
const ariaOpen = __('Hide more tools & options', 'rehub-framework');

const UrlInputPopover = withState({
	openAdvanced: false,
})(props => {
	const {openAdvanced, setState,} = props;

	if (!props.onChange && !props.onChangeNewTab && !props.onChangeNoFollow && !props.onChangeModal && !props.onChangeModalId) {
		return null
	}

	const mainClassName = classnames([
		'rehub-url-input-popover',
	], {
		'rehub--show-advanced': openAdvanced,
	});

	const moreButtonClasses = classnames([
		'rehub-url-input-control__more-button',
	], {
		'rehub--active': props.newTab || props.noFollow || props.onModal || props.onModalId,
	});

	return (
		<Popover
			className={mainClassName}
			focusOnMount={false}
			position="bottom center"
		>
			<PanelBody>
				<div className="rehub-url-input-popover__input-wrapper">
					<Dashicon className="rehub-url-input-control__icon" icon="admin-links"/>
					{props.onChange && !props.disableSuggestions && // Auto-suggestions for inputting url.
					<URLInput
						className="rehub-url-input-control__input"
						value={props.value}
						onChange={props.onChange}
						autoFocus={false} // eslint-disable-line
					/>
					}
					{props.onChange && props.disableSuggestions && // Plain text control for inputting url.
					<TextControl
						className="rehub-url-input-control__input rehub-url-input-control__input--plain"
						value={props.value}
						onChange={props.onChange}
						autoFocus={false} // eslint-disable-line
						placeholder={__('Paste or type URL', 'rehub-framework')}
					/>
					}
					{(props.onChangeNewTab || props.onChangeNoFollow || props.onChangeModal) &&
					<IconButton
						className={moreButtonClasses}
						icon="ellipsis"
						label={openAdvanced ? ariaOpen : ariaClosed}
						onClick={() => setState({openAdvanced: !openAdvanced})}
						aria-expanded={openAdvanced}
					/>
					}
				</div>
				{props.onChangeNewTab && openAdvanced &&
				<ToggleControl
					label={__('Open link in new tab', 'rehub-framework')}
					checked={props.newTab}
					onChange={props.onChangeNewTab}
				/>
				}
				{props.onChangeNoFollow && openAdvanced &&
				<ToggleControl
					label={__('Nofollow link', 'rehub-framework')}
					checked={props.noFollow}
					onChange={props.onChangeNoFollow}
				/>
				}
				{props.onChangeModal && openAdvanced &&
				<ToggleControl
					label={__('Open link in modal', 'rehub-framework')}
					checked={props.onModal}
					onChange={props.onChangeModal}
				/>
				}
				{props.onChangeModalId && props.onModal && openAdvanced &&
				<TextControl
					className="rehub-url-input-control__input rehub-url-input-control__input--plain"
					value={props.onModalId}
					onChange={props.onChangeModalId}
					autoFocus={false}
					placeholder={__('Modal ID', 'rehub-framework')}
				/>
				}
			</PanelBody>
		</Popover>
	)
});

UrlInputPopover.defaultProps = {
	value: '',
	disableSuggestions: false,
	onChange: null,

	newTab: false,
	noFollow: false,
	onChangeNewTab: null,
	onChangeNoFollow: null,
	onChangeModal: null,
	onChangeModalId: null,
};

export default UrlInputPopover
