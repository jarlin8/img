/**
 * WordPress dependencies
 */
import {__} from '@wordpress/i18n';
import {Component, Fragment, createRef} from '@wordpress/element';
import {withFocusOutside} from '@wordpress/components';
import {compose} from "@wordpress/compose";
import {RichText} from '@wordpress/block-editor';

/**
 * Internal dependencies
 */
import Inspector from "./Inspector";
import Controls from "./Controls";
import AddItemButton from "../../components/add-item-button";

/**
 * External dependencies
 */
import classnames from "classnames";
import {cloneDeep} from 'lodash';

class EditBlock extends Component {
	constructor() {
		super(...arguments);
		this.accordionRef = createRef();
	}

	componentDidMount() {
		const accordionNode = this.accordionRef.current;

		accordionNode.addEventListener('click', function (ev) {
			const targetClass = ev.target.className;

			if (targetClass.indexOf('c-accordion-item__title') >= 0) {
				const items = accordionNode.getElementsByClassName('c-accordion-item');
				const currentItemClass = ev.target.parentNode.className;

				for (let i = 0; i < items.length; i++) {
					items[i].className = 'c-accordion-item close';
				}

				if (currentItemClass === 'c-accordion-item close') {
					ev.target.parentNode.className = 'c-accordion-item open';
					ev.target.nextSibling.classList.add('stuckMoveDownOpacity');
				}
			}
		}, false);
	}

	render() {
		const {isSelected, className, attributes, setAttributes} = this.props;
		const mainClasses = classnames([className, 'c-accordion']);
		const {tabs} = attributes;

		return (
			<Fragment>
				{isSelected && (
					<Fragment>
						<Inspector {...this.props} />
						<Controls {...this.props} />
					</Fragment>
				)}
				<div className={mainClasses} ref={this.accordionRef}>
					{tabs.map((tab, index) => {
						const {title, content} = tab;

						return (
							<div className='c-accordion-item close' key={index}>
								<RichText
									placeholder={__('Sample title', 'rehub-framework')}
									tagName="h3"
									className="c-accordion-item__title"
									value={title}
									onChange={(value) => {
										const tabsClone = cloneDeep(tabs);
										tabsClone[index].title = value;

										setAttributes({
											tabs: tabsClone
										});
									}}
								/>
								<div className='c-accordion-item__content'>
									<RichText
										placeholder={__('Sample content', 'rehub-framework')}
										tagName="div"
										className="c-accordion-item__text"
										value={content}
										onChange={(value) => {
											const tabsClone = cloneDeep(tabs);
											tabsClone[index].content = value;

											setAttributes({
												tabs: tabsClone
											});
										}}
									/>
								</div>
							</div>
						);
					})}
					<AddItemButton
						className='pt15'
						handleClick={() => {
							const tabsClone = cloneDeep(tabs);
							tabsClone.push({
								title: __('Sample title', 'rehub-framework'),
								content: __('Sample content', 'rehub-framework')
							});

							setAttributes({tabs: tabsClone});
						}}
					/>
				</div>
			</Fragment>
		);
	}
}

export default compose(
	withFocusOutside
)(EditBlock);