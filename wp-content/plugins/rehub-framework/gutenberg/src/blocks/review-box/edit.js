/**
 * WordPress dependencies
 */
import {__} from '@wordpress/i18n';
import {RichText} from '@wordpress/block-editor';
import {Fragment} from '@wordpress/element';

/**
 * External dependencies
 */
import classnames from "classnames";

/**
 * Internal dependencies
 */
import Inspector from "./Inspector";
import Criteria from "./Criteria";
import ConsPros from "../../components/cons-pros";

import { useSelect } from '@wordpress/data';

export default function edit(props) {
	const {className, isSelected, attributes, setAttributes} = props;
	const {title, description, score, scoreManual, mainColor, criterias, prosTitle, positives, consTitle, negatives} = attributes;
	const mainClasses = classnames([className, 'c-review-box']);
	let totalScore = 0;

	const scoreStyles = {
		backgroundColor: mainColor
	};

	const postId = useSelect(
		( select ) => select( 'core/editor' ).getCurrentPostId(),
		[]
	);

	// Recalculate score by criterias
	if (criterias.length > 0) {
		criterias.forEach((item) => {
			totalScore += item.value;
		});

		totalScore = totalScore / criterias.length;
		totalScore = +totalScore.toFixed(1);
	} else {
		totalScore = 0;
	}
	setAttributes({postId: postId, score: totalScore});
	return (
		<Fragment>
			{isSelected && (
				<Fragment>
					<Inspector {...props} />
				</Fragment>
			)}
			<div className={mainClasses}>
				<div className='c-review-box__wrapper'>
					<div className="review-top">
						<div className="overall-score" style={scoreStyles}>
							<span className='overall'>{scoreManual ? scoreManual : score}</span>
							<span className='overall-text'>{__('Expert Score', 'rehub-framework')}</span>
						</div>
						<div className="review-text">
							<RichText
								placeholder={__('Awesome', 'rehub-framework')}
								tagName="span"
								className="review-header"
								value={title}
								onChange={(value) => {
									setAttributes({
										title: value
									});
								}}
								keepPlaceholderOnFocus
							/>
							<RichText
								placeholder={__('Place here Description for your reviewbox', 'rehub-framework')}
								tagName="p"
								value={description}
								onChange={(value) => {
									setAttributes({
										description: value
									});
								}}
								keepPlaceholderOnFocus
							/>
						</div>
					</div>
					<Criteria
						setAttributes={setAttributes}
						criterias={criterias}
						mainColor={mainColor}
					/>
					<ConsPros
						setAttributes={setAttributes}
						prosTitle={prosTitle}
						consTitle={consTitle}
						positives={positives}
						negatives={negatives}
						className='mt20'
					/>
				</div>
			</div>
		</Fragment>
	);
}