import Inspector from './inspector';
import Controls from './controls';
import classnames from 'classnames';
import {Fragment} from '@wordpress/element';

const {
	RichText,
} = wp.blockEditor || wp.editor;

const {__} = wp.i18n;
const {Component} = wp.element;

class EditBlock extends Component {
	render() {
		const {
			isSelected,
			setAttributes,
			attributes: {
				style,
				title,
				text,
			},
		} = this.props;

		let themeclass = '',
			colorclass = '';

		switch (style) {
			case 'main':
				themeclass = ' rehub-main-color-border';
				colorclass = 'rehub-main-color';
				break;
			case 'secondary':
				themeclass = ' rehub-sec-color-border';
				colorclass = 'rehub-sec-color';
				break;
		}

		const _content = <div className={classnames(
			'wpsm-titlebox',
			`wpsm_style_${style}`,
			themeclass
		)}>
			<strong>
				<RichText
				placeholder={__('Title', 'rehub-framework')}
				value={title}
				onChange={(title) => setAttributes({title})}
				multiline={false}
				allowedFormats={[]}
				keepPlaceholderOnFocus={true}
				className={colorclass}
				unstableOnSplit={ () => false }
			/>
			</strong>
			<div>
				<RichText
					placeholder={__('Content', 'rehub-framework')}
					value={text}
					onChange={text => setAttributes({text})}
					keepPlaceholderOnFocus={true}
				/>
			</div>
		</div>;

	return (
			<Fragment>
				{isSelected && <Fragment>
					<Inspector {...this.props} />
					<Controls {...this.props} />
				</Fragment>}
				{_content}
			</Fragment>
		)
	}
}

export default EditBlock;
