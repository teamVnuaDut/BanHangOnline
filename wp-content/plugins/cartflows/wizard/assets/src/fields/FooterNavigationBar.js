import React, { useState } from 'react';
import { __ } from '@wordpress/i18n';
import { useStateValue } from '../utils/StateProvider';
import { useHistory, useLocation } from 'react-router-dom';
import apiFetch from '@wordpress/api-fetch';
import { ArrowRightIcon } from '@heroicons/react/24/outline';

function FooterNavigationBar( {
	previousStep,
	nextStep,
	currentStep,
	maxSteps,
} ) {
	const history = useHistory();
	const query = new URLSearchParams( useLocation().search );
	const currentActiveStep = query.get( 'step' );
	const [ completedSteps, setCompletedSteps ] = useState( () => {
		return JSON.parse( localStorage.getItem( 'completedSteps' ) ) || [];
	} );
	const [
		{
			settingsProcess,
			showFooterImportButton,
			action_button,
			isStoreCheckoutImported,
			selected_page_builder,
		},
	] = useStateValue();

	const woocommerceStatus =
		window.cartflows_wizard?.woocommerce_status ?? null;

	const isStepCompleted =
		completedSteps.includes( currentActiveStep ) ||
		( currentActiveStep === 'plugin-install' &&
			String( woocommerceStatus ).toLowerCase() === 'active' ) ||
		[ 'store-checkout', 'optin', 'ready' ].includes( currentActiveStep );

	const getNextButtonString = () => {
		if (
			nextStep &&
			! [ 'ready', 'store-checkout', 'optin' ].includes(
				currentActiveStep
			)
		) {
			return __( 'Next', 'cartflows' );
		} else if (
			[ 'store-checkout', 'optin' ].includes( currentActiveStep )
		) {
			return __( 'Skip', 'cartflows' );
		}
		return __( 'Finish Store Setup', 'cartflows' );
	};

	const handlePreviousStep = () => {
		if ( previousStep !== 'dashboard' ) {
			history.push( {
				pathname: 'index.php',
				search: `?page=cartflow-setup&step=${ previousStep }`,
			} );
		}
	};

	const handleNextStep = ( e ) => {
		e.preventDefault();

		if ( ! isStepCompleted ) {
			return;
		}

		setCompletedSteps( ( prev ) => [
			...new Set( [ ...prev, currentStep ] ),
		] );

		if ( nextStep && settingsProcess !== 'processing' ) {
			history.push( {
				pathname: 'index.php',
				search: `?page=cartflow-setup&step=${ nextStep }`,
			} );
		}

		if ( ! nextStep && currentActiveStep === 'ready' ) {
			e.target.innerText = __( 'Redirecting..', 'cartflows' );

			const ajaxData = new window.FormData();
			ajaxData.append( 'action', 'cartflows_onboarding_exit' );
			ajaxData.append(
				'security',
				cartflows_wizard.onboarding_exit_nonce
			);
			ajaxData.append( 'current_step', currentActiveStep );

			apiFetch( { url: ajaxurl, method: 'POST', body: ajaxData } ).then(
				( response ) => {
					if ( response.success ) {
						let redirectUrl = '?page=cartflows';
						if ( isStoreCheckoutImported ) {
							redirectUrl += '&path=store-checkout';
						} else if (
							selected_page_builder !== 'bricks-builder'
						) {
							redirectUrl += '&path=library';
						}
						window.location.href =
							cartflows_wizard.admin_url + redirectUrl;
					}
				}
			);
		}
	};

	const isNextDisabled =
		( currentStep !== 'plugin-install' && ! isStepCompleted ) ||
		settingsProcess === 'processing';
	const paginationClass =
		'relative z-10 inline-flex items-center rounded-full p-1 text-sm font-semibold text-white focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-500';
	return (
		<footer className="wcf-setup-footer bg-white shadow-md-1 fixed inset-x-0 bottom-0 h-[70px] z-10">
			<div className="flex items-center justify-between max-w-md mx-auto px-7 py-4 h-full">
				<div className="wcf-footer-left-section">
					<button
						type="button"
						onClick={ handlePreviousStep }
						disabled={ previousStep === 'dashboard' }
					>
						{ __( 'Back', 'cartflows' ) }
					</button>
				</div>
				<div className="wcf-footer--pagination hidden md:-mt-px md:flex gap-3">
					{ Array( maxSteps )
						.fill()
						.map( ( i, index ) => {
							return (
								<span
									key={ index }
									className={ `wcf-footer-pagination--tab ${ paginationClass } ${
										currentStep === index
											? 'bg-primary-500'
											: 'bg-primary-100'
									}` }
								></span>
							);
						} ) }
				</div>

				<div className="wcf-footer-right-section">
					<button
						onClick={ handleNextStep }
						disabled={ isNextDisabled }
						className={
							isNextDisabled
								? 'opacity-50 cursor-not-allowed'
								: ''
						}
					>
						{ getNextButtonString() }
					</button>
				</div>
			</div>

			{ currentActiveStep === 'store-checkout' &&
				showFooterImportButton && (
					<div className="wcf-import-instant-checkout absolute top-4 right-7">
						<button
							className={ `wcf-wizard--button wcf-import-global-flow px-5 py-2 text-sm ${ action_button?.button_class }` }
						>
							{ action_button.button_text }
							<ArrowRightIcon
								className="w-5 mt-0.5 ml-1.5 stroke-2"
								aria-hidden="true"
							/>
						</button>
					</div>
				) }
		</footer>
	);
}

export default FooterNavigationBar;
