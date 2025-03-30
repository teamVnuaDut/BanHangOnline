import React, { useEffect, useState } from 'react';
import { __ } from '@wordpress/i18n';
import { useStateValue } from '../utils/StateProvider';
import apiFetch from '@wordpress/api-fetch';
import confetti from 'canvas-confetti';
import { ArrowLongRightIcon, ArrowPathIcon } from '@heroicons/react/24/outline';

function ReadyStep() {
	const [
		{ showConfetti, selected_page_builder, isStoreCheckoutImported },
		dispatch,
	] = useStateValue();
	const ConfettiFrame = confetti.create(
		document.getElementById( 'wcf-confetti-wrapper' ),
		{ resize: true }
	);

	const [ isFinishingSetup, setIsFinishingSetup ] = useState( false );

	if ( ! showConfetti ) {
		setTimeout( function () {
			ConfettiFrame( {
				particleCount: 250,
				origin: { x: 1, y: 1.4 },
				gravity: 0.4,
				spread: 80,
				ticks: 300,
				angle: 120,
				startVelocity: 100,
				colors: [
					'#0e6ef1',
					'#f5b800',
					'#ff344c',
					'#98e027',
					'#9900f1',
				],
			} );

			dispatch( {
				status: 'SET_SHOW_CONFETTI',
				showConfetti: true,
			} );
		}, 100 );
	}

	useEffect( () => {
		const ajaxData = new window.FormData();
		ajaxData.append( 'action', 'cartflows_onboarding_completed' );
		ajaxData.append(
			'security',
			cartflows_wizard.onboarding_completed_nonce
		);

		apiFetch( {
			url: ajaxurl,
			method: 'POST',
			body: ajaxData,
		} ).then( () => {} );
	}, [] );

	const handleClick = ( e ) => {
		e.preventDefault();

		setIsFinishingSetup( true );
		let redirectUrl = '?page=cartflows';

		if ( isStoreCheckoutImported ) {
			// Redirect to Store Checkout.
			redirectUrl = redirectUrl + '&path=store-checkout';
		} else if ( 'bricks-builder' === selected_page_builder ) {
			// Redirect to Dashboard to create the template from scratch.
			redirectUrl = redirectUrl; // Redirect to the default page i:e dashboard itself.
		} else {
			// Redirec to Import the ready-made templates of start from scratch.
			redirectUrl = redirectUrl + '&path=library';
		}

		// Redirect to the created url.
		window.location.href = cartflows_wizard.admin_url + redirectUrl;
	};

	return (
		<div className="wcf-container">
			<canvas
				id="wcf-confetti-wrapper"
				width={ window.innerWidth }
				height={ window.innerHeight }
			/>
			<div className="wcf-row mt-12">
				<div className="bg-white rounded mx-auto px-11">
					<div className="text-center overflow-hidden">
						<span className="text-sm font-medium text-primary-600 mb-10 text-center block tracking-[.24em] uppercase">
							{ __( 'Step 6 of 6', 'cartflows' ) }
						</span>
						<h1 className="wcf-step-heading mb-4">
							{ __(
								'Congratulations, You Did It!',
								'cartflows'
							) }
						</h1>
						<p className="text-center overflow-hidden mb-10 mx-auto text-lg font-normal text-slate-500">
							{ __(
								'CartFlows is set up on your website! Please watch the short video below for your next steps.',
								'cartflows'
							) }
						</p>
					</div>
					<iframe
						className="mx-auto"
						width="80%"
						height="400"
						src="https://www.youtube.com/embed/nQ8O1jObdlc"
						title={ __(
							'CartFlows Extended Walkthrough Tutorial',
							'cartflows'
						) }
						allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
					></iframe>

					<div className="mt-[50px] flex justify-center">
						<div
							className={ `wcf-wizard--button hover:text-white ${
								isFinishingSetup ? 'cursor-wait opacity-80' : ''
							}` }
							onClick={ handleClick }
						>
							{ ! isFinishingSetup
								? __( 'Finish Store Setup', 'cartflows' )
								: __( 'Finishing the Setup', 'cartflows' ) }

							{ ! isFinishingSetup ? (
								<ArrowLongRightIcon className="w-5 ml-1.5 fill-[#243c5a] stroke-2" />
							) : (
								<ArrowPathIcon className="w-5 ml-1.5 stroke-2 animate-spin" />
							) }
						</div>
					</div>
				</div>
			</div>
		</div>
	);
}

export default ReadyStep;
