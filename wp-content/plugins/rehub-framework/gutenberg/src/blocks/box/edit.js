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
				type,
				float,
				textalign,
				content,
				takeDate,
				date,
				label,
			},
		} = this.props;

		const _content = <div className={classnames(
				'wpsm_box',
				`${type}_type`,
				`${float}float_box`
			)}
			style={{
				textAlign: textalign,
				}}>
			<i></i>
			{takeDate && <span className="label-info">{date} {label}</span>}
				<div>
					<RichText
						placeholder={__('Content', 'rehub-framework')}
						value={content}
						onChange={content => setAttributes({content})}
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
