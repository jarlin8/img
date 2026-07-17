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
				content,
				level,
				backgroundText,
				textAlign,
			},
		} = this.props;
		const tagName = 'h' + level;

		const wrapperClasses = {
			center: 'rh-flex-justify-center',
			left: 'rh-flex-justify-start',
			right: 'rh-flex-justify-end',
		}
		const numberClasses = {
			center: 'text-center',
			left: 'text-left-align',
			right: 'text-right-align',
		}

		const _content = <div className={classnames(
			'wpsm_heading_number',
			'position-relative',
			'mb25',
			'rh-flex-center-align',
			wrapperClasses[textAlign],
		)}>
			<div className={classnames(
				'number',
				'abdfullwidth',
				'width-100p',
				numberClasses[textAlign],
			)}>
				{backgroundText}
			</div>
			<div className="wpsm_heading_context position-relative">
				<RichText
					tagName={tagName}
					className={'mt0 mb0 ml15 mr15'}
					placeholder={__('Write heading…', 'rehub-framework')}
					value={content}
					onChange={(content) => setAttributes({content})}
					multiline={false}
					allowedFormats={[]}
					keepPlaceholderOnFocus={true}
					unstableOnSplit={() => false}
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
