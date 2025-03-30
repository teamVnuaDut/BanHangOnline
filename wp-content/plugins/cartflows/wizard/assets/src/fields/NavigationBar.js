import React, { useEffect, useState } from 'react';
import { __ } from '@wordpress/i18n';
import CfIcon from '@WizardImages/cartflows-icon.svg';
import { getExitSetupWizard } from '@Utils/Helpers';
import apiFetch from '@wordpress/api-fetch';
import { useLocation, useHistory } from 'react-router-dom';
import { Disclosure } from '@headlessui/react';
import { XMarkIcon } from '@heroicons/react/24/outline';

function Index() {
	const search = useLocation().search;
	const history = useHistory();
	const stepParam = new URLSearchParams( search ).get( 'step' );
	const step = stepParam || 'welcome';

	const [ lastVisitedStep ] = useState( step );
	const woocommerce_status = window.cartflows_wizard?.woocommerce_status;
	const [ completedSteps ] = useState( () => {
		return JSON.parse( localStorage.getItem( 'completedSteps' ) ) || [];
	} );

	// Add useEffect to synchronize completedSteps with navigation
	useEffect( () => {
		localStorage.setItem(
			'completedSteps',
			JSON.stringify( completedSteps )
		);
	}, [ completedSteps ] );

	const menus = [
		{ name: __( 'Welcome', 'cartflows' ), id: 'welcome' },
		{ name: __( 'Page Builder', 'cartflows' ), id: 'page-builder' },
		{ name: __( 'Required Plugins', 'cartflows' ), id: 'plugin-install' },
		{ name: __( 'Store Checkout', 'cartflows' ), id: 'store-checkout' },
		{ name: __( 'Subscribe', 'cartflows' ), id: 'optin' },
		{ name: __( 'Done', 'cartflows' ), id: 'ready' },
	];

	const handleExit = ( e ) => {
		e.preventDefault();
		const ajaxData = new window.FormData();
		ajaxData.append( 'action', 'cartflows_onboarding_exit' );
		ajaxData.append( 'security', cartflows_wizard.onboarding_exit_nonce );
		ajaxData.append( 'current_step', step );

		apiFetch( { url: ajaxurl, method: 'POST', body: ajaxData } ).then(
			( response ) => {
				if ( response.success ) {
					window.location.href = getExitSetupWizard();
				}
			}
		);
	};

	const handleStepRedirection = ( e ) => {
		e.preventDefault();
		const stepToRedirect = e.target.id;
		const stepIndex = menus.findIndex(
			( menu ) => menu.id === stepToRedirect
		);
		const currentStepIndex = menus.findIndex(
			( menu ) => menu.id === step
		);

		if (
			stepIndex < currentStepIndex ||
			lastVisitedStep === stepToRedirect
		) {
			history.push( {
				pathname: 'index.php',
				search: `?page=cartflow-setup&step=${ stepToRedirect }`,
			} );
			return;
		}

		if (
			stepToRedirect === 'store-checkout' &&
			step === 'plugin-install' &&
			woocommerce_status === 'active'
		) {
			history.push( {
				pathname: 'index.php',
				search: `?page=cartflow-setup&step=store-checkout`,
			} );
			return;
		}

		if ( ! completedSteps.includes( menus[ stepIndex - 1 ]?.id ) ) {
			alert(
				__(
					'Please complete the previous step before proceeding.',
					'cartflows'
				)
			);
			return;
		}

		history.push( {
			pathname: 'index.php',
			search: `?page=cartflow-setup&step=${ stepToRedirect }`,
		} );
	};

	return (
		<Disclosure
			as="nav"
			className="bg-white fixed top-0 w-full z-30 border-b border-slate-200"
		>
			{ () => (
				<div className="px-4 sm:px-6 lg:px-8">
					<div className="flex h-16 justify-between">
						<div className="flex flex-shrink-0 items-center">
							<img
								className="block lg:hidden h-8 w-auto"
								src={ CfIcon }
								alt="CartFlows"
							/>
							<img
								className="hidden lg:block h-8 w-auto"
								src={ CfIcon }
								alt="CartFlows"
							/>
						</div>
						<MenuNavigation
							menus={ menus }
							step={ step }
							completedSteps={ completedSteps }
							handleStepRedirection={ handleStepRedirection }
						/>
						<button
							type="button"
							className="rounded-full bg-white p-1 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
							onClick={ handleExit }
							title={ __( 'Exit setup wizard', 'cartflows' ) }
						>
							<span className="sr-only">Exit Wizard</span>
							<XMarkIcon className="h-6 w-6" aria-hidden="true" />
						</button>
					</div>
				</div>
			) }
		</Disclosure>
	);
}

const MenuNavigation = ( {
	menus,
	step,
	completedSteps,
	handleStepRedirection,
} ) => {
	return (
		<div className="wcf-wizard-menu--navbar hidden md:flex lg:space-x-8 space-x-4">
			{ menus.map( ( menu ) => {
				const menuIndex = menus.findIndex( ( m ) => m.id === menu.id );
				const stepIndex = menus.findIndex( ( m ) => m.id === step );
				const isCompleted =
					menuIndex < stepIndex || completedSteps.includes( menu.id );
				const isStoreCheckout =
					menu.id === 'store-checkout' &&
					step === 'plugin-install' &&
					window.cartflows_wizard?.woocommerce_status === 'active';
				const isActive = step === menu.id;
				const isClickable = isCompleted || isStoreCheckout;
				let borderClass;
				let textClass;

				if ( isActive ) {
					borderClass = 'border-primary-500';
					textClass = 'text-gray-800';
				} else if ( isClickable ) {
					borderClass = 'border-transparent';
					textClass =
						'text-gray-300 hover:border-gray-300 hover:text-gray-700';
				} else {
					borderClass = 'border-transparent';
					textClass = 'text-gray-300 cursor-not-allowed';
				}

				const linkClasses = `inline-flex items-center border-b-2 px-1 pt-1 font-medium focus:outline-none focus:shadow-none text-sm lg:text-base ${ borderClass } ${ textClass }`;

				return (
					<a
						href="#"
						className={ linkClasses }
						id={ menu.id }
						onClick={
							isClickable
								? handleStepRedirection
								: ( e ) => e.preventDefault()
						}
						key={ menu.id }
					>
						{ menu.name }
					</a>
				);
			} ) }
		</div>
	);
};

export default Index;
