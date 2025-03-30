import React, { useState, useEffect, useCallback } from 'react';
import { __ } from '@wordpress/i18n';
import { useHistory } from 'react-router-dom';
import apiFetch from '@wordpress/api-fetch';
import { useStateValue } from '../utils/StateProvider';
import { RadioGroup } from '@headlessui/react';
import { ColorPickerField } from '@WizardFields';
import { sendPostMessage } from '@Utils/Helpers';
import parse from 'html-react-parser';
import { ArrowRightIcon, ArrowLeftIcon } from '@heroicons/react/24/outline';

import GlobalFlowItem from './components/GlobalFlowItem';
import UploadSiteLogo from './components/UploadSiteLogo';
import NoFlowsFound from './components/NoFlowsFound';
import GlobalFlowHeader from './components/GlobalFlowHeader';
import GlobalFlowLibrarySkeleton from './skeletons/GlobalFlowLibrarySkeleton';
import TemplateLoadingSkeleton from './skeletons/TemplateLoadingSkeleton';
import InstantCheckoutImage from '@WizardImages/instant-checkout.png';

import InstantCheckoutData from './data/InstantCheckoutData.json';

function GlobalCheckout() {
	const [ processing, setProcessing ] = useState( true );
	const [ previewProcessing, setPreviewProcessing ] = useState( true );

	const [ ShowSideBar, setShowSideBar ] = useState( false );

	const [ showFlowNotFound, setShowFlowNotFound ] = useState( false );

	const [ filteredFlows, setFilteredFlows ] = useState( 0 );

	const [ selectedStoreFlow, setSelectedFlow ] = useState();
	const [ selectedStoreFlowTitle, setSelectedFlowTitle ] = useState();

	const [ { action_button, selected_page_builder, site_logo }, dispatch ] =
		useStateValue();

	// Removing cartflows_wizard.default_page_builder to ensure the onboarding step displays templates only for the selected builder, not the default or previously chosen one.

	const page_builder = selected_page_builder
		? selected_page_builder
		: 'gutenberg';
	const [ previewUrl, setPreviewUrl ] = useState();

	const [ importErrors, setImportErrors ] = useState( {
		hasError: false,
		errorMessage: '',
		callToAction: '',
	} );

	const { hasError, errorMessage, callToAction } = importErrors;

	const history = useHistory();

	const redirectNextStep = function () {
		history.push( {
			pathname: 'index.php',
			search: `?page=cartflow-setup&step=optin`,
		} );
	};

	function classNames( ...classes ) {
		return classes.filter( Boolean ).join( ' ' );
	}

	const changeButtonText = useCallback( ( data ) => {
		dispatch( {
			status: 'SET_NEXT_STEP',
			action_button: data,
		} );
	}, [] );

	const wcfCartflowsTypePro = function () {
		if (
			cartflows_wizard.is_pro &&
			'pro' === cartflows_wizard.cf_pro_type
		) {
			return true;
		}

		return false;
	};

	const updateStoreCheckoutFunnelList = (
		InstantCheckoutJsonData,
		pageBuilderName,
		pageBuilderSlug
	) => {
		// Make a deep copy of the flow data to avoid mutation
		const updatedInstantCheckoutData = JSON.parse(
			JSON.stringify( InstantCheckoutJsonData )
		);

		// Update the placeholders with dynamic values
		updatedInstantCheckoutData.page_builder = pageBuilderSlug;
		updatedInstantCheckoutData.cartflows_flow_page_builder[ 0 ].name =
			pageBuilderName;
		updatedInstantCheckoutData.cartflows_flow_page_builder[ 0 ].slug =
			pageBuilderSlug;
		updatedInstantCheckoutData.featured_image_url = InstantCheckoutImage;
		updatedInstantCheckoutData.thumbnail_image_url = InstantCheckoutImage;

		return updatedInstantCheckoutData;
	};

	const showHideFooterImportButton = ( showHide ) => {
		dispatch( {
			status: 'SET_SHOW_FOOTER_IMPORT_BUTTON',
			showButton: showHide,
		} );
	};

	useEffect( () => {
		// Set Foooter button text.
		changeButtonText( {
			button_text: __( 'Import & Continue', 'cartflows' ),
			button_class: 'wcf-import-global-flow',
		} );

		if ( filteredFlows <= 0 ) {
			const formData = new window.FormData();
			formData.append( 'action', 'cartflows_get_global_flow_list' );
			formData.append(
				'security',
				cartflows_wizard.get_global_flow_list_nonce
			);

			formData.append( 'page_builder', page_builder );

			setProcessing( true );

			apiFetch( {
				url: cartflows_wizard.ajax_url,
				method: 'POST',
				body: formData,
			} ).then( ( response ) => {
				if ( response?.data?.flows.length > 0 ) {
					const all_flows = Object.values( response.data.flows );
					const parsedFlows = [];
					all_flows.map(
						( flows ) => ( parsedFlows[ flows.ID ] = flows )
					);
					setFilteredFlows( parsedFlows );

					setInstantCheckoutFunnel( parsedFlows );

					setProcessing( false );
				} else {
					setProcessing( false );
					setShowFlowNotFound( false );

					// If there are no funnels found then still show the instant checkout funnels.
					const parsedFlows = setInstantCheckoutFunnel( [], true );

					setFilteredFlows( parsedFlows );
				}
			} );
		}

		const setInstantCheckoutFunnel = (
			parsedFlows = [],
			shouldReturn = false
		) => {
			/**
			 * Add Instant Checkout Store Checkout Funnel Option to the existing.
			 * To-Do: Remove this when a ready-made template is been introduced.
			 *
			 * @since 2.1.0
			 */
			parsedFlows[ 0 ] = updateStoreCheckoutFunnelList(
				InstantCheckoutData,
				page_builder.charAt( 0 ).toUpperCase() +
					page_builder.slice( 1 ),
				page_builder
			);

			setSelectedFlow( Object.keys( parsedFlows )[ 0 ] );
			setSelectedFlowTitle(
				parsedFlows[ Object.keys( parsedFlows )[ 0 ] ].title
			);

			// Setting the instant Checkout as default option to be selected so show the import store checkout button on the footer.
			showHideFooterImportButton( true );

			if ( shouldReturn ) {
				return parsedFlows;
			}
		};

		const importStoreCheckoutSuccessEvent = document.addEventListener(
			'wcf-store-checkout-import-success',
			function () {
				changeButtonText( {
					button_text: __( 'Processing..', 'cartflows' ),
				} );

				dispatch( {
					status: 'SET_STORE_CHECKOUT_IMPORTED',
					storeCheckoutImported: true,
				} );
				console.log( 'Store Checkout Imported' );
				// Redirect to next step once the import is success.
				setTimeout( function () {
					redirectNextStep();
				}, 1000 );
			},
			false
		);

		const importStoreCheckoutProcessEvent = document.addEventListener(
			'wcf-store-checkout-import-text-processing',
			function () {
				changeButtonText( {
					button_text: __( 'Importing..', 'cartflows' ),
				} );
			},
			false
		);

		const importStoreCheckoutErrorEvent = document.addEventListener(
			'wcf-store-checkout-import-error',
			function ( e ) {
				setImportErrors( {
					hasError: e.detail.is_error,
					errorMessage: e.detail.errorMsg,
					callToAction: e.detail.callToAction,
				} );

				changeButtonText( {
					button_text: __( 'Importing Failed..', 'cartflows' ),
				} );
			},
			false
		);

		return () => {
			document.removeEventListener(
				'wcf-store-checkout-import-success',
				importStoreCheckoutSuccessEvent
			);

			document.removeEventListener(
				'wcf-store-checkout-import-text-processing',
				importStoreCheckoutProcessEvent
			);

			document.removeEventListener(
				'wcf-store-checkout-import-error',
				importStoreCheckoutErrorEvent
			);
		};
	}, [ changeButtonText ] );

	const showOptionsSideBar = function ( e ) {
		e.preventDefault();
		let selected_flow_id = '';
		let wrapper_element = '';
		if ( ! ShowSideBar ) {
			wrapper_element = e.target.closest( '.wcf-item' );

			if ( wrapper_element ) {
				selected_flow_id = wrapper_element.hasAttribute( 'data-key' )
					? wrapper_element.getAttribute( 'data-key' )
					: '';

				setSelectedFlow( selected_flow_id );
				showHideFooterImportButton( true );
			}
		} else {
			setSelectedFlow( selectedStoreFlow );
			showHideFooterImportButton( false );
		}
		/**
		 * Don't open the preview panel if the selected funnel is Instant Checkout.
		 * To-Do: Remove this compatibility when a ready-made Instant Checkout funnel is introduced.
		 * Zero index/funnel ID is set for the Instant Checkout only.
		 */
		if ( '0' === selected_flow_id ) {
			return;
		}

		/* Set show popup true/false */
		if ( ShowSideBar ) {
			setShowSideBar( false );
			history.push( {
				pathname: 'index.php',
				search: `?page=cartflow-setup&step=store-checkout`,
			} );
		} else {
			if ( null === wrapper_element || 'undefined' === wrapper_element ) {
				return;
			}

			const flow_title = filteredFlows[ selected_flow_id ].title;

			setPreviewProcessing( true );
			setShowSideBar( true );
			setSelectedFlowTitle( flow_title );
			_get_flow_url( filteredFlows[ selected_flow_id ].steps );
			history.push( {
				pathname: 'index.php',
				search: `?page=cartflow-setup&step=store-checkout&preview=true`,
			} );
		}
	};

	const _get_flow_url = function ( steps ) {
		if ( steps.length > 0 ) {
			steps.forEach( ( step ) => {
				if ( 'checkout' === step.type ) {
					setPreviewUrl(
						step.link +
							'?wcf-remove-cross-origin=true&wcf-load-onboarding-iframe=true'
					);
				}
			} );
		}
	};

	/**
	 * Set the customizer logo if set and remove the loading skeleton on iframe load.
	 */
	const handleIframeOnLoad = function () {
		setPreviewProcessing( false );
		setCustomizerLogo( site_logo );
	};

	/**
	 * Change the pallete color on selection.
	 *
	 * @param {event} event
	 */
	const onPaletteChange = ( event ) => {
		const color_val = event.hex;

		sendPostMessage( {
			action: 'changePrimaryColor',
			data: {
				default_builder: page_builder,
				primary_color: color_val,
				values_to_change: [
					{
						'background-color': color_val,
						'border-color': color_val,
					},
				],
			},
		} );
	};

	/**
	 * Set the customizer logo when the iframe is loading so as to avoid delay.
	 *
	 * @param {logo_data} logo_data
	 */
	const setCustomizerLogo = function ( logo_data ) {
		let preview_action = 'changeHeaderLogo';

		if ( '' === logo_data && '' === cartflows_wizard.site_logo ) {
			preview_action = 'clearHeaderLogo';
		}

		sendPostMessage( {
			action: preview_action,
			data: {
				default_builder: page_builder,
				site_logo: logo_data,
			},
		} );
	};

	return (
		<div className="wcf-col wcf-flow-list-wrapper">
			<div className="wcf-container">
				{ /* Main Step Content */ }
				<div className="wcf-col wcf-col--left">
					<div className="max-w-full text-center mt-4">
						<div className="max-w-full">
							{ processing && <GlobalFlowLibrarySkeleton /> }

							{ showFlowNotFound && <NoFlowsFound /> }

							{ ! processing && filteredFlows.length > 0 && (
								<>
									<GlobalFlowHeader />

									<RadioGroup
										value={ selectedStoreFlow }
										// onChange={ showOptionsSideBar }
										onClick={ showOptionsSideBar }
										className={
											'wcf-store-flow-importer__list wcf-items-list wcf-flow-row relative flex gap-6 flex-wrap justify-center'
										}
									>
										{ filteredFlows.map( ( item ) => (
											<RadioGroup.Option
												key={ item.ID }
												value={ item.ID }
												data-key={ item.ID }
												className={ ( {
													checked,
													active,
												} ) =>
													classNames(
														`wcf-item hover:translate-y-[-1px] rounded transition-all flex-grow-0 flex-shrink-0 basis-[calc(33.33%-7rem)] relative`,
														checked
															? 'border-0'
															: 'border-gray-300',
														active
															? 'border-0'
															: 'border-gray-300'
													)
												}
											>
												{ ( { checked, active } ) => (
													<>
														<GlobalFlowItem
															key={ item.ID }
															item={ item }
															isChecked={
																checked
															}
															isActive={ active }
														/>
														{ /* <div
															className={ classNames(
																active
																	? 'border-2'
																	: 'border-2',
																checked
																	? 'border-orange-500'
																	: 'border-transparent',
																'absolute -inset-px rounded pointer-events-none'
															) }
															aria-hidden="true"
														/> */ }
													</>
												) }
											</RadioGroup.Option>
										) ) }
									</RadioGroup>

									<span
										id={
											'wcf-selected-store-checkout-template'
										}
										data-selected-flow={ selectedStoreFlow }
										data-selected-flow-info={ JSON.stringify(
											filteredFlows[ selectedStoreFlow ]
										) }
									></span>
								</>
							) }
						</div>
					</div>
				</div>
			</div>

			{ /* Sidemenu */ }
			{ ShowSideBar && (
				<div className="wcf-bg--overlay w-full h-full bg-white absolute before:block top-0 right-0 left-0 z-50">
					<div className="wcf-sidebar bg-[#F7F7F9] shadow w-full">
						<div className="wcf-sidebar--header">
							<div className="wcf-template-info flex gap-3 items-center">
								<button
									type="button"
									className="p-3 border border-slate-200 rounded hover:border-slate-500 text-gray-400 hover:text-gray-800 "
									onClick={ showOptionsSideBar }
								>
									<ArrowLeftIcon
										className="w-4 h-4 stroke-2"
										aria-hidden="true"
									/>
								</button>

								<div className="wcf-template-name">
									<p className="text-[#6B7280]">
										{ __(
											'Selected Template:',
											'cartflows'
										) }
									</p>
									<h3 className="font-semibold text-gray-600 text-base">
										{ selectedStoreFlowTitle }
									</h3>
								</div>
							</div>
							<div className="wcf-header-action--buttons flex gap-4">
								<div className="wcf-design--options flex gap-6">
									<UploadSiteLogo
										defaultPageBuilder={ page_builder }
									/>
									<ColorPickerField
										id={ 'primary_color' }
										name={ 'primary_color' }
										label={ __(
											'Change Primary Color',
											'cartflows'
										) }
										value={ '' }
										displayAs={ 'button' }
										handleOnchange={ onPaletteChange }
									/>
								</div>
							</div>
						</div>
					</div>

					<div className="wcf-sidebar-template-preview w-full h-screen relative">
						{ previewProcessing ? (
							<TemplateLoadingSkeleton />
						) : null }

						{ '' !== previewUrl && (
							<iframe
								id="cartflows-templates-preview"
								title="Website Preview"
								height="100%"
								width="100%"
								src={ previewUrl }
								onLoad={ handleIframeOnLoad }
								// eslint-disable-next-line react/no-unknown-property
								allowpaymentrequest="true"
								sandbox="allow-scripts allow-same-origin"
							/>
						) }

						<div
							className={ `wcf-sidebar--footer fixed bottom-0 bg-white p-3.5 w-full border-t border-slate-200 ${
								previewProcessing ? 'wcf-content-blocked' : ''
							}` }
						>
							<div className="wcf-options--row flex justify-between items-center">
								<div className="wcf-footer--info-wrapper">
									<span className="text-sm font-medium text-primary-600 text-center block tracking-[.24em] uppercase">
										{ __( 'Step 4 of 6', 'cartflows' ) }
									</span>
								</div>
								<div className="wcf-footer--action-buttons flex items-center gap-5">
									{ hasError && (
										<p className="wcf-import-error-wrapper flex bg-red-100 p-3 rounded items-center gap-5">
											<h3 className="wcf-import-error--heading font-normal text-red-700 text-sm">
												{ parse( errorMessage ) }
											</h3>
											<span className="wcf-import-error--message text-sm text-slate-800">
												{ parse( callToAction ) }
											</span>
										</p>
									) }

									{ 'pro' ===
										filteredFlows[ selectedStoreFlow ]
											.type && ! wcfCartflowsTypePro() ? (
										<>
											<div className="font-medium text-sm text-red-700">
												{ __(
													'Access all of our pro templates when you upgrade your plan to CartFlows Pro today.',
													'cartflows'
												) }
											</div>
											<a
												className={ `wcf-wizard--button px-5 py-2 text-sm hover:text-white` }
												href="https://cartflows.com/"
												target="_blank"
												rel="noreferrer"
											>
												{ __(
													'Get CartFlows Pro',
													'cartflows'
												) }
											</a>
										</>
									) : (
										<button
											className={ `wcf-wizard--button px-5 py-2 text-sm ${
												action_button.button_class
													? action_button.button_class
													: ''
											}` }
										>
											{ action_button.button_text }
											<ArrowRightIcon
												className="w-5 mt-0.5 ml-1.5 stroke-2"
												aria-hidden="true"
											/>
										</button>
									) }
								</div>
							</div>
						</div>
					</div>
				</div>
			) }
		</div>
	);
}

export default GlobalCheckout;
