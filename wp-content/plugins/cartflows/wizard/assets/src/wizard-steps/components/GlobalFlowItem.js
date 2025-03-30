import { __ } from '@wordpress/i18n';

// SCSS.
import './GlobalFlowItem.scss';

function GlobalFlowItem( { item, isChecked, isActive } ) {
	const { thumbnail_image_url, title, type, is_recommanded } = item;

	return (
		<>
			{ 'pro' === type ? (
				<span className={ `wcf-item__type wcf-item__type--${ type }` }>
					{ type }
				</span>
			) : (
				''
			) }

			<div
				className={ `wcf-item__inner bg-white border shadow-sm relative overflow-hidden rounded-lg cursor-pointer transition-all block group hover:-translate-y-px hover:border-primary-400 hover:shadow-xl hover:shadow-primary-50 ${
					isChecked || isActive
						? 'border-primary-400'
						: 'border-slate-200'
				}` }
			>
				{ is_recommanded && (
					<span
						className={ `wcf-item__recommanded-badge bg-primary-500 border-primary-500 text-white absolute top-0 right-0 px-2.5 py-0.5 font-normal text-xs rounded-tr-lg rounded-bl-lg border cursor-default z-10` }
					>
						{ __( 'Recommended', 'cartflows' ) }
					</span>
				) }
				<div className="wcf-item__thumbnail-wrap transition-none">
					<div
						className="wcf-item__thumbnail group-hover:transform-none bg-white relative position bg-top bg-cover bg-no-repeat overflow-hidden before:block before:pt-[100%]"
						style={ {
							backgroundImage: `url("${ thumbnail_image_url }")`,
						} }
					></div>
				</div>
				<div className="wcf-item__heading-wrap py-2.5 px-4 text-center border-t border-slate-200">
					<div className="wcf-item__heading text-slate-600 text-center text-base font-semibold">
						{ title }
					</div>
				</div>
			</div>
		</>
	);
}

export default GlobalFlowItem;
